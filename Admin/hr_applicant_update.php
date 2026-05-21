<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/Mailer.php';

$id     = (int) ($_POST['id'] ?? 0);
$status = trim($_POST['status'] ?? 'applied');
$inform = !empty($_POST['inform_applicant']);   // only email when admin explicitly ticked the box
$allowed = ['applied', 'reviewed', 'shortlisted', 'hired', 'rejected', 'withdrawn'];
if (!in_array($status, $allowed, true)) $status = 'applied';

// Fetch applicant + old status so we know what transitioned.
$stmt = $con->prepare("SELECT * FROM client_career WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$applicant = $stmt->get_result()->fetch_assoc();
if (!$applicant) { header('Location: hr_applicants.php'); exit; }
$oldStatus = $applicant['status'] ?? 'applied';

$upd = $con->prepare("UPDATE client_career SET status = ? WHERE id = ?");
$upd->bind_param('si', $status, $id);
$upd->execute();

// Auto-email is now OPT-IN: admin must tick the "inform by email" checkbox.
// We still gate by status type (only candidate-facing transitions get the email).
$candidateFacing = ['shortlisted', 'rejected', 'hired'];
$msgNote = 'Status updated.';

if ($inform && $status !== $oldStatus && in_array($status, $candidateFacing, true)) {
    // Map status -> template letter_type. We reuse the 'rejection' template for rejected,
    // and reuse 'offer' for shortlisted (closest match). Fall back silently if not found.
    $typeMap = ['rejected' => 'rejection', 'shortlisted' => 'offer', 'hired' => 'offer'];
    $type    = $typeMap[$status] ?? null;

    if ($type) {
        $q = $con->prepare(
            "SELECT * FROM letter_templates
             WHERE letter_type = ? AND role_tag = 'general' AND is_active = 1
             ORDER BY id LIMIT 1"
        );
        $q->bind_param('s', $type);
        $q->execute();
        $tpl = $q->get_result()->fetch_assoc();

        if ($tpl) {
            // Derive honorific + pronouns from the applicant row (no title_prefix on applicants;
            // we fall back to neutral pronouns and first-name salutation).
            [$pSubj, $pObj, $pPoss, $first, $hFirst, $hFull] = derive_pronouns([
                'title_prefix' => '',
                'first_name'   => '',
                'name'         => $applicant['name'],
            ]);
            $companyLegal = $APP_SETTINGS['company_legal_name'] ?: ($APP_SETTINGS['name'] ?? 'Voldebug');

            $vars = [
                'name'                => $applicant['name'],
                'first_name'          => $first,
                'honorific_name'      => $hFirst,
                'honorific_full_name' => $hFull,
                'role'                => $applicant['Position'],
                'company'             => $companyLegal,
                'signatory'           => $APP_SETTINGS['signatory_name']        ?? '',
                'signatory_role'      => $APP_SETTINGS['signatory_designation'] ?? '',
                'pronoun_subject'     => $pSubj,
                'pronoun_object'      => $pObj,
                'pronoun_possessive'  => $pPoss,
                'Pronoun_subject'     => ucfirst($pSubj),
                'Pronoun_object'      => ucfirst($pObj),
                'Pronoun_possessive'  => ucfirst($pPoss),
                'start_date'          => '',
                'end_date'            => '',
                'issue_date'          => date('jS F Y'),
                // Non-letter email: explicitly empty so {{#IF}} blocks collapse.
                'verify_url'          => '',
                'accept_url'          => '',
                'tasks_summary'       => '',
                'github_repo'         => '',
                'enrollment_number'   => '',
                'internship_type'     => '',
                'college'             => '',
                'stipend'             => '',
                'reporting_location'  => '',
                'mentor'              => '',
            ];
            $subject = render_placeholders($tpl['email_subject'], $vars);
            $body    = nl2br(htmlspecialchars(render_placeholders($tpl['email_body'], $vars)));

            $mailer = new Mailer($con, $APP_SECRETS['smtp']);
            $res = $mailer->send(
                $applicant['email'], $applicant['name'], $subject, $body,
                [], ['type' => 'applicant_status', 'id' => $id]
            );
            $msgNote = $res['ok']
                ? "Status updated and candidate emailed ({$status})."
                : "Status updated but email failed: " . $res['error'];
        }
    }
}

header('Location: hr_applicants.php?msg=' . urlencode($msgNote));
