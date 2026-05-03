<?php
/**
 * dashboard.php – Shown after successful login
 */
require_once __DIR__ . '/includes/SessionManager.php';

session_start();

$username  = $_SESSION['username']  ?? null;
$sessionId = $_SESSION['session_id'] ?? null;

// If not logged in, redirect
if (!$username || !$sessionId) {
    header('Location: index.php');
    exit;
}

// Check session timeout
$lastActive = $_SESSION['last_active'] ?? 0;
if ((time() - $lastActive) >= SessionManager::SESSION_TIMEOUT) {
    SessionManager::destroySession($username, $sessionId);
    header('Location: index.php?msg=timeout');
    exit;
}

// Refresh last_active
$_SESSION['last_active'] = time();
SessionManager::touch($username, $sessionId);

// Handle logout
if (isset($_GET['logout'])) {
    SessionManager::destroySession($username, $sessionId);
    header('Location: index.php?msg=logout');
    exit;
}

$userSessions = SessionManager::getUserSessions($username);
$timeLeft     = SessionManager::SESSION_TIMEOUT - (time() - $lastActive);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – <?= htmlspecialchars($username) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f0f4f8; }
        .card { border-radius: 12px; box-shadow: 0 4px 18px rgba(0,0,0,.1); }
        .session-badge { border-left: 4px solid #198754; padding-left: 10px; }
        #countdown { font-size: 1.8rem; font-weight: bold; color: #0d6efd; }
        .progress { height: 10px; border-radius: 5px; }
    </style>
</head>
<body>
<div class="container py-5">

    <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3>👋 Welcome, <strong><?= htmlspecialchars($username) ?></strong>!</h3>
                <p class="text-muted mb-0">
                    Session ID: <code><?= htmlspecialchars($sessionId) ?></code>
                </p>
            </div>
            <a href="?logout=1" class="btn btn-danger">🚪 Logout</a>
        </div>
    </div>

    <div class="row g-4">

        <!-- Session Timer -->
        <div class="col-md-5">
            <div class="card p-4 text-center">
                <h5>⏱ Session Timeout</h5>
                <div id="countdown"><?= $timeLeft ?>s</div>
                <p class="text-muted small">5-minute session expiry</p>
                <div class="progress mb-3">
                    <div id="progress-bar" class="progress-bar bg-success"
                         role="progressbar"
                         style="width:<?= round(($timeLeft/300)*100) ?>%"></div>
                </div>
                <button class="btn btn-outline-primary btn-sm" onclick="keepAlive()">
                    🔄 Keep Session Alive
                </button>
                <small class="d-block text-muted mt-2">
                    Clicking any page action also resets the timer.
                </small>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="col-md-7">
            <div class="card p-4">
                <h5>📋 Your Active Sessions
                    <span class="badge bg-<?= count($userSessions) >= 3 ? 'danger' : 'primary' ?> ms-2">
                        <?= count($userSessions) ?> / <?= SessionManager::MAX_SESSIONS ?>
                    </span>
                </h5>

                <?php if (empty($userSessions)): ?>
                    <p class="text-muted">No active sessions found.</p>
                <?php else: ?>
                    <?php foreach ($userSessions as $i => $s): ?>
                        <?php
                            $age    = time() - $s['last_active'];
                            $remain = max(0, SessionManager::SESSION_TIMEOUT - $age);
                            $isCurrent = $s['session_id'] === $sessionId;
                        ?>
                        <div class="session-badge mb-2 <?= $isCurrent ? 'border-primary' : '' ?>">
                            <strong>Session <?= $i + 1 ?></strong>
                            <?php if ($isCurrent): ?>
                                <span class="badge bg-primary ms-1">← This session</span>
                            <?php endif; ?>
                            <br>
                            <small class="text-muted">
                                ID: <code><?= substr($s['session_id'], 0, 12) ?>…</code><br>
                                IP: <?= htmlspecialchars($s['ip']) ?><br>
                                Expires in: <strong><?= $remain ?>s</strong>
                            </small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (count($userSessions) >= SessionManager::MAX_SESSIONS): ?>
                    <div class="alert alert-danger py-2 mt-2 mb-0">
                        ⚠️ Maximum session limit reached. New logins will be denied.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div><!-- /row -->

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-outline-secondary me-2">← Back to Login</a>
        <a href="admin.php" class="btn btn-outline-danger">🛠 Admin Panel</a>
    </div>

</div>

<script>
let timeLeft = <?= $timeLeft ?>;
const total  = <?= SessionManager::SESSION_TIMEOUT ?>;

const cd  = document.getElementById('countdown');
const bar = document.getElementById('progress-bar');

const timer = setInterval(() => {
    timeLeft--;
    if (timeLeft <= 0) {
        clearInterval(timer);
        alert('⏰ Session expired! You will be logged out.');
        window.location = 'index.php?msg=timeout';
        return;
    }
    cd.textContent = timeLeft + 's';
    const pct = Math.round((timeLeft / total) * 100);
    bar.style.width = pct + '%';
    bar.className = 'progress-bar ' + (pct > 50 ? 'bg-success' : pct > 20 ? 'bg-warning' : 'bg-danger');
}, 1000);

function keepAlive() {
    fetch('keepalive.php')
        .then(r => r.json())
        .then(d => {
            if (d.ok) {
                timeLeft = <?= SessionManager::SESSION_TIMEOUT ?>;
                alert('✅ Session refreshed! Timer reset to 5 minutes.');
            }
        });
}
</script>
</body>
</html>
