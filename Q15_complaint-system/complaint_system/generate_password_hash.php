<?php
// ============================================================
// generate_password_hash.php — Password Hash Generator
// ============================================================
// Run this ONCE to generate correct password hash for admin123
// Then DELETE this file (security risk if left on server!)
// ============================================================

echo "<h2>🔐 Password Hash Generator - College Complaint System</h2>";
echo "<hr>";

// Generate hash for admin123
$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h3>Generated Password Hash:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Password</th><th>Hash</th></tr>";
echo "<tr><td><strong>$password</strong></td>";
echo "<td style='font-family: monospace; font-size: 11px;'>$hash</td></tr>";
echo "</table>";

echo "<hr>";
echo "<h3>📋 SQL Command to Update Database:</h3>";
echo "<p>Copy and paste this into phpMyAdmin SQL tab:</p>";
echo "<div style='background: #f5f5f5; padding: 15px; border-left: 4px solid #4CAF50;'>";
echo "<pre>";
echo "-- Update admin password in database\n";
echo "USE college_complaints;\n\n";
echo "UPDATE admins SET password = '$hash' WHERE username = 'admin';\n\n";
echo "-- Verify the update\n";
echo "SELECT id, username, LEFT(password, 30) as password_preview FROM admins;\n";
echo "</pre>";
echo "</div>";

echo "<hr>";
echo "<h3>✅ Admin Login Credentials After Update:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Username</th><th>Password</th></tr>";
echo "<tr><td><code>admin</code></td><td><code>admin123</code></td></tr>";
echo "</table>";

echo "<hr>";
echo "<h3>🧪 Test Your Login:</h3>";
echo "<ol>";
echo "<li>Run the SQL UPDATE command above in phpMyAdmin</li>";
echo "<li>Go to <a href='admin_login.php'>admin_login.php</a></li>";
echo "<li>Enter: username=<code>admin</code>, password=<code>admin123</code></li>";
echo "<li>If it works, come back and <strong>DELETE THIS FILE!</strong></li>";
echo "</ol>";

echo "<hr>";
echo "<p style='color: red; font-weight: bold; font-size: 18px;'>⚠️ IMPORTANT: DELETE THIS FILE AFTER USE!</p>";
echo "<p>This file can generate password hashes which is a security risk.</p>";
?>
