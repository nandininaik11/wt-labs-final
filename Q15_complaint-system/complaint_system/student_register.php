<?php
// ============================================================
// FILE: student_register.php
// PURPOSE: Allows new students to create an account
// THEORY: This demonstrates PHP form handling, password hashing,
//         SQL INSERT, and input validation
// ============================================================

session_start();

// Redirect if already logged in
if (isset($_SESSION['student_id'])) {
    header("Location: complaint.php");
    exit();
}

include 'db.php';

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get and sanitize all form inputs
    $name     = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email    = mysqli_real_escape_string($conn, trim($_POST['email']));
    $roll_no  = mysqli_real_escape_string($conn, trim($_POST['roll_no']));
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    // Server-side validation
    // THEORY: Client-side (HTML/JS) validation can be bypassed.
    //         Always validate on SERVER side too for security!
    
    if (empty($name) || empty($email) || empty($roll_no) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // filter_var() with FILTER_VALIDATE_EMAIL checks proper email format
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        // strlen() counts characters — password must be at least 6 chars
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists (to prevent duplicate accounts)
        $check = mysqli_query($conn, "SELECT id FROM students WHERE email='$email' OR roll_no='$roll_no'");
        
        if (mysqli_num_rows($check) > 0) {
            $error = "Email or Roll Number already registered.";
        } else {
            // Hash the password before storing
            // SECURITY: password_hash() uses bcrypt algorithm
            // Even if database is stolen, attacker can't get plain passwords
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            // PASSWORD_DEFAULT uses the strongest available algorithm (currently bcrypt)

            // INSERT query to add new student to database
            $sql = "INSERT INTO students (name, email, password, roll_no) 
                    VALUES ('$name', '$email', '$hashed', '$roll_no')";

            if (mysqli_query($conn, $sql)) {
                // mysqli_query() returns TRUE on successful INSERT
                $success = "Registration successful! You can now login.";
            } else {
                // mysqli_error() returns the MySQL error message
                $error = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Register - Complaint System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">🎓 Student Registration</h3>
                    <p class="mb-0 small">Create your account</p>
                </div>
                <div class="card-body p-4">

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <?php echo $success; ?>
                            <a href="student_login.php" class="btn btn-sm btn-success ms-2">Login Now</a>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" 
                                   placeholder="e.g. Rahul Sharma" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" 
                                   placeholder="your@college.edu" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Roll Number</label>
                            <input type="text" name="roll_no" class="form-control" 
                                   placeholder="e.g. CS2023001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" 
                                   placeholder="Minimum 6 characters" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" 
                                   placeholder="Repeat password" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2">Register</button>
                    </form>

                    <hr>
                    <p class="text-center mb-0">
                        Already have an account? <a href="student_login.php">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
