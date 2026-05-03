<?php
// ============================================================
// includes/auth.php — Session & Authentication Helper
// Sessions in PHP allow us to remember who is logged in
// across multiple pages (HTTP is stateless by default).
// ============================================================

// session_start() MUST be called before any session variable is used
// It starts or resumes an existing session using a cookie (PHPSESSID)
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // start session only if not already started
}

// Function: Check if user is logged in (either as admin or regular user)
// If not, redirect them to the login page
function requireLogin() {
    // Check if logged in as user OR admin
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
        // Not logged in at all - redirect to login
        header("Location: login.php");
        exit();
    }
}

// Function: Check if user is admin
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Function: Require admin access (redirect if not admin)
function requireAdmin() {
    requireLogin(); // First check if logged in
    if (!isAdmin()) {
        // Logged in but not admin - deny access
        die('<div style="text-align:center;padding:60px;font-family:sans-serif;">
            <h2>🚫 Access Denied</h2>
            <p>You must be an admin to view this page.</p>
            <a href="dashboard.php">Go to Dashboard</a> | <a href="logout.php">Logout</a>
        </div>');
    }
}

// Function: Get currently logged-in user's name from session
function currentUser() {
    if (isset($_SESSION['admin_username'])) {
        return $_SESSION['admin_username'] . ' (Admin)';
    }
    return $_SESSION['user_name'] ?? 'Guest'; // ?? is null-coalescing operator
}

// Function: Get currently logged-in user's ID from session
function currentUserId() {
    // Return user_id for regular users, admin_id for admins
    return $_SESSION['user_id'] ?? $_SESSION['admin_id'] ?? null;
}
?>
