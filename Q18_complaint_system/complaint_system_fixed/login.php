<?php
// ============================================================
// login.php — User Login Page
// Concepts: Sessions, password_verify(), HTTP redirects
// ============================================================
require_once 'includes/db.php';
require_once 'includes/auth.php';

// If already logged in, go straight to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // ── STEP 1: Check if email exists in ADMINS table first ──
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? AND is_active = TRUE");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            // ✅ ADMIN LOGIN SUCCESS
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_email']    = $admin['email'];
            $_SESSION['admin_role']     = $admin['role'];
            $_SESSION['is_admin']       = true;
            
            // Update last login time
            $update = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
            $update->execute([$admin['id']]);
            
            // Redirect to admin dashboard
            header("Location: admin.php");
            exit();
        }

        // ── STEP 2: If not admin, check USERS table ──
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // ✅ USER LOGIN SUCCESS
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_admin']  = false;
            
            // Redirect to user dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // ❌ LOGIN FAILED - Don't reveal whether email or password was wrong
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Complaint System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-box">
        <h1>🔐 Login</h1>
        <p class="subtitle">Complaint Management System</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="you@example.com" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Your password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Log In</button>
        </form>

        <div class="link-row">
            New here? <a href="register.php">Create an account</a>
        </div>
    </div>
</div>
</body>
</html>
