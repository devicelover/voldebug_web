<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/CertificateGenerator.php';
require_once __DIR__ . '/../includes/Mailer.php';
require_once __DIR__ . '/../includes/upload_safe.php';

set_time_limit(600); // bulk generation can take a while

/** Normalise a user-typed date (Excel, dd/mm/yyyy, dd-mm-yyyy, yyyy-mm-dd) to Y-m-d, or return '' on failure. */
function bulk_normalize_date(string $raw): string {
    $raw = trim($raw);
    if ($raw === '') return '';
    // Excel numeric dates: days since 1900-01-01 (with the Lotus/Excel epoch quirk).
    if (ctype_digit($raw) && (int)$raw > 25569 && (int)$raw < 60000) {
        $unix = ((int)$raw - 25569) * 86400;
        return gmdate('Y-m-d', $unix);
    }
    $fmts = ['Y-m-d','Y/m/d','d-m-Y','d/m/Y','d.m.Y','d M Y','d F Y','j M Y','j F Y','m/d/Y','m-d-Y'];
    foreach ($fmts as $f) {
        $d = DateTime::createFromFormat($f, $raw);
        $errs = DateTime::getLastErrors();
        if ($d && (!$errs || ($errs['warning_count'] === 0 && $errs['error_count'] === 0))) {
            return $d->format('Y-m-d');
        }
    }
    return '';
}

$batchName   = trim($_POST['batch_name'] ?? '');
$courseDflt  = trim($_POST['course_name'] ?? '');
$template_id = (int) ($_POST['template_id'] ?? 0);
$partner_id  = (int) ($_POST['partner_id']  ?? 0);
$includeSig  = isset($_POST['include_signature']) && $_POST['include_signature'] === '1';
$includeStmp = isset($_POST['include_stamp'])     && $_POST['include_stamp']     === '1';
$sendEmail   = isset($_POST['send_email'])        && $_POST['send_email']        === '1';

// Optional batch-wide guest signatory — uploaded once, applied to every cert in the batch.
$guestForBatch = null;
$guestName = trim($_POST['guest_name'] ?? '');
if ($guestName !== '') {
    $guestDir = __DIR__ . '/images/cert_guests/';
    $guestRes = safe_image_upload('guest_signature_image', $guestDir, '');
    if (!empty($guestRes['error'])) {
        header('Location: hr_certificate_bulk.php?msg=' . urlencode('Guest signature rejected: ' . $guestRes['error']));
        exit;
    }
    $guestForBatch = [
        'name'            => $guestName,
        'designation'     => trim($_POST['guest_designation']  ?? ''),
        'organization'    => trim($_POST['guest_organization'] ?? ''),
        'signature_image' => $guestRes['name'] ?? '',
    ];
}

if ($batchName === '' || $template_id === 0 || empty($_FILES['sheet']['name'])) {
    header('Location: hr_certificate_bulk.php?msg=' . urlencode('Batch name, template, and file required.'));
    exit;
}

// Load template + partner + settings
$tStmt = $con->prepare("SELECT * FROM certificate_templates WHERE id = ?");
$tStmt->bind_param('i', $template_id); $tStmt->execute();
$template = $tStmt->get_result()->fetch_assoc();
if (!$template) { header('Location: hr_certificate_bulk.php?msg=' . urlencode('Template not found.')); exit; }

$partner = null;
if ($partner_id > 0) {
    $pStmt = $con->prepare("SELECT * FROM certificate_partners WHERE id = ?");
    $pStmt->bind_param('i', $partner_id); $pStmt->execute();
    $partner = $pStmt->get_result()->fetch_assoc() ?: null;
}

$settings = $con->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc() ?: [];
if (empty($settings['signatory_name'])) {
    header('Location: hr_certificate_bulk.php?msg=' . urlencode('Set up letterhead signatory first.'));
    exit;
}

// === Parse rows from CSV or XLSX ===
$ext = strtolower(pathinfo($_FILES['sheet']['name'], PATHINFO_EXTENSION));
$tmp = $_FILES['sheet']['tmp_name'];
$rows = [];

