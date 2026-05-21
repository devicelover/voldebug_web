<?php
// Public career-apply endpoint. CSRF NOT enforced (anonymous POST) —
// protected by rate-limiting + CAPTCHA + honeypot + dedupe.
require __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/Mailer.php';
require_once __DIR__ . '/includes/rate_limit.php';
require_once __DIR__ . '/includes/captcha.php';

rate_limit_or_die('career_apply', 5, 300);

if (isset($_POST["client_cureer"])) {
    // Honeypot: hidden field 'website_hp' must remain empty (bots fill all fields).
    if (!empty($_POST['website_hp'])) {
        // Silently accept and discard — looks successful to the bot, never reaches DB.
        echo '<script>window.location.href="career.php";</script>';
        exit;
    }

    captcha_require();

    $name           = trim($_POST["name"]        ?? '');
    $description    = trim($_POST["description"] ?? '');
    $email          = trim($_POST["email"]       ?? '');
    $phone          = trim($_POST["phone"]       ?? '');
    $Position       = trim($_POST["Position"]    ?? '');
    $applied_job_id = isset($_POST["applied_job_id"]) && $_POST["applied_job_id"] !== ''
        ? (int) $_POST["applied_job_id"] : null;
    $pdf_uploaded   = $_FILES["pdf"]["name"] ?? '';

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $name === '' || $Position === '') {
        echo '<script>alert("Please complete all required fields with a valid email."); history.back();</script>';
        exit;
    }

    // === Duplicate detection: same email + same position in last 60 days = block ===
    $dup = $con->prepare(
        "SELECT id, created_at FROM client_career
         WHERE email = ? AND (Position = ? OR applied_job_id = ?)
           AND created_at > (NOW() - INTERVAL 60 DAY)
         LIMIT 1"
    );
    $jobIdForDup = $applied_job_id ?? 0;
    $dup->bind_param('ssi', $email, $Position, $jobIdForDup);
    $dup->execute();
    if ($existing = $dup->get_result()->fetch_assoc()) {
        echo '<script>alert("We already have an application from this email for ' . addslashes($Position) . ' (Ref #' . (int)$existing['id'] . '). Check your status at /application-status.php"); window.location.href="application-status.php";</script>';
        exit;
    }

    if (strtolower(pathinfo($pdf_uploaded, PATHINFO_EXTENSION)) !== 'pdf') {
        echo '<script>alert("Only PDF files are allowed."); window.location.href="career-details.php";</script>';
        exit;
    }

    $filename = time() . '_' . bin2hex(random_bytes(3)) . '.pdf';
    $target_dir = __DIR__ . '/Admin/images/client_resume_pdfs/';
    if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

    $stmt = $con->prepare(
        "INSERT INTO client_career (name, description, email, phone, Position, pdf, applied_job_id, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'applied')"
    );
    $stmt->bind_param('ssssssi', $name, $description, $email, $phone, $Position, $filename, $applied_job_id);

    if ($stmt->execute()) {
        if (!move_uploaded_file($_FILES["pdf"]["tmp_name"], $target_dir . $filename)) {
            echo '<script>alert("Failed to store resume. Please try again."); window.location.href="career-details.php";</script>';
            exit;
        }

        $applicantId = (int) $con->insert_id;
        $companyName = $APP_SETTINGS['name']     ?? 'Voldebug';
        $hrEmail     = $APP_SETTINGS['hr_email'] ?? 'hr@voldebug.in';

        $statusUrl = public_url('application-status.php');
        $subject = "We received your application — {$companyName}";
        $body = '<p>Hi ' . htmlspecialchars($name) . ',</p>'
              . '<p>Thanks for applying to <strong>' . htmlspecialchars($companyName) . '</strong> for the position of <strong>' . htmlspecialchars($Position) . '</strong>.</p>'
              . '<p><strong>Your reference number is #' . $applicantId . '.</strong> '
              . 'You can check the status of your application any time at <a href="' . htmlspecialchars($statusUrl) . '">' . htmlspecialchars($statusUrl) . '</a> using this reference + the email you applied with.</p>'
              . '<p>Our team reviews applications weekly. For any queries, reply to this email or write to <a href="mailto:' . htmlspecialchars($hrEmail) . '">' . htmlspecialchars($hrEmail) . '</a>.</p>'
              . '<p>— ' . htmlspecialchars($companyName) . ' HR</p>';

        try {
            $mailer = new Mailer($con, $APP_SECRETS['smtp']);
            $mailer->send($email, $name, $subject, $body, [], ['type' => 'applicant_ack', 'id' => $applicantId]);
        } catch (Throwable $e) {
            // Don't block the applicant on mail failure.
        }

        echo '<script>alert("Application submitted successfully. You will receive a confirmation by email."); window.location.href="career.php";</script>';
        exit;
    }

    echo '<script>alert("Application failed: ' . addslashes($stmt->error) . '"); window.location.href="career-details.php";</script>';
    exit;
}
