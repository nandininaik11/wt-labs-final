<?php
/**
 * layout.php – Shared header / footer for all pages
 */

function pageHeader(string $title, string $activePage = ''): void {
    $user = $_SESSION['name'] ?? '';
    $role = $_SESSION['role'] ?? '';
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?> – Attendance System</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2c3e7a;
            --accent:  #e74c3c;
            --light-bg: #f4f6fb;
        }
        body        { background: var(--light-bg); font-family: 'Segoe UI', sans-serif; }
        .navbar     { background: var(--primary) !important; }
        .navbar-brand { font-weight: 700; letter-spacing: .5px; }
        .sidebar    { min-height: 100vh; background: #fff;
                      border-right: 1px solid #dee2e6; padding-top: 1rem; }
        .sidebar .nav-link { color: #444; border-radius: 8px; margin: 2px 8px; }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active { background: var(--primary); color: #fff; }
        .sidebar .nav-link i { width: 20px; }
        .main-content { padding: 2rem; }
        .card       { border: none; border-radius: 14px;
                      box-shadow: 0 2px 12px rgba(0,0,0,.08); }
        .card-header { border-radius: 14px 14px 0 0 !important;
                       background: var(--primary); color: #fff; font-weight: 600; }
        .stat-card  { border-left: 5px solid var(--primary); }
        .badge-present { background: #d4edda; color: #155724; }
        .badge-absent  { background: #f8d7da; color: #721c24; }
        table.attendance-table th { background: var(--primary); color: #fff; }
        .check-present { accent-color: #28a745; width: 18px; height: 18px; }
        .check-absent  { accent-color: #dc3545; width: 18px; height: 18px; }
        pre { background: #1e1e1e; color: #dcdcdc; border-radius: 8px;
              padding: 14px; font-size: .82rem; }
    </style>
</head>
<body>

<!-- ── Navbar ──────────────────────────────────────────────── -->
<nav class="navbar navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-mortarboard-fill me-2"></i>AttendanceMS
        </a>
        <?php if ($user): ?>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white-50 small">
                <i class="bi bi-person-circle me-1"></i>
                <?= htmlspecialchars($user) ?>
                <span class="badge bg-warning text-dark ms-1"><?= ucfirst($role) ?></span>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
        <?php endif; ?>
    </div>
</nav>

<div class="container-fluid">
<div class="row">

<?php if ($user): ?>
<!-- ── Sidebar ─────────────────────────────────────────────── -->
<nav class="col-md-2 d-none d-md-block sidebar pt-3">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?= $activePage==='dashboard'?'active':'' ?>" href="index.php">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <?php if ($role === 'teacher'): ?>
        <li class="nav-item">
            <a class="nav-link <?= $activePage==='take_attendance'?'active':'' ?>"
               href="take_attendance.php">
                <i class="bi bi-check2-square"></i> Take Attendance
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activePage==='view_attendance'?'active':'' ?>"
               href="view_attendance.php">
                <i class="bi bi-table"></i> View Records
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activePage==='students'?'active':'' ?>"
               href="students.php">
                <i class="bi bi-people"></i> Students
            </a>
        </li>
        <?php else: ?>
        <li class="nav-item">
            <a class="nav-link <?= $activePage==='my_attendance'?'active':'' ?>"
               href="my_attendance.php">
                <i class="bi bi-calendar-check"></i> My Attendance
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $activePage==='profile'?'active':'' ?>"
               href="profile.php">
                <i class="bi bi-person"></i> My Profile
            </a>
        </li>
        <?php endif; ?>
        <li class="nav-item mt-3">
            <a class="nav-link text-danger" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
    </ul>
</nav>
<?php endif; ?>

<!-- ── Main Content Area ────────────────────────────────────── -->
<main class="<?= $user ? 'col-md-10' : 'col-12' ?> main-content">
<?php
}

function pageFooter(): void {
    ?>
</main>
</div><!-- /row -->
</div><!-- /container-fluid -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}
?>
