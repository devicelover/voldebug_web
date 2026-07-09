<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg         = $_GET['msg'] ?? '';
$batchFilter = isset($_GET['batch'])  ? (int)$_GET['batch']  : 0;
$statusFlt   = ($_GET['status'] ?? '') === 'revoked' ? 'revoked' : (($_GET['status'] ?? '') === 'active' ? 'active' : '');
$q           = trim((string) ($_GET['q'] ?? ''));
$perPage     = 50;
$page        = max(1, (int) ($_GET['page'] ?? 1));
$offset      = ($page - 1) * $perPage;

$where = []; $args = []; $types = '';
if ($batchFilter) { $where[] = 'c.batch_id = ?'; $args[] = $batchFilter; $types .= 'i'; }
if ($statusFlt === 'revoked') { $where[] = 'c.revoked = 1'; }
elseif ($statusFlt === 'active') { $where[] = 'c.revoked = 0'; }
if ($q !== '') {
    $where[] = '(c.recipient_name LIKE ? OR c.recipient_email LIKE ? OR c.course_name LIKE ? OR c.verify_token LIKE ?)';
    $wild = '%' . $q . '%';
    array_push($args, $wild, $wild, $wild, $wild); $types .= 'ssss';
}
$whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';

// Total count (for pagination).
$countStmt = $con->prepare("SELECT COUNT(*) c FROM certificates_issued c" . $whereSql);
if ($args) $countStmt->bind_param($types, ...$args);
$countStmt->execute();
$rowCount = (int) $countStmt->get_result()->fetch_assoc()['c'];
$pageCount = max(1, (int) ceil($rowCount / $perPage));

$sql = "SELECT c.*, t.template_name, t.title AS cert_title, p.name AS partner_name
        FROM certificates_issued c
        LEFT JOIN certificate_templates t ON t.id = c.template_id
        LEFT JOIN certificate_partners  p ON p.id = c.partner_id"
        . $whereSql
        . " ORDER BY c.id DESC LIMIT ? OFFSET ?";
