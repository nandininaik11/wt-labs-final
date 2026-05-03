<?php
// ========================================
// USER REGISTRATION PAGE
// Handles user registration with validation
// ========================================

// Include configuration and functions
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Initialize session
init_session();

// If user is already logged in, redirect to dashboard
if (is_logged_in()) {
    redirect('dashboard.php');
}

// Initialize variables
$username = $email = $full_name = "";
$errors = [];
$success_message = "";

// THEORY: Form Processing with POST Method
// $_SERVER['REQUEST_METHOD'] checks how form was submitted
// POST method is secure as data is not visible in URL
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // THEORY: Data Sanitization
    // Clean all input data to prevent XSS attacks
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $full_name = sanitize_input($_POST['full_name']);
    $password = $_POST['password'];  // Don't sanitize password (may contain special chars)
    $confirm_password = $_POST['confirm_password'];
    
    // THEORY: Server-Side Validation
    // NEVER trust client-side validation alone!
    // Always validate on server to prevent malicious submissions
    
    // Validate username (3-50 characters, alphanumeric and underscore only)
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores";
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!validate_email($email)) {
        $errors[] = "Invalid email format";
    }
    
    // Validate full name
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } else {
        $password_validation = validate_password($password);
        if (!$password_validation['valid']) {
            $errors[] = $password_validation['message'];
        }
    }
    
    // Validate password confirmation
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // THEORY: Database Operations - Check for Duplicates
    // If no validation errors, check database for existing username/email
    if (empty($errors)) {
        
        // THEORY: Prepared Statements (SQL Injection Prevention)
        // Prepared statements separate SQL code from data
        // Placeholders (?) are replaced with actual values safely
        
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        
        // THEORY: Bind Parameters
        // bind_param() binds variables to prepared statement
        // "ss" means two string parameters
        // Data types: i=integer, d=double, s=string, b=blob
        $stmt->bind_param("ss", $username, $email);
        
        // Execute the query
        $stmt->execute();
        
        // Store result to check if rows exist
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email already exists";
        }
        
        $stmt->close();
    }
    
    // THEORY: Insert Data into Database
    // If all validations pass, create new user account
    if (empty($errors)) {
        
        // Hash password before storing
        $hashed_password = hash_password($password);
        
        // Prepare INSERT statement
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
        
        // Bind all four parameters (all strings)
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $full_name);
        
        // Execute insert operation
        if ($stmt->execute()) {
            // Registration successful
            $success_message = "Registration successful! You can now login.";
            
            // Log activity
            log_activity("New user registered: $username");
            
            // Clear form fields
            $username = $email = $full_name = "";
            
            // Set flash message for login page
            set_flash_message('success', 'Registration successful! Please login.');
            
            // Redirect to login page after 2 seconds
            header("refresh:2;url=login.php");
            
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- THEORY: Viewport meta tag for responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - Lab 13</title>
    
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- THEORY: Internal CSS for page-specific styles -->
    <style>
        /* Additional page-specific styles */
        .form-container {
            max-width: 500px;
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
            <h2>User Registration</h2>
            
            <!-- THEORY: Display Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- THEORY: Display Success Message -->
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                    <p>Redirecting to login page...</p>
                </div>
            <?php endif; ?>
            
            <!-- THEORY: HTML Form
                 method="POST" - Sends data securely in request body
                 action="" - Submits to same page (self-processing form)
            -->
            <form method="POST" action="" id="registrationForm">
                
                <!-- Username Field -->
                <div class="form-group">
                    <label for="username">Username:</label>
                    <!-- THEORY: value attribute preserves input on validation errors -->
                    <!-- required attribute provides HTML5 client-side validation -->
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="<?php echo $username; ?>" 
                           required 
                           pattern="[a-zA-Z0-9_]{3,50}"
                           title="3-50 characters, letters, numbers, and underscores only">
                </div>
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email:</label>
                    <!-- THEORY: type="email" provides browser-level email validation -->
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?php echo $email; ?>" 
                           required>
                </div>
                
                <!-- Full Name Field -->
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" 
                           id="full_name" 
                           name="full_name" 
                           value="<?php echo $full_name; ?>" 
                           required>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password:</label>
                    <!-- THEORY: type="password" masks input with bullets/asterisks -->
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           minlength="6">
                    <small>Must contain uppercase, lowercase, and number</small>
                </div>
                
                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" 
                           id="confirm_password" 
                           name="confirm_password" 
                           required>
                </div>
                
                <!-- Submit Button -->
                <div class="form-group">
                    <!-- THEORY: type="submit" triggers form submission -->
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
                
                <!-- Link to Login Page -->
                <div class="form-footer">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
    
    <!-- THEORY: Client-Side Validation with JavaScript -->
    <script>
        // Get form element
        const form = document.getElementById('registrationForm');
        
        // Add event listener for form submission
        form.addEventListener('submit', function(event) {
            // Get password values
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Check if passwords match
            if (password !== confirmPassword) {
                // Prevent form submission
                event.preventDefault();
                // Show alert
                alert('Passwords do not match!');
                return false;
            }
            
            // Password strength validation
            if (password.length < 6) {
                event.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
            
            // Check for uppercase letter
            if (!/[A-Z]/.test(password)) {
                event.preventDefault();
                alert('Password must contain at least one uppercase letter!');
                return false;
            }
            
            // Check for lowercase letter
            if (!/[a-z]/.test(password)) {
                event.preventDefault();
                alert('Password must contain at least one lowercase letter!');
                return false;
            }
            
            // Check for number
            if (!/[0-9]/.test(password)) {
                event.preventDefault();
                alert('Password must contain at least one number!');
                return false;
            }
        });
    </script>
</body>
</html>