if ($ext === 'csv') {
    $fh = fopen($tmp, 'r');
    if (!$fh) { header('Location: hr_certificate_bulk.php?msg=' . urlencode('Could not read CSV.')); exit; }
    $headers = array_map('strtolower', array_map('trim', fgetcsv($fh) ?: []));
    while (($r = fgetcsv($fh)) !== false) {
        if (count(array_filter($r, fn($v) => trim((string)$v) !== '')) === 0) continue;
        $row = [];
        foreach ($headers as $i => $h) $row[$h] = isset($r[$i]) ? trim((string)$r[$i]) : '';
        $rows[] = $row;
    }
    fclose($fh);
} elseif ($ext === 'xlsx' || $ext === 'xls') {
    require_once VOLDEBUG_ROOT . '/vendor/autoload.php';
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmp);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, false);
        if (!$data) throw new Exception('Empty sheet');
        $headers = array_map(fn($v) => strtolower(trim((string)$v)), $data[0]);
        for ($i = 1; $i < count($data); $i++) {
            $r = $data[$i];
            if (!is_array($r)) continue;
            if (count(array_filter($r, fn($v) => trim((string)$v) !== '')) === 0) continue;
            $row = [];
            foreach ($headers as $j => $h) $row[$h] = isset($r[$j]) ? trim((string)$r[$j]) : '';
            $rows[] = $row;
        }
    } catch (Throwable $e) {
        header('Location: hr_certificate_bulk.php?msg=' . urlencode('Could not parse XLSX: ' . $e->getMessage()));
        exit;
    }
} else {
    header('Location: hr_certificate_bulk.php?msg=' . urlencode('Upload .csv, .xlsx or .xls.'));
    exit;
}

if (!$rows) { header('Location: hr_certificate_bulk.php?msg=' . urlencode('No data rows found in the sheet.')); exit; }

// === Pre-flight header check — surface missing-column errors up front instead of silently failing every row.
$headerKeys = array_keys($rows[0]);
if (!in_array('name', $headerKeys, true)) {
    header('Location: hr_certificate_bulk.php?msg=' . urlencode('Sheet is missing the required "name" column.'));
    exit;
}
if (!in_array('course_name', $headerKeys, true) && $courseDflt === '') {
    header('Location: hr_certificate_bulk.php?msg=' . urlencode('Provide a Course/Program name in the form, or a "course_name" column in the sheet.'));
    exit;
}

