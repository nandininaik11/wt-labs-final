<?php
// ============================================================
// test_login.php — Debug Login Issues
// ============================================================
// This script tests if your database has the correct data
// and if password verification works.
// ============================================================

require_once 'includes/db.php';

echo "<h2>🔍 Login System Diagnostic Tool</h2>";
echo "<hr>";

// Test credentials
$test_credentials = [
    ['email' => 'admin@complaint.com', 'password' => 'admin123', 'type' => 'Admin'],
    ['email' => 'rajesh@example.com', 'password' => 'user123', 'type' => 'User']
];

foreach ($test_credentials as $cred) {
    echo "<h3>Testing: {$cred['type']} Login</h3>";
    echo "<strong>Email:</strong> {$cred['email']}<br>";
    echo "<strong>Password:</strong> {$cred['password']}<br><br>";

    // Check if it's an admin
    if ($cred['type'] === 'Admin') {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$cred['email']]);
        $account = $stmt->fetch();

        if ($account) {
            echo "✅ Admin found in database<br>";
            echo "Username: {$account['username']}<br>";
            echo "Role: {$account['role']}<br>";
            echo "Active: " . ($account['is_active'] ? 'Yes' : 'No') . "<br>";
            echo "Password Hash: " . substr($account['password'], 0, 30) . "...<br><br>";

            // Test password
            if (password_verify($cred['password'], $account['password'])) {
                echo "✅ <strong style='color: green;'>PASSWORD VERIFICATION SUCCESS!</strong><br>";
                echo "→ You can login with these credentials<br>";
            } else {
                echo "❌ <strong style='color: red;'>PASSWORD VERIFICATION FAILED!</strong><br>";
                echo "→ The password hash in database doesn't match 'admin123'<br>";
                echo "→ Run generate_password_hash.php to fix this<br>";
            }
        } else {
            echo "❌ <strong style='color: red;'>Admin not found in database!</strong><br>";
            echo "→ Run database.sql to create admin accounts<br>";
        }
    } else {
        // Check users table
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$cred['email']]);
        $account = $stmt->fetch();

        if ($account) {
            echo "✅ User found in database<br>";
            echo "Name: {$account['name']}<br>";
            echo "Password Hash: " . substr($account['password'], 0, 30) . "...<br><br>";

            // Test password
            if (password_verify($cred['password'], $account['password'])) {
                echo "✅ <strong style='color: green;'>PASSWORD VERIFICATION SUCCESS!</strong><br>";
                echo "→ You can login with these credentials<br>";
            } else {
                echo "❌ <strong style='color: red;'>PASSWORD VERIFICATION FAILED!</strong><br>";
                echo "→ The password hash in database doesn't match 'user123'<br>";
                echo "→ Run generate_password_hash.php to fix this<br>";
            }
        } else {
            echo "❌ <strong style='color: red;'>User not found in database!</strong><br>";
            echo "→ Run database.sql to create user accounts<br>";
        }
    }

    echo "<hr>";
}

// Check database tables
echo "<h3>📊 Database Status</h3>";

try {
    // Count admins
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admins");
    $adminCount = $stmt->fetch()['count'];
    echo "✅ Admins table: {$adminCount} records<br>";

    // Count users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $userCount = $stmt->fetch()['count'];
    echo "✅ Users table: {$userCount} records<br>";

    // Count complaints
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM complaints");
    $complaintCount = $stmt->fetch()['count'];
    echo "✅ Complaints table: {$complaintCount} records<br>";

} catch (PDOException $e) {
    echo "❌ Error checking database: " . $e->getMessage() . "<br>";
    echo "→ Make sure you've imported database.sql<br>";
}

echo "<hr>";
echo "<h3>🔧 Next Steps</h3>";
echo "<ol>";
echo "<li>If password verification failed: Run <code>generate_password_hash.php</code></li>";
echo "<li>Copy the SQL commands it shows</li>";
echo "<li>Run them in phpMyAdmin</li>";
echo "<li>Come back here and refresh this page</li>";
echo "<li>When all tests pass ✅, try logging in at <a href='login.php'>login.php</a></li>";
echo "</ol>";

echo "<hr>";
echo "<p style='color: red;'><strong>⚠️ DELETE THIS FILE after fixing your login!</strong></p>";
?>
