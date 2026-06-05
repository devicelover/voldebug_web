<?php
// Renders a single certificate: landscape A4, brand letterhead, optional partner co-branding,
// QR-verifiable token, optional stamp + signature. Stores PDF under Admin/certificates/.
// Records the issuance in certificates_issued.

use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class CertificateGenerator
{
    public function __construct(
        private mysqli $db,
        private array  $settings,
        private string $publicBase,
        private string $certsDir
    ) {
        if (!is_dir($this->certsDir)) {
            mkdir($this->certsDir, 0755, true);
        }
    }

    /**
     * @param array $input  recipient details + custom fields
     *   keys: recipient_name (required), recipient_email, course_name, completion_date (Y-m-d),
     *         duration, custom1..custom5
     * @param array $template row from certificate_templates
     * @param array|null $partner row from certificate_partners (or null)
     * @param array $opts   ['include_signature' => bool, 'include_stamp' => bool, 'batch_id' => ?int]
     * @return array {ok:bool, id:int, token:string, pdf_path:string, html:string}
     */
    public function generate(array $input, array $template, ?array $partner = null, array $opts = []): array
    {
        $includeSig   = $opts['include_signature'] ?? true;
        $includeStamp = $opts['include_stamp']     ?? true;
        $batchId      = $opts['batch_id']          ?? null;

        $token       = $this->randomToken();
        $verifyUrl   = rtrim($this->publicBase, '/') . '/verify.php?t=' . urlencode($token);
        $completionDate = $input['completion_date'] ?? date('Y-m-d');
        $issueDate      = date('jS F Y', strtotime($completionDate));

        // Derive honorifics for body text via the shared helper.
        if (function_exists('derive_pronouns')) {
            [$pSubj, $pObj, $pPoss, $first, $hFirst, $hFull] = derive_pronouns([
                'title_prefix' => $input['title_prefix'] ?? '',
                'first_name'   => $input['first_name']   ?? '',
                'name'         => $input['recipient_name'],
            ]);
        } else {
            $first = $input['recipient_name'];
            $hFirst = $first; $hFull = $first;
            $pSubj = 'they'; $pObj = 'them'; $pPoss = 'their';
        }

        $companyLegal = $this->settings['company_legal_name'] ?: ($this->settings['name'] ?? 'Voldebug');

        $vars = [
            'name'                => $input['recipient_name'],
            'first_name'          => $first,
            'honorific_name'      => $hFirst,
            'honorific_full_name' => $hFull,
            'course'              => $input['course_name']  ?? '',
            'date'                => $issueDate,
            'duration'            => $input['duration']     ?? '',
            'company'             => $companyLegal,
            'signatory'           => $this->settings['signatory_name']        ?? '',
            'signatory_role'      => $this->settings['signatory_designation'] ?? '',
            'partner_name'        => $partner['name']     ?? '',
            'partner_subtitle'    => $partner['subtitle'] ?? '',
            'pronoun_subject'     => $pSubj,    'Pronoun_subject'    => ucfirst($pSubj),
            'pronoun_object'      => $pObj,     'Pronoun_object'     => ucfirst($pObj),
            'pronoun_possessive'  => $pPoss,    'Pronoun_possessive' => ucfirst($pPoss),
            'verify_url'          => $verifyUrl,
            'custom1'             => $input['custom1'] ?? '',
            'custom2'             => $input['custom2'] ?? '',
            'custom3'             => $input['custom3'] ?? '',
            'custom4'             => $input['custom4'] ?? '',
            'custom5'             => $input['custom5'] ?? '',
            // Optional per-cert guest signatory (rendered alongside Voldebug + partner sigs).
            'guest_signatory'     => $input['guest_signatory'] ?? null,
        ];

        $bodyHtml     = function_exists('render_placeholders')
            ? render_placeholders($template['body_html'],     $vars)
            : strtr($template['body_html'],     $this->flatten($vars));
        $emailBody    = function_exists('render_placeholders')
            ? render_placeholders($template['email_body'],    $vars)
            : strtr($template['email_body'],    $this->flatten($vars));
        $emailSubject = function_exists('render_placeholders')
            ? render_placeholders($template['email_subject'], $vars)
            : strtr($template['email_subject'], $this->flatten($vars));

        // Clean up stray double-periods caused by "Pvt. Ltd." + sentence period.
        $bodyHtml = preg_replace('/\.\.(?=[\s<])/', '.', $bodyHtml);

        $qr = ($template['qr_enabled'] ?? 1) ? $this->buildQrDataUri($verifyUrl) : null;
        $html = $this->buildFullHtml($template, $partner, $bodyHtml, $vars, $qr, $token, $includeSig, $includeStamp);

        $opts2 = new DompdfOptions();
        $opts2->set('isRemoteEnabled', true);
        $opts2->set('isHtml5ParserEnabled', true);
        $opts2->set('defaultPaperSize', 'a4');
        $pdf = new Dompdf($opts2);
        $pdf->loadHtml($html);
        $orient = (($template['orientation'] ?? 'landscape') === 'portrait') ? 'portrait' : 'landscape';
        $pdf->setPaper('A4', $orient);
        $pdf->render();

        $filename = $token . '.pdf';
        $abs = rtrim($this->certsDir, '\\/') . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($abs, $pdf->output());
        $relPath = 'Admin/certificates/' . $filename;

        $customJson = json_encode([
            'custom1' => $input['custom1'] ?? '',
            'custom2' => $input['custom2'] ?? '',
            'custom3' => $input['custom3'] ?? '',
            'custom4' => $input['custom4'] ?? '',
            'custom5' => $input['custom5'] ?? '',
            'guest_signatory' => $input['guest_signatory'] ?? null,
        ]);

        $stmt = $this->db->prepare(
            "INSERT INTO certificates_issued
                (verify_token, template_id, partner_id, batch_id, recipient_name, recipient_email,
                 course_name, completion_date, duration, custom_fields,
                 include_signature, include_stamp, pdf_path)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $tid = (int) $template['id'];
        $pid = $partner ? (int) $partner['id'] : null;
        $bid = $batchId !== null ? (int) $batchId : null;
        $name  = (string) $input['recipient_name'];
        $email = (string) ($input['recipient_email'] ?? '');
        $course = (string) ($input['course_name'] ?? '');
        $duration = (string) ($input['duration'] ?? '');
        $sig = $includeSig ? 1 : 0;
        $stmp = $includeStamp ? 1 : 0;
        // Type string: s=token, i=tid, i=pid, i=bid,
        //              s=name, s=email, s=course, s=completionDate, s=duration, s=customJson,
        //              i=sig, i=stmp, s=relPath  → siiissssssiis (13 chars)
        // Use the NULLIF form unconditionally — partner_id / batch_id default to 0 which becomes NULL.
        $stmt->close();
        $stmt = $this->db->prepare(
            "INSERT INTO certificates_issued
                (verify_token, template_id, partner_id, batch_id, recipient_name, recipient_email,
                 course_name, completion_date, duration, custom_fields,
                 include_signature, include_stamp, pdf_path)
             VALUES (?, ?, NULLIF(?, 0), NULLIF(?, 0), ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $pidI = $pid ?? 0;
        $bidI = $bid ?? 0;
        $stmt->bind_param('siiissssssiis',
            $token, $tid, $pidI, $bidI, $name, $email,
            $course, $completionDate, $duration, $customJson,
            $sig, $stmp, $relPath
        );
        $stmt->execute();
        $newId = (int) $stmt->insert_id;

        return [
            'ok' => true,
            'id' => $newId,
            'token' => $token,
            'pdf_path' => $abs,
            'pdf_rel'  => $relPath,
            'html' => $html,
            'email_subject' => $emailSubject,
            'email_body'    => $emailBody,
        ];
    }

    // ============================================================
    //  Internal: HTML builder
    // ============================================================

    private function buildFullHtml(array $tpl, ?array $partner, string $body, array $vars, ?string $qrDataUri, string $token, bool $includeSig, bool $includeStamp): string
    {
        $brand   = $this->settings['brand_color']  ?: '#1a8f4a';
        $brandDk = '#0f5a2e';                                   // darker shade for borders
        $gold    = '#b8924a';                                   // refined gold
        $cream   = '#fbf8ed';                                   // off-white background
        $title   = htmlspecialchars($tpl['title']);
        $company = htmlspecialchars($vars['company']);
        $cin     = htmlspecialchars($this->settings['cin']     ?? '');
        $sigName = htmlspecialchars($vars['signatory']);
        $sigRole = htmlspecialchars($vars['signatory_role']);
        $verify  = htmlspecialchars($vars['verify_url']);

        $logoTag = $this->imageTag($this->settings['logo'] ?? '', 'letterhead', 'max-height:42px');
        $sigImg  = $includeSig   ? $this->imageTag($this->settings['signature_image'] ?? '', 'letterhead', 'max-height:38px') : '';
        $stampImg= $includeStamp ? $this->imageTag($this->settings['stamp_image']     ?? '', 'letterhead', 'max-height:80px') : '';

        // Partner block — only rendered if a partner row was passed.
        $partnerLogo     = $partner ? $this->imageTag($partner['logo'] ?? '', 'cert_partners', 'max-height:42px') : '';
        $partnerName     = $partner ? htmlspecialchars($partner['name']) : '';
        $partnerSubtitle = $partner ? htmlspecialchars($partner['subtitle'] ?? '') : '';
        $partnerSigName  = $partner ? htmlspecialchars($partner['signatory_name'] ?? '') : '';
        $partnerSigRole  = $partner ? htmlspecialchars($partner['signatory_designation'] ?? '') : '';
        $partnerSigImg   = $partner ? $this->imageTag($partner['signature_image'] ?? '', 'cert_partners', 'max-height:38px') : '';

        // Header: brand on left, optional partner on right (centered if no partner).
        if ($partner) {
            $headerHtml = '<table class="hdr"><tr>'
                . '<td class="brand-cell"><div class="brand-row">' . $logoTag . '<span class="company-name">' . $company . '</span></div>'
                . ($cin ? '<div class="cin">CIN: ' . $cin . '</div>' : '') . '</td>'
                . '<td class="hdr-divider"></td>'
                . '<td class="partner-cell"><div class="brand-row">' . ($partnerLogo ?: '') . '<span class="company-name">' . $partnerName . '</span></div>'
                . ($partnerSubtitle ? '<div class="cin">' . $partnerSubtitle . '</div>' : '') . '</td>'
                . '</tr></table>';
        } else {
            $headerHtml = '<div class="hdr-solo">'
                . $logoTag . '<div class="company-name-solo">' . $company . '</div>'
                . ($cin ? '<div class="cin">CIN: ' . $cin . '</div>' : '')
                . '</div>';
        }

        // Signature block — supports up to 3 columns: Voldebug (always), Partner (if set),
        // Guest (if set on this specific cert). One signature shows centered; 2-3 shown in a row.
        $sigCols = [];

        // 1. Voldebug signatory (default). Company name omitted from sub-line — header already
        // identifies the issuing org.
        $sigCols[] = [
            'img'  => $sigImg,
            'name' => $sigName,
            'role' => $sigRole,
        ];

        // 2. Partner co-signatory (only if a partner row was passed AND has a signatory).
        if ($partner && $partnerSigName !== '') {
            $sigCols[] = [
                'img'  => $partnerSigImg,
                'name' => $partnerSigName,
                'role' => $partnerSigRole . ($partnerName ? ' · ' . $partnerName : ''),
            ];
        }

        // 3. Guest signatory (per-cert, dropped in via $vars['guest_signatory']).
        $guest = $vars['guest_signatory'] ?? null;
        if (is_array($guest) && !empty($guest['name'])) {
            $guestImg = !empty($guest['signature_image'])
                ? $this->imageTag($guest['signature_image'], 'cert_guests', 'max-height:38px')
                : '';
            $guestRole = trim(($guest['designation'] ?? '') . (!empty($guest['organization']) ? ' · ' . $guest['organization'] : ''));
            $sigCols[] = [
                'img'  => $guestImg,
                'name' => htmlspecialchars($guest['name']),
                'role' => htmlspecialchars($guestRole),
            ];
        }

        if (count($sigCols) === 1) {
            $c = $sigCols[0];
            $signaturesHtml = '<div class="sig-single">'
                . ($c['img'] ?: '<span class="sig-blank">&nbsp;</span>') . '<div class="sig-line-solo"></div>'
                . '<div class="sig-name">' . $c['name'] . '</div>'
                . '<div class="sig-role">' . $c['role'] . '</div>'
                . '</div>';
        } else {
            $tds = '';
            foreach ($sigCols as $c) {
                $tds .= '<td>' . ($c['img'] ?: '<span class="sig-blank">&nbsp;</span>') . '<div class="sig-line"></div>'
                      . '<div class="sig-name">' . $c['name'] . '</div>'
                      . '<div class="sig-role">' . $c['role'] . '</div></td>';
            }
            $signaturesHtml = '<table class="sigs sigs-' . count($sigCols) . '"><tr>' . $tds . '</tr></table>';
        }

        $qrHtml = $qrDataUri
            ? '<div class="qr"><img src="' . $qrDataUri . '" alt="Verify"><div class="qr-label">Scan to verify<br><span class="ref">VDB-' . htmlspecialchars(substr($token, 0, 10)) . '</span></div></div>'
            : '';

        $stampHtml = $stampImg ? '<div class="stamp">' . $stampImg . '</div>' : '';

        // CSS-only corner ornament (dompdf SVG transforms are unreliable).
        // Each corner uses 4 small decorative lines + a gold diamond accent.
        // Built per-corner via inline style to avoid transform: scale().
        $cornerHtml = function(string $pos) use ($brandDk, $gold): string {
            $tl = ($pos === 'tl'); $tr = ($pos === 'tr');
            $bl = ($pos === 'bl'); $br = ($pos === 'br');
            // Position the diamond inward from the corner
            $dx = ($tl || $bl) ? 'left:14px'   : 'right:14px';
            $dy = ($tl || $tr) ? 'top:14px'    : 'bottom:14px';
            // L-shaped accent lines
            $lh = ($tl || $bl) ? 'left:4px'    : 'right:4px';
            $lv = ($tl || $tr) ? 'top:4px'     : 'bottom:4px';
            return '<div class="cnr cnr-' . $pos . '">'
                . '<div class="cnr-l-h" style="' . $lv . ';' . $lh . '"></div>'
                . '<div class="cnr-l-v" style="' . $lv . ';' . $lh . '"></div>'
                . '<div class="cnr-dot" style="' . $dx . ';' . $dy . '"></div>'
                . '</div>';
        };

        return <<<HTML
<!doctype html>
<html><head><meta charset="utf-8">
<style>
    @page { size: A4 landscape; margin: 0; }
    html, body { margin: 0; padding: 0; background: #fff; font-family: DejaVu Sans, sans-serif; color: #1a1a1a; }

    /* Fixed-size canvas matching A4 landscape exactly. No overflow → no page 2. */
    .cert {
        position: relative;
        width: 297mm; height: 210mm;
        background: {$cream};
        overflow: hidden;
        box-sizing: border-box;
    }

    /* Layered decorative borders */
    .border-1 { position: absolute; left: 10mm; right: 10mm; top: 10mm; bottom: 10mm;
                border: 5px solid {$brandDk}; border-radius: 4px; }
    .border-2 { position: absolute; left: 13mm; right: 13mm; top: 13mm; bottom: 13mm;
                border: 1px solid {$gold}; }
    .border-3 { position: absolute; left: 15mm; right: 15mm; top: 15mm; bottom: 15mm;
                border: 1px solid {$gold}; }

    /* CSS-only corner ornaments — L-bracket lines + gold diamond dot */
    .cnr { position: absolute; width: 26px; height: 26px; z-index: 3; }
    .cnr-tl { left: 10mm; top: 10mm; }
    .cnr-tr { right: 10mm; top: 10mm; }
    .cnr-bl { left: 10mm; bottom: 10mm; }
    .cnr-br { right: 10mm; bottom: 10mm; }
    .cnr-l-h { position: absolute; width: 18px; height: 2px; background: {$gold}; }
    .cnr-l-v { position: absolute; width: 2px;  height: 18px; background: {$gold}; }
    .cnr-dot {
        position: absolute; width: 6px; height: 6px;
        background: {$brandDk}; transform: rotate(45deg);
        border: 1px solid {$gold};
    }

    /* All content lives in this safe zone — guaranteed inside the inner border. */
    .content {
        position: absolute;
        left: 22mm; right: 22mm; top: 20mm; bottom: 20mm;
        text-align: center;
    }

    /* Header strip */
    .hdr { width: 100%; border-collapse: collapse; }
    .hdr td { vertical-align: middle; padding: 0 14px; }
    .hdr .brand-cell, .hdr .partner-cell { width: 47%; text-align: center; }
    .hdr .hdr-divider { width: 6%; }
    .hdr .hdr-divider::before { content: '⬥'; color: {$gold}; font-size: 12pt; }
    .hdr-solo { text-align: center; padding-top: 0; }
    .hdr-solo img { vertical-align: middle; margin-right: 10px; }
    .company-name-solo { display: inline-block; font-size: 14pt; font-weight: 700; color: {$brandDk}; vertical-align: middle; letter-spacing: 0.3px; }
    .brand-row { display: block; white-space: nowrap; }
    .brand-row img { display: inline-block; vertical-align: middle; margin-right: 8px; }
    .company-name { display: inline-block; font-size: 12.5pt; font-weight: 700; color: {$brandDk}; vertical-align: middle; letter-spacing: 0.3px; }
    .cin { font-size: 7.5pt; color: #888; letter-spacing: 0.5px; margin-top: 3px; }

    /* Divider band between header and title */
    .divider {
        margin: 10px auto 6px auto;
        width: 80mm; height: 1px; background: linear-gradient(90deg, transparent, {$gold}, transparent);
        position: relative;
    }
    .divider::before, .divider::after {
        content: ''; position: absolute; top: -3px; width: 7px; height: 7px;
        background: {$gold}; transform: rotate(45deg);
    }
    .divider::before { left: 50%; margin-left: -3.5px; }

    /* Title block */
    .pre-title {
        font-family: DejaVu Sans, sans-serif;
        font-size: 8.5pt; letter-spacing: 6px;
        color: {$gold}; text-transform: uppercase;
        margin-top: 4px;
    }
    .title {
        font-family: DejaVu Serif, Georgia, serif;
        font-size: 36pt; font-weight: 800;
        color: #1a1a1a;
        margin: 6px 0 4px 0;
        letter-spacing: 1px;
    }
    .title-rule {
        display: block; width: 60mm; height: 2px;
        background: {$brandDk};
        margin: 4px auto 0 auto;
        position: relative;
    }
    .title-rule::after {
        content: ''; position: absolute; top: -3px; left: 50%; margin-left: -4px;
        width: 8px; height: 8px; background: {$gold}; transform: rotate(45deg);
    }

    /* Body — paragraph classes that templates use (.lead / .recipient / .body) */
    .body-wrap { max-width: 220mm; margin: 10px auto 0 auto; }
    .body-wrap p { margin: 4px 0; line-height: 1.5; }
    .body-wrap .lead {
        font-size: 9.5pt; color: #5a5a5a; letter-spacing: 1.8px;
        text-transform: uppercase; margin: 6px 0 2px 0;
    }
    .body-wrap .recipient {
        font-family: DejaVu Serif, Georgia, serif;
        font-style: italic; font-weight: 700;
        font-size: 34pt; color: {$brandDk};
        margin: 4px 0 0 0; letter-spacing: 1px;
        line-height: 1.15;
    }
    /* Delicate gold rule beneath the recipient name with a centered diamond */
    .body-wrap .recipient + .body, .body-wrap .recipient { /* anchor for diamond */ }
    .recipient-wrap { position: relative; padding-bottom: 12px; }
    .recipient-wrap::after {
        content: ''; position: absolute; left: 50%; bottom: 4px;
        width: 80mm; height: 1px;
        background: {$gold};
        margin-left: -40mm;
    }
    .body-wrap .body {
        font-size: 10.5pt; color: #2a2a2a; line-height: 1.55;
        margin: 4px auto; max-width: 175mm;
    }
    .body-wrap strong { color: #111; font-weight: 700; }
    .body-wrap em { color: {$brandDk}; font-style: normal; font-weight: 600; }

    /* Decorative break between body and signature */
    .ornament-break {
        text-align: center; margin: 14px 0 8px 0;
    }
    .ornament-break .swash {
        display: inline-block; height: 1px; width: 30mm; background: {$gold};
        vertical-align: middle; margin: 0 8px;
    }
    .ornament-break .dot {
        display: inline-block; width: 6px; height: 6px;
        background: {$brandDk}; transform: rotate(45deg);
        vertical-align: middle; margin: 0 2px;
    }
    .ornament-break .dot-gold { background: {$gold}; }

    /* Signatures — tighter, closer to the body. Width scales with column count. */
    .sig-block { margin: 6mm auto 0 auto; }
    .sigs { border-collapse: collapse; margin: 0 auto; }
    .sigs-2 { width: 70%; } .sigs-2 td { width: 50%; }
    .sigs-3 { width: 90%; } .sigs-3 td { width: 33.33%; }
    .sigs td { text-align: center; padding: 0 16px; vertical-align: bottom; }
    .sig-single { text-align: center; }
    .sig-line, .sig-line-solo {
        border-top: 1px solid #2a2a2a;
        margin: 4px 10mm 4px 10mm;
    }
    .sig-line-solo { margin: 4px 60mm 4px 60mm; }
    .sig-name { font-weight: 700; font-size: 10.5pt; color: #111; letter-spacing: 0.4px; margin-top: 4px; }
    .sig-role { font-size: 8.5pt; color: #666; margin-top: 1px; letter-spacing: 0.2px; }
    .sig-blank { display: inline-block; height: 36px; }

    /* Stamp — bottom right, semi-transparent */
    .stamp {
        position: absolute; right: 28mm; bottom: 8mm;
        opacity: 0.88;
    }

    /* QR — bottom left */
    .qr {
        position: absolute; left: 28mm; bottom: 8mm;
        text-align: center;
    }
    .qr img { width: 22mm; height: 22mm; }
    .qr-label { font-size: 6.5pt; color: #555; margin-top: 2px; line-height: 1.2; }
    .qr-label .ref { font-family: DejaVu Sans Mono, monospace; font-size: 6.5pt; color: #111; }

    /* Tiny verification footer at the very bottom centerline */
    .verify-foot {
        position: absolute; left: 0; right: 0; bottom: 4mm;
        text-align: center; font-size: 6.5pt; color: #999;
    }
    .verify-foot .url { font-family: DejaVu Sans Mono, monospace; font-size: 6.5pt; color: #666; }
</style>
</head>
<body>

<div class="cert">
    <div class="border-1"></div>
    <div class="border-2"></div>
    <div class="border-3"></div>

    {$cornerHtml('tl')}
    {$cornerHtml('tr')}
    {$cornerHtml('bl')}
    {$cornerHtml('br')}

    <div class="content">
        {$headerHtml}

        <div class="divider"></div>

        <div class="pre-title">Awarded by</div>
        <div class="title">{$title}</div>
        <span class="title-rule"></span>

        <div class="body-wrap">
            {$body}
        </div>

        <div class="ornament-break">
            <span class="swash"></span>
            <span class="dot dot-gold"></span>
            <span class="dot"></span>
            <span class="dot dot-gold"></span>
            <span class="swash"></span>
        </div>

        <div class="sig-block">
            {$signaturesHtml}
        </div>
    </div>

    {$qrHtml}
    {$stampHtml}

    <div class="verify-foot">
        Authenticity verifiable at <span class="url">{$verify}</span>
    </div>
</div>

</body></html>
HTML;
    }

    private function signatureBlock(string $img1, string $name1, string $role1, string $org1,
                                    string $img2 = '', string $name2 = '', string $role2 = '', string $org2 = ''): string
    {
        $colDual = $name2 !== '' || $org2 !== '';
        $td1 = '<td><div class="sig-line">' . $img1 . '</div><div class="name-line"><span class="name">' . $name1 . '</span></div>'
             . '<div class="role">' . $role1 . ', ' . $org1 . '</div></td>';
        $td2 = $colDual
            ? '<td><div class="sig-line">' . $img2 . '</div><div class="name-line"><span class="name">' . $name2 . '</span></div>'
             . '<div class="role">' . $role2 . ', ' . $org2 . '</div></td>'
            : '';
        return '<div class="sigs"><table><tr>' . $td1 . $td2 . '</tr></table></div>';
    }

    private function imageTag(string $relPath, string $subdir, string $style = ''): string
    {
        if (!$relPath) return '';
        $abs = (defined('VOLDEBUG_ROOT') ? VOLDEBUG_ROOT : dirname(__DIR__))
            . '/Admin/images/' . $subdir . '/' . $relPath;
        if (!is_file($abs)) return '';
        $mime = mime_content_type($abs) ?: 'image/png';
        $data = base64_encode(file_get_contents($abs));
        return '<img src="data:' . $mime . ';base64,' . $data . '" style="' . htmlspecialchars($style, ENT_QUOTES) . '" alt="">';
    }

    private function buildQrDataUri(string $url): string
    {
        return Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->size(200)
            ->margin(6)
            ->build()
            ->getDataUri();
    }

    private function randomToken(int $bytes = 16): string
    {
        return rtrim(strtr(base64_encode(random_bytes($bytes)), '+/', '-_'), '=');
    }

    private function flatten(array $vars): array
    {
        $out = [];
        foreach ($vars as $k => $v) $out['{{' . $k . '}}'] = (string) $v;
        return $out;
    }
}
