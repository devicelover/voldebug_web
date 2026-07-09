<?php
session_start();
if (!isset($_SESSION["loggedin"])) { header("Location: auth-login.php"); exit; }

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="certificate_bulk_template.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['name','email','course_name','completion_date','duration','custom1','custom2','custom3','custom4','custom5']);
fputcsv($out, ['Komal Shah',  'komal@example.com',  'AI Fundamentals Bootcamp', '2026-04-30', '12 weeks', 'Grade A+', '', '', '', '']);
fputcsv($out, ['Rutu Patel',  'rutu@example.com',   'Cybersecurity 101',         '2026-04-30', '8 weeks',  'Distinction', '', '', '', '']);
fputcsv($out, ['Jordan Taylor','jordan@example.com','AI Fundamentals Bootcamp', '2026-04-30', '12 weeks', 'Merit', '', '', '', '']);
fclose($out);
