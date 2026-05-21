<?php
/**
 * End-date reminder cron.
 *
 * Purpose: when an intern/employee's `end_date` is today or past and they are still `active`
 * (i.e. nobody has generated their completion letter yet), email HR a heads-up with a direct
 * link to the intern's admin page. Does NOT auto-send the completion letter — HR reviews
 * and clicks Generate themselves.
 *
 * Runs once per intern (completion_reminded_at guards against duplicates).
 *
 * Schedule on Hostinger: cron tab → daily at 09:00 UTC
 *   /usr/bin/php /home/USER/public_html/cron/end_date_reminder.php
 */

require __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';

$today = date('Y-m-d');

$stmt = $con->prepare(
    "SELECT id, name, email, role, end_date, employee_type, github_repo
     FROM interns
     WHERE status = 'active'
       AND end_date IS NOT NULL
       AND end_date <= ?
       AND completion_reminded_at IS NULL
     ORDER BY end_date ASC"
);
$stmt->bind_param('s', $today);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$count = count($rows);
echo "Found {$count} intern(s) needing a completion-letter reminder.\n";

if (!$count) exit(0);

$hrEmail = $APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in';
$company = $APP_SETTINGS['name']     ?? 'Voldebug';
$base    = rtrim($APP_SECRETS['public_base_url'], '/');

$mailer = new Mailer($con, $APP_SECRETS['smtp']);

foreach ($rows as $r) {
    $detail = $base . '/Admin/hr_intern_detail.php?id=' . (int) $r['id'];
    $subject = "[HR reminder] Completion letter due for " . $r['name'];
    $body = "<p>The following intern/employee has reached their end date and is still marked active:</p>"
          . "<table style='border-collapse:collapse'>"
          . "<tr><td style='padding:4px'><strong>Name</strong></td><td style='padding:4px'>" . htmlspecialchars($r['name']) . "</td></tr>"
          . "<tr><td style='padding:4px'><strong>Role</strong></td><td style='padding:4px'>" . htmlspecialchars($r['role']) . " (" . htmlspecialchars($r['employee_type']) . ")</td></tr>"
          . "<tr><td style='padding:4px'><strong>End date</strong></td><td style='padding:4px'>" . htmlspecialchars($r['end_date']) . "</td></tr>"
          . "<tr><td style='padding:4px'><strong>Email</strong></td><td style='padding:4px'>" . htmlspecialchars($r['email']) . "</td></tr>"
          . ($r['github_repo'] ? "<tr><td style='padding:4px'><strong>GitHub</strong></td><td style='padding:4px'><a href='" . htmlspecialchars($r['github_repo']) . "'>" . htmlspecialchars($r['github_repo']) . "</a></td></tr>" : '')
          . "</table>"
          . "<p>Open their page to review tasks, then <strong>generate &amp; email the completion letter</strong>:</p>"
          . "<p><a href='" . htmlspecialchars($detail) . "'>" . htmlspecialchars($detail) . "</a></p>";

    $res = $mailer->send($hrEmail, $company . ' HR', $subject, $body, [], ['type' => 'end_date_reminder', 'id' => (int) $r['id']]);

    if ($res['ok']) {
        $con->query("UPDATE interns SET completion_reminded_at = NOW() WHERE id = " . (int) $r['id']);
        echo "  reminded: {$r['name']} ({$r['email']})\n";
    } else {
        echo "  FAILED: {$r['name']} — " . $res['error'] . "\n";
    }
}

echo "done.\n";
