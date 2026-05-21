<?php
// One-liner traffic tracker. Public pages do: require_once __DIR__ . '/includes/track_view.php';
// Skips bots, fails silently if DB is down. Adds ~1-2ms per request.

require_once __DIR__ . '/../config.php';

(function () use ($con) {
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if ($ua === '' || preg_match('/(bot|crawl|spider|slurp|preview|fetch|monitor|httpx|curl|wget)/i', $ua)) return;
    if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'GET') return;

    $path  = parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
    if (strlen($path) > 255) $path = substr($path, 0, 255);
    if (str_contains($path, '/Admin/') || str_contains($path, '/admin/')) return;
    if (preg_match('/\.(css|js|png|jpg|jpeg|svg|gif|ico|woff2?|ttf|eot|map|webp)$/i', $path)) return;

    $query    = (string) ($_SERVER['QUERY_STRING'] ?? '');
    if (strlen($query) > 255) $query = substr($query, 0, 255);
    $referrer = substr((string) ($_SERVER['HTTP_REFERER'] ?? ''), 0, 500);
    $ip       = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    if (str_contains($ip, ',')) $ip = trim(explode(',', $ip)[0]);
    $ipBin    = @inet_pton($ip) ?: str_repeat("\0", 4);
    $uaShort  = substr($ua, 0, 60);
    $country  = strtoupper(substr((string)($_SERVER['HTTP_CF_IPCOUNTRY'] ?? ''), 0, 2));

    try {
        // VARBINARY can be passed as a string type to bind_param; mysqli ships the bytes as-is.
        $stmt = $con->prepare("INSERT INTO page_views (path, query, referrer, ip, ua_short, country) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('ssssss', $path, $query, $referrer, $ipBin, $uaShort, $country);
            @$stmt->execute();
        }
    } catch (Throwable $e) {
        // fail silently — analytics must never break the page
    }
})();
