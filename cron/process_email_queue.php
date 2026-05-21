<?php
/**
 * Bulk email queue processor.
 *
 * Schedule on Hostinger: every minute.
 *     * * * * * /usr/bin/php /home/USER/public_html/cron/process_email_queue.php
 *
 * Picks up to BATCH_PER_RUN rows from email_queue whose scheduled_at <= NOW(),
 * sends each via PHPMailer through hr@voldebug.in (SMTP auth) with the
 * campaign's "From" identity, and marks status. Unsubscribed emails are
 * skipped silently. Failures are recorded with error_message and not retried
 * automatically (admin can manually re-queue).
 */

require __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';

const BATCH_PER_RUN = 10; // hard cap regardless of campaign rate; keeps each cron tick brief.

$baseUrl = rtrim($APP_SECRETS['public_base_url'] ?? '', '/');

$rows = $con->query(
    "SELECT * FROM email_queue
     WHERE status = 'queued' AND scheduled_at <= NOW()
     ORDER BY scheduled_at ASC
     LIMIT " . BATCH_PER_RUN
)->fetch_all(MYSQLI_ASSOC);

if (!$rows) { echo "nothing to send.\n"; exit; }

$mailer = new Mailer($con, $APP_SECRETS['smtp']);
$sent = 0; $failed = 0; $skipped = 0;

foreach ($rows as $r) {
    $id = (int) $r['id'];
    $con->query("UPDATE email_queue SET status='sending' WHERE id={$id}");

    $unsubUrl = $baseUrl . '/unsubscribe.php?e=' . urlencode($r['to_email']) . '&t=' . urlencode($r['unsubscribe_token']);

    $res = $mailer->send(
        $r['to_email'], $r['to_name'],
        $r['subject'], $r['body'],
        [], // no attachments in bulk for now
        ['type' => 'bulk', 'id' => (int) $r['campaign_id']],
        [
            'from_email'      => $r['from_email'],
            'from_name'       => $r['from_name'],
            'reply_to'        => $r['reply_to'],
            'unsubscribe_url' => $unsubUrl,
        ]
    );

    if ($res['ok']) {
        $con->query("UPDATE email_queue SET status='sent', sent_at=NOW() WHERE id={$id}");
        $sent++;
        echo "  sent: {$r['to_email']}\n";
    } else {
        $isUnsub = stripos((string)$res['error'], 'unsubscribed') !== false;
        $newStatus = $isUnsub ? 'skipped' : 'failed';
        $err = (string) $res['error'];
        $upd = $con->prepare("UPDATE email_queue SET status=?, error_message=? WHERE id=?");
        $upd->bind_param('ssi', $newStatus, $err, $id);
        $upd->execute();
        if ($isUnsub) { $skipped++; } else { $failed++; }
        echo "  {$newStatus}: {$r['to_email']} ({$err})\n";
    }
}

// Mark campaigns whose queue is fully drained as completed.
$con->query(
    "UPDATE email_campaigns c
     SET status='completed', completed_at = COALESCE(completed_at, NOW())
     WHERE c.status IN ('queued', 'sending')
       AND NOT EXISTS (
           SELECT 1 FROM email_queue q
           WHERE q.campaign_id = c.id AND q.status IN ('queued', 'sending')
       )"
);

echo "done. sent={$sent} failed={$failed} skipped={$skipped}\n";
