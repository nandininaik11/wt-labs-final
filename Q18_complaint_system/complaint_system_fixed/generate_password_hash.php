<?php
// ============================================================
// generate_password_hash.php — Password Hash Generator
// ============================================================
// This script generates password hashes that you can insert into your database.
// Run this file ONCE, copy the SQL commands, then DELETE this file (security).
// ============================================================

echo "<h2>🔐 Password Hash Generator</h2>";
echo "<p>Copy these SQL commands and run them in phpMyAdmin to set correct passwords.</p>";
echo "<hr>";

// Define passwords to hash
$passwords = [
    'admin123' => 'Admin password',
    'super123' => 'Super Admin password',
    'mod123'   => 'Moderator password',
    'user123'  => 'User password'
];

echo "<h3>Password Hashes:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Password</th><th>Hash</th></tr>";

foreach ($passwords as $password => $description) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "<tr><td><strong>$password</strong><br><small>($description)</small></td>";
    echo "<td style='font-family: monospace; font-size: 11px;'>$hash</td></tr>";
}

echo "</table>";

// Generate SQL UPDATE commands
echo "<hr><h3>📋 SQL Commands to Update Database:</h3>";
echo "<p>Copy and paste these into phpMyAdmin SQL tab:</p>";
echo "<div style='background: #f5f5f5; padding: 15px; border-left: 4px solid #4CAF50;'>";
echo "<pre>";

// Admin passwords
echo "-- Update Admin Passwords\n";
echo "UPDATE admins SET password = '" . password_hash('admin123', PASSWORD_DEFAULT) . "' WHERE email = 'admin@complaint.com';\n";
echo "UPDATE admins SET password = '" . password_hash('super123', PASSWORD_DEFAULT) . "' WHERE email = 'superadmin@complaint.com';\n";
echo "UPDATE admins SET password = '" . password_hash('mod123', PASSWORD_DEFAULT) . "' WHERE email = 'moderator@complaint.com';\n\n";

// User passwords
echo "-- Update User Passwords\n";
echo "UPDATE users SET password = '" . password_hash('user123', PASSWORD_DEFAULT) . "' WHERE email = 'rajesh@example.com';\n";
echo "UPDATE users SET password = '" . password_hash('user123', PASSWORD_DEFAULT) . "' WHERE email = 'priya@example.com';\n";
echo "UPDATE users SET password = '" . password_hash('user123', PASSWORD_DEFAULT) . "' WHERE email = 'amit@example.com';\n\n";

echo "-- Verify the changes\n";
echo "SELECT id, email, username, LEFT(password, 30) as password_preview FROM admins;\n";
echo "SELECT id, email, name, LEFT(password, 30) as password_preview FROM users;\n";

echo "</pre>";
echo "</div>";

echo "<hr>";
echo "<h3>✅ Login Credentials After Update:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Type</th><th>Email/Username</th><th>Password</th></tr>";
echo "<tr><td>Super Admin</td><td>superadmin@complaint.com</td><td><code>super123</code></td></tr>";
echo "<tr><td>Admin</td><td>admin@complaint.com</td><td><code>admin123</code></td></tr>";
echo "<tr><td>Moderator</td><td>moderator@complaint.com</td><td><code>mod123</code></td></tr>";
echo "<tr><td>User</td><td>rajesh@example.com</td><td><code>user123</code></td></tr>";
echo "<tr><td>User</td><td>priya@example.com</td><td><code>user123</code></td></tr>";
echo "<tr><td>User</td><td>amit@example.com</td><td><code>user123</code></td></tr>";
echo "</table>";

echo "<hr>";
echo "<p style='color: red; font-weight: bold;'>⚠️ IMPORTANT: DELETE THIS FILE AFTER USE!</p>";
echo "<p>This file can generate password hashes which is a security risk if left on your server.</p>";
?>
