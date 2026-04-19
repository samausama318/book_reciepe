<?php
// Start session
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Set page title
$page_title = "Register";

// Check for error messages
$error = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'username_exists':
            $error = 'Username already exists. Please choose another one.';
            break;
        case 'email_exists':
            $error = 'Email already exists. Please use another email.';
            break;
        case 'password_mismatch':
            $error = 'Passwords do not match.';
            break;
        case 'empty':
            $error = 'Please fill in all fields.';
            break;
        case 'password_length':
            $error = 'Password must be at least 8 characters long.';
            break;
    }
}

// Include header
include 'includes/header.php';
?>

    <div class="auth-background register-background">
        <div class="auth-overlay">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-7 col-lg-6">
                        <div class="card auth-card shadow-lg">
                            <div class="card-header text-center bg-white border-bottom-0 pt-4">
                                <h3 class="text-success fw-bold">Create an Account</h3>
                                <p class="text-muted">Join our recipe community today</p>
                            </div>
                            <div class="card-body p-4">
                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <form action="process_user.php" method="post">
                                    <input type="hidden" name="action" value="register">

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username*</label>
                                        <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                            <input type="text" class="form-control border-start-0" id="username" name="username" placeholder="Choose a username" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email*</label>
                                        <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                            <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="Your email address" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password*</label>
                                        <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                            <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="Minimum 8 characters" minlength="8" required>
                                        </div>
                                        <div class="password-strength mt-2">
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%" id="password-strength"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <small class="text-muted">Password Strength</small>
                                                <small class="text-muted" id="password-strength-text">Too weak</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="confirm_password" class="form-label">Confirm Password*</label>
                                        <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                            <input type="password" class="form-control border-start-0" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" minlength="8" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                            <label class="form-check-label" for="terms">
                                                I agree to the <a href="#" class="text-success">Terms of Service</a> and <a href="#" class="text-success">Privacy Policy</a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-user-plus me-2"></i>Create Account
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer bg-white text-center border-top-0 pb-4">
                                <p class="mb-0">Already have an account? <a href="login.php" class="text-success fw-bold">Sign In</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Password strength script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('password-strength');
            const strengthText = document.getElementById('password-strength-text');

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                // Check length
                if (password.length >= 8) strength += 25;

                // Check for lowercase and uppercase letters
                if (password.match(/[a-z]+/)) strength += 15;
                if (password.match(/[A-Z]+/)) strength += 15;

                // Check for numbers
                if (password.match(/[0-9]+/)) strength += 15;

                // Check for special characters
                if (password.match(/[^a-zA-Z0-9]+/)) strength += 30;

                // Update the strength bar
                strengthBar.style.width = strength + '%';

                // Update classes based on strength
                if (strength < 30) {
                    strengthBar.className = 'progress-bar bg-danger';
                    strengthText.textContent = 'Too weak';
                } else if (strength < 60) {
                    strengthBar.className = 'progress-bar bg-warning';
                    strengthText.textContent = 'Medium';
                } else if (strength < 80) {
                    strengthBar.className = 'progress-bar bg-info';
                    strengthText.textContent = 'Good';
                } else {
                    strengthBar.className = 'progress-bar bg-success';
                    strengthText.textContent = 'Strong';
                }
            });
        });
    </script>

<?php
// Include footer
include 'includes/footer.php';
?>