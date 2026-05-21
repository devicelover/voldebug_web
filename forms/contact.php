<?php
// Contact form endpoint — routes through PHPMailer.
require __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';
require_once __DIR__ . '/../includes/rate_limit.php';
require_once __DIR__ . '/../includes/captcha.php';
rate_limit_or_die('contact', 5, 300);
// Honeypot
if (!empty($_POST['website_hp'])) { header("Location: ../index.php?success=1"); exit; }
captcha_require();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

$name    = htmlspecialchars(trim($_POST["name"]    ?? ''));
$email   = filter_var(trim($_POST["email"] ?? ''), FILTER_VALIDATE_EMAIL) ?: null;
$subject = htmlspecialchars(trim($_POST["subject"] ?? ''));
$message = htmlspecialchars(trim($_POST["message"] ?? ''));

if (!$name || !$email || !$subject || !$message) {
    $err = '';
    if (!$name)    $err .= "&missing_name=1";
    if (!$email)   $err .= "&missing_email=1";
    if (!$subject) $err .= "&missing_subject=1";
    if (!$message) $err .= "&missing_message=1";
    header("Location: ../index.php?error=1" . $err);
    exit;
}

$to = $APP_SETTINGS['email'] ?? 'hr@voldebug.in';
$html = '<h3>New message from website</h3>'
      . '<p><strong>From:</strong> ' . $name . ' &lt;' . $email . '&gt;</p>'
      . '<p><strong>Subject:</strong> ' . $subject . '</p>'
      . '<p><strong>Message:</strong><br>' . nl2br($message) . '</p>';

$mailer = new Mailer($con, $APP_SECRETS['smtp']);
$res = $mailer->send($to, 'Voldebug Team', "Contact: {$subject}", $html, [], ['type' => 'contact_form', 'id' => null]);

header("Location: ../index.php?success=" . ($res['ok'] ? 1 : 0));
exit;
