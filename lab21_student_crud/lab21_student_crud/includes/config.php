<?php
/* ============================================================
   includes/config.php
   Database connection + shared helper functions
   Lab Q21: Student Records CRUD (Edit + Delete)

   THEORY (Unit III – PHP + MySQL):
   MySQLi = MySQL Improved extension
   Provides Object-Oriented interface to MySQL database
   ============================================================ */

// ── Database Credentials ──────────────────────────────────────
// Change these to match your MySQL setup
define('DB_HOST', 'localhost');   // MySQL server address
define('DB_USER', 'root');        // MySQL username (default for XAMPP)
define('DB_PASS', 'WJ28@krhps');            // MySQL password (blank for XAMPP default)
define('DB_NAME', 'student_db'); // Database name we created in schema.sql

// ── Create Database Connection ────────────────────────────────
// new mysqli() = creates a MySQLi connection object
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if connection failed
// connect_error is NULL if successful, or error message string if failed
if ($conn->connect_error) {
    // die() = print message and stop all execution immediately
    die("
    <div style='font-family:sans-serif;max-width:600px;margin:60px auto;
                background:#fee2e2;border:2px solid #ef4444;border-radius:12px;padding:24px'>
        <h2 style='color:#dc2626'>⚠️ Database Connection Failed</h2>
        <p><strong>Error:</strong> " . htmlspecialchars($conn->connect_error) . "</p>
        <h3>How to fix:</h3>
        <ol>
            <li>Make sure <strong>XAMPP MySQL</strong> is running (green in XAMPP panel)</li>
            <li>Import <code>sql/schema.sql</code> into phpMyAdmin</li>
            <li>If your MySQL has a password, update <code>DB_PASS</code> in config.php</li>
        </ol>
        <p>phpMyAdmin URL: <a href='http://localhost/phpmyadmin'>http://localhost/phpmyadmin</a></p>
    </div>");
}

// Set character encoding to UTF-8 (supports all languages and symbols)
$conn->set_charset("utf8mb4");

// ── Constants for dropdown options ───────────────────────────
// These match the ENUM values in the database
define('DEPARTMENTS', [
    'Computer Science',
    'Information Technology',
    'Electronics',
    'Mechanical',
    'Civil',
    'Electrical'
]);

define('YEARS', [1 => '1st Year', 2 => '2nd Year', 3 => '3rd Year', 4 => '4th Year']);

// ============================================================
// HELPER FUNCTIONS
// ============================================================

/**
 * flash($message, $type)
 * Store a notification message in session to show after redirect
 * THEORY: Flash messages = messages that survive one page redirect
 *
 * @param string $message  The message text to display
 * @param string $type     'success', 'error', 'warning', 'info'
 */
function flash(string $message, string $type = 'success'): void {
    // Store in session so it survives the redirect
    $_SESSION['flash'] = ['msg' => $message, 'type' => $type];
}

/**
 * showFlash()
 * Display the flash message (if any) and clear it
 * Returns HTML string of the Bootstrap alert
 */
function showFlash(): string {
    // Check if a flash message exists in session
    if (!isset($_SESSION['flash'])) return ''; // No message, return empty

    $f   = $_SESSION['flash'];
    unset($_SESSION['flash']); // Delete after reading (show only once)

    // Map our type to Bootstrap alert class
    $map = [
        'success' => 'success',  // Green
        'error'   => 'danger',   // Red
        'warning' => 'warning',  // Yellow
        'info'    => 'info'      // Blue
    ];
    $cls = $map[$f['type']] ?? 'info';

    // Return Bootstrap alert HTML
    // htmlspecialchars() prevents XSS (Cross-Site Scripting) attacks
    return "
    <div class='alert alert-{$cls} alert-dismissible fade show shadow-sm' role='alert'>
        {$f['msg']}
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>";
}

/**
 * sanitize($value)
 * Clean user input: remove whitespace, prevent XSS
 * THEORY: Never trust user input! Always sanitize before using.
 *
 * @param  string $value Raw user input
 * @return string        Clean, safe string
 */
function sanitize(string $value): string {
    $value = trim($value);               // Remove leading/trailing spaces
    $value = stripslashes($value);        // Remove backslashes
    $value = htmlspecialchars($value);    // Convert < > " ' & to HTML entities
    return $value;
}

/**
 * validateStudent($data)
 * Server-side validation for student form data
 * THEORY: Always validate on server even if you have JS validation
 *
 * @param  array $data  Associative array of form fields
 * @return array        Array of error messages (empty = no errors)
 */
function validateStudent(array $data): array {
    $errors = []; // Will collect all validation errors

    // Validate Name: required, max 100 chars
    if (empty($data['name'])) {
        $errors[] = "Student name is required.";
    } elseif (strlen($data['name']) > 100) {
        $errors[] = "Name cannot exceed 100 characters.";
    } elseif (!preg_match('/^[a-zA-Z\s\.]+$/', $data['name'])) {
        // preg_match() checks against a Regular Expression (regex)
        // ^ = start, [a-zA-Z\s\.]+ = letters, spaces, dots only, $ = end
        $errors[] = "Name can only contain letters, spaces, and dots.";
    }

    // Validate Roll Number: required, max 20 chars, alphanumeric
    if (empty($data['roll_no'])) {
        $errors[] = "Roll number is required.";
    } elseif (!preg_match('/^[A-Z0-9]{2,10}$/', strtoupper($data['roll_no']))) {
        $errors[] = "Roll number must be 2–10 alphanumeric characters (e.g. CS001).";
    }

    // Validate Email: required + must be valid email format
    if (empty($data['email'])) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        // filter_var() with FILTER_VALIDATE_EMAIL = built-in email validator
        $errors[] = "Please enter a valid email address.";
    }

    // Validate Department: must be one of our allowed values
    if (empty($data['department']) || !in_array($data['department'], DEPARTMENTS)) {
        $errors[] = "Please select a valid department.";
    }

    // Validate Year: must be 1, 2, 3, or 4
    if (empty($data['year']) || !in_array((int)$data['year'], [1, 2, 3, 4])) {
        $errors[] = "Year must be between 1 and 4.";
    }

    // Validate CGPA: must be between 0.00 and 10.00
    if ($data['cgpa'] !== '' && ($data['cgpa'] < 0 || $data['cgpa'] > 10)) {
        $errors[] = "CGPA must be between 0.00 and 10.00.";
    }

    // Validate Phone: optional, but if provided must be 10 digits
    if (!empty($data['phone']) && !preg_match('/^[0-9]{10}$/', $data['phone'])) {
        $errors[] = "Phone must be exactly 10 digits (numbers only).";
    }

    return $errors; // Return all errors found (empty array = all valid)
}
?>
