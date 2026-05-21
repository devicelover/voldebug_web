<?php
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

$id = (int) ($_GET['id'] ?? 0);
if (!$id) { http_response_code(404); exit; }

$stmt = $con->prepare("SELECT name, pdf FROM client_career WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row || !$row['pdf']) { http_response_code(404); exit; }

// Whitelist filename so path traversal is impossible.
if (!preg_match('/^[A-Za-z0-9_\-.]+\.pdf$/', $row['pdf'])) {
    http_response_code(400); exit;
}

$abs = __DIR__ . '/images/client_resume_pdfs/' . $row['pdf'];
if (!is_file($abs)) { http_response_code(410); echo 'Resume file missing on disk.'; exit; }

$display = preg_replace('/[^A-Za-z0-9_\-]/', '_', $row['name']) . '_resume.pdf';
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $display . '"');
readfile($abs);
