<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$id = (int) ($_GET['id'] ?? 0);
$row = ['id'=>0,'name'=>'','subtitle'=>'','logo'=>'','website'=>'',
        'signatory_name'=>'','signatory_designation'=>'','signature_image'=>'','is_active'=>1];
if ($id) {
    $stmt = $con->prepare("SELECT * FROM certificate_partners WHERE id = ?");
    $stmt->bind_param('i', $id); $stmt->execute();
    if ($r = $stmt->get_result()->fetch_assoc()) $row = $r;
}
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Edit Partner Institute')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => $id ? 'Edit Partner Institute' : 'New Partner Institute')); ?>
        <div class="card"><div class="card-body">
            <form method="post" action="hr_certificate_partner_save.php" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

                <div class="row">
                    <div class="col-md-8 form-group"><label>Institute / Partner name</label>
                        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($row['name']) ?>" placeholder="e.g. Marwadi University · Department of CSE"></div>
                    <div class="col-md-4 form-group"><label>Website</label>
                        <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($row['website']) ?>"></div>
                </div>

                <div class="form-group"><label>Subtitle (shown below name on the certificate)</label>
                    <input type="text" name="subtitle" class="form-control" value="<?= htmlspecialchars($row['subtitle']) ?>" placeholder="e.g. School of Computer Studies"></div>

                <div class="row">
                    <div class="col-md-6 form-group"><label>Logo</label>
                        <?php if (!empty($row['logo'])): ?><div class="mb-2"><img src="images/cert_partners/<?= htmlspecialchars($row['logo']) ?>" style="max-height:60px"></div><?php endif; ?>
                        <input type="file" name="logo" class="form-control" accept="image/*"></div>
                    <div class="col-md-6 form-group"><label>Authorised signature image (optional)</label>
                        <?php if (!empty($row['signature_image'])): ?><div class="mb-2"><img src="images/cert_partners/<?= htmlspecialchars($row['signature_image']) ?>" style="max-height:48px"></div><?php endif; ?>
                        <input type="file" name="signature_image" class="form-control" accept="image/*"></div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group"><label>Signatory name</label>
                        <input type="text" name="signatory_name" class="form-control" value="<?= htmlspecialchars($row['signatory_name']) ?>" placeholder="e.g. Dr. Priya Patel"></div>
                    <div class="col-md-6 form-group"><label>Signatory designation</label>
                        <input type="text" name="signatory_designation" class="form-control" value="<?= htmlspecialchars($row['signatory_designation']) ?>" placeholder="e.g. Head of Department"></div>
                </div>

                <div class="form-group"><label><input type="checkbox" name="is_active" value="1" <?= (int)$row['is_active']===1?'checked':'' ?>> Active</label></div>

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="hr_certificate_partners.php" class="btn btn-outline-secondary">Back</a>
            </form>
        </div></div>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
