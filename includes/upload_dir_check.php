<?php
// Check if uploads directory exists, if not create it
$uploadDir = 'uploads/recipes';
$fullPath = __DIR__ . '/../' . $uploadDir;

if (!file_exists($fullPath)) {
    // Create directories recursively
    if (!mkdir($fullPath, 0755, true)) {
        die('Failed to create upload directories. Please check permissions.');
    }
}

// Return the upload directory path
define('UPLOAD_DIR', $uploadDir);
?>