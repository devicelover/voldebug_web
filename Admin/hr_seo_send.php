<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';

$lid         = (int) ($_POST['lead_id']     ?? 0);
$template_id = (int) ($_POST['template_id'] ?? 0); // optional now
$action      = $_POST['action'] ?? 'preview';
$rawSubject  = trim($_POST['subject'] ?? '');
$rawBody     = (string) ($_POST['body'] ?? '');
$back        = 'hr_seo_detail.php?id=' . $lid;
if (!$lid || $rawSubject === '' || $rawBody === '') {
    header('Location: ' . $back . '&msg=' . urlencode('Subject and body are required.'));
    exit;
}

$lstmt = $con->prepare("SELECT * FROM seo_leads WHERE id = ?");
$lstmt->bind_param('i', $lid); $lstmt->execute();
$lead = $lstmt->get_result()->fetch_assoc();
if (!$lead) { header('Location: hr_seo_leads.php'); exit; }

// Template is only used to optionally auto-advance lead status (outreach → contacted, etc.).
$tpl = null;
if ($template_id > 0) {
    $tstmt = $con->prepare("SELECT letter_type FROM letter_templates WHERE id = ?");
    $tstmt->bind_param('i', $template_id); $tstmt->execute();
    $tpl = $tstmt->get_result()->fetch_assoc();
}

[$pSubj, $pObj, $pPoss, $first, $hFirst, $hFull] = derive_pronouns([
    'title_prefix' => '',
    'first_name'   => '',
    'name'         => $lead['contact_name'] ?: $lead['business_name'],
]);

$extras = [];
foreach (preg_split('/\r?\n/', $_POST['extra_vars'] ?? '') as $line) {
    if (preg_match('/^\s*([A-Za-z0-9_]+)\s*=\s*(.*)$/', $line, $m)) $extras[$m[1]] = $m[2];
}

$companyLegal = $APP_SETTINGS['company_legal_name'] ?: ($APP_SETTINGS['name'] ?? 'Voldebug');

$vars = array_merge([
    'business_name'       => $lead['business_name'],
    'contact_name'        => $lead['contact_name'],
    'honorific_name'      => $hFirst,
    'honorific_full_name' => $hFull,
    'first_name'          => $first,
    'website'             => $lead['website'],
    'industry'            => $lead['industry'],
    'pronoun_subject' => $pSubj, 'pronoun_object' => $pObj, 'pronoun_possessive' => $pPoss,
    'Pronoun_subject' => ucfirst($pSubj), 'Pronoun_object' => ucfirst($pObj), 'Pronoun_possessive' => ucfirst($pPoss),
    'company'             => $companyLegal,
    'signatory'           => $APP_SETTINGS['signatory_name']        ?? '',
    'signatory_role'      => $APP_SETTINGS['signatory_designation'] ?? '',
    'issue_date'          => date('jS F Y'),
    'verify_url'          => '',
    'accept_url'          => '',
], $extras);

$subject   = render_placeholders($rawSubject, $vars);
$plainBody = render_placeholders($rawBody,    $vars);
$htmlBody  = (strip_tags($plainBody) === $plainBody) ? nl2br(htmlspecialchars($plainBody)) : $plainBody;

if ($action === 'preview') {
    header('Content-Type: text/html; charset=utf-8');
    echo "<!doctype html><html><head><meta charset='utf-8'><title>Preview</title>"
       . "<style>body{font-family:system-ui,sans-serif;max-width:680px;margin:40px auto;padding:0 20px}"
       . ".meta{background:#f5f7fb;padding:12px;border-radius:8px;margin-bottom:18px;font-size:14px}"
       . ".body{background:#fff;border:1px solid #eef;padding:24px;border-radius:8px;line-height:1.55}</style></head><body>"
       . "<div class='meta'><strong>To:</strong> " . htmlspecialchars($lead['email'])
       . "<br><strong>Subject:</strong> " . htmlspecialchars($subject)
       . "<br><a href='" . htmlspecialchars($back) . "'>← back</a></div>"
       . "<div class='body'>" . $htmlBody . "</div></body></html>";
    exit;
}

$mailer = new Mailer($con, $APP_SECRETS['smtp']);
$res = $mailer->send($lead['email'], $lead['contact_name'] ?: $lead['business_name'],
    $subject, $htmlBody, [], ['type' => 'seo', 'id' => $lid]);

$autoStatus = ['seo_outreach' => 'contacted', 'seo_proposal' => 'proposal_sent'];
if ($res['ok'] && $tpl && isset($autoStatus[$tpl['letter_type']])) {
    $newStatus = $autoStatus[$tpl['letter_type']];
    $upd = $con->prepare("UPDATE seo_leads SET status = ? WHERE id = ?");
    $upd->bind_param('si', $newStatus, $lid);
    $upd->execute();
}

$msg = $res['ok'] ? "Email sent to {$lead['email']}." : "Email failed: {$res['error']}";
header('Location: ' . $back . '&msg=' . urlencode($msg));
