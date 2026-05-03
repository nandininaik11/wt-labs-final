# 🎓 Lab Q12 – Attendance System (PHP + MySQL)

---

## 📁 File Structure

```
lab12_attendance/
├── index.php               ← Dashboard (teacher stats / student stats)
├── login.php               ← Unified login (teacher + student)
├── register.php            ← Student self-registration ← Lab requirement (a)
├── logout.php              ← Clears session
├── take_attendance.php     ← Teacher marks attendance ← Lab requirement (b)
├── view_attendance.php     ← Filter/view records (teacher)
├── my_attendance.php       ← Student views own records
├── students.php            ← Teacher views all students
├── profile.php             ← User profile page
├── includes/
│   ├── config.php          ← DB connection + helper functions
│   └── layout.php          ← Shared HTML header/sidebar/footer
└── sql/
    └── schema.sql          ← Database tables + seed data
```

---

## ⚙️ Setup Commands

### Step 1: Start XAMPP
```bash
# Start Apache + MySQL from XAMPP Control Panel
# OR on Linux:
sudo /opt/lampp/lampp start
```

### Step 2: Create Database
**Option A – phpMyAdmin (recommended)**
1. Open `http://localhost/phpmyadmin`
2. Click "New" → Name: `attendance_db` → Create
3. Click "Import" → Upload `sql/schema.sql` → Go

**Option B – Terminal**
```bash
mysql -u root -p < sql/schema.sql
```

### Step 3: Place Project in htdocs
```bash
# Copy folder to XAMPP htdocs
cp -r lab12_attendance /opt/lampp/htdocs/
# OR on Windows: paste into C:\xampp\htdocs\
```

### Step 4: Update DB credentials (if needed)
Edit `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');    // your MySQL username
define('DB_PASS', '');        // your MySQL password (blank for XAMPP default)
define('DB_NAME', 'attendance_db');
```

### Step 5: Open in browser
```
http://localhost/lab12_attendance/
```

---

## 🔐 Demo Login Credentials

| Role    | Email                  | Password    |
|---------|------------------------|-------------|
| Teacher | teacher@college.com    | password123 |
| Student | alice@student.com      | password123 |
| Student | bob@student.com        | password123 |
| Student | carol@student.com      | password123 |

---

## 🖥️ Expected Output (Show to Examiner)

### 1. Student Registration (`register.php`)
- Form with: Full Name, Roll Number, Email, Department (dropdown), Password, Confirm Password
- Validates: duplicate email, duplicate roll no, password match, email format
- On success: redirects to login with flash message "Registration successful"

### 2. Login Page (`login.php`)
- Single form for both teachers and students
- Quick demo login buttons for easy testing
- On success: redirects to role-appropriate dashboard

### 3. Teacher Dashboard (`index.php`)
- Stats: Total Students, Present Today, Total Records
- Quick-action buttons: Take Attendance, View Records
- Recent attendance sessions table with date, subject, present%, progress bar

### 4. Take Attendance (`take_attendance.php`) ← CORE FEATURE
- Date picker (defaults to today)
- Subject input with autocomplete
- Table with columns: #, Roll No, Student Name, Department, Present ✓, Absent ✓
- "Mark All Present" / "Mark All Absent" buttons
- Live counter: "Present: 3 | Absent: 1"
- Present ↔ Absent checkboxes are synced (mutually exclusive)
- Rows turn green (present) or red (absent) in real time
- Submit saves to MySQL with INSERT ... ON DUPLICATE KEY UPDATE

### 5. View Records (`view_attendance.php`)
- Filter by Date, Subject, Student
- Shows summary stats for filtered set
- Table: Roll No, Name, Department, Date, Subject, Status badge

### 6. Student Dashboard
- Circular % gauge for attendance
- Warning if below 75%: "You need X more consecutive classes"
- Recent 5 attendance records

### 7. My Attendance (`my_attendance.php`)
- Per-subject cards with progress bars
- Full history table with green/red row colors

---

## 📖 Complete Theory (WT Syllabus – Unit III)

### 1. MySQL with PHP (Core Concept)

PHP connects to MySQL using two main APIs:
- **MySQLi** (MySQL Improved) – used in this lab
- **PDO** (PHP Data Objects) – database-agnostic

#### MySQLi Basics

```php
// 1. Connect
$conn = new mysqli('localhost', 'root', '', 'attendance_db');
if ($conn->connect_error) die("Failed: " . $conn->connect_error);

// 2. Simple query
$result = $conn->query("SELECT * FROM users");
while ($row = $result->fetch_assoc()) {
    echo $row['name'];
}

// 3. Prepared statement (prevents SQL injection)
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);   // "s" = string, "i" = int
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

// 4. Insert
$stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $email);
$stmt->execute();
echo $conn->insert_id; // last inserted ID
```

