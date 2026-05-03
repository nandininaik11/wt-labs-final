<?php
// ========================================
// INDEX PAGE (Home Page)
// Entry point of the application
// ========================================

// Include configuration and functions
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Initialize session
init_session();

// THEORY: Check Authentication Status
// Redirect logged-in users to dashboard, others to login
if (is_logged_in()) {
    redirect('dashboard.php');
} else {
    redirect('login.php');
}

// THEORY: This file acts as a router
// It determines where to send the user based on their authentication status
// No HTML is needed because we always redirect
?>
