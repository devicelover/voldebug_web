<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$intern_id = (int) ($_POST['intern_id'] ?? 0);
$back      = 'hr_intern_detail.php?id=' . $intern_id;

if (isset($_POST['delete_id'])) {
    $did = (int) $_POST['delete_id'];
    $stmt = $con->prepare("DELETE FROM intern_checkins WHERE id = ? AND intern_id = ?");
    $stmt->bind_param('ii', $did, $intern_id);
    $stmt->execute();
    header('Location: ' . $back . '&msg=' . urlencode('Check-in deleted.'));
    exit;
}

$week    = $_POST['week_starting'] ?? date('Y-m-d');
$notes   = trim($_POST['notes'] ?? '');
$rating  = $_POST['rating'] !== '' ? (int) $_POST['rating'] : null;

if ($rating !== null && ($rating < 1 || $rating > 5)) $rating = null;
if ($notes === '' || !$intern_id) { header('Location: ' . $back); exit; }

// Upsert on (intern_id, week_starting).
$stmt = $con->prepare(
    "INSERT INTO intern_checkins (intern_id, week_starting, notes, rating)
     VALUES (?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE notes = VALUES(notes), rating = VALUES(rating)"
);
$stmt->bind_param('issi', $intern_id, $week, $notes, $rating);
$stmt->execute();

header('Location: ' . $back . '&msg=' . urlencode('Check-in saved.'));