// === Dry-run branch: parse + validate every row, but render a preview page instead of generating.
$isDryRun = isset($_POST['dry_run']) && $_POST['dry_run'] === '1';
if ($isDryRun) {
    $dupCheck  = $con->prepare(
        "SELECT id FROM certificates_issued
         WHERE template_id = ? AND recipient_name = ? AND course_name = ? AND completion_date = ?
           AND revoked = 0 LIMIT 1"
    );
    $preview = ['valid' => [], 'skipped' => []];
    foreach ($rows as $rowIdx => $r) {
        $rowNum = $rowIdx + 2;
        $name   = trim((string) ($r['name'] ?? ''));
        if ($name === '') { $preview['skipped'][] = ['row' => $rowNum, 'error' => 'blank name']; continue; }
        $email  = filter_var($r['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '';
        $course = trim((string) ($r['course_name'] ?? '')) ?: $courseDflt;
        if ($course === '') { $preview['skipped'][] = ['row' => $rowNum, 'name' => $name, 'error' => 'no course_name']; continue; }
        $rawDate = trim((string) ($r['completion_date'] ?? ''));
        if ($rawDate === '') { $date = date('Y-m-d'); }
        else {
            $date = bulk_normalize_date($rawDate);
            if ($date === '') { $preview['skipped'][] = ['row' => $rowNum, 'name' => $name, 'error' => 'unparseable date: ' . $rawDate]; continue; }
        }
        $dupCheck->bind_param('isss', $template_id, $name, $course, $date);
        $dupCheck->execute();
        if ($dupCheck->get_result()->fetch_assoc()) {
            $preview['skipped'][] = ['row' => $rowNum, 'name' => $name, 'error' => 'duplicate — active cert already exists'];
            continue;
        }
        $preview['valid'][] = ['row' => $rowNum, 'name' => $name, 'email' => $email, 'course' => $course, 'date' => $date];
    }
    include __DIR__ . '/hr_certificate_bulk_preview_view.php';
    exit;
}

// === Create batch record ===
$createdBy = $_SESSION['username'] ?? 'admin';
$bIns = $con->prepare("INSERT INTO certificate_batches (name, template_id, partner_id, course_name, recipient_count, status, created_by) VALUES (?, ?, NULLIF(?,0), ?, ?, 'processing', ?)");
$count = count($rows);
$pidI = $partner_id ?: 0;
$bIns->bind_param('siisis', $batchName, $template_id, $pidI, $courseDflt, $count, $createdBy);
$bIns->execute();
$batchId = (int) $bIns->insert_id;

// === Generate ===
$gen    = new CertificateGenerator($con, $settings, $APP_SECRETS['public_base_url'], VOLDEBUG_ROOT . '/Admin/certificates');
$mailer = $sendEmail ? new Mailer($con, $APP_SECRETS['smtp']) : null;
$ok = 0; $failed = 0; $emailed = 0;
$rowErrors = [];  // per-row error log, persisted to certificate_batches.failed_details
$emailUpd  = $con->prepare("UPDATE certificates_issued SET emailed_at = NOW() WHERE id = ?");
$dupCheck  = $con->prepare(
    "SELECT id FROM certificates_issued
     WHERE template_id = ? AND recipient_name = ? AND course_name = ? AND completion_date = ?
       AND revoked = 0 AND (batch_id IS NULL OR batch_id <> ?) LIMIT 1"
);

// Outer try/catch: ANY uncaught throwable (dompdf crash, disk-full, OOM near the tail)
// marks the batch as 'failed' rather than leaving it stuck in 'processing' forever.
try {
    foreach ($rows as $rowIdx => $r) {
        $rowNum = $rowIdx + 2;  // sheet row number (1-indexed + header row)
        $name  = trim((string) ($r['name'] ?? ''));
        if ($name === '') {
            $failed++;
            $rowErrors[] = ['row' => $rowNum, 'error' => 'blank name'];
            continue;
        }
        $email = filter_var($r['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '';
        $course = trim((string) ($r['course_name'] ?? '')) ?: $courseDflt;
        if ($course === '') {
            $failed++;
            $rowErrors[] = ['row' => $rowNum, 'name' => $name, 'error' => 'no course_name (row + form both blank)'];
            continue;
        }

        // Date normalisation. Blank → today. Invalid → row rejected (better than "1st January 1970" on a real cert).
        $rawDate = trim((string) ($r['completion_date'] ?? ''));
        if ($rawDate === '') {
            $date = date('Y-m-d');
        } else {
            $date = bulk_normalize_date($rawDate);
            if ($date === '') {
                $failed++;
                $rowErrors[] = ['row' => $rowNum, 'name' => $name, 'error' => 'unparseable completion_date: ' . $rawDate];
                continue;
            }
        }

        // Dedup: skip if an identical active cert already exists from a different batch.
        $dupCheck->bind_param('isssi', $template_id, $name, $course, $date, $batchId);
        $dupCheck->execute();
        if ($dupCheck->get_result()->fetch_assoc()) {
            $failed++;
            $rowErrors[] = ['row' => $rowNum, 'name' => $name, 'error' => 'duplicate — active cert exists for this name/course/date'];
            continue;
        }

        try {
            $rowInput = [
                'recipient_name'  => $name,
                'recipient_email' => $email,
                'course_name'     => $course,
                'completion_date' => $date,
                'duration'        => $r['duration'] ?? '',
                'custom1'         => $r['custom1']  ?? '',
                'custom2'         => $r['custom2']  ?? '',
                'custom3'         => $r['custom3']  ?? '',
                'custom4'         => $r['custom4']  ?? '',
                'custom5'         => $r['custom5']  ?? '',
            ];
            if ($guestForBatch) $rowInput['guest_signatory'] = $guestForBatch;
            $res = $gen->generate($rowInput, $template, $partner, [
                'include_signature' => $includeSig,
                'include_stamp'     => $includeStmp,
                'batch_id'          => $batchId,
            ]);
            $ok++;

            if ($sendEmail && $email && $mailer) {
                $sendRes = $mailer->send(
                    $email, $name,
                    $res['email_subject'], nl2br(htmlspecialchars($res['email_body'])),
                    [['path' => $res['pdf_path'], 'name' => 'certificate.pdf']],
                    ['type' => 'certificate', 'id' => $res['id']]
                );
                if ($sendRes['ok']) {
                    $emailed++;
                    $rid = (int)$res['id'];
                    $emailUpd->bind_param('i', $rid);
                    $emailUpd->execute();
                }
                usleep(500000); // 0.5s throttle to be polite to SMTP
            }
        } catch (Throwable $e) {
            $failed++;
            $rowErrors[] = ['row' => $rowNum, 'name' => $name, 'error' => $e->getMessage()];
        }
    }
    $status = 'completed';
} catch (Throwable $outer) {
    $status = 'failed';
    $rowErrors[] = ['row' => 0, 'error' => 'batch aborted: ' . $outer->getMessage()];
}

$failedJson = $rowErrors ? json_encode($rowErrors, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null;
$upd = $con->prepare("UPDATE certificate_batches SET success_count = ?, failed_count = ?, failed_details = ?, status = ?, completed_at = NOW() WHERE id = ?");
$upd->bind_param('iissi', $ok, $failed, $failedJson, $status, $batchId);
$upd->execute();

$msg = "Batch #{$batchId} " . ($status === 'failed' ? 'ABORTED' : 'done') . " — generated {$ok} of {$count} certificates";
if ($failed) $msg .= ", {$failed} rejected/failed (see batch details)";
if ($emailed) $msg .= "; emailed {$emailed}";
$msg .= '.';
header('Location: hr_certificates.php?batch=' . $batchId . '&msg=' . urlencode($msg));
