<?php
/**
 * login.php – Unified login for student and teacher
 */
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/layout.php';

if (isLoggedIn()) { header('Location: index.php'); exit; }

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, role, roll_no FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['roll_no'] = $user['roll_no'];
            flash("Welcome back, " . $user['name'] . "! 👋", 'success');
            header('Location: index.php'); exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}

pageHeader('Login');
?>

<div class="row justify-content-center mt-5">
<div class="col-md-5 col-lg-4">

    <!-- Logo / Title -->
    <div class="text-center mb-4">
        <div style="font-size:3rem">🎓</div>
        <h4 class="fw-bold" style="color:var(--primary)">Attendance System</h4>
        <p class="text-muted">Lab Q12 – PHP & MySQL</p>
    </div>

    <div class="card">
        <div class="card-header py-3">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login
        </div>
        <div class="card-body p-4">

            <?= showFlash() ?>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2">
                    <i class="bi bi-exclamation-triangle me-1"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($email) ?>"
                           placeholder="your@email.com" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="••••••••" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>
                </div>
            </form>

            <hr>
            <div class="text-center">
                <small class="text-muted">New student?</small>
                <a href="register.php" class="ms-1 btn btn-sm btn-outline-success">
                    <i class="bi bi-person-plus"></i> Register
                </a>
            </div>

        </div>
    </div>

    <!-- Quick login panel -->
    <div class="card mt-3">
        <div class="card-body p-3">
            <p class="fw-semibold mb-2 small"><i class="bi bi-lightning-fill text-warning"></i> Quick Demo Login</p>
            <div class="d-grid gap-2">
                <button class="btn btn-sm btn-outline-primary"
                        onclick="fillLogin('teacher@college.com','password123')">
                    👨‍🏫 Login as Teacher
                </button>
                <button class="btn btn-sm btn-outline-success"
                        onclick="fillLogin('alice@student.com','password123')">
                    👩‍🎓 Login as Student (Alice)
                </button>
            </div>
        </div>
    </div>

</div>
</div>

<script>
function fillLogin(email, pass) {
    document.querySelector('[name=email]').value = email;
    document.querySelector('[name=password]').value = pass;
}
</script>

<?php pageFooter(); ?>
