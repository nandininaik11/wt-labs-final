<?php
// ============================================================
// view_complaint.php — View Single Complaint Details
// Concepts: $_GET superglobal, SQL WHERE clause, security check
// ============================================================
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin();

// ── Get complaint ID from URL query string ──
// URL: view_complaint.php?id=5 → $_GET['id'] = "5"
// $_GET is a superglobal array containing URL parameters
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// (int) cast ensures it's an integer — prevents SQL injection via URL

if ($id <= 0) {
    die("Invalid complaint ID.");
}

// Fetch complaint — also verify it belongs to logged-in user
// This prevents users from viewing other users' complaints
$stmt = $pdo->prepare("SELECT * FROM complaints WHERE id = ? AND user_id = ?");
$stmt->execute([$id, currentUserId()]);
$complaint = $stmt->fetch();

// If not found (doesn't exist or belongs to someone else)
if (!$complaint) {
    die("Complaint not found or access denied.");
}

// Map status → badge CSS class
$badgeMap = [
    'Pending'     => 'badge-pending',
    'In Progress' => 'badge-progress',
    'Resolved'    => 'badge-resolved',
    'Rejected'    => 'badge-rejected',
];
$badgeClass = $badgeMap[$complaint['status']] ?? 'badge-pending';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint #<?= $complaint['id'] ?> — Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <span class="brand">📋 Complaint Management System</span>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container" style="max-width:680px">
    <div class="card">
        <!-- Complaint Header -->
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;">
            <div>
                <h2 style="margin-bottom:4px">
                    Complaint #<?= $complaint['id'] ?>
                </h2>
                <small style="color:#64748b">
                    Submitted on <?= date('d F Y \a\t h:i A', strtotime($complaint['submitted_at'])) ?>
                </small>
            </div>
            <span class="badge <?= $badgeClass ?>" style="font-size:0.95rem;padding:6px 18px;">
                <?= $complaint['status'] ?>
            </span>
        </div>

        <!-- Detail rows -->
        <table style="border:none">
            <tr>
                <td style="border:none;width:160px;font-weight:700;color:#64748b;padding:10px 0">Organization</td>
                <td style="border:none;padding:10px 0"><?= htmlspecialchars($complaint['organization']) ?></td>
            </tr>
            <tr>
                <td style="border:none;font-weight:700;color:#64748b;padding:10px 0">Subject</td>
                <td style="border:none;padding:10px 0"><?= htmlspecialchars($complaint['subject']) ?></td>
            </tr>
            <tr>
                <td style="border:none;font-weight:700;color:#64748b;padding:10px 0;vertical-align:top">Description</td>
                <td style="border:none;padding:10px 0;white-space:pre-wrap;line-height:1.7">
                    <?= htmlspecialchars($complaint['description']) ?>
                    <!-- pre-wrap: keeps newlines from textarea, htmlspecialchars: safe output -->
                </td>
            </tr>
        </table>

        <!-- Status Timeline (visual representation) -->
        <div style="margin-top:32px;padding-top:24px;border-top:1px solid #e2e8f0;">
            <h3 style="margin-bottom:16px;font-size:1rem;color:#64748b">Status Timeline</h3>
            <div style="display:flex;gap:0;align-items:center;">
                <?php
                // Array of all possible statuses in order
                $steps = ['Pending', 'In Progress', 'Resolved'];
                $current = $complaint['status'];
                $currentIdx = array_search($current, $steps); // index of current status
                // array_search returns false if not found (e.g. 'Rejected')
                foreach ($steps as $i => $step):
                    // Is this step completed (at or before current)?
                    $done = ($currentIdx !== false && $i <= $currentIdx);
                    $active = ($step === $current);
                    $color = $done ? '#16a34a' : '#e2e8f0';
                    $textColor = $done ? '#16a34a' : '#94a3b8';
                ?>
                    <div style="text-align:center;flex:1">
                        <!-- Circle indicator -->
                        <div style="width:32px;height:32px;border-radius:50%;background:<?= $color ?>;
                                    color:#fff;display:flex;align-items:center;justify-content:center;
                                    margin:0 auto;font-size:0.85rem;font-weight:700;
                                    border: 2px solid <?= $active ? '#166534' : $color ?>;">
                            <?= $done ? '✓' : ($i+1) ?>
                        </div>
                        <div style="font-size:0.75rem;margin-top:6px;color:<?= $textColor ?>;font-weight:<?= $active ? '700' : '400' ?>">
                            <?= $step ?>
                        </div>
                    </div>
                    <!-- Connector line between steps (not after last) -->
                    <?php if ($i < count($steps)-1): ?>
                        <div style="flex:1;height:2px;background:<?= ($currentIdx !== false && $i < $currentIdx) ? '#16a34a' : '#e2e8f0' ?>;"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Special display for 'Rejected' status -->
            <?php if ($current === 'Rejected'): ?>
                <div class="alert alert-error" style="margin-top:16px">
                    ❌ This complaint was rejected by the authority.
                </div>
            <?php endif; ?>
        </div>

        <!-- Back button -->
        <div style="margin-top:28px">
            <a href="dashboard.php" class="btn" style="background:#e2e8f0;color:#334155">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</div>

</body>
</html>
