<?php
// Start session
session_start();

// Include database connection
require_once 'db_connect.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get action type (login or register)
    $action = $_POST['action'] ?? '';

    // Process based on action type
    switch ($action) {
        case 'login':
            // Login user
            loginUser($conn);
            break;

        case 'register':
            // Register new user
            registerUser($conn);
            break;

        case 'logout':
            // Logout user
            logoutUser();
            break;

        default:
            // Invalid action, redirect to home
            header('Location: index.php');
            exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'logout') {
    // Logout via GET request
    logoutUser();
} else {
    // Not a valid request, redirect to home
    header('Location: index.php');
    exit;
}

/**
 * Login a user
 *
 * @param mysqli $conn Database connection
 */
function loginUser($conn) {
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Validate required fields
    if (empty($username) || empty($password)) {
        header('Location: login.php?error=empty');
        exit;
    }

    // Check if user exists
    $query = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to home page
            header('Location: index.php');
            exit;
        }
    }

    // Invalid login
    header('Location: login.php?error=invalid');
    exit;
}

/**
 * Register a new user
 *
 * @param mysqli $conn Database connection
 */
function registerUser($conn) {
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate required fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        header('Location: register.php?error=empty');
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        header('Location: register.php?error=password_mismatch');
        exit;
    }

    // Check password length
    if (strlen($password) < 8) {
        header('Location: register.php?error=password_length');
        exit;
    }

    // Check if username already exists
    $query = "SELECT id FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        header('Location: register.php?error=username_exists');
        exit;
    }

    // Check if email already exists
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        header('Location: register.php?error=email_exists');
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($conn, $query)) {
        // Registration successful, redirect to login page
        header('Location: login.php?registered=1');
        exit;
    } else {
        // Database error
        header('Location: register.php?error=database');
        exit;
    }
}

/**
 * Logout a user
 */
function logoutUser() {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header('Location: login.php');
    exit;
}
?>