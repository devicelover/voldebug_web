<?php
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }
require_once __DIR__ . '/partials/config.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="newsletter_subscribers_' . date('Y-m-d') . '.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['id', 'email', 'name', 'source', 'status', 'subscribed_at']);
$r = $con->query("SELECT id, email, name, source, status, created_at FROM newsletter_subscribers ORDER BY id DESC");
while ($row = $r->fetch_assoc()) {
    fputcsv($out, [$row['id'], $row['email'], $row['name'], $row['source'], $row['status'], $row['created_at']]);
}
fclose($out);
