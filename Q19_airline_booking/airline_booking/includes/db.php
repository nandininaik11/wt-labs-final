<?php
// ============================================================
// includes/db.php — PDO Database Connection
// PDO = PHP Data Objects: secure, object-oriented DB interface
// ============================================================

define('DB_HOST', 'localhost');  // MySQL server (XAMPP default)
define('DB_USER', 'root');       // MySQL username
define('DB_PASS', 'WJ28@krhps');           // MySQL password (empty in XAMPP)
define('DB_NAME', 'airline_db'); // Our database name

try {
    // DSN = Data Source Name: driver:host=...;dbname=...;charset=...
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions on error
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // return rows as arrays
    ]);
} catch (PDOException $e) {
    // die() stops script and prints error message
    die("<div style='font-family:sans-serif;padding:30px;color:red'>
        <h2>❌ Database Error</h2>
        <p>".$e->getMessage()."</p>
        <p>Make sure XAMPP MySQL is running and you imported database.sql</p>
    </div>");
}
?>
