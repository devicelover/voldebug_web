<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$keepId = (int) ($_POST['keep_id'] ?? 0);
$email  = trim($_POST['email'] ?? '');
if (!$keepId || $email === '') { header('Location: hr_applicant_dedupe.php'); exit; }

// Pull all rows for that email; delete the ones that aren't the keeper.
$stmt = $con->prepare("SELECT id, pdf FROM client_career WHERE LOWER(email) = LOWER(?)");
$stmt->bind_param('s', $email);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$deleted = 0;
foreach ($rows as $row) {
    if ((int)$row['id'] === $keepId) continue;
    // Delete resume file on disk if present and not shared with the keeper.
    if (!empty($row['pdf'])) {
        $resumePath = __DIR__ . '/images/client_resume_pdfs/' . $row['pdf'];
        if (is_file($resumePath)) {
            // Check that this file isn't also the keeper's resume.
            $check = $con->prepare("SELECT 1 FROM client_career WHERE id = ? AND pdf = ?");
            $check->bind_param('is', $keepId, $row['pdf']);
            $check->execute();
            if (!$check->get_result()->fetch_row()) {
                @unlink($resumePath);
            }
        }
    }
    $del = $con->prepare("DELETE FROM client_career WHERE id = ?");
    $delId = (int) $row['id'];
    $del->bind_param('i', $delId);
    $del->execute();
    $deleted++;
}

header('Location: hr_applicant_dedupe.php?msg=' . urlencode("Kept #{$keepId}, deleted {$deleted} duplicate(s) for {$email}."));
