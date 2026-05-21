<?php
// Dynamic sitemap. Serves text/xml. Point Google Search Console at /sitemap.php.
// Includes static pages + every row from career, blog, projects, services.

require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/xml; charset=utf-8');

$base = rtrim($APP_SECRETS['public_base_url'] ?? '', '/');

$urls = [];
$static = [
    '/'                      => ['weekly',  '1.0'],
    '/about.php'             => ['monthly', '0.7'],
    '/service.php'           => ['weekly',  '0.9'],
    '/career.php'            => ['daily',   '0.9'],
    '/blog.php'              => ['weekly',  '0.7'],
    '/contact.php'           => ['monthly', '0.5'],
    '/faq.php'               => ['monthly', '0.5'],
    '/portfolio.php'         => ['weekly',  '0.7'],
    '/team.php'              => ['monthly', '0.5'],
    '/pricing.php'           => ['monthly', '0.5'],
    '/ai-school.php'         => ['weekly',  '0.8'],
    '/application-status.php'=> ['monthly', '0.3'],
    // High-intent geo + service landing pages
    '/cctv-vadodara.php'         => ['weekly',  '0.95'],
    '/cybersecurity-vadodara.php'=> ['weekly',  '0.95'],
    '/web-development.php'       => ['weekly',  '0.9'],
    '/mobile-apps.php'           => ['weekly',  '0.9'],
];

foreach ($static as $path => [$freq, $pri]) {
    $urls[] = ['loc' => $base . $path, 'changefreq' => $freq, 'priority' => $pri];
}

// Careers
$r = $con->query("SELECT id FROM career");
while ($row = $r->fetch_assoc()) {
    $urls[] = ['loc' => $base . '/career-details.php?id=' . (int) $row['id'], 'changefreq' => 'weekly', 'priority' => '0.8'];
}

// Blog
$r = $con->query("SELECT id FROM blog WHERE status = 'Active'");
while ($row = $r->fetch_assoc()) {
    $urls[] = ['loc' => $base . '/blog-details.php?id=' . (int) $row['id'], 'changefreq' => 'monthly', 'priority' => '0.6'];
}

// Projects
$r = $con->query("SELECT id FROM projects WHERE toggle = 'Active'");
while ($row = $r->fetch_assoc()) {
    $urls[] = ['loc' => $base . '/project-details.php?id=' . (int) $row['id'], 'changefreq' => 'monthly', 'priority' => '0.6'];
}

// Services
$r = $con->query("SELECT id FROM services WHERE toggle = 'Active'");
while ($row = $r->fetch_assoc()) {
    $urls[] = ['loc' => $base . '/service-details.php?id=' . (int) $row['id'], 'changefreq' => 'monthly', 'priority' => '0.7'];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($u['loc']) . "</loc>\n";
    echo "    <changefreq>" . $u['changefreq'] . "</changefreq>\n";
    echo "    <priority>" . $u['priority'] . "</priority>\n";
    echo "  </url>\n";
}
echo '</urlset>' . "\n";
