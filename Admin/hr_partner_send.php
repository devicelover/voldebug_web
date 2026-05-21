<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';

$pid           = (int) ($_POST['partner_id']  ?? 0);
$template_id   = (int) ($_POST['template_id'] ?? 0); // optional now — only used for status auto-progression
$action        = $_POST['action'] ?? 'preview';
$rawSubject    = trim($_POST['subject'] ?? '');
$rawBody       = (string) ($_POST['body'] ?? '');
$back          = 'hr_partner_detail.php?id=' . $pid;

if (!$pid || $rawSubject === '' || $rawBody === '') {
    header('Location: ' . $back . '&msg=' . urlencode('Subject and body are required.'));
    exit;
}

$pstmt = $con->prepare("SELECT * FROM key_partners WHERE id = ?");
$pstmt->bind_param('i', $pid);
$pstmt->execute();
$partner = $pstmt->get_result()->fetch_assoc();
if (!$partner) { header('Location: hr_partners.php'); exit; }

// Template lookup is optional — only used so the status auto-progression logic still works
// when the admin started from a known template type (intro / onboarding / rejection).
$tpl = null;
if ($template_id > 0) {
    $tstmt = $con->prepare("SELECT letter_type FROM letter_templates WHERE id = ?");
    $tstmt->bind_param('i', $template_id);
    $tstmt->execute();
    $tpl = $tstmt->get_result()->fetch_assoc();
}

// Derive honorific + pronouns from partner.
[$pSubj, $pObj, $pPoss, $first, $hFirst, $hFull] = derive_pronouns([
    'title_prefix' => $partner['title_prefix'],
    'first_name'   => $partner['first_name'],
    'name'         => $partner['contact_name'],
]);

// Parse extra_vars from the textarea (one "key = value" per line).
$extras = [];
foreach (preg_split('/\r?\n/', $_POST['extra_vars'] ?? '') as $line) {
    if (preg_match('/^\s*([A-Za-z0-9_]+)\s*=\s*(.*)$/', $line, $m)) {
        $extras[$m[1]] = $m[2];
    }
}

$companyLegal = $APP_SETTINGS['company_legal_name'] ?: ($APP_SETTINGS['name'] ?? 'Voldebug');

$vars = array_merge([
    // partner data
    'company_name'        => $partner['company_name'],
    'contact_name'        => $partner['contact_name'],
    'honorific_name'      => $hFirst,
    'honorific_full_name' => $hFull,
    'first_name'          => $first,
    'country'             => $partner['country'],
    'city'                => $partner['city'],
    'territory'           => $partner['territories'] ?: $partner['country'],
    'commission_rate'     => $partner['commission_rate'],
    'pronoun_subject'     => $pSubj, 'pronoun_object' => $pObj, 'pronoun_possessive' => $pPoss,
    'Pronoun_subject'     => ucfirst($pSubj), 'Pronoun_object' => ucfirst($pObj), 'Pronoun_possessive' => ucfirst($pPoss),
    // company
    'company'             => $companyLegal,
    'signatory'           => $APP_SETTINGS['signatory_name']        ?? '',
    'signatory_role'      => $APP_SETTINGS['signatory_designation'] ?? '',
    'issue_date'          => date('jS F Y'),
    'verify_url'          => '',
    'accept_url'          => '',
], $extras);

// Use the admin-edited subject + body (pre-filled from template, then optionally edited).
$subject   = render_placeholders($rawSubject, $vars);
$plainBody = render_placeholders($rawBody,    $vars);
// If the admin pasted HTML (contains tags), respect it; otherwise treat as plain text and nl2br.
$htmlBody  = (strip_tags($plainBody) === $plainBody) ? nl2br(htmlspecialchars($plainBody)) : $plainBody;

if ($action === 'preview') {
    header('Content-Type: text/html; charset=utf-8');
    echo "<!doctype html><html><head><meta charset='utf-8'><title>Preview</title>"
       . "<style>body{font-family:system-ui,sans-serif;max-width:680px;margin:40px auto;padding:0 20px;color:#222}"
       . ".meta{background:#f5f7fb;padding:12px;border-radius:8px;margin-bottom:18px;font-size:14px}"
       . ".body{background:#fff;border:1px solid #eef;padding:24px;border-radius:8px;line-height:1.55}</style></head><body>"
       . "<div class='meta'><strong>To:</strong> " . htmlspecialchars($partner['email'])
       . "<br><strong>Subject:</strong> " . htmlspecialchars($subject)
       . "<br><a href='" . htmlspecialchars($back) . "'>← back to partner</a></div>"
       . "<div class='body'>" . $htmlBody . "</div>"
       . "</body></html>";
    exit;
}

// SEND
$mailer = new Mailer($con, $APP_SECRETS['smtp']);
$res = $mailer->send(
    $partner['email'], $partner['contact_name'],
    $subject, $htmlBody,
    [], ['type' => 'partner', 'id' => $pid]
);

// Light status auto-progression: intro→invited, onboarding→onboarded, rejection→rejected.
$autoStatus = [
    'partner_intro'       => 'invited',
    'partner_onboarding'  => 'onboarded',
    'partner_rejection'   => 'rejected',
];
if ($res['ok'] && $tpl && isset($autoStatus[$tpl['letter_type']])) {
    $newStatus = $autoStatus[$tpl['letter_type']];
    $upd = $con->prepare("UPDATE key_partners SET status = ? WHERE id = ?");
    $upd->bind_param('si', $newStatus, $pid);
    $upd->execute();
    if ($tpl['letter_type'] === 'partner_intro')      $con->query("UPDATE key_partners SET intro_sent_at = NOW() WHERE id = {$pid}");
    if ($tpl['letter_type'] === 'partner_onboarding') $con->query("UPDATE key_partners SET onboarded_at  = NOW() WHERE id = {$pid}");
}

$msg = $res['ok'] ? "Email sent to {$partner['email']}." : "Email failed: {$res['error']}";
header('Location: ' . $back . '&msg=' . urlencode($msg));
