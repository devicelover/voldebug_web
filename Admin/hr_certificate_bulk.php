<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$templates = $con->query("SELECT id, template_name, title, cert_kind FROM certificate_templates WHERE is_active = 1 ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$partners  = $con->query("SELECT id, name, subtitle FROM certificate_partners  WHERE is_active = 1 ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$msg = $_GET['msg'] ?? '';
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Bulk Generate Certificates')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Bulk Generate Certificates')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="card mb-3"><div class="card-body">
            <h5>📋 Step 1 — Download the template</h5>
            <p>Use this CSV / Excel template. Fill one row per recipient. Required columns: <strong>name, course_name</strong>. Optional: email, completion_date (YYYY-MM-DD), duration, custom1, custom2, custom3.</p>
            <a href="hr_certificate_bulk_template.php" class="btn btn-outline-secondary btn-sm">⬇ Download CSV template</a>
            <small class="text-muted ml-2">You can also save your Excel sheet as <strong>.xlsx</strong> directly — both supported.</small>
        </div></div>

        <div class="card"><div class="card-body">
            <h5>⬆ Step 2 — Upload &amp; generate</h5>
            <?php if (!$templates): ?>
                <div class="alert alert-warning">No active templates. <a href="hr_certificate_template_edit.php">Create one first.</a></div>
            <?php else: ?>
            <form method="post" action="hr_certificate_bulk_process.php" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <div class="col-md-6 form-group"><label>Batch name *</label>
                        <input type="text" name="batch_name" class="form-control" required placeholder="e.g. AI Bootcamp — Batch 7"></div>
                    <div class="col-md-6 form-group"><label>Course / Program name *</label>
                        <input type="text" name="course_name" class="form-control" required placeholder="Used as default if the row doesn't have its own"></div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group"><label>Template *</label>
                        <select name="template_id" class="form-control" required>
                            <?php foreach ($templates as $t): ?><option value="<?= (int)$t['id'] ?>">[<?= htmlspecialchars($t['cert_kind']) ?>] <?= htmlspecialchars($t['template_name']) ?></option><?php endforeach; ?>
                        </select></div>
                    <div class="col-md-6 form-group"><label>Partner institute (optional)</label>
                        <select name="partner_id" class="form-control">
                            <option value="0">— no partner —</option>
                            <?php foreach ($partners as $p): ?><option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option><?php endforeach; ?>
                        </select></div>
                </div>

                <div class="form-group">
                    <label>Upload .csv or .xlsx</label>
                    <input type="file" name="sheet" class="form-control" accept=".csv,.xlsx,.xls" required>
                </div>

                <div class="form-group">
                    <label class="mr-3"><input type="checkbox" name="include_signature" value="1" checked> Include signature</label>
                    <label class="mr-3"><input type="checkbox" name="include_stamp"     value="1" checked> Include digital stamp</label>
                    <label class="mr-3"><input type="checkbox" name="send_email"        value="1"> Email each cert (recipients with valid emails)</label>
                </div>

                <h6 class="mt-3"><label><input type="checkbox" id="toggleGuestB" onclick="document.getElementById('guestBlockB').style.display = this.checked ? 'block' : 'none'"> Add another signatory to all certificates in this batch (guest speaker, head, mentor)</label></h6>
                <div id="guestBlockB" style="display:none; background:#f8faf9; padding:14px 18px; border-radius:8px; margin-bottom:14px">
                    <p class="text-muted" style="font-size:13px">Applied uniformly to every certificate in this batch, alongside the Voldebug signatory and any partner-institute signatory.</p>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Guest name</label>
                            <input type="text" name="guest_name" class="form-control" placeholder="e.g. Dr. Priya Sharma"></div>
                        <div class="col-md-6 form-group"><label>Designation</label>
                            <input type="text" name="guest_designation" class="form-control" placeholder="e.g. Guest Speaker"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Organization (optional)</label>
                            <input type="text" name="guest_organization" class="form-control" placeholder="e.g. IIT Bombay"></div>
                        <div class="col-md-6 form-group"><label>Signature image (optional)</label>
                            <input type="file" name="guest_signature_image" class="form-control" accept="image/*"></div>
                    </div>
                </div>

                <button type="submit" name="dry_run" value="1" class="btn btn-outline-info" title="Parse and validate the sheet without generating anything. Recommended before large batches.">🔍 Preview parsing</button>
                <button type="submit" class="btn btn-primary">Process &amp; Generate</button>
                <a href="hr_certificates.php" class="btn btn-outline-secondary">Back</a>
            </form>
            <p class="text-muted mt-2" style="font-size:12px">Tip: click <strong>Preview parsing</strong> first to see column mapping, per-row validation, and duplicate detection — before committing to a full batch.</p>
            <?php endif; ?>
        </div></div>

    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
