<?php
/**
 * MONTERO STUDIO - Stateless Session Authentication & Security Helper (Vercel-ready)
 * Implements signed cookie authentication and Double Submit Cookie CSRF protection.
 */

// Helper to get secret key for signing cookies
function get_app_secret() {
    return getenv('APP_SECRET') ?: hash('sha256', getenv('DATABASE_URL') ?: 'montero_studio_default_secret_389274928');
}

// Initialize CSRF Token (Double Submit Cookie Pattern)
// We set a cookie containing a secure random token if it doesn't exist
$isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
if (empty($_COOKIE['csrf_token'])) {
    $csrfToken = bin2hex(random_bytes(32));
    if (!headers_sent()) {
        setcookie('csrf_token', $csrfToken, [
            'expires'  => time() + 7200, // 2 hours
            'path'     => '/',
            'secure'   => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
} else {
    $csrfToken = $_COOKIE['csrf_token'];
}

/**
 * Verify whether an admin is logged in via secure signed cookie.
 */
function is_admin_logged_in() {
    if (empty($_COOKIE['admin_auth'])) {
        return false;
    }

    $cookieParts = explode('.', $_COOKIE['admin_auth'], 2);
    if (count($cookieParts) !== 2) {
        return false;
    }

    list($payloadBase64, $signature) = $cookieParts;
    $secret = get_app_secret();
    $expectedSignature = hash_hmac('sha256', $payloadBase64, $secret);

    if (!hash_equals($expectedSignature, $signature)) {
        return false;
    }

    $payload = json_decode(base64_decode($payloadBase64), true);
    if (!$payload || empty($payload['username']) || empty($payload['expires'])) {
        return false;
    }

    if ($payload['expires'] < time()) {
        return false;
    }

    return true;
}

/**
 * Get current admin username.
 */
function get_admin_user() {
    if (!is_admin_logged_in()) {
        return 'Administrador';
    }
    list($payloadBase64, ) = explode('.', $_COOKIE['admin_auth'], 2);
    $payload = json_decode(base64_decode($payloadBase64), true);
    return $payload['username'] ?? 'Administrador';
}

/**
 * Enforce authentication for admin pages. Redirects to login page if unauthorized.
 */
function require_admin_auth() {
    if (!is_admin_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Validate a CSRF token using the Double Submit Cookie pattern.
 */
function validate_admin_csrf($token) {
    $cookieToken = $_COOKIE['csrf_token'] ?? '';
    if (empty($token) || empty($cookieToken)) {
        return false;
    }
    return hash_equals($cookieToken, $token);
}

/**
 * Retrieve the current CSRF token.
 */
function get_admin_csrf_token() {
    return $_COOKIE['csrf_token'] ?? '';
}

/**
 * Set the admin auth cookie (stateless login).
 */
function set_admin_login_cookie($username) {
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    $expires = time() + 7200; // 2 hours

    $payload = [
        'username' => $username,
        'expires'  => $expires
    ];
    $payloadBase64 = base64_encode(json_encode($payload));
    $secret = get_app_secret();
    $signature = hash_hmac('sha256', $payloadBase64, $secret);
    $cookieValue = $payloadBase64 . '.' . $signature;

    setcookie('admin_auth', $cookieValue, [
        'expires'  => $expires,
        'path'     => '/',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

/**
 * Clear the admin auth cookie (stateless logout).
 */
function clear_admin_login_cookie() {
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    setcookie('admin_auth', '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}
