<?php
// ============================================================
// FILE: db.php
// PURPOSE: Database connection — included in every PHP file
//
// THEORY: Instead of writing connection code in every file,
// we write it ONCE here and use include 'db.php' elsewhere.
// This is the DRY principle: Don't Repeat Yourself.
//
// PHP MySQLi (MySQL Improved):
// - Connects PHP to MySQL database
// - Supports both procedural and object-oriented styles
// - We use procedural style (functions, not objects) for simplicity
// ============================================================

// mysqli_connect(host, username, password, database_name)
$conn = mysqli_connect("localhost", "root", "WJ28@krhps", "waste_management");
//                       ^host        ^user  ^pw  ^database

// localhost = MySQL is on the same machine (XAMPP local server)
// root      = default MySQL admin user in XAMPP
// ""        = no password (XAMPP default — never use in production!)
// waste_management = our database name (created in setup.sql)

// Check if connection failed
if (!$conn) {
    // mysqli_connect_error() returns error message string from MySQL
    die("Connection Failed: " . mysqli_connect_error());
    // die() = print message + stop all PHP execution immediately
}

// Set character encoding to UTF-8
// This handles all characters including: ₹ é ñ and Marathi/Hindi text
mysqli_set_charset($conn, "utf8");
?>
