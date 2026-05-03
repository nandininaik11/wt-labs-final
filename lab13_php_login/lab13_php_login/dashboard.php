<?php
// ========================================
// DASHBOARD PAGE
// Protected page accessible only to logged-in users
// Displays session information and user data
// ========================================

// Include configuration and functions
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Initialize session
init_session();

// THEORY: Page Protection
// Redirect to login if user is not authenticated
if (!is_logged_in()) {
    set_flash_message('error', 'Please login to access this page');
    redirect('login.php');
}

// THEORY: Fetch User Data from Database
// Get complete user information for display
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, email, full_name, created_at, last_login, login_count FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// THEORY: Session Information
// Calculate session duration
$login_time = $_SESSION['login_time'];
$current_time = time();
$session_duration = $current_time - $login_time;

// Convert seconds to minutes
$session_minutes = floor($session_duration / 60);
$session_seconds = $session_duration % 60;

// THEORY: Get Active Sessions for this User
$sessions_stmt = $conn->prepare("SELECT session_id, ip_address, user_agent, created_at, last_activity FROM user_sessions WHERE user_id = ? ORDER BY last_activity DESC");
$sessions_stmt->bind_param("i", $user_id);
$sessions_stmt->execute();
$sessions_result = $sessions_stmt->get_result();
$sessions_stmt->close();

// Get flash message if exists
$flash_message = get_flash_message();

