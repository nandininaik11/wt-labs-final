<?php
// ============================================================
// includes/db.php — Database Connection
// This file creates a connection to MySQL using PDO.
// PDO = PHP Data Objects: a safe, modern way to talk to databases.
// ============================================================

// Database credentials — change these to match your XAMPP/WAMP setup
define('DB_HOST', 'localhost');   // MySQL server address
define('DB_USER', 'root');        // MySQL username (default in XAMPP is 'root')
define('DB_PASS', 'WJ28@krhps');            // MySQL password (default in XAMPP is empty '')
define('DB_NAME', 'complaint_db'); // name of our database

try {
    // PDO DSN (Data Source Name): tells PHP what type of DB and which one to use
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    
    // Create PDO connection object
    // PDO::ATTR_ERRMODE => EXCEPTION means throw errors as PHP exceptions (catchable)
    // PDO::ATTR_DEFAULT_FETCH_MODE => ASSOC means fetch rows as associative arrays
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // If connection fails, stop everything and show error message
    die("❌ Database connection failed: " . $e->getMessage());
}
?>
