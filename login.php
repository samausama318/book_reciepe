<?php
// Start session
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Set page title
$page_title = "Login";

// Check for error messages
$error = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid':
            $error = 'Invalid username or password.';
            break;
        case 'empty':
            $error = 'Please fill in all fields.';
            break;
    }
}

// Check for success messages
$success = '';
if (isset($_GET['registered'])) {
    $success = 'Registration successful! You can now log in.';
}

// Include header
include 'includes/header.php';
?>

    <div class="auth-background login-background">
        <div class="auth-overlay">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="card auth-card shadow-lg">
                            <div class="card-header text-center bg-white border-bottom-0 pt-4">
                                <h3 class="text-primary fw-bold">Welcome Back</h3>
                                <p class="text-muted">Sign in to access your recipe collection</p>
                            </div>
                            <div class="card-body p-4">
                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($success)): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <form action="process_user.php" method="post">
                                    <input type="hidden" name="action" value="login">

                                    <div class="mb-4">
                                        <label for="username" class="form-label">Username</label>
                                        <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                            <input type="text" class="form-control border-start-0" id="username" name="username" placeholder="Enter your username" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between">
                                            <label for="password" class="form-label">Password</label>
                                            <a href="#" class="text-primary small">Forgot password?</a>
                                        </div>
                                        <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                            <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="Enter your password" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">Remember me</label>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer bg-white text-center border-top-0 pb-4">
                                <p class="mb-0">Don't have an account? <a href="register.php" class="text-primary fw-bold">Create Account</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
// Include footer
include 'includes/footer.php';
?>