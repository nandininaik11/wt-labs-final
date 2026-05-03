<?php
// ============================================================
// FILE: logout.php
// PURPOSE: Destroy the admin session and redirect to login
//
// THEORY: To log out a user in PHP:
//   1. session_start() — must access session first
//   2. $_SESSION = [] — clear all session variables from memory
//   3. session_destroy() — delete session data from server
//   4. header(Location) — redirect browser to login page
// ============================================================

session_start();          // Access existing session
$_SESSION = [];           // Wipe all session variables
session_destroy();        // Delete session file from server
header("Location: admin_login.php"); // Redirect to login
exit();
?>
