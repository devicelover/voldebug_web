<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$id = (int) ($_GET['id'] ?? 0);
$row = [
    'id' => 0, 'template_name' => '', 'title' => 'Certificate of Completion',
    'cert_kind' => 'completion', 'body_html' => '',
    'email_subject' => 'Your certificate from {{company}}', 'email_body' => '',
    'orientation' => 'landscape', 'qr_enabled' => 1, 'is_active' => 1,
];
if ($id) {
    $stmt = $con->prepare("SELECT * FROM certificate_templates WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($r = $stmt->get_result()->fetch_assoc()) $row = $r;
}
$kinds = ['completion','participation','achievement','merit','custom'];
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Edit Cert Template')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => $id ? 'Edit Cert Template' : 'New Cert Template')); ?>

        <div class="card"><div class="card-body">
            <form method="post" action="hr_certificate_template_save.php">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

                <div class="row">
                    <div class="col-md-6 form-group"><label>Template name (internal)</label>
                        <input type="text" name="template_name" class="form-control" required value="<?= htmlspecialchars($row['template_name']) ?>"></div>
                    <div class="col-md-3 form-group"><label>Kind</label>
                        <select name="cert_kind" class="form-control">
                            <?php foreach ($kinds as $k): ?><option value="<?= $k ?>" <?= $row['cert_kind']===$k?'selected':'' ?>><?= $k ?></option><?php endforeach; ?>
                        </select></div>
                    <div class="col-md-3 form-group"><label>Orientation</label>
                        <select name="orientation" class="form-control">
                            <option value="landscape" <?= $row['orientation']==='landscape'?'selected':'' ?>>Landscape</option>
                            <option value="portrait"  <?= $row['orientation']==='portrait'?'selected':'' ?>>Portrait</option>
                        </select></div>
                </div>

                <div class="form-group"><label>Big heading shown on the certificate</label>
                    <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($row['title']) ?>" placeholder="Certificate of Completion"></div>

                <div class="form-group">
                    <label>Body (HTML; supports placeholders)</label>
                    <textarea name="body_html" class="form-control" rows="10" style="font-family:Consolas,monospace;"><?= htmlspecialchars($row['body_html']) ?></textarea>
                    <small class="text-muted">Placeholders: <code>{{name}} {{honorific_name}} {{course}} {{date}} {{duration}} {{company}} {{partner_name}} {{custom1}}..{{custom5}}</code> · conditional: <code>{{#IF field}}…{{/IF}}</code></small>
                </div>

                <div class="form-group">
                    <label>Email subject (when emailing this cert)</label>
                    <input type="text" name="email_subject" class="form-control" value="<?= htmlspecialchars($row['email_subject']) ?>">
                </div>
                <div class="form-group">
                    <label>Email body</label>
                    <textarea name="email_body" class="form-control" rows="6"><?= htmlspecialchars($row['email_body']) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-3"><label><input type="checkbox" name="qr_enabled" value="1" <?= (int)$row['qr_enabled']===1?'checked':'' ?>> Embed QR verification</label></div>
                    <div class="col-md-3"><label><input type="checkbox" name="is_active"  value="1" <?= (int)$row['is_active']===1?'checked':''  ?>> Active</label></div>
                </div>

                <button type="submit" class="btn btn-primary">Save Template</button>
                <button type="submit" formaction="hr_certificate_preview.php" formtarget="_blank" formnovalidate class="btn btn-outline-info" title="Render a preview with a sample recipient in a new tab.">👁 Preview</button>
                <input type="hidden" name="mode" value="inline_template">
                <a href="hr_certificate_templates.php" class="btn btn-outline-secondary">Back</a>
            </form>
        </div></div>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
