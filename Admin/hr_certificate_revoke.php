<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$id     = (int) ($_POST['id'] ?? 0);
$reason = trim($_POST['reason'] ?? 'Revoked by admin.');
if (!$id) { header('Location: hr_certificates.php'); exit; }

$stmt = $con->prepare("UPDATE certificates_issued SET revoked = 1, revoked_reason = ? WHERE id = ?");
$stmt->bind_param('si', $reason, $id);
$stmt->execute();

header('Location: hr_certificates.php?msg=' . urlencode("Certificate #{$id} revoked — verify page now shows 'Revoked'."));
