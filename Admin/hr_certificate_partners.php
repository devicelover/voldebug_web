<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg  = $_GET['msg'] ?? '';
$rows = $con->query("SELECT * FROM certificate_partners ORDER BY name")->fetch_all(MYSQLI_ASSOC);
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Cert Partner Institutes')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Partner Institutes (for co-branded certs)')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="d-flex justify-content-between mb-3">
            <p class="text-muted mb-0">Institutes &amp; partners you co-organise programs with. Their logo + signatory appears on the certificate when selected.</p>
            <a href="hr_certificate_partner_edit.php" class="btn btn-primary">+ New Partner Institute</a>
        </div>

        <div class="card"><div class="card-body">
            <table class="table table-bordered">
                <thead class="text-center"><tr><th>Logo</th><th>Name</th><th>Signatory</th><th>Active</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td class="text-center">
                            <?php if ($r['logo'] && is_file(__DIR__ . '/images/cert_partners/' . $r['logo'])): ?>
                                <img src="images/cert_partners/<?= htmlspecialchars($r['logo']) ?>" alt="" style="max-height:40px">
                            <?php else: ?>—<?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($r['name']) ?></strong><br><small><?= htmlspecialchars($r['subtitle']) ?></small><?php if ($r['website']): ?><br><small><a href="<?= htmlspecialchars($r['website']) ?>" target="_blank"><?= htmlspecialchars($r['website']) ?></a></small><?php endif; ?></td>
                        <td><?= htmlspecialchars($r['signatory_name']) ?: '—' ?><br><small><?= htmlspecialchars($r['signatory_designation']) ?></small></td>
                        <td class="text-center"><?= (int)$r['is_active'] === 1 ? '✓' : '—' ?></td>
                        <td class="text-nowrap">
                            <a href="hr_certificate_partner_edit.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-success">Edit</a>
                            <form method="post" action="hr_certificate_partner_save.php" style="display:inline" onsubmit="return confirm('Delete this partner institute?')">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="delete_id" value="<?= (int)$r['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; if (!$rows): ?>
                    <tr><td colspan="5" class="text-muted">No partners added yet. Add one if you co-organise any programs.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div></div>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
