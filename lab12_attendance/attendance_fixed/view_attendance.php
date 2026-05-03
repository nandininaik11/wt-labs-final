<?php
/**
 * view_attendance.php – Filter and view attendance records (teacher)
 */
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/layout.php';
requireRole('teacher');

$filterDate    = $_GET['date']      ?? '';
$filterSubject = $_GET['subject']   ?? '';
$filterStudent = intval($_GET['student_id'] ?? 0);

// Build query
$where  = ['1=1'];
$params = [];
$types  = '';

if ($filterDate) {
    $where[] = 'a.date = ?';
    $params[] = $filterDate; $types .= 's';
}
if ($filterSubject) {
    $where[] = 'a.subject = ?';
    $params[] = $filterSubject; $types .= 's';
}
if ($filterStudent) {
    $where[] = 'a.student_id = ?';
    $params[] = $filterStudent; $types .= 'i';
}

$sql = "SELECT a.*, u.name, u.roll_no, u.department
        FROM attendance a
        JOIN users u ON u.id = a.student_id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY a.date DESC, u.roll_no ASC";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Dropdowns
$allSubjects = $conn->query("SELECT DISTINCT subject FROM attendance ORDER BY subject")->fetch_all(MYSQLI_ASSOC);
$allStudents = $conn->query("SELECT id, name, roll_no FROM users WHERE role='student' ORDER BY roll_no")->fetch_all(MYSQLI_ASSOC);

// Stats for filtered result
$presentCount = count(array_filter($records, fn($r) => $r['status'] === 'present'));
$absentCount  = count($records) - $presentCount;

pageHeader('View Attendance', 'view_attendance');
echo showFlash();
?>

<h4 class="mb-4"><i class="bi bi-table me-2"></i>Attendance Records</h4>

<!-- ── Filters ─────────────────────────────────────────────── -->
<div class="card mb-4">
    <div class="card-header"><i class="bi bi-funnel me-2"></i>Filter Records</div>
    <div class="card-body">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">📅 Date</label>
                <input type="date" name="date" class="form-control"
                       value="<?= htmlspecialchars($filterDate) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">📚 Subject</label>
                <select name="subject" class="form-select">
                    <option value="">All Subjects</option>
                    <?php foreach ($allSubjects as $s): ?>
                        <option value="<?= htmlspecialchars($s['subject']) ?>"
                            <?= $filterSubject === $s['subject'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['subject']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">👤 Student</label>
                <select name="student_id" class="form-select">
                    <option value="">All Students</option>
                    <?php foreach ($allStudents as $s): ?>
                        <option value="<?= $s['id'] ?>"
                            <?= $filterStudent === $s['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['roll_no'] . ' – ' . $s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="view_attendance.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- ── Summary Stats ───────────────────────────────────────── -->
<?php if (!empty($records)): ?>
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card p-3 text-center stat-card">
            <div class="fs-3 fw-bold"><?= count($records) ?></div>
            <small class="text-muted">Total Records</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center stat-card" style="border-color:#28a745">
            <div class="fs-3 fw-bold text-success"><?= $presentCount ?></div>
            <small class="text-muted">Present</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center stat-card" style="border-color:#dc3545">
            <div class="fs-3 fw-bold text-danger"><?= $absentCount ?></div>
            <small class="text-muted">Absent</small>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ── Records Table ───────────────────────────────────────── -->
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-list-check me-2"></i>
            <?= count($records) ?> record(s) found
        </span>
        <?php if (!empty($records) && $filterDate && $filterSubject): ?>
        <a href="take_attendance.php?date=<?= $filterDate ?>&subject=<?= urlencode($filterSubject) ?>"
           class="btn btn-sm btn-warning">
            <i class="bi bi-pencil"></i> Edit This Session
        </a>
        <?php endif; ?>
    </div>
    <div class="card-body p-0">
        <?php if (empty($records)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1"></i><br>
                No records found. Use the filters above or
                <a href="take_attendance.php">take attendance</a>.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0 attendance-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Roll No</th>
                        <th>Student Name</th>
                        <th>Department</th>
                        <th>Date</th>
                        <th>Subject</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $i => $r): ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td><code class="text-primary"><?= htmlspecialchars($r['roll_no']) ?></code></td>
                        <td><strong><?= htmlspecialchars($r['name']) ?></strong></td>
                        <td><small class="text-muted"><?= htmlspecialchars($r['department']) ?></small></td>
                        <td><?= date('d M Y', strtotime($r['date'])) ?></td>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($r['subject']) ?></span></td>
                        <td>
                            <span class="badge badge-<?= $r['status'] ?> px-3 py-2 fs-6">
                                <?= $r['status'] === 'present' ? '✅ Present' : '❌ Absent' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php pageFooter(); ?>
