<?php
// Preview endpoint — renders a certificate PDF in-place WITHOUT inserting a DB row or writing a file.
// Called from:
//   1. hr_certificate_generate.php  (POST: full form → preview with actual entered data)
//   2. hr_certificate_template_edit.php (POST: template body_html → preview with sample recipient)
// The response is a PDF (Content-Type: application/pdf, inline) so the browser shows it in a new tab.

require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/CertificateGenerator.php';

use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;

// Accept either `template_id` (from the generate form) or `id` (from the template edit form).
$template_id = (int) ($_POST['template_id'] ?? $_POST['id'] ?? 0);
$partner_id  = (int) ($_POST['partner_id']  ?? 0);
$mode        = ($_POST['mode'] ?? '') === 'inline_template' ? 'inline_template' : 'form';

// Load persistent template row when a template_id is given (form mode + inline-template-with-id).
$template = null;
if ($template_id > 0) {
    $tStmt = $con->prepare("SELECT * FROM certificate_templates WHERE id = ?");
    $tStmt->bind_param('i', $template_id); $tStmt->execute();
    $template = $tStmt->get_result()->fetch_assoc();
}

// Inline template preview: template body_html comes from the editor textarea, not a DB row.
if ($mode === 'inline_template') {
    $template = $template ?: [
        'id' => 0, 'template_name' => '(unsaved)',
        'title' => trim($_POST['title'] ?? 'Certificate of Completion'),
        'cert_kind' => 'completion',
        'body_html' => (string) ($_POST['body_html'] ?? ''),
        'email_subject' => '', 'email_body' => '',
        'orientation' => ($_POST['orientation'] ?? 'landscape') === 'portrait' ? 'portrait' : 'landscape',
        'qr_enabled' => 1,
    ];
    // Override any DB row with the editor's live values.
    $template['title']       = trim($_POST['title']       ?? $template['title']);
    $template['body_html']   = (string) ($_POST['body_html'] ?? $template['body_html']);
    $template['orientation'] = ($_POST['orientation'] ?? $template['orientation']) === 'portrait' ? 'portrait' : 'landscape';
}

if (!$template) {
    http_response_code(400);
    echo 'Template not found. Pick one first.';
    exit;
}

$partner = null;
if ($partner_id > 0) {
    $pStmt = $con->prepare("SELECT * FROM certificate_partners WHERE id = ?");
    $pStmt->bind_param('i', $partner_id); $pStmt->execute();
    $partner = $pStmt->get_result()->fetch_assoc() ?: null;
}

$settings = $con->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc() ?: [];

// Build input — either from the generate form, or a sample recipient for template preview.
$isSample = $mode === 'inline_template';
$input = [
    'recipient_name'  => $isSample ? 'Ritesh Solanki'          : trim($_POST['recipient_name'] ?? 'Ritesh Solanki'),
    'recipient_email' => $isSample ? 'sample@voldebug.in'      : trim($_POST['recipient_email'] ?? ''),
    'course_name'     => $isSample ? 'AI Fundamentals'          : trim($_POST['course_name']    ?? 'AI Fundamentals'),
    'completion_date' => $_POST['completion_date'] ?? date('Y-m-d'),
    'duration'        => $isSample ? '8 weeks'                  : trim($_POST['duration']       ?? '8 weeks'),
    'custom1'         => trim($_POST['custom1'] ?? ($isSample ? 'Grade A+' : '')),
    'custom2'         => trim($_POST['custom2'] ?? ''),
    'custom3'         => trim($_POST['custom3'] ?? ''),
    'custom4'         => trim($_POST['custom4'] ?? ''),
    'custom5'         => trim($_POST['custom5'] ?? ''),
];

// Guest signatory: form mode only (from form fields). Template preview does not have this.
if (!$isSample) {
    $guestName = trim($_POST['guest_name'] ?? '');
    if ($guestName !== '') {
        $input['guest_signatory'] = [
            'name'            => $guestName,
            'designation'     => trim($_POST['guest_designation']  ?? ''),
            'organization'    => trim($_POST['guest_organization'] ?? ''),
            'signature_image' => '',   // Uploaded images are not saved on preview
        ];
    }
}

// Rebuild the HTML using the CertificateGenerator's internal builder, then render to PDF WITHOUT writing files.
// Simplest path: instantiate a lightweight ReflectionMethod-based accessor. But cleaner: replicate the render
// bootstrapping since CertificateGenerator::generate() also writes to disk + DB. Use a subclass or a static
// helper. Here we take the shortcut: call generate() into a scratch dir, then stream the resulting PDF and
// delete both the file and the DB row it inserted.
$scratchDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'voldebug_cert_preview';
if (!is_dir($scratchDir)) @mkdir($scratchDir, 0755, true);

$gen = new CertificateGenerator($con, $settings, $APP_SECRETS['public_base_url'], $scratchDir);
try {
    $res = $gen->generate($input, $template, $partner, [
        'include_signature' => isset($_POST['include_signature']) && $_POST['include_signature'] === '1',
        'include_stamp'     => isset($_POST['include_stamp'])     && $_POST['include_stamp']     === '1',
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Preview render failed: ' . htmlspecialchars($e->getMessage());
    exit;
}

// Clean up the transient row + file — this is a preview, not an issuance.
$cid = (int) $res['id'];
if ($cid > 0) {
    $con->query("DELETE FROM certificates_issued WHERE id = " . $cid);
}
$streamPath = $res['pdf_path'];

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="preview.pdf"');
header('Cache-Control: no-store, no-cache, must-revalidate');
readfile($streamPath);
@unlink($streamPath);
