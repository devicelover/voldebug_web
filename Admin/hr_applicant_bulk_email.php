<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
// Accept the selected applicant IDs from the applicants page form.
csrf_require();   // enforce CSRF on the POST that lands here

$rawIds = trim((string)($_POST['ids'] ?? ''));
$ids = [];
foreach (explode(',', $rawIds) as $part) {
    $i = (int) trim($part);
    if ($i > 0) $ids[] = $i;
}
$ids = array_values(array_unique($ids));

if (!$ids) {
    header('Location: hr_applicants.php?msg=' . urlencode('No applicants selected.'));
    exit;
}

// Pull recipient details + dedupe by email (case-insensitive).
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $con->prepare("SELECT id, name, email, Position, status FROM client_career WHERE id IN ($placeholders)");
$stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$recipients = [];
$seenEmails = [];
foreach ($rows as $r) {
    $k = strtolower((string)$r['email']);
    if (!filter_var($r['email'], FILTER_VALIDATE_EMAIL) || isset($seenEmails[$k])) continue;
    $seenEmails[$k] = true;
    $recipients[] = $r;
}

// Lookups for the form
$signatories = $con->query("SELECT label, name, email FROM signatories WHERE is_active = 1 ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$templates   = $con->query(
    "SELECT id, template_name, letter_type, role_tag, email_subject, email_body
     FROM letter_templates WHERE is_active = 1 ORDER BY letter_type, role_tag"
)->fetch_all(MYSQLI_ASSOC);
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Email selected applicants')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Email ' . count($recipients) . ' selected applicant' . (count($recipients) === 1 ? '' : 's'))); ?>

        <div class="alert alert-info">
            Sending one email per applicant (not a single email with everyone in TO/CC).
            Each message is personalised with the applicant's <code>{{name}}</code>, <code>{{first_name}}</code>, <code>{{role}}</code>, and <code>{{email}}</code>.
            <br>
            <small class="text-muted">Send is throttled at ~1 email/second to stay polite to the SMTP server. For very large lists (50+) use the <a href="hr_bulk_email.php">queued bulk email portal</a> instead.</small>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card"><div class="card-body">
                    <form method="post" action="hr_applicant_bulk_send.php" id="bulkSendForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="ids" value="<?= htmlspecialchars(implode(',', array_column($recipients, 'id'))) ?>">

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Start from a template (optional)</label>
                                <select class="form-control tpl-picker" data-form-id="bulkSendForm">
                                    <option value="">— blank / write manually —</option>
                                    <?php foreach ($templates as $t): ?>
                                        <option value="<?= (int)$t['id'] ?>"
                                                data-subject="<?= htmlspecialchars($t['email_subject'], ENT_QUOTES) ?>"
                                                data-body="<?= htmlspecialchars($t['email_body'], ENT_QUOTES) ?>">
                                            [<?= htmlspecialchars($t['letter_type']) ?>/<?= htmlspecialchars($t['role_tag']) ?>] <?= htmlspecialchars($t['template_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Picks the template into Subject + Body — still editable.</small>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>From identity</label>
                                <select name="from_label" class="form-control">
                                    <?php foreach ($signatories as $s): ?>
                                        <option value="<?= htmlspecialchars($s['label']) ?>"><?= htmlspecialchars($s['name']) ?> &lt;<?= htmlspecialchars($s['email']) ?>&gt;</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control fld-subject" required
                                   value="Update on your application — {{role}}">
                        </div>

                        <div class="form-group">
                            <label>Body (HTML or plain text)</label>
                            <textarea name="body" class="form-control fld-body" rows="13" required>Hi {{first_name}},

Thanks again for applying for the {{role}} role at Voldebug Innovations Pvt. Ltd.

[your message here]

Warm Regards,
{{signatory}}
{{signatory_role}}</textarea>
                            <small class="text-muted">
                                Available per-recipient placeholders:
                                <code>{{name}}</code> · <code>{{first_name}}</code> ·
                                <code>{{role}}</code> · <code>{{email}}</code> ·
                                <code>{{honorific_name}}</code> ·
                                <code>{{company}}</code> · <code>{{signatory}}</code> · <code>{{signatory_role}}</code>
                            </small>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="update_status" value="1">
                                Also mark these applicants as "reviewed" after sending
                            </label>
                        </div>

                        <button type="submit" name="action" value="preview" class="btn btn-outline-info">Preview first recipient</button>
                        <button type="submit" name="action" value="send"    class="btn btn-primary">Send to all <?= count($recipients) ?></button>
                        <a href="hr_applicants.php" class="btn btn-outline-secondary">Cancel</a>
                    </form>
                </div></div>
            </div>

            <div class="col-lg-4">
                <div class="card"><div class="card-body">
                    <h5>Recipients (<?= count($recipients) ?>)</h5>
                    <?php if (count($rows) !== count($recipients)): ?>
                        <div class="alert alert-warning" style="padding:6px 10px;font-size:13px">
                            <?= count($rows) - count($recipients) ?> row(s) skipped (duplicate or invalid email).
                        </div>
                    <?php endif; ?>
                    <div style="max-height:480px;overflow-y:auto;font-size:13px">
                        <ul style="list-style:none;padding:0;margin:0">
                            <?php foreach ($recipients as $r): ?>
                                <li style="padding:6px 0;border-bottom:1px solid #eef">
                                    <strong><?= htmlspecialchars($r['name']) ?></strong><br>
                                    <small><?= htmlspecialchars($r['email']) ?></small><br>
                                    <small class="text-muted"><?= htmlspecialchars($r['Position']) ?> · <?= htmlspecialchars($r['status'] ?? 'applied') ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div></div>
            </div>
        </div>

    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
<script>
document.addEventListener('change', function (e) {
    var sel = e.target;
    if (!sel.classList || !sel.classList.contains('tpl-picker')) return;
    var formId = sel.getAttribute('data-form-id');
    var form   = document.getElementById(formId);
    if (!form) return;
    var subject = form.querySelector('.fld-subject');
    var body    = form.querySelector('.fld-body');
    var opt     = sel.options[sel.selectedIndex];
    if (!opt || !opt.value) return;
    if (subject) subject.value = opt.getAttribute('data-subject') || subject.value;
    if (body)    body.value    = opt.getAttribute('data-body')    || body.value;
});
</script>
</body></html>
