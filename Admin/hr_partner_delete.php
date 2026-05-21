<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) { header("Location: hr_partners.php"); exit; }

$stmt = $con->prepare("DELETE FROM key_partners WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

header("Location: hr_partners.php?msg=" . urlencode("Partner deleted."));
