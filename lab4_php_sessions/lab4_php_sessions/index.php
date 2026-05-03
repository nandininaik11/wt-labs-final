<?php
// ============================================================
// THEORY: Sessions in PHP
// session_start() must be called BEFORE any HTML output
// It creates/resumes a session (stored on the server)
// $_SESSION is a superglobal array to store session data
// ============================================================
session_start(); // Start the session engine

// If user is already logged in, send them to dashboard
// isset() checks if a variable exists and is not null
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php"); // Redirect to dashboard
    exit(); // Stop further code execution
}

// THEORY: Cookies in PHP
// Check if the username cookie exists (set after previous login)
// This is used to pre-fill the name field as a convenience
$remembered_name = "";
if (isset($_COOKIE['username'])) {
    // htmlspecialchars() prevents XSS (Cross-Site Scripting) attacks
    // It converts < > & " ' into safe HTML entities
    $remembered_name = htmlspecialchars($_COOKIE['username']);
}
?>
<!DOCTYPE html>
<!-- THEORY: HTML5 Doctype tells the browser to use modern HTML5 rules -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- viewport meta tag makes site responsive on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 4 - PHP Forms, Cookies & Sessions</title>
    <!-- Link to external Bootstrap CSS (CDN = Content Delivery Network) -->
    <!-- THEORY: Bootstrap is a CSS framework with pre-built UI components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- THEORY: Bootstrap uses a 12-column grid system
     container = fixed width centered wrapper
     row = horizontal group of columns
     col-md-6 = takes 6 of 12 columns on medium+ screens -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- Card = Bootstrap component for a bordered box with shadow -->
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>🔐 User Registration / Login</h3>
                    <small>Lab 4: HTML Forms, Cookies & Sessions</small>
                </div>
                <div class="card-body p-4">

                    <!-- ============================================
                    THEORY: HTML FORM ELEMENT
                    action = where form data is sent (form_handler.php)
                    method = how data is sent (POST hides data in body,
                             GET appends data to URL like ?name=John)
                    ============================================ -->
                    <form action="form_handler.php" method="POST" id="registrationForm" novalidate>

                        <!-- THEORY: Tabs let us switch between GET and POST demo -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Choose Submit Method:</label>
                            <div class="d-flex gap-2">
                                <!-- These buttons change the form's method attribute via JavaScript -->
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="setMethod('POST')">
                                    Use POST (Secure)
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="setMethod('GET')">
                                    Use GET (See URL)
                                </button>
                            </div>
                            <!-- Shows which method is currently selected -->
                            <small class="text-muted">Currently using: <strong id="methodDisplay">POST</strong></small>
                        </div>

                        <hr>

                        <!-- NAME FIELD -->
                        <!-- THEORY: label's 'for' must match input's 'id' for accessibility -->
                        <!-- value="<?= $remembered_name ?>" = PHP short echo tag to output cookie value -->
                        <div class="mb-3">
                            <label for="name" class="form-label">👤 Full Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                placeholder="Enter your full name"
                                value="<?= $remembered_name ?>"
                                required
                            >
                            <!-- This div shows validation error messages (controlled by JS) -->
                            <div class="invalid-feedback">Please enter your name.</div>
                        </div>

                        <!-- EMAIL FIELD -->
                        <!-- THEORY: type="email" gives basic browser-level email validation -->
                        <!-- But we ALSO validate server-side in PHP (never trust client alone!) -->
                        <div class="mb-3">
                            <label for="email" class="form-label">📧 Email Address</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="you@example.com"
                                required
                            >
                            <div class="invalid-feedback" id="emailError">Please enter a valid email.</div>
                        </div>

                        <!-- PASSWORD FIELD -->
                        <!-- THEORY: type="password" masks the input characters -->
                        <div class="mb-3">
                            <label for="password" class="form-label">🔑 Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Min 6 characters"
                                required
                                minlength="6"
                            >
                            <div class="invalid-feedback">Password must be at least 6 characters.</div>
                        </div>

                        <!-- REMEMBER ME CHECKBOX -->
                        <!-- THEORY: Checkbox sends value only when checked
                             If unchecked, the field is NOT sent in the form data -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" value="yes">
                            <label class="form-check-label" for="remember">
                                🍪 Remember my name (stores a Cookie)
                            </label>
                        </div>

                        <!-- HIDDEN FIELD -->
                        <!-- THEORY: Hidden inputs submit data the user cannot see or change -->
                        <!-- Useful for sending form IDs, CSRF tokens, etc. -->
                        <input type="hidden" name="form_source" value="registration_page">

                        <!-- SUBMIT BUTTON -->
                        <!-- type="submit" triggers form submission -->
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            🚀 Submit Form
                        </button>

                        <!-- Link to see GET method demo separately -->
                        <a href="get_demo.php" class="btn btn-outline-secondary w-100">
                            🔍 See GET Method Demo
                        </a>

                    </form>
                </div>

                <!-- Show cookie info if a cookie is already saved -->
                <?php if ($remembered_name): ?>
                <div class="card-footer text-center">
                    <small class="text-success">
                        🍪 Cookie found! Welcome back, <strong><?= $remembered_name ?></strong>!
                        <a href="clear_cookie.php" class="text-danger">Clear Cookie</a>
                    </small>
                </div>
                <?php endif; ?>
            </div>

            <!-- Information box below the form -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6>📚 What this lab demonstrates:</h6>
                    <ul class="small text-muted">
                        <li><strong>GET vs POST</strong>: Different ways to send form data</li>
                        <li><strong>Email Validation</strong>: Both client-side (JS) and server-side (PHP)</li>
                        <li><strong>Cookies</strong>: Storing username in browser for 30 days</li>
                        <li><strong>Sessions</strong>: Server-side login state management</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS bundle (includes Popper.js for dropdowns etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ============================================================
// THEORY: CLIENT-SIDE JAVASCRIPT VALIDATION
// This runs in the BROWSER before the form is sent to server
// Purpose: Give instant feedback to user without page reload
// But NEVER rely only on JS validation - always validate in PHP too!
// ============================================================

// Function to switch between GET and POST methods
function setMethod(method) {
    // document.getElementById() = DOM method to get element by its id attribute
    document.getElementById('registrationForm').method = method;
    // Update the display text to show current method
    document.getElementById('methodDisplay').textContent = method;
}

// Listen for form submit event
document.getElementById('registrationForm').addEventListener('submit', function(event) {
    // Get the email input element from DOM
    const emailInput = document.getElementById('email');
    const emailValue = emailInput.value; // Get the typed value

    // THEORY: Regular Expression (Regex) for email validation
    // ^ = start, $ = end, \S+ = one or more non-whitespace chars
    // @ = literal @, \. = literal dot
    const emailRegex = /^\S+@\S+\.\S+$/;

    // Test if email matches the regex pattern
    if (!emailRegex.test(emailValue)) {
        // Prevent form from submitting
        event.preventDefault();
        // Add Bootstrap's is-invalid class to show red border
        emailInput.classList.add('is-invalid');
        document.getElementById('emailError').textContent = '❌ Invalid email format!';
        return; // Stop function here
    } else {
        // Remove error styling if email is valid
        emailInput.classList.remove('is-invalid');
        emailInput.classList.add('is-valid'); // Add green border
    }

    // Bootstrap's built-in form validation
    // This checks all HTML5 'required', 'minlength' etc. attributes
    if (!this.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
    }
    // Add 'was-validated' class to show Bootstrap validation styles
    this.classList.add('was-validated');
});
</script>

</body>
</html>
