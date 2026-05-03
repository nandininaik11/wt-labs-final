<?php
// ============================================================
// logout.php — Destroy Session and Logout User
// Concepts: session_destroy(), session_unset(), redirect
// ============================================================
require_once 'includes/auth.php';

// session_unset() removes all session variables ($_SESSION array)
session_unset();

// session_destroy() deletes the session file from server
// After this, PHPSESSID cookie becomes invalid
session_destroy();

// Redirect to login page with a logout message
header("Location: login.php?msg=logged_out");
exit();
?>
