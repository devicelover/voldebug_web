<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$id  = (int) ($_GET['id'] ?? 0);
$msg = $_GET['msg'] ?? '';
if (!$id) { header('Location: hr_seo_leads.php'); exit; }
$stmt = $con->prepare("SELECT * FROM seo_leads WHERE id = ?");
$stmt->bind_param('i', $id); $stmt->execute();
$l = $stmt->get_result()->fetch_assoc();
if (!$l) { header('Location: hr_seo_leads.php'); exit; }
$tpls = $con->query("SELECT id, template_name, letter_type, email_subject, email_body FROM letter_templates WHERE letter_type LIKE 'seo_%' AND is_active=1 ORDER BY letter_type")->fetch_all(MYSQLI_ASSOC);
$L = $con->prepare("SELECT * FROM email_log WHERE to_email = ? AND context_type IN ('seo','manual') ORDER BY id DESC LIMIT 20");
$L->bind_param('s', $l['email']); $L->execute();
$logs = $L->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'SEO Lead — ' . $l['business_name'])); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'SEO Lead: ' . htmlspecialchars($l['business_name']))); ?>
        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
        <div class="row">
            <div class="col-lg-5">
                <div class="card"><div class="card-body">
                    <h5>Details</h5>
                    <table class="table table-sm">
                        <tr><th>Contact</th><td><?= htmlspecialchars($l['contact_name']) ?></td></tr>
                        <tr><th>Email</th><td><a href="mailto:<?= htmlspecialchars($l['email']) ?>"><?= htmlspecialchars($l['email']) ?></a></td></tr>
                        <tr><th>Phone</th><td><?= htmlspecialchars($l['phone']) ?: '—' ?></td></tr>
                        <tr><th>Website</th><td><?= htmlspecialchars($l['website']) ?: '—' ?></td></tr>
                        <tr><th>Industry</th><td><?= htmlspecialchars($l['industry']) ?: '—' ?></td></tr>
                        <tr><th>Budget</th><td><?= htmlspecialchars($l['monthly_budget']) ?: '—' ?></td></tr>
                        <tr><th>Services</th><td><?= htmlspecialchars($l['services']) ?: '—' ?></td></tr>
                        <tr><th>Source</th><td><?= htmlspecialchars($l['source']) ?: '—' ?></td></tr>
                        <tr><th>Status</th><td><strong><?= htmlspecialchars($l['status']) ?></strong></td></tr>
                    </table>
                    <div><strong>Notes:</strong><br><?= nl2br(htmlspecialchars($l['notes'] ?? '')) ?></div>
                    <div class="mt-3"><a href="hr_seo_edit.php?id=<?= $id ?>" class="btn btn-outline-success btn-sm">Edit</a>
                        <a href="hr_seo_leads.php" class="btn btn-outline-secondary btn-sm">Back</a>
                        <form method="post" action="hr_seo_delete.php" style="display:inline" onsubmit="return confirm('Delete this lead?')">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div></div>
            </div>
            <div class="col-lg-7">
                <div class="card"><div class="card-body">
                    <h5>Send email</h5>
                    <form method="post" action="hr_seo_send.php" id="seoSendForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="lead_id" value="<?= $id ?>">

                        <div class="form-group">
                            <label>Start from a template (optional)</label>
                            <select name="template_id" class="form-control tpl-picker" data-form-id="seoSendForm">
                                <option value="">— blank / write manually —</option>
                                <?php foreach ($tpls as $t): ?>
                                    <option value="<?= (int)$t['id'] ?>"
                                            data-subject="<?= htmlspecialchars($t['email_subject'], ENT_QUOTES) ?>"
                                            data-body="<?= htmlspecialchars($t['email_body'], ENT_QUOTES) ?>">
                                        <?= htmlspecialchars($t['template_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Selecting fills the fields below — you can still edit before sending.</small>
                        </div>

                        <div class="form-group"><label>Subject</label>
                            <input type="text" name="subject" class="form-control fld-subject" required></div>
                        <div class="form-group"><label>Body</label>
                            <textarea name="body" class="form-control fld-body" rows="10" required></textarea>
                            <small class="text-muted">Placeholders: <code>{{honorific_name}} {{business_name}} {{website}} {{industry}} {{company}} {{signatory}}</code> + custom keys below.</small>
                        </div>
                        <div class="form-group"><label>Extra placeholders (key = value per line)</label>
                            <textarea name="extra_vars" class="form-control" rows="4" placeholder="seo_observation = your title tags are duplicated across pages
proposal_price = ₹20,000 / month for 3 months"></textarea>
                        </div>
                        <button type="submit" name="action" value="send"    class="btn btn-primary">Send</button>
                        <button type="submit" name="action" value="preview" class="btn btn-outline-secondary">Preview</button>
                    </form>
                </div></div>
                <div class="card"><div class="card-body">
                    <h5>Email log</h5>
                    <?php if (!$logs): ?><p class="text-muted">No emails yet.</p><?php else: ?>
                    <table class="table table-sm"><thead><tr><th>When</th><th>Subject</th><th>Status</th></tr></thead><tbody>
                    <?php foreach ($logs as $e): ?><tr><td><?= htmlspecialchars($e['created_at']) ?></td><td><?= htmlspecialchars($e['subject']) ?></td>
                        <td><span class="badge badge-<?= $e['status']==='sent'?'success':($e['status']==='failed'?'danger':'secondary') ?>"><?= htmlspecialchars($e['status']) ?></span></td></tr><?php endforeach; ?>
                    </tbody></table><?php endif; ?>
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
