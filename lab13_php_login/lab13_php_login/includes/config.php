<?php
// ========================================
// DATABASE CONFIGURATION FILE
// This file establishes connection to MySQL database
// ========================================

// THEORY: Define constants for database connection
// Constants are immutable variables defined using define() function
// They are accessible throughout the application without using $ symbol
define('DB_HOST', 'localhost');      // Database server address (localhost for local development)
define('DB_USER', 'root');           // MySQL username (default: root for XAMPP/WAMP)
define('DB_PASS', 'WJ28@krhps');               // MySQL password (default: empty for XAMPP/WAMP)
define('DB_NAME', 'login_system');   // Database name we created in setup.sql

// THEORY: MySQLi Extension
// MySQLi (MySQL Improved) is a PHP extension for accessing MySQL databases
// It supports both procedural and object-oriented programming
// Benefits: Prepared statements (prevent SQL injection), transactions, multiple queries

// Create database connection using MySQLi
// mysqli object provides methods to interact with MySQL database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// THEORY: Error Handling
// Check if connection was successful
// connect_error property contains error message if connection failed
// connect_errno property contains error code
if ($conn->connect_error) {
    // die() function stops script execution and outputs error message
    die("Connection failed: " . $conn->connect_error);
}

// THEORY: Character Set
// Set character encoding to UTF-8 for international character support
// This ensures proper handling of special characters, emojis, etc.
$conn->set_charset("utf8mb4");

// THEORY: Why we don't close connection here?
// Connection is kept open for use by other scripts that include this file
// It will automatically close when PHP script finishes execution
// Or we can explicitly close it using $conn->close() when done

// Optional: Set timezone (useful for timestamp operations)
date_default_timezone_set('Asia/Kolkata');  // Set to Indian Standard Time

// THEORY: This file will be included in other PHP files using:
// require_once 'config.php';  - includes file once, throws fatal error if not found
// include_once 'config.php';  - includes file once, shows warning if not found
// The 'once' variants prevent multiple inclusions of the same file

?>
