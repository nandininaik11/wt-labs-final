# 🎓 College Complaint Registration System
### PHP + MySQL | Web Technology Lab Question 15

---

## 📁 FILE STRUCTURE
```
complaint-system/
├── index.php              → Entry point (redirects to student login)
├── student_login.php      → Student login page
├── student_register.php   → Student registration page
├── complaint.php          → File complaints + view own complaints (protected)
├── admin_login.php        → Admin login page
├── admin_dashboard.php    → Admin sees ALL complaints (protected)
├── logout.php             → Destroys session & logs out
├── db.php                 → Database connection (included everywhere)
├── setup.sql              → Run this ONCE to create DB and tables
└── css/
    └── style.css          → Custom CSS styles
```

---

## ⚙️ SETUP & RUN INSTRUCTIONS

### Step 1 — Install XAMPP (Local PHP + MySQL Server)
1. Download XAMPP from: https://www.apachefriends.org/download.html
2. Install it (default path: `C:\xampp` on Windows)
3. Open **XAMPP Control Panel**
4. Start **Apache** (the PHP web server)
5. Start **MySQL** (the database server)
   → Both should show green "Running" status

### Step 2 — Place project files
1. Extract this ZIP
2. Copy the `complaint-system` folder to:
   - Windows: `C:\xampp\htdocs\complaint-system`
   - Mac/Linux: `/Applications/XAMPP/htdocs/complaint-system`

### Step 3 — Create the Database
1. Open your browser → go to: http://localhost/phpmyadmin
2. Click **"New"** (left panel) → OR click **SQL** tab at top
3. Paste the contents of `setup.sql` into the SQL box
4. Click **Go** / **Execute**
   → You should see "college_complaints" database created

### Step 4 — Open in VS Code (optional, for reading code)
1. Open VS Code
2. File → Open Folder → Select `complaint-system`
3. Install recommended extension: **PHP Intelephense** (for PHP highlighting)

### Step 5 — Run the Application
Open your browser and go to:
```
http://localhost/complaint-system/
```
This will redirect you to the student login page.

---

## 🖥️ EXPECTED OUTPUT (What to Show the Examiner)

### Screen 1 — Student Login
- URL: `http://localhost/complaint-system/student_login.php`
- Shows a blue card with Email + Password fields
- Bottom link: "Register here" and "Admin Login →"

### Screen 2 — Student Registration
- Fill in: Name, Email, Roll No, Password, Confirm Password
- On success: Green alert "Registration successful!"

### Screen 3 — Complaint Page (after student login)
- LEFT side: Form with Category dropdown, Subject, Description
- RIGHT side: List of student's own complaints with status badges
- Navbar shows "Welcome, [Name]!" and Logout button

### Screen 4 — Admin Login
- URL: `http://localhost/complaint-system/admin_login.php`
- Dark background, red card
- Use: Username = `admin` | Password = `admin123`

### Screen 5 — Admin Dashboard
- Summary cards: Total / Pending / In Progress / Resolved counts
- Table with ALL complaints from ALL students
- Each row has a dropdown to change status + Update button

---

## 📖 COMPLETE THEORY (WT Syllabus Mapped)

### 1. HTML5 (Unit I)
HTML (HyperText Markup Language) is the skeleton of web pages.
HTML5 is the latest version with semantic tags.

Key HTML elements used in this project:
- `<!DOCTYPE html>` — Tells browser to use HTML5
- `<form method="POST">` — Submits data to server
- `<input type="text/email/password">` — User input fields
- `<select><option>` — Dropdown menus
- `<textarea>` — Multi-line text input
- `<table><tr><td>` — Tabular data display
- `<nav>` — Semantic navigation element (HTML5)

### 2. CSS & Bootstrap (Unit I)
CSS controls visual styling. Bootstrap is a CSS framework with pre-built classes.

Key concepts:
- **Selectors**: `.class`, `#id`, `element`
- **Box Model**: margin → border → padding → content
- **Flexbox**: `d-flex`, `justify-content-between`, `align-items-center`
- **Responsive Grid**: `col-md-5`, `col-md-7` (12-column grid)
- **Pseudo-classes**: `:hover`, `:focus`
- **Media Queries**: `@media (max-width: 768px)` for mobile

