# 📚 Lab Question 13: PHP Login Module with Sessions & Cookies

## 🎯 Project Overview
Complete PHP login system with user registration, authentication using sessions, and cookie-based "Remember Me" functionality. Built for Web Technology Lab practical examination.

---

## 📁 File Structure
```
lab13_php_login/
│
├── index.php              # Entry point (redirects based on login status)
├── register.php           # User registration form
├── login.php              # Login form with "Remember Me"
├── dashboard.php          # Protected user dashboard
├── profile.php            # User profile edit page
├── logout.php             # Session & cookie cleanup
│
├── includes/
│   ├── config.php         # Database configuration
│   └── functions.php      # Helper functions
│
├── database/
│   └── setup.sql          # MySQL database setup script
│
├── css/
│   └── style.css          # Stylesheet for all pages
│
├── logs/
│   └── activity.log       # Activity logging file
│
├── THEORY.md              # Complete WT syllabus theory
├── VIVA_QUESTIONS.md      # Viva preparation Q&A
└── README.md              # This file
```

---

## ⚙️ SETUP INSTRUCTIONS (Step-by-Step)

### **Prerequisites**
- ✅ XAMPP/WAMP/MAMP installed (PHP 7.4+ and MySQL 5.7+)
- ✅ VS Code (or any code editor)
- ✅ Web browser (Chrome, Firefox, Edge)

---

### **Step 1: Install XAMPP (if not installed)**

1. Download XAMPP from: https://www.apachefriends.org/
2. Install XAMPP to `C:\xampp` (Windows) or `/Applications/XAMPP` (Mac)
3. Open XAMPP Control Panel
4. Start **Apache** and **MySQL** servers

**Verify Installation:**
- Open browser: http://localhost
- You should see XAMPP dashboard

---

### **Step 2: Extract and Place Project Files**

1. Extract the `lab13_php_login.zip` file
2. Copy the `lab13_php_login` folder to:
   - **Windows:** `C:\xampp\htdocs\`
   - **Mac:** `/Applications/XAMPP/htdocs/`
   - **Linux:** `/opt/lampp/htdocs/`

**Final path should be:**
```
C:\xampp\htdocs\lab13_php_login\
```

---

### **Step 3: Create Database**

**Method 1: Using phpMyAdmin (Recommended for beginners)**

1. Open browser: http://localhost/phpmyadmin
2. Click "New" to create database
3. Database name: `login_system`
4. Collation: `utf8mb4_general_ci`
5. Click "Create"
6. Click on `login_system` database
7. Click "SQL" tab
8. Open file: `database/setup.sql`
9. Copy all SQL code
10. Paste in SQL tab
11. Click "Go"

**Method 2: Using MySQL Command Line**

```bash
# Open terminal/command prompt
# Navigate to project folder
cd C:\xampp\htdocs\lab13_php_login

# Login to MySQL (password is usually empty for XAMPP)
mysql -u root -p

# Run the SQL file
source database/setup.sql;

# Exit MySQL
exit;
```

---

### **Step 4: Configure Database Connection**

1. Open `includes/config.php` in VS Code
2. Verify these settings match your XAMPP configuration:

```php
define('DB_HOST', 'localhost');    // Should be 'localhost'
define('DB_USER', 'root');         // Default XAMPP user
define('DB_PASS', '');             // Default XAMPP password (empty)
define('DB_NAME', 'login_system'); // Database name
```

**If you changed MySQL root password:**
- Update `DB_PASS` with your password

---

### **Step 5: Open Project in VS Code**

```bash
# Open VS Code
code .

# OR from VS Code menu:
File → Open Folder → Select lab13_php_login folder
```

**VS Code Extensions (Recommended):**
- PHP Intelephense
- PHP Debug
- MySQL
- Live Server (for HTML preview)

---

### **Step 6: Run the Application**

1. Ensure Apache and MySQL are running in XAMPP
2. Open browser
3. Navigate to: **http://localhost/lab13_php_login**
4. You'll be redirected to login page

---

## 🚀 TESTING THE APPLICATION

### **Option 1: Use Test Account**
```
Username: admin
Password: password123
```

### **Option 2: Create New Account**
1. Click "Register here" on login page
2. Fill registration form:
   - Username: testuser123
   - Email: test@example.com
   - Full Name: Test User
   - Password: Test@123
   - Confirm Password: Test@123
3. Click "Register"
4. Login with new credentials

---

## 🎓 HOW IT WORKS (Theory Explanation)

### **Sessions (Server-Side Storage)**
```
1. User logs in → PHP creates session
2. Session ID generated (e.g., abc123xyz)
3. Session ID stored in cookie (PHPSESSID)
4. Session data stored on server
5. Every page request sends session cookie
6. Server retrieves session data using ID
```

### **Cookies (Client-Side Storage)**
```
1. "Remember Me" checkbox creates cookie
2. Cookie contains: username:hash
3. Cookie stored on user's browser
4. Valid for 30 days
5. Auto-login on return visit
6. Deleted on logout
```

### **Password Security**
```
1. Plain password: "MyPass123"
2. Hashed with bcrypt: "$2y$10$abc...xyz"
3. Stored in database (hashed version)
4. Login: Compare plain with hash using password_verify()
5. NEVER store plain passwords!
```

### **SQL Injection Prevention**
```php
// WRONG (Vulnerable):
$query = "SELECT * FROM users WHERE username = '$username'";