// THEORY: Cookie Information
$cookie_status = isset($_COOKIE['remember_me']) ? 'Active' : 'Not Set';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lab 13</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dashboard-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .info-card h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .label {
            font-weight: bold;
            color: #555;
        }
        
        .value {
            color: #333;
        }
        
        .session-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .session-table th,
        .session-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .session-table th {
            background: #f5f5f5;
            font-weight: bold;
        }
        
        .current-session {
            background: #e7f3ff;
        }
        
        .action-buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-container">
            
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h1>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h1>
                <p>You are successfully logged in to your dashboard</p>
            </div>
            
            <!-- Flash Message -->
            <?php if ($flash_message): ?>
                <div class="alert alert-<?php echo $flash_message['type']; ?>">
                    <?php echo $flash_message['message']; ?>
                </div>
            <?php endif; ?>
            
            <!-- User Information Card -->
            <div class="info-card">
                <h3>📋 User Information</h3>
                
                <div class="info-row">
                    <span class="label">User ID:</span>
                    <span class="value"><?php echo $user_id; ?></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Username:</span>
                    <span class="value"><?php echo htmlspecialchars($user['username']); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Full Name:</span>
                    <span class="value"><?php echo htmlspecialchars($user['full_name']); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Account Created:</span>
                    <span class="value"><?php echo date('F j, Y, g:i a', strtotime($user['created_at'])); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Last Login:</span>
                    <span class="value"><?php echo $user['last_login'] ? date('F j, Y, g:i a', strtotime($user['last_login'])) : 'First login'; ?></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Total Logins:</span>
                    <span class="value"><?php echo $user['login_count']; ?> times</span>
                </div>
            </div>
            
            <!-- Session Information Card -->
            <div class="info-card">
                <h3>🔐 Session Information (Stored on Server)</h3>
                
                <div class="info-row">
                    <span class="label">Session ID:</span>
                    <span class="value"><?php echo session_id(); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Session Duration:</span>
                    <span class="value"><?php echo $session_minutes; ?> minutes, <?php echo $session_seconds; ?> seconds</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Login Time:</span>
                    <span class="value"><?php echo date('F j, Y, g:i:s a', $login_time); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Current IP Address:</span>
                    <span class="value"><?php echo get_ip_address(); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Browser/User Agent:</span>
                    <span class="value" style="font-size: 0.9em;"><?php echo htmlspecialchars($_SERVER['HTTP_USER_AGENT']); ?></span>
                </div>
            </div>
            
            <!-- Cookie Information Card -->
            <div class="info-card">
                <h3>🍪 Cookie Information (Stored on Client Browser)</h3>
                
                <div class="info-row">
                    <span class="label">Cookies Enabled:</span>
                    <span class="value" id="cookieEnabled">Checking...</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Remember Me Cookie:</span>
                    <span class="value"><?php echo $cookie_status; ?></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Session Cookie (PHPSESSID):</span>
                    <span class="value" id="sessionCookie">Checking...</span>
                </div>
                
                <div class="info-row">
                    <span class="label">All Cookies:</span>
                    <span class="value" id="allCookies" style="font-size: 0.9em;">Checking...</span>
                </div>
            </div>
            
            <!-- Active Sessions Card -->
            <div class="info-card">
                <h3>💻 Active Sessions Across Devices</h3>
                
                <?php if ($sessions_result->num_rows > 0): ?>
                    <table class="session-table">
                        <thead>
                            <tr>
                                <th>Device/Browser</th>
                                <th>IP Address</th>
                                <th>Created</th>
                                <th>Last Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($session = $sessions_result->fetch_assoc()): ?>
                                <?php 
                                    // Check if this is the current session
                                    $is_current = ($session['session_id'] == session_id());
                                ?>
                                <tr class="<?php echo $is_current ? 'current-session' : ''; ?>">
                                    <td>
                                        <?php 
                                        // Extract browser name from user agent
                                        $user_agent = $session['user_agent'];
                                        if (strpos($user_agent, 'Chrome') !== false) echo '🌐 Chrome';
                                        elseif (strpos($user_agent, 'Firefox') !== false) echo '🦊 Firefox';
                                        elseif (strpos($user_agent, 'Safari') !== false) echo '🧭 Safari';
                                        elseif (strpos($user_agent, 'Edge') !== false) echo '🌊 Edge';
                                        else echo '💻 Unknown';
                                        
                                        echo $is_current ? ' <strong>(Current)</strong>' : '';
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($session['ip_address']); ?></td>
                                    <td><?php echo date('M j, g:i a', strtotime($session['created_at'])); ?></td>
                                    <td><?php echo date('M j, g:i a', strtotime($session['last_activity'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No active sessions found.</p>
                <?php endif; ?>
            </div>
            
            <!-- Session Data Explanation -->
            <div class="info-card">
                <h3>📚 Understanding Sessions vs Cookies</h3>
                
                <h4>Sessions (Server-Side Storage):</h4>
                <ul>
                    <li><strong>Stored:</strong> On the web server</li>
                    <li><strong>Security:</strong> More secure (data not exposed to client)</li>
                    <li><strong>Size:</strong> Can store large amounts of data</li>
                    <li><strong>Lifetime:</strong> Expires when browser closes or timeout occurs</li>
                    <li><strong>Access:</strong> Only accessible by server-side code</li>
                    <li><strong>Example:</strong> User login status, shopping cart</li>
                </ul>
                
                <h4>Cookies (Client-Side Storage):</h4>
                <ul>
                    <li><strong>Stored:</strong> On the user's browser</li>
                    <li><strong>Security:</strong> Less secure (can be viewed/modified by user)</li>
                    <li><strong>Size:</strong> Limited to ~4KB per cookie</li>
                    <li><strong>Lifetime:</strong> Can be set to specific expiration date</li>
                    <li><strong>Access:</strong> Accessible by both client and server</li>
                    <li><strong>Example:</strong> "Remember Me" functionality, user preferences</li>
                </ul>
                
                <h4>How They Work Together:</h4>
                <ul>
                    <li>PHP creates a session and generates a unique Session ID</li>
                    <li>Session ID is stored in a cookie named PHPSESSID on the client</li>
                    <li>Session data is stored on the server, indexed by Session ID</li>
                    <li>Browser sends session cookie with every request</li>
                    <li>Server uses Session ID from cookie to retrieve session data</li>
                </ul>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="profile.php" class="btn btn-primary">Edit Profile</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
    
    <!-- THEORY: JavaScript for Cookie Detection -->
    <script>
        // Check if cookies are enabled
        document.getElementById('cookieEnabled').textContent = navigator.cookieEnabled ? 'Yes ✓' : 'No ✗';
        
        // Get all cookies
        const cookies = document.cookie;
        document.getElementById('allCookies').textContent = cookies || 'No cookies found';
        
        // Check for session cookie
        const sessionCookie = cookies.split(';').find(c => c.trim().startsWith('PHPSESSID='));
        document.getElementById('sessionCookie').textContent = sessionCookie ? 'Set ✓' : 'Not Set ✗';
        
        // THEORY: Auto-refresh session duration every second
        setInterval(function() {
            // This would require AJAX to fetch updated session time from server
            // For now, we'll just update the display time
            console.log('Session active - ' + new Date().toLocaleTimeString());
        }, 1000);
        
        // Log session info to console for debugging
        console.log('=== SESSION DEBUG INFO ===');
        console.log('Session ID from cookie:', sessionCookie);
        console.log('All cookies:', cookies);
        console.log('Cookie enabled:', navigator.cookieEnabled);
    </script>
</body>
</html>
