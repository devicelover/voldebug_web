<?php
// PHPMailer wrapper. Every send is logged into the email_log table.

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class Mailer
{
    private array $smtp;
    private mysqli $db;

    public function __construct(mysqli $db, array $smtpConfig)
    {
        $this->db   = $db;
        $this->smtp = $smtpConfig;
    }

    /**
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlBody
     * @param array  $attachments  each: ['path' => '...', 'name' => '...']
     * @param array  $context      ['type' => 'letter|contact|quote|manual', 'id' => int|null]
     * @return array{ok:bool, log_id:int, error:?string}
     */
    public function send(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        array  $attachments = [],
        array  $context = ['type' => 'manual', 'id' => null],
        array  $opts = []
    ): array {
        // Check unsubscribe list before doing anything.
        if ($this->isUnsubscribed($toEmail)) {
            $logId = $this->logPending($toEmail, $toName, $subject, $htmlBody, $attachments, $context);
            $this->logFailed($logId, 'Recipient is unsubscribed.');
            return ['ok' => false, 'log_id' => $logId, 'error' => 'Recipient is unsubscribed.'];
        }

        $logId = $this->logPending($toEmail, $toName, $subject, $htmlBody, $attachments, $context);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $this->smtp['host'];
            $mail->Port       = (int) $this->smtp['port'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->smtp['username'];
            $mail->Password   = $this->smtp['password'];
            $mail->SMTPSecure = $this->smtp['encryption'] === 'tls'
                ? PHPMailer::ENCRYPTION_STARTTLS
                : PHPMailer::ENCRYPTION_SMTPS;
            $mail->CharSet    = 'UTF-8';

            // From identity: override is per-send (for partner emails, bulk campaigns etc.).
            // SMTP auth always stays as hr@voldebug.in; we set "From" header + Reply-To.
            $fromEmail = $opts['from_email'] ?? $this->smtp['from_email'];
            $fromName  = $opts['from_name']  ?? $this->smtp['from_name'];
            $replyTo   = $opts['reply_to']   ?? ($this->smtp['reply_to'] ?? $fromEmail);
            $mail->setFrom($fromEmail, $fromName);
            if ($replyTo) $mail->addReplyTo($replyTo);

            // RFC-compliant unsubscribe headers (one-click + mailto) — only when caller passes an unsubscribe URL.
            if (!empty($opts['unsubscribe_url'])) {
                $unsubUrl    = $opts['unsubscribe_url'];
                $unsubMailto = $this->smtp['from_email'] . '?subject=unsubscribe';
                $mail->addCustomHeader('List-Unsubscribe', '<' . $unsubUrl . '>, <mailto:' . $unsubMailto . '>');
                $mail->addCustomHeader('List-Unsubscribe-Post', 'List-Unsubscribe=One-Click');
                // Also append a plain unsubscribe footer at the bottom of HTML body if not present.
                if (strpos($htmlBody, 'unsubscribe') === false) {
                    $htmlBody .= '<hr><p style="font-size:11px;color:#888;text-align:center">'
                              . 'You are receiving this email from ' . htmlspecialchars($fromName)
                              . '. <a href="' . htmlspecialchars($unsubUrl) . '">Unsubscribe</a> if you would prefer not to.</p>';
                }
            }

            $mail->addAddress($toEmail, $toName);

            foreach ($attachments as $att) {
                $path = $att['path'] ?? '';
                $name = $att['name'] ?? basename($path);
                if ($path && is_file($path)) {
                    $mail->addAttachment($path, $name);
                }
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody)));

            $mail->send();
            $this->logSent($logId);
            return ['ok' => true, 'log_id' => $logId, 'error' => null];
        } catch (PHPMailerException $e) {
            $err = $mail->ErrorInfo ?: $e->getMessage();
            $this->logFailed($logId, $err);
            return ['ok' => false, 'log_id' => $logId, 'error' => $err];
        }
    }

    private function logPending(string $to, string $name, string $subj, string $body, array $attachments, array $ctx): int
    {
        $pathsCsv = implode(',', array_map(fn($a) => $a['path'] ?? '', $attachments));
        $type = $ctx['type'] ?? 'manual';
        $id   = $ctx['id']   ?? null;
        $stmt = $this->db->prepare(
            "INSERT INTO email_log (to_email, to_name, subject, body, attachments, context_type, context_id, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')"
        );
        $stmt->bind_param('ssssssi', $to, $name, $subj, $body, $pathsCsv, $type, $id);
        $stmt->execute();
        return (int) $stmt->insert_id;
    }

    private function logSent(int $logId): void
    {
        $this->db->query("UPDATE email_log SET status='sent', sent_at=NOW() WHERE id={$logId}");
    }

    private function logFailed(int $logId, string $err): void
    {
        $stmt = $this->db->prepare("UPDATE email_log SET status='failed', error_message=? WHERE id=?");
        $stmt->bind_param('si', $err, $logId);
        $stmt->execute();
    }

    private function isUnsubscribed(string $email): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM email_unsubscribes WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return (bool) $stmt->get_result()->fetch_row();
    }
}