// RIGHT (Secure with Prepared Statements):
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
```

---

## 🖥️ EXPECTED OUTPUT FOR VIVA

### **1. Registration Flow**
```
Input: Fill form → Submit
↓
Server validates data
↓
Check for duplicates in database
↓
Hash password with bcrypt
↓
Insert into users table
↓
Output: "Registration successful! Please login."
↓
Redirect to login page
```

### **2. Login Flow**
```
Input: Username + Password → Submit
↓
Server queries database
↓
Verify password with password_verify()
↓
Create session with user data
↓
If "Remember Me" → Set cookie (30 days)
↓
Update last_login timestamp
↓
Output: Dashboard with user info
```

### **3. Dashboard Display**
```
Shows:
✅ User Information (name, email, username)
✅ Session Data (session ID, login time, duration)
✅ Cookie Status (PHPSESSID, remember_me)
✅ Active Sessions table
✅ Theory explanations
```

### **4. Logout Flow**
```
Click Logout
↓
Unset all session variables
↓
Delete session cookie (PHPSESSID)
↓
Delete remember_me cookie
↓
Destroy session on server
↓
Output: "Logged out successfully"
↓
Redirect to login page
```

---

## 📝 WHAT TO SHOW EXAMINER

### **Show File Structure**
```bash
# In VS Code terminal:
tree /f
# OR
dir /s
```

### **Show Database**
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click `login_system` database
3. Show `users` table with test data
4. Show `user_sessions` table

### **Demonstrate Features**

**Feature 1: Registration**
- Open: http://localhost/lab13_php_login/register.php
- Fill form with validation errors
- Show client-side validation (JavaScript)
- Show server-side validation (PHP)
- Submit and show success

**Feature 2: Login**
- Login with wrong credentials → Show error
- Login with correct credentials → Show dashboard
- Point out session data

**Feature 3: Sessions**
- Show `$_SESSION` array in dashboard
- Open browser dev tools → Application tab → Cookies
- Show PHPSESSID cookie

**Feature 4: Remember Me Cookie**
- Logout
- Login with "Remember Me" checked
- Show `remember_me` cookie in browser
- Close browser
- Reopen → Show auto-login

**Feature 5: Session Tracking**
- Show active sessions table in dashboard
- Open in different browser
- Login again
- Show multiple sessions tracked

---

## 🐛 TROUBLESHOOTING

### **Problem: "Connection failed"**
```
Solution:
1. Check MySQL is running in XAMPP
2. Verify database name is 'login_system'
3. Check config.php credentials
```

### **Problem: "404 Not Found"**
```
Solution:
1. Ensure folder is in htdocs
2. Use correct URL: http://localhost/lab13_php_login
3. Check Apache is running
```

### **Problem: "Session not working"**
```
Solution:
1. Check php.ini: session.save_path is writable
2. Clear browser cookies
3. Restart Apache server
```

### **Problem: "Cookies not setting"**
```
Solution:
1. Check browser allows cookies
2. No output before setcookie() calls
3. No spaces in cookie values
```

---

## 🔍 CODE EXPLANATION FOR VIVA

### **Key Concepts Used:**

**1. Prepared Statements (SQL Injection Prevention)**
```php
// Prevents: ' OR '1'='1
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
```

**2. Password Hashing (Security)**
```php
// Registration:
$hash = password_hash($password, PASSWORD_DEFAULT);

// Login:
password_verify($plain_password, $hashed_password);
```

**3. Session Management**
```php
session_start();                    // Initialize session
$_SESSION['user_id'] = 123;         // Store data
session_regenerate_id(true);        // Security
session_destroy();                  // Cleanup
```

**4. Cookie Management**
```php
// Set cookie (30 days)
setcookie('name', 'value', time() + (30 * 86400), "/");

