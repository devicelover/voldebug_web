<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/LetterGenerator.php';
require_once __DIR__ . '/../includes/Mailer.php';

/**
 * SINGLE-SHOT letter generator.
 *
 * Action contract:
 *   - "generate"          → create the letter once, redirect back to intern detail.
 *   - "generate_download" → create + stream the PDF inline as download in the same hit.
 *   - "generate_email"    → create + send the PDF as an email attachment, redirect back.
 *
 * Each action runs $gen->generate() exactly once, so the issued-letter row is created once.
 * For follow-up actions on existing letters (re-download / re-email), use hr_letter_download.php
 * and hr_letter_email.php — those operate on letters_issued.id and do NOT re-insert.
 */

$intern_id   = (int) ($_POST['intern_id']   ?? 0);
$template_id = (int) ($_POST['template_id'] ?? 0);
$action      = $_POST['action'] ?? 'generate';

$back = 'hr_intern_detail.php?id=' . $intern_id;

if (!$intern_id || !$template_id) {
    header('Location: ' . $back . '&msg=' . urlencode('Missing intern or template.'));
    exit;
}

$stmt = $con->prepare("SELECT * FROM interns WHERE id = ?");
$stmt->bind_param('i', $intern_id);
$stmt->execute();
$intern = $stmt->get_result()->fetch_assoc();

$stmt = $con->prepare("SELECT * FROM letter_templates WHERE id = ?");
$stmt->bind_param('i', $template_id);
$stmt->execute();
$template = $stmt->get_result()->fetch_assoc();

if (!$intern || !$template) {
    header('Location: ' . $back . '&msg=' . urlencode('Not found.'));
    exit;
}

$settings = $con->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc() ?: [];
if (empty($settings['signatory_name'])) {
    header('Location: ' . $back . '&msg=' . urlencode('Configure letterhead (signatory) first in HR → Letterhead.'));
    exit;
}

$gen = new LetterGenerator($con, $settings, $APP_SECRETS['public_base_url'], VOLDEBUG_ROOT . '/Admin/letters');
$opts = [
    'include_signature' => isset($_POST['include_signature']) && $_POST['include_signature'] === '1',
    'include_stamp'     => isset($_POST['include_stamp'])     && $_POST['include_stamp']     === '1',
];

// === Create the letter exactly once ===
$result = $gen->generate($intern, $template, [], $opts);

if ($action === 'generate_download') {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $template['letter_type'] . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $intern['name']) . '.pdf"');
    readfile($result['pdf_path']);
    exit;
}

if ($action === 'generate_email') {
    $mailer = new Mailer($con, $APP_SECRETS['smtp']);
    $htmlBody = nl2br(htmlspecialchars($result['email_body']));
    $send = $mailer->send(
        $intern['email'], $intern['name'],
        $result['email_subject'], $htmlBody,
        (int) $template['attach_pdf'] === 1
            ? [['path' => $result['pdf_path'], 'name' => $template['letter_type'] . '.pdf']]
            : [],
        ['type' => 'letter', 'id' => $result['letter_id']]
    );
    $msg = $send['ok']
        ? 'Letter created (ref VDB-' . substr($result['token'], 0, 10) . ') and emailed to ' . $intern['email'] . '.'
        : 'Letter created but email failed: ' . $send['error'];
    header('Location: ' . $back . '&msg=' . urlencode($msg));
    exit;
}

// Default: "generate" — letter is created, just redirect back. PDF is downloadable
// and emailable from the issued-letters table.
$msg = 'Letter created (ref VDB-' . substr($result['token'], 0, 10) . '). Use Download or Email from the list below.';
header('Location: ' . $back . '&msg=' . urlencode($msg) . '&new=' . (int)$result['letter_id']);
