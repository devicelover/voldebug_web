<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/CertificateGenerator.php';
require_once __DIR__ . '/../includes/Mailer.php';

$template_id = (int) ($_POST['template_id'] ?? 0);
$partner_id  = (int) ($_POST['partner_id']  ?? 0);
$back        = 'hr_certificate_generate.php';

if (!$template_id) { header('Location: ' . $back . '?msg=' . urlencode('Pick a template.')); exit; }

$tStmt = $con->prepare("SELECT * FROM certificate_templates WHERE id = ?");
$tStmt->bind_param('i', $template_id); $tStmt->execute();
$template = $tStmt->get_result()->fetch_assoc();
if (!$template) { header('Location: ' . $back . '?msg=' . urlencode('Template not found.')); exit; }

$partner = null;
if ($partner_id > 0) {
    $pStmt = $con->prepare("SELECT * FROM certificate_partners WHERE id = ?");
    $pStmt->bind_param('i', $partner_id); $pStmt->execute();
    $partner = $pStmt->get_result()->fetch_assoc() ?: null;
}

$settings = $con->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc() ?: [];
if (empty($settings['signatory_name'])) {
    header('Location: ' . $back . '?msg=' . urlencode('Set up letterhead signatory first (HR → Letterhead).'));
    exit;
}

$input = [
    'recipient_name'  => trim($_POST['recipient_name'] ?? ''),
    'recipient_email' => trim($_POST['recipient_email'] ?? ''),
    'course_name'     => trim($_POST['course_name']    ?? ''),
    'completion_date' => $_POST['completion_date']     ?? date('Y-m-d'),
    'duration'        => trim($_POST['duration']       ?? ''),
    'custom1'         => trim($_POST['custom1']        ?? ''),
    'custom2'         => trim($_POST['custom2']        ?? ''),
    'custom3'         => trim($_POST['custom3']        ?? ''),
];
if ($input['recipient_name'] === '' || $input['course_name'] === '') {
    header('Location: ' . $back . '?msg=' . urlencode('Recipient name and course name are required.'));
    exit;
}

// Optional guest signatory section: name + designation + organization + (optional) image.
$guestName = trim($_POST['guest_name'] ?? '');
if ($guestName !== '') {
    // Save guest signature image if provided.
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
    $input['guest_signatory'] = [
        'name'            => $guestName,
        'designation'     => trim($_POST['guest_designation']  ?? ''),
        'organization'    => trim($_POST['guest_organization'] ?? ''),
        'signature_image' => $guestImg,
    ];
}

$gen = new CertificateGenerator($con, $settings, $APP_SECRETS['public_base_url'], VOLDEBUG_ROOT . '/Admin/certificates');
$opts = [
    'include_signature' => isset($_POST['include_signature']) && $_POST['include_signature'] === '1',
    'include_stamp'     => isset($_POST['include_stamp'])     && $_POST['include_stamp']     === '1',
];
$result = $gen->generate($input, $template, $partner, $opts);

$msg = 'Certificate created — Ref VDB-' . substr($result['token'], 0, 10);

// Optionally email it now.
if (!empty($_POST['send_email']) && $input['recipient_email'] !== '') {
    try {
        $mailer = new Mailer($con, $APP_SECRETS['smtp']);
        $sendRes = $mailer->send(
            $input['recipient_email'], $input['recipient_name'],
            $result['email_subject'], nl2br(htmlspecialchars($result['email_body'])),
            [['path' => $result['pdf_path'], 'name' => 'certificate.pdf']],
            ['type' => 'certificate', 'id' => $result['id']]
        );
        if ($sendRes['ok']) {
            $con->query("UPDATE certificates_issued SET emailed_at = NOW() WHERE id = " . (int)$result['id']);
            $msg .= ' · emailed to ' . $input['recipient_email'];
        } else {
            $msg .= ' · email FAILED: ' . $sendRes['error'];
        }
    } catch (Throwable $e) { $msg .= ' · email failed: ' . $e->getMessage(); }
}

header('Location: hr_certificates.php?msg=' . urlencode($msg));
