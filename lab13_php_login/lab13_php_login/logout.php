<?php
// ========================================
// LOGOUT SCRIPT
// Destroys session and clears cookies
// ========================================

// Include configuration and functions
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Initialize session
init_session();

// THEORY: Logout Process
// Properly destroying a session requires multiple steps

// Step 1: Log the logout activity
if (is_logged_in()) {
    $username = $_SESSION['username'];
    log_activity("User logged out: " . $username);
    
    // THEORY: Delete session from database
    $session_id = session_id();
    $stmt = $conn->prepare("DELETE FROM user_sessions WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $stmt->close();
}

// Step 2: THEORY: Unset all session variables
// This clears all data stored in $_SESSION array
$_SESSION = array();

// Step 3: THEORY: Delete the session cookie
// Session cookie is named PHPSESSID by default
// We need to delete it from the client's browser
if (isset($_COOKIE[session_name()])) {
    // Get session cookie parameters
    $params = session_get_cookie_params();
    
    // Set cookie with expiry time in the past to delete it
    setcookie(
        session_name(),           // Cookie name (PHPSESSID)
        '',                       // Empty value
        time() - 42000,           // Expiry time in the past
        $params['path'],          // Cookie path
        $params['domain'],        // Cookie domain
        $params['secure'],        // Secure flag
        $params['httponly']       // HttpOnly flag
    );
}

// Step 4: THEORY: Delete "Remember Me" cookie if exists
if (isset($_COOKIE['remember_me'])) {
    delete_cookie('remember_me');
}

// Step 5: THEORY: Destroy the session
// This removes the session file from the server
session_destroy();

// Step 6: Set flash message for login page
// We need to start a new session to set flash message
session_start();
set_flash_message('success', 'You have been logged out successfully');

// Step 7: Redirect to login page
redirect('login.php');

// THEORY: Why all these steps?
// ================================
// 1. Unset variables: Clears session data in current script
// 2. Delete cookie: Prevents browser from sending session ID
// 3. Destroy session: Removes session file from server
// 
// Without all steps, user might still appear logged in on next visit
// This ensures complete cleanup of authentication state
?>
