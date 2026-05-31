<?php
/**
 * MONTERO STUDIO - Secure Image Delivery Server
 * Prevents Directory Traversal and validates file existence and extension safety.
 */

// Define upload directory
$uploadDir = __DIR__ . '/uploads/';

// Retrieve and sanitize filename input
$filename = isset($_GET['file']) ? $_GET['file'] : '';
$filename = basename($filename); // Remove any directory paths (../ or ..\)

if (empty($filename)) {
    header("HTTP/1.0 400 Bad Request");
    echo "Petición incorrecta.";
    exit;
}

$filePath = $uploadDir . $filename;

// Verify file exists and is located within the uploads folder
if (!file_exists($filePath) || !is_file($filePath)) {
    header("HTTP/1.0 404 Not Found");
    echo "Imagen no encontrada.";
    exit;
}

// Extension allow-list verification
$extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
$allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

if (!in_array($extension, $allowedExtensions)) {
    header("HTTP/1.0 403 Forbidden");
    echo "Acceso denegado.";
    exit;
}

// Detect MIME Content-Type
$mimeType = '';
if (function_exists('mime_content_type')) {
    $mimeType = @mime_content_type($filePath);
}

// Fallback MIME mappings
if (empty($mimeType) || $mimeType === 'text/plain') {
    switch ($extension) {
        case 'png':  $mimeType = 'image/png'; break;
        case 'webp': $mimeType = 'image/webp'; break;
        case 'gif':  $mimeType = 'image/gif'; break;
        case 'jpg':
        case 'jpeg': $mimeType = 'image/jpeg'; break;
        default:     $mimeType = 'application/octet-stream'; break;
    }
}

// Double check mime type is indeed an image
if (strpos($mimeType, 'image/') !== 0) {
    header("HTTP/1.0 403 Forbidden");
    echo "Acceso denegado.";
    exit;
}

// Send security and caching headers
header("Content-Type: " . $mimeType);
header("X-Content-Type-Options: nosniff");
header("Content-Length: " . filesize($filePath));
header("Cache-Control: public, max-age=86400"); // Cache for 1 day

// Output the file contents safely
readfile($filePath);
exit;
