<?php
// ============================================================
// FILE: student_login.php
// PURPOSE: Login page for students
// THEORY: PHP sessions allow data to persist across pages.
//         session_start() must be called at the TOP of every
//         page that uses sessions — before any HTML output.
// ============================================================

session_start(); // Start a session to store login data across pages

// If student is already logged in, redirect them to complaint page
if (isset($_SESSION['student_id'])) {
    header("Location: complaint.php"); // header() sends HTTP redirect
    exit(); // Always call exit() after header redirect
}

include 'db.php'; // Include database connection

$error = ""; // Variable to hold any error message to show user

// ---- Handle form submission ----
// $_SERVER['REQUEST_METHOD'] tells us how the page was accessed
// 'POST' means the form was submitted, 'GET' means just visiting the page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // $_POST[] gets data submitted via HTML form with method="post"
    // THEORY: POST data is sent in HTTP request body (not in URL)
    //         GET data appears in URL — not safe for passwords!
    
    $email    = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);
    // mysqli_real_escape_string() escapes special characters to prevent SQL Injection
    // trim() removes leading/trailing whitespace
    // SECURITY: Never use raw $_POST data directly in SQL queries!

    // SQL query to find student with this email
    $sql    = "SELECT * FROM students WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    // mysqli_query() runs a SQL statement and returns a result object

    if (mysqli_num_rows($result) == 1) {
        // mysqli_num_rows() counts how many rows the query returned
        // We expect exactly 1 row (unique email)
        
        $student = mysqli_fetch_assoc($result);
        // mysqli_fetch_assoc() returns the row as an associative array
        // e.g., $student['email'], $student['name'], etc.

        // password_verify() checks if plain password matches the stored hash
        // SECURITY: Passwords are NEVER stored as plain text — always hashed!
        if (password_verify($password, $student['password'])) {
            // Login successful — store student info in SESSION
            $_SESSION['student_id']   = $student['id'];
            $_SESSION['student_name'] = $student['name'];
            // Sessions store data on the SERVER, linked to user via a cookie
            
            header("Location: complaint.php"); // Redirect to complaint page
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>
<!DOCTYPE html>
<!-- THEORY: DOCTYPE tells browser this is HTML5 document -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- charset=UTF-8 supports all characters including special symbols -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- viewport meta tag makes page responsive on mobile devices -->
    <title>Student Login - Complaint System</title>
    <!-- Bootstrap CSS from CDN (Content Delivery Network) -->
    <!-- Bootstrap is a CSS framework with pre-built classes for responsive design -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <!-- Bootstrap card component — gives a white box with shadow -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">🎓 Student Login</h3>
                    <p class="mb-0 small">Complaint Registration System</p>
                </div>
                <div class="card-body p-4">

                    <!-- Show error message if login failed -->
                    <?php if ($error): ?>
                        <!-- PHP if inside HTML — outputs only if $error is not empty -->
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <!-- HTML Form — action="" means submit to same page, method="post" -->
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <!-- type="email" validates email format in browser -->
                            <input type="email" name="email" class="form-control" 
                                   placeholder="your@college.edu" required>
                            <!-- required = HTML5 form validation (won't submit if empty) -->
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <!-- type="password" hides the typed characters -->
                            <input type="password" name="password" class="form-control" 
                                   placeholder="Enter your password" required>
                        </div>
                        <!-- type="submit" sends the form data -->
                        <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                    </form>

                    <hr>
                    <p class="text-center mb-0">
                        Don't have an account? 
                        <a href="student_register.php">Register here</a>
                    </p>
                </div>
            </div>
            <!-- Link to admin login -->
            <p class="text-center mt-3">
                <a href="admin_login.php" class="text-secondary">Admin Login →</a>
            </p>
        </div>
    </div>
</div>

<!-- Bootstrap JS (needed for interactive components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
