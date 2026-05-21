<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';

$aid       = (int) ($_POST['applicant_id'] ?? 0);
$fromLabel = trim($_POST['from_label'] ?? 'hr');
$subject   = trim($_POST['subject']    ?? '');
$body      = (string) ($_POST['body']  ?? '');
$action    = $_POST['action'] ?? 'preview';

if (!$aid || $subject === '' || $body === '') { header('Location: hr_applicants.php'); exit; }

$stmt = $con->prepare("SELECT * FROM client_career WHERE id = ?");
$stmt->bind_param('i', $aid); $stmt->execute();
$applicant = $stmt->get_result()->fetch_assoc();
if (!$applicant) { header('Location: hr_applicants.php'); exit; }

$sig = $con->prepare("SELECT name, email FROM signatories WHERE label = ? LIMIT 1");
$sig->bind_param('s', $fromLabel); $sig->execute();
$signatory = $sig->get_result()->fetch_assoc() ?: ['name' => 'Voldebug HR', 'email' => 'hr@voldebug.in'];

[$pSubj, $pObj, $pPoss, $first, $hFirst, $hFull] = derive_pronouns([
    'title_prefix' => '', 'first_name' => '', 'name' => $applicant['name'],
]);

$vars = [
    'name'                => $applicant['name'],
    'first_name'          => $first,
    'honorific_name'      => $hFirst,
    'honorific_full_name' => $hFull,
    'email'               => $applicant['email'],
    'role'                => $applicant['Position'],
    'company'             => $APP_SETTINGS['company_legal_name'] ?? 'Voldebug',
    'signatory'           => $signatory['name'],
];
$personalSubject = render_placeholders($subject, $vars);
$personalBody    = render_placeholders($body,    $vars);

if ($action === 'preview') {
    header('Content-Type: text/html; charset=utf-8');
    echo "<!doctype html><html><head><meta charset='utf-8'><title>Preview</title>"
       . "<style>body{font-family:system-ui,sans-serif;max-width:680px;margin:40px auto;padding:0 20px}"
       . ".meta{background:#f5f7fb;padding:12px;border-radius:8px;margin-bottom:18px;font-size:14px}"
       . ".body{background:#fff;border:1px solid #eef;padding:24px;border-radius:8px;line-height:1.55}</style></head><body>"
       . "<div class='meta'><strong>From:</strong> " . htmlspecialchars($signatory['name']) . " &lt;" . htmlspecialchars($signatory['email']) . "&gt;"
       . "<br><strong>To:</strong> " . htmlspecialchars($applicant['name']) . " &lt;" . htmlspecialchars($applicant['email']) . "&gt;"
       . "<br><strong>Subject:</strong> " . htmlspecialchars($personalSubject)
       . "<br><a href='hr_applicants.php'>← back</a></div>"
       . "<div class='body'>" . nl2br($personalBody) . "</div></body></html>";
    exit;
}

$mailer = new Mailer($con, $APP_SECRETS['smtp']);
$res = $mailer->send(
    $applicant['email'], $applicant['name'],
    $personalSubject, nl2br($personalBody),
    [], ['type' => 'applicant_custom', 'id' => $aid],
    ['from_email' => $signatory['email'], 'from_name' => $signatory['name'], 'reply_to' => $signatory['email']]
);
$msg = $res['ok'] ? "Email sent to {$applicant['email']} (from {$signatory['name']})." : "Failed: {$res['error']}";
header('Location: hr_applicants.php?msg=' . urlencode($msg));
