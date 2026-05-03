<?php
// profile.php
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/layout.php';
requireLogin();

$uid  = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

pageHeader('My Profile', 'profile');
?>
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card p-4">
    <h5 class="mb-4"><i class="bi bi-person-circle me-2"></i>My Profile</h5>
    <table class="table">
        <tr><th>Name</th><td><?= htmlspecialchars($user['name']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($user['email']) ?></td></tr>
        <tr><th>Role</th><td><span class="badge bg-primary"><?= ucfirst($user['role']) ?></span></td></tr>
        <?php if ($user['roll_no']): ?>
        <tr><th>Roll No</th><td><code><?= htmlspecialchars($user['roll_no']) ?></code></td></tr>
        <?php endif; ?>
        <tr><th>Department</th><td><?= htmlspecialchars($user['department']) ?></td></tr>
        <tr><th>Joined</th><td><?= date('d M Y', strtotime($user['created_at'])) ?></td></tr>
    </table>
</div>
</div>
</div>
<?php pageFooter(); ?>
