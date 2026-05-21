<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg = $_GET['msg'] ?? '';
$filter = $_GET['status'] ?? '';
$signatories = $con->query("SELECT label, name, email FROM signatories WHERE is_active=1 ORDER BY id")->fetch_all(MYSQLI_ASSOC);

// Templates relevant to applicant emails (status updates, offers, rejections, custom partner
// outreach can also be repurposed). We expose all active templates.
$applicantTemplates = $con->query(
    "SELECT id, template_name, letter_type, role_tag, email_subject, email_body
     FROM letter_templates WHERE is_active = 1 ORDER BY letter_type, role_tag"
)->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT c.*, j.job_name
        FROM client_career c
        LEFT JOIN career j ON j.id = c.applied_job_id";
$args = [];
if ($filter !== '') {
    $sql .= " WHERE c.status = ?";
    $args[] = $filter;
}
$sql .= " ORDER BY c.id DESC";

$stmt = $con->prepare($sql);
if ($args) $stmt->bind_param(str_repeat('s', count($args)), ...$args);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$statuses = ['applied' => 'Applied', 'reviewed' => 'Reviewed', 'shortlisted' => 'Shortlisted', 'hired' => 'Hired', 'rejected' => 'Rejected', 'withdrawn' => 'Withdrawn'];
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Applicants')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Career Applicants')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="card mb-3"><div class="card-body d-flex align-items-center justify-content-between">
            <form method="get" class="form-inline mb-0">
                <label class="mr-2">Filter status</label>
                <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">All</option>
                    <?php foreach ($statuses as $k => $v): ?>
                        <option value="<?= $k ?>" <?= $filter===$k?'selected':'' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            <div class="text-muted">Total: <strong><?= count($rows) ?></strong></div>
        </div></div>

        <div class="card"><div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Position</th><th>Applied for (job)</th>
                            <th>Resume</th><th>Status</th><th>Applied</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?= (int)$r['id'] ?></td>
                            <td><?= htmlspecialchars($r['name']) ?></td>
                            <td><a href="mailto:<?= htmlspecialchars($r['email']) ?>"><?= htmlspecialchars($r['email']) ?></a></td>
                            <td><?= htmlspecialchars($r['phone']) ?></td>
                            <td><?= htmlspecialchars($r['Position']) ?></td>
                            <td><?= htmlspecialchars($r['job_name'] ?? '—') ?></td>
                            <td>
                                <?php if (!empty($r['pdf'])): ?>
                                    <a href="hr_resume_download.php?id=<?= (int)$r['id'] ?>" target="_blank">Download</a>
                                <?php else: ?>—<?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="hr_applicant_update.php" id="statusForm<?= (int)$r['id'] ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                    <select name="status" class="form-control form-control-sm status-sel" data-id="<?= (int)$r['id'] ?>" data-name="<?= htmlspecialchars($r['name'], ENT_QUOTES) ?>">
                                        <?php foreach ($statuses as $k => $v): ?>
                                            <option value="<?= $k ?>" <?= ($r['status']??'applied')===$k?'selected':'' ?>><?= $v ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label class="d-block mt-1" style="font-size:11px;color:#777">
                                        <input type="checkbox" name="inform_applicant" value="1"> inform by email
                                    </label>
                                    <button type="submit" class="btn btn-sm btn-outline-primary mt-1" style="padding:2px 8px;font-size:11px">Save</button>
                                </form>
                            </td>
                            <td><?= htmlspecialchars($r['created_at'] ?? '') ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#hireModal<?= (int)$r['id'] ?>">Hire as Intern</button>
                                <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#mailModal<?= (int)$r['id'] ?>">Email</button>
                            </td>
                        </tr>

                        <!-- Hire modal -->
                        <div class="modal fade" id="hireModal<?= (int)$r['id'] ?>" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
                            <form method="post" action="hr_applicant_hire.php">
