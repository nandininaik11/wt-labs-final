<?php
/**
 * index.php  –  Login page
 * Lab Q11: Limit max 3 concurrent sessions per user, 5-min timeout
 */
require_once __DIR__ . '/includes/SessionManager.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');      // Simplified: no real auth needed for lab

    // Basic validation
    if (empty($username) || empty($password)) {
        $message = '⚠️ Please enter both username and password.';
        $messageType = 'warning';
    } else {
        // Attempt to create a session (enforces max 3 concurrent sessions)
        $result = SessionManager::createSession($username);
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'danger';

        if ($result['success']) {
            // Redirect to dashboard after 1.5 seconds
            header("Refresh: 1; url=dashboard.php");
        }
    }
}

// Gather live session data for the demo status panel
$allSessions = SessionManager::getAllSessions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Q11 – Session Manager Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f0f4f8; }
        .card  { border-radius: 12px; box-shadow: 0 4px 18px rgba(0,0,0,.12); }
        .badge-session { font-size: .78rem; }
        pre { background:#1e1e1e; color:#dcdcdc; border-radius:8px; padding:14px;
              font-size:.82rem; overflow-x:auto; }
        .session-row { border-left: 4px solid #0d6efd; margin-bottom:6px; }
        .timeout-bar { height:6px; border-radius:3px; background:#e9ecef; }
        .timeout-fill { height:100%; border-radius:3px; background:#0d6efd; transition:width .5s; }
    </style>
</head>
<body>
<div class="container py-5">

    <!-- ── Header ───────────────────────────────────────────────── -->
    <div class="text-center mb-4">
        <h2 class="fw-bold">🔐 PHP Session Manager</h2>
        <p class="text-muted">Lab Q11 – Max 3 concurrent sessions · 5-minute timeout</p>
    </div>

    <div class="row g-4 justify-content-center">

        <!-- ── Login Card ─────────────────────────────────────── -->
        <div class="col-md-5">
            <div class="card p-4">
                <h5 class="mb-3">Login</h5>

                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?> py-2"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control"
                               placeholder="e.g. alice, bob, charlie"
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                        <div class="form-text">Try the same username 4 times to see the limit!</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Any password (lab demo)" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login →</button>
                </form>

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">📊 Dashboard</a>
                    <a href="admin.php"     class="btn btn-sm btn-outline-danger">🛠 Admin View</a>
                </div>
            </div>
        </div>

        <!-- ── Live Session Status ────────────────────────────── -->
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3">📡 Live Session Status
                    <span class="badge bg-secondary float-end" style="font-size:.75rem">
                        Auto-refreshes every 10s
                    </span>
                </h5>

                <?php if (empty($allSessions)): ?>
                    <p class="text-muted">No active sessions. Be the first to login!</p>
                <?php else: ?>
                    <?php foreach ($allSessions as $user => $sessions): ?>
                        <div class="mb-3">
                            <strong>👤 <?= htmlspecialchars($user) ?></strong>
                            <span class="badge bg-<?= count($sessions) >= 3 ? 'danger' : 'success' ?> ms-2 badge-session">
                                <?= count($sessions) ?> / <?= SessionManager::MAX_SESSIONS ?> sessions
                            </span>
                            <?php foreach ($sessions as $idx => $s): ?>
                                <?php
                                    $age     = time() - $s['last_active'];
                                    $remain  = max(0, SessionManager::SESSION_TIMEOUT - $age);
                                    $pct     = round(($remain / SessionManager::SESSION_TIMEOUT) * 100);
                                ?>
                                <div class="session-row ps-2 mt-1 py-1">
                                    <small class="text-muted">
                                        Session <?= $idx + 1 ?> &bull;
                                        ID: <code><?= substr($s['session_id'], 0, 8) ?>…</code> &bull;
                                        IP: <?= htmlspecialchars($s['ip']) ?> &bull;
                                        Expires in: <strong><?= $remain ?>s</strong>
                                    </small>
                                    <div class="timeout-bar mt-1">
                                        <div class="timeout-fill" style="width:<?= $pct ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- ── Key Config Box ─────────────────────────────── -->
            <div class="card p-3 mt-3 bg-dark text-light">
                <h6 class="text-warning">⚙️ Key Configuration (SessionManager.php)</h6>
                <pre class="mb-0">const MAX_SESSIONS   = 3;    // Max concurrent
const SESSION_TIMEOUT = 300; // 5 min (seconds)</pre>
            </div>
        </div>

    </div><!-- /row -->
</div>

<script>
    // Auto-refresh page every 10 seconds so session timers update
    setTimeout(() => location.reload(), 10000);
</script>
</body>
</html>
