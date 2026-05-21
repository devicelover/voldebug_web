<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$id = (int) ($_GET['id'] ?? 0);
$row = [
    'id'=>0,'company_name'=>'','contact_name'=>'','title_prefix'=>'','first_name'=>'',
    'email'=>'','phone'=>'','country'=>'','city'=>'','website'=>'',
    'territories'=>'','commission_rate'=>'','status'=>'prospect','notes'=>'',
];
if ($id) {
    $stmt = $con->prepare("SELECT * FROM key_partners WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($r = $stmt->get_result()->fetch_assoc()) $row = $r;
}
$statuses = ['prospect','invited','onboarded','active','paused','terminated','rejected'];
$titles   = ['','Mr.','Ms.','Mrs.','Mx.','Dr.'];
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Edit Partner')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => $id ? 'Edit Key Partner' : 'Add Key Partner')); ?>
        <div class="card"><div class="card-body">
            <form method="post" action="hr_partner_save.php">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                <div class="row">
                    <div class="col-md-8 form-group"><label>Partner Company name</label>
                        <input type="text" name="company_name" class="form-control" required value="<?= htmlspecialchars($row['company_name']) ?>"></div>
                    <div class="col-md-4 form-group"><label>Website</label>
                        <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($row['website']) ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group"><label>Title</label>
                        <select name="title_prefix" class="form-control">
                            <?php foreach ($titles as $t): ?><option value="<?= $t ?>" <?= $row['title_prefix']===$t?'selected':'' ?>><?= $t === '' ? '—' : $t ?></option><?php endforeach; ?>
                        </select></div>
                    <div class="col-md-4 form-group"><label>Contact full name</label>
                        <input type="text" name="contact_name" class="form-control" required value="<?= htmlspecialchars($row['contact_name']) ?>"></div>
                    <div class="col-md-3 form-group"><label>First name (auto if blank)</label>
                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($row['first_name']) ?>"></div>
                    <div class="col-md-3 form-group"><label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone']) ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group"><label>Email</label>
                        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($row['email']) ?>"></div>
                    <div class="col-md-3 form-group"><label>Country</label>
                        <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($row['country']) ?>"></div>
                    <div class="col-md-3 form-group"><label>City</label>
                        <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($row['city']) ?>"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group"><label>Territories covered</label>
                        <input type="text" name="territories" class="form-control" placeholder="e.g. Germany, Austria, Switzerland" value="<?= htmlspecialchars($row['territories']) ?>"></div>
                    <div class="col-md-3 form-group"><label>Commission rate</label>
                        <input type="text" name="commission_rate" class="form-control" placeholder="e.g. 15% or Tiered" value="<?= htmlspecialchars($row['commission_rate']) ?>"></div>
                    <div class="col-md-3 form-group"><label>Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($statuses as $s): ?><option value="<?= $s ?>" <?= $row['status']===$s?'selected':'' ?>><?= $s ?></option><?php endforeach; ?>
                        </select></div>
                </div>
                <div class="form-group"><label>Internal notes</label>
                    <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($row['notes'] ?? '') ?></textarea></div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="hr_partners.php" class="btn btn-outline-secondary">Back</a>
            </form>
        </div></div>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
