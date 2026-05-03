<?php
/* ============================================================
   edit.php  –  Edit Student Record
   Lab Q21: Core feature — UPDATE student data in database

   THEORY (Unit III – PHP + MySQL):
   1. GET request: PHP reads ?id=N from URL, fetches student data
   2. Pre-fill the form with existing data
   3. POST request: PHP validates and runs UPDATE SQL query
   4. Redirect back to index.php with success message
   ============================================================ */

session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/layout.php';

// ── STEP 1: Get student ID from URL ──────────────────────────
// When user clicks Edit button: href="edit.php?id=3"
// PHP reads: $_GET['id'] = '3'
// (int) casts it to integer = 3 (safety: prevents "3; DROP TABLE...")
$id = (int)($_GET['id'] ?? 0);

// If no valid ID provided, redirect to home
if ($id <= 0) {
    flash("❌ Invalid student ID.", 'error');
    header('Location: index.php');
    exit;
}

// ── STEP 2: Fetch the existing student from database ──────────
// Prepared statement: safer than string concatenation
// "?" is a placeholder — actual value is bound separately
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
// bind_param("i", $id): "i" = integer type, $id = the value
$stmt->bind_param("i", $id);
$stmt->execute();
$result  = $stmt->get_result();
$student = $result->fetch_assoc(); // Returns one row as associative array
$stmt->close();

// If student not found in database, show error
if (!$student) {
    flash("❌ Student not found (ID: $id).", 'error');
    header('Location: index.php');
    exit;
}

// ── STEP 3: Initialize form data ─────────────────────────────
// Start with existing data from database
// If POST (form submitted), use POST data instead (so user's edits survive validation errors)
$form = [
    'name'       => $student['name'],
    'roll_no'    => $student['roll_no'],
    'email'      => $student['email'],
    'department' => $student['department'],
    'year'       => $student['year'],
    'cgpa'       => $student['cgpa'],
    'phone'      => $student['phone'] ?? '',
];

$errors = []; // Will hold validation error messages

