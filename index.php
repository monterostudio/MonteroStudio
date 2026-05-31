<?php
/**
 * MonteroStudio - Main Entry Point & Contact Controller (SPA Layout)
 * Implements strict input sanitization, token checks, and SPA dynamic templating.
 */

// Start session if not already initialized
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate a secure CSRF token if it does not exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle Form POST requests (AJAX Form Controller)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set headers for JSON response
    header('Content-Type: application/json; charset=utf-8');
    
    // Read and sanitize inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? 'General');
    $message = trim($_POST['message'] ?? '');
    $csrf_token = $_POST['csrf_token'] ?? '';

    // 1. Validate CSRF Token (hash_equals to prevent timing attacks)
    if (empty($csrf_token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        echo json_encode([
            'success' => false,
            'message' => 'Error de seguridad: Token de validación inválido o expirado. Por favor, recarga la página.'
        ]);
        exit;
    }

    // 2. Validate Name
    if (empty($name) || strlen($name) < 2 || strlen($name) > 80) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, ingresa un nombre válido (entre 2 y 80 caracteres).'
        ]);
        exit;
    }

    // 3. Validate Email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, ingresa una dirección de correo electrónico válida.'
        ]);
        exit;
    }

    // 4. Validate Message
    if (empty($message) || strlen($message) < 10 || strlen($message) > 1500) {
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, describe tu proyecto con más detalles (entre 10 y 1500 caracteres).'
        ]);
        exit;
    }

    // 5. Success Flow
    // Regenerate CSRF token after successful submission (prevent token reuse)
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Optional: Log message details locally for mock/review in workspace
    // Ensure the folder exists if writing to file (TODO: production should connect to database or send SMTP mail)
    
    echo json_encode([
        'success' => true,
        'message' => '¡Tu mensaje ha sido recibido con éxito! En MONTERO STUDIO me pondré en contacto contigo a la brevedad.',
        'new_csrf' => $_SESSION['csrf_token']
    ]);
    exit;
}

// Handle Page GET requests (SPA Renderer)
// Load structural HTML parts and components in order
include_once __DIR__ . '/includes/header.php';

// SPA Sections inclusion
include_once __DIR__ . '/components/hero.php';
include_once __DIR__ . '/components/about.php';
include_once __DIR__ . '/components/services.php';
include_once __DIR__ . '/components/portfolio.php';
include_once __DIR__ . '/components/cinema.php';
include_once __DIR__ . '/components/contact.php';

include_once __DIR__ . '/includes/footer.php';
