<?php
// ============================================================
// admin.php — Admin Panel
// Admin can view ALL complaints and update their status
// Concepts: SQL UPDATE, PHP ENUM handling, role-based access
// ============================================================
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireAdmin(); // Check if user is admin, redirect if not

$message = '';

// ── Handle status update (POST request) ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_id'], $_POST['status'])) {
    $cid    = (int)$_POST['complaint_id'];
    $status = $_POST['status'];
    
    // Whitelist valid statuses (never trust user input!)
    $validStatuses = ['Pending', 'In Progress', 'Resolved', 'Rejected'];
    
    if (in_array($status, $validStatuses)) {
        // SQL UPDATE: modify existing row
        $stmt = $pdo->prepare("UPDATE complaints SET status = ? WHERE id = ?");
        $stmt->execute([$status, $cid]);
        $message = "Complaint #$cid status updated to '$status'.";
    }
}

// ── Fetch all complaints with user names (JOIN) ──
// SQL JOIN combines data from two tables
// complaints.user_id references users.id
$all = $pdo->query("
    SELECT c.*, u.name AS user_name, u.email AS user_email
    FROM complaints c
    JOIN users u ON c.user_id = u.id   -- match complaint's user_id with user's id
    ORDER BY c.submitted_at DESC
")->fetchAll();

// ── Count by status for quick overview ──
$counts = $pdo->query("
    SELECT status, COUNT(*) AS cnt
    FROM complaints
    GROUP BY status
")->fetchAll(PDO::FETCH_KEY_PAIR); // returns ['Pending'=>3, 'Resolved'=>5, ...]
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — Complaint System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <span class="brand">🛡️ Admin Panel — Complaint System</span>
    <div>
        <a href="dashboard.php">My Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container" style="max-width:1100px">

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Summary Stats -->
    <div class="stats-grid" style="margin-bottom:24px">
        <?php
        $statuses = ['Pending'=>'#d97706','In Progress'=>'#2563eb','Resolved'=>'#16a34a','Rejected'=>'#dc2626'];
        foreach ($statuses as $st => $color):
            $count = $counts[$st] ?? 0;
        ?>
        <div class="stat-card">
            <div class="num" style="color:<?= $color ?>"><?= $count ?></div>
            <div class="lbl"><?= $st ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- All Complaints Table -->
    <div class="card">
        <h2>📋 All Complaints (<?= count($all) ?>)</h2>

        <?php if (empty($all)): ?>
            <p style="color:#64748b">No complaints submitted yet.</p>
        <?php else: ?>
        <div style="overflow-x:auto"> <!-- horizontal scroll on small screens -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Organization</th>
                    <th>Subject</th>
                    <th>Current Status</th>
                    <th>Date</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all as $c): ?>
                <tr>
                    <td>#<?= $c['id'] ?></td>
                    <td>
                        <!-- PHP string concatenation using . operator -->
                        <?= htmlspecialchars($c['user_name']) ?><br>
                        <small style="color:#64748b"><?= htmlspecialchars($c['user_email']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($c['organization']) ?></td>
                    <td style="max-width:200px">
                        <?= htmlspecialchars(substr($c['subject'], 0, 60)) ?>
                        <!-- substr() trims long subjects to 60 chars -->
                        <?= strlen($c['subject']) > 60 ? '…' : '' ?>
                    </td>
                    <td>
                        <?php
                        $badgeMap = [
                            'Pending'     => 'badge-pending',
                            'In Progress' => 'badge-progress',
                            'Resolved'    => 'badge-resolved',
                            'Rejected'    => 'badge-rejected',
                        ];
                        ?>
                        <span class="badge <?= $badgeMap[$c['status']] ?? '' ?>">
                            <?= $c['status'] ?>
                        </span>
                    </td>
                    <td><?= date('d M Y', strtotime($c['submitted_at'])) ?></td>
                    <td>
                        <!-- Inline form to update this specific complaint's status -->
                        <!-- Each row has its own mini form -->
                        <form method="post" style="display:flex;gap:6px;align-items:center">
                            <!-- Hidden field: passes complaint ID without user seeing it -->
                            <input type="hidden" name="complaint_id" value="<?= $c['id'] ?>">
                            <select name="status" style="padding:5px 8px;border-radius:6px;border:1px solid #e2e8f0;font-size:0.85rem">
                                <?php foreach (['Pending','In Progress','Resolved','Rejected'] as $s): ?>
                                    <option value="<?= $s ?>" <?= ($c['status']===$s)?'selected':'' ?>>
                                        <?= $s ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
