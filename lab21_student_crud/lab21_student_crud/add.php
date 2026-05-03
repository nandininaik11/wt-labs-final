<?php
/* ============================================================
   add.php  –  Add New Student
   Lab Q21: INSERT new student into database
   ============================================================ */

session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/layout.php';

// Initialize empty form
$form   = ['name'=>'','roll_no'=>'','email'=>'','department'=>'','year'=>'','cgpa'=>'','phone'=>''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = [
        'name'       => sanitize($_POST['name']       ?? ''),
        'roll_no'    => strtoupper(sanitize($_POST['roll_no'] ?? '')),
        'email'      => sanitize($_POST['email']      ?? ''),
        'department' => sanitize($_POST['department'] ?? ''),
        'year'       => (int)($_POST['year']          ?? 0),
        'cgpa'       => (float)($_POST['cgpa']        ?? 0),
        'phone'      => sanitize($_POST['phone']      ?? ''),
    ];

    $errors = validateStudent($form);

    if (empty($errors)) {
        // Check roll_no uniqueness
        $stmt = $conn->prepare("SELECT id FROM students WHERE roll_no = ?");
        $stmt->bind_param("s", $form['roll_no']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) $errors[] = "Roll number already exists.";
        $stmt->close();
    }
    if (empty($errors)) {
        // Check email uniqueness
        $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->bind_param("s", $form['email']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) $errors[] = "Email already registered.";
        $stmt->close();
    }

    if (empty($errors)) {
        // SQL INSERT: add new row to table
        $stmt = $conn->prepare(
            "INSERT INTO students (name, roll_no, email, department, year, cgpa, phone)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $phone = $form['phone'] ?: null;
        $stmt->bind_param("ssssids",
            $form['name'], $form['roll_no'], $form['email'],
            $form['department'], $form['year'], $form['cgpa'], $phone
        );
        if ($stmt->execute()) {
            $newId = $conn->insert_id; // Get the ID of the newly inserted row
            flash("✅ Student <strong>{$form['name']}</strong> added successfully! (ID: $newId)", 'success');
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}

pageHeader('Add Student', 'add');
?>

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">
            <i class="bi bi-person-plus-fill text-primary me-2"></i>Add New Student
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:.82rem">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Add Student</li>
            </ol>
        </nav>
    </div>
    <a href="index.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger" style="border-radius:10px">
    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix these errors:</strong>
    <ul class="mt-2 mb-0 ps-3">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><i class="bi bi-person-badge me-2"></i>Student Details</div>
    <div class="card-body p-4">
    <form method="POST" action="">
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($form['name']) ?>"
                       placeholder="e.g. Alice Patel" required maxlength="100">
            </div>
            <div class="col-md-6">
                <label class="form-label">Roll Number <span class="text-danger">*</span></label>
                <input type="text" name="roll_no" class="form-control"
                       value="<?= htmlspecialchars($form['roll_no']) ?>"
                       placeholder="e.g. CS005" required style="text-transform:uppercase"
                       oninput="this.value=this.value.toUpperCase()">
            </div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($form['email']) ?>"
                       placeholder="student@college.edu" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone <span class="text-muted">(optional)</span></label>
                <input type="tel" name="phone" class="form-control"
                       value="<?= htmlspecialchars($form['phone']) ?>"
                       placeholder="10-digit number" maxlength="10"
                       oninput="this.value=this.value.replace(/\D/g,'')">
            </div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department" class="form-select" required>
                    <option value="">-- Select --</option>
                    <?php foreach (DEPARTMENTS as $d): ?>
                        <option value="<?= $d ?>" <?= ($form['department']===$d)?'selected':'' ?>><?= $d ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Year <span class="text-danger">*</span></label>
                <select name="year" class="form-select" required>
                    <option value="">-- Select --</option>
                    <?php foreach (YEARS as $v => $l): ?>
                        <option value="<?= $v ?>" <?= ($form['year']==$v)?'selected':'' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">CGPA</label>
                <input type="number" name="cgpa" class="form-control"
                       value="<?= $form['cgpa'] ?>" min="0" max="10" step="0.01"
                       placeholder="e.g. 8.75">
            </div>
        </div>
        <div class="d-flex gap-3 pt-2 border-top">
            <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-person-plus-fill me-2"></i>Add Student
            </button>
            <a href="index.php" class="btn btn-outline-secondary px-4">Cancel</a>
            <button type="reset" class="btn btn-light px-4 ms-auto">Clear Form</button>
        </div>
    </form>
    </div>
</div>

<?php pageFooter(); ?>
