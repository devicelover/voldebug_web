<?php
// Floating WhatsApp button + scroll-aware "Get a Quote" CTA.
// Drop near the bottom of every public page footer:  <?php include __DIR__ . '/includes/floating_cta.php'; ?>

// Phone number used for WhatsApp deep-link. From settings.phone (digits only).
$ctaPhone = '';
if (isset($APP_SETTINGS) && !empty($APP_SETTINGS['phone'])) {
    $ctaPhone = preg_replace('/[^0-9]/', '', (string) $APP_SETTINGS['phone']);
} elseif (function_exists('mysqli_query') && isset($con)) {
    $r = @$con->query("SELECT phone FROM settings WHERE id = 1");
    if ($r && ($s = $r->fetch_assoc())) $ctaPhone = preg_replace('/[^0-9]/', '', (string) $s['phone']);
}
if ($ctaPhone === '') $ctaPhone = '919499699693';   // fallback from VDS deck
$waUrl = 'https://wa.me/' . $ctaPhone . '?text=' . urlencode("Hi Voldebug, I'd like to know more about your services.");
?>
<style>
    .vdb-float-stack {
        position: fixed; right: 18px; bottom: 18px; z-index: 9990;
        display: flex; flex-direction: column; gap: 12px; align-items: flex-end;
    }
    .vdb-float-btn {
        display: inline-flex; align-items: center; justify-content: center;
        text-decoration: none; box-shadow: 0 6px 24px rgba(0,0,0,.18);
        transition: transform .18s, box-shadow .18s; cursor: pointer; border: none;
    }
    .vdb-float-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(0,0,0,.25); }
    .vdb-wa { background: #25D366; color: #fff !important; width: 56px; height: 56px; border-radius: 50%; font-size: 28px; }
    .vdb-wa:hover { color: #fff; background: #1ebd5d; }
    .vdb-cta {
        background: #1a8f4a; color: #fff !important; padding: 12px 18px; border-radius: 28px;
        font-weight: 600; font-size: 14px; letter-spacing: .2px;
        opacity: 0; transform: translateY(20px); pointer-events: none;
    }
    .vdb-cta.is-visible { opacity: 1; transform: translateY(0); pointer-events: auto; }
    .vdb-cta:hover { color: #fff; background: #166e3a; }
    .vdb-cta i { margin-right: 6px; }
    @media (max-width: 575.98px) {
        .vdb-float-stack { right: 12px; bottom: 12px; gap: 8px; }
        .vdb-wa { width: 50px; height: 50px; font-size: 24px; }
        .vdb-cta { padding: 10px 14px; font-size: 13px; }
    }
</style>

<div class="vdb-float-stack" aria-label="Quick actions">
    <a href="get-a-quote.php" class="vdb-float-btn vdb-cta" id="vdbStickyCta" aria-label="Get a free quote">
        💬 Get a free quote
    </a>
    <a href="<?= htmlspecialchars($waUrl) ?>" class="vdb-float-btn vdb-wa" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>
</div>

<script>
// Reveal the "Get a free quote" pill once the user has scrolled ~30% — feels less aggressive than always-on.
(function () {
    var cta = document.getElementById('vdbStickyCta');
    if (!cta) return;
    var revealAt = 400;
    function check() {
        if ((window.scrollY || document.documentElement.scrollTop) > revealAt) cta.classList.add('is-visible');
        else cta.classList.remove('is-visible');
    }
    window.addEventListener('scroll', check, { passive: true });
    check();
})();
</script>
