<?php
// ============================================================
// register.php — User Registration Page
// Concepts: PHP form handling, password hashing, PDO INSERT
// ============================================================
require_once 'includes/db.php';    // get $pdo database connection
require_once 'includes/auth.php';  // get session helpers

$error   = '';  // will hold error message if any
$success = '';  // will hold success message if any

// PHP processes form data when HTTP method is POST
// $_POST is a superglobal array containing submitted form values
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ── 1. Collect and sanitize inputs ──
    // trim() removes whitespace from start/end
    // htmlspecialchars() converts <, >, & to HTML entities — prevents XSS attacks
    $name     = trim(htmlspecialchars($_POST['name']));
    $email    = trim(htmlspecialchars($_POST['email']));
    $password = $_POST['password'];  // don't htmlspecialchars passwords
    $confirm  = $_POST['confirm'];

    // ── 2. Server-side validation ──
    // Always validate on server even if you validate on client (JS can be bypassed)
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // FILTER_VALIDATE_EMAIL is a built-in PHP filter for email validation
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // ── 3. Check if email already exists ──
        // Prepared statements: use ? placeholders to prevent SQL Injection
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);     // bind value to ? placeholder safely
        
        if ($stmt->fetch()) {
            $error = "An account with this email already exists.";
        } else {
            // ── 4. Hash password before storing ──
            // NEVER store plain-text passwords!
            // password_hash() uses bcrypt algorithm by default
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            
            // ── 5. Insert new user into database ──
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed]);
            
            $success = "Registration successful! You can now log in.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Viewport meta tag: makes page responsive on mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Complaint System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-box">
        <h1>📝 Register</h1>
        <p class="subtitle">Complaint Management System</p>

        <!-- PHP echo: outputs variable values into HTML -->
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <!-- HTML Form: method="post" sends data to same page (action="") -->
        <form method="post" action="">
            <div class="form-group">
                <label for="name">Full Name</label>
                <!-- value preserves typed text if form re-submits with error -->
                <input type="text" id="name" name="name"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                       placeholder="Your full name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="you@example.com" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <!-- type="password" hides characters as user types -->
                <input type="password" id="password" name="password"
                       placeholder="Min. 6 characters" required>
            </div>
            <div class="form-group">
                <label for="confirm">Confirm Password</label>
                <input type="password" id="confirm" name="confirm"
                       placeholder="Repeat password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Create Account</button>
        </form>

        <div class="link-row">
            Already have an account? <a href="login.php">Log In</a>
        </div>
    </div>
</div>
</body>
</html>
