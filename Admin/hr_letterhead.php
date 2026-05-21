<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$msg = $_GET['msg'] ?? '';
$row = $con->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc() ?: [];
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Letterhead')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Letterhead & Signatory')); ?>

                <?php if ($msg === 'saved'): ?>
                    <div class="alert alert-success">Letterhead settings saved.</div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-xl-10 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <p class="text-muted">
                                    These assets appear on every generated letter PDF (offer, joining, completion, experience).
                                    Upload a logo, the HR signatory's signature image, and a digital stamp. Signatory details
                                    print under the signature.
                                </p>
                                <form method="post" action="hr_letterhead_save.php" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label>Company Logo</label>
                                            <?php if (!empty($row['logo'])): ?>
                                                <div class="mb-2"><img src="images/letterhead/<?= htmlspecialchars($row['logo']) ?>" style="max-height:70px; background:#f5f7fb; padding:4px; border-radius:6px"></div>
                                            <?php endif; ?>
                                            <input type="file" name="logo" accept="image/*" class="form-control">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Signature Image</label>
                                            <?php if (!empty($row['signature_image'])): ?>
                                                <div class="mb-2"><img src="images/letterhead/<?= htmlspecialchars($row['signature_image']) ?>" style="max-height:70px; background:#f5f7fb; padding:4px; border-radius:6px"></div>
                                            <?php endif; ?>
                                            <input type="file" name="signature_image" accept="image/*" class="form-control">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Digital Stamp</label>
                                            <?php if (!empty($row['stamp_image'])): ?>
                                                <div class="mb-2"><img src="images/letterhead/<?= htmlspecialchars($row['stamp_image']) ?>" style="max-height:90px; background:#f5f7fb; padding:4px; border-radius:6px"></div>
                                            <?php endif; ?>
                                            <input type="file" name="stamp_image" accept="image/*" class="form-control">
                                        </div>
                                    </div>

                                    <hr>

                                    <h6 class="mt-2 mb-3">Company identity</h6>
                                    <div class="row">
                                        <div class="col-md-8 form-group">
                                            <label>Full legal company name</label>
                                            <input type="text" name="company_legal_name" class="form-control" value="<?= htmlspecialchars($row['company_legal_name'] ?? '') ?>" placeholder="e.g. Voldebug Innovations Pvt. Ltd." required>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Brand color (hex)</label>
                                            <input type="text" name="brand_color" class="form-control" value="<?= htmlspecialchars($row['brand_color'] ?? '#1a8f4a') ?>" pattern="^#[0-9A-Fa-f]{6}$" placeholder="#1a8f4a">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>CIN / Corporate ID</label>
                                            <input type="text" name="cin" class="form-control" value="<?= htmlspecialchars($row['cin'] ?? '') ?>" placeholder="U72900GJ2022PTC134191">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Website (shown in header)</label>
                                            <input type="text" name="website" class="form-control" value="<?= htmlspecialchars($row['website'] ?? '') ?>" placeholder="www.voldebug.in">
                                        </div>
                                    </div>

                                    <h6 class="mt-3 mb-3">Signatory &amp; contact</h6>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Signatory Name</label>
                                            <input type="text" name="signatory_name" class="form-control" value="<?= htmlspecialchars($row['signatory_name'] ?? '') ?>" placeholder="e.g. Meet Bisht" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Signatory Designation</label>
                                            <input type="text" name="signatory_designation" class="form-control" value="<?= htmlspecialchars($row['signatory_designation'] ?? '') ?>" placeholder="e.g. Director" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label>HR Email (at bottom of letter)</label>
                                            <input type="email" name="hr_email" class="form-control" value="<?= htmlspecialchars($row['hr_email'] ?? 'hr@voldebug.in') ?>" required>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Admin email (header block)</label>
                                            <input type="email" name="admin_email" class="form-control" value="<?= htmlspecialchars($row['admin_email'] ?? 'admin@voldebug.in') ?>">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Phone (header block)</label>
                                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone'] ?? '') ?>" placeholder="+91 98765 43210">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Full Letterhead Address (multi-line allowed)</label>
                                        <textarea name="letterhead_address" class="form-control" rows="3" placeholder="T/F-342, Siddharth Magnum Plus, Besides Susen - Tarsali Ring Rd, Vadodara, Gujarat 390009"><?= htmlspecialchars($row['letterhead_address'] ?? '') ?></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save Letterhead</button>
                                    <a href="hr_letterhead_preview.php" target="_blank" class="btn btn-outline-secondary">Preview sample letter</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'partials/footer.php'; ?>
    </div>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
