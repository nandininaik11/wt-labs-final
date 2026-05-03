<?php
// ========================================
// HELPER FUNCTIONS FOR LOGIN SYSTEM
// Contains reusable functions for authentication and validation
// ========================================

// THEORY: Session Management
// Sessions allow data to be stored on server and persist across page requests
// Each user gets a unique session ID stored in a cookie
// session_start() must be called before accessing $_SESSION superglobal

/**
 * Initialize session if not already started
 * THEORY: This prevents "headers already sent" errors
 */
function init_session() {
    // session_status() returns PHP_SESSION_NONE if session hasn't started
    if (session_status() == PHP_SESSION_NONE) {
        // Start new session or resume existing one
        session_start();
        
        // THEORY: Session Security
        // Regenerate session ID to prevent session fixation attacks
        // session_regenerate_id() creates new session ID while preserving session data
        if (!isset($_SESSION['initiated'])) {
            session_regenerate_id(true);  // true parameter deletes old session
            $_SESSION['initiated'] = true;
        }
    }
}

/**
 * Check if user is logged in
 * THEORY: Validates session data to ensure authenticated access
 * @return bool True if logged in, false otherwise
 */
function is_logged_in() {
    init_session();
    // Check if user_id exists in session and is numeric
    return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
}

/**
 * Redirect to another page
 * THEORY: header() function sends raw HTTP header to browser
 * Location header redirects browser to specified URL
 * @param string $url Destination URL
 */
function redirect($url) {
    // exit() stops script execution after redirect
    header("Location: " . $url);
    exit();
}

/**
 * Sanitize user input to prevent XSS attacks
 * THEORY: Cross-Site Scripting (XSS) Prevention
 * htmlspecialchars() converts special characters to HTML entities
 * @param string $data User input data
 * @return string Sanitized data
 */
function sanitize_input($data) {
    // trim() removes whitespace from beginning and end
    $data = trim($data);
    
    // stripslashes() removes backslashes added by magic_quotes
    $data = stripslashes($data);
    
    // htmlspecialchars() prevents HTML/JavaScript injection
    // ENT_QUOTES: Convert both double and single quotes
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}

/**
 * Validate email format
 * THEORY: filter_var() function validates/sanitizes data
 * FILTER_VALIDATE_EMAIL checks if string is valid email format
 * @param string $email Email to validate
 * @return bool True if valid, false otherwise
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 * THEORY: Password should be strong enough to resist brute force attacks
 * @param string $password Password to validate
 * @return array Array with 'valid' boolean and 'message' string
 */
function validate_password($password) {
    $result = ['valid' => true, 'message' => ''];
    
    // Check minimum length (8 characters recommended)
    if (strlen($password) < 6) {
        $result['valid'] = false;
        $result['message'] = 'Password must be at least 6 characters long';
        return $result;
    }
    
    // THEORY: Regular expressions (regex) for pattern matching
    // preg_match() returns 1 if pattern matches, 0 if not
    
    // Check for at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        $result['valid'] = false;
        $result['message'] = 'Password must contain at least one uppercase letter';
        return $result;
    }
    
    // Check for at least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        $result['valid'] = false;
        $result['message'] = 'Password must contain at least one lowercase letter';
        return $result;
    }
    
    // Check for at least one number
    if (!preg_match('/[0-9]/', $password)) {
        $result['valid'] = false;
        $result['message'] = 'Password must contain at least one number';
        return $result;
    }
    
    return $result;
}

/**
 * Hash password using bcrypt algorithm
 * THEORY: Never store plain text passwords!
 * password_hash() creates secure one-way hash using bcrypt
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hash_password($password) {
    // PASSWORD_DEFAULT uses bcrypt algorithm
    // Cost factor of 10 (can be increased for more security)
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password against hash
 * THEORY: password_verify() securely compares password with hash
 * It's resistant to timing attacks
 * @param string $password Plain text password
 * @param string $hash Stored password hash
 * @return bool True if password matches, false otherwise
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Set cookie for "Remember Me" functionality
 * THEORY: Cookies are small data files stored on client browser
 * setcookie() creates/updates cookie
 * @param string $name Cookie name
 * @param string $value Cookie value
 * @param int $days Number of days to keep cookie
 */
function set_remember_cookie($name, $value, $days = 30) {
    // time() returns current Unix timestamp (seconds since Jan 1, 1970)
    // 86400 = seconds in a day (60 * 60 * 24)
    $expiry = time() + ($days * 86400);
    
    // setcookie() parameters:
    // 1. name, 2. value, 3. expiry time, 4. path, 5. domain, 6. secure, 7. httponly
    // httponly: true prevents JavaScript access (XSS protection)
    // secure: true sends cookie only over HTTPS (set to false for local development)
    setcookie($name, $value, $expiry, "/", "", false, true);
}

/**
 * Delete cookie
 * THEORY: Set expiry time in the past to delete cookie
 * @param string $name Cookie name
 */
function delete_cookie($name) {
    // Set expiry to 1 hour ago
    setcookie($name, "", time() - 3600, "/");
}

/**
 * Get user's IP address
 * THEORY: Useful for security logging and session tracking
 * @return string IP address
 */
function get_ip_address() {
    // Check for IP if behind proxy (like CloudFlare)
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Display flash message
 * THEORY: Flash messages are session-based temporary messages
 * They appear once and are then deleted
 * @param string $type Message type (success, error, warning)
 * @param string $message Message text
 */
function set_flash_message($type, $message) {
    init_session();
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * @return array|null Flash message array or null
 */
function get_flash_message() {
    init_session();
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);  // Delete after reading
        return $message;
    }
    return null;
}

/**
 * Log activity to file
 * THEORY: File handling for logging user activities
 * @param string $message Log message
 */
function log_activity($message) {
    // fopen() opens file in append mode ('a')
    $log_file = fopen("../logs/activity.log", "a");
    
    if ($log_file) {
        // Format: [timestamp] IP: xxx.xxx.xxx.xxx - message
        $log_entry = "[" . date("Y-m-d H:i:s") . "] IP: " . get_ip_address() . " - " . $message . "\n";
        
        // fwrite() writes to file
        fwrite($log_file, $log_entry);
        
        // fclose() closes file handle
        fclose($log_file);
    }
}

?>
