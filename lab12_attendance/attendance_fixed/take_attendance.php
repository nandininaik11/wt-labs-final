<?php
/**
 * take_attendance.php – Teacher marks attendance using checkboxes
 * Lab Q12 Core Feature: Roll No + Name + Checkboxes
 */
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/layout.php';
requireRole('teacher');

$uid       = $_SESSION['user_id'];
$today     = date('Y-m-d');
$message   = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_attendance'])) {
    $date         = $_POST['att_date']    ?? $today;
    $subject      = trim($_POST['subject'] ?? 'General');
    $studentIds   = array_map('intval', $_POST['students'] ?? []);  // all student IDs
    $presentIds   = array_map('intval', $_POST['present']  ?? []);  // checked = present

    if (empty($subject)) {
        flash("Subject is required.", 'error');
    } elseif (empty($studentIds)) {
        flash("No students found.", 'error');
    } else {
        $saved = 0;
        foreach ($studentIds as $sid) {
            $status = in_array($sid, $presentIds) ? 'present' : 'absent';

            // INSERT or UPDATE if already marked
            $stmt = $conn->prepare(
                "INSERT INTO attendance (student_id, date, status, subject, marked_by)
                 VALUES (?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE status = VALUES(status), marked_by = VALUES(marked_by)"
            );
            $stmt->bind_param("isssi", $sid, $date, $status, $subject, $uid);
            $stmt->execute();
            $stmt->close();
            $saved++;
        }
        $pc = count($presentIds);
        $ac = $saved - $pc;
        flash("✅ Attendance saved! $pc Present, $ac Absent for '$subject' on " . date('d M Y', strtotime($date)), 'success');
        header("Location: view_attendance.php?date=$date&subject=" . urlencode($subject));
        exit;
    }
}

// Load all students
$students = $conn->query(
    "SELECT id, name, roll_no, department FROM users
     WHERE role = 'student' ORDER BY roll_no ASC"
)->fetch_all(MYSQLI_ASSOC);

// Pre-fill if date/subject provided (editing mode)
$filterDate    = $_GET['date']    ?? $today;
$filterSubject = $_GET['subject'] ?? '';

// Load existing attendance for pre-ticking checkboxes
$existingPresent = [];
if ($filterSubject) {
    $stmt = $conn->prepare(
        "SELECT student_id FROM attendance WHERE date = ? AND subject = ? AND status = 'present'"
    );
    $stmt->bind_param("ss", $filterDate, $filterSubject);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $existingPresent = array_column($rows, 'student_id');
    $stmt->close();
}

// Get distinct subjects for dropdown
$subjects = $conn->query("SELECT DISTINCT subject FROM attendance ORDER BY subject")->fetch_all(MYSQLI_ASSOC);
$subjectList = array_column($subjects, 'subject');
if (!in_array('Web Technology', $subjectList)) array_unshift($subjectList, 'Web Technology');
if (!in_array('General',        $subjectList)) array_unshift($subjectList, 'General');
$subjectList = array_unique($subjectList);

pageHeader('Take Attendance', 'take_attendance');
echo showFlash();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-check2-square me-2"></i>Take Attendance</h4>
    <a href="view_attendance.php" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-table"></i> View Records
    </a>
</div>

<?php if (empty($students)): ?>
    <div class="alert alert-warning">
        No students registered yet.
        <a href="register.php">Register students</a> first.
    </div>
<?php else: ?>

