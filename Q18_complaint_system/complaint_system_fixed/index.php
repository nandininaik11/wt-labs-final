<?php
// index.php — Entry point of the application
// If user is logged in → go to dashboard
// If not → go to login page
require_once 'includes/auth.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}
exit();
?>
