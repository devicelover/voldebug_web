<?php require_once __DIR__ . '/includes/track_view.php'; ?>
<?php
require __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/rate_limit.php';

$lookup    = null;
$error     = null;
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    rate_limit_or_die('app_status_lookup', 10, 300);

    $submitted = true;
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $ref   = trim($_POST['ref'] ?? '');

    if (!$email || $ref === '' || !ctype_digit($ref)) {
        $error = "Please enter both the email you applied with and the application reference number.";
    } else {
        $stmt = $con->prepare(
            "SELECT c.id, c.name, c.Position, c.status, c.created_at, j.job_name
             FROM client_career c
             LEFT JOIN career j ON j.id = c.applied_job_id
             WHERE c.id = ? AND c.email = ? LIMIT 1"
        );
        $rid = (int) $ref;
        $stmt->bind_param('is', $rid, $email);
        $stmt->execute();
        $lookup = $stmt->get_result()->fetch_assoc();
        if (!$lookup) $error = "We couldn't find an application matching that reference + email.";
    }
}

$company  = htmlspecialchars($APP_SETTINGS['name'] ?? 'Voldebug');
$hrEmail  = htmlspecialchars($APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in');

$statusCopy = [
    'applied'     => ['Received',     'Your application is in our queue. We review weekly.'],
    'reviewed'    => ['Under review', 'The hiring team has seen your application and is evaluating it.'],
    'shortlisted' => ['Shortlisted',  'You\'ve been shortlisted. Expect an email from HR with next steps.'],
    'hired'       => ['Hired',        'Congratulations — you\'ve been offered a position. Check your email for the offer letter.'],
    'rejected'    => ['Not selected', 'After review we\'re not moving forward this time. We appreciate your interest.'],
    'withdrawn'   => ['Withdrawn',    'This application was withdrawn.'],
];
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Application status — <?= $company ?></title>
<link rel="icon" type="image/png" href="assets/img/logo/favicon.ico">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<style>
    body { background:#f5f7fb; font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif; color:#222; }
    .wrap { max-width:640px; margin:60px auto; padding:0 16px; }
    .card { background:#fff; border-radius:12px; box-shadow:0 6px 30px rgba(0,0,0,.06); padding:28px; }
    h1 { font-size:22px; margin:0 0 6px 0; }
    .sub { color:#777; margin-bottom:22px; }
    .status-pill { display:inline-block; padding:4px 10px; border-radius:999px; font-weight:600; font-size:13px; }
    .s-received    { background:#eef2ff; color:#2b4cc4; }
    .s-review      { background:#fff4d6; color:#7a5300; }
    .s-shortlist   { background:#dff7e5; color:#146c2e; }
    .s-hired       { background:#d6f0ff; color:#0b4c82; }
    .s-rejected    { background:#fde8ea; color:#a11a25; }
    .s-withdrawn   { background:#eee; color:#555; }
    .kv { display:grid; grid-template-columns:160px 1fr; gap:10px 14px; margin-top:14px; }
    .kv dt { color:#777; } .kv dd { margin:0; font-weight:600; }
    .form-group { margin-bottom:14px; }
    label { font-weight:500; display:block; margin-bottom:4px; }
    input.form-control { width:100%; }
</style>
</head><body>
<div class="wrap">
    <div class="card">
        <h1>Check your application status</h1>
        <p class="sub">Enter the email you applied with + your reference number (shown in the confirmation email).</p>

        <?php if ($lookup): ?>
            <?php [$label, $desc] = $statusCopy[$lookup['status']] ?? ['Unknown', '']; ?>
            <?php $cls = ['applied' => 's-received', 'reviewed' => 's-review', 'shortlisted' => 's-shortlist', 'hired' => 's-hired', 'rejected' => 's-rejected', 'withdrawn' => 's-withdrawn'][$lookup['status']] ?? 's-received'; ?>
            <div style="border:1px solid #eef; padding:16px; border-radius:10px; background:#fafbff">
                <span class="status-pill <?= $cls ?>"><?= htmlspecialchars($label) ?></span>
                <p style="margin:10px 0 0 0"><?= htmlspecialchars($desc) ?></p>
                <dl class="kv">
                    <dt>Applicant</dt>   <dd><?= htmlspecialchars($lookup['name']) ?></dd>
                    <dt>Position</dt>    <dd><?= htmlspecialchars($lookup['Position']) ?></dd>
                    <dt>Listed role</dt> <dd><?= htmlspecialchars($lookup['job_name'] ?? '—') ?></dd>
                    <dt>Reference</dt>   <dd>#<?= (int) $lookup['id'] ?></dd>
                    <dt>Applied on</dt>  <dd><?= htmlspecialchars($lookup['created_at']) ?></dd>
                </dl>
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" style="margin-top:18px">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Reference #</label>
                <input type="number" name="ref" class="form-control" required value="<?= htmlspecialchars($_POST['ref'] ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary">Check status</button>
            <a href="career.php" class="btn btn-link">View open roles</a>
        </form>

        <p style="color:#888; font-size:13px; margin-top:20px">Trouble finding your reference? Write to <a href="mailto:<?= $hrEmail ?>"><?= $hrEmail ?></a>.</p>
    </div>
</div>
</body></html>
