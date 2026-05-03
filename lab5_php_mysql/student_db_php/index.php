<?php
// ============================================================
// FILE: index.php
// PURPOSE: Display all students + Delete functionality
// THEORY: SELECT query fetches data from the database
//         DELETE query removes a record by its id
// ============================================================

// include() imports another PHP file into this one
// We get $conn (database connection) from db.php
include('includes/db.php');

// -------------------------------------------------------
// HANDLE DELETE (when user clicks "Delete" button)
// $_GET['action'] reads the 'action' parameter from URL
// Example URL: index.php?action=delete&id=3
// -------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'delete') {

    // Get the student id from the URL
    // (int) converts it to integer to prevent SQL injection
    $id = (int)$_GET['id'];

    // SQL: DELETE removes a row WHERE id matches
    $sql_delete = "DELETE FROM students WHERE id = $id";

    if (mysqli_query($conn, $sql_delete)) {
        // header() redirects browser to another page
        header("Location: index.php?msg=deleted");
        exit(); // stop running this script after redirect
    } else {
        echo "❌ Error deleting: " . mysqli_error($conn);
    }
}

// -------------------------------------------------------
// FETCH ALL STUDENTS using SELECT query
// SELECT * = select ALL columns
// FROM students = from the students table
// -------------------------------------------------------
$sql_select = "SELECT * FROM students ORDER BY id ASC";

// mysqli_query() sends the SQL to MySQL and returns a result
$result = mysqli_query($conn, $sql_select);

// mysqli_num_rows() counts how many rows were returned
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Database Manager</title>
    <!-- Bootstrap 5 CDN: pre-built CSS classes for beautiful UI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-4">

    <!-- Page Header -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">🎓 Student Database Manager</h3>
            <span class="badge bg-light text-primary fs-6">Total: <?= $total ?> Students</span>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] == 'inserted'): ?>
            <div class="alert alert-success">✅ Student added successfully!</div>
        <?php elseif ($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-info">✏️ Student updated successfully!</div>
        <?php elseif ($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-danger">🗑️ Student deleted successfully!</div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="mb-3">
        <a href="insert.php" class="btn btn-success">➕ Add New Student</a>
    </div>

    <!-- Students Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // -------------------------------------------------------
                    // DISPLAY RECORDS: Loop through each row from SELECT result
                    // mysqli_fetch_assoc() returns one row as an associative array
                    // ['id'], ['name'], ['email'] match our column names
                    // -------------------------------------------------------
                    if ($total > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";   // htmlspecialchars prevents XSS attacks
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>
                                    <a href='update.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>✏️ Edit</a>
                                    &nbsp;
                                    <a href='index.php?action=delete&id=" . $row['id'] . "'
                                       class='btn btn-danger btn-sm'
                                       onclick=\"return confirm('Are you sure you want to delete this student?')\">
                                       🗑️ Delete
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center text-muted'>No students found. <a href='insert.php'>Add one!</a></td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Theory Footer -->
    <div class="card mt-4 border-info">
        <div class="card-header bg-info text-white">📖 SQL Operations Used on This Page</div>
        <div class="card-body small">
            <code>SELECT * FROM students ORDER BY id ASC;</code> → Fetch all records<br>
            <code>DELETE FROM students WHERE id = ?;</code> → Remove a specific student
        </div>
    </div>

</div>
</body>
</html>
<?php
// Always close the database connection at end of page
mysqli_close($conn);
?>
