<?php
// Tiny math-CAPTCHA. No external service, no JS. Bots that don't execute JS / parse simple
// math drop ~95% of automated submissions. Good first defence; upgrade to Turnstile later
// if needed for clearly-determined attackers.

if (session_status() === PHP_SESSION_NONE) session_start();

/** Generate a new challenge and store the answer in the session. Returns the question string. */
function captcha_challenge(): string {
    $a = random_int(2, 9);
    $b = random_int(2, 9);
    $op = ['+', '-', '×'][random_int(0, 2)];
    $answer = match ($op) {
        '+' => $a + $b,
        '-' => $a - $b,
        '×' => $a * $b,
    };
    $_SESSION['_captcha_answer'] = (string) $answer;
    return "What is {$a} {$op} {$b}?";
}

/** Render the field as an HTML snippet. Call inside <form>. */
function captcha_field(): string {
    $q = captcha_challenge();
    return '<div class="form-group" style="margin:12px 0">'
        . '<label style="font-weight:500">' . htmlspecialchars($q) . '</label>'
        . '<input type="text" name="_captcha" class="form-control" required autocomplete="off" pattern="-?[0-9]+" inputmode="numeric" placeholder="Enter the answer">'
        . '</div>';
}

/** Verify. Returns true on success; on failure also clears the session answer so the user gets a new one. */
function captcha_verify(): bool {
    $expected = $_SESSION['_captcha_answer'] ?? '';
    $given    = trim((string)($_POST['_captcha'] ?? ''));
    unset($_SESSION['_captcha_answer']);
    return $expected !== '' && $given !== '' && $expected === $given;
}

/** Convenience: 400 + die on failure. */
function captcha_require(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') return;
    if (!captcha_verify()) {
        http_response_code(400);
        die('CAPTCHA failed — please go back and try again.');
    }
}
