<?php
// ============================================================
// FILE: insert.php
// PURPOSE: Show form to add a new student + process form data
// THEORY: $_POST = superglobal array that holds form data
//         when HTML form uses method="POST"
//         INSERT INTO = SQL command to add a new row
// ============================================================

include('includes/db.php');

$error   = "";  // Will store validation error messages
$success = "";  // Will store success message

// -------------------------------------------------------
// Check if form was submitted
// isset() = checks if a variable or key exists
// $_POST['submit'] = the name attribute of the submit button
// -------------------------------------------------------
if (isset($_POST['submit'])) {

    // Get values from HTML form (what user typed)
    $name  = trim($_POST['name']);   // trim() removes extra spaces from start/end
    $email = trim($_POST['email']);

    // ----- VALIDATION (check inputs before inserting) -----

    // Check: name must not be empty
    if (empty($name)) {
        $error = "❌ Name is required!";
    }
    // Check: email must not be empty
    elseif (empty($email)) {
        $error = "❌ Email is required!";
    }
    // Check: email format must be valid (abc@xyz.com)
    // filter_var() is a built-in PHP function for validation
    // FILTER_VALIDATE_EMAIL checks proper email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Invalid email format!";
    }
    else {
        // ----- INSERT into database -----
        // mysqli_real_escape_string() = escapes special characters
        // This prevents SQL Injection attacks!
        $name  = mysqli_real_escape_string($conn, $name);
        $email = mysqli_real_escape_string($conn, $email);

        // SQL INSERT query
        // We only specify name, email — id is AUTO_INCREMENT
        $sql = "INSERT INTO students (name, email) VALUES ('$name', '$email')";

        if (mysqli_query($conn, $sql)) {
            // mysqli_insert_id() returns the id of the newly inserted row
            $new_id = mysqli_insert_id($conn);
            // Redirect to index with success message
            header("Location: index.php?msg=inserted");
            exit();
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-4">

    <div class="card shadow-sm" style="max-width: 600px; margin: auto;">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">➕ Add New Student</h4>
        </div>
        <div class="card-body">

            <!-- Show error message if validation fails -->
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <!--
                HTML FORM
                action="insert.php"  → send data back to this same file
                method="POST"        → use POST (not GET) so data doesn't show in URL
            -->
            <form action="insert.php" method="POST">

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Student Name</label>
                    <!--
                        name="name" → PHP reads this as $_POST['name']
                        value="..."  → keeps typed value if form reloads after error
                    -->
                    <input type="text"
                           class="form-control"
                           id="name"
                           name="name"
                           placeholder="Enter full name"
                           value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>"
                           required>
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email Address</label>
                    <input type="email"
                           class="form-control"
                           id="email"
                           name="email"
                           placeholder="Enter email address"
                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                           required>
                </div>

                <!-- Submit Button -->
                <!-- name="submit" → allows PHP to detect form submission via isset($_POST['submit']) -->
                <button type="submit" name="submit" class="btn btn-success w-100">💾 Save Student</button>
            </form>

        </div>
        <div class="card-footer">
            <a href="index.php" class="btn btn-secondary btn-sm">← Back to List</a>
        </div>
    </div>

    <!-- Theory Box -->
    <div class="card mt-4 border-warning" style="max-width:600px;margin:auto;">
        <div class="card-header bg-warning">📖 SQL Used Here</div>
        <div class="card-body small">
            <code>INSERT INTO students (name, email) VALUES ('Alice', 'alice@mail.com');</code>
        </div>
    </div>

</div>
</body>
</html>
<?php mysqli_close($conn); ?>
