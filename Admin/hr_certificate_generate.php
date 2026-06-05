<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$templates = $con->query("SELECT id, template_name, title, cert_kind FROM certificate_templates WHERE is_active = 1 ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$partners  = $con->query("SELECT id, name, subtitle FROM certificate_partners  WHERE is_active = 1 ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$msg = $_GET['msg'] ?? '';
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Generate Certificate')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Generate Single Certificate')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <?php if (!$templates): ?>
            <div class="alert alert-warning">No active templates. <a href="hr_certificate_template_edit.php">Create one first.</a></div>
        <?php else: ?>
        <div class="card"><div class="card-body">
            <form method="post" action="hr_certificate_generate_action.php" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <div class="col-md-6 form-group"><label>Template *</label>
                        <select name="template_id" class="form-control" required>
                            <?php foreach ($templates as $t): ?><option value="<?= (int)$t['id'] ?>">[<?= htmlspecialchars($t['cert_kind']) ?>] <?= htmlspecialchars($t['template_name']) ?></option><?php endforeach; ?>
                        </select></div>
                    <div class="col-md-6 form-group"><label>Partner institute (optional)</label>
                        <select name="partner_id" class="form-control">
                            <option value="0">— no partner —</option>
                            <?php foreach ($partners as $p): ?><option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?><?= $p['subtitle'] ? ' — ' . htmlspecialchars($p['subtitle']) : '' ?></option><?php endforeach; ?>
                        </select></div>
                </div>

                <h6 class="mt-3">Recipient</h6>
                <div class="row">
                    <div class="col-md-6 form-group"><label>Full name *</label>
                        <input type="text" name="recipient_name" class="form-control" required placeholder="e.g. Komal Shah"></div>
                    <div class="col-md-6 form-group"><label>Email (optional — for sending)</label>
                        <input type="email" name="recipient_email" class="form-control"></div>
                </div>

                <h6 class="mt-3">Program details</h6>
                <div class="row">
                    <div class="col-md-6 form-group"><label>Course / Program name *</label>
                        <input type="text" name="course_name" class="form-control" required placeholder="e.g. AI Fundamentals Bootcamp"></div>
                    <div class="col-md-3 form-group"><label>Completion date</label>
                        <input type="date" name="completion_date" class="form-control" value="<?= date('Y-m-d') ?>"></div>
                    <div class="col-md-3 form-group"><label>Duration (optional)</label>
                        <input type="text" name="duration" class="form-control" placeholder="e.g. 12 weeks"></div>
                </div>

                <h6 class="mt-3 mb-2">Custom fields (used as {{custom1}}…{{custom5}} placeholders)</h6>
                <div class="row">
                    <div class="col-md-4 form-group"><label>Custom 1</label><input type="text" name="custom1" class="form-control" placeholder="e.g. Grade A+"></div>
                    <div class="col-md-4 form-group"><label>Custom 2</label><input type="text" name="custom2" class="form-control"></div>
                    <div class="col-md-4 form-group"><label>Custom 3</label><input type="text" name="custom3" class="form-control"></div>
                </div>

                <div class="form-group">
                    <label class="mr-3"><input type="checkbox" name="include_signature" value="1" checked> Include signature</label>
                    <label class="mr-3"><input type="checkbox" name="include_stamp"     value="1" checked> Include digital stamp</label>
                    <label class="mr-3"><input type="checkbox" name="send_email"        value="1"> Email to recipient now</label>
                </div>

                <h6 class="mt-3"><label><input type="checkbox" id="toggleGuest" onclick="document.getElementById('guestBlock').style.display = this.checked ? 'block' : 'none'"> Add another signatory (guest speaker, head, mentor, etc.)</label></h6>
                <div id="guestBlock" style="display:none; background:#f8faf9; padding:14px 18px; border-radius:8px; margin-bottom:14px">
                    <p class="text-muted" style="font-size:13px">Used in addition to the default Voldebug signatory + any partner-institute signatory. Goes on this cert only.</p>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Guest name</label>
                            <input type="text" name="guest_name" class="form-control" placeholder="e.g. Dr. Priya Sharma"></div>
                        <div class="col-md-6 form-group"><label>Designation</label>
                            <input type="text" name="guest_designation" class="form-control" placeholder="e.g. Guest Speaker / Head of Programs"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Organization (optional)</label>
                            <input type="text" name="guest_organization" class="form-control" placeholder="e.g. IIT Bombay"></div>
                        <div class="col-md-6 form-group"><label>Signature image (optional)</label>
                            <input type="file" name="guest_signature_image" class="form-control" accept="image/*"></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Generate Certificate</button>
                <a href="hr_certificates.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div></div>
        <?php endif; ?>

    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
