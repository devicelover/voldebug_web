<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

if (isset($_POST['delete_client'])) {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id <= 0) { header("Location: client_edit.php"); exit; }

    $stmt = $con->prepare("DELETE FROM `clients` WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        header("Location: client_edit.php?msg=" . urlencode("Client deleted."));
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
}
