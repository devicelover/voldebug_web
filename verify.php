<?php
require_once __DIR__ . '/includes/track_view.php';
require __DIR__ . '/includes/bootstrap.php';

$token = trim($_GET['t'] ?? '');
$record = null; $recordType = ''; $notFound = false; $revoked = false;

if ($token !== '' && preg_match('/^[A-Za-z0-9_-]{10,96}$/', $token)) {
    // Try letters_issued first
    $stmt = $con->prepare(
        "SELECT l.*, i.github_repo, i.mentor, i.status AS intern_status
         FROM letters_issued l
         LEFT JOIN interns i ON i.id = l.intern_id
         WHERE l.verify_token = ? LIMIT 1"
    );
    $stmt->bind_param('s', $token); $stmt->execute();
    $record = $stmt->get_result()->fetch_assoc();
    if ($record) { $recordType = 'letter'; }

    // Then try certificates_issued
    if (!$record) {
        $stmt = $con->prepare(
            "SELECT c.*, t.title AS cert_title, t.cert_kind, p.name AS partner_name
             FROM certificates_issued c
             JOIN certificate_templates t ON t.id = c.template_id
             LEFT JOIN certificate_partners p ON p.id = c.partner_id
             WHERE c.verify_token = ? LIMIT 1"
        );
        $stmt->bind_param('s', $token); $stmt->execute();
        $record = $stmt->get_result()->fetch_assoc();
        if ($record) { $recordType = 'certificate'; }
    }

    if (!$record)                       $notFound = true;
    elseif ((int) $record['revoked'] === 1) $revoked = true;
} else {
    $notFound = true;
}

$company   = htmlspecialchars($APP_SETTINGS['name']               ?? 'Voldebug');
$legalName = htmlspecialchars($APP_SETTINGS['company_legal_name'] ?: ($APP_SETTINGS['name'] ?? 'Voldebug Innovations Pvt. Ltd.'));
$hrEmail   = htmlspecialchars($APP_SETTINGS['hr_email']           ?? 'hr@voldebug.in');

$pageTitle = $recordType === 'certificate' ? 'Certificate verification' : 'Document verification';
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Verify <?= htmlspecialchars($pageTitle) ?> — <?= $company ?></title>
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
            <h1><?= $company ?> &middot; <?= htmlspecialchars($pageTitle) ?></h1>
            <div style="margin-left:auto">
                <?php if ($notFound): ?><span class="badge-bad">Not found</span>
                <?php elseif ($revoked): ?><span class="badge-warn">Revoked</span>
                <?php else: ?><span class="badge-ok">Authentic</span><?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <?php if ($notFound): ?>
                <p>We couldn't find a document matching this verification code. It may be invalid or forged.</p>
                <p style="color:#777; font-size:13px">If you were given this link by someone claiming to be from <?= $company ?>, please email <a href="mailto:<?= $hrEmail ?>"><?= $hrEmail ?></a>.</p>

            <?php elseif ($revoked && $recordType === 'letter'): ?>
                <p>This letter was issued by <?= $legalName ?> but has since been <strong>revoked</strong>.</p>
                <?php if (!empty($record['revoked_reason'])): ?><p><em>Reason:</em> <?= htmlspecialchars($record['revoked_reason']) ?></p><?php endif; ?>
                <dl class="kv">
                    <dt>Issued to</dt><dd><?= htmlspecialchars($record['recipient_name']) ?></dd>
                    <dt>Issue date</dt><dd><?= htmlspecialchars($record['issue_date']) ?></dd>
                </dl>

            <?php elseif ($revoked && $recordType === 'certificate'): ?>
                <p>This certificate was issued by <?= $legalName ?> but has since been <strong>revoked</strong>.</p>
                <?php if (!empty($record['revoked_reason'])): ?><p><em>Reason:</em> <?= htmlspecialchars($record['revoked_reason']) ?></p><?php endif; ?>
                <dl class="kv">
                    <dt>Recipient</dt><dd><?= htmlspecialchars($record['recipient_name']) ?></dd>
                    <dt>Course</dt>   <dd><?= htmlspecialchars($record['course_name']) ?></dd>
                </dl>

            <?php elseif ($recordType === 'certificate'): ?>
                <p>This certificate was genuinely issued by <?= $legalName ?>.</p>
                <dl class="kv">
                    <dt>Recipient</dt> <dd><?= htmlspecialchars($record['recipient_name']) ?></dd>
                    <dt>Course / Program</dt><dd><?= htmlspecialchars($record['course_name']) ?></dd>
                    <dt>Certificate type</dt><dd><?= htmlspecialchars($record['cert_title']) ?> <small class="text-muted">(<?= htmlspecialchars($record['cert_kind']) ?>)</small></dd>
                    <?php if (!empty($record['partner_name'])): ?>
                        <dt>Partner institute</dt><dd><?= htmlspecialchars($record['partner_name']) ?></dd>
                    <?php endif; ?>
                    <?php if (!empty($record['duration'])): ?>
                        <dt>Duration</dt><dd><?= htmlspecialchars($record['duration']) ?></dd>
                    <?php endif; ?>
                    <?php if (!empty($record['completion_date'])): ?>
                        <dt>Completion date</dt><dd><?= htmlspecialchars(date('d M Y', strtotime($record['completion_date']))) ?></dd>
                    <?php endif; ?>
                    <dt>Reference</dt><dd style="font-family:monospace; font-size:13px">VDB-<?= htmlspecialchars($record['verify_token']) ?></dd>
                </dl>

            <?php else: /* letter, authentic */ ?>
                <p>This letter was genuinely issued by <?= $legalName ?>.</p>
                <dl class="kv">
                    <dt>Recipient</dt> <dd><?= htmlspecialchars($record['recipient_name']) ?></dd>
                    <dt>Role</dt>      <dd><?= htmlspecialchars($record['role_snapshot']) ?></dd>
                    <dt>Letter type</dt><dd><?= htmlspecialchars(ucwords(str_replace('_', ' ', $record['letter_type']))) ?></dd>
                    <dt>Issue date</dt><dd><?= htmlspecialchars(date('d M Y', strtotime($record['issue_date']))) ?></dd>
                    <dt>Reference</dt> <dd style="font-family:monospace; font-size:13px">VDB-<?= htmlspecialchars($record['verify_token']) ?></dd>
                </dl>
            <?php endif; ?>
        </div>
    </div>
    <div class="foot">&copy; <?= date('Y') ?> <?= $legalName ?>. Letters &amp; certificates are digitally signed and traceable.</div>
</div>
</body></html>
