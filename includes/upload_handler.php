<?php
/**
 * Handle image uploads for recipes
 *
 * @param array $file The $_FILES array element for the uploaded file
 * @return array Result with success status, path or error message
 */
function upload_image($file) {
    // Include the uploads directory configuration
    require_once 'upload_dir_check.php';

    // Set upload directory
    $target_dir = UPLOAD_DIR . '/';

    // Get file extension
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Generate unique filename to prevent overwriting
    $new_filename = uniqid('recipe_') . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Check if file is an actual image
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return [
            'success' => false,
            'message' => 'File is not an image.'
        ];
    }

    // Check file size (limit to 2MB)
    if ($file['size'] > 2000000) {
        return [
            'success' => false,
            'message' => 'Sorry, your file is too large. Maximum size is 2MB.'
        ];
    }

    // Allow only certain file formats
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_extension, $allowed_extensions)) {
        return [
            'success' => false,
            'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.'
        ];
    }

    // Try to upload file
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return [
            'success' => true,
            'path' => $target_file
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Sorry, there was an error uploading your file.'
        ];
    }
}
?>