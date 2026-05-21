<?php
require __DIR__ . '/includes/bootstrap.php';

$email = trim($_GET['e'] ?? $_POST['e'] ?? '');
$tok   = trim($_GET['t'] ?? $_POST['t'] ?? '');
$ok    = false;

// Token is HMAC of email so we can verify without storing per-recipient secrets.
$expected = hash_hmac('sha256', strtolower($email), $APP_SECRETS['smtp']['password'] ?? 'voldebug-salt');

if ($email !== '' && hash_equals($expected, $tok)) {
    $stmt = $con->prepare("INSERT IGNORE INTO email_unsubscribes (email, reason) VALUES (?, 'user-opt-out')");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $ok = true;
}

$company = htmlspecialchars($APP_SETTINGS['name'] ?? 'Voldebug');
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Unsubscribe — <?= $company ?></title>
<style>
    body{background:#f5f7fb;font-family:system-ui,sans-serif;color:#222}
    .wrap{max-width:520px;margin:80px auto;padding:0 16px}
    .card{background:#fff;border-radius:12px;box-shadow:0 6px 30px rgba(0,0,0,.06);padding:32px;text-align:center}
    h1{font-size:22px;margin:0 0 12px 0}
    .ok{color:#146c2e}.bad{color:#a11a25}
</style></head><body><div class="wrap"><div class="card">
    <?php if ($ok): ?>
        <h1 class="ok">You've been unsubscribed</h1>
        <p>We won't send any more marketing emails to <strong><?= htmlspecialchars($email) ?></strong>.</p>
        <p style="color:#777">Transactional emails (e.g. application confirmations you've requested) are not affected.</p>
    <?php else: ?>
        <h1 class="bad">Couldn't process unsubscribe</h1>
        <p>The link appears invalid. Please email <a href="mailto:<?= htmlspecialchars($APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in') ?>"><?= htmlspecialchars($APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in') ?></a> and we'll remove you manually.</p>
    <?php endif; ?>
</div></div></body></html>
