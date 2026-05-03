<?php
/**
 * config.php – Database connection using MySQLi
 * Lab Q12: Attendance System
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Change to your MySQL username
define('DB_PASS', '');           // Change to your MySQL password
define('DB_NAME', 'attendance_db');

// Create MySQLi connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("
    <div style='font-family:sans-serif;background:#fee;border:2px solid red;padding:20px;margin:40px auto;max-width:600px;border-radius:8px'>
        <h3>⚠️ Database Connection Failed</h3>
        <p><strong>Error:</strong> " . htmlspecialchars($conn->connect_error) . "</p>
        <p><strong>Fix:</strong></p>
        <ol>
            <li>Make sure MySQL/XAMPP is running</li>
            <li>Import <code>sql/schema.sql</code> in phpMyAdmin</li>
            <li>Update DB_USER/DB_PASS in <code>includes/config.php</code></li>
        </ol>
    </div>");
}

$conn->set_charset("utf8mb4");

// ── Helper: flash messages via session ──────────────────────
function flash(string $msg, string $type = 'success'): void {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function showFlash(): string {
    if (!isset($_SESSION['flash'])) return '';
    $f   = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $map = ['success'=>'success','error'=>'danger','warning'=>'warning','info'=>'info'];
    $cls = $map[$f['type']] ?? 'info';
    return "<div class='alert alert-{$cls} alert-dismissible fade show' role='alert'>
                {$f['msg']}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

// ── Auth helpers ─────────────────────────────────────────────
function requireLogin(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php'); exit;
    }
}
function requireRole(string $role): void {
    requireLogin();
    if ($_SESSION['role'] !== $role) {
        header('Location: index.php'); exit;
    }
}
function isLoggedIn(): bool { return !empty($_SESSION['user_id']); }
function isTeacher(): bool  { return ($_SESSION['role'] ?? '') === 'teacher'; }
function isStudent(): bool  { return ($_SESSION['role'] ?? '') === 'student'; }
?>
