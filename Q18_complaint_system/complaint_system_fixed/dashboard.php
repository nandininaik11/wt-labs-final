<?php
// ============================================================
// dashboard.php — Main Dashboard
// Shows stats and list of user's complaints
// Concepts: SQL SELECT, PHP loops, HTML tables
// ============================================================
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin(); // redirect to login if not authenticated

// ── Redirect admins to admin panel ──
if (isAdmin()) {
    header("Location: admin.php");
    exit();
}

$userId = currentUserId(); // get logged-in user's ID from session

// ── Fetch complaint statistics for this user ──
// COUNT(*) counts total rows; WHERE filters by user_id
$stats = $pdo->prepare("
    SELECT
        COUNT(*) AS total,
        SUM(status = 'Pending')     AS pending,
        SUM(status = 'In Progress') AS inprogress,
        SUM(status = 'Resolved')    AS resolved,
        SUM(status = 'Rejected')    AS rejected
    FROM complaints
    WHERE user_id = ?
");
$stats->execute([$userId]);
$s = $stats->fetch(); // one row with all counts

// ── Fetch all complaints for this user (newest first) ──
// ORDER BY submitted_at DESC = latest complaint on top
$stmt = $pdo->prepare("
    SELECT * FROM complaints
    WHERE user_id = ?
    ORDER BY submitted_at DESC
");
$stmt->execute([$userId]);
$complaints = $stmt->fetchAll(); // fetchAll() returns ALL rows as array of arrays

// ── Flash message (set in submit_complaint.php after redirect) ──
// Flash messages: stored in session, shown once, then deleted
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']); // delete after reading
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Complaint System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <span class="brand">📋 Complaint Management System</span>
    <div>
        <!-- PHP echo inside HTML attribute -->
        <span style="color:rgba(255,255,255,0.7);font-size:0.9rem;">
            👤 <?= htmlspecialchars(currentUser()) ?>
        </span>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">

    <!-- Flash message (success/error after form submit) -->
    <?php if ($flash): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="num" style="color:#2563eb"><?= $s['total'] ?></div>
            <div class="lbl">Total Complaints</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#d97706"><?= $s['pending'] ?></div>
            <div class="lbl">Pending</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#2563eb"><?= $s['inprogress'] ?></div>
            <div class="lbl">In Progress</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#16a34a"><?= $s['resolved'] ?></div>
            <div class="lbl">Resolved</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#dc2626"><?= $s['rejected'] ?></div>
            <div class="lbl">Rejected</div>
        </div>
    </div>

    <!-- Complaints Table -->
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h2 style="margin:0">📂 My Complaints</h2>
            <a href="submit_complaint.php" class="btn btn-primary">＋ New Complaint</a>
        </div>

        <?php if (empty($complaints)): ?>
            <!-- PHP if with alternative syntax (colon + endif) — common in templates -->
            <div class="alert alert-info">
                You haven't submitted any complaints yet.
                <a href="submit_complaint.php">Submit your first complaint →</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Organization</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP foreach loop: iterate over all complaints -->
                    <?php foreach ($complaints as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['organization']) ?></td>
                        <td><?= htmlspecialchars($c['subject']) ?></td>
                        <td>
                            <!-- Dynamic CSS class based on status value -->
                            <?php
                            // Map status string to CSS badge class
                            $badgeMap = [
                                'Pending'     => 'badge-pending',
                                'In Progress' => 'badge-progress',
                                'Resolved'    => 'badge-resolved',
                                'Rejected'    => 'badge-rejected',
                            ];
                            $badgeClass = $badgeMap[$c['status']] ?? 'badge-pending';
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <?= $c['status'] ?>
                            </span>
                        </td>
                        <!-- date() formats a Unix timestamp; strtotime() converts DB date string -->
                        <td><?= date('d M Y', strtotime($c['submitted_at'])) ?></td>
                        <td>
                            <a href="view_complaint.php?id=<?= $c['id'] ?>"
                               class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div><!-- /.container -->
</body>
</html>
