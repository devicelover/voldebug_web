<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$id                = (int) ($_POST['id'] ?? 0);
$title_prefix      = trim($_POST['title_prefix']      ?? '');
$name              = trim($_POST['name']              ?? '');
$first_name        = trim($_POST['first_name']        ?? '');
$email             = trim($_POST['email']             ?? '');
$phone             = trim($_POST['phone']             ?? '');
$enrollment_number = trim($_POST['enrollment_number'] ?? '');
$role              = trim($_POST['role']              ?? '');
$role_tag          = trim($_POST['role_tag']          ?? 'general');
$employee_type     = trim($_POST['employee_type']     ?? 'intern');
$internship_type   = trim($_POST['internship_type']   ?? '');
$college           = trim($_POST['college']           ?? '');
$start_date        = $_POST['start_date'] ?: null;
$end_date          = $_POST['end_date']   ?: null;
$github_repo       = trim($_POST['github_repo']       ?? '');
$linkedin_url      = trim($_POST['linkedin_url']      ?? '');
$mentor            = trim($_POST['mentor']            ?? '');
$stipend           = trim($_POST['stipend']            ?? '');
$reporting_location= trim($_POST['reporting_location'] ?? '');
$tasks_summary     = trim($_POST['tasks_summary']     ?? '');
$performance       = trim($_POST['performance_notes'] ?? '');
$status            = trim($_POST['status']            ?? 'active');

if ($id > 0) {
    $stmt = $con->prepare(
        "UPDATE interns SET title_prefix=?, name=?, first_name=?, email=?, phone=?,
            enrollment_number=?, role=?, role_tag=?,
            employee_type=?, internship_type=?, college=?,
            start_date=?, end_date=?, github_repo=?, linkedin_url=?, mentor=?,
            stipend=?, reporting_location=?,
            tasks_summary=?, performance_notes=?, status=?
         WHERE id = ?"
    );
    $stmt->bind_param(
        'sssssssssssssssssssssi',
        $title_prefix, $name, $first_name, $email, $phone,
        $enrollment_number, $role, $role_tag,
        $employee_type, $internship_type, $college,
        $start_date, $end_date, $github_repo, $linkedin_url, $mentor,
        $stipend, $reporting_location,
        $tasks_summary, $performance, $status, $id
    );
    $stmt->execute();
    header('Location: hr_intern_detail.php?id=' . $id . '&msg=' . urlencode('Saved.'));
} else {
    $stmt = $con->prepare(
        "INSERT INTO interns
            (title_prefix, name, first_name, email, phone,
             enrollment_number, role, role_tag,
             employee_type, internship_type, college,
             start_date, end_date, github_repo, linkedin_url, mentor,
             stipend, reporting_location,
             tasks_summary, performance_notes, status)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
    );
    $stmt->bind_param(
        'sssssssssssssssssssss',
        $title_prefix, $name, $first_name, $email, $phone,
        $enrollment_number, $role, $role_tag,
        $employee_type, $internship_type, $college,
        $start_date, $end_date, $github_repo, $linkedin_url, $mentor,
        $stipend, $reporting_location,
        $tasks_summary, $performance, $status
    );
    $stmt->execute();
    $nid = (int) $stmt->insert_id;
    header('Location: hr_intern_detail.php?id=' . $nid . '&msg=' . urlencode('Created.'));
}
