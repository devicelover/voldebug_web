<?php
// Rendered when bulk_process runs in dry_run mode. Shows what WOULD have been generated.
// Variables provided by caller: $preview (['valid' => [], 'skipped' => []]), $rows, $headerKeys,
// $batchName, $template, $courseDflt.
$validCount   = count($preview['valid']);
$skippedCount = count($preview['skipped']);
$totalCount   = $validCount + $skippedCount;
?>
<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<head><?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Bulk Preview')); ?><?php include 'partials/head-css.php'; ?></head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Bulk upload — preview')); ?>

        <div class="alert alert-info">
            <strong>This is a dry-run preview.</strong> Nothing has been saved or emailed.
            Review the parsing, fix any issues in your sheet, and re-upload with the <em>Process &amp; Generate</em> button when ready.
        </div>

        <div class="card mb-3"><div class="card-body">
            <h5>Summary</h5>
            <div class="row">
                <div class="col-md-3"><small class="text-muted text-uppercase">Batch</small><div><strong><?= htmlspecialchars($batchName) ?></strong></div></div>
                <div class="col-md-3"><small class="text-muted text-uppercase">Template</small><div><?= htmlspecialchars($template['template_name']) ?></div></div>
                <div class="col-md-2"><small class="text-muted text-uppercase">Total rows</small><div><strong><?= $totalCount ?></strong></div></div>
                <div class="col-md-2"><small class="text-muted text-uppercase">Would generate</small><div class="text-success"><strong><?= $validCount ?></strong></div></div>
                <div class="col-md-2"><small class="text-muted text-uppercase">Would skip</small><div class="<?= $skippedCount > 0 ? 'text-danger' : 'text-muted' ?>"><strong><?= $skippedCount ?></strong></div></div>
            </div>
            <hr>
            <small class="text-muted">Detected columns: <code><?= htmlspecialchars(implode(', ', $headerKeys)) ?></code></small>
        </div></div>

        <?php if ($skippedCount > 0): ?>
        <div class="card mb-3"><div class="card-body">
            <h5 class="text-danger">Rows that would be skipped</h5>
            <table class="table table-sm">
                <thead><tr><th style="width:70px">Row</th><th>Name</th><th>Reason</th></tr></thead>
                <tbody>
                <?php foreach ($preview['skipped'] as $s): ?>
                    <tr>
                        <td><?= (int) $s['row'] ?></td>
                        <td><?= htmlspecialchars($s['name'] ?? '—') ?></td>
                        <td class="text-danger"><?= htmlspecialchars($s['error']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div></div>
        <?php endif; ?>

        <div class="card"><div class="card-body">
            <h5>Rows that would be generated (<?= $validCount ?>)</h5>
            <?php if ($validCount === 0): ?>
                <p class="text-muted">Nothing would be generated. Fix the skipped rows above and re-upload.</p>
            <?php else: ?>
                <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead><tr><th style="width:60px">Row</th><th>Name</th><th>Email</th><th>Course</th><th>Completion</th></tr></thead>
                    <tbody>
                    <?php $shown = 0; foreach ($preview['valid'] as $v): if ($shown++ >= 30) break; ?>
                        <tr>
                            <td><?= (int) $v['row'] ?></td>
                            <td><?= htmlspecialchars($v['name']) ?></td>
                            <td><small><?= htmlspecialchars($v['email'] ?: '—') ?></small></td>
                            <td><?= htmlspecialchars($v['course']) ?></td>
                            <td><small><?= htmlspecialchars($v['date']) ?></small></td>
                        </tr>
                    <?php endforeach; if ($validCount > 30): ?>
                        <tr><td colspan="5" class="text-muted text-center">… and <?= $validCount - 30 ?> more.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </div></div>

        <div class="mt-3">
            <a href="hr_certificate_bulk.php" class="btn btn-primary">← Back to upload &amp; commit</a>
        </div>

    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
