<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/CertificateGenerator.php';
require_once __DIR__ . '/../includes/Mailer.php';

set_time_limit(600); // bulk generation can take a while

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
    $guestImg = '';
    if (!empty($_FILES['guest_signature_image']['name'])) {
        $allow = ['jpg','jpeg','png','gif','webp','svg'];
        $ext = strtolower(pathinfo($_FILES['guest_signature_image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allow, true)) {
            $dir = __DIR__ . '/images/cert_guests/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $guestImg = 'guest_' . time() . '_' . bin2hex(random_bytes(2)) . '.' . $ext;
            if (!move_uploaded_file($_FILES['guest_signature_image']['tmp_name'], $dir . $guestImg)) {
                $guestImg = '';
            }
        }
    }
    $guestForBatch = [
        'name'            => $guestName,
        'designation'     => trim($_POST['guest_designation']  ?? ''),
        'organization'    => trim($_POST['guest_organization'] ?? ''),
        'signature_image' => $guestImg,
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

foreach ($rows as $r) {
    $name  = $r['name']  ?? '';
    if ($name === '') { $failed++; continue; }
    $email = filter_var($r['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '';
    $course = $r['course_name'] ?: $courseDflt;
    $date  = $r['completion_date'] ?: date('Y-m-d');

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
                $con->query("UPDATE certificates_issued SET emailed_at = NOW() WHERE id = " . (int)$res['id']);
            }
            usleep(500000); // 0.5s throttle to be polite to SMTP
        }
    } catch (Throwable $e) {
        $failed++;
    }
}

$status = 'completed';
$upd = $con->prepare("UPDATE certificate_batches SET success_count = ?, failed_count = ?, status = ?, completed_at = NOW() WHERE id = ?");
$upd->bind_param('iisi', $ok, $failed, $status, $batchId);
$upd->execute();

$msg = "Batch #{$batchId} done — generated {$ok} of {$count} certificates";
if ($failed) $msg .= ", {$failed} failed";
if ($emailed) $msg .= "; emailed {$emailed}";
$msg .= '.';
header('Location: hr_certificates.php?batch=' . $batchId . '&msg=' . urlencode($msg));
