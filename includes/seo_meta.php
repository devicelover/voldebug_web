<?php
// Shared SEO helpers — call seo_head() inside <head> of landing pages.

function seo_head(array $opts): string {
    $title   = $opts['title']    ?? 'Voldebug';
    $desc    = $opts['desc']     ?? '';
    $keywords= $opts['keywords'] ?? '';
    $canonical = $opts['canonical'] ?? '';
    $ogImage = $opts['og_image'] ?? '/assets/img/logo/logo.png';
    $jsonld  = $opts['jsonld']   ?? null;

    $h  = '<title>' . htmlspecialchars($title) . '</title>' . "\n";
    $h .= '<meta name="description" content="' . htmlspecialchars($desc) . '">' . "\n";
    if ($keywords) $h .= '<meta name="keywords" content="' . htmlspecialchars($keywords) . '">' . "\n";
    $h .= '<meta name="author" content="Voldebug Innovations Pvt. Ltd.">' . "\n";
    $h .= '<meta name="robots" content="index, follow">' . "\n";
    if ($canonical) $h .= '<link rel="canonical" href="' . htmlspecialchars($canonical) . '">' . "\n";
    // Open Graph
    $h .= '<meta property="og:title" content="'       . htmlspecialchars($title) . '">' . "\n";
    $h .= '<meta property="og:description" content="' . htmlspecialchars($desc)  . '">' . "\n";
    $h .= '<meta property="og:type" content="website">' . "\n";
    if ($canonical) $h .= '<meta property="og:url" content="' . htmlspecialchars($canonical) . '">' . "\n";
    if ($ogImage)   $h .= '<meta property="og:image" content="' . htmlspecialchars($ogImage) . '">' . "\n";
    // Twitter card
    $h .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    $h .= '<meta name="twitter:title" content="' . htmlspecialchars($title) . '">' . "\n";
    $h .= '<meta name="twitter:description" content="' . htmlspecialchars($desc) . '">' . "\n";
    // JSON-LD
    if ($jsonld) {
        $h .= '<script type="application/ld+json">' . json_encode($jsonld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
    return $h;
}

/** LocalBusiness JSON-LD — use for Vadodara geo pages */
function jsonld_local_business(string $serviceName, string $serviceUrl): array {
    return [
        '@context' => 'https://schema.org',
        '@type'    => 'LocalBusiness',
        'name'     => 'Voldebug Innovations Pvt. Ltd.',
        'image'    => 'https://voldebug.in/assets/img/logo/logo.png',
        'url'      => $serviceUrl,
        'telephone'=> '+91 9499699693',
        'email'    => 'admin@voldebug.in',
        'priceRange' => '₹₹',
        'address'  => [
            '@type' => 'PostalAddress',
            'streetAddress'   => 'TF/342, Siddharth Business Hub, Vijay Nagar',
            'addressLocality' => 'Vadodara',
            'addressRegion'   => 'Gujarat',
            'postalCode'      => '390009',
            'addressCountry'  => 'IN',
        ],
        'geo' => ['@type' => 'GeoCoordinates', 'latitude' => 22.3072, 'longitude' => 73.1812],
        'areaServed' => ['@type' => 'City', 'name' => 'Vadodara'],
        'sameAs' => ['https://voldebug.in'],
        'makesOffer' => [
            '@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $serviceName],
        ],
    ];
}

/** Service JSON-LD for global service pages */
function jsonld_service(string $name, string $desc, string $url): array {
    return [
        '@context'  => 'https://schema.org',
        '@type'     => 'Service',
        'name'      => $name,
        'description' => $desc,
        'url'       => $url,
        'provider'  => [
            '@type' => 'Organization',
            'name'  => 'Voldebug Innovations Pvt. Ltd.',
            'url'   => 'https://voldebug.in',
            'logo'  => 'https://voldebug.in/assets/img/logo/logo.png',
            'email' => 'admin@voldebug.in',
            'telephone' => '+91 9499699693',
        ],
        'areaServed' => ['@type' => 'Country', 'name' => ['India', 'United States', 'Germany', 'United Kingdom', 'United Arab Emirates']],
    ];
}
