<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg = $_GET['msg'] ?? '';
$res = $con->query("SELECT id, template_name, letter_type, role_tag, is_active, updated_at FROM letter_templates ORDER BY letter_type, role_tag");
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Letter Templates')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Letter Templates')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="text-muted mb-0">Templates power the PDFs and emails sent to interns/employees. Placeholders: <code>{{name}} {{role}} {{start_date}} {{end_date}} {{tasks_summary}} {{github_repo}} {{company}} {{signatory}} {{verify_url}}</code></p>
            <a href="hr_template_edit.php" class="btn btn-primary">+ New Template</a>
        </div>

        <div class="card"><div class="card-body">
            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th>Name</th><th>Type</th><th>Role tag</th><th>Active</th><th>Updated</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['template_name']) ?></td>
                            <td><span class="badge badge-info"><?= htmlspecialchars($r['letter_type']) ?></span></td>
                            <td><code><?= htmlspecialchars($r['role_tag']) ?></code></td>
                            <td class="text-center"><?= ((int)$r['is_active'] === 1) ? '✓' : '—' ?></td>
                            <td><?= htmlspecialchars($r['updated_at']) ?></td>
                            <td>
                                <a href="hr_template_edit.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-success">Edit</a>
                                <form method="post" action="hr_template_save.php" style="display:inline">
<?php echo csrf_field(); ?>
                                    <input type="hidden" name="delete_id" value="<?= (int)$r['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this template?')">Delete</button>
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
