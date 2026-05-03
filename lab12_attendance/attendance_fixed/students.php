<?php
/**
 * students.php – Teacher views all registered students
 */
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/layout.php';
requireRole('teacher');

$students = $conn->query(
    "SELECT u.*,
            COUNT(a.id)                 as total_classes,
            SUM(a.status='present')     as present_count
     FROM users u
     LEFT JOIN attendance a ON a.student_id = u.id
     WHERE u.role = 'student'
     GROUP BY u.id
     ORDER BY u.roll_no"
)->fetch_all(MYSQLI_ASSOC);

pageHeader('Students', 'students');
?>

<h4 class="mb-4"><i class="bi bi-people me-2"></i>Registered Students (<?= count($students) ?>)</h4>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 attendance-table">
            <thead>
                <tr>
                    <th>#</th><th>Roll No</th><th>Name</th>
                    <th>Email</th><th>Department</th>
                    <th>Classes</th><th>Present</th><th>%</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">No students yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($students as $i => $s):
                        $pct = $s['total_classes'] > 0
                            ? round(($s['present_count']/$s['total_classes'])*100) : 0;
                    ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><code class="text-primary fw-bold"><?= htmlspecialchars($s['roll_no']) ?></code></td>
                        <td><strong><?= htmlspecialchars($s['name']) ?></strong></td>
                        <td><small><?= htmlspecialchars($s['email']) ?></small></td>
                        <td><small><?= htmlspecialchars($s['department']) ?></small></td>
                        <td><?= $s['total_classes'] ?></td>
                        <td class="text-success"><?= $s['present_count'] ?></td>
                        <td>
                            <span class="badge bg-<?= $pct>=75?'success':'danger' ?>">
                                <?= $pct ?>%
                            </span>
                        </td>
                        <td><small class="text-muted"><?= date('d M Y', strtotime($s['created_at'])) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php pageFooter(); ?>