<form method="POST" action="" id="attendanceForm">

    <!-- ── Configuration Row ──────────────────────────────── -->
    <div class="card mb-4">
        <div class="card-header"><i class="bi bi-gear me-2"></i>Session Details</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">📅 Date *</label>
                    <input type="date" name="att_date" id="att_date" class="form-control"
                           value="<?= htmlspecialchars($filterDate) ?>" max="<?= $today ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">📚 Subject *</label>
                    <input type="text" name="subject" id="subject" class="form-control"
                           value="<?= htmlspecialchars($filterSubject ?: 'Web Technology') ?>"
                           list="subject-list" placeholder="Type or select subject" required>
                    <datalist id="subject-list">
                        <?php foreach ($subjectList as $s): ?>
                            <option value="<?= htmlspecialchars($s) ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-secondary w-100"
                            onclick="loadExisting()">
                        <i class="bi bi-arrow-clockwise"></i> Load Existing
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Attendance Table ───────────────────────────────── -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-people me-2"></i>Students (<?= count($students) ?> total)</span>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-success" onclick="markAll(true)">
                    ✅ Mark All Present
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="markAll(false)">
                    ❌ Mark All Absent
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 attendance-table">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th style="width:120px">Roll No</th>
                        <th>Student Name</th>
                        <th>Department</th>
                        <th style="width:100px" class="text-center">
                            Present
                            <br><small class="fw-normal opacity-75">(✓ = Present)</small>
                        </th>
                        <th style="width:100px" class="text-center">
                            Absent
                            <br><small class="fw-normal opacity-75">(✓ = Absent)</small>
                        </th>
                    </tr>
                </thead>
                <tbody id="studentTable">
                    <?php foreach ($students as $i => $s): ?>
                    <?php
                        $isPresent = !empty($existingPresent)
                            ? in_array($s['id'], $existingPresent)
                            : true; // default: all present
                    ?>
                    <tr id="row-<?= $s['id'] ?>">
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td><code class="text-primary fw-bold"><?= htmlspecialchars($s['roll_no']) ?></code></td>
                        <td>
                            <strong><?= htmlspecialchars($s['name']) ?></strong>
                            <!-- hidden field so all student IDs are submitted -->
                            <input type="hidden" name="students[]" value="<?= $s['id'] ?>">
                        </td>
                        <td><small class="text-muted"><?= htmlspecialchars($s['department']) ?></small></td>

                        <!-- Present checkbox -->
                        <td class="text-center">
                            <input type="checkbox"
                                   class="check-present present-cb"
                                   name="present[]"
                                   value="<?= $s['id'] ?>"
                                   id="p-<?= $s['id'] ?>"
                                   <?= $isPresent ? 'checked' : '' ?>
                                   onchange="syncAbsent(<?= $s['id'] ?>)">
                        </td>

                        <!-- Absent indicator (inverse of present) -->
                        <td class="text-center">
                            <input type="checkbox"
                                   class="check-absent absent-cb"
                                   id="a-<?= $s['id'] ?>"
                                   <?= !$isPresent ? 'checked' : '' ?>
                                   onchange="syncPresent(<?= $s['id'] ?>)">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer: Live counter + Submit -->
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-success fs-6 me-2">
                    ✅ Present: <span id="presentCount">0</span>
                </span>
                <span class="badge bg-danger fs-6">
                    ❌ Absent: <span id="absentCount">0</span>
                </span>
            </div>
            <button type="submit" name="submit_attendance" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-save me-2"></i>Save Attendance
            </button>
        </div>
    </div>

</form>

<?php endif; ?>

<script>
// ── Sync present ↔ absent checkboxes ─────────────────────────
function syncAbsent(id) {
    const pc = document.getElementById('p-' + id);
    const ac = document.getElementById('a-' + id);
    ac.checked = !pc.checked;
    updateRow(id, pc.checked);
    updateCounter();
}
function syncPresent(id) {
    const pc = document.getElementById('p-' + id);
    const ac = document.getElementById('a-' + id);
    pc.checked = !ac.checked;
    updateRow(id, pc.checked);
    updateCounter();
}
function updateRow(id, isPresent) {
    const row = document.getElementById('row-' + id);
    row.style.background = isPresent ? '#f0fff4' : '#fff5f5';
}
function markAll(present) {
    document.querySelectorAll('.present-cb').forEach(cb => {
        cb.checked = present;
        const id = cb.value;
        document.getElementById('a-' + id).checked = !present;
        updateRow(id, present);
    });
    updateCounter();
}
function updateCounter() {
    const p = document.querySelectorAll('.present-cb:checked').length;
    const t = document.querySelectorAll('.present-cb').length;
    document.getElementById('presentCount').textContent = p;
    document.getElementById('absentCount').textContent  = t - p;
}
function loadExisting() {
    const date    = document.getElementById('att_date').value;
    const subject = document.getElementById('subject').value;
    if (!date || !subject) { alert('Select date and subject first.'); return; }
    window.location = 'take_attendance.php?date=' + date + '&subject=' + encodeURIComponent(subject);
}
// Initial counter update + row colours
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.present-cb').forEach(cb => {
        updateRow(cb.value, cb.checked);
    });
    updateCounter();
});
</script>

<?php pageFooter(); ?>
