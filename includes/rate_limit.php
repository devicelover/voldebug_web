<?php
// Simple DB-backed rate limiter for public POST endpoints.
// Usage:
//     require_once __DIR__ . '/includes/rate_limit.php';
//     rate_limit_or_die('career_apply', 5, 300); // 5 hits per 300s per IP

require_once __DIR__ . '/../config.php';

function _rate_limit_ip_binary(): string {
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP']
        ?? $_SERVER['HTTP_X_FORWARDED_FOR']
        ?? $_SERVER['REMOTE_ADDR']
        ?? '0.0.0.0';
    // If XFF had multiple, keep the first.
    if (strpos($ip, ',') !== false) $ip = trim(explode(',', $ip)[0]);
    $packed = @inet_pton($ip);
    return $packed ?: str_pad('', 4, "\0");
}

/**
 * If the bucket is over limit, send 429 and die. Otherwise record a hit.
 * Only enforced on POST to avoid blocking casual page reloads.
 */
function rate_limit_or_die(string $bucket, int $limit, int $windowSeconds): void {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') return;

    global $con;
    if (!$con instanceof mysqli) return; // fail-open if DB is down

    $ipBin = _rate_limit_ip_binary();

    // Prune old rows ~1% of requests.
    if (mt_rand(1, 100) === 1) {
        $con->query("DELETE FROM rate_limit_hits WHERE hit_at < (NOW() - INTERVAL 1 DAY)");
    }

    $stmt = $con->prepare(
        "SELECT COUNT(*) AS c FROM rate_limit_hits
         WHERE bucket = ? AND ip = ? AND hit_at > (NOW() - INTERVAL ? SECOND)"
    );
    $stmt->bind_param('ssi', $bucket, $ipBin, $windowSeconds);
    $stmt->execute();
    $c = (int) $stmt->get_result()->fetch_assoc()['c'];

    if ($c >= $limit) {
        http_response_code(429);
        header('Retry-After: ' . $windowSeconds);
        die("Too many requests. Please wait a few minutes and try again.");
    }

    $ins = $con->prepare("INSERT INTO rate_limit_hits (bucket, ip) VALUES (?, ?)");
    $ins->bind_param('ss', $bucket, $ipBin);
    $ins->execute();
}
