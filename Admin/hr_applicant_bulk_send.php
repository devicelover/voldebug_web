<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';

$action       = $_POST['action']       ?? 'preview';
$subjectTpl   = trim($_POST['subject'] ?? '');
$bodyTpl      = (string) ($_POST['body'] ?? '');
$fromLabel    = trim($_POST['from_label'] ?? 'hr');
$updateStatus = !empty($_POST['update_status']);
$rawIds       = (string) ($_POST['ids'] ?? '');

if ($subjectTpl === '' || $bodyTpl === '' || $rawIds === '') {
    header('Location: hr_applicants.php?msg=' . urlencode('Missing subject, body, or recipients.'));
    exit;
}

$ids = array_values(array_filter(array_map('intval', explode(',', $rawIds)), fn($x) => $x > 0));
if (!$ids) { header('Location: hr_applicants.php'); exit; }

// Resolve From identity once.
$sig = $con->prepare("SELECT name, email FROM signatories WHERE label = ? LIMIT 1");
$sig->bind_param('s', $fromLabel);
$sig->execute();
$signatory = $sig->get_result()->fetch_assoc() ?: ['name' => 'Voldebug HR', 'email' => 'hr@voldebug.in'];

// Pull all selected applicants.
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $con->prepare("SELECT id, name, email, Position, status FROM client_career WHERE id IN ($placeholders)");
$stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Dedupe by email (keep first occurrence).
$recipients = []; $seen = [];
foreach ($rows as $r) {
    $k = strtolower((string)$r['email']);
    if (!filter_var($r['email'], FILTER_VALIDATE_EMAIL) || isset($seen[$k])) continue;
    $seen[$k] = true;
    $recipients[] = $r;
}
if (!$recipients) {
    header('Location: hr_applicants.php?msg=' . urlencode('No valid recipients (all duplicates or invalid).'));
    exit;
}

// Build per-recipient vars helper
$companyLegal = $APP_SETTINGS['company_legal_name'] ?: ($APP_SETTINGS['name'] ?? 'Voldebug');
$buildVars = function (array $r) use ($signatory, $companyLegal, $APP_SETTINGS) {
    [$pSubj, $pObj, $pPoss, $first, $hFirst, $hFull] = derive_pronouns([
        'title_prefix' => '',
        'first_name'   => '',
        'name'         => $r['name'],
    ]);
    return [
        'name'                => $r['name'],
        'first_name'          => $first,
        'honorific_name'      => $hFirst,
        'honorific_full_name' => $hFull,
        'email'               => $r['email'],
        'role'                => $r['Position'],
        'company'             => $companyLegal,
        'signatory'           => $signatory['name'],
        'signatory_role'      => $APP_SETTINGS['signatory_designation'] ?? '',
        'pronoun_subject'     => $pSubj,
        'pronoun_object'      => $pObj,
        'pronoun_possessive'  => $pPoss,
        'Pronoun_subject'     => ucfirst($pSubj),
        'Pronoun_object'      => ucfirst($pObj),
        'Pronoun_possessive'  => ucfirst($pPoss),
        // intentionally empty so {{#IF}} blocks collapse for non-letter context:
        'verify_url'          => '',
        'accept_url'          => '',
    ];
};

// === PREVIEW MODE — render only the first recipient as a sample, no send. ===
if ($action === 'preview') {
    $r = $recipients[0];
    $vars = $buildVars($r);
    $subj = render_placeholders($subjectTpl, $vars);
    $body = render_placeholders($bodyTpl,    $vars);
    $html = (strip_tags($body) === $body) ? nl2br(htmlspecialchars($body)) : $body;
    header('Content-Type: text/html; charset=utf-8');
    echo "<!doctype html><html><head><meta charset='utf-8'><title>Preview</title>"
       . "<style>body{font-family:system-ui,sans-serif;max-width:680px;margin:40px auto;padding:0 20px;color:#222}"
       . ".meta{background:#f5f7fb;padding:12px;border-radius:8px;margin-bottom:18px;font-size:14px}"
       . ".body{background:#fff;border:1px solid #eef;padding:24px;border-radius:8px;line-height:1.55}</style></head><body>"
       . "<div class='meta'><strong>From:</strong> " . htmlspecialchars($signatory['name']) . " &lt;" . htmlspecialchars($signatory['email']) . "&gt;"
       . "<br><strong>To (sample, recipient 1 of " . count($recipients) . "):</strong> " . htmlspecialchars($r['name']) . " &lt;" . htmlspecialchars($r['email']) . "&gt;"
       . "<br><strong>Subject:</strong> " . htmlspecialchars($subj)
       . "<br><a href='javascript:history.back()'>← back to compose</a></div>"
       . "<div class='body'>" . $html . "</div></body></html>";
    exit;
}

// === SEND MODE — loop, personalise, mail one-at-a-time with a tiny throttle. ===
$mailer = new Mailer($con, $APP_SECRETS['smtp']);
$sent = 0; $failed = 0; $errors = [];
foreach ($recipients as $r) {
    $vars = $buildVars($r);
    $subj = render_placeholders($subjectTpl, $vars);
    $body = render_placeholders($bodyTpl,    $vars);
    $html = (strip_tags($body) === $body) ? nl2br(htmlspecialchars($body)) : $body;

    $res = $mailer->send(
        $r['email'], $r['name'], $subj, $html,
        [], ['type' => 'applicant_bulk', 'id' => (int)$r['id']],
        ['from_email' => $signatory['email'], 'from_name' => $signatory['name'], 'reply_to' => $signatory['email']]
    );
    if ($res['ok']) {
        $sent++;
        if ($updateStatus) {
            $rid = (int)$r['id'];
            $con->query("UPDATE client_career SET status = 'reviewed' WHERE id = {$rid} AND status = 'applied'");
        }
    } else {
        $failed++;
        $errors[] = $r['email'] . ': ' . $res['error'];
    }
    // Be polite to SMTP — sleep 1s between sends. PHP CLI/Apache will tolerate this for small batches.
    if (count($recipients) > 3) usleep(800000); // 0.8s
}

$msg = "Sent {$sent} of " . count($recipients) . " emails";
if ($failed) $msg .= "; {$failed} failed";
if ($updateStatus && $sent) $msg .= " · marked as 'reviewed'";
$msg .= '.';
if ($failed && $errors) $msg .= ' First error: ' . substr($errors[0], 0, 200);

header('Location: hr_applicants.php?msg=' . urlencode($msg));
