<?php include 'partials/session.php'; require_once __DIR__ . '/../includes/csrf.php'; ?>
<?php include 'partials/main.php'; ?>
<?php include 'partials/config.php'; ?>
<?php include 'authentication.php'; ?>
<?php
$id = (int) ($_GET['id'] ?? 0);
$msg = $_GET['msg'] ?? '';
if (!$id) { header('Location: hr_interns.php'); exit; }

$stmt = $con->prepare("SELECT * FROM interns WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$intern = $stmt->get_result()->fetch_assoc();
if (!$intern) { header('Location: hr_interns.php'); exit; }

// Templates available for this role (role-specific first, then general fallback).
$tpls = $con->prepare(
    "SELECT id, template_name, letter_type, role_tag
     FROM letter_templates
     WHERE is_active = 1 AND (role_tag = ? OR role_tag = 'general')
     ORDER BY (role_tag = ?) DESC, letter_type"
);
$tpls->bind_param('ss', $intern['role_tag'], $intern['role_tag']);
$tpls->execute();
$templates = $tpls->get_result()->fetch_all(MYSQLI_ASSOC);

// Letters already issued to this intern.
$L = $con->prepare("SELECT * FROM letters_issued WHERE intern_id = ? ORDER BY created_at DESC");
$L->bind_param('i', $id);
$L->execute();
$letters = $L->get_result()->fetch_all(MYSQLI_ASSOC);

// Weekly check-ins for this intern.
$K = $con->prepare("SELECT * FROM intern_checkins WHERE intern_id = ? ORDER BY week_starting DESC");
$K->bind_param('i', $id);
$K->execute();
$checkins = $K->get_result()->fetch_all(MYSQLI_ASSOC);

// Offer/joining-letter responses so HR sees acceptance status at a glance.
$RR = $con->prepare(
    "SELECT lr.*, l.letter_type, l.verify_token
     FROM letter_responses lr
     JOIN letters_issued l ON l.id = lr.letter_id
     WHERE l.intern_id = ? ORDER BY lr.created_at DESC"
);
$RR->bind_param('i', $id);
$RR->execute();
$responses = $RR->get_result()->fetch_all(MYSQLI_ASSOC);

// Emails sent that referenced a letter for this intern.
$E = $con->prepare(
    "SELECT el.*
     FROM email_log el
     LEFT JOIN letters_issued li ON li.id = el.context_id AND el.context_type='letter'
     WHERE el.to_email = ? OR li.intern_id = ?
     ORDER BY el.id DESC LIMIT 25"
);
$E->bind_param('si', $intern['email'], $id);
$E->execute();
$emails = $E->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Intern — ' . $intern['name'])); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<?php include 'partials/body.php'; ?>
<div id="layout-wrapper">
    <?php include 'partials/menu.php'; ?>
    <div class="main-content"><div class="page-content"><div class="container-fluid">
        <?php includeFileWithVariables('partials/page-title.php', array('pagetitle' => 'Voldebug', 'title' => 'Intern: ' . htmlspecialchars($intern['name']))); ?>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <div class="row">
            <div class="col-lg-5">
                <div class="card"><div class="card-body">
                    <h5 class="mb-3">Details</h5>
                    <table class="table table-sm mb-2">
                        <?php if (!empty($intern['title_prefix'])): ?>
                        <tr><th>Title</th><td><?= htmlspecialchars($intern['title_prefix']) ?></td></tr>
                        <?php endif; ?>
                        <tr><th>Email</th><td><?= htmlspecialchars($intern['email']) ?></td></tr>
                        <tr><th>Phone</th><td><?= htmlspecialchars($intern['phone']) ?></td></tr>
                        <tr><th>Enrollment #</th><td><?= htmlspecialchars($intern['enrollment_number'] ?? '') ?: '—' ?></td></tr>
                        <tr><th>College</th><td><?= htmlspecialchars($intern['college'] ?? '') ?: '—' ?></td></tr>
                        <tr><th>Role</th><td><?= htmlspecialchars($intern['role']) ?> <code>(<?= htmlspecialchars($intern['role_tag']) ?>)</code></td></tr>
                        <tr><th>Type</th><td><?= htmlspecialchars($intern['employee_type']) ?><?= !empty($intern['internship_type']) ? ' · ' . htmlspecialchars($intern['internship_type']) : '' ?></td></tr>
                        <tr><th>Status</th><td><strong><?= htmlspecialchars($intern['status']) ?></strong></td></tr>
                        <tr><th>Start</th><td><?= htmlspecialchars($intern['start_date'] ?? '') ?></td></tr>
                        <tr><th>End</th><td><?= htmlspecialchars($intern['end_date'] ?? '') ?></td></tr>
                        <tr><th>Mentor</th><td><?= htmlspecialchars($intern['mentor'] ?? '') ?></td></tr>
                        <tr><th>GitHub</th><td>
                            <?php if (!empty($intern['github_repo'])): ?>
                                <a href="<?= htmlspecialchars($intern['github_repo']) ?>" target="_blank"><?= htmlspecialchars($intern['github_repo']) ?></a>
                            <?php else: ?>—<?php endif; ?>
                        </td></tr>
                        <tr><th>LinkedIn</th><td>
                            <?php if (!empty($intern['linkedin_url'])): ?>
                                <a href="<?= htmlspecialchars($intern['linkedin_url']) ?>" target="_blank">profile</a>
                            <?php else: ?>—<?php endif; ?>
                        </td></tr>
                    </table>
                    <div><strong>Tasks summary:</strong><br><?= nl2br(htmlspecialchars($intern['tasks_summary'] ?? '')) ?></div>
                    <hr>
                    <div><strong>Performance notes (internal):</strong><br><?= nl2br(htmlspecialchars($intern['performance_notes'] ?? '')) ?></div>
                    <div class="mt-3">
                        <a href="hr_intern_edit.php?id=<?= $id ?>" class="btn btn-outline-success btn-sm">Edit</a>
                        <a href="hr_interns.php" class="btn btn-outline-secondary btn-sm">Back to list</a>
                        <form method="post" action="hr_intern_convert_partner.php" style="display:inline" onsubmit="return confirm('Promote <?= htmlspecialchars($intern['name'], ENT_QUOTES) ?> to a Key Partner? Their intern record will be marked completed.')">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button type="submit" class="btn btn-outline-warning btn-sm">→ Promote to Key Partner</button>
                        </form>
                        <form method="post" action="hr_intern_delete.php" style="display:inline" onsubmit="return confirm('Delete this intern? This also removes their letters and check-ins.')">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div></div>
            </div>

            <div class="col-lg-7">
                <div class="card"><div class="card-body">
                    <h5 class="mb-3">Generate a letter</h5>
                    <?php if (!$templates): ?>
                        <p class="text-muted">No active templates. <a href="hr_templates.php">Create a template first</a>.</p>
                    <?php else: ?>
                    <form method="post" action="hr_letter_generate.php">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="intern_id" value="<?= $id ?>">
                        <div class="form-group">
                            <label>Template</label>
                            <select name="template_id" class="form-control">
                                <?php foreach ($templates as $t): ?>
                                    <option value="<?= (int)$t['id'] ?>">[<?= htmlspecialchars($t['letter_type']) ?>/<?= htmlspecialchars($t['role_tag']) ?>] <?= htmlspecialchars($t['template_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="mr-3"><input type="checkbox" name="include_signature" value="1" checked> Include signature</label>
                            <label class="mr-3"><input type="checkbox" name="include_stamp" value="1" checked> Include digital stamp</label>
                            <small class="text-muted d-block">Uncheck for casual / internal letters where you don't want signature or stamp on the PDF.</small>
                        </div>
                        <button type="submit" name="action" value="generate" class="btn btn-primary">Generate Letter</button>
                        <small class="text-muted d-block mt-2">The letter is created once and appears in the "Letters issued" list below. From there you can Download the PDF, Email it to the recipient, or Revoke it — without creating duplicates.</small>
                    </form>
                    <?php endif; ?>
                </div></div>

                <div class="card"><div class="card-body">
                    <h5 class="mb-3">Letters issued</h5>
                    <?php if (!$letters): ?>
                        <p class="text-muted">No letters issued yet.</p>
                    <?php else: ?>
                        <table class="table table-sm table-bordered">
                            <thead><tr><th>Type</th><th>Issued</th><th>Ref</th><th></th></tr></thead>
                            <tbody>
                            <?php foreach ($letters as $l): ?>
                                <tr>
                                    <td><?= htmlspecialchars($l['letter_type']) ?></td>
                                    <td><?= htmlspecialchars($l['issue_date']) ?></td>
                                    <td><code>VDB-<?= htmlspecialchars(substr($l['verify_token'], 0, 10)) ?>…</code></td>
                                    <td class="text-nowrap">
                                        <a href="hr_letter_download.php?id=<?= (int)$l['id'] ?>" class="btn btn-sm btn-outline-primary">PDF</a>
                                        <?php if ((int)$l['revoked'] !== 1): ?>
                                        <form method="post" action="hr_letter_email.php" style="display:inline" onsubmit="return confirm('Email this letter to <?= htmlspecialchars($intern['email'], ENT_QUOTES) ?>?')">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="letter_id" value="<?= (int)$l['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-success">Email</button>
                                        </form>
                                        <?php endif; ?>
                                        <a href="../verify.php?t=<?= urlencode($l['verify_token']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Verify</a>
                                        <form method="post" action="hr_letter_revoke.php" style="display:inline">
<?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?= (int)$l['id'] ?>">
                                            <input type="hidden" name="intern_id" value="<?= $id ?>">
                                            <?php if ((int)$l['revoked'] === 1): ?>
                                                <span class="badge badge-warning">revoked</span>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Revoke this letter? The verify page will show \'Revoked\'.')">Revoke</button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div></div>

                <?php if ($responses): ?>
                <div class="card"><div class="card-body">
                    <h5 class="mb-3">Offer / joining responses</h5>
                    <table class="table table-sm">
                        <thead><tr><th>Letter</th><th>Response</th><th>When</th><th>Notes</th></tr></thead>
                        <tbody>
                        <?php foreach ($responses as $rr): ?>
                            <tr>
                                <td><?= htmlspecialchars($rr['letter_type']) ?> — VDB-<?= htmlspecialchars(substr($rr['verify_token'], 0, 8)) ?></td>
                                <td>
                                    <?php $cls = $rr['response'] === 'accepted' ? 'success' : 'danger'; ?>
                                    <span class="badge badge-<?= $cls ?>"><?= htmlspecialchars($rr['response']) ?></span>
                                </td>
                                <td><?= htmlspecialchars($rr['created_at']) ?></td>
                                <td><small><?= nl2br(htmlspecialchars($rr['notes'] ?? '')) ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div></div>
                <?php endif; ?>

                <div class="card"><div class="card-body">
                    <h5 class="mb-3">Weekly check-ins</h5>
                    <p class="text-muted">Log what the intern worked on each week. Use <em>Aggregate → tasks summary</em> to paste all notes into the completion-letter field.</p>

                    <form method="post" action="hr_checkin_save.php" class="form-inline mb-3">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="intern_id" value="<?= $id ?>">
                        <input type="date" name="week_starting" class="form-control mr-2" value="<?= date('Y-m-d', strtotime('monday this week')) ?>" required>
                        <input type="text" name="notes" class="form-control mr-2" placeholder="What did they work on this week?" style="min-width:320px" required>
                        <select name="rating" class="form-control mr-2">
                            <option value="">no rating</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?><option value="<?= $i ?>"><?= $i ?>/5</option><?php endfor; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Log</button>
                    </form>

                    <?php if ($checkins): ?>
                        <table class="table table-sm table-bordered">
                            <thead><tr><th>Week</th><th>Notes</th><th>Rating</th><th></th></tr></thead>
                            <tbody>
                            <?php foreach ($checkins as $ck): ?>
                                <tr>
                                    <td><?= htmlspecialchars($ck['week_starting']) ?></td>
                                    <td><?= nl2br(htmlspecialchars($ck['notes'])) ?></td>
                                    <td class="text-center"><?= $ck['rating'] ? (int)$ck['rating'] . '/5' : '—' ?></td>
                                    <td>
                                        <form method="post" action="hr_checkin_save.php" style="display:inline" onsubmit="return confirm('Delete this check-in?')">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="intern_id" value="<?= $id ?>">
                                            <input type="hidden" name="delete_id" value="<?= (int)$ck['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">×</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <form method="post" action="hr_intern_save.php" class="mt-2">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id"   value="<?= $id ?>">
                            <input type="hidden" name="title_prefix" value="<?= htmlspecialchars($intern['title_prefix'] ?? '') ?>">
                            <input type="hidden" name="name"  value="<?= htmlspecialchars($intern['name']) ?>">
                            <input type="hidden" name="first_name" value="<?= htmlspecialchars($intern['first_name'] ?? '') ?>">
                            <input type="hidden" name="email" value="<?= htmlspecialchars($intern['email']) ?>">
                            <input type="hidden" name="phone" value="<?= htmlspecialchars($intern['phone']) ?>">
                            <input type="hidden" name="enrollment_number" value="<?= htmlspecialchars($intern['enrollment_number'] ?? '') ?>">
                            <input type="hidden" name="role"  value="<?= htmlspecialchars($intern['role']) ?>">
                            <input type="hidden" name="role_tag" value="<?= htmlspecialchars($intern['role_tag']) ?>">
                            <input type="hidden" name="employee_type" value="<?= htmlspecialchars($intern['employee_type']) ?>">
                            <input type="hidden" name="internship_type" value="<?= htmlspecialchars($intern['internship_type'] ?? '') ?>">
                            <input type="hidden" name="college" value="<?= htmlspecialchars($intern['college'] ?? '') ?>">
                            <input type="hidden" name="start_date" value="<?= htmlspecialchars($intern['start_date'] ?? '') ?>">
                            <input type="hidden" name="end_date"   value="<?= htmlspecialchars($intern['end_date'] ?? '') ?>">
                            <input type="hidden" name="github_repo" value="<?= htmlspecialchars($intern['github_repo']) ?>">
                            <input type="hidden" name="linkedin_url" value="<?= htmlspecialchars($intern['linkedin_url']) ?>">
                            <input type="hidden" name="mentor" value="<?= htmlspecialchars($intern['mentor']) ?>">
                            <input type="hidden" name="performance_notes" value="<?= htmlspecialchars($intern['performance_notes'] ?? '') ?>">
                            <input type="hidden" name="status" value="<?= htmlspecialchars($intern['status']) ?>">
                            <?php
                            $agg = [];
                            foreach (array_reverse($checkins) as $ck) {
                                $agg[] = '[' . $ck['week_starting'] . '] ' . $ck['notes'];
                            }
                            $aggText = implode("\n", $agg);
                            ?>
                            <input type="hidden" name="tasks_summary" value="<?= htmlspecialchars($aggText) ?>">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Aggregate → tasks summary (overwrite)</button>
                        </form>
                    <?php else: ?>
                        <p class="text-muted">No check-ins yet.</p>
                    <?php endif; ?>
                </div></div>

                <div class="card"><div class="card-body">
                    <h5 class="mb-3">Email log</h5>
                    <?php if (!$emails): ?><p class="text-muted">No emails sent.</p><?php else: ?>
                    <table class="table table-sm">
                        <thead><tr><th>When</th><th>Subject</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php foreach ($emails as $e): ?>
                            <tr>
                                <td><?= htmlspecialchars($e['created_at']) ?></td>
                                <td><?= htmlspecialchars($e['subject']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $e['status']==='sent'?'success':($e['status']==='failed'?'danger':'secondary') ?>"><?= htmlspecialchars($e['status']) ?></span>
                                    <?php if ($e['status']==='failed' && !empty($e['error_message'])): ?>
                                        <small class="text-danger d-block"><?= htmlspecialchars(substr($e['error_message'], 0, 120)) ?></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div></div>
            </div>
        </div>
    </div></div></div>
    <?php include 'partials/footer.php'; ?>
</div>
<?php include 'partials/right-sidebar.php'; ?>
<?php include 'partials/vendor-scripts.php'; ?>
</body></html>
