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
    'title'     => 'Cybersecurity Services in Vadodara — Penetration Testing & Audits | Voldebug',
    'desc'      => 'Vadodara-based cybersecurity firm offering penetration testing, security audits, ISO/SOC 2 readiness, and incident response for businesses in Gujarat. CERT-In aligned.',
    'keywords'  => 'cybersecurity Vadodara, penetration testing Vadodara, security audit Gujarat, VAPT Vadodara, ISO 27001 Vadodara, Voldebug',
    'canonical' => 'https://voldebug.in/cybersecurity-vadodara.php',
    'jsonld'    => jsonld_local_business('Cybersecurity & Penetration Testing', 'https://voldebug.in/cybersecurity-vadodara.php'),
]); ?>
<link rel="icon" type="image/png" href="assets/img/logo/favicon.ico">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/fontawesome.min.css">
<link rel="stylesheet" href="style.css">
<style>
    .hero { background: linear-gradient(135deg, #0a1929 0%, #102a43 100%); color: #fff; padding: 80px 0 60px; }
    .hero h1 { font-size: 38px; font-weight: 800; line-height: 1.15; margin-bottom: 18px; }
    .hero p.lead { font-size: 17px; opacity: .95; max-width: 640px; }
    .pill { display:inline-block;background:rgba(26,143,74,.25);padding:4px 12px;border-radius:999px;font-size:13px;margin-bottom:14px;color:#7fe5a4;}
    .cta-box { background:#fff;color:#222;border-radius:14px;padding:22px;box-shadow:0 12px 40px rgba(0,0,0,.4);margin-top:22px; }
    .section { padding: 60px 0; }
    .service-card { padding: 26px; border-radius: 12px; background: #fff; border: 1px solid #eef; height: 100%; }
    .service-card h4 { color: #1a8f4a; }
    .service-card .tag { display:inline-block;background:#1a8f4a;color:#fff;padding:2px 8px;border-radius:4px;font-size:11px;margin-bottom:8px;letter-spacing:.5px; }
    .stat { text-align: center; padding: 20px; }
    .stat .num { font-size: 36px; font-weight: 800; color: #1a8f4a; line-height: 1; }
    .stat .lbl { color: #666; margin-top: 6px; font-size: 14px; }
</style>
</head>
<body>

<?php include_once "header.php"; ?>

<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="pill">🛡️ Vadodara · pan-India delivery</div>
                <h1>Cybersecurity services for businesses in Vadodara &amp; Gujarat.</h1>
                <p class="lead">Penetration testing, security audits, ISO 27001 / SOC 2 readiness, and incident response — delivered by a Vadodara-based team that's done it for fintech, IoT, healthcare and SaaS companies.</p>
                <ul style="list-style:none;padding:0;margin-top:18px;">
                    <li>✓ CERT-In aligned reporting · NIST / OWASP methodology</li>
                    <li>✓ Free 30-min discovery call — get a real assessment, not a sales pitch</li>
                    <li>✓ Local team in Tarsali · fluent in Gujarati &amp; English</li>
                    <li>✓ Retainer + project-based engagements available</li>
                </ul>
            </div>
            <div class="col-lg-5">
                <div class="cta-box">
                    <h4 style="margin:0 0 4px 0">Book a free 30-min security review</h4>
                    <p style="color:#666;font-size:14px">We'll walk through your current setup and flag the top 3 gaps — even if you don't engage us further.</p>
                    <form method="post" action="forms/quote.php">
                        <input type="hidden" name="subject" value="Cybersecurity Vadodara enquiry">
                        <input type="text"  name="name"    class="form-control mb-2" placeholder="Your name *" required>
                        <input type="email" name="email"   class="form-control mb-2" placeholder="Work email *" required>
                        <input type="tel"   name="phone"   class="form-control mb-2" placeholder="Phone *" required>
                        <textarea name="message" class="form-control mb-2" rows="3" placeholder="Briefly: company, sector, what you need reviewed (web app, network, cloud, etc.)" required></textarea>
                        <div style="position:absolute;left:-9999px"><input name="website_hp" tabindex="-1"></div>
                        <?= captcha_field(); ?>
                        <button class="btn btn-block" type="submit" style="background:#1a8f4a;color:#fff;border:none">Book the 30-min call →</button>
                        <p style="font-size:12px;color:#888;margin:8px 0 0 0">+91 9499699693 · admin@voldebug.in</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="text-center mb-2">Our cybersecurity services</h2>
        <p class="text-center text-muted mb-5">Strict scoping. Written reports. No "trust us, it's secure" handwaving.</p>
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-3"><div class="service-card">
                <span class="tag">VAPT</span>
                <h4>Web &amp; Mobile App Pentesting</h4>
                <p>Black-box / grey-box testing of your web app, mobile app or API. OWASP Top 10 + business-logic flaws. Full report with reproducible exploit steps and prioritized fixes.</p>
            </div></div>
            <div class="col-md-6 col-lg-4 mb-3"><div class="service-card">
                <span class="tag">VAPT</span>
                <h4>Network &amp; Infrastructure Pentesting</h4>
                <p>External-facing infra + internal segment testing. Active Directory, cloud (AWS/Azure/GCP) misconfig hunting, lateral-movement simulation.</p>
            </div></div>
            <div class="col-md-6 col-lg-4 mb-3"><div class="service-card">
                <span class="tag">COMPLIANCE</span>
                <h4>ISO 27001 / SOC 2 Readiness</h4>
                <p>Gap assessment, policy authoring, control implementation, evidence gathering. We get you audit-ready in 12–16 weeks — without an army of consultants.</p>
            </div></div>
            <div class="col-md-6 col-lg-4 mb-3"><div class="service-card">
                <span class="tag">DPDP</span>
                <h4>India DPDP Act &amp; GDPR Compliance</h4>
                <p>Data-flow mapping, privacy notice drafting, consent-management, breach-response playbook. Especially relevant for fintech, edtech and healthcare in Gujarat.</p>
            </div></div>
            <div class="col-md-6 col-lg-4 mb-3"><div class="service-card">
                <span class="tag">IR</span>
                <h4>Incident Response &amp; Forensics</h4>
                <p>Breach? Phishing campaign? Ransomware? We help triage, contain, and document the incident — on-site if needed in Vadodara, remote elsewhere.</p>
            </div></div>
            <div class="col-md-6 col-lg-4 mb-3"><div class="service-card">
                <span class="tag">TRAINING</span>
                <h4>Security Awareness Training</h4>
                <p>2-hour workshops for staff. Phishing simulations. Custom modules for your dev team on secure coding &amp; OWASP. We also run the Voldebug AI School locally.</p>
            </div></div>
        </div>
    </div>
</section>

<section class="section" style="background:#0a1929; color:#fff">
    <div class="container">
        <div class="row">
            <div class="col-md-3"><div class="stat"><div class="num">50+</div><div class="lbl" style="color:#aac">VAPT engagements delivered</div></div></div>
            <div class="col-md-3"><div class="stat"><div class="num">12+</div><div class="lbl" style="color:#aac">Industries served (fintech, IoT, EdTech…)</div></div></div>
            <div class="col-md-3"><div class="stat"><div class="num">CERT-In</div><div class="lbl" style="color:#aac">Aligned reporting standards</div></div></div>
            <div class="col-md-3"><div class="stat"><div class="num">24/7</div><div class="lbl" style="color:#aac">Incident response (retainer)</div></div></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width: 800px">
        <h2 class="text-center mb-4">How we work</h2>
        <ol style="font-size: 16px; line-height: 1.7;">
            <li><strong>Free discovery (30 min)</strong> — we understand your scope, sector, threat model.</li>
            <li><strong>Scoped proposal</strong> — fixed fee, fixed timeline, named consultants. No surprises mid-engagement.</li>
            <li><strong>Active engagement</strong> — daily standups for sprints; immediate disclosure for critical findings.</li>
            <li><strong>Written report</strong> — executive summary + technical evidence + remediation plan you can hand to your engineering team.</li>
            <li><strong>Retest after fixes (free)</strong> — within 30 days of report, we re-verify your remediations.</li>
        </ol>
    </div>
</section>

<?php include_once "footer.php"; ?>

<script src="assets/js/Jquery-3.7.0.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body></html>
