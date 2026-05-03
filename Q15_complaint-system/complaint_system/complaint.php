<?php
// ============================================================
// FILE: complaint.php
// PURPOSE: Students submit complaints and view their own complaints
// THEORY: This page is PROTECTED — only logged-in students can access it.
//         We check the session to authenticate the user.
// ============================================================

session_start();

// AUTHENTICATION CHECK — if not logged in, redirect to login page
// isset() checks if a variable exists AND is not null
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

include 'db.php';

// Get student info from session
// Sessions persist data across page requests for the same browser
$student_id   = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];

$success = "";
$error   = "";

// ---- Handle complaint form submission ----
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $category    = mysqli_real_escape_string($conn, trim($_POST['category']));
    $subject     = mysqli_real_escape_string($conn, trim($_POST['subject']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));

    // Validate: make sure nothing is empty
    if (empty($category) || empty($subject) || empty($description)) {
        $error = "All fields are required.";
    } else {
        // INSERT complaint linked to the logged-in student
        $sql = "INSERT INTO complaints (student_id, category, subject, description) 
                VALUES ('$student_id', '$category', '$subject', '$description')";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Complaint submitted successfully!";
        } else {
            $error = "Failed to submit: " . mysqli_error($conn);
        }
    }
}

// ---- Fetch all complaints by this student ----
// ORDER BY submitted_at DESC = newest first
$complaints_sql    = "SELECT * FROM complaints WHERE student_id = '$student_id' ORDER BY submitted_at DESC";
$complaints_result = mysqli_query($conn, $complaints_sql);
// The query returns all complaints filed by this student only
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Complaint</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* CSS within HTML file using <style> tag — Internal CSS */
        .status-pending    { background-color: #fff3cd; color: #856404; }
        .status-inprogress { background-color: #cff4fc; color: #055160; }
        .status-resolved   { background-color: #d1e7dd; color: #0a3622; }
    </style>
</head>
<body class="bg-light">

<!-- Navigation Bar — Bootstrap navbar component -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">🎓 College Complaints</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <!-- PHP echoes student name from session -->
            <span class="text-white">Welcome, <?php echo htmlspecialchars($student_name); ?>!</span>
            <!-- htmlspecialchars() converts special chars to HTML entities
                 Prevents XSS (Cross-Site Scripting) attacks
                 e.g., <script> becomes &lt;script&gt; -->
            <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">

        <!-- LEFT COLUMN: Complaint Form -->
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📝 File a New Complaint</h5>
                </div>
                <div class="card-body">

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">

                        <!-- Dropdown (SELECT) for complaint category -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category" class="form-select" required>
                                <!-- <select> creates a dropdown menu -->
                                <!-- value="" option prevents empty selection -->
                                <option value="" disabled selected>-- Select Category --</option>
                                <option value="Library">Library</option>
                                <option value="Hostel">Hostel</option>
                                <option value="Canteen">Canteen</option>
                                <option value="Faculty">Faculty / Teaching</option>
                                <option value="Infrastructure">Infrastructure</option>
                                <option value="Fees">Fees / Accounts</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Subject</label>
                            <input type="text" name="subject" class="form-control" 
                                   placeholder="Brief title of your complaint" 
                                   maxlength="200" required>
                            <!-- maxlength limits characters to match DB column size -->
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <!-- <textarea> allows multi-line text input -->
                            <!-- rows="5" sets visible height -->
                            <textarea name="description" class="form-control" rows="5"
                                      placeholder="Describe your complaint in detail..." 
                                      required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Submit Complaint</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Student's Complaint History -->
        <div class="col-md-7">
            <div class="card shadow border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">📋 My Complaints</h5>
                </div>
                <div class="card-body p-0">

                    <?php if (mysqli_num_rows($complaints_result) == 0): ?>
                        <!-- Show message if student has no complaints yet -->
                        <div class="text-center p-4 text-muted">
                            <p>You haven't filed any complaints yet.</p>
                        </div>
                    <?php else: ?>
                        <!-- Loop through each complaint row -->
                        <!-- mysqli_fetch_assoc() fetches one row at a time as array -->
                        <!-- while loop continues until all rows are fetched (returns false) -->
                        <?php while ($row = mysqli_fetch_assoc($complaints_result)): ?>
                            <?php
                                // Determine CSS class based on status
                                $statusClass = 'status-pending';
                                if ($row['status'] == 'In Progress') $statusClass = 'status-inprogress';
                                if ($row['status'] == 'Resolved')    $statusClass = 'status-resolved';
                            ?>
                            <div class="complaint-card border-bottom p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <!-- htmlspecialchars prevents XSS when echoing DB data -->
                                        <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($row['subject']); ?></h6>
                                        <span class="badge bg-secondary"><?php echo $row['category']; ?></span>
                                    </div>
                                    <span class="badge <?php echo $statusClass; ?> px-3 py-2">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </div>
                                <p class="mt-2 mb-1 text-muted small"><?php echo htmlspecialchars($row['description']); ?></p>
                                <small class="text-muted">
                                    <!-- date() formats a date string, strtotime() converts DB timestamp -->
                                    Filed on: <?php echo date('d M Y, h:i A', strtotime($row['submitted_at'])); ?>
                                </small>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