Bootstrap classes used:
- `container`, `row`, `col-md-X` — Grid layout
- `card`, `card-body`, `card-header` — Card component
- `btn`, `btn-primary` — Buttons
- `form-control`, `form-select` — Styled inputs
- `alert alert-danger` — Error messages
- `navbar`, `navbar-dark` — Navigation bar
- `table-striped`, `table-hover` — Table styling
- `badge` — Status pills

### 3. PHP (Unit III)
PHP (Hypertext Preprocessor) is a server-side scripting language.
It runs on the server, processes logic, and outputs HTML to the browser.

Key PHP concepts used:
- **Sessions**: `session_start()`, `$_SESSION[]` — Store user login state
- **Form Handling**: `$_POST[]`, `$_SERVER['REQUEST_METHOD']`
- **MySQLi**: `mysqli_connect()`, `mysqli_query()`, `mysqli_fetch_assoc()`
- **Security**: `password_hash()`, `password_verify()`, `mysqli_real_escape_string()`
- **Control flow**: `if/elseif/else`, `while`, `foreach`
- **String functions**: `trim()`, `strlen()`, `substr()`, `htmlspecialchars()`
- **Validation**: `filter_var($email, FILTER_VALIDATE_EMAIL)`
- **Redirect**: `header("Location: page.php")` + `exit()`

PHP Script Flow:
1. Browser sends HTTP request
2. Apache web server receives it
3. PHP engine processes the .php file
4. PHP queries MySQL for data
5. PHP generates HTML with the data embedded
6. HTML is sent back to browser as HTTP response

### 4. MySQL (Unit III)
MySQL is a Relational Database Management System (RDBMS).
Data is stored in tables with rows and columns.

SQL commands used:
- `CREATE DATABASE` — Creates a new database
- `CREATE TABLE` — Defines structure of a table
- `INSERT INTO` — Adds new records
- `SELECT * FROM` — Reads records
- `UPDATE ... SET ... WHERE` — Modifies existing records
- `DELETE FROM` — Removes records
- `INNER JOIN` — Combines data from two tables
- `COUNT(*)` — Counts rows
- `ORDER BY ... DESC` — Sorts results

Key MySQL Concepts:
- **Primary Key**: Unique identifier for each row (`AUTO_INCREMENT`)
- **Foreign Key**: Links two tables (student_id in complaints → id in students)
- **ENUM**: Column with fixed allowed values ('Pending', 'In Progress', 'Resolved')
- **TIMESTAMP**: Automatically records date/time
- **ON DELETE CASCADE**: Deletes related records automatically

### 5. Security Concepts
- **SQL Injection**: Attacker injects SQL code via input fields
  → Prevention: `mysqli_real_escape_string()` or prepared statements
- **Password Hashing**: Never store plain passwords
  → Use: `password_hash()` with bcrypt
- **XSS (Cross-Site Scripting)**: Attacker injects JavaScript
  → Prevention: `htmlspecialchars()` converts < > to &lt; &gt;
- **Session Security**: Sessions store auth state server-side
  → Session ID sent to browser as cookie

### 6. Client-Server Architecture
```
Browser (Client)                   Server
─────────────────                  ──────────────────────────
HTML + CSS + JS    ←──── HTTP ────→ Apache (PHP engine)
                   ←── Response ─── PHP processes + MySQL
                                     └── MySQL Database
```

---

## ❓ LIKELY VIVA QUESTIONS & ANSWERS

**Q1: What is PHP? Why is it server-side?**
A: PHP (Hypertext Preprocessor) is a scripting language that runs on the web server, not the browser. The server processes PHP code and sends only the resulting HTML to the client. This means the client never sees PHP code — it's hidden on the server.

**Q2: What is the difference between GET and POST methods?**
A: GET sends data in the URL (visible, bookmarkable, limited size, for fetching data). POST sends data in the HTTP request body (not visible in URL, more secure, no size limit, for sending sensitive data like passwords). We use POST for all forms.

