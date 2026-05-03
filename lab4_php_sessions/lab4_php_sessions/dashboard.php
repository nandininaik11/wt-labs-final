<?php
// ============================================================
// THEORY: SESSION PROTECTION (Authentication Guard)
// Every protected page must check if user is logged in
// This prevents direct URL access without logging in
// ============================================================
session_start(); // Reconnect to the existing session

// Check if the user is NOT logged in
// If not logged in, redirect to login page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // THEORY: header("Location:") = HTTP 302 Redirect
    // This tells the browser: "Go to this URL instead"
    header("Location: index.php?error=unauthorized");
    exit(); // Always exit after redirect — prevents code below from running!
}

// Safely retrieve session variables using null coalescing operator (??)
// THEORY: ?? means "use this value if the left side is null/unset"
$username    = $_SESSION['username']    ?? 'Guest';
$email       = $_SESSION['email']       ?? 'N/A';
$login_time  = $_SESSION['login_time']  ?? 'Unknown';
$method_used = $_SESSION['method_used'] ?? 'Unknown';
$cookie_set  = $_SESSION['cookie_was_set'] ?? false;

// Check if cookie exists in browser
// THEORY: $_COOKIE is a superglobal containing all cookies sent by browser
$cookie_value = isset($_COOKIE['username']) ? $_COOKIE['username'] : null;

