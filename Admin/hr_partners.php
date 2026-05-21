<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg     = $_GET['msg']    ?? '';
$filter  = $_GET['status'] ?? '';

$sql = "SELECT * FROM key_partners";
$args = [];
if ($filter !== '') { $sql .= " WHERE status = ?"; $args[] = $filter; }
$sql .= " ORDER BY created_at DESC";

$stmt = $con->prepare($sql);
if ($args) $stmt->bind_param(str_repeat('s', count($args)), ...$args);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$statuses = ['prospect','invited','onboarded','active','paused','terminated','rejected'];
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Key Partners')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Key Partners')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="get" class="form-inline mb-0">
                <label class="mr-2">Status</label>
                <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">All</option>
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= $s ?>" <?= $filter===$s?'selected':'' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="text-muted">Total: <strong><?= count($rows) ?></strong></span>
            </form>
            <a href="hr_partner_edit.php" class="btn btn-primary">+ Add Partner</a>
        </div>

        <div class="card"><div class="card-body">
            <table class="table table-bordered table-sm">
                <thead class="text-center"><tr>
                    <th>Company</th><th>Contact</th><th>Country / Territory</th><th>Commission</th><th>Status</th><th></th>
                </tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($r['company_name']) ?></strong><br><small><?= htmlspecialchars($r['website']) ?></small></td>
                        <td><?= htmlspecialchars($r['contact_name']) ?><br><a href="mailto:<?= htmlspecialchars($r['email']) ?>"><?= htmlspecialchars($r['email']) ?></a></td>
                        <td><?= htmlspecialchars($r['country']) ?><?= $r['city'] ? ', ' . htmlspecialchars($r['city']) : '' ?><?php if ($r['territories']): ?><br><small><?= htmlspecialchars($r['territories']) ?></small><?php endif; ?></td>
                        <td><?= htmlspecialchars($r['commission_rate']) ?: '—' ?></td>
                        <td><?php $cls = ['active'=>'success','onboarded'=>'primary','invited'=>'info','prospect'=>'secondary','rejected'=>'danger','terminated'=>'danger','paused'=>'warning'][$r['status']] ?? 'secondary'; ?>
                            <span class="badge badge-<?= $cls ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                        <td class="text-nowrap">
                            <a href="hr_partner_detail.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-primary">Open</a>
                            <a href="hr_partner_edit.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-success">Edit</a>
                            <form method="post" action="hr_partner_delete.php" style="display:inline" onsubmit="return confirm('Delete partner <?= htmlspecialchars($r['company_name'], ENT_QUOTES) ?>?')">
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
