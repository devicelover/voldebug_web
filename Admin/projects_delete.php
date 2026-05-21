<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

if (isset($_POST['delete_project'])) {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id <= 0) { header("Location: projects_edit.php"); exit; }

    $stmt = $con->prepare("DELETE FROM `projects` WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        header("Location: projects_edit.php?msg=" . urlencode("Project deleted."));
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
}
