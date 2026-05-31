<?php
/**
 * MONTERO STUDIO - Secure Admin Logout (Stateless)
 * Clears authentication cookies and redirects to login page.
 */

require_once dirname(__DIR__) . '/includes/admin_auth.php';

// Clear the admin auth cookie
clear_admin_login_cookie();

header("Location: login.php");
exit;
