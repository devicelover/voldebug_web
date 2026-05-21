<?php
// Single entry point for the HR / letters subsystem.
// Loads composer autoload, DB connection, and secrets, and exposes helpers.

if (!defined('VOLDEBUG_ROOT')) {
    define('VOLDEBUG_ROOT', dirname(__DIR__));
}

require_once VOLDEBUG_ROOT . '/vendor/autoload.php';
require_once VOLDEBUG_ROOT . '/config.php';         // provides $con (mysqli)

$secretsPath = __DIR__ . '/secrets.php';
if (!file_exists($secretsPath)) {
    die('Missing includes/secrets.php — copy secrets.php.example and fill it in.');
}
$APP_SECRETS = require $secretsPath;

// Load persistent letterhead settings once so every page has them.
$APP_SETTINGS = [];
if (isset($con) && $con instanceof mysqli) {
    $r = $con->query("SELECT * FROM settings WHERE id = 1");
    if ($r && ($row = $r->fetch_assoc())) {
        $APP_SETTINGS = $row;
    }
}

// Guard function definitions so the bootstrap is safe to require twice.
if (!function_exists('public_url')) {
    /** Resolve a URL relative to the configured public base (used in QR codes, emails). */
    function public_url(string $path = ''): string {
        global $APP_SECRETS;
        $base = rtrim($APP_SECRETS['public_base_url'] ?? '', '/');
        return $base . '/' . ltrim($path, '/');
    }

    /** Generate a URL-safe random token for letter verification. */
    function generate_verify_token(int $bytes = 16): string {
        return rtrim(strtr(base64_encode(random_bytes($bytes)), '+/', '-_'), '=');
    }

    /**
     * Replace placeholders in a template string.
     *
     * Supports:
     *   - {{key}}                — simple substitution
     *   - {{#IF key}}...{{/IF}}  — block only rendered if the value is non-empty
     *
     * Unknown simple keys are left as-is (handy for debugging missing data).
     */
    function render_placeholders(string $template, array $vars): string {
        // Conditional blocks first. Non-greedy so multiple blocks in one doc don't collide.
        $template = preg_replace_callback(
            '/\{\{\s*#IF\s+([a-zA-Z0-9_]+)\s*\}\}(.*?)\{\{\s*\/IF\s*\}\}/s',
            function (array $m) use ($vars): string {
                $key = $m[1];
                $value = $vars[$key] ?? '';
                if (is_string($value) && trim($value) === '') return '';
                if ($value === null || $value === false || $value === 0 || $value === '0') return '';
                return $m[2];
            },
            $template
        );

        // Simple substitutions.
        return preg_replace_callback(
            '/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/',
            function (array $m) use ($vars): string {
                return array_key_exists($m[1], $vars) ? (string) $vars[$m[1]] : $m[0];
            },
            $template
        );
    }

    /**
     * Derive pronouns + name parts + honorifics from an intern row.
     * Returns [subject, object, possessive, first_name, honorific_first, honorific_full].
     *
     *   title_prefix    subject  object  possessive   honorific_first   honorific_full
     *   Mr              he       him     his          "Mr. Rutu"        "Mr. Rutu Patel"
     *   Ms / Mrs        she      her     her          "Ms. Rutu"        "Ms. Rutu Patel"
     *   (none/Mx/Dr)    they     them    their        "Rutu" / "Dr."    "Rutu Patel" / "Dr. …"
     */
    function derive_pronouns(array $intern): array {
        $prefix = trim((string)($intern['title_prefix'] ?? ''));
        $full   = trim((string)($intern['name']         ?? ''));
        $first  = trim((string)($intern['first_name']   ?? ''));
        if ($first === '' && $full !== '') {
            $parts = preg_split('/\s+/', $full);
            $first = $parts[0] ?? '';
        }
        $normalizedPrefix = $prefix !== '' ? rtrim($prefix, '.') . '.' : '';

        $p = strtolower($prefix);
        if ($p === 'mr.' || $p === 'mr') {
            $s = ['he','him','his']; $np = 'Mr.';
        } elseif ($p === 'ms.' || $p === 'ms' || $p === 'mrs.' || $p === 'mrs' || $p === 'miss') {
            $s = ['she','her','her']; $np = rtrim($prefix, '.') . '.';
        } else {
            $s = ['they','them','their']; $np = $normalizedPrefix;  // '' if no prefix
        }

        $honorificFirst = trim(($np ? "{$np} " : '') . $first);
        $honorificFull  = trim(($np ? "{$np} " : '') . $full);

        return [$s[0], $s[1], $s[2], $first, $honorificFirst, $honorificFull];
    }
}
