<?php
/* ============================================================
   index.php  –  Student Records List (Main Page)
   Lab Q21: Responsive website showing EDIT and DELETE

   THEORY (Unit III – PHP + MySQL):
   This page demonstrates:
   1. SELECT query to fetch all students from database
   2. Display in HTML table
   3. Edit button → links to edit.php with student ID
   4. Delete button → shows confirmation modal → sends to delete.php
   ============================================================ */

// Start session FIRST (needed for flash messages)
session_start();

// Include database connection and helper functions
require_once __DIR__ . '/includes/config.php';

// Include layout (navbar, sidebar etc.)
require_once __DIR__ . '/includes/layout.php';

// ── FETCH ALL STUDENTS FROM DATABASE ─────────────────────────
// SQL: SELECT = read data, * = all columns, ORDER BY roll_no = sorted
$sql    = "SELECT * FROM students ORDER BY roll_no ASC";
$result = $conn->query($sql);
// $result is a MySQLi result object

// fetch_all(MYSQLI_ASSOC) = returns ALL rows as array of associative arrays
// MYSQLI_ASSOC = each row is ['column_name' => 'value'] format
$students = $result->fetch_all(MYSQLI_ASSOC);

// ── COUNT STATISTICS FOR DASHBOARD ───────────────────────────
// Total students
$totalStudents = count($students); // PHP count() returns array length

// Count students by department using SQL aggregate
$deptStats = $conn->query(
    "SELECT department, COUNT(*) as count
     FROM students
     GROUP BY department
     ORDER BY count DESC
     LIMIT 1" // Only get the top department
)->fetch_assoc();

// Calculate average CGPA using SQL AVG() function
$avgCgpa = $conn->query(
    "SELECT ROUND(AVG(cgpa), 2) as avg_cgpa FROM students"
)->fetch_assoc()['avg_cgpa'];

// Output the HTML page
pageHeader('Student Records', 'students');
?>

<!-- ══════════════════════════════════════════════════════════
     PAGE HEADER SECTION
     Title + breadcrumb + Add Student button
     ══════════════════════════════════════════════════════════ -->
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">
            <i class="bi bi-people-fill text-primary me-2"></i>Student Records
        </h1>
        <!-- Breadcrumb: shows where user is in the site (Unit I: nav) -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:.82rem">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Students</li>
            </ol>
        </nav>
    </div>
    <!-- Add Student button -->
    <a href="add.php" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-person-plus-fill"></i>
        <span>Add Student</span>
    </a>
</div>

<!-- Flash message area (success/error notifications) -->
<?= showFlash() ?>

