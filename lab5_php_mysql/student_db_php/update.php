<?php
// ============================================================
// FILE: update.php
// PURPOSE: Edit existing student record in database
// THEORY: UPDATE = SQL command to change existing row data
//         WHERE clause = ensures only the right row is changed
//         First we SELECT (fetch) the current data to pre-fill form,
//         then after submit we UPDATE the row.
// ============================================================

include('includes/db.php');

$error = "";

// -------------------------------------------------------
// Step 1: Get the student 'id' from URL parameter
// URL example: update.php?id=2
// (int) = cast to integer, prevents SQL injection
// -------------------------------------------------------
$id = (int)$_GET['id'];

// -------------------------------------------------------
// Step 2: Fetch current data for this student
// We SELECT WHERE id matches so form shows existing values
// -------------------------------------------------------
$sql_fetch = "SELECT * FROM students WHERE id = $id";
$result    = mysqli_query($conn, $sql_fetch);
$student   = mysqli_fetch_assoc($result); // Returns one row as array

// If student not found (wrong id in URL)
if (!$student) {
    die("❌ Student not found! <a href='index.php'>Go Back</a>");
}

// -------------------------------------------------------
// Step 3: Handle form submission (UPDATE)
// -------------------------------------------------------
if (isset($_POST['submit'])) {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Validation
    if (empty($name)) {
        $error = "❌ Name is required!";
    } elseif (empty($email)) {
        $error = "❌ Email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Invalid email format!";
    } else {
        // Escape to prevent SQL injection
        $name  = mysqli_real_escape_string($conn, $name);
        $email = mysqli_real_escape_string($conn, $email);

        // -------------------------------------------------------
        // SQL UPDATE query:
        // SET   = what values to change
        // WHERE = which row to change (VERY IMPORTANT!)
        //         Without WHERE, ALL rows would be updated!
        // -------------------------------------------------------
        $sql_update = "UPDATE students SET name='$name', email='$email' WHERE id=$id";

        if (mysqli_query($conn, $sql_update)) {
            // mysqli_affected_rows() = how many rows were changed
            $affected = mysqli_affected_rows($conn);
            header("Location: index.php?msg=updated");
            exit();
        } else {
            $error = "❌ Update failed: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-4">

    <div class="card shadow-sm" style="max-width:600px; margin:auto;">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">✏️ Edit Student (ID: <?= $id ?>)</h4>
        </div>
        <div class="card-body">

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <!--
                FORM pre-filled with existing student data
                value="..." shows current database values so user can see and edit them
            -->
            <form action="update.php?id=<?= $id ?>" method="POST">

                <div class="mb-3">
                    <label class="form-label fw-bold">Student Name</label>
                    <!--
                        We show the POST value if form was submitted (after error)
                        Otherwise we show the database value ($student['name'])
                    -->
                    <input type="text"
                           class="form-control"
                           name="name"
                           value="<?= htmlspecialchars(isset($_POST['name']) ? $_POST['name'] : $student['name']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email"
                           class="form-control"
                           name="email"
                           value="<?= htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : $student['email']) ?>"
                           required>
                </div>

                <button type="submit" name="submit" class="btn btn-warning w-100">🔄 Update Student</button>
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
            <code>UPDATE students SET name='Bob', email='bob@mail.com' WHERE id=2;</code><br>
            <small class="text-danger">⚠️ Without WHERE clause, ALL rows would be updated!</small>
        </div>
    </div>

</div>
</body>
</html>
<?php mysqli_close($conn); ?>
