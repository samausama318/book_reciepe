<?php
// Include database connection
require_once 'db_connect.php';

// Check if ID parameter exists
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get recipe ID from URL parameter
$id = mysqli_real_escape_string($conn, $_GET['id']);

// Get the image file path before deleting the recipe
$query = "SELECT image FROM recipes WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $recipe = mysqli_fetch_assoc($result);
    $image_path = $recipe['image'];

    // Delete recipe from database
    $delete_query = "DELETE FROM recipes WHERE id = $id";

    if (mysqli_query($conn, $delete_query)) {
        // Delete the image file if it exists and is a local file (not a URL)
        if (!empty($image_path) && !filter_var($image_path, FILTER_VALIDATE_URL) && file_exists($image_path)) {
            unlink($image_path);
        }

        // Redirect to home page with success message
        header('Location: index.php?status=deleted');
        exit;
    } else {
        // If delete failed, go back to home page with error
        header('Location: index.php?error=delete');
        exit;
    }
} else {
    // Recipe not found
    header('Location: index.php');
    exit;
}

// Close database connection
mysqli_close($conn);
?>