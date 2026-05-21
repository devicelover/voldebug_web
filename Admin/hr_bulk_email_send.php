<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }

require_once __DIR__ . '/../includes/bootstrap.php';

$name          = trim($_POST['name']          ?? '');
$subject       = trim($_POST['subject']       ?? '');
$body          = (string) ($_POST['body']     ?? '');
$audience      = trim($_POST['audience']      ?? 'custom');
$customList    = (string) ($_POST['custom_list'] ?? '');
$fromLabel     = trim($_POST['from_label']    ?? 'hr');
$ratePerHour   = max(5, min(200, (int) ($_POST['rate_per_hour'] ?? 30)));
$action        = $_POST['action']             ?? 'preview';

if ($name === '' || $subject === '' || $body === '') {
    header('Location: hr_bulk_email.php?msg=' . urlencode('Fill name, subject and body.'));
    exit;
}

// Resolve from identity.
$sig = $con->prepare("SELECT name, email FROM signatories WHERE label = ? LIMIT 1");
$sig->bind_param('s', $fromLabel);
$sig->execute();
$signatory = $sig->get_result()->fetch_assoc() ?: ['name' => 'Voldebug HR', 'email' => 'hr@voldebug.in'];

// Build the recipient list based on audience.
$recipients = []; // each: ['email'=>..., 'name'=>...]
switch ($audience) {
    case 'applicants':
        $r = $con->query("SELECT DISTINCT email, MAX(name) AS name FROM client_career WHERE email LIKE '%@%' GROUP BY LOWER(email)");
        while ($row = $r->fetch_assoc()) $recipients[] = $row;
        break;
    case 'interns':
        $r = $con->query("SELECT email, name FROM interns WHERE email LIKE '%@%'");
        while ($row = $r->fetch_assoc()) $recipients[] = $row;
        break;
    case 'partners':
        $r = $con->query("SELECT email, contact_name AS name FROM key_partners WHERE email LIKE '%@%'");
        while ($row = $r->fetch_assoc()) $recipients[] = $row;
        break;
    case 'seo_leads':
        $r = $con->query("SELECT email, contact_name AS name FROM seo_leads WHERE email LIKE '%@%'");
        while ($row = $r->fetch_assoc()) $recipients[] = $row;
        break;
    case 'custom':
    default:
        foreach (preg_split('/\r?\n/', $customList) as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $parts = array_map('trim', explode('|', $line, 2));
            $email = $parts[0];
            $rname = $parts[1] ?? '';
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) $recipients[] = ['email' => $email, 'name' => $rname];
        }
        break;
}

// Dedupe + sanitise.
$seen = [];
$recipients = array_values(array_filter($recipients, function ($r) use (&$seen) {
    $k = strtolower($r['email']);
    if (isset($seen[$k])) return false;
    if (!filter_var($r['email'], FILTER_VALIDATE_EMAIL)) return false;
    $seen[$k] = true;
    return true;
}));

if (!$recipients) {
    header('Location: hr_bulk_email.php?msg=' . urlencode('No valid recipients found.'));
    exit;
}

// Preview mode: render the first email and bail.
if ($action === 'preview') {
    $first   = $recipients[0];
    $personalSubject = strtr($subject, ['{{name}}' => $first['name'], '{{email}}' => $first['email']]);
    $personalBody    = strtr($body,    ['{{name}}' => $first['name'], '{{email}}' => $first['email'],
                                        '{{company}}' => $APP_SETTINGS['company_legal_name'] ?? 'Voldebug']);
    header('Content-Type: text/html; charset=utf-8');
    echo "<!doctype html><html><head><meta charset='utf-8'><title>Preview</title>"
       . "<style>body{font-family:system-ui,sans-serif;max-width:680px;margin:40px auto;padding:0 20px}"
       . ".meta{background:#f5f7fb;padding:12px;border-radius:8px;margin-bottom:18px;font-size:14px}"
       . ".body{background:#fff;border:1px solid #eef;padding:24px;border-radius:8px;line-height:1.55}</style></head><body>"
       . "<div class='meta'><strong>From:</strong> " . htmlspecialchars($signatory['name']) . " &lt;" . htmlspecialchars($signatory['email']) . "&gt;"
       . "<br><strong>To (sample):</strong> " . htmlspecialchars($first['name']) . " &lt;" . htmlspecialchars($first['email']) . "&gt;"
       . "<br><strong>Subject:</strong> " . htmlspecialchars($personalSubject)
       . "<br><strong>Total recipients (after dedupe):</strong> " . count($recipients)
       . "<br><a href='hr_bulk_email.php'>← back</a></div>"
       . "<div class='body'>" . $personalBody . "</div></body></html>";
    exit;
}

// === ENQUEUE ===
$createdBy = $_SESSION['username'] ?? 'admin';
$ins = $con->prepare("INSERT INTO email_campaigns (name, subject, body, audience, from_label, recipients_count, status, created_by) VALUES (?,?,?,?,?,?,?,?)");
$count = count($recipients);
$status = 'queued';
$ins->bind_param('sssssiis', $name, $subject, $body, $audience, $fromLabel, $count, $status, $createdBy);
$ins->execute();
$campaignId = (int) $ins->insert_id;

// Spread schedule: each email scheduled `(60/rate)` minutes after the previous one.
$secondsBetween = max(60, (int) round(3600 / $ratePerHour));
$unsubSalt = $APP_SECRETS['smtp']['password'] ?? 'voldebug-salt';
$baseUrl   = rtrim($APP_SECRETS['public_base_url'] ?? '', '/');

$q = $con->prepare(
    "INSERT INTO email_queue (campaign_id, to_email, to_name, subject, body, from_email, from_name, reply_to, unsubscribe_token, status, scheduled_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'queued', DATE_ADD(NOW(), INTERVAL ? SECOND))"
);

$offset = 0;
foreach ($recipients as $r) {
    $personalSubject = strtr($subject, ['{{name}}' => $r['name'], '{{email}}' => $r['email']]);
    $personalBody    = strtr($body,    ['{{name}}' => $r['name'], '{{email}}' => $r['email'],
                                        '{{company}}' => $APP_SETTINGS['company_legal_name'] ?? 'Voldebug']);
    $unsubTok = hash_hmac('sha256', strtolower($r['email']), $unsubSalt);
    $q->bind_param('isssssssssi',
        $campaignId, $r['email'], $r['name'], $personalSubject, $personalBody,
        $signatory['email'], $signatory['name'], $signatory['email'], $unsubTok, $offset);
    $q->execute();
    $offset += $secondsBetween;
}

header('Location: hr_bulk_email.php?msg=' . urlencode("Campaign #{$campaignId} enqueued with {$count} recipients at {$ratePerHour}/hr."));
