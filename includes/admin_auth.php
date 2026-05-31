<?php
/**
 * MONTERO STUDIO - Session Authentication & Security Helper
 * Sets secure session headers and provides helper validation methods.
 */

// Start session with secure parameters if not active
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    session_start([
        'cookie_httponly' => true,
        'cookie_secure'   => $isSecure,
        'cookie_samesite' => 'Lax'
    ]);
}

// Generate session-level CSRF validation token if missing
if (empty($_SESSION['admin_csrf_token'])) {
    $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Verify whether an admin is logged in.
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
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
 * Validate a CSRF token to prevent Cross-Site Request Forgery.
 */
function validate_admin_csrf($token) {
    if (empty($token) || empty($_SESSION['admin_csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['admin_csrf_token'], $token);
}

/**
 * Retrieve the current CSRF token.
 */
function get_admin_csrf_token() {
    return $_SESSION['admin_csrf_token'];
}
