<?php
// ============================================================
// generate_password_hash.php — Password Hash Generator
// ============================================================
// Run this ONCE to generate correct password hash for password123
// Then DELETE this file (security risk if left on server!)
// ============================================================

echo "<h2>🔐 Password Hash Generator - Attendance System</h2>";
echo "<hr>";

// Generate hash for password123
$password = "password123";
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
echo "-- Update ALL user passwords to 'password123'\n";
echo "USE attendance_db;\n\n";
echo "UPDATE users SET password = '$hash' WHERE email = 'teacher@college.com';\n";
echo "UPDATE users SET password = '$hash' WHERE email = 'alice@student.com';\n";
echo "UPDATE users SET password = '$hash' WHERE email = 'bob@student.com';\n";
echo "UPDATE users SET password = '$hash' WHERE email = 'carol@student.com';\n\n";
echo "-- Verify the update\n";
echo "SELECT id, name, email, role, LEFT(password, 30) as password_preview FROM users;\n";
echo "</pre>";
echo "</div>";

echo "<hr>";
echo "<h3>✅ Login Credentials After Update:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Role</th><th>Email</th><th>Password</th></tr>";
echo "<tr><td>Teacher</td><td><code>teacher@college.com</code></td><td><code>password123</code></td></tr>";
echo "<tr><td>Student</td><td><code>alice@student.com</code></td><td><code>password123</code></td></tr>";
echo "<tr><td>Student</td><td><code>bob@student.com</code></td><td><code>password123</code></td></tr>";
echo "<tr><td>Student</td><td><code>carol@student.com</code></td><td><code>password123</code></td></tr>";
echo "</table>";

echo "<hr>";
echo "<h3>🧪 Test Your Login:</h3>";
echo "<ol>";
echo "<li>Run the SQL UPDATE commands above in phpMyAdmin</li>";
echo "<li>Go to <a href='login.php'>login.php</a></li>";
echo "<li>Try logging in with any account above</li>";
echo "<li>If it works, come back and <strong>DELETE THIS FILE!</strong></li>";
echo "</ol>";

echo "<hr>";
echo "<p style='color: red; font-weight: bold; font-size: 18px;'>⚠️ IMPORTANT: DELETE THIS FILE AFTER USE!</p>";
echo "<p>This file can generate password hashes which is a security risk.</p>";
?>
