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
    'title'     => 'CCTV Installation in Vadodara — Voldebug Innovations',
    'desc'      => 'Professional CCTV installation and security camera services in Vadodara. Hikvision, CP Plus, Dahua. Home + commercial. Free site survey. Call +91 9499699693.',
    'keywords'  => 'CCTV Vadodara, CCTV installation Vadodara, security camera Vadodara, Hikvision Vadodara, CCTV dealer Vadodara, Voldebug',
    'canonical' => 'https://voldebug.in/cctv-vadodara.php',
    'jsonld'    => jsonld_local_business('CCTV Installation & Security Cameras', 'https://voldebug.in/cctv-vadodara.php'),
]); ?>
<link rel="icon" type="image/png" href="assets/img/logo/favicon.ico">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/fontawesome.min.css">
<link rel="stylesheet" href="style.css">
<style>
    .hero { background: linear-gradient(135deg, #1a8f4a 0%, #0d6b35 100%); color: #fff; padding: 80px 0 60px; }
    .hero h1 { font-size: 38px; font-weight: 800; line-height: 1.15; margin-bottom: 18px; }
    .hero p.lead { font-size: 17px; opacity: .95; max-width: 640px; }
    .pill { display:inline-block;background:rgba(255,255,255,.18);padding:4px 12px;border-radius:999px;font-size:13px;margin-bottom:14px;}
    .cta-box { background:#fff;color:#222;border-radius:14px;padding:22px;box-shadow:0 12px 40px rgba(0,0,0,.25);margin-top:22px; }
    .section { padding: 60px 0; }
    .feature { padding: 22px; border-radius: 12px; background: #f8faf9; height: 100%; }
    .feature h4 { color: #1a8f4a; }
    .price-card { border: 1px solid #e5eaef; border-radius: 14px; padding: 28px; height: 100%; }
    .price-card.featured { border-color: #1a8f4a; box-shadow: 0 10px 40px rgba(26,143,74,.18); }
    .price-card .price { font-size: 28px; font-weight: 800; color: #1a8f4a; }
    .price-card ul { padding-left: 18px; }
    .price-card li { margin: 6px 0; font-size: 14px; }
    .brand-row img { height: 38px; margin: 0 18px; opacity: .7; }
    .faq { padding: 18px 22px; background: #f5f7fb; border-radius: 10px; margin-bottom: 12px; }
    .faq summary { font-weight: 600; cursor: pointer; }
</style>
</head>
<body>

<?php include_once "header.php"; ?>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="pill">🛡️ Trusted by 50+ Vadodara businesses</div>
                <h1>CCTV Installation in Vadodara — secure your home or business in one visit.</h1>
                <p class="lead">Voldebug Innovations is a Vadodara-based security &amp; technology firm. We supply, install and remotely monitor CCTV systems for shops, factories, offices, schools and homes across Vadodara &amp; Gujarat.</p>
                <ul style="list-style:none;padding:0;margin-top:18px;">
                    <li>✓ Free on-site survey within Vadodara city limits</li>
                    <li>✓ Hikvision · CP Plus · Dahua · Bosch — authorised brands</li>
                    <li>✓ 2-year on-site warranty + remote mobile-app setup</li>
                    <li>✓ Service across Tarsali, Sayajigunj, Alkapuri, Manjalpur, Akota &amp; Vadodara rural</li>
                </ul>
            </div>
            <div class="col-lg-5">
                <div class="cta-box">
                    <h4 style="margin:0 0 4px 0">Get a free site survey</h4>
                    <p style="color:#666;font-size:14px">We visit, measure, and quote — no obligation. Average response: 24 hours.</p>
                    <form method="post" action="forms/quote.php">
                        <input type="hidden" name="subject" value="CCTV Vadodara enquiry">
                        <input type="text"  name="name"    class="form-control mb-2" placeholder="Your name *" required>
                        <input type="email" name="email"   class="form-control mb-2" placeholder="Email *" required>
                        <input type="tel"   name="phone"   class="form-control mb-2" placeholder="Phone (we WhatsApp) *" required>
                        <textarea name="message" class="form-control mb-2" rows="3" placeholder="Site type (home / shop / factory), # of cameras, address area *" required></textarea>
                        <!-- honeypot -->
                        <div style="position:absolute;left:-9999px"><input name="website_hp" tabindex="-1"></div>
                        <?= captcha_field(); ?>
                        <button class="btn btn-success btn-block" type="submit" style="background:#1a8f4a;border:none">Request free survey →</button>
                        <p style="font-size:12px;color:#888;margin:8px 0 0 0">Or call <strong>+91 9499699693</strong> directly · admin@voldebug.in</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="section">
    <div class="container">
        <h2 class="text-center mb-4">Why local Vadodara businesses pick us</h2>
        <div class="row">
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-map-marker-alt"></i> Local team, local response</h4>
                <p>Office in Tarsali. Engineers in Vadodara. When something fails at 9 PM, someone shows up — not a call-centre ticket sitting overnight.</p>
            </div></div>
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-shield-alt"></i> Cybersecurity-first wiring</h4>
                <p>Most installers leave default passwords + exposed DVR ports. We harden every install: changed credentials, VPN access, isolated VLAN, signed firmware. Your cameras can't be hijacked.</p>
            </div></div>
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-mobile-alt"></i> Mobile app + cloud backup</h4>
                <p>Watch your shop from your phone. Optional cloud DVR backup so footage survives even if the DVR is stolen or damaged.</p>
            </div></div>
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-eye"></i> Night vision &amp; AI alerts</h4>
                <p>4K resolution, 30m IR night vision, AI-based human/vehicle detection. Get alerts only when something matters — not for every leaf.</p>
            </div></div>
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-tools"></i> 2-year on-site warranty</h4>
                <p>Free replacement of any failed camera, switch or DVR within 24 months. Service contracts available beyond.</p>
            </div></div>
            <div class="col-md-4 mb-3"><div class="feature">
                <h4><i class="fas fa-rupee-sign"></i> Transparent pricing</h4>
                <p>No "site inspection charges". Quote is itemised: per camera, per cable run, per night of labour. What you see is what you pay.</p>
            </div></div>
        </div>
    </div>
</section>

<!-- PRICING -->
<section class="section" style="background:#f5f7fb">
    <div class="container">
        <h2 class="text-center mb-4">Sample packages</h2>
        <p class="text-center text-muted mb-5">Indicative pricing for Vadodara installations. Final quote depends on site survey.</p>
        <div class="row">
            <div class="col-md-4 mb-3"><div class="price-card">
                <h4>Home / Shop Starter</h4>
                <div class="price">₹18,500 <small style="font-size:13px;font-weight:400;color:#888">onwards</small></div>
                <ul>
                    <li>4× HD cameras (2 MP)</li>
                    <li>4-channel DVR + 1 TB HDD</li>
                    <li>Mobile-app setup</li>
                    <li>Installation + cabling</li>
                    <li>1-year warranty</li>
                </ul>
            </div></div>
            <div class="col-md-4 mb-3"><div class="price-card featured">
                <h4>Business Pro · <small style="font-size:13px;color:#1a8f4a">most popular</small></h4>
                <div class="price">₹42,000 <small style="font-size:13px;font-weight:400;color:#888">onwards</small></div>
                <ul>
                    <li>8× 4MP IP cameras with night vision</li>
                    <li>8-channel NVR + 2 TB HDD</li>
                    <li>AI human / vehicle detection</li>
                    <li>VPN remote access (hardened)</li>
                    <li>2-year on-site warranty</li>
                </ul>
            </div></div>
            <div class="col-md-4 mb-3"><div class="price-card">
                <h4>Enterprise / Factory</h4>
                <div class="price">Custom</div>
                <ul>
                    <li>16+ cameras, 4K, multi-floor</li>
                    <li>Centralised NVR + cloud backup</li>
                    <li>Integration with access control</li>
                    <li>SOC monitoring (24/7 optional)</li>
                    <li>Dedicated account manager</li>
                </ul>
            </div></div>
        </div>
    </div>
</section>

<!-- AREAS SERVED (good for local SEO) -->
<section class="section">
    <div class="container text-center">
        <h3>Vadodara areas we serve</h3>
        <p class="text-muted">Tarsali · Sayajigunj · Alkapuri · Manjalpur · Akota · Gotri · Race Course · Karelibaug · Atladara · Fatehgunj · Subhanpura · Sama · Vasna · Ajwa Road · Waghodia Road · Padra · Karjan · Halol — and other Vadodara &amp; Vadodara-rural locations.</p>
    </div>
</section>

<!-- FAQs (good for SEO + featured-snippet eligibility) -->
<section class="section" style="background:#f5f7fb">
    <div class="container" style="max-width: 800px;">
        <h2 class="text-center mb-4">Common questions</h2>
        <details class="faq" open><summary>How fast can you install CCTV at my place in Vadodara?</summary><p>Most home installs (4–6 cameras) finish in one day. Larger commercial sites take 2–4 days. We schedule a free site survey first — usually within 24 hours of your enquiry.</p></details>
        <details class="faq"><summary>Will my CCTV work without internet?</summary><p>Yes. The DVR records locally to a hard drive 24/7. You only need internet if you want to view footage on your phone remotely or use cloud backup.</p></details>
        <details class="faq"><summary>Can I add more cameras later?</summary><p>Absolutely. We size the DVR/NVR for headroom (e.g. install an 8-channel for 4 cameras now, expand later). Cable runs are documented so future additions are quick.</p></details>
        <details class="faq"><summary>What if a camera fails after installation?</summary><p>Under our 2-year on-site warranty we replace it free, on-site, within 48 hours. Beyond that we offer annual service contracts.</p></details>
        <details class="faq"><summary>Do you secure the CCTV system itself?</summary><p>Yes — this is where we're different. We are a cybersecurity company by training, so every install gets: default passwords changed, DVR firmware updated, public-internet exposure removed, encrypted remote access via VPN, and a one-page security report you can keep.</p></details>
    </div>
</section>

<?php include_once "footer.php"; ?>

<script src="assets/js/Jquery-3.7.0.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body></html>
