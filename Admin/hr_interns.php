<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg = $_GET['msg'] ?? '';
$rows = $con->query(
    "SELECT i.*, COUNT(l.id) AS letters_count
     FROM interns i
     LEFT JOIN letters_issued l ON l.intern_id = i.id
     GROUP BY i.id
     ORDER BY i.created_at DESC"
)->fetch_all(MYSQLI_ASSOC);
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Interns & Employees')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Interns & Employees')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="text-muted mb-0">All interns, employees, contractors. Promote applicants from the Applicants page, or add directly below.</p>
            <a href="hr_intern_edit.php" class="btn btn-primary">+ Add Directly</a>
        </div>

        <div class="card"><div class="card-body">
            <table class="table table-bordered">
                <thead class="text-center"><tr>
                    <th>Name</th><th>Type</th><th>Role</th><th>Tag</th><th>Start</th><th>End</th><th>Status</th><th>GitHub</th><th>Letters</th><th>Actions</th>
                </tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($r['name']) ?></strong><br><small><?= htmlspecialchars($r['email']) ?></small></td>
                        <td><span class="badge badge-secondary"><?= htmlspecialchars($r['employee_type']) ?></span></td>
                        <td><?= htmlspecialchars($r['role']) ?></td>
                        <td><code><?= htmlspecialchars($r['role_tag']) ?></code></td>
                        <td><?= htmlspecialchars($r['start_date'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['end_date'] ?? '') ?></td>
                        <td>
                            <?php
                            $s = $r['status'];
                            $cls = ['active' => 'success', 'completed' => 'primary', 'terminated' => 'danger', 'on_hold' => 'warning'][$s] ?? 'secondary';
                            ?>
                            <span class="badge badge-<?= $cls ?>"><?= htmlspecialchars($s) ?></span>
                        </td>
                        <td class="text-center">
                            <?php if (!empty($r['github_repo'])): ?>
                                <a href="<?= htmlspecialchars($r['github_repo']) ?>" target="_blank">repo</a>
                            <?php else: ?>—<?php endif; ?>
                        </td>
                        <td class="text-center"><?= (int) $r['letters_count'] ?></td>
                        <td class="text-nowrap">
                            <a href="hr_intern_detail.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-primary">Open</a>
                            <a href="hr_intern_edit.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-success">Edit</a>
                            <form method="post" action="hr_intern_delete.php" style="display:inline" onsubmit="return confirm('Delete <?= htmlspecialchars($r['name'], ENT_QUOTES) ?>? This also removes their letters and check-ins.')">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
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
