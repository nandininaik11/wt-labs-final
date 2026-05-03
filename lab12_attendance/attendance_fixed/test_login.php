<?php
// ============================================================
// test_login.php — Login Diagnostic Tool
// ============================================================
// Tests if login credentials will work
// DELETE THIS FILE after fixing login!
// ============================================================

session_start();
require_once __DIR__ . '/includes/config.php';

echo "<h2>🔍 Attendance System Login Diagnostic</h2>";
echo "<hr>";

// Test accounts
$test_accounts = [
    ['email' => 'teacher@college.com', 'password' => 'password123', 'role' => 'Teacher'],
    ['email' => 'alice@student.com', 'password' => 'password123', 'role' => 'Student (Alice)'],
    ['email' => 'bob@student.com', 'password' => 'password123', 'role' => 'Student (Bob)']
];

foreach ($test_accounts as $account) {
    echo "<h3>Testing: {$account['role']}</h3>";
    echo "<strong>Email:</strong> {$account['email']}<br>";
    echo "<strong>Password:</strong> {$account['password']}<br><br>";
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $account['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user) {
        echo "✅ User found in database<br>";
        echo "Name: {$user['name']}<br>";
        echo "Role: {$user['role']}<br>";
        if ($user['roll_no']) {
            echo "Roll No: {$user['roll_no']}<br>";
        }
        echo "Password Hash: " . substr($user['password'], 0, 30) . "...<br><br>";
        
        // Test password verification
        if (password_verify($account['password'], $user['password'])) {
            echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px;'>";
            echo "✅ <strong style='color: green;'>PASSWORD VERIFICATION SUCCESS!</strong><br>";
            echo "→ You can login with these credentials at <a href='login.php'>login.php</a><br>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px;'>";
            echo "❌ <strong style='color: red;'>PASSWORD VERIFICATION FAILED!</strong><br>";
            echo "→ The password hash doesn't match 'password123'<br>";
            echo "→ Run <a href='generate_password_hash.php'>generate_password_hash.php</a> to fix<br>";
            echo "</div>";
        }
    } else {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px;'>";
        echo "❌ <strong style='color: red;'>User not found!</strong><br>";
        echo "→ Import sql/schema.sql in phpMyAdmin<br>";
        echo "</div>";
    }
    
    echo "<hr>";
}

// Check database status
echo "<h3>📊 Database Status</h3>";

$sql = "SELECT COUNT(*) as count FROM users";
$result = $conn->query($sql);
if ($result) {
    $count = $result->fetch_assoc()['count'];
    echo "✅ Users table: <strong>$count</strong> records<br>";
} else {
    echo "❌ Users table: Not found or error<br>";
}

$sql = "SELECT COUNT(*) as count FROM attendance";
$result = $conn->query($sql);
if ($result) {
    $count = $result->fetch_assoc()['count'];
    echo "✅ Attendance table: <strong>$count</strong> records<br>";
} else {
    echo "❌ Attendance table: Not found or error<br>";
}

echo "<hr>";
echo "<h3>🔧 Next Steps</h3>";
echo "<ol>";
echo "<li>If password verification failed: Click <a href='generate_password_hash.php'>generate_password_hash.php</a></li>";
echo "<li>Copy ALL the SQL UPDATE commands it shows</li>";
echo "<li>Open phpMyAdmin → attendance_db → SQL tab</li>";
echo "<li>Paste and run the commands</li>";
echo "<li>Come back here and refresh this page</li>";
echo "<li>When all tests pass ✅, try logging in at <a href='login.php'>login.php</a></li>";
echo "<li><strong style='color: red;'>DELETE this file and generate_password_hash.php!</strong></li>";
echo "</ol>";

echo "<hr>";
echo "<p style='color: red;'><strong>⚠️ DELETE THIS FILE after fixing your login!</strong></p>";
?>
