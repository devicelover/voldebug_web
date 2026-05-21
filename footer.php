<?php
if (!isset($con)) { include __DIR__ . '/config.php'; }
$row = $con->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc() ?: [];
$companyLegal = $row['company_legal_name'] ?? ($row['name'] ?? 'Voldebug Innovations Pvt. Ltd.');
$companyName  = $row['name']    ?? 'Voldebug';
$phoneDigits  = preg_replace('/[^0-9]/', '', (string)($row['phone'] ?? ''));
$emailContact = $row['email']   ?? 'contactus@voldebug.in';
$websiteUrl   = $row['website'] ?: 'https://voldebug.in';
$facebook     = $row['facebook'] ?? '';
$instagram    = $row['instagram']?? '';
$github       = $row['github']   ?? '';
$linkedin     = $row['linkedin'] ?? '';
$twitter      = $row['twitter']  ?? '';
$selfBack     = htmlspecialchars(basename($_SERVER['REQUEST_URI'] ?? 'index.php'));
?>

<!-- Subscribe-success toast (one-shot, fades after 4s) -->
<?php if (!empty($_GET['subscribed']) && $_GET['subscribed'] === '1'): ?>
<div id="vdb-toast" style="position:fixed;left:50%;top:18px;transform:translateX(-50%);background:#1a8f4a;color:#fff;padding:12px 20px;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,.2);font-weight:600;z-index:10000;font-family:system-ui,sans-serif">
    ✓ Subscribed — check your inbox for confirmation.
</div>
<script>setTimeout(function(){var t=document.getElementById('vdb-toast');if(t)t.style.opacity='0';t&&t.parentNode.removeChild(t);},4000);</script>
<?php endif; ?>

<footer class="footer pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="footer__widget">
                    <h5>Contact Us</h5>
                    <div class="footer__widget--contact">
                        <ul>
                            <?php if ($phoneDigits): ?><li><i class="fa-sharp fa-solid fa-phone"></i><a href="tel:+<?= htmlspecialchars($phoneDigits) ?>">+<?= htmlspecialchars($phoneDigits) ?></a></li><?php endif; ?>
                            <li><i class="fa-regular fa-envelope"></i><a href="mailto:<?= htmlspecialchars($emailContact) ?>"><?= htmlspecialchars($emailContact) ?></a></li>
                            <?php if (!empty($row['letterhead_address'] ?? $row['address'] ?? '')): ?>
                                <li style="display:block;margin-top:6px;font-size:13px;color:#aaa"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($row['letterhead_address'] ?? $row['address']) ?></li>
                            <?php endif; ?>
                        </ul>
                        <div class="footer__social">
                            <?php if ($facebook):  ?><a href="<?= htmlspecialchars($facebook)  ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a><?php endif; ?>
                            <?php if ($instagram): ?><a href="<?= htmlspecialchars($instagram) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a><?php endif; ?>
                            <?php if ($linkedin):  ?><a href="<?= htmlspecialchars($linkedin)  ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a><?php endif; ?>
                            <?php if ($github):    ?><a href="<?= htmlspecialchars($github)    ?>" target="_blank" rel="noopener" aria-label="GitHub"><i class="fa-brands fa-github"></i></a><?php endif; ?>
                            <?php if ($twitter):   ?><a href="<?= htmlspecialchars($twitter)   ?>" target="_blank" rel="noopener" aria-label="Twitter / X"><i class="fa-brands fa-x-twitter"></i></a><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="footer__widget">
                    <h5>Company</h5>
                    <div class="footer__widget--link">
                        <ul style="list-style:none;padding:0">
                            <li><a href="about.php">About</a></li>
                            <li><a href="project.php">Projects</a></li>
                            <li><a href="career.php">Careers</a></li>
                            <li><a href="blog.php">Blog</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="footer__widget">
                    <h5>Services</h5>
                    <div class="footer__widget--link">
                        <ul style="list-style:none;padding:0">
                            <li><a href="web-development.php">Web Development</a></li>
                            <li><a href="mobile-apps.php">Mobile Apps</a></li>
                            <li><a href="cybersecurity-vadodara.php">Cybersecurity (Vadodara)</a></li>
                            <li><a href="cctv-vadodara.php">CCTV Installation (Vadodara)</a></li>
                            <li><a href="ai-school.php">AI School</a></li>
                            <li><a href="get-a-quote.php"><strong>Request a quote →</strong></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-12 col-sm-12">
                <div class="footer__widget">
                    <h5>Newsletter</h5>
                    <p style="font-size:13px;color:#aaa;margin-bottom:10px">1-2 emails / month — case studies, advisories, launches. No spam.</p>
                    <form method="post" action="subscribe.php" style="display:flex;gap:6px;flex-wrap:wrap">
                        <input type="hidden" name="source" value="footer">
                        <input type="hidden" name="back"   value="<?= $selfBack ?>">
                        <div style="position:absolute;left:-9999px"><input name="website_hp" tabindex="-1"></div>
                        <input type="email" name="email" placeholder="you@example.com" required
                               style="flex:1;min-width:160px;padding:9px 12px;border-radius:8px;border:1px solid #444;background:#1a1a1a;color:#fff">
                        <button type="submit" style="padding:9px 14px;background:#1a8f4a;border:none;border-radius:8px;color:#fff;font-weight:600;cursor:pointer">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- footer credit -->
<div class="footer-credit">
    <div class="container">
        <div class="footer-credit--img">
            <a href="index.php"><img src="assets/img/logo/2.png" alt="<?= htmlspecialchars($companyName) ?> logo"></a>
        </div>
        <div class="footer-credit__wrapper">
            <div class="copy-right">
                &copy; <?= date('Y') ?> All Rights Reserved · <a href="index.php"><?= htmlspecialchars($companyLegal) ?></a>
                <?php if (!empty($row['cin'])): ?><br><small style="opacity:.6">CIN: <?= htmlspecialchars($row['cin']) ?></small><?php endif; ?>
            </div>
            <div class="footer-links">
                <ul>
                    <li><a href="faq.php">Terms of use</a></li>
                    <li><a href="faq.php">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- footer credit end -->

<!-- Organization JSON-LD for rich-result eligibility (sitewide) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "<?= htmlspecialchars($companyLegal, ENT_QUOTES) ?>",
    "alternateName": "Voldebug",
    "url": "<?= htmlspecialchars($websiteUrl, ENT_QUOTES) ?>",
    "logo": "<?= htmlspecialchars($websiteUrl, ENT_QUOTES) ?>/assets/img/logo/2.png",
    <?php if ($phoneDigits): ?>"telephone": "+<?= htmlspecialchars($phoneDigits, ENT_QUOTES) ?>",<?php endif; ?>
    "email": "<?= htmlspecialchars($emailContact, ENT_QUOTES) ?>",
    <?php if (!empty($row['cin'])): ?>"identifier": "CIN <?= htmlspecialchars($row['cin'], ENT_QUOTES) ?>",<?php endif; ?>
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?= htmlspecialchars($row['letterhead_address'] ?? $row['address'] ?? 'Vadodara, Gujarat, India', ENT_QUOTES) ?>",
        "addressLocality": "Vadodara",
        "addressRegion": "Gujarat",
        "addressCountry": "IN"
    },
    "sameAs": [
        <?php $samesAs = array_filter([$facebook, $instagram, $linkedin, $github, $twitter]);
              echo implode(",\n        ", array_map(fn($u) => '"' . htmlspecialchars($u, ENT_QUOTES) . '"', $samesAs)); ?>
    ]
}
</script>

<?php include __DIR__ . '/includes/floating_cta.php'; ?>