<!-- ══════════════════════════════════════════════════════════
     STATISTICS CARDS
     3 summary cards showing totals
     Bootstrap Grid: row + col-md-4 = 3 equal columns on medium screens
     ══════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    <!-- Total Students Card -->
    <div class="col-sm-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe">👥</div>
            <div>
                <!-- PHP echo: print the variable value into HTML -->
                <div class="stat-num"><?= $totalStudents ?></div>
                <div class="stat-lbl">Total Students</div>
            </div>
        </div>
    </div>

    <!-- Average CGPA Card -->
    <div class="col-sm-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7">📊</div>
            <div>
                <div class="stat-num"><?= $avgCgpa ?? 'N/A' ?></div>
                <!-- ?? = null coalescing: use 'N/A' if $avgCgpa is null -->
                <div class="stat-lbl">Average CGPA</div>
            </div>
        </div>
    </div>

    <!-- Top Department Card -->
    <div class="col-sm-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7">🏆</div>
            <div>
                <div class="stat-num" style="font-size:1.1rem">
                    <?= htmlspecialchars($deptStats['department'] ?? 'N/A') ?>
                </div>
                <div class="stat-lbl">Top Department (<?= $deptStats['count'] ?? 0 ?> students)</div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     STUDENT RECORDS TABLE
     Shows all students with Edit and Delete buttons
     ══════════════════════════════════════════════════════════ -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span>
            <i class="bi bi-table me-2"></i>
            All Students
            <!-- Badge showing total count -->
            <span class="badge bg-primary ms-1" id="resultCount">
                <?= $totalStudents ?> student(s) found
            </span>
        </span>

        <!-- SEARCH BOX (Unit II: JavaScript live filtering) -->
        <!-- As user types, JavaScript hides non-matching rows -->
        <div class="search-box" style="width:280px">
            <i class="bi bi-search search-icon"></i>
            <input type="text"
                   id="searchInput"
                   class="form-control"
                   placeholder="Search by name, roll no, dept…">
        </div>
    </div>

    <!-- Responsive wrapper: adds horizontal scroll on small screens -->
    <div class="table-responsive">
        <?php if (empty($students)): ?>
            <!-- Show message if no students found -->
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                No student records found.
                <a href="add.php">Add the first student</a>
            </div>
        <?php else: ?>

        <!-- ── STUDENT TABLE ──────────────────────────────────
             HTML5 Table (Unit I Syllabus):
             <table> container
             <thead> header row
             <tbody> data rows
             <th> header cell, <td> data cell
             data-label attribute used for mobile responsive display
             ──────────────────────────────────────────────────── -->
        <table class="student-table" id="studentTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Year</th>
                    <th>CGPA</th>
                    <th>Phone</th>
                    <th style="min-width:140px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // PHP foreach loop: iterate over every student record
                // $i = row number (1, 2, 3...), $s = student data array
                foreach ($students as $i => $s):

                    // Determine CGPA badge color class based on value
                    // PHP ternary chaining: if ≥8 → high, elseif ≥6 → medium, else → low
                    $cgpaClass = $s['cgpa'] >= 8 ? 'cgpa-high'
                               : ($s['cgpa'] >= 6 ? 'cgpa-medium' : 'cgpa-low');
                ?>
                <tr>
                    <!-- Row number -->
                    <td data-label="#">
                        <span class="text-muted"><?= $i + 1 ?></span>
                    </td>

                    <!-- Roll Number: styled as code element -->
                    <td data-label="Roll No">
                        <code style="color:#2563eb;font-weight:700;font-size:.9rem">
                            <?= htmlspecialchars($s['roll_no']) ?>
                            <!-- htmlspecialchars prevents XSS: converts < > & to entities -->
                        </code>
                    </td>

                    <!-- Student Name -->
                    <td data-label="Name">
                        <strong><?= htmlspecialchars($s['name']) ?></strong>
                    </td>

                    <!-- Email -->
                    <td data-label="Email">
                        <!-- mailto: link opens default email app -->
                        <a href="mailto:<?= htmlspecialchars($s['email']) ?>"
                           style="color:var(--text);font-size:.85rem">
                            <?= htmlspecialchars($s['email']) ?>
                        </a>
                    </td>

                    <!-- Department Badge -->
                    <td data-label="Department">
                        <span class="dept-badge">
                            <?= htmlspecialchars($s['department']) ?>
                        </span>
                    </td>

                    <!-- Year: with ordinal suffix (1st, 2nd, etc.) -->
                    <td data-label="Year">
                        <?php
                        // Array of ordinal suffixes
                        $suffixes = ['', 'st', 'nd', 'rd', 'th'];
                        $yr = (int)$s['year'];
                        echo $yr . ($suffixes[$yr] ?? 'th') . ' Yr';
                        ?>
                    </td>

                    <!-- CGPA with colored badge -->
                    <td data-label="CGPA">
                        <span class="cgpa-badge <?= $cgpaClass ?>">
                            <?= number_format($s['cgpa'], 2) ?>
                            <!-- number_format ensures 2 decimal places: 8.7 → 8.70 -->
                        </span>
                    </td>

                    <!-- Phone (optional field) -->
                    <td data-label="Phone">
                        <?php if ($s['phone']): ?>
                            <!-- tel: link opens dialer on mobile -->
                            <a href="tel:<?= htmlspecialchars($s['phone']) ?>"
                               style="color:var(--text);font-size:.85rem">
                                <?= htmlspecialchars($s['phone']) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>

                    <!-- ── ACTION BUTTONS ─────────────────────────────────
                         EDIT: Links to edit.php?id=N with student's ID
                         DELETE: Triggers JavaScript modal for confirmation
                         ──────────────────────────────────────────────────── -->
                    <td data-label="Actions">
                        <div class="d-flex gap-2 align-items-center">

                            <!-- EDIT BUTTON -->
                            <!-- Links to edit.php, passing student ID in URL query string -->
                            <a href="edit.php?id=<?= $s['id'] ?>"
                               class="btn-edit">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </a>

                            <!-- DELETE BUTTON -->
                            <!-- onclick: calls JavaScript function to show confirmation modal -->
                            <!-- passes student ID and name to the modal -->
                            <button class="btn-delete"
                                    onclick="confirmDelete(<?= $s['id'] ?>, '<?= htmlspecialchars(addslashes($s['name'])) ?>')">
                                <i class="bi bi-trash3-fill"></i> Delete
                            </button>
                            <!-- addslashes() escapes quotes in name for safe JS string -->

                        </div>
                    </td>
                </tr>
                <?php endforeach; // End of foreach loop ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div><!-- /table-responsive -->
