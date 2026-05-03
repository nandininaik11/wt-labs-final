<?php
// ============================================================
// FILE: logout.php
// PURPOSE: Destroys the session and logs out the user
// THEORY: To log out a user, we:
//         1. Start the session (to access existing session data)
//         2. Clear all session variables
//         3. Destroy the session on the server
//         4. Optionally delete the session cookie from browser
//         5. Redirect to login page
// ============================================================

session_start(); // Must start session before we can destroy it

// Method 1: Unset all session variables (empties $_SESSION array)
$_SESSION = array();
// Equivalent to unsetting each variable individually

// Method 2: Destroy the session data on the server
session_destroy();
// This deletes the session file from the server
// The session ID cookie in the browser becomes invalid

// Redirect to student login page after logout
header("Location: student_login.php");
exit();
?>
