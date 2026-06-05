<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg  = $_GET['msg']  ?? '';
$rows = $con->query("SELECT * FROM certificate_templates ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Certificate Templates')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Certificate Templates')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="d-flex justify-content-between mb-3">
            <p class="text-muted mb-0">Templates power both single-recipient and bulk certificate generation.
                Body supports placeholders: <code>{{name}} {{course}} {{date}} {{duration}} {{partner_name}} {{honorific_name}} {{custom1}}..{{custom5}}</code> + <code>{{#IF field}}…{{/IF}}</code> blocks.</p>
            <a href="hr_certificate_template_edit.php" class="btn btn-primary">+ New Template</a>
        </div>

        <div class="card"><div class="card-body">
            <table class="table table-bordered">
                <thead class="text-center"><tr>
                    <th>Title</th><th>Kind</th><th>Orientation</th><th>QR</th><th>Active</th><th>Updated</th><th></th>
                </tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($r['template_name']) ?></strong><br><small><?= htmlspecialchars($r['title']) ?></small></td>
                        <td><span class="badge badge-info"><?= htmlspecialchars($r['cert_kind']) ?></span></td>
                        <td><?= htmlspecialchars($r['orientation']) ?></td>
                        <td class="text-center"><?= (int)$r['qr_enabled'] === 1 ? '✓' : '—' ?></td>
                        <td class="text-center"><?= (int)$r['is_active']  === 1 ? '✓' : '—' ?></td>
                        <td><small><?= htmlspecialchars($r['updated_at']) ?></small></td>
                        <td class="text-nowrap">
                            <a href="hr_certificate_template_edit.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-success">Edit</a>
                            <form method="post" action="hr_certificate_template_save.php" style="display:inline" onsubmit="return confirm('Delete this template?')">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="delete_id" value="<?= (int)$r['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div></div>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
