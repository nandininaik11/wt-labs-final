<?php
// ============================================================
// FILE: admin_login.php
// PURPOSE: Separate login page for administrators
// THEORY: Admins are stored in a separate table from students.
//         We use a different session variable to distinguish
//         student sessions from admin sessions.
// ============================================================

session_start();

// If admin already logged in, go to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Sanitize input before using in SQL
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    // Query the admins table
    $sql    = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);

        // Verify password using password_verify()
        // This compares plain text with bcrypt hash stored in DB
        if (password_verify($password, $admin['password'])) {
            // Set admin session — different key from student session
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];
            
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin account not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Complaint System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-danger text-white text-center py-4">
                    <h3 class="mb-0">🔐 Admin Login</h3>
                    <p class="mb-0 small">Complaint Management Panel</p>
                </div>
                <div class="card-body p-4">

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <!-- Admin login form -->
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Admin Username</label>
                            <input type="text" name="username" class="form-control" 
                                   placeholder="Enter admin username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" 
                                   placeholder="Enter admin password" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2">Login as Admin</button>
                    </form>

                    <!-- Default credentials hint (remove in production!) -->
                    <div class="alert alert-info mt-3 small">
                        <strong>Default Credentials:</strong><br>
                        Username: <code>admin</code><br>
                        Password: <code>admin123</code>
                    </div>

                    <hr>
                    <p class="text-center mb-0">
                        <a href="student_login.php">← Back to Student Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
