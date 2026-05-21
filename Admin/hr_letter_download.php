<?php
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/../includes/bootstrap.php';

$id = (int) ($_GET['id'] ?? 0);
if (!$id) { http_response_code(404); exit; }

$stmt = $con->prepare("SELECT * FROM letters_issued WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$l = $stmt->get_result()->fetch_assoc();
if (!$l) { http_response_code(404); exit; }

$abs = VOLDEBUG_ROOT . '/' . ltrim($l['pdf_path'], '/');
if (!is_file($abs)) {
    http_response_code(410); echo 'Letter PDF missing on disk. Regenerate from intern page.'; exit;
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $l['letter_type'] . '_VDB-' . substr($l['verify_token'], 0, 8) . '.pdf"');
readfile($abs);
