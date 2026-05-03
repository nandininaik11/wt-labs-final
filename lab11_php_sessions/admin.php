<?php
/**
 * admin.php – View all active sessions across all users
 */
require_once __DIR__ . '/includes/SessionManager.php';

// Handle force-clear all sessions
if (isset($_GET['clear_all'])) {
    SessionManager::saveSessions([]);
    header('Location: admin.php?cleared=1');
    exit;
}

$allSessions = SessionManager::getAllSessions();
$total = array_sum(array_map('count', $allSessions));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin – Session Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #1a1a2e; color: #eaeaea; }
        .card { background: #16213e; border: 1px solid #0f3460; border-radius: 12px; }
        .table { color: #eaeaea; }
        .table th { color: #7ec8e3; border-color: #0f3460; }
        .table td { border-color: #0f3460; }
        code { color: #ffd460; }
        pre { background: #0f3460; color: #dcdcdc; border-radius: 8px;
              padding: 16px; font-size: .82rem; }
        .badge-limit { font-size: .8rem; }
        h2, h5 { color: #7ec8e3; }
    </style>
</head>
<body>
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>🛠 Admin Panel – Session Manager</h2>
            <p class="text-muted mb-0">Lab Q11 · All active sessions across all users</p>
        </div>
        <div>
            <a href="index.php" class="btn btn-outline-light me-2">← Login</a>
            <a href="?clear_all=1"
               onclick="return confirm('Clear ALL sessions?')"
               class="btn btn-outline-danger">🗑 Clear All</a>
        </div>
    </div>

    <?php if (isset($_GET['cleared'])): ?>
        <div class="alert alert-success">All sessions cleared.</div>
    <?php endif; ?>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <h4 class="mb-0 text-warning"><?= count($allSessions) ?></h4>
                <small class="text-muted">Active Users</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <h4 class="mb-0 text-info"><?= $total ?></h4>
                <small class="text-muted">Total Sessions</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <h4 class="mb-0 text-success"><?= SessionManager::MAX_SESSIONS ?></h4>
                <small class="text-muted">Max Per User</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <h4 class="mb-0 text-danger"><?= SessionManager::SESSION_TIMEOUT ?>s</h4>
                <small class="text-muted">Timeout (5 min)</small>
            </div>
        </div>
    </div>

    <!-- Session Table -->
    <div class="card p-4">
        <h5 class="mb-3">🔑 Session Details</h5>

        <?php if (empty($allSessions)): ?>
            <p class="text-muted">No active sessions found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>#</th>
                            <th>Session ID</th>
                            <th>IP Address</th>
                            <th>Created</th>
                            <th>Last Active</th>
                            <th>Expires In</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allSessions as $user => $sessions): ?>
                            <?php foreach ($sessions as $i => $s): ?>
                                <?php
                                    $age    = time() - $s['last_active'];
                                    $remain = max(0, SessionManager::SESSION_TIMEOUT - $age);
                                    $pct    = round(($remain / SessionManager::SESSION_TIMEOUT) * 100);
                                ?>
                                <tr>
                                    <?php if ($i === 0): ?>
                                        <td rowspan="<?= count($sessions) ?>" class="align-middle">
                                            <strong><?= htmlspecialchars($user) ?></strong>
                                            <span class="badge bg-<?= count($sessions) >= 3 ? 'danger' : 'success' ?> badge-limit ms-1">
                                                <?= count($sessions) ?>/3
                                            </span>
                                        </td>
                                    <?php endif; ?>
                                    <td><?= $i + 1 ?></td>
                                    <td><code><?= substr($s['session_id'], 0, 10) ?>…</code></td>
                                    <td><?= htmlspecialchars($s['ip']) ?></td>
                                    <td><small><?= date('H:i:s', $s['created_at']) ?></small></td>
                                    <td><small><?= date('H:i:s', $s['last_active']) ?></small></td>
                                    <td>
                                        <div class="progress" style="height:6px;width:80px;display:inline-block;vertical-align:middle">
                                            <div class="progress-bar bg-<?= $pct > 50 ? 'success' : ($pct > 20 ? 'warning' : 'danger') ?>"
                                                 style="width:<?= $pct ?>%"></div>
                                        </div>
                                        <small class="ms-1"><?= $remain ?>s</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $remain > 60 ? 'success' : 'danger' ?>">
                                            <?= $remain > 0 ? 'Active' : 'Expired' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Raw JSON -->
    <div class="card p-4 mt-4">
        <h5>📄 Raw Session Store (data/sessions.json)</h5>
        <pre><?= htmlspecialchars(json_encode($allSessions, JSON_PRETTY_PRINT)) ?></pre>
    </div>

</div>

<script>
    setTimeout(() => location.reload(), 10000);
</script>
</body>
</html>
