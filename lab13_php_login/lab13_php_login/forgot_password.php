<?php
// ========================================
// FORGOT PASSWORD PAGE (Placeholder)
// In a real application, this would send password reset email
// ========================================

require_once 'includes/config.php';
require_once 'includes/functions.php';

init_session();

if (is_logged_in()) {
    redirect('dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Lab 13</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Forgot Password</h2>
            
            <div class="info-box" style="background: #fff3cd; border-color: #ffc107; color: #856404;">
                <h4>⚠️ Demo Placeholder</h4>
                <p>This is a placeholder page. In a production application, this would:</p>
                <ul>
                    <li>Accept user's email address</li>
                    <li>Generate unique reset token</li>
                    <li>Send password reset link via email</li>
                    <li>Allow user to set new password</li>
                </ul>
            </div>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Enter your email address:</label>
                    <input type="email" id="email" name="email" required>
                    <small>We'll send you a password reset link</small>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </div>
                
                <div class="form-footer">
                    <p>Remember your password? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
