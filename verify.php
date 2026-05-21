<?php
require __DIR__ . '/includes/bootstrap.php';

$token = trim($_GET['t'] ?? '');
$letter = null; $intern = null; $notFound = false; $revoked = false;

if ($token !== '' && preg_match('/^[A-Za-z0-9_-]{10,96}$/', $token)) {
    $stmt = $con->prepare(
        "SELECT l.*, i.github_repo, i.mentor, i.status AS intern_status
         FROM letters_issued l
         LEFT JOIN interns i ON i.id = l.intern_id
         WHERE l.verify_token = ? LIMIT 1"
    );
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $res = $stmt->get_result();
    $letter = $res->fetch_assoc();
    if (!$letter) {
        $notFound = true;
    } elseif ((int) $letter['revoked'] === 1) {
        $revoked = true;
    }
} else {
    $notFound = true;
}

$company = htmlspecialchars($APP_SETTINGS['name'] ?? 'Voldebug');
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Verify Letter — <?= $company ?></title>
<link rel="icon" type="image/png" href="assets/img/logo/favicon.ico">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<style>
    body { background: #f5f7fb; font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; color: #222; }
    .wrap { max-width: 720px; margin: 60px auto; padding: 0 16px; }
    .card { background: #fff; border-radius: 12px; box-shadow: 0 6px 30px rgba(0,0,0,.06); overflow: hidden; }
    .card-hdr { padding: 18px 24px; border-bottom: 1px solid #eef; display: flex; align-items: center; gap: 12px; }
    .badge-ok   { background: #e6f7ea; color: #146c2e; padding: 4px 10px; border-radius: 999px; font-weight: 600; font-size: 13px; }
    .badge-bad  { background: #fde8ea; color: #a11a25; padding: 4px 10px; border-radius: 999px; font-weight: 600; font-size: 13px; }
    .badge-warn { background: #fff4d6; color: #7a5300; padding: 4px 10px; border-radius: 999px; font-weight: 600; font-size: 13px; }
    .card-body { padding: 24px; }
    .kv { display: grid; grid-template-columns: 200px 1fr; gap: 10px 16px; }
    .kv dt { color: #777; font-weight: 500; }
    .kv dd { margin: 0; font-weight: 600; color: #111; }
    .foot { text-align: center; color: #888; font-size: 13px; margin-top: 18px; }
    h1 { font-size: 20px; margin: 0; }
</style>
</head><body>
<div class="wrap">
    <div class="card">
        <div class="card-hdr">
            <h1><?= $company ?> &middot; Letter verification</h1>
            <div style="margin-left:auto">
                <?php if ($notFound): ?>
                    <span class="badge-bad">Not found</span>
                <?php elseif ($revoked): ?>
                    <span class="badge-warn">Revoked</span>
                <?php else: ?>
                    <span class="badge-ok">Authentic</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <?php if ($notFound): ?>
                <p>We couldn't find a letter matching this verification code. It may be an invalid or forged link.</p>
                <p style="color:#777; font-size:13px">If you were given this link by someone claiming to be from <?= $company ?>, please email us at <a href="mailto:<?= htmlspecialchars($APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in') ?>"><?= htmlspecialchars($APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in') ?></a>.</p>
            <?php elseif ($revoked): ?>
                <p>This letter was issued by <?= $company ?> but has since been <strong>revoked</strong>.</p>
                <?php if (!empty($letter['revoked_reason'])): ?>
                    <p><em>Reason:</em> <?= htmlspecialchars($letter['revoked_reason']) ?></p>
                <?php endif; ?>
                <dl class="kv">
                    <dt>Issued to</dt><dd><?= htmlspecialchars($letter['recipient_name']) ?></dd>
                    <dt>Issue date</dt><dd><?= htmlspecialchars($letter['issue_date']) ?></dd>
                </dl>
            <?php else: ?>
                <p>This letter was genuinely issued by <?= $company ?>. Details:</p>
                <dl class="kv">
                    <dt>Recipient</dt> <dd><?= htmlspecialchars($letter['recipient_name']) ?></dd>
                    <dt>Role</dt>      <dd><?= htmlspecialchars($letter['role_snapshot']) ?></dd>
                    <dt>Letter type</dt><dd><?= htmlspecialchars(ucwords(str_replace('_', ' ', $letter['letter_type']))) ?></dd>
                    <dt>Issue date</dt><dd><?= htmlspecialchars(date('d M Y', strtotime($letter['issue_date']))) ?></dd>
                    <dt>Reference</dt> <dd style="font-family:monospace; font-size:13px">VDB-<?= htmlspecialchars($letter['verify_token']) ?></dd>
                </dl>
            <?php endif; ?>
        </div>
    </div>
    <div class="foot">&copy; <?= date('Y') ?> <?= $company ?>. All letters are digitally signed and traceable.</div>
</div>
</body></html>
