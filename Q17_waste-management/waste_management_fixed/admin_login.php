<?php
// ============================================================
// FILE: admin_login.php
// PURPOSE: Secure login page for administrators
//
// THEORY:
//   - PHP Sessions: used to maintain login state across pages
//   - password_verify(): safely checks bcrypt-hashed password
//   - POST method: credentials never appear in URL
//   - header("Location:..."): HTTP redirect after login
// ============================================================

session_start(); // Start session before any output

// If admin is already logged in, go straight to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

include 'db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
    // NOTE: Do NOT escape the password — we compare via password_verify()

    // Query admins table for matching username
    $sql    = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);

        // password_verify(plain, hash):
        //   - Compares the entered plain-text password against the bcrypt hash
        //   - NEVER store plain passwords — always use hash
        //   - bcrypt is slow by design — makes brute-force attacks hard
        if (password_verify($password, $admin['password'])) {

            // Login success — store admin info in session
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['fullname'];
            $_SESSION['admin_user'] = $admin['username'];
            // Sessions persist data on the SERVER
            // Browser gets a cookie (PHPSESSID) linking it to this session

            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "No admin found with that username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — SwachhCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="admin-body">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-4 col-sm-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Admin card header — red theme to distinguish from public pages -->
                <div class="card-header text-center py-4"
                     style="background: linear-gradient(135deg,#c0392b,#e74c3c);">
                    <div class="fs-1">🔐</div>
                    <h4 class="text-white fw-bold mb-1">Admin Login</h4>
                    <p class="text-white opacity-75 mb-0 small">Waste Management Control Panel</p>
                </div>

                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-x-octagon-fill me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <!-- input-group: Bootstrap component that attaches icon to input -->
                                <input type="text" name="username" class="form-control form-control-lg"
                                       placeholder="admin" required autocomplete="username">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="password" class="form-control form-control-lg"
                                       placeholder="••••••••" required autocomplete="current-password">
                                <!-- type="password": hides characters as dots -->
                            </div>
                        </div>

                        <button type="submit"
                                class="btn btn-danger w-100 btn-lg fw-bold">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login to Admin Panel
                        </button>
                    </form>

                    <!-- Default credentials hint (remove in production!) -->
                    <div class="alert alert-info mt-3 small mb-0">
                        <strong>Demo Credentials:</strong><br>
                        Username: <code>admin</code> &nbsp;|&nbsp; Password: <code>admin123</code>
                    </div>
                </div>

                <div class="card-footer text-center bg-light py-3">
                    <a href="report.php" class="text-decoration-none text-muted small">
                        ← Back to Public Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
