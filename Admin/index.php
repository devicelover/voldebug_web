<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
// Stats (best-effort — any missing table is silently zero).
function safe_count(mysqli $con, string $sql): int {
    $r = @$con->query($sql);
    if (!$r) return 0;
    return (int) ($r->fetch_assoc()['c'] ?? 0);
}

$stats = [
    'applicants_total'   => safe_count($con, "SELECT COUNT(*) c FROM client_career"),
    'applicants_30d'     => safe_count($con, "SELECT COUNT(*) c FROM client_career WHERE created_at > (NOW() - INTERVAL 30 DAY)"),
    'applicants_pending' => safe_count($con, "SELECT COUNT(*) c FROM client_career WHERE status IN ('applied','reviewed')"),
    'interns_active'     => safe_count($con, "SELECT COUNT(*) c FROM interns WHERE status = 'active'"),
    'interns_completed'  => safe_count($con, "SELECT COUNT(*) c FROM interns WHERE status = 'completed'"),
    'partners_active'    => safe_count($con, "SELECT COUNT(*) c FROM key_partners WHERE status IN ('active','onboarded')"),
    'partners_prospect'  => safe_count($con, "SELECT COUNT(*) c FROM key_partners WHERE status IN ('prospect','invited')"),
    'seo_leads_open'     => safe_count($con, "SELECT COUNT(*) c FROM seo_leads WHERE status NOT IN ('won','lost')"),
    'letters_issued'     => safe_count($con, "SELECT COUNT(*) c FROM letters_issued WHERE revoked = 0"),
    'emails_sent_30d'    => safe_count($con, "SELECT COUNT(*) c FROM email_log WHERE status = 'sent' AND created_at > (NOW() - INTERVAL 30 DAY)"),
    'emails_failed_30d'  => safe_count($con, "SELECT COUNT(*) c FROM email_log WHERE status = 'failed' AND created_at > (NOW() - INTERVAL 30 DAY)"),
    'queue_pending'      => safe_count($con, "SELECT COUNT(*) c FROM email_queue WHERE status = 'queued'"),
    'unsubscribes'       => safe_count($con, "SELECT COUNT(*) c FROM email_unsubscribes"),
];

// Recent rows.
$recentApplicants = $con->query(
    "SELECT id, name, email, Position, status, created_at FROM client_career ORDER BY id DESC LIMIT 8"
)->fetch_all(MYSQLI_ASSOC);

$recentEmails = $con->query(
    "SELECT id, to_email, subject, status, context_type, created_at FROM email_log ORDER BY id DESC LIMIT 8"
)->fetch_all(MYSQLI_ASSOC);

