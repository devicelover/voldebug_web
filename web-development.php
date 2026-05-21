<?php
require_once __DIR__ . '/includes/track_view.php';
require_once __DIR__ . '/includes/captcha.php';
require_once __DIR__ . '/includes/seo_meta.php';
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?= seo_head([
    'title'     => 'Web Development Company — Custom Websites & Web Apps | Voldebug',
    'desc'      => 'Voldebug builds custom websites, e-commerce stores, SaaS dashboards and web apps for clients in India, US, UK, Germany and UAE. Fixed-price packages from ₹50k.',
    'keywords'  => 'web development company India, custom website development, e-commerce development, SaaS development, web app developers',
    'canonical' => 'https://voldebug.in/web-development.php',
    'jsonld'    => jsonld_service(
        'Web Development Services',
        'Custom websites, e-commerce platforms, SaaS dashboards and web applications built end-to-end by Voldebug Innovations.',
        'https://voldebug.in/web-development.php'
    ),
]); ?>
<link rel="icon" type="image/png" href="assets/img/logo/favicon.ico">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/fontawesome.min.css">
<link rel="stylesheet" href="style.css">
<style>
    .hero { background: linear-gradient(135deg, #0d6efd 0%, #042a6b 100%); color: #fff; padding: 80px 0 60px; }
    .hero h1 { font-size: 38px; font-weight: 800; line-height: 1.15; margin-bottom: 18px; }
    .pill { display:inline-block;background:rgba(255,255,255,.18);padding:4px 12px;border-radius:999px;font-size:13px;margin-bottom:14px;}
    .section { padding: 60px 0; }
    .pkg { border: 1px solid #eef; border-radius: 14px; padding: 28px; height: 100%; transition: transform .2s; }
    .pkg:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(13,110,253,.12); }
    .pkg.featured { border-color: #0d6efd; box-shadow: 0 10px 40px rgba(13,110,253,.18); }
    .pkg .price { font-size: 26px; font-weight: 800; color: #0d6efd; }
    .pkg ul { padding-left: 18px; }
    .pkg li { margin: 6px 0; font-size: 14px; }
    .case { padding: 18px; background: #f8faf9; border-radius: 10px; height: 100%; }
    .case h5 { color: #0d6efd; margin-bottom: 6px; }
    .stack { display: inline-block; background: #e5eaef; padding: 2px 8px; border-radius: 4px; font-size: 11px; margin: 2px; }
    .cta-strip { background: #0d6efd; color: #fff; padding: 40px 0; text-align: center; }
</style>
</head>
<body>

<?php include_once "header.php"; ?>

<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="pill">🌍 Serving clients across India · US · UK · DACH · UAE</div>
                <h1>Custom websites &amp; web apps that ship on time.</h1>
                <p class="lead" style="opacity:.95;font-size:17px;max-width:640px">
                    From a clean landing page to a full SaaS dashboard — Voldebug builds, secures and ships modern web products. 8 years of work for fintech, IoT, education and e-commerce companies worldwide.
                </p>
                <a href="get-a-quote.php" class="btn btn-light btn-lg mt-3" style="color:#0d6efd;font-weight:700">Get a free quote →</a>
                <a href="portfolio.php" class="btn btn-outline-light btn-lg mt-3 ml-2">See our work</a>
            </div>
            <div class="col-lg-5">
                <div style="background:rgba(255,255,255,.1);padding:24px;border-radius:14px;border:1px solid rgba(255,255,255,.2)">
                    <h5>What we ship</h5>
                    <ul style="list-style:none;padding:0;margin:0">
                        <li>✓ Marketing websites &amp; landing pages</li>
                        <li>✓ E-commerce stores (Shopify, WooCommerce, custom)</li>
                        <li>✓ Custom SaaS / dashboards</li>
                        <li>✓ Booking platforms &amp; marketplaces</li>
                        <li>✓ Internal tools / admin panels</li>
                        <li>✓ API + headless backends</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="text-center mb-2">Fixed-price packages — no scope creep surprises</h2>
        <p class="text-center text-muted mb-5">Per-project packages with locked deliverables, timelines and price. Custom engagements available too.</p>
        <div class="row">
            <div class="col-md-4 mb-3"><div class="pkg">
                <h4>Marketing Website</h4>
                <div class="price">₹50,000</div>
                <p class="text-muted" style="font-size:13px">2-3 weeks · ideal for early-stage startups, agencies, consultants</p>
                <ul>
                    <li>Up to 5 pages, fully responsive</li>
                    <li>Modern stack (React / Next.js or PHP)</li>
                    <li>SEO essentials (meta, sitemap, schema)</li>
                    <li>Contact form + analytics</li>
                    <li>1 month free support</li>
                </ul>
            </div></div>
            <div class="col-md-4 mb-3"><div class="pkg featured">
                <h4>Growth Website + SEO <small style="font-size:12px;color:#0d6efd">most popular</small></h4>
                <div class="price">₹1,50,000 / 3-mo</div>
                <p class="text-muted" style="font-size:13px">8-10 page site + ongoing SEO + content for 3 months</p>
                <ul>
                    <li>Multi-page site, CMS-backed</li>
                    <li>Initial SEO audit + technical fixes</li>
                    <li>4 SEO-optimized articles / month</li>
                    <li>Monthly performance report</li>
                    <li>A/B test 2 landing pages</li>
                </ul>
            </div></div>
            <div class="col-md-4 mb-3"><div class="pkg">
                <h4>SaaS / Custom Web App</h4>
                <div class="price">From ₹3,50,000</div>
                <p class="text-muted" style="font-size:13px">6-12 weeks · fully scoped after discovery</p>
                <ul>
                    <li>Custom-designed dashboard</li>
                    <li>Auth, billing (Stripe/Razorpay), API</li>
                    <li>Cloud-hosted, secured, monitored</li>
                    <li>Source code + documentation</li>
                    <li>3 months free maintenance</li>
                </ul>
            </div></div>
        </div>
    </div>
</section>

<section class="section" style="background:#f5f7fb">
    <div class="container">
        <h2 class="text-center mb-4">Recent work</h2>
        <div class="row">
            <div class="col-md-4 mb-3"><div class="case">
                <h5>Ecosaras — IoT Cold-storage Dashboard</h5>
                <p style="font-size:14px">Real-time temperature/humidity tracking for 60+ cold-storage units. Web + mobile dashboards, SMS alerts.</p>
                <span class="stack">React</span><span class="stack">Node.js</span><span class="stack">MQTT</span><span class="stack">AWS</span>
            </div></div>
            <div class="col-md-4 mb-3"><div class="case">
                <h5>Smart Investigation System</h5>
                <p style="font-size:14px">Police-grade case-management platform. Role-based access, evidence chain-of-custody, FIR linkage.</p>
                <span class="stack">PHP</span><span class="stack">MySQL</span><span class="stack">React</span>
            </div></div>
            <div class="col-md-4 mb-3"><div class="case">
                <h5>KB Engineering — Industrial Catalogue</h5>
                <p style="font-size:14px">High-conversion e-commerce + product catalogue for an engineering equipment manufacturer.</p>
                <span class="stack">WooCommerce</span><span class="stack">Custom PHP</span>
            </div></div>
            <div class="col-md-4 mb-3"><div class="case">
                <h5>Manav Soni — Personal Brand</h5>
                <p style="font-size:14px">High-end portfolio site with case-study microsites and lead-capture funnels.</p>
                <span class="stack">Next.js</span><span class="stack">Tailwind</span>
            </div></div>
            <div class="col-md-4 mb-3"><div class="case">
                <h5>Trutt — Trucking Marketplace</h5>
                <p style="font-size:14px">Two-sided platform connecting truck owners and freight customers. Real-time matching.</p>
                <span class="stack">React Native</span><span class="stack">Node.js</span><span class="stack">PostgreSQL</span>
            </div></div>
            <div class="col-md-4 mb-3"><div class="case">
                <h5>Evvapes — D2C E-commerce</h5>
                <p style="font-size:14px">Mobile-first e-commerce with custom payment integration and inventory dashboard.</p>
                <span class="stack">Shopify Plus</span><span class="stack">Custom UI</span>
            </div></div>
        </div>
        <p class="text-center mt-3"><a href="portfolio.php" class="btn btn-outline-primary">See full portfolio →</a></p>
    </div>
</section>

<section class="cta-strip">
    <div class="container">
        <h3 style="margin-bottom: 12px">Have a project in mind?</h3>
        <p style="margin-bottom: 20px; opacity: .9">15-minute discovery call · written estimate within 48 hours · no obligation.</p>
        <a href="get-a-quote.php" class="btn btn-light btn-lg" style="color:#0d6efd;font-weight:700">Request a quote</a>
        <a href="mailto:admin@voldebug.in" class="btn btn-outline-light btn-lg ml-2">Email us</a>
    </div>
</section>

<?php include_once "footer.php"; ?>

<script src="assets/js/Jquery-3.7.0.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body></html>
