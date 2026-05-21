<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$id  = (int) ($_GET['id'] ?? 0);
$msg = $_GET['msg'] ?? '';
if (!$id) { header('Location: hr_partners.php'); exit; }

$stmt = $con->prepare("SELECT * FROM key_partners WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if (!$p) { header('Location: hr_partners.php'); exit; }

// Email templates available for partners (letter_type starts with 'partner_')
$tpls = $con->query("SELECT id, template_name, letter_type, email_subject, email_body FROM letter_templates WHERE letter_type LIKE 'partner_%' AND is_active=1 ORDER BY letter_type")->fetch_all(MYSQLI_ASSOC);

// Email log for this partner (by email address).
$L = $con->prepare("SELECT * FROM email_log WHERE to_email = ? AND context_type IN ('partner','manual') ORDER BY id DESC LIMIT 30");
$L->bind_param('s', $p['email']);
$L->execute();
$logs = $L->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Partner — ' . $p['company_name'])); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Partner: ' . htmlspecialchars($p['company_name']))); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="row">
            <div class="col-lg-5">
                <div class="card"><div class="card-body">
                    <h5>Details</h5>
                    <table class="table table-sm mb-2">
                        <tr><th>Contact</th><td><?= htmlspecialchars(($p['title_prefix']?$p['title_prefix'].' ':'').$p['contact_name']) ?></td></tr>
                        <tr><th>Email</th><td><a href="mailto:<?= htmlspecialchars($p['email']) ?>"><?= htmlspecialchars($p['email']) ?></a></td></tr>
                        <tr><th>Phone</th><td><?= htmlspecialchars($p['phone']) ?: '—' ?></td></tr>
                        <tr><th>Website</th><td><?= htmlspecialchars($p['website']) ?: '—' ?></td></tr>
                        <tr><th>Country</th><td><?= htmlspecialchars($p['country']) ?><?= $p['city']?', '.htmlspecialchars($p['city']):'' ?></td></tr>
                        <tr><th>Territories</th><td><?= htmlspecialchars($p['territories']) ?: '—' ?></td></tr>
                        <tr><th>Commission</th><td><?= htmlspecialchars($p['commission_rate']) ?: '—' ?></td></tr>
                        <tr><th>Status</th><td><strong><?= htmlspecialchars($p['status']) ?></strong></td></tr>
                    </table>
                    <div><strong>Internal notes:</strong><br><?= nl2br(htmlspecialchars($p['notes'] ?? '')) ?></div>
                    <div class="mt-3">
                        <a href="hr_partner_edit.php?id=<?= $id ?>" class="btn btn-outline-success btn-sm">Edit</a>
                        <a href="hr_partners.php" class="btn btn-outline-secondary btn-sm">Back</a>
                        <form method="post" action="hr_partner_delete.php" style="display:inline" onsubmit="return confirm('Delete this partner?')">
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
                    <form method="post" action="hr_partner_send.php" id="partnerSendForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="partner_id" value="<?= $id ?>">

                        <div class="form-group">
                            <label>Start from a template (optional)</label>
                            <select name="template_id" class="form-control tpl-picker" data-form-id="partnerSendForm">
                                <option value="">— blank / write manually —</option>
                                <?php foreach ($tpls as $t): ?>
                                    <option value="<?= (int)$t['id'] ?>"
                                            data-subject="<?= htmlspecialchars($t['email_subject'], ENT_QUOTES) ?>"
                                            data-body="<?= htmlspecialchars($t['email_body'], ENT_QUOTES) ?>">
                                        <?= htmlspecialchars($t['template_name']) ?> (<?= htmlspecialchars($t['letter_type']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Selecting fills the fields below — you can still edit before sending.</small>
                        </div>

                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control fld-subject" required>
                        </div>

                        <div class="form-group">
                            <label>Body (plain text or HTML; supports placeholders)</label>
                            <textarea name="body" class="form-control fld-body" rows="10" required></textarea>
                            <small class="text-muted">Placeholders: <code>{{honorific_name}} {{honorific_full_name}} {{contact_name}} {{company_name}} {{country}} {{territory}} {{commission_rate}} {{company}} {{signatory}}</code> + any custom keys you list below.</small>
                        </div>

                        <div class="form-group">
                            <label>Extra placeholders (key = value per line — overrides defaults)</label>
                            <textarea name="extra_vars" class="form-control" rows="5" placeholder="meeting_topic = Q1 review
meeting_date = 15 May 2026
meeting_link = https://meet.google.com/...
report_period = April 2026
report_summary = 12 leads routed; 3 closed."></textarea>
                        </div>

                        <button type="submit" name="action" value="send"     class="btn btn-primary">Send Email</button>
                        <button type="submit" name="action" value="preview"  class="btn btn-outline-secondary">Preview (no send)</button>
                    </form>
                </div></div>

                <div class="card"><div class="card-body">
                    <h5>Email log</h5>
                    <?php if (!$logs): ?><p class="text-muted">No emails yet.</p><?php else: ?>
                    <table class="table table-sm">
                        <thead><tr><th>When</th><th>Subject</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td><?= htmlspecialchars($l['created_at']) ?></td>
                                <td><?= htmlspecialchars($l['subject']) ?></td>
                                <td><span class="badge badge-<?= $l['status']==='sent'?'success':($l['status']==='failed'?'danger':'secondary') ?>"><?= htmlspecialchars($l['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
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
