<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$rows = $con->query("SELECT * FROM email_log ORDER BY id DESC LIMIT 200")->fetch_all(MYSQLI_ASSOC);
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Email Log')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Email Log (last 200)')); ?>
        <div class="card"><div class="card-body">
            <table class="table table-bordered table-sm">
                <thead><tr><th>ID</th><th>When</th><th>To</th><th>Subject</th><th>Context</th><th>Status</th><th>Error</th></tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?= (int)$r['id'] ?></td>
                        <td><?= htmlspecialchars($r['created_at']) ?></td>
                        <td><?= htmlspecialchars($r['to_email']) ?><br><small><?= htmlspecialchars($r['to_name']) ?></small></td>
                        <td><?= htmlspecialchars($r['subject']) ?></td>
                        <td><?= htmlspecialchars($r['context_type']) ?>/<?= (int)($r['context_id'] ?? 0) ?></td>
                        <td>
                            <?php $cls = ['sent'=>'success','failed'=>'danger','pending'=>'secondary'][$r['status']] ?? 'secondary'; ?>
                            <span class="badge badge-<?= $cls ?>"><?= htmlspecialchars($r['status']) ?></span>
                        </td>
                        <td><small class="text-danger"><?= htmlspecialchars(substr($r['error_message'] ?? '', 0, 200)) ?></small></td>
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
