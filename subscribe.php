<?php
require __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/rate_limit.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }

rate_limit_or_die('newsletter', 10, 600);

// Honeypot
if (!empty($_POST['website_hp'])) { header('Location: index.php?subscribed=1'); exit; }

$email  = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$name   = substr(trim($_POST['name']  ?? ''), 0, 120);
$source = substr(trim($_POST['source']?? 'footer'), 0, 60);
$back   = (string) ($_POST['back']     ?? 'index.php');
// Only allow same-site redirect targets.
if (!preg_match('~^[a-zA-Z0-9_\-./?=&]+$~', $back) || str_starts_with($back, '/')) $back = 'index.php';

if (!$email) {
    header('Location: ' . $back . '?subscribed=0&err=invalid');
    exit;
}

$ip    = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
if (str_contains($ip, ',')) $ip = trim(explode(',', $ip)[0]);
$ipBin = @inet_pton($ip) ?: str_repeat("\0", 4);

// UPSERT — if email exists already, no-op (don't re-subscribe / overwrite).
$stmt = $con->prepare(
    "INSERT INTO newsletter_subscribers (email, name, source, ip)
     VALUES (?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE status = 'subscribed'"
);
$stmt->bind_param('ssss', $email, $name, $source, $ipBin);
$stmt->execute();

// Best-effort: send a confirmation email + ping HR. Failures are silent.
try {
    require_once __DIR__ . '/includes/Mailer.php';
    $mailer = new Mailer($con, $APP_SECRETS['smtp']);
    $company = $APP_SETTINGS['name'] ?? 'Voldebug';
    $hrEmail = $APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in';

    // To the subscriber
    $mailer->send(
        $email, $name ?: $email,
        "Thanks for subscribing — {$company}",
        '<p>Hi' . ($name ? ' ' . htmlspecialchars($name) : '') . ',</p>'
      . '<p>Thanks for subscribing to <strong>' . htmlspecialchars($company) . '</strong> updates. We send 1–2 emails a month — case studies, cybersecurity advisories, and product launches.</p>'
      . '<p>If this wasn\'t you, just ignore this message and we won\'t reach out again.</p>'
      . '<p>— ' . htmlspecialchars($company) . ' Team</p>',
        [], ['type' => 'newsletter_ack', 'id' => null]
    );

    // To HR (internal notification)
    $mailer->send(
        $hrEmail, 'HR',
        "New newsletter subscriber: {$email}",
        '<p>A new subscriber just signed up via the website:</p>'
      . '<ul><li><strong>Email:</strong> ' . htmlspecialchars($email) . '</li>'
      . '<li><strong>Name:</strong> ' . htmlspecialchars($name ?: '—') . '</li>'
      . '<li><strong>Source:</strong> ' . htmlspecialchars($source) . '</li>'
      . '<li><strong>From page:</strong> ' . htmlspecialchars($back) . '</li></ul>',
        [], ['type' => 'newsletter_internal', 'id' => null]
    );
} catch (Throwable $e) { /* don't block on mail failure */ }

header('Location: ' . $back . '?subscribed=1');
