<?php
// Contact / quote inbound handler. Routes via PHPMailer + logs to email_log.
require __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/Mailer.php';
require_once __DIR__ . '/includes/rate_limit.php';
rate_limit_or_die('contact', 5, 300);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(403);
    header("Location: get-a-quote.php");
    exit;
}

$name    = str_replace(["\r", "\n"], [" ", " "], strip_tags(trim($_POST["name"] ?? '')));
$email   = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
$phone   = trim($_POST["phone"]   ?? '');
$message = trim($_POST["message"] ?? '');

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
    http_response_code(400);
    echo "Please complete the form and try again.";
    exit;
}

$to_email = $APP_SETTINGS['email'] ?? 'hr@voldebug.in';
$company  = $APP_SETTINGS['name']  ?? 'Voldebug';
$subject  = "Quote/Contact request from {$name}";
$html     = '<h3>New inbound message</h3>'
          . '<p><strong>Name:</strong> ' . htmlspecialchars($name)    . '</p>'
          . '<p><strong>Email:</strong> ' . htmlspecialchars($email)  . '</p>'
          . '<p><strong>Phone:</strong> ' . htmlspecialchars($phone)  . '</p>'
          . '<p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($message)) . '</p>';

$mailer = new Mailer($con, $APP_SECRETS['smtp']);
$res = $mailer->send($to_email, $company . ' Team', $subject, $html, [], ['type' => 'contact_form', 'id' => null]);

if ($res['ok']) {
    header("Location: get-a-quote.php?sent=1");
    exit;
}

http_response_code(500);
echo "Something went wrong. Please try again or write to " . htmlspecialchars($to_email) . ".";
