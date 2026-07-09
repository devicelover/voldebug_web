<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';

$id = (int) ($_POST['id'] ?? 0);
if (!$id) { header('Location: hr_certificates.php'); exit; }

$stmt = $con->prepare(
    "SELECT c.*, t.email_subject, t.email_body, p.name AS partner_name
     FROM certificates_issued c
     JOIN certificate_templates t ON t.id = c.template_id
     LEFT JOIN certificate_partners p ON p.id = c.partner_id
     WHERE c.id = ?"
);
$stmt->bind_param('i', $id); $stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) { header('Location: hr_certificates.php'); exit; }

if ((int)$row['revoked'] === 1) {
    header('Location: hr_certificates.php?msg=' . urlencode('Cannot email a revoked certificate.'));
    exit;
}
if (empty($row['recipient_email']) || !filter_var($row['recipient_email'], FILTER_VALIDATE_EMAIL)) {
    header('Location: hr_certificates.php?msg=' . urlencode('No valid email on this certificate row.'));
    exit;
}
$abs = VOLDEBUG_ROOT . '/' . ltrim($row['pdf_path'], '/');
if (!is_file($abs)) {
    header('Location: hr_certificates.php?msg=' . urlencode('Certificate PDF missing on disk.'));
    exit;
}

$companyLegal = $APP_SETTINGS['company_legal_name'] ?: ($APP_SETTINGS['name'] ?? 'Voldebug');
$verifyUrl = rtrim($APP_SECRETS['public_base_url'], '/') . '/verify.php?t=' . urlencode($row['verify_token']);

$vars = [
    'name'           => $row['recipient_name'],
    'honorific_name' => $row['recipient_name'],
    'course'         => $row['course_name'],
    'date'           => $row['completion_date'] ? date('jS F Y', strtotime($row['completion_date'])) : '',
    'duration'       => $row['duration'],
    'partner_name'   => $row['partner_name'] ?? '',
    'company'        => $companyLegal,
    'signatory'      => $APP_SETTINGS['signatory_name']        ?? '',
    'signatory_role' => $APP_SETTINGS['signatory_designation'] ?? '',
    'verify_url'     => $verifyUrl,
];
$subject = render_placeholders($row['email_subject'], $vars);
$body    = render_placeholders($row['email_body'],    $vars);
$html    = nl2br(htmlspecialchars($body));

$mailer = new Mailer($con, $APP_SECRETS['smtp']);
$res = $mailer->send(
    $row['recipient_email'], $row['recipient_name'],
    $subject, $html,
    [['path' => $abs, 'name' => 'certificate.pdf']],
    ['type' => 'certificate', 'id' => $id]
);

if ($res['ok']) {
    $upd = $con->prepare("UPDATE certificates_issued SET emailed_at = NOW() WHERE id = ?");
    $upd->bind_param('i', $id);
    $upd->execute();
    $msg = "Certificate emailed to {$row['recipient_email']}.";
} else {
    $msg = "Email failed: {$res['error']}";
}
header('Location: hr_certificates.php?msg=' . urlencode($msg));
