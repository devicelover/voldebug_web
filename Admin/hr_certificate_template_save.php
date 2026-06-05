<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

if (isset($_POST['delete_id'])) {
    $did = (int) $_POST['delete_id'];
    $stmt = $con->prepare("DELETE FROM certificate_templates WHERE id = ?");
    $stmt->bind_param('i', $did); $stmt->execute();
    header('Location: hr_certificate_templates.php?msg=' . urlencode('Template deleted.'));
    exit;
}

$id            = (int) ($_POST['id'] ?? 0);
$template_name = trim($_POST['template_name'] ?? '');
$title         = trim($_POST['title']         ?? 'Certificate of Completion');
$cert_kind     = trim($_POST['cert_kind']     ?? 'completion');
$body_html     = (string) ($_POST['body_html'] ?? '');
$email_subject = trim($_POST['email_subject'] ?? '');
$email_body    = (string) ($_POST['email_body'] ?? '');
$orientation   = ($_POST['orientation'] ?? 'landscape') === 'portrait' ? 'portrait' : 'landscape';
$qr_enabled    = isset($_POST['qr_enabled']) ? 1 : 0;
$is_active     = isset($_POST['is_active'])  ? 1 : 0;

if ($id > 0) {
    $stmt = $con->prepare("UPDATE certificate_templates SET template_name=?, title=?, cert_kind=?, body_html=?, email_subject=?, email_body=?, orientation=?, qr_enabled=?, is_active=? WHERE id=?");
    $stmt->bind_param('sssssssiii', $template_name, $title, $cert_kind, $body_html, $email_subject, $email_body, $orientation, $qr_enabled, $is_active, $id);
    $stmt->execute();
    header('Location: hr_certificate_templates.php?msg=' . urlencode('Template updated.'));
} else {
    $stmt = $con->prepare("INSERT INTO certificate_templates (template_name, title, cert_kind, body_html, email_subject, email_body, orientation, qr_enabled, is_active) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssssii', $template_name, $title, $cert_kind, $body_html, $email_subject, $email_body, $orientation, $qr_enabled, $is_active);
    $stmt->execute();
    header('Location: hr_certificate_templates.php?msg=' . urlencode('Template created.'));
}
