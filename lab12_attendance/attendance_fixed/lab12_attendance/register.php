<?php
/**
 * register.php – Student self-registration
 * Lab Q12: Attendance System
 */
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/layout.php';

// Already logged in? Go home
if (isLoggedIn()) { header('Location: index.php'); exit; }

$errors = [];
$form   = ['name'=>'','email'=>'','roll_no'=>'','department'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name       = trim($_POST['name']       ?? '');
    $email      = trim($_POST['email']      ?? '');
    $roll_no    = trim($_POST['roll_no']    ?? '');
    $department = trim($_POST['department'] ?? '');
    $password   = $_POST['password']        ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';

    $form = compact('name','email','roll_no','department');

    // Validation
    if (empty($name))       $errors[] = "Name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                            $errors[] = "Valid email is required.";
    if (empty($roll_no))    $errors[] = "Roll number is required.";
    if (empty($department)) $errors[] = "Department is required.";
    if (strlen($password) < 6)
                            $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm)
                            $errors[] = "Passwords do not match.";

    // Check email uniqueness
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Email already registered.";
        }
        $stmt->close();
    }

    // Check roll_no uniqueness
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE roll_no = ?");
        $stmt->bind_param("s", $roll_no);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Roll number already exists.";
        }
        $stmt->close();
    }

    // Insert student
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt   = $conn->prepare(
            "INSERT INTO users (name, email, password, role, roll_no, department)
             VALUES (?, ?, ?, 'student', ?, ?)"
        );
        $stmt->bind_param("sssss", $name, $email, $hashed, $roll_no, $department);
        if ($stmt->execute()) {
            flash("✅ Registration successful! Please login.", 'success');
            header('Location: login.php'); exit;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}

pageHeader('Student Registration');
?>

<div class="row justify-content-center mt-4">
<div class="col-md-7 col-lg-6">

    <div class="card">
        <div class="card-header py-3">
            <i class="bi bi-person-plus me-2"></i>Student Registration
        </div>
        <div class="card-body p-4">

            <p class="text-muted mb-4">
                Register yourself as a student. Already registered?
                <a href="login.php">Login here</a>
            </p>

            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="" novalidate>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Full Name *</label>
                        <input type="text" name="name" class="form-control"
                               value="<?= htmlspecialchars($form['name']) ?>"
                               placeholder="e.g. Alice Patel" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Roll Number *</label>
                        <input type="text" name="roll_no" class="form-control"
                               value="<?= htmlspecialchars($form['roll_no']) ?>"
                               placeholder="e.g. CS004" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Email Address *</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($form['email']) ?>"
                               placeholder="student@example.com" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Department *</label>
                        <select name="department" class="form-select" required>
                            <option value="">-- Select Department --</option>
                            <?php
                            $depts = ['Computer Science','Information Technology',
                                      'Electronics','Mechanical','Civil','Electrical'];
                            foreach ($depts as $d):
                                $sel = ($form['department'] === $d) ? 'selected' : '';
                            ?>
                            <option value="<?= $d ?>" <?= $sel ?>><?= $d ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password *</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Min. 6 characters" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Confirm Password *</label>
                        <input type="password" name="confirm_password" class="form-control"
                               placeholder="Repeat password" required>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-check me-2"></i>Register as Student
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Demo credentials box -->
    <div class="card mt-3 border-warning">
        <div class="card-body py-2 px-3">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Demo logins (from seed data):</strong><br>
                Teacher: <code>teacher@college.com</code> / <code>password123</code><br>
                Student: <code>alice@student.com</code> / <code>password123</code>
            </small>
        </div>
    </div>

</div>
</div>

<?php pageFooter(); ?>
