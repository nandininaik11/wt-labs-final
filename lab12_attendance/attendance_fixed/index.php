<?php
/**
 * index.php – Dashboard (different views for teacher and student)
 */
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/layout.php';
requireLogin();

$role = $_SESSION['role'];
$uid  = $_SESSION['user_id'];

if ($role === 'teacher') {
    // ── Teacher Stats ─────────────────────────────────────
    $totalStudents = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='student'")->fetch_assoc()['c'];
    $today = date('Y-m-d');
    $todayPresent = $conn->query("SELECT COUNT(*) as c FROM attendance WHERE date='$today' AND status='present'")->fetch_assoc()['c'];
    $totalRecords = $conn->query("SELECT COUNT(*) as c FROM attendance")->fetch_assoc()['c'];
    $subjects     = $conn->query("SELECT DISTINCT subject FROM attendance ORDER BY subject")->fetch_all(MYSQLI_ASSOC);

    // Recent attendance sessions
    $recent = $conn->query(
        "SELECT a.date, a.subject,
                SUM(a.status='present') as present_count,
                SUM(a.status='absent')  as absent_count,
                u.name as teacher_name
         FROM attendance a
         JOIN users u ON u.id = a.marked_by
         GROUP BY a.date, a.subject
         ORDER BY a.date DESC, a.subject
         LIMIT 7"
    );

} else {
    // ── Student Stats ──────────────────────────────────────
    $stmt = $conn->prepare(
        "SELECT
            COUNT(*) as total,
            SUM(status='present') as present_count,
            SUM(status='absent') as absent_count
         FROM attendance WHERE student_id = ?"
    );
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stats = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $total   = $stats['total'] ?: 0;
    $present = $stats['present_count'] ?: 0;
    $absent  = $stats['absent_count']  ?: 0;
    $pct     = $total > 0 ? round(($present / $total) * 100) : 0;

    // Recent 5 records
    $stmt = $conn->prepare(
        "SELECT date, subject, status FROM attendance
         WHERE student_id = ? ORDER BY date DESC LIMIT 5"
    );
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $recent = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

pageHeader('Dashboard', 'dashboard');
echo showFlash();
?>

<h4 class="mb-4">
    <i class="bi bi-speedometer2 me-2"></i>
    <?= $role === 'teacher' ? '👨‍🏫 Teacher Dashboard' : '👩‍🎓 Student Dashboard' ?>
</h4>

<?php if ($role === 'teacher'): ?>
<!-- ══════════════════ TEACHER DASHBOARD ══════════════════ -->

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="fs-1 me-3">👥</div>
                <div>
                    <div class="fs-3 fw-bold text-primary"><?= $totalStudents ?></div>
                    <div class="text-muted small">Total Students</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-3" style="border-color:#28a745">
            <div class="d-flex align-items-center">
                <div class="fs-1 me-3">✅</div>
                <div>
                    <div class="fs-3 fw-bold text-success"><?= $todayPresent ?></div>
                    <div class="text-muted small">Present Today</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-3" style="border-color:#e74c3c">
            <div class="d-flex align-items-center">
                <div class="fs-1 me-3">📋</div>
                <div>
                    <div class="fs-3 fw-bold text-danger"><?= $totalRecords ?></div>
                    <div class="text-muted small">Total Records</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick actions -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <a href="take_attendance.php" class="btn btn-primary btn-lg w-100 py-3">
            <i class="bi bi-check2-square fs-4 me-2"></i><br>
            <strong>Take Attendance</strong><br>
            <small class="opacity-75">Mark today's attendance</small>
        </a>
    </div>
    <div class="col-md-6">
        <a href="view_attendance.php" class="btn btn-outline-primary btn-lg w-100 py-3">
            <i class="bi bi-table fs-4 me-2"></i><br>
            <strong>View Records</strong><br>
            <small class="opacity-75">Filter and export</small>
        </a>
    </div>
</div>

<!-- Recent sessions -->
<div class="card">
    <div class="card-header"><i class="bi bi-clock-history me-2"></i>Recent Attendance Sessions</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="attendance-table">
                <tr>
                    <th>Date</th><th>Subject</th>
                    <th><span class="badge bg-success">Present</span></th>
                    <th><span class="badge bg-danger">Absent</span></th>
                    <th>% Present</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($recent)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No attendance records yet.</td></tr>
            <?php else: ?>
                <?php foreach ($recent as $r):
                    $tot = $r['present_count'] + $r['absent_count'];
                    $p   = $tot > 0 ? round(($r['present_count']/$tot)*100) : 0;
                ?>
                <tr>
                    <td><?= date('d M Y', strtotime($r['date'])) ?></td>
                    <td><span class="badge bg-primary"><?= htmlspecialchars($r['subject']) ?></span></td>
                    <td class="text-success fw-bold"><?= $r['present_count'] ?></td>
                    <td class="text-danger fw-bold"><?= $r['absent_count'] ?></td>
                    <td>
                        <div class="progress" style="height:8px;width:80px">
                            <div class="progress-bar bg-<?= $p>=75?'success':($p>=50?'warning':'danger') ?>"
                                 style="width:<?= $p ?>%"></div>
                        </div>
                        <small><?= $p ?>%</small>
                    </td>
                    <td>
                        <a href="view_attendance.php?date=<?= $r['date'] ?>&subject=<?= urlencode($r['subject']) ?>"
                           class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php else: ?>
<!-- ══════════════════ STUDENT DASHBOARD ══════════════════ -->

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card p-3 text-center">
            <div class="fs-2 fw-bold text-primary"><?= $total ?></div>
            <div class="text-muted small">Total Classes</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 text-center" style="border-color:#28a745">
            <div class="fs-2 fw-bold text-success"><?= $present ?></div>
            <div class="text-muted small">Present</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 text-center" style="border-color:#dc3545">
            <div class="fs-2 fw-bold text-danger"><?= $absent ?></div>
            <div class="text-muted small">Absent</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 text-center" style="border-color:<?= $pct>=75?'#28a745':'#dc3545' ?>">
            <div class="fs-2 fw-bold text-<?= $pct>=75?'success':'danger' ?>"><?= $pct ?>%</div>
            <div class="text-muted small">Attendance %</div>
        </div>
    </div>
</div>

<!-- Attendance % bar -->
<div class="card mb-4 p-3">
    <div class="d-flex justify-content-between mb-1">
        <span class="fw-semibold">Overall Attendance</span>
        <span class="fw-bold <?= $pct>=75?'text-success':'text-danger' ?>"><?= $pct ?>%
            <?= $pct >= 75 ? '✅' : '⚠️ Below 75% threshold!' ?>
        </span>
    </div>
    <div class="progress" style="height:16px">
        <div class="progress-bar bg-<?= $pct>=75?'success':'danger' ?>"
             style="width:<?= $pct ?>%;font-size:.75rem;line-height:16px">
             <?= $pct ?>%
        </div>
    </div>
    <?php if ($pct < 75 && $total > 0):
        $needed = ceil(0.75 * $total - $present);
    ?>
    <small class="text-danger mt-1 d-block">
        ⚠️ You need to attend <?= $needed ?> more consecutive classes to reach 75%.
    </small>
    <?php endif; ?>
</div>

<!-- Recent records -->
<div class="card">
    <div class="card-header"><i class="bi bi-calendar-event me-2"></i>Recent Attendance</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead><tr><th>Date</th><th>Subject</th><th>Status</th></tr></thead>
            <tbody>
            <?php if (empty($recent)): ?>
                <tr><td colspan="3" class="text-center text-muted py-4">No attendance records yet.</td></tr>
            <?php else: ?>
                <?php foreach ($recent as $r): ?>
                <tr>
                    <td><?= date('d M Y', strtotime($r['date'])) ?></td>
                    <td><?= htmlspecialchars($r['subject']) ?></td>
                    <td>
                        <span class="badge badge-<?= $r['status'] ?> px-3 py-2">
                            <?= $r['status'] === 'present' ? '✅ Present' : '❌ Absent' ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">
    <a href="my_attendance.php" class="btn btn-primary">
        <i class="bi bi-calendar-check me-1"></i> View Full Attendance
    </a>
</div>

<?php endif; ?>

<?php pageFooter(); ?>
