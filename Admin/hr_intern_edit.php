<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$id = (int) ($_GET['id'] ?? 0);
$row = [
    'id' => 0, 'title_prefix' => '', 'name' => '', 'first_name' => '',
    'email' => '', 'phone' => '', 'role' => '',
    'role_tag' => 'general', 'employee_type' => 'intern', 'internship_type' => '',
    'start_date' => '', 'end_date' => '',
    'enrollment_number' => '', 'college' => '',
    'github_repo' => '', 'linkedin_url' => '', 'mentor' => '',
    'stipend' => '', 'reporting_location' => '',
    'tasks_summary' => '', 'performance_notes' => '', 'status' => 'active',
];
if ($id) {
    $stmt = $con->prepare("SELECT * FROM interns WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($r = $stmt->get_result()->fetch_assoc()) $row = $r;
}
$roleTags        = ['general', 'cybersecurity_intern', 'web_dev_intern', 'web_designer_intern', 'ai_intern', 'marketing_intern', 'employee', 'contractor'];
$types           = ['intern', 'employee', 'contractor'];
$statuses        = ['active', 'completed', 'terminated', 'on_hold'];
$internshipTypes = ['', 'Hybrid (Remote + On-site)', 'Remote', 'On-site', 'Full-time'];
$titlePrefixes   = ['', 'Mr.', 'Ms.', 'Mrs.', 'Mx.', 'Dr.'];
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Intern details')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => $id ? 'Edit Intern' : 'Add Intern / Employee')); ?>

        <div class="card"><div class="card-body">
            <form method="post" action="hr_intern_save.php">
<?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                <div class="row">
                    <div class="col-md-2 form-group"><label>Title</label>
                        <select name="title_prefix" class="form-control">
                            <?php foreach ($titlePrefixes as $t): ?>
                                <option value="<?= htmlspecialchars($t) ?>" <?= ($row['title_prefix'] ?? '') === $t ? 'selected' : '' ?>><?= $t === '' ? '—' : htmlspecialchars($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Optional. Used for "Mr./Ms. &lt;name&gt;" in letters.</small>
                    </div>
                    <div class="col-md-5 form-group"><label>Full Name</label>
                        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($row['name']) ?>" placeholder="e.g. Rutu Patel"></div>
                    <div class="col-md-5 form-group"><label>Email</label>
                        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($row['email']) ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group"><label>First name (for mid-letter references)</label>
                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($row['first_name'] ?? '') ?>" placeholder="auto-derived from Full Name if blank">
                    </div>
                    <div class="col-md-9"><small class="text-muted d-block mt-4">Only fill if the auto-derived first name is wrong. We use "{{title}} {{first_name}}" as the honorific (e.g. "Ms. Rutu") in letters after the first mention.</small></div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group"><label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone']) ?>"></div>
                    <div class="col-md-3 form-group"><label>Type</label>
                        <select name="employee_type" class="form-control">
                            <?php foreach ($types as $t): ?>
                                <option value="<?= $t ?>" <?= $row['employee_type']===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select></div>
                    <div class="col-md-3 form-group"><label>Role title</label>
                        <input type="text" name="role" class="form-control" required value="<?= htmlspecialchars($row['role']) ?>"></div>
                    <div class="col-md-3 form-group"><label>Role tag</label>
                        <select name="role_tag" class="form-control">
                            <?php foreach ($roleTags as $t): ?>
                                <option value="<?= $t ?>" <?= $row['role_tag']===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select></div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group"><label>Enrollment Number</label>
                        <input type="text" name="enrollment_number" class="form-control" value="<?= htmlspecialchars($row['enrollment_number'] ?? '') ?>" placeholder="e.g. 2203031050292"></div>
                    <div class="col-md-4 form-group"><label>Internship Type</label>
                        <select name="internship_type" class="form-control">
                            <?php foreach ($internshipTypes as $t): ?>
                                <option value="<?= htmlspecialchars($t) ?>" <?= ($row['internship_type'] ?? '') === $t ? 'selected' : '' ?>><?= $t === '' ? '—' : htmlspecialchars($t) ?></option>
                            <?php endforeach; ?>
                        </select></div>
                    <div class="col-md-4 form-group"><label>College / Institute</label>
                        <input type="text" name="college" class="form-control" value="<?= htmlspecialchars($row['college'] ?? '') ?>" placeholder="Printed on letter"></div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group"><label>Start date</label>
                        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($row['start_date'] ?? '') ?>"></div>
                    <div class="col-md-3 form-group"><label>End date</label>
                        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($row['end_date'] ?? '') ?>"></div>
                    <div class="col-md-3 form-group"><label>Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($statuses as $t): ?>
                                <option value="<?= $t ?>" <?= $row['status']===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select></div>
                    <div class="col-md-3 form-group"><label>Mentor</label>
                        <input type="text" name="mentor" class="form-control" value="<?= htmlspecialchars($row['mentor']) ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group"><label>GitHub repo</label>
                        <input type="url" name="github_repo" class="form-control" placeholder="https://github.com/..." value="<?= htmlspecialchars($row['github_repo']) ?>"></div>
                    <div class="col-md-6 form-group"><label>LinkedIn</label>
                        <input type="url" name="linkedin_url" class="form-control" value="<?= htmlspecialchars($row['linkedin_url']) ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group"><label>Stipend / Compensation (shown on offer letter)</label>
                        <input type="text" name="stipend" class="form-control" placeholder='e.g. "₹15,000/month" or "To be communicated separately"' value="<?= htmlspecialchars($row['stipend'] ?? '') ?>"></div>
                    <div class="col-md-6 form-group"><label>Reporting location (shown on offer letter)</label>
                        <input type="text" name="reporting_location" class="form-control" placeholder="e.g. Vadodara office / Remote" value="<?= htmlspecialchars($row['reporting_location'] ?? '') ?>"></div>
                </div>
                <div class="form-group"><label>Tasks summary (printed on completion letter)</label>
                    <textarea name="tasks_summary" class="form-control" rows="4"><?= htmlspecialchars($row['tasks_summary'] ?? '') ?></textarea></div>
                <div class="form-group"><label>Internal performance notes (never printed)</label>
                    <textarea name="performance_notes" class="form-control" rows="3"><?= htmlspecialchars($row['performance_notes'] ?? '') ?></textarea></div>

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="hr_interns.php" class="btn btn-outline-secondary">Back</a>
            </form>
        </div></div>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
