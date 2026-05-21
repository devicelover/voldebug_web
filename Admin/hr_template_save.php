<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

if (isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];
    $stmt = $con->prepare("DELETE FROM letter_templates WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: hr_templates.php?msg=' . urlencode('Template deleted.'));
    exit;
}

$id            = (int) ($_POST['id'] ?? 0);
$template_name = trim($_POST['template_name'] ?? '');
$letter_type   = trim($_POST['letter_type']   ?? 'custom');
$role_tag      = trim($_POST['role_tag']      ?? 'general');
$email_subject = trim($_POST['email_subject'] ?? '');
$email_body    = (string) ($_POST['email_body']  ?? '');
$letter_body   = (string) ($_POST['letter_body'] ?? '');
$attach_pdf    = isset($_POST['attach_pdf']) ? 1 : 0;
$is_active     = isset($_POST['is_active'])  ? 1 : 0;

if ($id > 0) {
    $stmt = $con->prepare(
        "UPDATE letter_templates SET template_name=?, letter_type=?, role_tag=?, email_subject=?, email_body=?, letter_body=?, attach_pdf=?, is_active=? WHERE id=?"
    );
    $stmt->bind_param('ssssssiii', $template_name, $letter_type, $role_tag, $email_subject, $email_body, $letter_body, $attach_pdf, $is_active, $id);
    $stmt->execute();
    header('Location: hr_templates.php?msg=' . urlencode('Template updated.'));
} else {
    $stmt = $con->prepare(
        "INSERT INTO letter_templates (template_name, letter_type, role_tag, email_subject, email_body, letter_body, attach_pdf, is_active)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('ssssssii', $template_name, $letter_type, $role_tag, $email_subject, $email_body, $letter_body, $attach_pdf, $is_active);
    $stmt->execute();
    header('Location: hr_templates.php?msg=' . urlencode('Template created.'));
}
