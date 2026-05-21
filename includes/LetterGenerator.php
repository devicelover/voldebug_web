<?php
// Renders a letter (template + letterhead + intern data + QR) into a PDF
// and saves it under Admin/letters/. Records the issue in letters_issued.

use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class LetterGenerator
{
    public function __construct(
        private mysqli $db,
        private array  $settings,   // row from settings
        private string $publicBase, // e.g. https://voldebug.in
        private string $lettersDir  // absolute filesystem path
    ) {
        if (!is_dir($this->lettersDir)) {
            mkdir($this->lettersDir, 0755, true);
        }
    }

    /**
     * Generate a letter PDF for a given intern + template.
     *
     * @param array $intern   row from `interns`
     * @param array $template row from `letter_templates`
     * @param array $extraVars additional placeholder overrides (e.g. issue_date)
     * @return array{ok:bool, letter_id:int, token:string, pdf_path:string, html:string, email_body:string, email_subject:string}
     */
    /**
     * @param array $opts  ['include_signature' => bool, 'include_stamp' => bool] — both default true.
     */
    public function generate(array $intern, array $template, array $extraVars = [], array $opts = []): array
    {
        $includeSignature = $opts['include_signature'] ?? true;
        $includeStamp     = $opts['include_stamp']     ?? true;

        $token    = generate_verify_token(16);
        $issueDate = $extraVars['issue_date'] ?? date('Y-m-d');
        $base      = rtrim($this->publicBase, '/');
        $verifyUrl = $base . '/verify.php?t=' . urlencode($token);
        $acceptUrl = $base . '/accept-offer.php?t=' . urlencode($token);

        [$pSubj, $pObj, $pPoss, $firstName, $honorificFirst, $honorificFull] = derive_pronouns($intern);

        // Allow the caller to override the raw issue-date but still format it consistently.
        $rawIssueDate = $extraVars['issue_date'] ?? $issueDate;
        unset($extraVars['issue_date']);
        $issueDate = $rawIssueDate;

        // Capitalized pronouns for sentence-starts (so templates can write "{{Pronoun_poss}} contributions...").
        $ucfirst = fn(string $s) => $s === '' ? '' : strtoupper($s[0]) . substr($s, 1);

        $vars = array_merge([
            'name'                  => $intern['name'],
            'first_name'            => $firstName,
            'honorific_name'        => $honorificFirst,                          // "Ms. Rutu"
            'honorific_full_name'   => $honorificFull,                           // "Ms. Rutu Patel"
            // legacy alias kept so older templates don't break:
            'name_last_for_cert'    => $honorificFull,
            'title_prefix'       => trim((string)($intern['title_prefix'] ?? '')),
            'pronoun_subject'    => $pSubj,                                    // he / she / they
            'pronoun_object'     => $pObj,                                     // him / her / them
            'pronoun_possessive' => $pPoss,                                    // his / her / their
            // Capitalized variants for sentence-starts:
            'Pronoun_subject'    => $ucfirst($pSubj),                          // He / She / They
            'Pronoun_object'     => $ucfirst($pObj),                           // Him / Her / Them
            'Pronoun_possessive' => $ucfirst($pPoss),                          // His / Her / Their
            'email'              => $intern['email'],
            'phone'              => $intern['phone']             ?? '',
            'role'               => $intern['role'],
            'start_date'         => $intern['start_date'] ? date('jS F Y', strtotime($intern['start_date'])) : '',
            'end_date'           => $intern['end_date']   ? date('jS F Y', strtotime($intern['end_date']))   : '',
            'enrollment_number'  => $intern['enrollment_number'] ?? '',
            'internship_type'    => $intern['internship_type']   ?? '',
            'college'            => $intern['college']           ?? '',
            'tasks_summary'      => $intern['tasks_summary']     ?? '',
            'github_repo'        => $intern['github_repo']       ?? '',
            'mentor'             => $intern['mentor']            ?? '',
            'stipend'            => $intern['stipend']            ?? '',
            'reporting_location' => $intern['reporting_location'] ?? '',
            'company'            => $this->settings['company_legal_name'] ?: ($this->settings['name'] ?? 'Voldebug'),
            'signatory'          => $this->settings['signatory_name']        ?? '',
            'signatory_role'     => $this->settings['signatory_designation'] ?? '',
            'issue_date'         => date('jS F Y', strtotime($issueDate)),
            'verify_url'         => $verifyUrl,
            'accept_url'         => $acceptUrl,
        ], $extraVars);

        $letterBody  = render_placeholders($template['letter_body'],  $vars);
        $emailBody   = render_placeholders($template['email_body'],   $vars);
        $emailSubject= render_placeholders($template['email_subject'], $vars);

        // Collapse stray double-periods caused by legal names that already end in "." (e.g. "Ltd..").
        // Whitelist: only collapse when followed by whitespace or a closing tag, so we never eat "..." ellipses.
        $letterBody = preg_replace('/\.\.(?=[\s<])/', '.', $letterBody);

        $qrDataUri = $this->buildQrDataUri($verifyUrl);
        $html      = $this->buildFullHtml($letterBody, $vars, $qrDataUri, $token, $includeSignature, $includeStamp);

        $pdfOptions = new DompdfOptions();
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('defaultPaperSize', 'a4');
        $pdfOptions->set('dpi', 96);
        $pdf = new Dompdf($pdfOptions);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        $filename  = $token . '.pdf';
        $pdfAbs    = rtrim($this->lettersDir, '\\/') . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($pdfAbs, $pdf->output());

        $pdfRel = 'Admin/letters/' . $filename;

        $stmt = $this->db->prepare(
            "INSERT INTO letters_issued
                (verify_token, intern_id, template_id, letter_type, recipient_name, recipient_email, role_snapshot, issue_date, pdf_path, rendered_html)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $internId   = (int) $intern['id'];
        $templateId = (int) $template['id'];
        $type       = $template['letter_type'];
        $rname      = $intern['name'];
        $remail     = $intern['email'];
        $rrole      = $intern['role'];
        $stmt->bind_param(
            'siisssssss',
            $token, $internId, $templateId, $type, $rname, $remail, $rrole, $issueDate, $pdfRel, $html
        );
        $stmt->execute();
        $letterId = (int) $stmt->insert_id;

        return [
            'ok'            => true,
            'letter_id'     => $letterId,
            'token'         => $token,
            'pdf_path'      => $pdfAbs,
            'pdf_rel'       => $pdfRel,
            'html'          => $html,
            'email_body'    => $emailBody,
            'email_subject' => $emailSubject,
        ];
    }

    private function buildQrDataUri(string $url): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->size(180)
            ->margin(6)
            ->build();
        return $result->getDataUri();
    }

    private function buildFullHtml(string $letterBody, array $vars, string $qrDataUri, string $token, bool $includeSignature = true, bool $includeStamp = true): string
    {
        $logoSrc      = $this->imageSrcOrEmpty($this->settings['logo']            ?? '', 'letterhead');
        $signatureSrc = $includeSignature ? $this->imageSrcOrEmpty($this->settings['signature_image'] ?? '', 'letterhead') : '';
        $stampSrc     = $includeStamp     ? $this->imageSrcOrEmpty($this->settings['stamp_image']     ?? '', 'letterhead') : '';

        $brand        = $this->settings['brand_color']        ?: '#1a8f4a';
        $legalName    = htmlspecialchars($vars['company']);
        $cin          = htmlspecialchars($this->settings['cin'] ?? '');
        $address      = htmlspecialchars($this->settings['letterhead_address'] ?? '');
        $hrEmail      = htmlspecialchars($this->settings['hr_email']    ?? '');
        $adminEmail   = htmlspecialchars($this->settings['admin_email'] ?? '');
        $website      = htmlspecialchars($this->settings['website']     ?? '');
        $phone        = htmlspecialchars($this->settings['phone']       ?? '');
        $sigName      = htmlspecialchars($vars['signatory']);
        $sigRole      = htmlspecialchars($vars['signatory_role']);
        $issue        = htmlspecialchars($vars['issue_date']);
        $verify       = htmlspecialchars($vars['verify_url']);

        $contactEmail = $adminEmail !== '' ? $adminEmail : $hrEmail;
        $addressLine  = $address !== '' ? $address : '';
        $currentYear  = date('Y');

        // Signature block:
        //   - signature image disabled (include_signature=false)  → empty (no fake handwriting either)
        //   - image enabled + uploaded                            → render image
        //   - image enabled + not uploaded                        → italic-serif fallback of the name
        if (!$includeSignature) {
            $signatureBlock = '';
        } elseif ($signatureSrc !== '') {
            $signatureBlock = '<div class="sign-img">' . $signatureSrc . '</div>';
        } else {
            $signatureBlock = '<div class="handwritten">' . $sigName . '</div>';
        }

        return <<<HTML
<!doctype html>
<html><head><meta charset="utf-8">
<style>
    @page { margin: 11mm 12mm 11mm 12mm; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 9.8pt; color: #222; line-height: 1.38; }

    /* Top accent: thin brand-color rule over a thin dark rule */
    .top-band   { border-top: 3px solid {$brand}; }
    .top-band-2 { border-top: 1px solid #1a1a1a; margin-bottom: 6px; }

    /* Header */
    .header table { width: 100%; border-collapse: collapse; }
    .header td.logo-box { width: 150px; vertical-align: middle; padding: 4px 0; }
    .header td.logo-box .logo-wrap {
        border: 1px solid #dcdfe3; border-radius: 6px; padding: 4px 6px; display: inline-block; background: #fff;
    }
    .header td.logo-box img { max-height: 52px; }
    .header td.logo-box .cin { font-size: 7.8pt; color: #555; margin-top: 3px; }
    .header td.contact { text-align: right; vertical-align: middle; font-size: 8.5pt; line-height: 1.4; color: #222; }
    .header td.contact .row { display: block; }
    .header td.contact .addr { display: block; margin-top: 4px; color: #333; max-width: 310px; margin-left: auto; }

    .header-rule-1 { border-top: 2px solid {$brand}; margin-top: 4px; }
    .header-rule-2 { border-top: 1px solid #333; height: 1px; }

    /* Title */
    .title { text-align: center; margin: 10px 0 2px 0; }
    .title .t { display: inline-block; font-size: 15pt; font-weight: bold; border-bottom: 2px solid #111; padding: 0 6px 2px 6px; }
    .date-line { text-align: right; font-size: 9.5pt; margin: 6px 0 6px 0; color: #111; }

    /* Body */
    .body p  { margin: 0 0 6px 0; }
    .body ul { margin: 3px 0 6px 10px; padding: 0 0 0 14px; }
    .body li { margin-bottom: 1px; }
    .body strong { color: #111; }

    /* Signature block. Uses serif italic as cursive-fallback (dompdf does not bundle Lucida Handwriting). */
    .sign { margin-top: 12px; }
    .sign .salute { margin-bottom: 2px; }
    .sign .sig-row { margin-bottom: 2px; min-height: 34px; }
    .sign .sig-row img { max-height: 34px; }
    .sign .handwritten {
        font-family: Times, "DejaVu Serif", serif;
        font-style: italic; font-size: 18pt; color: #111; letter-spacing: 0.5px;
    }
    .sign .name  { font-weight: bold; }
    .sign .role  { font-size: 9.5pt; color: #333; }
    .sign .legal { font-size: 9.5pt; color: #333; }
    .sign .misc  { font-size: 9pt;   color: #333; }

    /* Stamp */
    .stamp-row { text-align: center; margin-top: 4px; }
    .stamp-row img { max-height: 90px; }

    /* Footer block — verify on the left, company info on the right, decoration underneath. */
    .footer { margin-top: 8px; padding-top: 5px; border-top: 1px dashed #bbb; font-size: 7.6pt; color: #555; page-break-inside: avoid; page-break-before: avoid; }
    .footer table { width: 100%; }
    .footer td.qr     { width: 50px; vertical-align: middle; }
    .footer td.qr img { width: 46px; height: 46px; }
    .footer td.verify { vertical-align: middle; padding-left: 8px; line-height: 1.35; }
    .footer td.verify .tok { font-family: DejaVu Sans Mono, monospace; font-size: 7pt; color: #111; word-break: break-all; }
    .footer td.co     { text-align: right; vertical-align: middle; padding-left: 10px; line-height: 1.35; }
    .footer td.co strong { color: {$brand}; }

    /* Bottom decoration — thin dark rule over brand rule with triangle caps, kept together. */
    .deco { margin-top: 6px; position: relative; height: 14px; page-break-inside: avoid; page-break-before: avoid; }
    .deco .rule-1 { border-top: 1px solid #1a1a1a; margin: 0 28px; }
    .deco .rule-2 { border-top: 3px solid {$brand}; margin: 2px 28px 0 28px; }
    .deco .tri    { position: absolute; width: 20px; height: 14px; background: {$brand}; top: 0; }
    .deco .tri-l  { left: 0;  }
    .deco .tri-r  { right: 0; }
</style>
</head>
<body>

<div class="top-band"></div>
<div class="top-band-2"></div>

<div class="header">
    <table><tr>
        <td class="logo-box">
            <span class="logo-wrap">{$logoSrc}</span>
            <div class="cin">CIN: {$cin}</div>
        </td>
        <td class="contact">
            <span class="row">☎  {$phone}</span>
            <span class="row">✉  {$contactEmail}</span>
            <span class="row">⌾  {$website}</span>
            <span class="addr">{$addressLine}</span>
        </td>
    </tr></table>
</div>

<div class="header-rule-1"></div>
<div class="header-rule-2"></div>

<div class="body">
{$letterBody}
</div>

<div class="sign">
    <div class="salute">Warm Regards,</div>
    <div class="sig-row">{$signatureBlock}</div>
    <div class="name">{$sigName}</div>
    <div class="role">{$sigRole}</div>
    <div class="legal">{$legalName}</div>
</div>

<div class="stamp-row">{$stampSrc}</div>

<div class="footer">
    <table><tr>
        <td class="qr"><img src="{$qrDataUri}" alt="Verify"></td>
        <td class="verify">
            <strong>Verify authenticity</strong><br>
            Ref: VDB-{$token}<br>
            <span class="tok">{$verify}</span>
        </td>
        <td class="co">
            <strong>{$legalName}</strong><br>
            CIN: {$cin}<br>
            {$website} &nbsp;·&nbsp; {$contactEmail}<br>
            &copy; {$currentYear} {$legalName} &nbsp;·&nbsp; System-generated document
        </td>
    </tr></table>
</div>

<div class="deco">
    <div class="tri tri-l"></div>
    <div class="tri tri-r"></div>
    <div class="rule-1"></div>
    <div class="rule-2"></div>
</div>

</body></html>
HTML;
    }

    private function imageSrcOrEmpty(string $relPath, string $subdir): string
    {
        if (!$relPath) return '';
        // Try absolute path in Admin/images/{$subdir}/
        $abs = VOLDEBUG_ROOT . '/Admin/images/' . $subdir . '/' . $relPath;
        if (!is_file($abs)) return '';
        $mime = mime_content_type($abs) ?: 'image/png';
        $data = base64_encode(file_get_contents($abs));
        $tag  = '<img src="data:' . $mime . ';base64,' . $data . '" alt="">';
        return $tag;
    }
}
