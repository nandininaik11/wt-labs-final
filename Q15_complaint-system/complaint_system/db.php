<?php
// ============================================================
// FILE: db.php
// PURPOSE: Database connection — included in every PHP file
//          that needs to talk to MySQL
// ============================================================

// mysqli_connect() creates a connection to MySQL database
// Parameters: host, username, password, database_name
$conn = mysqli_connect("localhost", "root", "WJ28@krhps", "college_complaints");
//                       ^host        ^user  ^pass  ^database name
//
// THEORY: PHP uses MySQLi (MySQL Improved) or PDO to connect to databases.
// MySQLi is specific to MySQL. PDO works with multiple databases.
// We use MySQLi here because it's simpler for beginners.

// Check if connection failed
if (!$conn) {
    // mysqli_connect_error() returns the error message if connection fails
    die("Connection Failed: " . mysqli_connect_error());
    // die() stops PHP execution and prints the message
    // This prevents the rest of the page from running without a DB connection
}

// If we reach here, connection was successful
// $conn is now a resource we pass to all MySQL queries
?>
