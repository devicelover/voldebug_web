<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$id = (int) ($_GET['id'] ?? 0);
$row = ['id'=>0,'business_name'=>'','contact_name'=>'','email'=>'','phone'=>'','website'=>'',
        'industry'=>'','monthly_budget'=>'','services'=>'','source'=>'','status'=>'new','notes'=>''];
if ($id) {
    $stmt = $con->prepare("SELECT * FROM seo_leads WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($r = $stmt->get_result()->fetch_assoc()) $row = $r;
}
$statuses = ['new','contacted','qualified','proposal_sent','won','lost'];
$sources  = ['','website','linkedin','referral','cold','event'];
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Edit SEO Lead')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => $id ? 'Edit SEO Lead' : 'Add SEO Lead')); ?>
        <div class="card"><div class="card-body">
            <form method="post" action="hr_seo_save.php">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                <div class="row">
                    <div class="col-md-6 form-group"><label>Business name</label>
                        <input type="text" name="business_name" class="form-control" required value="<?= htmlspecialchars($row['business_name']) ?>"></div>
                    <div class="col-md-6 form-group"><label>Website</label>
                        <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($row['website']) ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group"><label>Contact name</label>
                        <input type="text" name="contact_name" class="form-control" value="<?= htmlspecialchars($row['contact_name']) ?>"></div>
                    <div class="col-md-4 form-group"><label>Email</label>
                        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($row['email']) ?>"></div>
                    <div class="col-md-4 form-group"><label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone']) ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group"><label>Industry</label>
                        <input type="text" name="industry" class="form-control" value="<?= htmlspecialchars($row['industry']) ?>"></div>
                    <div class="col-md-3 form-group"><label>Monthly budget</label>
                        <input type="text" name="monthly_budget" class="form-control" placeholder="₹10k-25k" value="<?= htmlspecialchars($row['monthly_budget']) ?>"></div>
                    <div class="col-md-3 form-group"><label>Services interested</label>
                        <input type="text" name="services" class="form-control" placeholder="SEO, Google Ads" value="<?= htmlspecialchars($row['services']) ?>"></div>
                    <div class="col-md-3 form-group"><label>Source</label>
                        <select name="source" class="form-control">
                            <?php foreach ($sources as $s): ?><option value="<?= $s ?>" <?= $row['source']===$s?'selected':'' ?>><?= $s === '' ? '—' : $s ?></option><?php endforeach; ?>
                        </select></div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group"><label>Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($statuses as $s): ?><option value="<?= $s ?>" <?= $row['status']===$s?'selected':'' ?>><?= $s ?></option><?php endforeach; ?>
                        </select></div>
                </div>
                <div class="form-group"><label>Notes</label>
                    <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($row['notes'] ?? '') ?></textarea></div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="hr_seo_leads.php" class="btn btn-outline-secondary">Back</a>
            </form>
        </div></div>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
