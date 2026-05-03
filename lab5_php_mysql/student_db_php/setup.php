<?php
// ============================================================
// FILE: setup.php
// PURPOSE: Create the database and students table
// RUN THIS FIRST before using any other page
// THEORY: DDL (Data Definition Language) = CREATE, DROP, ALTER
//         DML (Data Manipulation Language) = INSERT, SELECT, UPDATE, DELETE
// ============================================================

// Step 1: Connect to MySQL WITHOUT selecting a database
// We don't select a database yet because student_db may not exist
$host     = "localhost";
$user     = "root";
$password = "WJ28@krhps";

$conn = mysqli_connect($host, $user, $password);

if (!$conn) {
    die("❌ Connection Failed: " . mysqli_connect_error());
}

// -------------------------------------------------------
// TASK 1: Create the database 'student_db'
// IF NOT EXISTS = only create if it doesn't already exist
// This avoids errors if you run setup.php again
// -------------------------------------------------------
$sql_create_db = "CREATE DATABASE IF NOT EXISTS student_db";

if (mysqli_query($conn, $sql_create_db)) {
    echo "✅ Database 'student_db' created successfully!<br>";
} else {
    echo "❌ Error creating database: " . mysqli_error($conn) . "<br>";
}

// Step 2: Now select (USE) the newly created database
mysqli_select_db($conn, "student_db");

// -------------------------------------------------------
// TASK 2: Create the 'students' table
// id     - INT, AUTO_INCREMENT (auto increases: 1,2,3...)
//           PRIMARY KEY = unique identifier for each row
// name   - VARCHAR(100) = text up to 100 characters
// email  - VARCHAR(100) = text up to 100 characters
// -------------------------------------------------------
$sql_create_table = "
    CREATE TABLE IF NOT EXISTS students (
        id    INT AUTO_INCREMENT PRIMARY KEY,
        name  VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL
    )
";

if (mysqli_query($conn, $sql_create_table)) {
    echo "✅ Table 'students' created successfully!<br>";
} else {
    echo "❌ Error creating table: " . mysqli_error($conn) . "<br>";
}

// -------------------------------------------------------
// TASK 3: Insert 3 sample records
// INSERT INTO table (col1, col2) VALUES (val1, val2)
// We don't insert 'id' because AUTO_INCREMENT handles it
// -------------------------------------------------------
$sample_students = [
    ["Alice Johnson", "alice@example.com"],
    ["Bob Smith",     "bob@example.com"],
    ["Carol White",   "carol@example.com"],
];

foreach ($sample_students as $student) {
    $name  = $student[0];
    $email = $student[1];

    // SQL query to insert one record
    $sql_insert = "INSERT INTO students (name, email) VALUES ('$name', '$email')";

    if (mysqli_query($conn, $sql_insert)) {
        echo "✅ Inserted student: $name<br>";
    } else {
        echo "❌ Error inserting $name: " . mysqli_error($conn) . "<br>";
    }
}

echo "<br>🎉 Setup complete! <a href='index.php'>Go to Student Manager →</a>";

// Step 4: Close connection when done
// Good practice: always close the connection after work is done
mysqli_close($conn);
?>
