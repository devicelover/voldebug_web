<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$id  = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$row = ['id' => 0, 'template_name' => '', 'letter_type' => 'joining', 'role_tag' => 'general',
        'email_subject' => '', 'email_body' => '', 'letter_body' => '', 'attach_pdf' => 1, 'is_active' => 1];
if ($id) {
    $stmt = $con->prepare("SELECT * FROM letter_templates WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($found = $res->fetch_assoc()) $row = $found;
}
$types = ['offer', 'joining', 'completion', 'experience', 'rejection', 'custom'];
$roles = ['general', 'cybersecurity_intern', 'web_dev_intern', 'web_designer_intern', 'ai_intern', 'marketing_intern', 'employee', 'contractor'];
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Edit Template')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => $id ? 'Edit Template' : 'New Template')); ?>

        <div class="card"><div class="card-body">
            <form method="post" action="hr_template_save.php">
<?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Template name</label>
                        <input type="text" name="template_name" class="form-control" required value="<?= htmlspecialchars($row['template_name']) ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Letter type</label>
                        <select name="letter_type" class="form-control">
                            <?php foreach ($types as $t): ?>
                                <option value="<?= $t ?>" <?= $row['letter_type']===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Role tag</label>
                        <select name="role_tag" class="form-control">
                            <?php foreach ($roles as $t): ?>
                                <option value="<?= $t ?>" <?= $row['role_tag']===$t?'selected':'' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email subject</label>
                    <input type="text" name="email_subject" class="form-control" value="<?= htmlspecialchars($row['email_subject']) ?>">
                </div>
                <div class="form-group">
                    <label>Email body (plain text; supports placeholders)</label>
                    <textarea name="email_body" class="form-control" rows="7"><?= htmlspecialchars($row['email_body']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Letter body (HTML; rendered inside the letterhead PDF)</label>
                    <textarea name="letter_body" class="form-control" rows="12" style="font-family:Consolas,monospace;"><?= htmlspecialchars($row['letter_body']) ?></textarea>
                    <small class="text-muted">Placeholders available: <code>{{name}} {{role}} {{start_date}} {{end_date}} {{tasks_summary}} {{github_repo}} {{company}} {{signatory}} {{signatory_role}} {{issue_date}} {{verify_url}}</code></small>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label class="mr-3"><input type="checkbox" name="attach_pdf" value="1" <?= ((int)$row['attach_pdf']===1)?'checked':'' ?>> Attach PDF</label>
                    </div>
                    <div class="col-md-3">
                        <label><input type="checkbox" name="is_active" value="1" <?= ((int)$row['is_active']===1)?'checked':'' ?>> Active</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Template</button>
                <a href="hr_templates.php" class="btn btn-outline-secondary">Back</a>
            </form>
        </div></div>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
