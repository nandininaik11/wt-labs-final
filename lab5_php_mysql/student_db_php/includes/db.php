<?php
// ============================================================
// FILE: includes/db.php
// PURPOSE: Connect PHP to MySQL database using mysqli
// THEORY: mysqli = MySQL Improved Extension (built into PHP)
//         It lets PHP talk to a MySQL database server.
// ============================================================

// Step 1: Define connection details
$host     = "localhost";   // MySQL server address (local machine = localhost)
$user     = "root";        // MySQL username (default is 'root' in XAMPP)
$password = "WJ28@krhps";            // MySQL password (empty by default in XAMPP)
$database = "student_db";  // The database we will create and use

// Step 2: Create connection using mysqli_connect()
// mysqli_connect(host, user, password, database)
$conn = mysqli_connect($host, $user, $password, $database);

// Step 3: Check if connection was successful
// mysqli_connect_error() returns an error message if connection fails
if (!$conn) {
    // die() stops the script and prints the error message
    die("❌ Connection Failed: " . mysqli_connect_error());
}
// If we reach here, connection was successful!
// $conn variable is now available wherever this file is included
?>
