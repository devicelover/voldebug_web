<?php
// Tiny CSRF helper. Call csrf_require() at the top of every admin POST handler.
// Templates render the token via csrf_field().

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Ensure the session has a CSRF secret and return it. */
function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

/** Render as a hidden input (use inside <form>). */
function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token()) . '">';
}

/**
 * Abort request if CSRF token is missing / invalid. Call first thing in POST handlers.
 * Only enforces on POST so GET / AJAX GET still work. Skipped for un-authenticated flows (login).
 */
function csrf_require(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    $posted = $_POST['_csrf'] ?? '';
    if (!hash_equals(csrf_token(), (string) $posted)) {
        http_response_code(403);
        die('CSRF token missing or invalid. Please reload the page and try again.');
    }
}
