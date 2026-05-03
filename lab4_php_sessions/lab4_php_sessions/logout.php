<?php
// ============================================================
// LOGOUT.PHP — How to properly destroy a PHP session
//
// THEORY: To fully log out a user, you must do ALL 3 steps:
// Step 1: Clear the $_SESSION array (empty all data)
// Step 2: Destroy the session file on the server
// Step 3: Delete the PHPSESSID cookie from the browser
//
// Doing only 1 or 2 steps may leave residual session data!
// ============================================================

session_start(); // Must start session before we can destroy it

// STEP 1: Empty all session variables
// This removes all data stored in $_SESSION for this session
$_SESSION = []; // Assign empty array to clear everything

// STEP 2: If session uses a cookie (default in PHP), expire it
// This removes the PHPSESSID cookie from the user's browser
// We expire it by setting a past timestamp (time() - 3600 = 1 hour ago)
if (ini_get("session.use_cookies")) {
    // session_get_cookie_params() returns current session cookie settings
    $params = session_get_cookie_params();
    setcookie(
        session_name(),     // Cookie name = 'PHPSESSID' (default)
        '',                 // Empty value
        time() - 42000,     // Past time = expires immediately
        $params["path"],    // Same path as session cookie
        $params["domain"],  // Same domain
        $params["secure"],  // Same secure setting
        $params["httponly"] // Same httponly setting
    );
}

// STEP 3: Destroy the session on the server
// This deletes the session file from the server's /tmp folder
session_destroy();

// Redirect to login page after logout
// Location header sends user back to the main page
header("Location: index.php?msg=logged_out");
exit(); // IMPORTANT: Exit immediately after redirect!
?>
