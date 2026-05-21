<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$range = $_GET['range'] ?? '7d';
$intervalSql = ['24h' => '1 DAY', '7d' => '7 DAY', '30d' => '30 DAY', '90d' => '90 DAY'][$range] ?? '7 DAY';

function q(mysqli $con, string $sql): array {
    $r = @$con->query($sql);
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}

$totalViews    = (int) $con->query("SELECT COUNT(*) c FROM page_views WHERE created_at > (NOW() - INTERVAL $intervalSql)")->fetch_assoc()['c'];
$uniqueIps     = (int) $con->query("SELECT COUNT(DISTINCT ip) c FROM page_views WHERE created_at > (NOW() - INTERVAL $intervalSql)")->fetch_assoc()['c'];
$totalToday    = (int) $con->query("SELECT COUNT(*) c FROM page_views WHERE created_at > (NOW() - INTERVAL 1 DAY)")->fetch_assoc()['c'];

$topPages = q($con, "
    SELECT path, COUNT(*) c, COUNT(DISTINCT ip) uniq
    FROM page_views
    WHERE created_at > (NOW() - INTERVAL $intervalSql)
    GROUP BY path
    ORDER BY c DESC LIMIT 20
");

$topReferrers = q($con, "
    SELECT
        CASE
            WHEN referrer = '' THEN '(direct / no referrer)'
            ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(referrer, '/', 3), '://', -1)
        END host,
        COUNT(*) c
    FROM page_views
    WHERE created_at > (NOW() - INTERVAL $intervalSql)
    GROUP BY host
    ORDER BY c DESC LIMIT 12
");

$topCountries = q($con, "
    SELECT COALESCE(NULLIF(country,''),'(unknown)') country, COUNT(*) c
    FROM page_views
    WHERE created_at > (NOW() - INTERVAL $intervalSql)
    GROUP BY country
    ORDER BY c DESC LIMIT 10
");

$dailyTrend = q($con, "
    SELECT DATE(created_at) d, COUNT(*) c, COUNT(DISTINCT ip) uniq
    FROM page_views
    WHERE created_at > (NOW() - INTERVAL $intervalSql)
    GROUP BY DATE(created_at)
    ORDER BY d ASC
");
$maxDay = max(array_map(fn($r) => (int)$r['c'], $dailyTrend) ?: [1]);
?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Traffic')); ?><?php include 'partials/head-css.php'; ?>
<style>
    .bar { display:inline-block; height:8px; background:#1a8f4a; border-radius:4px; vertical-align:middle; min-width:2px; }
    .bar-bg { background:#eef; border-radius:4px; height:8px; }
    .trend-bar { width:100%; background:#eef; border-radius:3px; overflow:hidden; height:24px; position:relative; margin:2px 0; }
    .trend-fill { background:#1a8f4a; height:100%; }
    .trend-bar span { position:absolute; left:6px; top:3px; font-size:11px; color:#fff; mix-blend-mode:difference; }
</style>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Traffic & Trends')); ?>

        <div class="mb-3">
            <a href="?range=24h" class="btn btn-sm <?= $range==='24h'?'btn-primary':'btn-outline-secondary' ?>">24h</a>
            <a href="?range=7d"  class="btn btn-sm <?= $range==='7d'?'btn-primary':'btn-outline-secondary' ?>">7 days</a>
            <a href="?range=30d" class="btn btn-sm <?= $range==='30d'?'btn-primary':'btn-outline-secondary' ?>">30 days</a>
            <a href="?range=90d" class="btn btn-sm <?= $range==='90d'?'btn-primary':'btn-outline-secondary' ?>">90 days</a>
        </div>

        <div class="row">
            <div class="col-md-4"><div class="card"><div class="card-body">
                <small class="text-muted text-uppercase">Total views (<?= $range ?>)</small>
                <div style="font-size:28px;font-weight:700"><?= number_format($totalViews) ?></div>
            </div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body">
                <small class="text-muted text-uppercase">Unique visitors (<?= $range ?>)</small>
                <div style="font-size:28px;font-weight:700"><?= number_format($uniqueIps) ?></div>
            </div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body">
                <small class="text-muted text-uppercase">Views (last 24h)</small>
                <div style="font-size:28px;font-weight:700"><?= number_format($totalToday) ?></div>
            </div></div></div>
        </div>

        <div class="row mt-3">
            <div class="col-md-7">
                <div class="card"><div class="card-body">
                    <h5>Top pages</h5>
                    <table class="table table-sm">
                        <thead><tr><th>Path</th><th class="text-right">Views</th><th class="text-right">Unique</th><th>Share</th></tr></thead>
                        <tbody>
                        <?php $maxC = max(array_map(fn($r) => (int)$r['c'], $topPages) ?: [1]); ?>
                        <?php foreach ($topPages as $p): $pct = round(((int)$p['c'] / $maxC) * 100); ?>
                            <tr>
                                <td><a href="<?= htmlspecialchars($p['path']) ?>" target="_blank"><?= htmlspecialchars($p['path']) ?></a></td>
                                <td class="text-right"><strong><?= (int)$p['c'] ?></strong></td>
                                <td class="text-right"><?= (int)$p['uniq'] ?></td>
                                <td style="width:180px"><div class="bar-bg"><div class="bar" style="width:<?= $pct ?>%"></div></div></td>
                            </tr>
                        <?php endforeach; if (!$topPages): ?>
                            <tr><td colspan="4" class="text-muted">No views in this period.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div></div>
            </div>
            <div class="col-md-5">
                <div class="card mb-3"><div class="card-body">
                    <h5>Top referrers</h5>
                    <table class="table table-sm">
                        <tbody>
                        <?php foreach ($topReferrers as $r): ?>
                            <tr><td><?= htmlspecialchars($r['host']) ?></td><td class="text-right"><strong><?= (int)$r['c'] ?></strong></td></tr>
                        <?php endforeach; if (!$topReferrers): ?>
                            <tr><td class="text-muted">No referrer data.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div></div>

                <div class="card"><div class="card-body">
                    <h5>Top countries</h5>
                    <table class="table table-sm">
                        <tbody>
                        <?php foreach ($topCountries as $r): ?>
                            <tr><td><?= htmlspecialchars($r['country']) ?></td><td class="text-right"><strong><?= (int)$r['c'] ?></strong></td></tr>
                        <?php endforeach; if (!$topCountries): ?>
                            <tr><td class="text-muted">Country tracking requires Cloudflare CF-IPCountry header.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div></div>
            </div>
        </div>

        <div class="card mt-3"><div class="card-body">
            <h5>Daily trend</h5>
            <?php foreach ($dailyTrend as $d): $pct = round(((int)$d['c'] / $maxDay) * 100); ?>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
                    <div style="width:90px;font-size:12px;color:#666"><?= htmlspecialchars($d['d']) ?></div>
                    <div class="trend-bar" style="flex:1"><div class="trend-fill" style="width:<?= $pct ?>%"></div><span><?= (int)$d['c'] ?> views · <?= (int)$d['uniq'] ?> uniq</span></div>
                </div>
            <?php endforeach; if (!$dailyTrend): ?>
                <p class="text-muted">No daily data yet.</p>
            <?php endif; ?>
        </div></div>

    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
