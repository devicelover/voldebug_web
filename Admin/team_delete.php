<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

if (isset($_POST['delete'])) {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id <= 0) { header("Location: team_edit1.php"); exit; }

    $stmt = $con->prepare("DELETE FROM `team` WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        header("Location: team_edit1.php?msg=" . urlencode("Team member deleted."));
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
}