</div><!-- /card -->

<!-- ══════════════════════════════════════════════════════════
     DELETE CONFIRMATION MODAL
     (Unit II: JavaScript DOM Manipulation)
     Shown when user clicks Delete button
     Prevents accidental deletion by asking for confirmation
     ══════════════════════════════════════════════════════════ -->

<!-- Bootstrap 5 Modal Component -->
<!-- data-bs-backdrop="static" = cannot dismiss by clicking outside -->
<div class="modal fade" id="deleteModal" tabindex="-1"
     data-bs-backdrop="static" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Body: shows student name dynamically (JS fills this in) -->
            <div class="modal-body py-4">
                <div class="text-center">
                    <div style="font-size:3rem;margin-bottom:1rem">🗑️</div>
                    <p class="mb-1">You are about to permanently delete:</p>
                    <!-- JavaScript will set this text via DOM manipulation -->
                    <h5 class="text-danger" id="deleteStudentName">student name</h5>
                    <p class="text-muted small mt-2 mb-0">
                        This action <strong>cannot be undone</strong>.
                        All records for this student will be permanently removed.
                    </p>
                </div>
            </div>

            <!-- Modal Footer: Cancel and Confirm Delete buttons -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>

                <!-- Confirm Delete: this link is updated by JavaScript -->
                <!-- href is filled in by confirmDelete() function below -->
                <a href="#" id="confirmDeleteBtn"
                   class="btn btn-danger">
                    <i class="bi bi-trash3-fill me-1"></i> Yes, Delete
                </a>
            </div>

        </div>
    </div>
</div>

<!-- ── JavaScript for Delete Modal (Unit II: DOM Manipulation) ──
     THEORY:
     - JavaScript runs in the browser (client-side)
     - DOM = Document Object Model (the HTML parsed as a tree)
     - document.getElementById() finds an element by its id attribute
     - .textContent sets the text inside an element
     - .setAttribute() changes an element's attribute
     ─────────────────────────────────────────────────────────── -->
<script>
/**
 * confirmDelete(studentId, studentName)
 * Shows the confirmation modal with correct student info
 *
 * @param {number} studentId   - Database ID of student to delete
 * @param {string} studentName - Name of student (for display)
 */
function confirmDelete(studentId, studentName) {

    // DOM Manipulation: find element by ID and update its text
    // document.getElementById('deleteStudentName') → finds <h5 id="deleteStudentName">
    document.getElementById('deleteStudentName').textContent = studentName;

    // Update the confirm button's href to point to delete.php with correct ID
    // delete.php?id=5 will delete the student with id=5
    document.getElementById('confirmDeleteBtn').setAttribute(
        'href',
        'delete.php?id=' + studentId
    );

    // Show the Bootstrap modal using the Modal API
    // new bootstrap.Modal() creates a modal controller object
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show(); // Display the modal
}
</script>

<?php
// Close the page (adds Bootstrap JS and closing HTML tags)
pageFooter();
?>