$pageArgs = $args; array_push($pageArgs, $perPage, $offset); $pageTypes = $types . 'ii';
$stmt = $con->prepare($sql);
if ($pageArgs) $stmt->bind_param($pageTypes, ...$pageArgs);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$total      = (int) $con->query("SELECT COUNT(*) c FROM certificates_issued")->fetch_assoc()['c'];
$last30     = (int) $con->query("SELECT COUNT(*) c FROM certificates_issued WHERE created_at > (NOW() - INTERVAL 30 DAY)")->fetch_assoc()['c'];
$batches    = $con->query("SELECT id, name, recipient_count, success_count, failed_count, failed_details, status, created_at FROM certificate_batches ORDER BY id DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);

function buildPageUrl(int $p, string $q, int $batch, string $status): string {
    $qs = ['page' => $p];
    if ($q !== '')      $qs['q']      = $q;
    if ($batch)         $qs['batch']  = $batch;
    if ($status !== '') $qs['status'] = $status;
    return 'hr_certificates.php?' . http_build_query($qs);
}
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
                <thead><tr><th>ID</th><th>Name</th><th>Recipients</th><th>OK / Fail</th><th>Status</th><th>When</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($batches as $b): ?>
                    <tr>
                        <td>#<?= (int)$b['id'] ?></td>
                        <td><?= htmlspecialchars($b['name']) ?></td>
                        <td><?= (int)$b['recipient_count'] ?></td>
                        <td><?= (int)$b['success_count'] ?> / <?php if ((int)$b['failed_count'] > 0 && !empty($b['failed_details'])): ?><a href="#" data-toggle="collapse" data-target="#fd<?= (int)$b['id'] ?>" title="View failed rows"><span class="text-danger"><?= (int)$b['failed_count'] ?></span></a><?php else: ?><span class="text-danger"><?= (int)$b['failed_count'] ?></span><?php endif; ?></td>
                        <td><span class="badge badge-<?= $b['status']==='completed'?'success':($b['status']==='failed'?'danger':'info') ?>"><?= htmlspecialchars($b['status']) ?></span></td>
                        <td><small><?= htmlspecialchars($b['created_at']) ?></small></td>
                        <td><a href="?batch=<?= (int)$b['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    <?php if (!empty($b['failed_details'])): ?>
                        <tr class="collapse" id="fd<?= (int)$b['id'] ?>"><td colspan="7">
                            <pre style="max-height:180px;overflow:auto;background:#fafafa;padding:8px;font-size:11px;margin:0;"><?php
                                $errs = json_decode($b['failed_details'], true) ?: [];
                                foreach ($errs as $e) {
                                    echo 'row ' . ($e['row'] ?? '-') . '  ' . htmlspecialchars(($e['name'] ?? '') . '  →  ' . ($e['error'] ?? '')) . "\n";
                                }
                            ?></pre>
                        </td></tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div></div>
        <?php endif; ?>

        <div class="card"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Issued certificates<?= $batchFilter ? ' (Batch #' . $batchFilter . ')' : '' ?></h5>
                <span class="text-muted"><?= number_format($rowCount) ?> result<?= $rowCount === 1 ? '' : 's' ?></span>
            </div>

            <form method="get" class="form-inline mb-3" style="gap:8px">
                <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search name, email, course, ref…" class="form-control form-control-sm mr-2" style="min-width:260px">
                <select name="status" class="form-control form-control-sm mr-2">
                    <option value="">All statuses</option>
                    <option value="active"  <?= $statusFlt==='active'?'selected':'' ?>>Active only</option>
                    <option value="revoked" <?= $statusFlt==='revoked'?'selected':'' ?>>Revoked only</option>
                </select>
                <?php if ($batchFilter): ?>
                    <input type="hidden" name="batch" value="<?= (int)$batchFilter ?>">
                <?php endif; ?>
                <button class="btn btn-sm btn-primary mr-2">Filter</button>
                <?php if ($q !== '' || $statusFlt !== '' || $batchFilter): ?>
                    <a href="hr_certificates.php" class="btn btn-sm btn-outline-secondary">Clear</a>
                <?php endif; ?>
            </form>

            <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="text-center"><tr>
                    <th>Ref</th><th>Recipient</th><th>Course</th><th>Template</th><th>Partner</th><th>Issued</th><th>Actions</th>
                </tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr<?= (int)$r['revoked'] === 1 ? ' class="table-warning"' : '' ?>>
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
                                <form method="post" action="hr_certificate_revoke.php" style="display:inline" onsubmit="var r = prompt('Reason for revoking (shown on the verify page):', 'Issued in error.'); if (r === null || r.trim() === '') return false; this.querySelector('input[name=reason]').value = r; return true;">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                    <input type="hidden" name="reason" value="">
                                    <button class="btn btn-sm btn-outline-danger">Revoke</button>
                                </form>
                            <?php else: ?>
                                <span class="badge badge-warning" title="<?= htmlspecialchars($r['revoked_reason'] ?? '') ?>">revoked</span>
                                <form method="post" action="hr_certificate_revoke.php" style="display:inline" onsubmit="return confirm('Restore this certificate to active?')">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                    <input type="hidden" name="action" value="unrevoke">
                                    <button class="btn btn-sm btn-outline-success">Un-revoke</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; if (!$rows): ?>
                    <tr><td colspan="7" class="text-muted text-center py-4">
                        <?php if ($q !== '' || $statusFlt !== '' || $batchFilter): ?>
                            No certificates match this filter. <a href="hr_certificates.php">Clear filters</a>.
                        <?php else: ?>
                            No certificates issued yet. <a href="hr_certificate_generate.php">Generate one</a> or <a href="hr_certificate_bulk.php">upload a batch</a> to get started.
                        <?php endif; ?>
                    </td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            </div>

            <?php if ($pageCount > 1): ?>
            <nav class="d-flex justify-content-center mt-3"><ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= $page <= 1 ? '#' : buildPageUrl($page - 1, $q, $batchFilter, $statusFlt) ?>">&laquo; Prev</a></li>
                <?php
                    // Show a compact page window around current
                    $winStart = max(1, $page - 2); $winEnd = min($pageCount, $page + 2);
                    if ($winStart > 1) { echo '<li class="page-item"><a class="page-link" href="' . buildPageUrl(1, $q, $batchFilter, $statusFlt) . '">1</a></li>'; if ($winStart > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>'; }
                    for ($p = $winStart; $p <= $winEnd; $p++) {
                        $cls = $p === $page ? 'active' : '';
                        echo '<li class="page-item ' . $cls . '"><a class="page-link" href="' . buildPageUrl($p, $q, $batchFilter, $statusFlt) . '">' . $p . '</a></li>';
                    }
                    if ($winEnd < $pageCount) { if ($winEnd < $pageCount - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>'; echo '<li class="page-item"><a class="page-link" href="' . buildPageUrl($pageCount, $q, $batchFilter, $statusFlt) . '">' . $pageCount . '</a></li>'; }
                ?>
                <li class="page-item <?= $page >= $pageCount ? 'disabled' : '' ?>"><a class="page-link" href="<?= $page >= $pageCount ? '#' : buildPageUrl($page + 1, $q, $batchFilter, $statusFlt) ?>">Next &raquo;</a></li>
            </ul></nav>
            <?php endif; ?>
        </div></div>

    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