$recentLetters = $con->query(
    "SELECT id, recipient_name, recipient_email, letter_type, issue_date FROM letters_issued ORDER BY id DESC LIMIT 5"
)->fetch_all(MYSQLI_ASSOC);
?>
<html lang="en">
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Dashboard')); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'partials/head-css.php'; ?>
    <style>
        .stat-card { border-left: 4px solid #1a8f4a; }
        .stat-card .stat-num { font-size: 26px; font-weight: 700; line-height: 1; color: #111; }
        .stat-card .stat-lbl { font-size: 12px; color: #777; margin-top: 4px; text-transform: uppercase; letter-spacing: .5px; }
        .stat-card .stat-sub { font-size: 11px; color: #aaa; margin-top: 2px; }
        .stat-card.warn { border-left-color: #f0ad4e; }
        .stat-card.danger { border-left-color: #d9534f; }
        .stat-card.info { border-left-color: #0d6efd; }
        .quick-action { display: inline-block; padding: 8px 14px; background: #1a8f4a; color: #fff !important; border-radius: 6px; margin: 4px 4px 0 0; font-size: 13px; }
        .quick-action:hover { background: #166e3a; text-decoration: none; }
    </style>
</head>
<body>
    <?php include 'partials/body.php'; ?>
    <div id="layout-wrapper">
        <?php include 'partials/menu.php'; ?>
        <div class="main-content"><div class="page-content"><div class="container-fluid">
            <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Dashboard')); ?>

            <!-- ===== Stat cards ===== -->
            <div class="row">
                <div class="col-md-3 col-6 mb-3">
                    <div class="card stat-card"><div class="card-body">
                        <div class="stat-num"><?= $stats['applicants_pending'] ?></div>
                        <div class="stat-lbl">Applicants to review</div>
                        <div class="stat-sub"><?= $stats['applicants_30d'] ?> new in 30d · <?= $stats['applicants_total'] ?> total</div>
                    </div></div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card stat-card info"><div class="card-body">
                        <div class="stat-num"><?= $stats['interns_active'] ?></div>
                        <div class="stat-lbl">Active interns</div>
                        <div class="stat-sub"><?= $stats['interns_completed'] ?> completed</div>
                    </div></div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card stat-card"><div class="card-body">
                        <div class="stat-num"><?= $stats['partners_active'] ?></div>
                        <div class="stat-lbl">Active key partners</div>
                        <div class="stat-sub"><?= $stats['partners_prospect'] ?> in pipeline</div>
                    </div></div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card stat-card info"><div class="card-body">
                        <div class="stat-num"><?= $stats['seo_leads_open'] ?></div>
                        <div class="stat-lbl">Open SEO leads</div>
                        <div class="stat-sub">Marketing pipeline</div>
                    </div></div>
                </div>

                <div class="col-md-3 col-6 mb-3">
                    <div class="card stat-card"><div class="card-body">
                        <div class="stat-num"><?= $stats['letters_issued'] ?></div>
                        <div class="stat-lbl">Letters issued</div>
                        <div class="stat-sub">QR-verifiable</div>
                    </div></div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card stat-card info"><div class="card-body">
                        <div class="stat-num"><?= $stats['emails_sent_30d'] ?></div>
                        <div class="stat-lbl">Emails sent (30d)</div>
                        <div class="stat-sub"><?= $stats['queue_pending'] ?> queued · <?= $stats['unsubscribes'] ?> opted-out</div>
                    </div></div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card stat-card <?= $stats['emails_failed_30d'] > 0 ? 'danger' : '' ?>"><div class="card-body">
                        <div class="stat-num"><?= $stats['emails_failed_30d'] ?></div>
                        <div class="stat-lbl">Email failures (30d)</div>
                        <div class="stat-sub"><a href="hr_email_log.php">view log</a></div>
                    </div></div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card stat-card warn"><div class="card-body">
                        <div class="stat-num"><?= $stats['queue_pending'] ?></div>
                        <div class="stat-lbl">Queued bulk emails</div>
                        <div class="stat-sub">Cron processes 10/min</div>
                    </div></div>
                </div>
            </div>

            <!-- ===== Quick actions ===== -->
            <div class="card mb-4"><div class="card-body">
                <h6>Quick actions</h6>
                <a href="hr_applicants.php" class="quick-action">Review applicants</a>
                <a href="hr_interns.php" class="quick-action">Manage interns</a>
                <a href="hr_partners.php" class="quick-action">Key partners</a>
                <a href="hr_seo_leads.php" class="quick-action">SEO leads</a>
                <a href="hr_bulk_email.php" class="quick-action">Send bulk email</a>
                <a href="hr_templates.php" class="quick-action">Letter templates</a>
                <a href="hr_letterhead.php" class="quick-action">Letterhead</a>
                <a href="settings.php" class="quick-action" style="background:#666">Site settings</a>
            </div></div>

            <!-- ===== Recent activity ===== -->
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="card"><div class="card-body">
                        <h5>Recent applicants</h5>
                        <table class="table table-sm">
                            <thead><tr><th>Name</th><th>Role</th><th>Status</th><th>Applied</th></tr></thead>
                            <tbody>
                            <?php foreach ($recentApplicants as $a): ?>
                                <tr>
                                    <td><a href="hr_applicants.php"><?= htmlspecialchars($a['name']) ?></a><br><small><?= htmlspecialchars($a['email']) ?></small></td>
                                    <td><?= htmlspecialchars($a['Position']) ?></td>
                                    <td><span class="badge badge-secondary"><?= htmlspecialchars($a['status'] ?? 'applied') ?></span></td>
                                    <td><small><?= htmlspecialchars(substr((string)($a['created_at'] ?? ''), 0, 10)) ?></small></td>
                                </tr>
                            <?php endforeach; if (!$recentApplicants): ?>
                                <tr><td colspan="4" class="text-muted">No applicants yet.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        <a href="hr_applicants.php" class="text-muted small">View all →</a>
                    </div></div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="card"><div class="card-body">
                        <h5>Recent emails</h5>
                        <table class="table table-sm">
                            <thead><tr><th>To</th><th>Subject</th><th>Status</th><th>When</th></tr></thead>
                            <tbody>
                            <?php foreach ($recentEmails as $e): ?>
                                <tr>
                                    <td><small><?= htmlspecialchars($e['to_email']) ?></small></td>
                                    <td><small><?= htmlspecialchars(mb_strimwidth((string)$e['subject'], 0, 40, '…')) ?></small></td>
                                    <td><span class="badge badge-<?= $e['status']==='sent'?'success':($e['status']==='failed'?'danger':'secondary') ?>"><?= htmlspecialchars($e['status']) ?></span></td>
                                    <td><small><?= htmlspecialchars(substr((string)$e['created_at'], 0, 16)) ?></small></td>
                                </tr>
                            <?php endforeach; if (!$recentEmails): ?>
                                <tr><td colspan="4" class="text-muted">No emails sent yet.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        <a href="hr_email_log.php" class="text-muted small">View log →</a>
                    </div></div>
                </div>
            </div>

            <?php if ($recentLetters): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card"><div class="card-body">
                        <h5>Recently issued letters</h5>
                        <table class="table table-sm">
                            <thead><tr><th>Recipient</th><th>Type</th><th>Issue date</th></tr></thead>
                            <tbody>
                            <?php foreach ($recentLetters as $l): ?>
                                <tr>
                                    <td><?= htmlspecialchars($l['recipient_name']) ?> <small class="text-muted">&lt;<?= htmlspecialchars($l['recipient_email']) ?>&gt;</small></td>
                                    <td><span class="badge badge-info"><?= htmlspecialchars($l['letter_type']) ?></span></td>
                                    <td><small><?= htmlspecialchars($l['issue_date']) ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div></div>
                </div>
            </div>
            <?php endif; ?>

        </div></div></div>
        <?php include 'partials/footer.php'; ?>
    </div>
    <?php include 'partials/right-sidebar.php'; ?>
    <?php include 'partials/vendor-scripts.php'; ?>
</body></html>