// ── STEP 4: Handle form submission (POST request) ─────────────
// $_SERVER['REQUEST_METHOD'] tells us how the page was accessed
// 'GET'  = User just visited the page (load the form)
// 'POST' = User submitted the form (process the update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Read and sanitize each form field from $_POST
    // $_POST['fieldname'] contains the submitted form values
    $form = [
        'name'       => sanitize($_POST['name']       ?? ''),
        'roll_no'    => strtoupper(sanitize($_POST['roll_no'] ?? '')), // UPPERCASE
        'email'      => sanitize($_POST['email']      ?? ''),
        'department' => sanitize($_POST['department'] ?? ''),
        'year'       => (int)($_POST['year']          ?? 0),
        'cgpa'       => (float)($_POST['cgpa']        ?? 0),
        'phone'      => sanitize($_POST['phone']      ?? ''),
    ];

    // ── Server-side validation ────────────────────────────────
    $errors = validateStudent($form);

    // ── Check uniqueness (roll_no and email must be unique) ───
    // But exclude the CURRENT student (their own roll_no/email is fine)
    if (empty($errors)) {

        // Check if another student already has this roll_no
        // "id != ?" excludes the current student from the check
        $stmt = $conn->prepare(
            "SELECT id FROM students WHERE roll_no = ? AND id != ?"
        );
        $stmt->bind_param("si", $form['roll_no'], $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Roll number '{$form['roll_no']}' is already used by another student.";
        }
        $stmt->close();

        // Check if another student already has this email
        $stmt = $conn->prepare(
            "SELECT id FROM students WHERE email = ? AND id != ?"
        );
        $stmt->bind_param("si", $form['email'], $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Email '{$form['email']}' is already used by another student.";
        }
        $stmt->close();
    }

    // ── If no errors, run the UPDATE query ───────────────────
    if (empty($errors)) {

        // SQL UPDATE: modify an existing record
        // SET column = new_value
        // WHERE id = ? : VERY IMPORTANT — without WHERE, ALL rows would be updated!
        $stmt = $conn->prepare(
            "UPDATE students
             SET name       = ?,
                 roll_no    = ?,
                 email      = ?,
                 department = ?,
                 year       = ?,
                 cgpa       = ?,
                 phone      = ?
             WHERE id = ?"
        );
        // bind_param format string:
        // s = string, i = integer, d = double (float)
        // "ssssids" = name(s), roll_no(s), email(s), dept(s), year(i), cgpa(d), phone(s)
        // Last "i" = id (integer)
        $stmt->bind_param(
            "ssssidsi",   // s=string, i=int, d=double: name,roll,email,dept,year,cgpa,phone,id
            $form['name'],
            $form['roll_no'],
            $form['email'],
            $form['department'],
            $form['year'],
            $form['cgpa'],
            $form['phone'] ?: null, // Store NULL if phone is empty
            $id
        );
        $stmt->execute();

        // $stmt->affected_rows: how many rows were changed
        // 1 = success, 0 = same data (no change), -1 = error
        if ($stmt->affected_rows >= 0) {
            $stmt->close();
            // Store success flash message in session
            flash("✅ Student <strong>{$form['name']}</strong> updated successfully!", 'success');
            // Redirect back to the list (PRG pattern)
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}

// ── STEP 5: Display the Edit Form ────────────────────────────
pageHeader("Edit Student – {$student['name']}", 'students');
?>

<!-- ── BREADCRUMB NAVIGATION ─────────────────────────────────
     Shows: Home > Students > Edit Student
     THEORY (Unit I): Navigation helps users know where they are -->
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">
            <i class="bi bi-pencil-square text-primary me-2"></i>Edit Student
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:.82rem">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="index.php">Students</a></li>
                <li class="breadcrumb-item active">
                    Edit: <?= htmlspecialchars($student['name']) ?>
                </li>
            </ol>
        </nav>
    </div>
    <a href="index.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<?= showFlash() ?>

<!-- ── STUDENT INFO BANNER ──────────────────────────────────── -->
<div class="alert alert-info d-flex align-items-center gap-2 mb-4" style="border-radius:10px">
    <i class="bi bi-info-circle-fill fs-5"></i>
    <div>
        Editing record for <strong><?= htmlspecialchars($student['name']) ?></strong>
        (ID: <code><?= $student['id'] ?></code> |
        Roll No: <code><?= htmlspecialchars($student['roll_no']) ?></code>)
        · Created: <?= date('d M Y', strtotime($student['created_at'])) ?>
    </div>
</div>

<!-- ── VALIDATION ERRORS ─────────────────────────────────────
     Show a list of all validation errors if any exist
     PHP: if(!empty($array)) → true if array has at least one element -->
<?php if (!empty($errors)): ?>
<div class="alert alert-danger" style="border-radius:10px">
    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix these errors:</strong>
    <ul class="mt-2 mb-0 ps-3">
        <?php foreach ($errors as $error): ?>
            <!-- PHP foreach: loop through each error message -->
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════════
     EDIT FORM
     HTML5 Form (Unit I) with Bootstrap styling
     method="POST" = send data in request body (not URL)
     action="" = submit to the SAME page (this file)
     novalidate = disable HTML5 browser validation (we handle it)
     ══════════════════════════════════════════════════════════ -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-person-gear me-2"></i>Student Information
    </div>
    <div class="card-body p-4">

    <form method="POST" action="" novalidate id="editForm">

        <!-- Row 1: Name + Roll Number -->
        <!-- Bootstrap grid: row + col-md-6 = 2 columns on medium screens -->
        <div class="row g-4 mb-4">

            <!-- Full Name Field -->
            <div class="col-md-6">
                <label class="form-label" for="name">
                    Full Name <span class="text-danger">*</span>
                    <!-- Red asterisk = required field -->
                </label>
                <input
                    type="text"                          <!-- Input type: text -->
                    id="name"                            <!-- for="" in label links here -->
                    name="name"                          <!-- $_POST['name'] key -->
                    class="form-control <?= !empty($errors) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($form['name']) ?>"
                    <!-- value = pre-filled with existing/submitted data -->
                    placeholder="e.g. Alice Patel"
                    required                             <!-- HTML5 required -->
                    maxlength="100"
                >
                <!-- Invalid feedback: shown by Bootstrap when is-invalid class is present -->
                <div class="invalid-feedback">Please enter a valid name.</div>
            </div>

            <!-- Roll Number Field -->
            <div class="col-md-6">
                <label class="form-label" for="roll_no">
                    Roll Number <span class="text-danger">*</span>
                </label>
                <input
                    type="text"
                    id="roll_no"
                    name="roll_no"
                    class="form-control"
                    value="<?= htmlspecialchars($form['roll_no']) ?>"
                    placeholder="e.g. CS001"
                    required
                    maxlength="20"
                    style="text-transform:uppercase"    <!-- CSS: force uppercase display -->
                >
                <div class="form-text">Automatically converted to uppercase</div>
                <!-- form-text: Bootstrap small helper text below field -->
            </div>
        </div>

        <!-- Row 2: Email + Phone -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label" for="email">
                    Email Address <span class="text-danger">*</span>
                </label>
                <input
                    type="email"                         <!-- type=email: validates format -->
                    id="email"
                    name="email"
                    class="form-control"
                    value="<?= htmlspecialchars($form['email']) ?>"
                    placeholder="student@college.edu"
                    required
                >
            </div>
            <div class="col-md-6">
                <label class="form-label" for="phone">
                    Phone Number <span class="text-muted">(optional)</span>
                </label>
                <input
                    type="tel"                           <!-- type=tel: shows number pad on mobile -->
                    id="phone"
                    name="phone"
                    class="form-control"
                    value="<?= htmlspecialchars($form['phone']) ?>"
                    placeholder="10-digit number"
                    maxlength="10"
                    pattern="[0-9]{10}"                  <!-- HTML5 pattern validation -->
                >
                <div class="form-text">Enter 10 digits, no spaces or dashes</div>
            </div>
        </div>

        <!-- Row 3: Department + Year + CGPA -->
        <div class="row g-4 mb-4">

            <!-- Department Dropdown -->
            <div class="col-md-4">
                <label class="form-label" for="department">
                    Department <span class="text-danger">*</span>
                </label>
                <!-- <select> = dropdown list (Unit I: HTML Form elements) -->
                <select id="department" name="department" class="form-select" required>
                    <option value="">-- Select Department --</option>
                    <?php
                    // DEPARTMENTS constant defined in config.php
                    // Loop through each department option
                    foreach (DEPARTMENTS as $dept):
                        // 'selected' attribute highlights the current department
                        $selected = ($form['department'] === $dept) ? 'selected' : '';
                    ?>
                        <option value="<?= $dept ?>" <?= $selected ?>><?= $dept ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Year Dropdown -->
            <div class="col-md-4">
                <label class="form-label" for="year">
                    Year of Study <span class="text-danger">*</span>
                </label>
                <select id="year" name="year" class="form-select" required>
                    <option value="">-- Select Year --</option>
                    <?php foreach (YEARS as $val => $label): ?>
                        <option value="<?= $val ?>"
                            <?= ($form['year'] == $val) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- CGPA Input -->
            <div class="col-md-4">
                <label class="form-label" for="cgpa">
                    CGPA <span class="text-muted">(0.00 – 10.00)</span>
                </label>
                <input
                    type="number"                       <!-- type=number: numeric keyboard, up/down arrows -->
                    id="cgpa"
                    name="cgpa"
                    class="form-control"
                    value="<?= $form['cgpa'] ?>"
                    min="0"                             <!-- Minimum allowed value -->
                    max="10"                            <!-- Maximum allowed value -->
                    step="0.01"                         <!-- Step = 0.01 allows decimals like 8.75 -->
                    placeholder="e.g. 8.75"
                >
            </div>
        </div>

        <!-- ── FORM ACTION BUTTONS ────────────────────────────── -->
        <div class="d-flex gap-3 pt-2 border-top">
            <!-- Submit button: updates the student record -->
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                Update Student
            </button>
            <!-- Cancel: go back without saving -->
            <a href="index.php" class="btn btn-outline-secondary px-4">
                <i class="bi bi-x-circle me-2"></i>
                Cancel
            </a>
            <!-- Reset: restores form to original database values -->
            <button type="reset" class="btn btn-light px-4 ms-auto">
                <i class="bi bi-arrow-counterclockwise me-2"></i>
                Reset Changes
            </button>
        </div>

    </form>
    </div><!-- /card-body -->
</div><!-- /card -->

<!-- ── JavaScript Form Validation (Unit II: JS Validation) ─────
     Client-side validation runs BEFORE sending to server
     This gives immediate feedback without a page reload
     NOTE: We ALSO do server-side validation in PHP (line ~35)
           because JavaScript can be disabled by the user
     ──────────────────────────────────────────────────────────── -->
<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function () {

    // Get the form element using getElementById (DOM Level 1)
    const form = document.getElementById('editForm');

    // Attach submit event listener
    form.addEventListener('submit', function (e) {

        let hasError = false;
        let firstError = null;

        // ── Validate Name ─────────────────────────────────────
        const name = document.getElementById('name');
        if (name.value.trim() === '') {
            name.classList.add('is-invalid');        // Red border
            hasError = true;
            if (!firstError) firstError = name;
        } else {
            name.classList.remove('is-invalid');
            name.classList.add('is-valid');          // Green border
        }

        // ── Validate Roll Number ──────────────────────────────
        const roll = document.getElementById('roll_no');
        const rollRegex = /^[A-Za-z0-9]{2,10}$/;    // JS Regular Expression
        if (!rollRegex.test(roll.value.trim())) {
            roll.classList.add('is-invalid');
            hasError = true;
            if (!firstError) firstError = roll;
        } else {
            roll.classList.remove('is-invalid');
            roll.classList.add('is-valid');
        }

        // ── Validate Email ────────────────────────────────────
        const email = document.getElementById('email');
        // JS regex for basic email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            email.classList.add('is-invalid');
            hasError = true;
            if (!firstError) firstError = email;
        } else {
            email.classList.remove('is-invalid');
            email.classList.add('is-valid');
        }

        // ── Validate Department ───────────────────────────────
        const dept = document.getElementById('department');
        if (dept.value === '') {
            dept.classList.add('is-invalid');
            hasError = true;
            if (!firstError) firstError = dept;
        } else {
            dept.classList.remove('is-invalid');
            dept.classList.add('is-valid');
        }

        // ── Validate Year ──────────────────────────────────────
        const year = document.getElementById('year');
        if (year.value === '') {
            year.classList.add('is-invalid');
            hasError = true;
        } else {
            year.classList.remove('is-invalid');
            year.classList.add('is-valid');
        }

        // If errors found: stop form submission, scroll to first error
        if (hasError) {
            e.preventDefault();           // Stop form from submitting
            e.stopPropagation();          // Stop event bubbling
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();       // Focus on first error field
            }
        }
    });

    // ── Auto-uppercase roll number as user types ──────────────
    document.getElementById('roll_no').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    // ── Phone: allow only numbers ─────────────────────────────
    document.getElementById('phone').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, ''); // Remove non-digits (\D = non-digit)
    });
});
</script>

<?php pageFooter(); ?>
