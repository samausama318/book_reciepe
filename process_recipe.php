<?php
// Include database connection
require_once 'db_connect.php';
require_once 'includes/upload_handler.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $action = mysqli_real_escape_string($conn, $_POST['action']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : '';
    $prep_time = isset($_POST['prep_time']) ? mysqli_real_escape_string($conn, $_POST['prep_time']) : '';
    $cook_time = isset($_POST['cook_time']) ? mysqli_real_escape_string($conn, $_POST['cook_time']) : '';
    $servings = isset($_POST['servings']) && is_numeric($_POST['servings']) ? mysqli_real_escape_string($conn, $_POST['servings']) : 0;
    $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] == 0) {
        $upload_result = upload_image($_FILES['recipe_image']);
        if ($upload_result['success']) {
            $image_path = $upload_result['path'];
        } else {
            // If upload failed, set error message
            $error_message = $upload_result['message'];
        }
    } else if (isset($_POST['image_url']) && !empty($_POST['image_url'])) {
        // If no file uploaded but URL provided
        $image_path = mysqli_real_escape_string($conn, $_POST['image_url']);
    }

    // Add new recipe
    if ($action == 'add') {
        // Default user_id is 1 (demo user)
        $user_id = 1;

        // Insert recipe into database
        $query = "INSERT INTO recipes (user_id, title, ingredients, instructions, prep_time, cook_time, servings, category, image) 
                  VALUES ($user_id, '$title', '$ingredients', '$instructions', '$prep_time', '$cook_time', $servings, '$category', '$image_path')";

        if (mysqli_query($conn, $query)) {
            // Get the ID of the newly inserted recipe
            $recipe_id = mysqli_insert_id($conn);
            // Redirect to view the new recipe
            header("Location: view_recipe.php?id=$recipe_id&status=added");
            exit;
        } else {
            // If insert failed, go back to add recipe page with error
            header("Location: add_recipe.php?error=database");
            exit;
        }
    }

    // Edit existing recipe
    else if ($action == 'edit' && isset($_POST['id'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        // Update image only if a new one was uploaded
        $image_update = "";
        if (!empty($image_path)) {
            $image_update = ", image = '$image_path'";
        }

        // Update recipe in database
        $query = "UPDATE recipes SET 
                  title = '$title', 
                  ingredients = '$ingredients', 
                  instructions = '$instructions', 
                  prep_time = '$prep_time', 
                  cook_time = '$cook_time', 
                  servings = $servings, 
                  category = '$category'
                  $image_update
                  WHERE id = $id";

        if (mysqli_query($conn, $query)) {
            // Redirect to view the updated recipe
            header("Location: view_recipe.php?id=$id&status=updated");
            exit;
        } else {
            // If update failed, go back to edit page with error
            header("Location: edit_recipe.php?id=$id&error=database");
            exit;
        }
    }

    // Invalid action
    else {
        header("Location: index.php");
        exit;
    }
} else {
    // If not POST request, redirect to home page
    header("Location: index.php");
    exit;
}

// Close database connection
mysqli_close($conn);
?>