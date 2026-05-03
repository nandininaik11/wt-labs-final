<?php
// ============================================================
// test_admin_login.php — Admin Login Diagnostic Tool
// ============================================================
// Tests if admin password is correct
// DELETE THIS FILE after fixing login!
// ============================================================

include 'db.php';

echo "<h2>🔍 College Complaint System - Admin Login Diagnostic</h2>";
echo "<hr>";

$test_username = 'admin';
$test_password = 'admin123';

echo "<h3>Testing Admin Login</h3>";
echo "<strong>Username:</strong> $test_username<br>";
echo "<strong>Password:</strong> $test_password<br><br>";

// Check if admin exists
$sql = "SELECT * FROM admins WHERE username = '$test_username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $admin = mysqli_fetch_assoc($result);
    
    echo "✅ Admin found in database<br>";
    echo "Username: {$admin['username']}<br>";
    echo "Password Hash: " . substr($admin['password'], 0, 30) . "...<br><br>";
    
    // Test password verification
    if (password_verify($test_password, $admin['password'])) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px;'>";
        echo "✅ <strong style='color: green;'>PASSWORD VERIFICATION SUCCESS!</strong><br>";
        echo "→ You can login at <a href='admin_login.php'>admin_login.php</a> with:<br>";
        echo "&nbsp;&nbsp;&nbsp;Username: <code>admin</code><br>";
        echo "&nbsp;&nbsp;&nbsp;Password: <code>admin123</code><br>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px;'>";
        echo "❌ <strong style='color: red;'>PASSWORD VERIFICATION FAILED!</strong><br>";
        echo "→ The password hash in database doesn't match 'admin123'<br>";
        echo "→ Run <a href='generate_password_hash.php'>generate_password_hash.php</a> to fix this<br>";
        echo "</div>";
    }
} else {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px;'>";
    echo "❌ <strong style='color: red;'>Admin not found in database!</strong><br>";
    echo "→ Run setup.sql in phpMyAdmin to create the admin account<br>";
    echo "</div>";
}

echo "<hr>";

// Check database status
echo "<h3>📊 Database Status</h3>";

$tables = ['admins', 'students', 'complaints'];
foreach ($tables as $table) {
    $sql = "SELECT COUNT(*) as count FROM $table";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $count = mysqli_fetch_assoc($result)['count'];
        echo "✅ Table '<strong>$table</strong>': $count records<br>";
    } else {
        echo "❌ Table '<strong>$table</strong>': Not found or error<br>";
    }
}

echo "<hr>";
echo "<h3>🔧 Next Steps to Fix Admin Login</h3>";
echo "<ol>";
echo "<li>If password verification failed: Click <a href='generate_password_hash.php'>generate_password_hash.php</a></li>";
echo "<li>Copy the SQL UPDATE command it shows</li>";
echo "<li>Open phpMyAdmin → college_complaints → SQL tab</li>";
echo "<li>Paste and click 'Go'</li>";
echo "<li>Come back here and refresh this page</li>";
echo "<li>When test passes ✅, try logging in at <a href='admin_login.php'>admin_login.php</a></li>";
echo "<li><strong style='color: red;'>DELETE this file and generate_password_hash.php!</strong></li>";
echo "</ol>";

echo "<hr>";
echo "<p style='color: red;'><strong>⚠️ DELETE THIS FILE after fixing your login!</strong></p>";
?>
