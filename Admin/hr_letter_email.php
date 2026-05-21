<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';

/**
 * Email an EXISTING issued letter — does NOT create a new letters_issued row.
 * Pulls the PDF from disk + re-renders the email body from the template.
 */

$letter_id = (int) ($_POST['letter_id'] ?? 0);
if (!$letter_id) { header('Location: hr_interns.php'); exit; }

$stmt = $con->prepare(
    "SELECT l.*, t.email_subject, t.email_body, t.attach_pdf,
            i.name AS intern_name, i.email AS intern_email, i.title_prefix, i.first_name,
            i.role, i.start_date, i.end_date, i.tasks_summary, i.github_repo, i.mentor,
            i.enrollment_number, i.internship_type, i.college, i.stipend, i.reporting_location
     FROM letters_issued l
     JOIN letter_templates t ON t.id = l.template_id
     JOIN interns i ON i.id = l.intern_id
     WHERE l.id = ?"
);
$stmt->bind_param('i', $letter_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) { header('Location: hr_interns.php'); exit; }

$back = 'hr_intern_detail.php?id=' . (int) $row['intern_id'];

if ((int) $row['revoked'] === 1) {
    header('Location: ' . $back . '&msg=' . urlencode('Cannot email a revoked letter.'));
    exit;
}

$abs = VOLDEBUG_ROOT . '/' . ltrim($row['pdf_path'], '/');
if (!is_file($abs)) {
    header('Location: ' . $back . '&msg=' . urlencode('Letter PDF missing on disk — regenerate.'));
    exit;
}

// Re-render the email body with the intern's data so {{honorific_name}} etc. resolve.
[$pSubj, $pObj, $pPoss, $first, $hFirst, $hFull] = derive_pronouns([
    'title_prefix' => $row['title_prefix'] ?? '',
    'first_name'   => $row['first_name']   ?? '',
    'name'         => $row['intern_name']  ?? '',
]);
$companyLegal = $APP_SETTINGS['company_legal_name'] ?: ($APP_SETTINGS['name'] ?? 'Voldebug');
$verifyUrl    = rtrim($APP_SECRETS['public_base_url'], '/') . '/verify.php?t=' . urlencode($row['verify_token']);
$acceptUrl    = rtrim($APP_SECRETS['public_base_url'], '/') . '/accept-offer.php?t=' . urlencode($row['verify_token']);

$vars = [
    'name'                => $row['intern_name'],
    'first_name'          => $first,
    'honorific_name'      => $hFirst,
    'honorific_full_name' => $hFull,
    'role'                => $row['role'],
    'role_snapshot'       => $row['role_snapshot'],
    'start_date'          => $row['start_date'] ? date('jS F Y', strtotime($row['start_date'])) : '',
    'end_date'            => $row['end_date']   ? date('jS F Y', strtotime($row['end_date']))   : '',
    'tasks_summary'       => $row['tasks_summary']     ?? '',
    'enrollment_number'   => $row['enrollment_number'] ?? '',
    'college'             => $row['college']           ?? '',
    'internship_type'     => $row['internship_type']   ?? '',
    'stipend'             => $row['stipend']           ?? '',
    'reporting_location'  => $row['reporting_location']?? '',
    'github_repo'         => $row['github_repo']       ?? '',
    'mentor'              => $row['mentor']            ?? '',
    'company'             => $companyLegal,
    'signatory'           => $APP_SETTINGS['signatory_name']        ?? '',
    'signatory_role'      => $APP_SETTINGS['signatory_designation'] ?? '',
    'issue_date'          => date('jS F Y', strtotime($row['issue_date'])),
    'verify_url'          => $verifyUrl,
    'accept_url'          => $acceptUrl,
    'pronoun_subject'     => $pSubj,    'Pronoun_subject'    => ucfirst($pSubj),
    'pronoun_object'      => $pObj,     'Pronoun_object'     => ucfirst($pObj),
    'pronoun_possessive'  => $pPoss,    'Pronoun_possessive' => ucfirst($pPoss),
];

$subject  = render_placeholders($row['email_subject'], $vars);
$plain    = render_placeholders($row['email_body'],    $vars);
$htmlBody = nl2br(htmlspecialchars($plain));

$attach = (int)$row['attach_pdf'] === 1
    ? [['path' => $abs, 'name' => $row['letter_type'] . '.pdf']]
    : [];

$mailer = new Mailer($con, $APP_SECRETS['smtp']);
$res = $mailer->send(
    $row['intern_email'], $row['intern_name'],
    $subject, $htmlBody, $attach,
    ['type' => 'letter', 'id' => $letter_id]
);

$msg = $res['ok']
    ? 'Letter VDB-' . substr($row['verify_token'], 0, 10) . ' emailed to ' . $row['intern_email'] . '.'
    : 'Email failed: ' . $res['error'];
header('Location: ' . $back . '&msg=' . urlencode($msg));
