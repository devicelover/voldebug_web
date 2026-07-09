<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$id     = (int) ($_POST['id'] ?? 0);
$action = ($_POST['action'] ?? 'revoke') === 'unrevoke' ? 'unrevoke' : 'revoke';
$reason = trim((string) ($_POST['reason'] ?? ''));
$actor  = (string) ($_SESSION['username'] ?? 'admin');
if (!$id) { header('Location: hr_certificates.php'); exit; }

if ($action === 'unrevoke') {
    $stmt = $con->prepare("UPDATE certificates_issued SET revoked = 0, revoked_reason = NULL, revoked_at = NULL, revoked_by = '' WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: hr_certificates.php?msg=' . urlencode("Certificate #{$id} restored — verify page will show 'Authentic' again."));
    exit;
}

if ($reason === '') $reason = 'Revoked by admin.';
$stmt = $con->prepare("UPDATE certificates_issued SET revoked = 1, revoked_reason = ?, revoked_at = NOW(), revoked_by = ? WHERE id = ?");
$stmt->bind_param('ssi', $reason, $actor, $id);
$stmt->execute();

header('Location: hr_certificates.php?msg=' . urlencode("Certificate #{$id} revoked — verify page now shows 'Revoked'."));
