<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) { header("Location: hr_interns.php"); exit; }

// Cascade clean-up of dependent rows + on-disk letter PDFs.
$letters = $con->prepare("SELECT id, pdf_path FROM letters_issued WHERE intern_id = ?");
$letters->bind_param('i', $id);
$letters->execute();
foreach ($letters->get_result()->fetch_all(MYSQLI_ASSOC) as $l) {
    $abs = __DIR__ . '/../' . ltrim((string)$l['pdf_path'], '/');
    if (is_file($abs)) @unlink($abs);
    // Wipe responses referencing this letter.
    $stmt = $con->prepare("DELETE FROM letter_responses WHERE letter_id = ?");
    $stmt->bind_param('i', $l['id']); $stmt->execute();
}
$con->query("DELETE FROM letters_issued  WHERE intern_id = $id");
$con->query("DELETE FROM intern_checkins WHERE intern_id = $id");

$stmt = $con->prepare("DELETE FROM interns WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

header("Location: hr_interns.php?msg=" . urlencode("Intern #{$id} deleted (including letters + check-ins)."));
