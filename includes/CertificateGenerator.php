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
        $stmt->bind_param('siiisssssiiii',
            $token, $tid, $pid, $bid, $name, $email,
            $course, $completionDate, $duration, $customJson,
            $sig, $stmp, $relPath
        );

        // bind_param requires variables by reference, and the partner/batch nullable ints
        // need to be re-bound with NULL handling. Quick workaround:
        if ($pid === null || $bid === null) {
            $stmt->close();
            $stmt = $this->db->prepare(
                "INSERT INTO certificates_issued
                    (verify_token, template_id, partner_id, batch_id, recipient_name, recipient_email,
                     course_name, completion_date, duration, custom_fields,
                     include_signature, include_stamp, pdf_path)
                 VALUES (?, ?, NULLIF(?, 0), NULLIF(?, 0), ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $pidI = $pid ?? 0; $bidI = $bid ?? 0;
            $stmt->bind_param('siiisssssiiii',
                $token, $tid, $pidI, $bidI, $name, $email,
                $course, $completionDate, $duration, $customJson,
                $sig, $stmp, $relPath
            );
        }
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
        $accent  = '#c8a45d';                                   // gold accent for ornate feel
        $title   = htmlspecialchars($tpl['title']);
        $company = htmlspecialchars($vars['company']);
        $cin     = htmlspecialchars($this->settings['cin']     ?? '');
        $sigName = htmlspecialchars($vars['signatory']);
        $sigRole = htmlspecialchars($vars['signatory_role']);
        $verify  = htmlspecialchars($vars['verify_url']);

        $logoTag = $this->imageTag($this->settings['logo'] ?? '', 'letterhead', 'height:60px');
        $sigImg  = $includeSig   ? $this->imageTag($this->settings['signature_image'] ?? '', 'letterhead', 'max-height:42px') : '';
        $stampImg= $includeStamp ? $this->imageTag($this->settings['stamp_image']     ?? '', 'letterhead', 'max-height:90px') : '';

        // Partner block — only rendered if a partner row was passed.
        $partnerLogo = $partner ? $this->imageTag($partner['logo'] ?? '', 'cert_partners', 'height:60px') : '';
        $partnerName = $partner ? htmlspecialchars($partner['name']) : '';
        $partnerSubtitle = $partner ? htmlspecialchars($partner['subtitle'] ?? '') : '';
        $partnerSigName  = $partner ? htmlspecialchars($partner['signatory_name'] ?? '') : '';
        $partnerSigRole  = $partner ? htmlspecialchars($partner['signatory_designation'] ?? '') : '';
        $partnerSigImg   = $partner ? $this->imageTag($partner['signature_image'] ?? '', 'cert_partners', 'max-height:42px') : '';

        // Build the dual or single signature block.
        if ($partner) {
            $signaturesHtml = $this->signatureBlock($sigImg, $sigName, $sigRole, $company,
                                                    $partnerSigImg, $partnerSigName, $partnerSigRole, $partnerName);
        } else {
            $signaturesHtml = $this->signatureBlock($sigImg, $sigName, $sigRole, $company);
        }

        $qrHtml = $qrDataUri
            ? '<div class="qr"><img src="' . $qrDataUri . '" alt="Verify QR"><div class="qr-label">Scan to verify<br><span class="ref">VDB-' . htmlspecialchars(substr($token, 0, 10)) . '</span></div></div>'
            : '';

        $headerPartnerHtml = '';
        if ($partner) {
            $headerPartnerHtml = '<td class="partner-cell"><div class="partner-wrap">'
                . ($partnerLogo ?: '<div class="partner-logo-placeholder">' . htmlspecialchars(substr($partnerName, 0, 18)) . '</div>')
                . '<div class="partner-name">' . $partnerName . '</div>'
                . ($partnerSubtitle ? '<div class="partner-sub">' . $partnerSubtitle . '</div>' : '')
                . '</div></td>';
        }

        $headerHtml = '<table class="hdr"><tr>'
            . '<td class="brand-cell"><div class="brand-wrap">' . $logoTag . '<div class="company-name">' . $company . '</div>'
            . ($cin ? '<div class="cin">CIN: ' . $cin . '</div>' : '') . '</div></td>'
            . ($partner ? '<td class="header-divider"></td>' . $headerPartnerHtml : '')
            . '</tr></table>';

        return <<<HTML
<!doctype html>
<html><head><meta charset="utf-8">
<style>
    @page { margin: 0; }
    body { font-family: DejaVu Sans, sans-serif; color: #1a1a1a; margin: 0; padding: 0; background: #fff; }

    .page { position: relative; width: 100%; height: 200mm; padding: 14mm 18mm; box-sizing: border-box; }
    .border-outer { position: absolute; left: 8mm; right: 8mm; top: 8mm; bottom: 8mm;
        border: 3px solid {$brand}; border-radius: 6px; }
    .border-inner { position: absolute; left: 11mm; right: 11mm; top: 11mm; bottom: 11mm;
        border: 1px solid {$accent}; border-radius: 4px; }

    /* Corner flourishes */
    .corner { position: absolute; width: 28px; height: 28px; border: 3px solid {$brand}; }
    .corner.tl { left: 4mm; top: 4mm; border-right: none; border-bottom: none; }
    .corner.tr { right: 4mm; top: 4mm; border-left: none; border-bottom: none; }
    .corner.bl { left: 4mm; bottom: 4mm; border-right: none; border-top: none; }
    .corner.br { right: 4mm; bottom: 4mm; border-left: none; border-top: none; }

    .content { position: relative; z-index: 2; padding: 12mm 14mm 8mm 14mm; }

    /* Header */
    .hdr { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    .hdr td { vertical-align: middle; }
    .hdr .brand-cell, .hdr .partner-cell { width: 50%; padding: 0 12px; }
    .hdr .header-divider { width: 1px; border-left: 1px solid {$accent}; height: 70px; }
    .hdr .brand-cell { text-align: left; }
    .hdr .partner-cell { text-align: right; }
    .hdr .brand-wrap img, .hdr .partner-wrap img { display: inline-block; vertical-align: middle; }
    .company-name, .partner-name { font-size: 14pt; font-weight: 700; color: {$brand}; margin-top: 4px; }
    .cin, .partner-sub { font-size: 8pt; color: #777; }
    .partner-logo-placeholder { display: inline-block; padding: 8px 14px; border: 1px dashed #aaa; color: #777; font-size: 11pt; }

    .title-area { text-align: center; margin: 18px 0 12px 0; }
    .title-area .pre-title { font-size: 10pt; letter-spacing: 4px; color: {$accent}; text-transform: uppercase; }
    .title-area .title {
        font-family: DejaVu Serif, Georgia, serif; font-size: 30pt; font-weight: 800;
        color: #1a1a1a; margin: 4px 0;
    }
    .title-area .underline {
        display: inline-block; width: 200px; height: 3px;
        background: linear-gradient(to right, transparent, {$brand}, transparent);
        margin: 6px auto;
    }

    .body { text-align: center; line-height: 1.6; }
    .body .lead { font-size: 11pt; color: #555; margin: 8px 0 4px 0; }
    .body .recipient {
        font-family: DejaVu Serif, Georgia, serif; font-style: italic; font-weight: 700;
        font-size: 26pt; color: {$brand}; margin: 6px 0 10px 0; letter-spacing: 0.5px;
    }
    .body .body { font-size: 11pt; color: #222; max-width: 560px; margin: 6px auto; }
    .body strong { color: #111; }

    /* Signatures */
    .sigs { margin-top: 28px; }
    .sigs table { width: 100%; border-collapse: collapse; }
    .sigs td { vertical-align: top; padding: 0 16px; text-align: center; }
    .sigs .sig-line { height: 30px; display: block; }
    .sigs .sig-line img { max-height: 30px; }
    .sigs .name-line { border-top: 1px solid #444; padding-top: 4px; margin: 0 18px; }
    .sigs .name { font-weight: 700; font-size: 11pt; color: #111; }
    .sigs .role { font-size: 9pt; color: #555; }

    /* Stamp */
    .stamp-wrap { position: absolute; right: 60mm; bottom: 28mm; opacity: 0.92; }
    .stamp-wrap img { max-height: 95px; }

    /* QR */
    .qr { position: absolute; left: 20mm; bottom: 22mm; text-align: center; }
    .qr img { width: 80px; height: 80px; }
    .qr-label { font-size: 7.5pt; color: #555; margin-top: 2px; }
    .qr-label .ref { font-family: DejaVu Sans Mono, monospace; font-size: 7pt; color: #111; }

    /* Footer ribbon */
    .footer { position: absolute; left: 18mm; right: 18mm; bottom: 12mm; text-align: center; font-size: 7.5pt; color: #888; }
    .footer .verify-line { font-family: DejaVu Sans Mono, monospace; font-size: 7pt; }
</style>
</head>
<body>

<div class="page">
    <div class="border-outer"></div>
    <div class="border-inner"></div>
    <div class="corner tl"></div>
    <div class="corner tr"></div>
    <div class="corner bl"></div>
    <div class="corner br"></div>

    <div class="content">
        {$headerHtml}

        <div class="title-area">
            <div class="pre-title">Voldebug Innovations · Awarded by</div>
            <div class="title">{$title}</div>
            <div class="underline"></div>
        </div>

        <div class="body">
            {$body}
        </div>

        {$signaturesHtml}
    </div>

    {$qrHtml}
    <div class="stamp-wrap">{$stampImg}</div>

    <div class="footer">
        Authenticity-verifiable at the URL below · system-generated certificate<br>
        <span class="verify-line">{$verify}</span>
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
