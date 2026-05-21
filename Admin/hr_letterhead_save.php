<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }

require_once __DIR__ . '/partials/config.php';

$dir = __DIR__ . '/images/letterhead/';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

function handle_upload(string $field, string $dir, array $allowed, ?string $existing): ?string {
    if (empty($_FILES[$field]['name'])) return $existing;
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) return $existing;
    $fname = $field . '_' . time() . '.' . $ext;
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $dir . $fname)) return $existing;
    // Delete old file (best-effort).
    if ($existing && is_file($dir . $existing)) @unlink($dir . $existing);
    return $fname;
}

$current = $con->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc() ?: [];
$logo = handle_upload('logo',            $dir, $allowed, $current['logo']            ?? '');
$sig  = handle_upload('signature_image', $dir, $allowed, $current['signature_image'] ?? '');
$stmp = handle_upload('stamp_image',     $dir, $allowed, $current['stamp_image']     ?? '');

$signatory_name        = trim($_POST['signatory_name']        ?? '');
$signatory_designation = trim($_POST['signatory_designation'] ?? '');
$hr_email              = trim($_POST['hr_email']              ?? 'hr@voldebug.in');
$admin_email           = trim($_POST['admin_email']           ?? '');
$phone                 = trim($_POST['phone']                 ?? '');
$letterhead_address    = trim($_POST['letterhead_address']    ?? '');
$company_legal_name    = trim($_POST['company_legal_name']    ?? 'Voldebug Innovations Pvt. Ltd.');
$cin                   = trim($_POST['cin']                   ?? '');
$website               = trim($_POST['website']               ?? '');
$brand_color           = trim($_POST['brand_color']           ?? '#1a8f4a');
if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $brand_color)) $brand_color = '#1a8f4a';

// Ensure a row with id=1 exists, then update.
if (!$current) {
    $con->query("INSERT INTO settings (id, name) VALUES (1, 'Voldebug')");
}

$stmt = $con->prepare(
    "UPDATE settings SET
        logo=?, signature_image=?, stamp_image=?,
        signatory_name=?, signatory_designation=?, hr_email=?, admin_email=?, phone=?, letterhead_address=?,
        company_legal_name=?, cin=?, website=?, brand_color=?
     WHERE id = 1"
);
$stmt->bind_param(
    'sssssssssssss',
    $logo, $sig, $stmp,
    $signatory_name, $signatory_designation, $hr_email, $admin_email, $phone, $letterhead_address,
    $company_legal_name, $cin, $website, $brand_color
);
$stmt->execute();

header('Location: hr_letterhead.php?msg=saved');
