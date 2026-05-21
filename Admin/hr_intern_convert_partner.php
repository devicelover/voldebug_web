<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) { header("Location: hr_interns.php"); exit; }

$stmt = $con->prepare("SELECT * FROM interns WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$intern = $stmt->get_result()->fetch_assoc();
if (!$intern) { header("Location: hr_interns.php"); exit; }

// Refuse if an active partner record already exists for this email.
$chk = $con->prepare("SELECT id FROM key_partners WHERE email = ? LIMIT 1");
$chk->bind_param('s', $intern['email']);
$chk->execute();
if ($existing = $chk->get_result()->fetch_assoc()) {
    header("Location: hr_partner_detail.php?id=" . (int)$existing['id'] . "&msg=" . urlencode("Partner already exists for this email."));
    exit;
}

// Best-effort population: company_name defaults to "<name>'s Practice" — admin edits after.
$company_name = trim($intern['name'] ?: 'New Partner') . "'s Partnership";
$status       = 'prospect';

$ins = $con->prepare(
    "INSERT INTO key_partners
        (company_name, contact_name, title_prefix, first_name, email, phone, status, notes)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);
$notes = "Promoted from intern record #{$id} on " . date('Y-m-d') . ". Original role: {$intern['role']}. Original GitHub: " . ($intern['github_repo'] ?: '—');
$ins->bind_param('ssssssss',
    $company_name, $intern['name'], $intern['title_prefix'], $intern['first_name'],
    $intern['email'], $intern['phone'], $status, $notes
);
$ins->execute();
$newPartnerId = (int) $ins->insert_id;

// Mark the intern as completed (so they don't show in active interns list anymore).
$con->query("UPDATE interns SET status = 'completed' WHERE id = " . $id);

header("Location: hr_partner_edit.php?id={$newPartnerId}&msg=" . urlencode("Intern promoted to Key Partner — please complete company details, territory and commission terms."));