### 2. PHP Sessions (Recap from Unit III)

```php
session_start();                        // Always first
$_SESSION['user_id'] = $user['id'];    // Store login data
$_SESSION['role']    = $user['role'];  // Store role

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Logout
session_unset();
session_destroy();
```

### 3. Password Security

```php
// Hash on registration (bcrypt, irreversible)
$hash = password_hash($password, PASSWORD_BCRYPT);
// Store $hash in database

// Verify on login
if (password_verify($input_password, $hash_from_db)) {
    // Correct!
}
```
Never store plain text passwords. `password_hash()` automatically adds a salt.

### 4. Form Handling in PHP

```php
// GET vs POST
// GET  – data in URL, bookmarkable, for fetching
// POST – data in body, for submitting (forms, login)

// Read POST data
$name = trim($_POST['name'] ?? '');      // trim whitespace
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

// Sanitize for output (prevent XSS)
echo htmlspecialchars($user_input);
```

### 5. HTML Forms with PHP Backend

```html
<form method="POST" action="register.php">
    <input type="text" name="name" required>
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Register</button>
</form>
```

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form
}
```

### 6. SQL Concepts Used

```sql
-- Tables
CREATE TABLE users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    email    VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role     ENUM('student','teacher') DEFAULT 'student'
);

-- INSERT with conflict handling
INSERT INTO attendance (student_id, date, status, subject, marked_by)
VALUES (1, '2024-01-15', 'present', 'Web Technology', 3)
ON DUPLICATE KEY UPDATE status = VALUES(status);
-- This handles re-taking attendance without duplicates

-- JOIN
SELECT a.*, u.name, u.roll_no
FROM attendance a
JOIN users u ON u.id = a.student_id
WHERE a.date = '2024-01-15';

-- Aggregate
SELECT subject,
       COUNT(*) as total,
       SUM(status='present') as present_count
FROM attendance
GROUP BY subject;
```

### 7. Bootstrap 5 (Unit I – CSS)

Used for responsive layout:
```html
<!-- Grid System -->
<div class="row">
    <div class="col-md-6">Half width on medium+</div>
</div>

<!-- Alerts -->
<div class="alert alert-success">Success!</div>
<div class="alert alert-danger">Error!</div>

<!-- Tables -->
<table class="table table-hover table-striped">...</table>

<!-- Forms -->
<input class="form-control">
<select class="form-select">
<button class="btn btn-primary">
```

### 8. JavaScript in take_attendance.php (Unit II)

```javascript
// DOM manipulation
document.getElementById('p-' + id).checked = true;

// Event handling
checkbox.addEventListener('change', function() { ... });

// Live counter update
const presentCount = document.querySelectorAll('.present-cb:checked').length;
document.getElementById('presentCount').textContent = presentCount;

