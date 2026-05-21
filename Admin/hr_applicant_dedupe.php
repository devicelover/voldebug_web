<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg = $_GET['msg'] ?? '';

// Find duplicate groups: applicants sharing email (case-insensitive) OR (name + phone).
$rows = $con->query(
    "SELECT email, COUNT(*) AS dup_count, GROUP_CONCAT(id ORDER BY id SEPARATOR ',') AS ids
     FROM client_career
     WHERE email <> ''
     GROUP BY LOWER(email)
     HAVING dup_count > 1
     ORDER BY dup_count DESC, MIN(id) DESC"
)->fetch_all(MYSQLI_ASSOC);

// Pull full rows for the duplicate IDs.
$detail = [];
if ($rows) {
    $allIds = [];
    foreach ($rows as $r) $allIds = array_merge($allIds, explode(',', $r['ids']));
    $placeholders = implode(',', array_fill(0, count($allIds), '?'));
    $stmt = $con->prepare("SELECT id, name, email, phone, Position, created_at, status FROM client_career WHERE id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($allIds)), ...array_map('intval', $allIds));
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) $detail[(int)$row['id']] = $row;
}

$totalDupGroups = count($rows);
$totalDupRows   = array_sum(array_map(fn($r) => (int)$r['dup_count'], $rows));
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Dedupe Applicants')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Deduplicate Applicants')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="alert alert-info">
            <strong><?= $totalDupGroups ?></strong> duplicate group(s) — <strong><?= $totalDupRows ?></strong> total rows.
            Keep the row you want to retain and delete the others. The dedupe handler also keeps the resume of the row you keep.
        </div>

        <?php if (!$rows): ?>
            <div class="card"><div class="card-body text-center text-muted">No duplicates found. Public form has dedupe + CAPTCHA in place; you're clean!</div></div>
        <?php else: ?>
        <?php foreach ($rows as $r): $ids = explode(',', $r['ids']); ?>
            <div class="card mb-3"><div class="card-body">
                <h5><?= htmlspecialchars($r['email']) ?> <small class="text-muted">— <?= (int)$r['dup_count'] ?> entries</small></h5>
                <form method="post" action="hr_applicant_dedupe_action.php" onsubmit="return confirm('Keep the selected row and delete the others?')">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="email" value="<?= htmlspecialchars($r['email']) ?>">
                    <table class="table table-sm table-bordered mt-2">
                        <thead class="text-center"><tr><th width="60">Keep</th><th>ID</th><th>Name</th><th>Phone</th><th>Position</th><th>Status</th><th>Applied</th></tr></thead>
                        <tbody>
                        <?php foreach ($ids as $i => $idStr): $d = $detail[(int)$idStr] ?? null; if (!$d) continue; ?>
                            <tr>
                                <td class="text-center"><input type="radio" name="keep_id" value="<?= (int)$d['id'] ?>" <?= $i === 0 ? 'checked' : '' ?> required></td>
                                <td>#<?= (int)$d['id'] ?></td>
                                <td><?= htmlspecialchars($d['name']) ?></td>
                                <td><?= htmlspecialchars($d['phone']) ?></td>
                                <td><?= htmlspecialchars($d['Position']) ?></td>
                                <td><?= htmlspecialchars($d['status']) ?></td>
                                <td><?= htmlspecialchars($d['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-sm btn-danger">Keep selected, delete others</button>
                </form>
            </div></div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
