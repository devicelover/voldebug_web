<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$id              = (int) ($_POST['id'] ?? 0);
$business_name   = trim($_POST['business_name']   ?? '');
$contact_name    = trim($_POST['contact_name']    ?? '');
$email           = trim($_POST['email']           ?? '');
$phone           = trim($_POST['phone']           ?? '');
$website         = trim($_POST['website']         ?? '');
$industry        = trim($_POST['industry']        ?? '');
$monthly_budget  = trim($_POST['monthly_budget']  ?? '');
$services        = trim($_POST['services']        ?? '');
$source          = trim($_POST['source']          ?? '');
$status          = trim($_POST['status']          ?? 'new');
$notes           = trim($_POST['notes']           ?? '');

if ($id > 0) {
    $stmt = $con->prepare("UPDATE seo_leads SET business_name=?, contact_name=?, email=?, phone=?, website=?, industry=?, monthly_budget=?, services=?, source=?, status=?, notes=? WHERE id=?");
    $stmt->bind_param('sssssssssssi',
        $business_name, $contact_name, $email, $phone, $website, $industry,
        $monthly_budget, $services, $source, $status, $notes, $id);
    $stmt->execute();
    header('Location: hr_seo_detail.php?id=' . $id . '&msg=' . urlencode('Saved.'));
} else {
    $stmt = $con->prepare("INSERT INTO seo_leads (business_name, contact_name, email, phone, website, industry, monthly_budget, services, source, status, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssssssss',
        $business_name, $contact_name, $email, $phone, $website, $industry,
        $monthly_budget, $services, $source, $status, $notes);
    $stmt->execute();
    header('Location: hr_seo_detail.php?id=' . (int)$stmt->insert_id . '&msg=' . urlencode('Created.'));
}