// Delete cookie
setcookie('name', '', time() - 3600, "/");
```

**5. Input Sanitization (XSS Prevention)**
```php
$clean = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
```

---

## 📊 DATABASE SCHEMA

### **users table**
```sql
+-------------+--------------+------+-----+---------+----------------+
| Field       | Type         | Null | Key | Default | Extra          |
+-------------+--------------+------+-----+---------+----------------+
| id          | int(11)      | NO   | PRI | NULL    | auto_increment |
| username    | varchar(50)  | NO   | UNI | NULL    |                |
| email       | varchar(100) | NO   | UNI | NULL    |                |
| password    | varchar(255) | NO   |     | NULL    |                |
| full_name   | varchar(100) | NO   |     | NULL    |                |
| created_at  | timestamp    | NO   |     | CURRENT_TIMESTAMP  |     |
| last_login  | timestamp    | YES  |     | NULL    |                |
| login_count | int(11)      | YES  |     | 0       |                |
+-------------+--------------+------+-----+---------+----------------+
```

### **user_sessions table**
```sql
+---------------+--------------+------+-----+---------+----------------+
| Field         | Type         | Null | Key | Default | Extra          |
+---------------+--------------+------+-----+---------+----------------+
| session_id    | varchar(255) | NO   | PRI | NULL    |                |
| user_id       | int(11)      | NO   | FK  | NULL    |                |
| ip_address    | varchar(45)  | YES  |     | NULL    |                |
| user_agent    | varchar(255) | YES  |     | NULL    |                |
| created_at    | timestamp    | NO   |     | CURRENT_TIMESTAMP  |     |
| last_activity | timestamp    | NO   |     | CURRENT_TIMESTAMP  |     |
+---------------+--------------+------+-----+---------+----------------+
```

---

## 🎯 LAB QUESTION CHECKLIST

✅ **User Registration Form**
   - Username, email, password fields
   - Client & server-side validation
   - Password strength requirements
   - Duplicate checking
   - Bcrypt password hashing

✅ **Login Form**
   - Username/email + password
   - "Remember Me" checkbox
   - Session creation on success
   - Cookie for persistent login

✅ **Cookie Tracking**
   - PHPSESSID (session cookie)
   - remember_me (30-day cookie)
   - Cookie creation/deletion

✅ **Session Handling**
   - $_SESSION superglobal
   - Session variables storage
   - session_start(), session_destroy()
   - Session security (regenerate_id)

✅ **MySQL Database**
   - users table with proper schema
   - user_sessions tracking table
   - Prepared statements
   - Foreign key relationships

✅ **Security Features**
   - SQL injection prevention
   - XSS protection
   - Password hashing
   - CSRF protection (session tokens)
   - HttpOnly cookies

---

## 📚 ADDITIONAL RESOURCES

**Official Documentation:**
- PHP Sessions: https://www.php.net/manual/en/book.session.php
- PHP Cookies: https://www.php.net/manual/en/features.cookies.php
- MySQLi: https://www.php.net/manual/en/book.mysqli.php
- Password Hashing: https://www.php.net/manual/en/function.password-hash.php

**Video Tutorials:**
- PHP Login System: Search "PHP login tutorial" on YouTube
- Sessions & Cookies: Search "PHP sessions cookies explained"

---

## 👨‍🏫 FOR THE VIVA

### **Be Ready to Explain:**
1. Difference between sessions and cookies
2. How password_hash() works
3. What is SQL injection and how prepared statements prevent it
4. Session lifecycle (start → use → destroy)
5. Cookie parameters (name, value, expiry, path, domain, secure, httponly)
6. Why we use bcrypt for passwords
7. What happens when user clicks "Remember Me"
8. How session ID is transmitted between client and server

### **Be Ready to Demonstrate:**
1. Complete registration flow
2. Login with and without "Remember Me"
3. Session data in dashboard
4. Cookies in browser developer tools
5. Database tables and their relationships
6. Logout process and session cleanup

---

## 📞 CONTACT & SUPPORT

If you face any issues during setup or demonstration:

**Common Issues:**
1. Apache not starting → Check port 80 is free
2. MySQL not starting → Check port 3306 is free
3. Database connection error → Verify credentials in config.php
4. Sessions not persisting → Check session.save_path permissions

**Debug Mode:**
Add this to test pages:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

**Good luck with your viva! 🎓**

*All code is extensively commented with theory explanations for better understanding.*
