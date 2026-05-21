<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

if (isset($_POST['delete_photo'])) {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id <= 0) { header("Location: photo_gallery.php"); exit; }

    // Best-effort: delete the image file from disk before removing the row.
    $stmt = $con->prepare("SELECT image FROM `photo_gallery` WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($row = $stmt->get_result()->fetch_assoc()) {
        $path = __DIR__ . '/images/gallery/' . $row['image'];
        if ($row['image'] && is_file($path)) @unlink($path);
    }

    $del = $con->prepare("DELETE FROM `photo_gallery` WHERE id = ?");
    $del->bind_param('i', $id);
    if ($del->execute()) {
        header("Location: photo_gallery.php?msg=" . urlencode("Photo deleted."));
    } else {
        echo "Error: " . htmlspecialchars($del->error);
    }
}
