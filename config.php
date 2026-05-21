<?php
// Root DB connection. Reads credentials from includes/secrets.php (gitignored).

$secretsPath = __DIR__ . '/includes/secrets.php';
if (!file_exists($secretsPath)) {
    die('Missing includes/secrets.php — copy includes/secrets.php.example and fill it in.');
}
$SECRETS = require $secretsPath;
$db      = $SECRETS['db'] ?? [];

date_default_timezone_set($SECRETS['timezone'] ?? 'UTC');

$servername = $db['host']     ?? '';
$username   = $db['username'] ?? '';
$password   = $db['password'] ?? '';
$dbname     = $db['database'] ?? '';

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$con->set_charset('utf8mb4');
