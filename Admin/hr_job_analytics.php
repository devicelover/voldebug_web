<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
// Aggregate: per-job views (30-day), applications, conversion.
$rows = $con->query(
    "SELECT
        j.id, j.job_name,
        COALESCE(v30.views_30, 0) AS views_30,
        COALESCE(apps.apps_total, 0) AS apps_total,
        COALESCE(apps.apps_30, 0) AS apps_30
     FROM career j
     LEFT JOIN (
         SELECT job_id, COUNT(*) AS views_30
         FROM career_clicks
         WHERE kind = 'view' AND created_at > (NOW() - INTERVAL 30 DAY)
         GROUP BY job_id
     ) v30 ON v30.job_id = j.id
     LEFT JOIN (
         SELECT applied_job_id, COUNT(*) AS apps_total,
                SUM(CASE WHEN created_at > (NOW() - INTERVAL 30 DAY) THEN 1 ELSE 0 END) AS apps_30
         FROM client_career
         GROUP BY applied_job_id
     ) apps ON apps.applied_job_id = j.id
     ORDER BY views_30 DESC, apps_total DESC"
)->fetch_all(MYSQLI_ASSOC);
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Job Analytics')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Job Analytics')); ?>

        <p class="text-muted">Views are logged on the public job-details page (bots excluded). Applications are rows in <code>client_career</code>.</p>

        <div class="card"><div class="card-body">
            <table class="table table-bordered">
                <thead class="text-center"><tr>
                    <th>Job</th><th>Views (30d)</th><th>Applications (30d)</th><th>Applications (all time)</th><th>Conversion (30d)</th>
                </tr></thead>
                <tbody>
                <?php foreach ($rows as $r):
                    $conv = $r['views_30'] > 0 ? round(($r['apps_30'] / $r['views_30']) * 100, 1) : 0;
                ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($r['job_name']) ?></strong></td>
                        <td class="text-center"><?= (int)$r['views_30'] ?></td>
                        <td class="text-center"><?= (int)$r['apps_30'] ?></td>
                        <td class="text-center"><?= (int)$r['apps_total'] ?></td>
                        <td class="text-center">
                            <?php if ($r['views_30'] > 0): ?>
                                <span class="badge badge-<?= $conv > 5 ? 'success' : ($conv > 1 ? 'warning' : 'secondary') ?>"><?= $conv ?>%</span>
                            <?php else: ?>—<?php endif; ?>
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
