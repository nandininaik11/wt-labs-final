<?php
// ========================================
// USER LOGIN PAGE
// Handles authentication with cookies and sessions
// ========================================

// Include configuration and functions
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Initialize session
init_session();

// If user is already logged in, redirect to dashboard
if (is_logged_in()) {
    redirect('dashboard.php');
}

// THEORY: Cookie-Based Auto Login ("Remember Me" Feature)
// Check if remember_me cookie exists and user is not logged in
if (isset($_COOKIE['remember_me']) && !is_logged_in()) {
    
    // Cookie format: username:hash
    $cookie_data = explode(':', $_COOKIE['remember_me']);
    
    if (count($cookie_data) == 2) {
        $username = $cookie_data[0];
        $cookie_hash = $cookie_data[1];
        
        // Verify cookie against database
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify cookie hash (cookie should be: username:md5(username.password))
            $expected_hash = md5($user['username'] . $user['password']);
            
            if ($cookie_hash === $expected_hash) {
                // Valid cookie - auto login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['login_time'] = time();
                
                log_activity("User auto-logged in via cookie: " . $user['username']);
                
                redirect('dashboard.php');
            } else {
                // Invalid cookie - delete it
                delete_cookie('remember_me');
            }
        }
        $stmt->close();
    }
}

// Initialize variables
$username = "";
$errors = [];

// THEORY: Processing Login Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get and sanitize input
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']) ? true : false;
    
    // THEORY: Input Validation
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    // THEORY: Database Authentication
    if (empty($errors)) {
        
        // Prepare SELECT statement to fetch user data
        $stmt = $conn->prepare("SELECT id, username, email, password, full_name FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);  // Allow login with username or email
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            // User found - verify password
            $user = $result->fetch_assoc();
            
            // THEORY: Password Verification
            // password_verify() compares plain password with hashed password
            // It's designed to be slow to prevent brute force attacks
            if (verify_password($password, $user['password'])) {
                
                // THEORY: Session Creation
                // Store user data in session superglobal
                // Session data persists across page requests until browser closes or logout
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['login_time'] = time();
                
                // THEORY: Session Security
                // Regenerate session ID to prevent session fixation attack
                session_regenerate_id(true);
                
                // THEORY: Cookie Management for "Remember Me"
                if ($remember_me) {
                    // Create cookie hash: md5(username + password_hash)
                    $cookie_value = $user['username'] . ':' . md5($user['username'] . $user['password']);
                    
                    // Set cookie for 30 days
                    set_remember_cookie('remember_me', $cookie_value, 30);
                    
                    log_activity("Remember me cookie set for: " . $user['username']);
                }
                
                // THEORY: Update Last Login Information
                $login_ip = get_ip_address();
                $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW(), login_count = login_count + 1 WHERE id = ?");
                $update_stmt->bind_param("i", $user['id']);
                $update_stmt->execute();
                $update_stmt->close();
                
                // THEORY: Track Session in Database
                $session_id = session_id();
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                
                $session_stmt = $conn->prepare("INSERT INTO user_sessions (session_id, user_id, ip_address, user_agent) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE last_activity = NOW()");
                $session_stmt->bind_param("siss", $session_id, $user['id'], $login_ip, $user_agent);
                $session_stmt->execute();
                $session_stmt->close();
                
                // Log successful login
                log_activity("User logged in: " . $user['username']);
                
                // Set flash message
                set_flash_message('success', 'Login successful! Welcome back.');
                
                // Redirect to dashboard
                redirect('dashboard.php');
                
            } else {
                // Invalid password
                $errors[] = "Invalid username or password";
                log_activity("Failed login attempt for username: " . $username);
            }
        } else {
            // User not found
            $errors[] = "Invalid username or password";
            log_activity("Failed login attempt - user not found: " . $username);
        }
        
        $stmt->close();
    }
}

// Get flash message if exists
$flash_message = get_flash_message();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lab 13</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-container {
            max-width: 450px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        
        .remember-me input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Login</h2>
            
            <!-- Display Flash Messages -->
            <?php if ($flash_message): ?>
                <div class="alert alert-<?php echo $flash_message['type']; ?>">
                    <?php echo $flash_message['message']; ?>
                </div>
            <?php endif; ?>
            
            <!-- Display Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- THEORY: Login Form -->
            <form method="POST" action="" id="loginForm">
                
                <!-- Username/Email Field -->
                <div class="form-group">
                    <label for="username">Username or Email:</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="<?php echo $username; ?>" 
                           required 
                           autofocus>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required>
                </div>
                
                <!-- THEORY: Remember Me Checkbox (Creates Cookie) -->
                <div class="form-group">
                    <div class="remember-me">
                        <input type="checkbox" 
                               id="remember_me" 
                               name="remember_me" 
                               value="1">
                        <label for="remember_me">Remember me for 30 days</label>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                
                <!-- Additional Links -->
                <div class="form-footer">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                    <p><a href="forgot_password.php">Forgot password?</a></p>
                </div>
            </form>
            
            <!-- THEORY: Display Test Credentials -->
            <div class="info-box" style="margin-top: 20px; padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <h4>Test Credentials:</h4>
                <p><strong>Username:</strong> admin</p>
                <p><strong>Password:</strong> password123</p>
                <p style="font-size: 0.9em; color: #666;">
                    (Or register a new account above)
                </p>
            </div>
        </div>
    </div>
    
    <!-- Client-Side Form Validation -->
    <script>
        // THEORY: JavaScript Event Handling
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            // Basic validation
            if (username === '' || password === '') {
                event.preventDefault();
                alert('Please fill in all fields!');
                return false;
            }
            
            if (password.length < 6) {
                event.preventDefault();
                alert('Password must be at least 6 characters!');
                return false;
            }
        });
        
        // THEORY: Display cookie status in console
        console.log('Cookies enabled:', navigator.cookieEnabled);
        console.log('Current cookies:', document.cookie);
    </script>
</body>
</html>
