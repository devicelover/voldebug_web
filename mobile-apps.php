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
    'title'     => 'Mobile App Development — iOS & Android Apps | Voldebug Innovations',
    'desc'      => 'Custom mobile app development — React Native, Flutter, native iOS & Android. From MVP to scale. Serving startups & enterprises in India and globally.',
    'keywords'  => 'mobile app development India, React Native developers, Flutter developers, iOS app development, Android app development, app developers Vadodara',
    'canonical' => 'https://voldebug.in/mobile-apps.php',
    'jsonld'    => jsonld_service(
        'Mobile App Development',
        'Custom mobile applications for iOS and Android — React Native, Flutter, and native development.',
        'https://voldebug.in/mobile-apps.php'
    ),
]); ?>
<link rel="icon" type="image/png" href="assets/img/logo/favicon.ico">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/fontawesome.min.css">
<link rel="stylesheet" href="style.css">
<style>
    .hero { background: linear-gradient(135deg, #6f42c1 0%, #3d1d72 100%); color: #fff; padding: 80px 0 60px; }
    .pill { display:inline-block;background:rgba(255,255,255,.18);padding:4px 12px;border-radius:999px;font-size:13px;margin-bottom:14px;}
    .section { padding: 60px 0; }
    .feature { padding: 22px; border-radius: 12px; background: #f8faf9; height: 100%; }
    .feature h4 { color: #6f42c1; }
    .step { padding: 18px; border-left: 3px solid #6f42c1; margin-bottom: 14px; background: #faf8ff; }
    .step h5 { margin: 0 0 4px 0; color: #6f42c1; }
</style>
</head>
<body>

<?php include_once "header.php"; ?>

<section class="hero">
    <div class="container">
        <div class="pill">📱 iOS · Android · Cross-platform</div>
        <h1 style="font-size:38px;font-weight:800">Mobile apps your customers actually use.</h1>
        <p class="lead" style="opacity:.95;font-size:17px;max-width:680px">
            Voldebug ships fast, secure, beautifully-designed mobile apps. We've built consumer apps, enterprise tools, IoT companion apps and on-demand marketplaces — for clients across India, US, UK and the UAE.
        </p>
        <a href="get-a-quote.php" class="btn btn-light btn-lg mt-3" style="color:#6f42c1;font-weight:700">Start your app →</a>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="text-center mb-4">What kind of app are you building?</h2>
        <div class="row">
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-rocket"></i> Startup MVP</h4>
                <p>Need to validate an idea fast? We ship a polished MVP in 4-6 weeks with the must-have features — and a clear roadmap for v2.</p>
            </div></div>
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-shopping-cart"></i> E-commerce / Marketplace</h4>
                <p>Two-sided marketplaces, on-demand services, D2C stores. Payments, geo-fencing, ratings, push notifications — built in.</p>
            </div></div>
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-microchip"></i> IoT Companion Apps</h4>
                <p>BLE / WiFi / MQTT device pairing, real-time telemetry, OTA firmware updates. We've shipped 6+ IoT apps end-to-end.</p>
            </div></div>
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-building"></i> Enterprise / B2B</h4>
                <p>Field-force apps, inspection apps, sales-team tools. Offline-first, MDM-ready, integrated with your existing systems.</p>
            </div></div>
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-heart"></i> Health &amp; Wellness</h4>
                <p>Fitness trackers, appointment booking, telemedicine. HIPAA-aware patterns, ABDM-compatible where required.</p>
            </div></div>
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-graduation-cap"></i> Education / EdTech</h4>
                <p>Course delivery, live classes, quizzes, progress tracking. Scales to lakhs of learners. Used by AI School curriculum.</p>
            </div></div>
        </div>
    </div>
</section>

<section class="section" style="background:#f5f7fb">
    <div class="container">
        <h2 class="text-center mb-4">Our process</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="step"><h5>Week 1 — Discovery &amp; design</h5><p>Workshop to nail down scope, user flows and wireframes. You get a clickable Figma prototype before any code is written.</p></div>
                <div class="step"><h5>Weeks 2-4 — Build sprint 1</h5><p>Core flows shipped weekly. You can install a test build on your phone from week 2.</p></div>
                <div class="step"><h5>Weeks 5-6 — Build sprint 2 + QA</h5><p>Remaining features + comprehensive testing on real devices. Internal beta release.</p></div>
            </div>
            <div class="col-md-6">
                <div class="step"><h5>Week 7 — Store submission</h5><p>App Store and Play Store listing, screenshots, privacy disclosures. We handle review feedback.</p></div>
                <div class="step"><h5>Week 8 — Launch</h5><p>Production release + analytics setup + crash monitoring. Optional: launch-week marketing support.</p></div>
                <div class="step"><h5>Post-launch — Support &amp; iterate</h5><p>3 months bug-fix included. Optional ongoing retainer for new features, OS updates, scaling.</p></div>
            </div>
        </div>
    </div>
</section>

<section class="section text-center">
    <div class="container">
        <h2>Tech stack</h2>
        <p class="text-muted mb-4">We pick the right tool per project — not religiously stuck on one framework.</p>
        <div style="font-size:18px; line-height: 2.4">
            <span class="badge badge-light mx-2 p-2">React Native</span>
            <span class="badge badge-light mx-2 p-2">Flutter</span>
            <span class="badge badge-light mx-2 p-2">Native iOS (Swift)</span>
            <span class="badge badge-light mx-2 p-2">Native Android (Kotlin)</span>
            <span class="badge badge-light mx-2 p-2">Node.js / Firebase backends</span>
            <span class="badge badge-light mx-2 p-2">PostgreSQL · MongoDB</span>
            <span class="badge badge-light mx-2 p-2">AWS · Cloudflare · Vercel</span>
        </div>
    </div>
</section>

<section class="section" style="background:#6f42c1;color:#fff;text-align:center">
    <div class="container">
        <h3>Have an app idea?</h3>
        <p style="opacity:.9">Discovery call is free. Written estimate within 48 hours. No pressure.</p>
        <a href="get-a-quote.php" class="btn btn-light btn-lg mt-2" style="color:#6f42c1;font-weight:700">Request a quote</a>
        <a href="mailto:admin@voldebug.in" class="btn btn-outline-light btn-lg mt-2 ml-2">Email us</a>
    </div>
</section>

<?php include_once "footer.php"; ?>

<script src="assets/js/Jquery-3.7.0.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body></html>