<?php echo csrf_field(); ?>
                                <div class="modal-header"><h5 class="modal-title">Hire <?= htmlspecialchars($r['name']) ?> as Intern</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                <div class="modal-body">
                                    <input type="hidden" name="applicant_id" value="<?= (int)$r['id'] ?>">
                                    <div class="row">
                                        <div class="col-md-4 form-group"><label>Title prefix</label>
                                            <select name="title_prefix" class="form-control">
                                                <option value="">—</option>
                                                <option>Mr.</option><option>Ms.</option><option>Mrs.</option><option>Mx.</option><option>Dr.</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8 form-group"><label>Role (as printed on letter)</label>
                                            <input type="text" name="role" class="form-control" value="<?= htmlspecialchars($r['Position']) ?>" required></div>
                                    </div>
                                    <div class="form-group"><label>Role tag (for template matching)</label>
                                        <select name="role_tag" class="form-control">
                                            <option value="general">general</option>
                                            <option value="cybersecurity_intern">cybersecurity_intern</option>
                                            <option value="web_dev_intern">web_dev_intern</option>
                                            <option value="web_designer_intern">web_designer_intern</option>
                                            <option value="ai_intern">ai_intern</option>
                                            <option value="marketing_intern">marketing_intern</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group"><label>Enrollment Number</label>
                                            <input type="text" name="enrollment_number" class="form-control" placeholder="e.g. 2203031050292"></div>
                                        <div class="col-md-6 form-group"><label>Internship Type</label>
                                            <select name="internship_type" class="form-control">
                                                <option value="">—</option>
                                                <option value="Hybrid (Remote + On-site)">Hybrid (Remote + On-site)</option>
                                                <option value="Remote">Remote</option>
                                                <option value="On-site">On-site</option>
                                                <option value="Full-time">Full-time</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group"><label>College / Institute</label>
                                        <input type="text" name="college" class="form-control" placeholder="e.g. Marwadi University"></div>
                                    <div class="row">
                                        <div class="col-md-6 form-group"><label>Start date</label>
                                            <input type="date" name="start_date" class="form-control" required></div>
                                        <div class="col-md-6 form-group"><label>End date</label>
                                            <input type="date" name="end_date" class="form-control"></div>
                                    </div>
                                    <div class="form-group"><label>GitHub repo (optional — for task tracking)</label>
                                        <input type="url" name="github_repo" class="form-control" placeholder="https://github.com/..."></div>
                                    <div class="form-group"><label>Mentor / supervisor</label>
                                        <input type="text" name="mentor" class="form-control"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Create Intern Record</button>
                                </div>
                            </form>
                        </div></div></div>

                        <!-- Custom email modal — pick a template OR write from scratch -->
                        <div class="modal fade" id="mailModal<?= (int)$r['id'] ?>" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
                            <form method="post" action="hr_applicant_send.php" id="mailForm<?= (int)$r['id'] ?>">
                                <?php echo csrf_field(); ?>
                                <div class="modal-header"><h5 class="modal-title">Email <?= htmlspecialchars($r['name']) ?> &lt;<?= htmlspecialchars($r['email']) ?>&gt;</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                <div class="modal-body">
                                    <input type="hidden" name="applicant_id" value="<?= (int)$r['id'] ?>">

                                    <div class="row">
                                        <div class="col-md-6 form-group"><label>Start from a template (optional)</label>
                                            <select class="form-control tpl-picker" data-form-id="mailForm<?= (int)$r['id'] ?>">
                                                <option value="">— blank / write manually —</option>
                                                <?php foreach ($applicantTemplates as $t): ?>
                                                    <option value="<?= (int)$t['id'] ?>"
                                                            data-subject="<?= htmlspecialchars($t['email_subject'], ENT_QUOTES) ?>"
                                                            data-body="<?= htmlspecialchars($t['email_body'], ENT_QUOTES) ?>">
                                                        [<?= htmlspecialchars($t['letter_type']) ?>] <?= htmlspecialchars($t['template_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="text-muted">Selecting fills the fields below — you can still edit before sending.</small>
                                        </div>
                                        <div class="col-md-6 form-group"><label>From identity</label>
                                            <select name="from_label" class="form-control">
                                                <?php foreach ($signatories as $s): ?>
                                                    <option value="<?= htmlspecialchars($s['label']) ?>"><?= htmlspecialchars($s['name']) ?> &lt;<?= htmlspecialchars($s['email']) ?>&gt;</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group"><label>Subject</label>
                                        <input type="text" name="subject" class="form-control fld-subject" required value="Update on your application — <?= htmlspecialchars($r['Position']) ?>"></div>

                                    <div class="form-group"><label>Body (HTML or plain text; placeholders: <code>{{name}} {{first_name}} {{role}} {{company}} {{signatory}}</code>)</label>
                                        <textarea name="body" class="form-control fld-body" rows="11" required>Hi {{first_name}},

Thanks again for applying for the {{role}} role.

[your message here]

Warm Regards,
{{signatory}}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="action" value="preview" class="btn btn-outline-info">Preview</button>
                                    <button type="submit" name="action" value="send"    class="btn btn-primary">Send Now</button>
                                </div>
                            </form>
                        </div></div></div>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div></div>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
<script>
// Template picker → autofill subject/body inside the same form.
// Skips empty selection so the admin can write from scratch.
document.addEventListener('change', function (e) {
    var sel = e.target;
    if (!sel.classList || !sel.classList.contains('tpl-picker')) return;
    var formId = sel.getAttribute('data-form-id');
    var form   = document.getElementById(formId);
    if (!form) return;
    var subject = form.querySelector('.fld-subject');
    var body    = form.querySelector('.fld-body');
    var opt     = sel.options[sel.selectedIndex];
    if (!opt || !opt.value) return; // blank choice — leave existing content alone
    if (subject) subject.value = opt.getAttribute('data-subject') || subject.value;
    if (body)    body.value    = opt.getAttribute('data-body')    || body.value;
});
</script>
</body></html>
