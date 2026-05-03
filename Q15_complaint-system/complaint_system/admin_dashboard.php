<?php
// ============================================================
// FILE: admin_dashboard.php
// PURPOSE: Admin sees ALL complaints with student details
//          Admin can update complaint status
// THEORY: This page uses SQL JOINs to combine data from two
//         tables: complaints + students
// ============================================================

session_start();

// Protect this page — only admins can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$admin_name = $_SESSION['admin_name'];
$success    = "";

// ---- Handle status update ----
// Admin can change complaint status to: Pending / In Progress / Resolved
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    
    $complaint_id = (int)$_POST['complaint_id'];
    // (int) casting forces the value to be an integer
    // SECURITY: Prevents SQL Injection for numeric values
    
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    $update_sql = "UPDATE complaints SET status = '$new_status' WHERE id = '$complaint_id'";
    // UPDATE query modifies existing data in database
    // SET: which column to change and what new value
    // WHERE: which row(s) to update (ALWAYS use WHERE or ALL rows update!)
    
    if (mysqli_query($conn, $update_sql)) {
        $success = "Status updated successfully!";
    }
}

// ---- Fetch ALL complaints with student information ----
// THEORY: SQL JOIN combines rows from two tables based on a related column
// INNER JOIN complaints (c) with students (s) where c.student_id = s.id
// This gives us student name, roll_no ALONG WITH complaint details
$sql = "SELECT c.*, s.name AS student_name, s.roll_no, s.email 
        FROM complaints c 
        INNER JOIN students s ON c.student_id = s.id 
        ORDER BY c.submitted_at DESC";
// c.* = all columns from complaints table
// s.name AS student_name = rename to avoid confusion
// INNER JOIN = only returns rows that have matching records in BOTH tables
// ORDER BY submitted_at DESC = newest complaints first

$result = mysqli_query($conn, $sql);

// Count statistics for summary cards
$total_sql      = "SELECT COUNT(*) as total FROM complaints";
$pending_sql    = "SELECT COUNT(*) as cnt FROM complaints WHERE status='Pending'";
$progress_sql   = "SELECT COUNT(*) as cnt FROM complaints WHERE status='In Progress'";
$resolved_sql   = "SELECT COUNT(*) as cnt FROM complaints WHERE status='Resolved'";

// COUNT(*) counts total rows matching the condition
$total    = mysqli_fetch_assoc(mysqli_query($conn, $total_sql))['total'];
$pending  = mysqli_fetch_assoc(mysqli_query($conn, $pending_sql))['cnt'];
$progress = mysqli_fetch_assoc(mysqli_query($conn, $progress_sql))['cnt'];
$resolved = mysqli_fetch_assoc(mysqli_query($conn, $resolved_sql))['cnt'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
        .stat-card { border-left: 5px solid; }
    </style>
</head>
<body class="bg-light">

<!-- Admin Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🔐 Admin Panel</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-white">Logged in: <?php echo htmlspecialchars($admin_name); ?></span>
            <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4 px-4">

    <h2 class="mb-4">📊 Complaint Management Dashboard</h2>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- STATISTICS SUMMARY CARDS -->
    <!-- Bootstrap Grid: row with 4 equal columns -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card stat-card border-primary shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Complaints</h6>
                    <!-- PHP echo inside HTML outputs the variable's value -->
                    <h2 class="text-primary fw-bold"><?php echo $total; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-warning shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Pending</h6>
                    <h2 class="text-warning fw-bold"><?php echo $pending; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">In Progress</h6>
                    <h2 class="text-info fw-bold"><?php echo $progress; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-success shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Resolved</h6>
                    <h2 class="text-success fw-bold"><?php echo $resolved; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- ALL COMPLAINTS TABLE -->
    <div class="card shadow border-0">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">📋 All Complaints</h5>
        </div>
        <div class="card-body p-0">
            <!-- table-responsive makes table scroll horizontally on small screens -->
            <div class="table-responsive">
                <!-- Bootstrap table classes: table-striped = alternate row colors -->
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Roll No</th>
                            <th>Category</th>
                            <th>Subject</th>
                            <th>Description</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) == 0): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted p-4">
                                    No complaints filed yet.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $sr = 1; // Serial number counter
                            while ($row = mysqli_fetch_assoc($result)): 
                            ?>
                                <tr>
                                    <td><?php echo $sr++; ?></td>
                                    <!-- $sr++ uses value then increments -->
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['student_name']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo htmlspecialchars($row['category']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                    <td>
                                        <!-- Show only first 80 characters of description -->
                                        <!-- substr() extracts part of a string -->
                                        <small><?php echo htmlspecialchars(substr($row['description'], 0, 80)); ?>
                                        <?php if (strlen($row['description']) > 80) echo "..."; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small><?php echo date('d M Y', strtotime($row['submitted_at'])); ?></small>
                                    </td>
                                    <td>
                                        <!-- Conditional badge color based on status -->
                                        <?php
                                            $badge = 'warning';
                                            if ($row['status'] == 'In Progress') $badge = 'info';
                                            if ($row['status'] == 'Resolved')    $badge = 'success';
                                        ?>
                                        <span class="badge bg-<?php echo $badge; ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Inline form for status update -->
                                        <!-- Each row has its own mini-form to update that complaint -->
                                        <form method="POST" style="min-width:150px">
                                            <!-- hidden input sends complaint ID without showing it -->
                                            <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="update_status" value="1">
                                            <select name="status" class="form-select form-select-sm mb-1" required>
                                                <option value="Pending"     <?php if($row['status']=='Pending')    echo 'selected'; ?>>Pending</option>
                                                <option value="In Progress" <?php if($row['status']=='In Progress') echo 'selected'; ?>>In Progress</option>
                                                <option value="Resolved"    <?php if($row['status']=='Resolved')   echo 'selected'; ?>>Resolved</option>
                                                <!-- selected attribute pre-selects the current status -->
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-danger w-100">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
