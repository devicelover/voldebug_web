<?php
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/../includes/bootstrap.php';

$id = (int) ($_GET['id'] ?? 0);
if (!$id) { http_response_code(404); exit; }

$stmt = $con->prepare("SELECT recipient_name, pdf_path, verify_token FROM certificates_issued WHERE id = ?");
$stmt->bind_param('i', $id); $stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) { http_response_code(404); exit; }

$abs = VOLDEBUG_ROOT . '/' . ltrim($row['pdf_path'], '/');
if (!is_file($abs)) { http_response_code(410); echo 'Certificate PDF missing on disk.'; exit; }

$name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $row['recipient_name']);
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="certificate_' . $name . '_' . substr($row['verify_token'], 0, 8) . '.pdf"');
readfile($abs);
