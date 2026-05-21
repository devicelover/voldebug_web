<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

if (!isset($_POST['submit'])) { header('Location: settings.php'); exit; }

$name          = trim($_POST['name']          ?? '');
$phone         = trim($_POST['phone']         ?? '');
$email         = trim($_POST['email']         ?? '');
$map           = trim($_POST['map']           ?? '');
$address       = trim($_POST['address']       ?? '');
$address_two   = trim($_POST['address_two']   ?? '');
$facebook      = trim($_POST['facebook']      ?? '');
$instagram     = trim($_POST['instagram']     ?? '');
$github        = trim($_POST['github']        ?? '');
$linkedin      = trim($_POST['linkedin']      ?? '');
$twitter       = trim($_POST['twitter']       ?? '');
$opening_hours = trim($_POST['opening_hours'] ?? '');
$opening_day   = trim($_POST['opening_day']   ?? '');

// Ensure row with id=1 exists, then UPDATE via prepared statement.
$exists = (int) $con->query("SELECT COUNT(*) c FROM settings WHERE id = 1")->fetch_assoc()['c'];
if ($exists === 0) {
    $con->query("INSERT INTO settings (id, name) VALUES (1, 'Voldebug')");
}

$stmt = $con->prepare(
    "UPDATE settings SET
        name = ?, phone = ?, email = ?, opening_hours = ?, opening_day = ?,
        map = ?, address = ?, address_two = ?,
        facebook = ?, instagram = ?, github = ?, linkedin = ?, twitter = ?
     WHERE id = 1"
);
$stmt->bind_param('sssssssssssss',
    $name, $phone, $email, $opening_hours, $opening_day,
    $map, $address, $address_two,
    $facebook, $instagram, $github, $linkedin, $twitter
);
if ($stmt->execute()) {
    header('Location: settings.php?msg=' . urlencode('Settings saved.'));
    exit;
}
echo "Error updating settings: " . htmlspecialchars($stmt->error);
