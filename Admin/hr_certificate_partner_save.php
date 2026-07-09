<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';
require_once __DIR__ . '/../includes/upload_safe.php';

if (isset($_POST['delete_id'])) {
    $did = (int) $_POST['delete_id'];
    $stmt = $con->prepare("DELETE FROM certificate_partners WHERE id = ?");
    $stmt->bind_param('i', $did); $stmt->execute();
    header('Location: hr_certificate_partners.php?msg=' . urlencode('Partner deleted.'));
    exit;
}

$dir = __DIR__ . '/images/cert_partners/';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$id           = (int) ($_POST['id'] ?? 0);
$name         = trim($_POST['name'] ?? '');
$subtitle     = trim($_POST['subtitle'] ?? '');
$website      = trim($_POST['website'] ?? '');
$sigName      = trim($_POST['signatory_name'] ?? '');
$sigRole      = trim($_POST['signatory_designation'] ?? '');
$is_active    = isset($_POST['is_active']) ? 1 : 0;

$current = ['logo' => '', 'signature_image' => ''];
if ($id > 0) {
    $r = $con->prepare("SELECT logo, signature_image FROM certificate_partners WHERE id = ?");
    $r->bind_param('i', $id); $r->execute();
    if ($row = $r->get_result()->fetch_assoc()) $current = $row;
}
$logoRes = safe_image_upload('logo',            $dir, $current['logo']);
$sigRes  = safe_image_upload('signature_image', $dir, $current['signature_image']);
$uploadErrors = array_filter([$logoRes['error'] ?? null, $sigRes['error'] ?? null]);
if ($uploadErrors) {
    header('Location: hr_certificate_partners.php?msg=' . urlencode('Upload rejected: ' . implode(' ', $uploadErrors)));
    exit;
}
$logo   = $logoRes['name'];
$sigImg = $sigRes['name'];

if ($id > 0) {
    $stmt = $con->prepare("UPDATE certificate_partners SET name=?, subtitle=?, logo=?, website=?, signatory_name=?, signatory_designation=?, signature_image=?, is_active=? WHERE id=?");
    $stmt->bind_param('sssssssii', $name, $subtitle, $logo, $website, $sigName, $sigRole, $sigImg, $is_active, $id);
    $stmt->execute();
    header('Location: hr_certificate_partners.php?msg=' . urlencode('Partner saved.'));
} else {
    $stmt = $con->prepare("INSERT INTO certificate_partners (name, subtitle, logo, website, signatory_name, signatory_designation, signature_image, is_active) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssssi', $name, $subtitle, $logo, $website, $sigName, $sigRole, $sigImg, $is_active);
    $stmt->execute();
    header('Location: hr_certificate_partners.php?msg=' . urlencode('Partner created.'));
}