// Generate unique Session ID for display
// THEORY: session_id() returns the current session identifier string
$session_id = session_id();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Welcome <?= htmlspecialchars($username) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- THEORY: PHP can output dynamic HTML using variables from session -->
<div class="container mt-4">

    <!-- Success Alert -->
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h4>✅ Login Successful! Welcome, <strong><?= htmlspecialchars($username) ?></strong>!</h4>
        <p class="mb-0">You are now viewing a SESSION-PROTECTED page.</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <div class="row">

        <!-- SESSION INFO CARD -->
        <div class="col-md-6 mb-3">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5>🖥️ SESSION Data (Server-Side)</h5>
                </div>
                <div class="card-body">
                    <!-- THEORY: Sessions are stored on SERVER, not browser -->
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th>Variable</th>
                            <th>Value</th>
                        </tr>
                        <tr>
                            <td><code>$_SESSION['username']</code></td>
                            <td><strong><?= htmlspecialchars($username) ?></strong></td>
                        </tr>
                        <tr>
                            <td><code>$_SESSION['email']</code></td>
                            <td><?= htmlspecialchars($email) ?></td>
                        </tr>
                        <tr>
                            <td><code>$_SESSION['login_time']</code></td>
                            <td><?= $login_time ?></td>
                        </tr>
                        <tr>
                            <td><code>$_SESSION['method_used']</code></td>
                            <td>
                                <!-- Color-code GET vs POST -->
                                <?php if ($method_used === 'POST'): ?>
                                    <span class="badge bg-success">POST</span> ✅ Secure
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">GET</span> ⚠️ Visible in URL
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><code>session_id()</code></td>
                            <!-- substr shows only first 20 chars of long session ID -->
                            <td><code class="text-muted small"><?= substr($session_id, 0, 20) ?>...</code></td>
                        </tr>
                    </table>

                    <!-- THEORY: Explain what session_id is -->
                    <div class="alert alert-info small p-2 mt-2">
                        <strong>How sessions work:</strong> PHP creates a unique ID (like a locker key),
                        stores your data on the server, and gives the browser just the ID as a cookie called
                        <code>PHPSESSID</code>. Every request sends this ID back, and PHP retrieves your data.
                    </div>
                </div>
            </div>
        </div>

        <!-- COOKIE INFO CARD -->
        <div class="col-md-6 mb-3">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5>🍪 COOKIE Data (Browser-Side)</h5>
                </div>
                <div class="card-body">
                    <?php if ($cookie_value): ?>
                        <!-- Cookie exists — show info -->
                        <div class="alert alert-success p-2">
                            <strong>Cookie Found!</strong><br>
                            <code>$_COOKIE['username']</code> = <strong><?= htmlspecialchars($cookie_value) ?></strong>
                        </div>
                        <p class="small">This cookie was set in your browser for <strong>30 days</strong>.
                        It will pre-fill your name on the login form next time you visit.</p>
                    <?php else: ?>
                        <!-- Cookie not set -->
                        <div class="alert alert-secondary p-2">
                            <strong>No cookie set.</strong><br>
                            You did not check "Remember my name" — so no cookie was created.
                        </div>
                    <?php endif; ?>

                    <!-- Cookie vs Session comparison -->
                    <table class="table table-bordered table-sm mt-2">
                        <tr><th colspan="3" class="bg-light text-center">Cookie vs Session</th></tr>
                        <tr><th>Feature</th><th>Cookie 🍪</th><th>Session 🖥️</th></tr>
                        <tr>
                            <td>Stored Where?</td>
                            <td>Browser</td>
                            <td>Server</td>
                        </tr>
                        <tr>
                            <td>Expiry</td>
                            <td>Set by developer</td>
                            <td>Browser close / timeout</td>
                        </tr>
                        <tr>
                            <td>Security</td>
                            <td>Lower (editable)</td>
                            <td>Higher (server-controlled)</td>
                        </tr>
                        <tr>
                            <td>Storage Limit</td>
                            <td>~4 KB</td>
                            <td>Server limit (MBs)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- GET VS POST EXPLANATION CARD -->
        <div class="col-12 mb-3">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5>📡 GET vs POST — What's the Difference?</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success">✅ POST Method (you used this)</h6>
                            <ul class="small">
                                <li>Data sent in <strong>HTTP request body</strong> (hidden)</li>
                                <li>URL stays clean: <code>form_handler.php</code></li>
                                <li>No length limit on data</li>
                                <li>Cannot be bookmarked</li>
                                <li>Use for: Login, Registration, Payment</li>
                                <li>PHP reads via: <code>$_POST['field']</code></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">⚠️ GET Method</h6>
                            <ul class="small">
                                <li>Data appended to URL: <code>?name=John&email=...</code></li>
                                <li>Visible in browser address bar</li>
                                <li>Max ~2000 characters</li>
                                <li>Can be bookmarked and shared</li>
                                <li>Use for: Search queries, filters, pagination</li>
                                <li>PHP reads via: <code>$_GET['field']</code></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- EMAIL VALIDATION CARD -->
        <div class="col-md-6 mb-3">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5>📧 Email Validation Explained</h5>
                </div>
                <div class="card-body small">
                    <p><strong>Your email validated:</strong> <code><?= htmlspecialchars($email) ?></code> ✅</p>
                    <hr>
                    <p><strong>PHP method used:</strong></p>
                    <pre class="bg-light p-2 rounded"><code>filter_var($email, FILTER_VALIDATE_EMAIL)</code></pre>
                    <p>This checks:</p>
                    <ul>
                        <li>Has exactly one <code>@</code> symbol</li>
                        <li>Has a domain (e.g., <code>gmail.com</code>)</li>
                        <li>Follows RFC 5322 email standard</li>
                    </ul>
                    <p><strong>JS Regex used:</strong></p>
                    <pre class="bg-light p-2 rounded"><code>/^\S+@\S+\.\S+$/</code></pre>
                </div>
            </div>
        </div>

        <!-- LOGOUT CARD -->
        <div class="col-md-6 mb-3">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5>🚪 Session Management</h5>
                </div>
                <div class="card-body">
                    <p class="small">Click logout to see how sessions are destroyed:</p>

                    <!-- Logout button — sends to logout.php -->
                    <a href="logout.php" class="btn btn-danger w-100 mb-2">
                        🚪 Logout (Destroy Session)
                    </a>
                    <a href="clear_cookie.php" class="btn btn-outline-warning w-100 mb-2">
                        🗑️ Clear Cookie Only
                    </a>
                    <a href="get_demo.php" class="btn btn-outline-info w-100">
                        🔍 View GET Demo
                    </a>

                    <div class="alert alert-light mt-2 p-2 small">
                        <strong>What logout does:</strong><br>
                        1. <code>$_SESSION = []</code> — empty all data<br>
                        2. <code>session_destroy()</code> — delete server session<br>
                        3. Delete <code>PHPSESSID</code> cookie from browser
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
