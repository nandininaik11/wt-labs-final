<?php
/**
 * my_attendance.php – Student views their own full attendance history
 */
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/layout.php';
requireRole('student');

$uid = $_SESSION['user_id'];

// Full attendance history
$stmt = $conn->prepare(
    "SELECT date, subject, status FROM attendance
     WHERE student_id = ? ORDER BY date DESC, subject"
);
$stmt->bind_param("i", $uid);
$stmt->execute();
$records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Per-subject stats
$stmt = $conn->prepare(
    "SELECT subject,
            COUNT(*) as total,
            SUM(status='present') as present_c,
            SUM(status='absent')  as absent_c
     FROM attendance WHERE student_id = ?
     GROUP BY subject ORDER BY subject"
);
$stmt->bind_param("i", $uid);
$stmt->execute();
$subjectStats = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

pageHeader('My Attendance', 'my_attendance');
?>

<h4 class="mb-4"><i class="bi bi-calendar-check me-2"></i>My Attendance</h4>

<!-- Per-subject summary cards -->
<?php if (!empty($subjectStats)): ?>
<div class="row g-3 mb-4">
    <?php foreach ($subjectStats as $s):
        $pct = $s['total'] > 0 ? round(($s['present_c']/$s['total'])*100) : 0;
    ?>
    <div class="col-md-4">
        <div class="card p-3 stat-card" style="border-color:<?= $pct>=75?'#28a745':'#dc3545' ?>">
            <div class="fw-bold"><?= htmlspecialchars($s['subject']) ?></div>
            <div class="progress my-2" style="height:8px">
                <div class="progress-bar bg-<?= $pct>=75?'success':'danger' ?>"
                     style="width:<?= $pct ?>%"></div>
            </div>
            <small class="text-muted">
                <?= $s['present_c'] ?>/<?= $s['total'] ?> classes &nbsp;|&nbsp;
                <strong class="text-<?= $pct>=75?'success':'danger' ?>"><?= $pct ?>%</strong>
            </small>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Full history table -->
<div class="card">
    <div class="card-header"><i class="bi bi-clock-history me-2"></i>Full Attendance History</div>
    <div class="card-body p-0">
        <?php if (empty($records)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1"></i><br>No attendance records yet.
            </div>
        <?php else: ?>
        <table class="table table-hover mb-0 attendance-table">
            <thead><tr><th>#</th><th>Date</th><th>Subject</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($records as $i => $r): ?>
                <tr class="<?= $r['status']==='present'?'table-success':'table-danger' ?>"
                    style="--bs-table-accent-bg:transparent">
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td><?= date('d M Y, D', strtotime($r['date'])) ?></td>
                    <td><span class="badge bg-primary"><?= htmlspecialchars($r['subject']) ?></span></td>
                    <td>
                        <span class="badge badge-<?= $r['status'] ?> px-3 py-2">
                            <?= $r['status'] === 'present' ? '✅ Present' : '❌ Absent' ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php pageFooter(); ?>
