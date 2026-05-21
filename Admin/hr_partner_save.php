<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$id              = (int) ($_POST['id'] ?? 0);
$company_name    = trim($_POST['company_name']    ?? '');
$contact_name    = trim($_POST['contact_name']    ?? '');
$title_prefix    = trim($_POST['title_prefix']    ?? '');
$first_name      = trim($_POST['first_name']      ?? '');
$email           = trim($_POST['email']           ?? '');
$phone           = trim($_POST['phone']           ?? '');
$country         = trim($_POST['country']         ?? '');
$city            = trim($_POST['city']            ?? '');
$website         = trim($_POST['website']         ?? '');
$territories     = trim($_POST['territories']     ?? '');
$commission_rate = trim($_POST['commission_rate'] ?? '');
$status          = trim($_POST['status']          ?? 'prospect');
$notes           = trim($_POST['notes']           ?? '');

if ($id > 0) {
    $stmt = $con->prepare("UPDATE key_partners SET company_name=?, contact_name=?, title_prefix=?, first_name=?, email=?, phone=?, country=?, city=?, website=?, territories=?, commission_rate=?, status=?, notes=? WHERE id=?");
    $stmt->bind_param('sssssssssssssi',
        $company_name, $contact_name, $title_prefix, $first_name, $email, $phone,
        $country, $city, $website, $territories, $commission_rate, $status, $notes, $id);
    $stmt->execute();
    header('Location: hr_partner_detail.php?id=' . $id . '&msg=' . urlencode('Saved.'));
} else {
    $stmt = $con->prepare("INSERT INTO key_partners (company_name, contact_name, title_prefix, first_name, email, phone, country, city, website, territories, commission_rate, status, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssssssssss',
        $company_name, $contact_name, $title_prefix, $first_name, $email, $phone,
        $country, $city, $website, $territories, $commission_rate, $status, $notes);
    $stmt->execute();
    header('Location: hr_partner_detail.php?id=' . (int)$stmt->insert_id . '&msg=' . urlencode('Created.'));
}