**Q3: What is a session? How does it work?**
A: A session stores user data on the server across multiple page requests. When `session_start()` is called, PHP creates a unique session ID and sends it to the browser as a cookie (PHPSESSID). On the next request, the browser sends this ID back, and PHP loads the saved data from server memory/file.

**Q4: Why do we hash passwords? What is bcrypt?**
A: Hashing converts a password to an irreversible encrypted string. Even if the database is stolen, attackers can't recover original passwords. bcrypt is a strong hashing algorithm that is deliberately slow (making brute force attacks impractical). `password_hash()` uses bcrypt by default.

**Q5: What is SQL Injection? How do you prevent it?**
A: SQL Injection is when an attacker enters SQL code in an input field to manipulate the database query. For example, entering `' OR 1=1 --` in a login field. Prevention: use `mysqli_real_escape_string()` to escape special characters, or use prepared statements with `?` placeholders.

**Q6: What is an INNER JOIN in SQL?**
A: JOIN combines rows from two tables based on a related column. INNER JOIN returns only rows that have matching values in BOTH tables. In this project: `SELECT c.*, s.name FROM complaints c INNER JOIN students s ON c.student_id = s.id` — gets complaint data along with the student's name.

**Q7: What is Bootstrap? Why use it?**
A: Bootstrap is a free CSS/JS framework with pre-built components (cards, buttons, navbars, grid) and responsive grid system. It saves time — instead of writing CSS from scratch, we add class names like `btn-primary` or `col-md-6`. It also automatically makes pages mobile-responsive.

**Q8: What is the difference between HTML and HTML5?**
A: HTML5 is the latest version. New features include: semantic tags (`<nav>`, `<section>`, `<article>`), form input types (`type="email"`, `type="date"`), canvas, video/audio support, local storage, and geolocation API.

**Q9: What is XSS? How does htmlspecialchars() prevent it?**
A: XSS (Cross-Site Scripting) is when an attacker stores JavaScript in the database (via a form), and it executes in other users' browsers. `htmlspecialchars()` converts `<script>` to `&lt;script&gt;` so the browser displays it as text instead of executing it.

**Q10: What is a Foreign Key?**
A: A foreign key is a column in one table that references the primary key of another table. It enforces referential integrity — you can't add a complaint for a student that doesn't exist. `ON DELETE CASCADE` means deleting a student automatically deletes their complaints.

**Q11: What is the difference between mysqli and PDO?**
A: Both connect PHP to MySQL. MySQLi (MySQL Improved) works ONLY with MySQL. PDO (PHP Data Objects) is database-agnostic — works with MySQL, PostgreSQL, SQLite etc. PDO uses prepared statements more naturally. MySQLi is simpler for beginners.

**Q12: What is `session_destroy()`?**
A: It destroys all data associated with the current session on the server. Used during logout. After this, the session ID cookie in the browser becomes invalid. We also clear `$_SESSION = array()` to remove variables from memory.

**Q13: Explain the MVC concept — does this project use it?**
A: MVC (Model-View-Controller) separates code into three layers: Model (database logic), View (HTML/UI), Controller (business logic). This basic PHP project does NOT use MVC — all three are in the same file. Laravel (mentioned in syllabus) implements MVC properly.

**Q14: What is ENUM in MySQL?**
A: ENUM restricts a column to a predefined list of values. Example: `status ENUM('Pending','In Progress','Resolved')`. If you try to insert any other value, MySQL raises an error. It saves storage and enforces data integrity.

**Q15: What is the difference between `include` and `require` in PHP?**
A: Both include a file. `include` shows a warning if file not found but continues execution. `require` shows a fatal error and stops execution. We use `include 'db.php'` — if db.php is missing, the page still partially loads (though it will error on query). For critical files, use `require`.

---

## 📌 QUICK REFERENCE — ADMIN CREDENTIALS
- **Username**: admin
- **Password**: admin123
- (Set in setup.sql using password_hash)
