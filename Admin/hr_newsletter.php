<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$msg = $_GET['msg'] ?? '';
$rows = $con->query("SELECT id, email, name, source, status, created_at FROM newsletter_subscribers ORDER BY id DESC LIMIT 500")->fetch_all(MYSQLI_ASSOC);
$total      = (int) $con->query("SELECT COUNT(*) c FROM newsletter_subscribers")->fetch_assoc()['c'];
$subscribed = (int) $con->query("SELECT COUNT(*) c FROM newsletter_subscribers WHERE status='subscribed'")->fetch_assoc()['c'];
$last30     = (int) $con->query("SELECT COUNT(*) c FROM newsletter_subscribers WHERE created_at > (NOW() - INTERVAL 30 DAY)")->fetch_assoc()['c'];
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Newsletter')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Newsletter Subscribers')); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="row">
            <div class="col-md-3"><div class="card"><div class="card-body">
                <small class="text-muted text-uppercase">Total</small>
                <div style="font-size:28px;font-weight:700"><?= number_format($total) ?></div>
            </div></div></div>
            <div class="col-md-3"><div class="card"><div class="card-body">
                <small class="text-muted text-uppercase">Active subscribers</small>
                <div style="font-size:28px;font-weight:700"><?= number_format($subscribed) ?></div>
            </div></div></div>
            <div class="col-md-3"><div class="card"><div class="card-body">
                <small class="text-muted text-uppercase">New (30d)</small>
                <div style="font-size:28px;font-weight:700"><?= number_format($last30) ?></div>
            </div></div></div>
            <div class="col-md-3"><div class="card"><div class="card-body">
                <small class="text-muted text-uppercase">Send to subscribers</small>
                <a href="hr_bulk_email.php" class="btn btn-primary btn-sm mt-2">Bulk email →</a>
            </div></div></div>
        </div>

        <div class="card mt-3"><div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Recent subscribers</h5>
                <a href="hr_newsletter_export.php" class="btn btn-sm btn-outline-secondary">⬇ Export CSV</a>
            </div>
            <table class="table table-bordered table-sm">
                <thead><tr><th>ID</th><th>Email</th><th>Name</th><th>Source</th><th>Status</th><th>Subscribed</th></tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?= (int)$r['id'] ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($r['email']) ?>"><?= htmlspecialchars($r['email']) ?></a></td>
                        <td><?= htmlspecialchars($r['name']) ?: '—' ?></td>
                        <td><span class="badge badge-info"><?= htmlspecialchars($r['source']) ?></span></td>
                        <td><span class="badge badge-<?= $r['status']==='subscribed'?'success':'secondary' ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                        <td><small><?= htmlspecialchars($r['created_at']) ?></small></td>
                    </tr>
                <?php endforeach; if (!$rows): ?>
                    <tr><td colspan="6" class="text-muted">No subscribers yet — encourage signups via the footer form.</td></tr>
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
