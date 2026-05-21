<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg = $_GET['msg'] ?? '';
// List recent campaigns + queue stats.
$camps = $con->query("SELECT * FROM email_campaigns ORDER BY id DESC LIMIT 20")->fetch_all(MYSQLI_ASSOC);
$queueStats = $con->query("SELECT status, COUNT(*) c FROM email_queue GROUP BY status")->fetch_all(MYSQLI_ASSOC);
$signatories = $con->query("SELECT label, name, email FROM signatories WHERE is_active = 1 ORDER BY id")->fetch_all(MYSQLI_ASSOC);

// Audience counts for the dropdown.
$applicantCount = (int) $con->query("SELECT COUNT(DISTINCT email) c FROM client_career WHERE email LIKE '%@%'")->fetch_assoc()['c'];
$internCount    = (int) $con->query("SELECT COUNT(*) c FROM interns")->fetch_assoc()['c'];
$partnerCount   = (int) $con->query("SELECT COUNT(*) c FROM key_partners")->fetch_assoc()['c'];
$seoCount       = (int) $con->query("SELECT COUNT(*) c FROM seo_leads")->fetch_assoc()['c'];

// Templates available for the picker.
$bulkTemplates = $con->query(
    "SELECT id, template_name, letter_type, role_tag, email_subject, email_body
     FROM letter_templates WHERE is_active = 1 ORDER BY letter_type, role_tag"
)->fetch_all(MYSQLI_ASSOC);
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Bulk Email')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Bulk Email / Cold Outreach')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="card"><div class="card-body">
            <p class="text-muted small mb-3">
                Compose a campaign once → it's enqueued and trickled out by the cron at a safe rate
                (~30 emails / hour by default) to avoid SMTP blocks, with proper List-Unsubscribe headers
                and an opt-out footer auto-appended. Unsubscribed addresses are silently skipped.
            </p>
            <form method="post" action="hr_bulk_email_send.php" id="bulkForm">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label>Start from a template (optional)</label>
                    <select class="form-control tpl-picker" data-form-id="bulkForm">
                        <option value="">— blank / write manually —</option>
                        <?php foreach ($bulkTemplates as $t): ?>
                            <option value="<?= (int)$t['id'] ?>"
                                    data-subject="<?= htmlspecialchars($t['email_subject'], ENT_QUOTES) ?>"
                                    data-body="<?= htmlspecialchars($t['email_body'], ENT_QUOTES) ?>">
                                [<?= htmlspecialchars($t['letter_type']) ?>/<?= htmlspecialchars($t['role_tag']) ?>] <?= htmlspecialchars($t['template_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Picks the template into Subject + Body fields below — you can edit before sending.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Campaign name (internal)</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Q2 Key Partner outreach — DACH">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>From identity</label>
                        <select name="from_label" class="form-control">
                            <?php foreach ($signatories as $s): ?>
                                <option value="<?= htmlspecialchars($s['label']) ?>"><?= htmlspecialchars($s['name']) ?> &lt;<?= htmlspecialchars($s['email']) ?>&gt;</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Audience</label>
                        <select name="audience" class="form-control" required>
                            <option value="applicants">All applicants (<?= $applicantCount ?>)</option>
                            <option value="interns">All interns (<?= $internCount ?>)</option>
                            <option value="partners">All key partners (<?= $partnerCount ?>)</option>
                            <option value="seo_leads">All SEO leads (<?= $seoCount ?>)</option>
                            <option value="custom">Custom list (paste below)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Custom recipients (only if "Custom list" above) — one per line: <code>email | name</code></label>
                    <textarea name="custom_list" class="form-control" rows="4" placeholder="klaus@example.de | Klaus Müller
priya@example.in | Priya Sharma"></textarea>
                </div>

                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" class="form-control fld-subject" required placeholder="Use {{name}} for personalisation">
                </div>

                <div class="form-group">
                    <label>Body (HTML allowed; use {{name}} {{email}} {{company}} for personalisation)</label>
                    <textarea name="body" class="form-control fld-body" rows="12" required placeholder="<p>Hi {{name}},</p>
<p>Quick note from {{company}} — ...</p>"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-3 form-group">
                        <label>Send rate (emails / hour)</label>
                        <input type="number" name="rate_per_hour" class="form-control" value="30" min="5" max="200">
                        <small class="text-muted">Lower = safer. 30/hr is conservative for Hostinger SMTP.</small>
                    </div>
                </div>

                <button type="submit" name="action" value="enqueue" class="btn btn-primary">Enqueue Campaign</button>
                <button type="submit" name="action" value="preview" class="btn btn-outline-secondary">Preview (no send)</button>
            </form>
        </div></div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card"><div class="card-body">
                    <h5>Queue status</h5>
                    <table class="table table-sm">
                        <?php foreach ($queueStats as $s): ?>
                            <tr><td><?= htmlspecialchars($s['status']) ?></td><td><strong><?= (int)$s['c'] ?></strong></td></tr>
                        <?php endforeach; ?>
                        <?php if (!$queueStats): ?><tr><td colspan="2" class="text-muted">Queue empty.</td></tr><?php endif; ?>
                    </table>
                    <small class="text-muted">Cron <code>cron/process_email_queue.php</code> must run every minute on the server for sends to flow.</small>
                </div></div>
            </div>
            <div class="col-md-6">
                <div class="card"><div class="card-body">
                    <h5>Unsubscribes</h5>
                    <?php $unsub = (int) $con->query("SELECT COUNT(*) c FROM email_unsubscribes")->fetch_assoc()['c']; ?>
                    <p><strong><?= $unsub ?></strong> address(es) have unsubscribed and will be skipped automatically.</p>
                </div></div>
            </div>
        </div>

        <div class="card mt-3"><div class="card-body">
            <h5>Recent campaigns</h5>
            <table class="table table-sm">
                <thead><tr><th>ID</th><th>Name</th><th>Audience</th><th>Recipients</th><th>Status</th><th>Created</th></tr></thead>
                <tbody>
                <?php foreach ($camps as $c): ?>
                    <tr>
                        <td>#<?= (int)$c['id'] ?></td>
                        <td><?= htmlspecialchars($c['name']) ?></td>
                        <td><?= htmlspecialchars($c['audience']) ?></td>
                        <td><?= (int)$c['recipients_count'] ?></td>
                        <td><span class="badge badge-<?= $c['status']==='completed'?'success':($c['status']==='aborted'?'danger':'info') ?>"><?= htmlspecialchars($c['status']) ?></span></td>
                        <td><?= htmlspecialchars($c['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$camps): ?><tr><td colspan="6" class="text-muted">No campaigns yet.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div></div>
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
