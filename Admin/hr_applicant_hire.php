<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$applicantId = (int) ($_POST['applicant_id'] ?? 0);
if (!$applicantId) { header('Location: hr_applicants.php?msg=' . urlencode('Missing applicant.')); exit; }

$stmt = $con->prepare("SELECT * FROM client_career WHERE id = ?");
$stmt->bind_param('i', $applicantId);
$stmt->execute();
$appl = $stmt->get_result()->fetch_assoc();
if (!$appl) { header('Location: hr_applicants.php?msg=' . urlencode('Applicant not found.')); exit; }

$role              = trim($_POST['role']              ?? $appl['Position']);
$role_tag          = trim($_POST['role_tag']          ?? 'general');
$start_date        = $_POST['start_date']             ?: null;
$end_date          = $_POST['end_date']               ?: null;
$github_repo       = trim($_POST['github_repo']       ?? '');
$mentor            = trim($_POST['mentor']            ?? '');
$enrollment_number = trim($_POST['enrollment_number'] ?? '');
$internship_type   = trim($_POST['internship_type']   ?? '');
$college           = trim($_POST['college']           ?? '');
$title_prefix      = trim($_POST['title_prefix']      ?? '');
$employee_type     = 'intern';
$status            = 'active';

// Refuse duplicate interns for same email+type.
$chk = $con->prepare("SELECT id FROM interns WHERE email = ? AND employee_type = ? LIMIT 1");
$chk->bind_param('ss', $appl['email'], $employee_type);
$chk->execute();
if ($chk->get_result()->fetch_assoc()) {
    header('Location: hr_applicants.php?msg=' . urlencode('Intern already exists for this email.'));
    exit;
}

$stmt = $con->prepare(
    "INSERT INTO interns
        (applicant_id, title_prefix, name, email, phone, enrollment_number, role, role_tag,
         employee_type, internship_type, college,
         start_date, end_date, github_repo, mentor, status)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param(
    'isssssssssssssss',
    $applicantId, $title_prefix, $appl['name'], $appl['email'], $appl['phone'], $enrollment_number,
    $role, $role_tag, $employee_type, $internship_type, $college,
    $start_date, $end_date, $github_repo, $mentor, $status
);
$stmt->execute();
$newInternId = (int) $stmt->insert_id;

$con->query("UPDATE client_career SET status = 'hired' WHERE id = {$applicantId}");

header('Location: hr_intern_detail.php?id=' . $newInternId . '&msg=' . urlencode('Intern created from applicant.'));
