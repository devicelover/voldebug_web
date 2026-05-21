<?php
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/LetterGenerator.php';

$settings = $con->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc() ?: [];
$template = $con->query("SELECT * FROM letter_templates WHERE letter_type='joining' AND role_tag='general' LIMIT 1")->fetch_assoc();

if (!$template) {
    http_response_code(500);
    die('No joining-letter template found.');
}

$fakeIntern = [
    'id'            => 0,
    'name'          => 'Sample Candidate',
    'email'         => 'sample@example.com',
    'role'          => 'Web Development Intern',
    'start_date'    => date('Y-m-d', strtotime('+7 days')),
    'end_date'      => date('Y-m-d', strtotime('+97 days')),
    'tasks_summary' => 'Front-end tasks, code reviews, documentation.',
    'github_repo'   => 'https://github.com/voldebug/sample',
    'mentor'        => $settings['signatory_name'] ?? 'HR Team',
];

$gen = new LetterGenerator($con, $settings, $APP_SECRETS['public_base_url'], VOLDEBUG_ROOT . '/Admin/letters');

// Preview-only: build HTML without persisting. We call the public generate() but
// then delete the DB row + PDF so previews don't pollute letters_issued.
$result = $gen->generate($fakeIntern, $template);
$con->query("DELETE FROM letters_issued WHERE id = " . (int) $result['letter_id']);
if (is_file($result['pdf_path'])) @unlink($result['pdf_path']);

header('Content-Type: text/html; charset=utf-8');
echo $result['html'];
