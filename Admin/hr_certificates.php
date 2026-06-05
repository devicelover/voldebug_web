<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg = $_GET['msg'] ?? '';
$batchFilter = isset($_GET['batch']) ? (int)$_GET['batch'] : 0;

$sql = "SELECT c.*, t.template_name, t.title AS cert_title, p.name AS partner_name
        FROM certificates_issued c
        LEFT JOIN certificate_templates t ON t.id = c.template_id
        LEFT JOIN certificate_partners  p ON p.id = c.partner_id";
$args = [];
if ($batchFilter) { $sql .= " WHERE c.batch_id = ?"; $args[] = $batchFilter; }
$sql .= " ORDER BY c.id DESC LIMIT 500";

$stmt = $con->prepare($sql);
if ($args) $stmt->bind_param(str_repeat('i', count($args)), ...$args);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$total      = (int) $con->query("SELECT COUNT(*) c FROM certificates_issued")->fetch_assoc()['c'];
$last30     = (int) $con->query("SELECT COUNT(*) c FROM certificates_issued WHERE created_at > (NOW() - INTERVAL 30 DAY)")->fetch_assoc()['c'];
$batches    = $con->query("SELECT id, name, recipient_count, status, created_at FROM certificate_batches ORDER BY id DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Certificates')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'E-Certificates Issued')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="row mb-3">
            <div class="col-md-3"><div class="card"><div class="card-body">
                <small class="text-muted text-uppercase">Total issued</small>
                <div style="font-size:26px;font-weight:700"><?= number_format($total) ?></div>
            </div></div></div>
            <div class="col-md-3"><div class="card"><div class="card-body">
                <small class="text-muted text-uppercase">New (30d)</small>
                <div style="font-size:26px;font-weight:700"><?= number_format($last30) ?></div>
            </div></div></div>
            <div class="col-md-6"><div class="card"><div class="card-body">
                <h6 class="mb-2">Quick actions</h6>
                <a href="hr_certificate_generate.php" class="btn btn-primary btn-sm">+ Generate single</a>
                <a href="hr_certificate_bulk.php"     class="btn btn-success btn-sm">⬆ Bulk upload</a>
                <a href="hr_certificate_templates.php" class="btn btn-outline-secondary btn-sm">Templates</a>
                <a href="hr_certificate_partners.php"  class="btn btn-outline-secondary btn-sm">Partner Institutes</a>
            </div></div></div>
        </div>

        <?php if ($batches): ?>
        <div class="card mb-3"><div class="card-body">
            <h5>Recent batches</h5>
            <table class="table table-sm">
                <thead><tr><th>ID</th><th>Name</th><th>Recipients</th><th>Status</th><th>When</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($batches as $b): ?>
                    <tr>
                        <td>#<?= (int)$b['id'] ?></td>
                        <td><?= htmlspecialchars($b['name']) ?></td>
                        <td><?= (int)$b['recipient_count'] ?></td>
                        <td><span class="badge badge-<?= $b['status']==='completed'?'success':'info' ?>"><?= htmlspecialchars($b['status']) ?></span></td>
                        <td><small><?= htmlspecialchars($b['created_at']) ?></small></td>
                        <td><a href="?batch=<?= (int)$b['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div></div>
        <?php endif; ?>

        <div class="card"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Issued certificates<?= $batchFilter ? ' (Batch #' . $batchFilter . ')' : '' ?></h5>
                <?php if ($batchFilter): ?><a href="hr_certificates.php" class="btn btn-sm btn-outline-secondary">Clear filter</a><?php endif; ?>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="text-center"><tr>
                    <th>Ref</th><th>Recipient</th><th>Course</th><th>Template</th><th>Partner</th><th>Issued</th><th>Actions</th>
                </tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><code>VDB-<?= htmlspecialchars(substr($r['verify_token'], 0, 10)) ?>…</code></td>
                        <td><?= htmlspecialchars($r['recipient_name']) ?><br><small><?= htmlspecialchars($r['recipient_email']) ?></small></td>
                        <td><?= htmlspecialchars($r['course_name']) ?></td>
                        <td><small><?= htmlspecialchars($r['template_name'] ?? '—') ?></small></td>
                        <td><small><?= htmlspecialchars($r['partner_name'] ?? '—') ?></small></td>
                        <td><small><?= htmlspecialchars(substr((string)$r['created_at'], 0, 16)) ?></small></td>
                        <td class="text-nowrap">
                            <a href="hr_certificate_download.php?id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline-primary">PDF</a>
                            <?php if ($r['recipient_email'] && (int)$r['revoked'] !== 1): ?>
                                <form method="post" action="hr_certificate_email.php" style="display:inline" onsubmit="return confirm('Email to <?= htmlspecialchars($r['recipient_email'], ENT_QUOTES) ?>?')">
                                    <?php echo csrf_field(); ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                    <button class="btn btn-sm btn-outline-success">Email</button>
                                </form>
                            <?php endif; ?>
                            <a href="../verify.php?t=<?= urlencode($r['verify_token']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Verify</a>
                            <?php if ((int)$r['revoked'] !== 1): ?>
                                <form method="post" action="hr_certificate_revoke.php" style="display:inline" onsubmit="return confirm('Revoke this certificate? The verify page will show it as revoked.')">
                                    <?php echo csrf_field(); ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger">Revoke</button>
                                </form>
                            <?php else: ?><span class="badge badge-warning">revoked</span><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; if (!$rows): ?>
                    <tr><td colspan="7" class="text-muted">No certificates issued yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div></div>

    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
