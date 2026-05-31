<?php
/**
 * MONTERO STUDIO - Secure Logout Controller
 * Destroys session variables and clears client-side cookie caches.
 */

require_once dirname(__DIR__) . '/includes/admin_auth.php';

// Unset all session variables
$_SESSION = [];

// Destroy session cookie if set
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session on server
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;
