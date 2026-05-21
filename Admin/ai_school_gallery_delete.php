<?php
include 'partials/session.php';
include 'partials/config.php';
include 'authentication.php';

if (isset($_POST['delete_ai_school_photo'])) {
    $id = (int) $_POST['id'];
    $stmt = $con->prepare("DELETE FROM ai_school_gallery WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: ai_school_gallery.php");
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    header("Location: ai_school_gallery.php");
}
exit();
