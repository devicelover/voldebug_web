<?php
require __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/rate_limit.php';

$token = trim($_GET['t'] ?? $_POST['t'] ?? '');
$letter = null; $notFound = false;

if ($token !== '' && preg_match('/^[A-Za-z0-9_\-]{10,96}$/', $token)) {
    $stmt = $con->prepare(
        "SELECT l.*, i.id AS intern_row_id, i.start_date, i.end_date
         FROM letters_issued l
         LEFT JOIN interns i ON i.id = l.intern_id
         WHERE l.verify_token = ? AND l.letter_type IN ('offer', 'joining') LIMIT 1"
    );
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $letter = $stmt->get_result()->fetch_assoc();
    if (!$letter) $notFound = true;
} else {
    $notFound = true;
}

$alreadyResponded = null;
if ($letter) {
    $r = $con->prepare("SELECT response, created_at FROM letter_responses WHERE letter_id = ?");
    $lid = (int) $letter['id'];
    $r->bind_param('i', $lid);
    $r->execute();
    $alreadyResponded = $r->get_result()->fetch_assoc();
}

$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $letter && !$alreadyResponded) {
    rate_limit_or_die('offer_response', 5, 300);

    $response = $_POST['response'] ?? '';
    if (!in_array($response, ['accepted', 'declined'], true)) {
        http_response_code(400); die('Invalid response.');
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $ipBin = @inet_pton($ip) ?: '';
    $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
    $notes = substr(trim($_POST['notes'] ?? ''), 0, 5000);
    $lid = (int) $letter['id'];

    $ins = $con->prepare(
        "INSERT INTO letter_responses (letter_id, response, responder_ip, user_agent, notes)
         VALUES (?, ?, ?, ?, ?)"
    );
    $ins->bind_param('issss', $lid, $response, $ipBin, $ua, $notes);
    $ins->execute();

    // Flip intern status if accepted + offer letter; mark applicant as hired.
    if ($response === 'accepted' && $letter['intern_row_id']) {
        $con->query("UPDATE interns SET status = 'active' WHERE id = " . (int) $letter['intern_row_id']);
    }

    $saved = true;
    $alreadyResponded = ['response' => $response, 'created_at' => date('Y-m-d H:i:s')];
}

$company = htmlspecialchars($APP_SETTINGS['name'] ?? 'Voldebug');
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Offer acceptance — <?= $company ?></title>
<link rel="icon" type="image/png" href="assets/img/logo/favicon.ico">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<style>
    body { background:#f5f7fb; font-family:system-ui,Segoe UI,Roboto,sans-serif; color:#222; }
    .wrap { max-width:640px; margin:60px auto; padding:0 16px; }
    .card { background:#fff; border-radius:12px; box-shadow:0 6px 30px rgba(0,0,0,.06); padding:28px; }
    h1 { font-size:22px; margin:0 0 8px 0; }
    .accept { background:#0d6efd; color:#fff; padding:10px 22px; border:none; border-radius:8px; font-weight:600; cursor:pointer; }
    .decline { background:#fff; color:#a11a25; padding:10px 22px; border:1px solid #a11a25; border-radius:8px; font-weight:600; cursor:pointer; margin-left:10px; }
    .kv { display:grid; grid-template-columns:140px 1fr; gap:8px 14px; margin:14px 0 20px 0; }
    .kv dt { color:#777; } .kv dd { margin:0; font-weight:600; }
    textarea { width:100%; min-height:80px; margin-top:8px; padding:8px; border:1px solid #ddd; border-radius:6px; }
</style>
</head><body>
<div class="wrap"><div class="card">
    <?php if ($notFound): ?>
        <h1>Link not valid</h1>
        <p>This offer link doesn't match anything we've issued. If you were expecting an offer from <?= $company ?>, please reply directly to the email you received.</p>

    <?php elseif ($alreadyResponded): ?>
        <h1>Response recorded</h1>
        <p>We've already recorded your response: <strong><?= htmlspecialchars(ucwords($alreadyResponded['response'])) ?></strong>
           on <?= htmlspecialchars($alreadyResponded['created_at']) ?>.</p>
        <p style="color:#777">If this was a mistake, please write to <a href="mailto:<?= htmlspecialchars($APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in') ?>"><?= htmlspecialchars($APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in') ?></a>.</p>

    <?php else: ?>
        <h1>Your offer from <?= $company ?></h1>
        <p>Please review the details and confirm.</p>
        <dl class="kv">
            <dt>Name</dt>  <dd><?= htmlspecialchars($letter['recipient_name']) ?></dd>
            <dt>Role</dt>  <dd><?= htmlspecialchars($letter['role_snapshot']) ?></dd>
            <dt>Start date</dt><dd><?= htmlspecialchars($letter['start_date'] ?? '—') ?></dd>
            <dt>Reference</dt><dd>VDB-<?= htmlspecialchars(substr($letter['verify_token'], 0, 10)) ?></dd>
        </dl>

        <form method="post">
            <input type="hidden" name="t" value="<?= htmlspecialchars($token) ?>">
            <label>Optional note for HR</label>
            <textarea name="notes" placeholder="Anything you'd like to add..."></textarea>
            <div style="margin-top:16px">
                <button type="submit" name="response" value="accepted" class="accept">Accept offer</button>
                <button type="submit" name="response" value="declined" class="decline">Decline</button>
            </div>
        </form>
    <?php endif; ?>
</div></div>
</body></html>