// Row color change
row.style.background = isPresent ? '#f0fff4' : '#fff5f5';
```

---

## ❓ Viva Questions + Answers

### Basic (Q1–Q10)

**Q1. What is the purpose of this system?**
A web-based attendance management system where:
- Students register themselves (requirement a)
- Teachers take attendance online using checkboxes with roll no and name (requirement b)
- Both can view attendance records with filtering

**Q2. What database and server-side language is used?**
PHP (server-side scripting) + MySQL (relational database). Connected using the MySQLi extension. The front-end uses HTML5, Bootstrap 5, and vanilla JavaScript.

**Q3. Explain the two database tables.**
- `users`: Stores both students and teachers. Differentiated by the `role` ENUM column (`student`/`teacher`). Students have a `roll_no` field.
- `attendance`: Stores one record per student per date per subject. Has a `UNIQUE KEY` on `(student_id, date, subject)` to prevent duplicates.

**Q4. How does student registration work?**
1. Student fills the form (`register.php`) with name, roll_no, email, department, password
2. PHP validates: non-empty fields, valid email, password length, password match
3. Checks MySQL for duplicate email/roll_no using prepared statements
4. If valid: hashes the password with `password_hash()` and inserts into `users` table
5. Redirects to login with success message

**Q5. How does login work?**
1. Teacher/student enters email + password
2. PHP queries `users` table WHERE email matches
3. Uses `password_verify()` to compare submitted password against bcrypt hash
4. If valid: stores user data in `$_SESSION` and redirects to dashboard
5. The `role` in session determines which dashboard/features to show

**Q6. How does taking attendance work?**
1. Teacher selects date and subject
2. System loads all students ordered by roll_no
3. Teacher checks "Present" checkboxes (default: all present)
4. On submit: PHP loops through all student IDs and inserts/updates each as present or absent
5. Uses `INSERT ... ON DUPLICATE KEY UPDATE` to handle re-marking

**Q7. What SQL statement prevents duplicate attendance records?**
```sql
INSERT INTO attendance (student_id, date, status, subject, marked_by)
VALUES (?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE status = VALUES(status), marked_by = VALUES(marked_by)
```
The `UNIQUE KEY (student_id, date, subject)` constraint means trying to insert a duplicate triggers the UPDATE clause instead.

**Q8. What is a prepared statement and why do you use it?**
A prepared statement separates SQL code from data:
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
```
Benefits:
- **Prevents SQL Injection**: Data is never interpreted as SQL code
- **Performance**: Query plan compiled once, executed multiple times
- **Cleaner code**: Separates logic from data

**Q9. What is SQL Injection and how is it prevented?**
SQL Injection is when a malicious user enters SQL code as input:
```
Email: ' OR '1'='1
Password: anything
→ Becomes: SELECT * FROM users WHERE email='' OR '1'='1'
→ Always true! Bypasses login!
```
Prevention: Always use **prepared statements** — bind_param() escapes the data so it's treated as a string, never as SQL.

**Q10. How does the 75% attendance warning work?**
```php
$pct = ($total > 0) ? round(($present / $total) * 100) : 0;
if ($pct < 75) {
    $needed = ceil(0.75 * $total - $present);
    echo "You need $needed more consecutive classes to reach 75%";
}
```

---

### Intermediate (Q11–Q20)

**Q11. What is the difference between MySQLi and PDO?**
| | MySQLi | PDO |
|---|---|---|
| Databases | MySQL only | 12+ databases |
| Interface | Procedural + OOP | OOP only |
| Named params | No (`?` only) | Yes (`:name`) |
| Performance | Slightly faster for MySQL | More portable |
| Used when | MySQL-only project | Multi-DB project |

**Q12. Explain `password_hash()` and `PASSWORD_BCRYPT`.**
Bcrypt is a slow hashing algorithm designed for passwords:
- Automatically generates and embeds a random salt
- The "cost factor" (default 10) means it runs 2^10 = 1024 rounds
- Slow by design to make brute force attacks impractical
- Same password hashed twice gives different results (due to random salt)
- `password_verify()` extracts the salt from the hash to compare

**Q13. What is the ENUM data type in MySQL?**
```sql
role ENUM('student','teacher') NOT NULL DEFAULT 'student'
```
ENUM restricts the column to a predefined set of values. It stores as an integer internally (1 byte or 2 bytes) but displays as a string. Attempting to insert any other value causes an error. Alternative: `VARCHAR` with a CHECK constraint.

**Q14. Explain the JOIN in `view_attendance.php`.**
```sql
SELECT a.*, u.name, u.roll_no
FROM attendance a
JOIN users u ON u.id = a.student_id
WHERE a.date = '2024-01-15'
```
- `attendance` table has `student_id` (foreign key)
- `JOIN` fetches matching row from `users` where `users.id = attendance.student_id`
- **INNER JOIN** (default): Only rows with matches in both tables
- **LEFT JOIN**: All attendance rows + NULL for missing user data

**Q15. How are FOREIGN KEYS used in this database?**
```sql
FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
```
- Ensures `student_id` in attendance must exist in `users.id`
- `ON DELETE CASCADE`: If a student is deleted, all their attendance records are deleted automatically
- Maintains **referential integrity** — no orphan records

**Q16. What is `htmlspecialchars()` and why use it?**
It converts special HTML characters to entities:
```php
htmlspecialchars('<script>alert("xss")</script>')
// Output: &lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;
```
Prevents **Cross-Site Scripting (XSS)**: without it, a malicious user could store `<script>` tags in the database that execute when viewed. Always sanitize user data before outputting it in HTML.

**Q17. Explain the role-based access control in this system.**
```php
function requireRole(string $role): void {
    requireLogin();           // Redirect if not logged in
    if ($_SESSION['role'] !== $role) {
        header('Location: index.php'); exit;  // Wrong role
    }
}

// Usage
requireRole('teacher');  // Only teachers can access take_attendance.php
requireRole('student');  // Only students can access my_attendance.php
```

**Q18. How does the "Mark All Present/Absent" button work?**
```javascript
function markAll(present) {
    document.querySelectorAll('.present-cb').forEach(cb => {
        cb.checked = present;
        document.getElementById('a-' + cb.value).checked = !present;
        updateRow(cb.value, present);
    });
    updateCounter();
}
```
`querySelectorAll` selects all present checkboxes, sets them to the desired state, and syncs the absent checkbox to the opposite.

**Q19. What does `ON DUPLICATE KEY UPDATE` do?**
When inserting a row that would violate a UNIQUE constraint, instead of throwing an error, MySQL executes the UPDATE clause:
```sql
INSERT INTO attendance (student_id, date, status) VALUES (1, '2024-01-15', 'present')
ON DUPLICATE KEY UPDATE status = 'present';
```
This is an UPSERT (UPDATE + INSERT) pattern — if the record exists, update it; if not, insert it. The `UNIQUE KEY (student_id, date, subject)` triggers this.

**Q20. What is the difference between GET and POST?**
| | GET | POST |
|---|---|---|
| Data location | URL query string | Request body |
| Visibility | Visible in URL | Hidden |
| Bookmarkable | Yes | No |
| Max data | ~2000 chars (URL limit) | No practical limit |
| Idempotent | Yes (safe to repeat) | No (creates/modifies data) |
| Used for | Fetching, filtering | Forms, login, creating |

---

### Advanced (Q21–Q28)

**Q21. What security vulnerabilities exist and how are they addressed?**
1. **SQL Injection** → Prepared statements with bind_param
2. **XSS (Cross-Site Scripting)** → htmlspecialchars() on all output
3. **Password storage** → bcrypt hashing, never plaintext
4. **Unauthorized access** → requireRole() on every protected page
5. **CSRF (not implemented)** → Production would add CSRF tokens to forms

**Q22. How would you add CSRF protection?**
```php
// In session after login:
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// In every form:
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

// On form submission:
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token mismatch!");
}
```

**Q23. What is `AUTO_INCREMENT` in MySQL?**
It automatically generates a unique integer for the `id` column on each INSERT. Starts at 1, increments by 1. PHP retrieves the last inserted ID with `$conn->insert_id`. It's used as the PRIMARY KEY to uniquely identify each row.

**Q24. How would you implement this in Laravel (as mentioned in syllabus)?**
```php
// Eloquent Model
class Attendance extends Model {
    protected $fillable = ['student_id','date','status','subject','marked_by'];
}

// Migration
Schema::create('attendance', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
    $table->date('date');
    $table->enum('status', ['present','absent']);
    $table->timestamps();
});

// Controller
public function store(Request $request) {
    $request->validate(['subject' => 'required', 'att_date' => 'required|date']);
    foreach ($request->students as $studentId) {
        Attendance::updateOrCreate(
            ['student_id' => $studentId, 'date' => $request->att_date, 'subject' => $request->subject],
            ['status' => in_array($studentId, $request->present ?? []) ? 'present' : 'absent']
        );
    }
    return redirect()->back()->with('success', 'Attendance saved!');
}
```

**Q25. What is Bootstrap's grid system used in this project?**
Bootstrap uses a 12-column grid:
```html
<div class="row">
    <div class="col-md-4">4/12 = 1/3 width on medium screens</div>
    <div class="col-md-8">8/12 = 2/3 width on medium screens</div>
</div>
<!-- col-md means applies from 768px+ width (tablet/desktop) -->
<!-- On mobile, all columns stack to full width -->
```

**Q26. Explain the sidebar navigation pattern.**
The sidebar uses Bootstrap's flexbox column layout:
```html
<nav class="col-md-2 sidebar">         <!-- 2 of 12 columns -->
    <ul class="nav flex-column">
        <li><a class="nav-link active" href="...">Dashboard</a></li>
    </ul>
</nav>
<main class="col-md-10 main-content">  <!-- 10 of 12 columns -->
    <!-- Page content -->
</main>
```
The `active` class is set dynamically by passing `$activePage` to `pageHeader()`.

**Q27. How would you export attendance to CSV?**
```php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="attendance.csv"');
$out = fopen('php://output', 'w');
fputcsv($out, ['Roll No', 'Name', 'Date', 'Subject', 'Status']);
foreach ($records as $r) {
    fputcsv($out, [$r['roll_no'], $r['name'], $r['date'], $r['subject'], $r['status']]);
}
fclose($out);
exit;
```

**Q28. What is the difference between authentication and authorization?**
- **Authentication**: Verifying identity — "Who are you?" → Login with email + password
- **Authorization**: Verifying permissions — "What can you do?" → `requireRole('teacher')`

In this system:
- Authentication: `password_verify()` on login
- Authorization: `$_SESSION['role']` checked on every protected page via `requireRole()`

---

## 🔑 Key Concepts Summary

| Concept | Implementation |
|---|---|
| Student self-registration | `register.php` with validation + password_hash |
| Teacher takes attendance | `take_attendance.php` with checkboxes, roll_no, name |
| MySQL connectivity | MySQLi with prepared statements |
| SQL Injection prevention | bind_param() on all user input |
| XSS prevention | htmlspecialchars() on all output |
| Role-based access | requireRole() using $_SESSION['role'] |
| Duplicate attendance | INSERT ... ON DUPLICATE KEY UPDATE |
| Password security | password_hash() + password_verify() |
| Form validation | Server-side in PHP (+ HTML5 required attrs) |
| Responsive UI | Bootstrap 5 grid + components |
