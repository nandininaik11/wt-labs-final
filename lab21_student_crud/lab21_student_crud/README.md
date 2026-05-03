# 📚 Lab Q21 – Student Records CRUD (Edit + Delete)
## Responsive PHP Website with MySQL

---

## 📁 Complete File Structure

```
lab21_student_crud/
│
├── index.php            ← Main page: lists ALL students with Edit + Delete buttons
├── edit.php             ← Edit form: pre-filled with student data, UPDATE query
├── delete.php           ← Handler: runs DELETE query, redirects (no HTML output)
├── add.php              ← Add student form, INSERT query
│
├── includes/
│   ├── config.php       ← DB connection, helper functions, validation
│   └── layout.php       ← Shared HTML: navbar, sidebar, CSS, JS (jQuery)
│
└── sql/
    └── schema.sql       ← CREATE TABLE + INSERT seed data (run this first!)
```

---

## ⚙️ Setup Commands (Step-by-Step — Beginner Friendly)

### STEP 1: Install XAMPP
Download from: **https://www.apachefriends.org**
- Install with default settings
- After install, open **XAMPP Control Panel**

### STEP 2: Start Services
In XAMPP Control Panel, click **Start** for:
- ✅ **Apache** (web server — runs PHP)
- ✅ **MySQL** (database server)

Both should turn green.

### STEP 3: Copy Project to htdocs
```
Windows: C:\xampp\htdocs\lab21_student_crud\
Linux:   /opt/lampp/htdocs/lab21_student_crud/
Mac:     /Applications/XAMPP/htdocs/lab21_student_crud/
```

### STEP 4: Import the Database
1. Open browser → go to: **http://localhost/phpmyadmin**
2. Click **"New"** (left sidebar)
3. Type database name: `student_db` → Click **Create**
4. Click the `student_db` database
5. Click **Import** tab (top menu)
6. Click **Choose File** → select `sql/schema.sql`
7. Click **Go** (bottom of page)
8. You should see: "Import has been successfully finished"

### STEP 5: Open the Website
```
http://localhost/lab21_student_crud/
```

You should see the student records table with 10 pre-loaded students!

---

### Alternative: Run with VS Code (No XAMPP)

**Step 1: Check PHP is installed**
```bash
php --version
# Should show PHP 8.x.x
```

**Step 2: Install MySQL separately**
```bash
# Ubuntu/Debian:
sudo apt install mysql-server php-mysqli
sudo mysql_secure_installation

# Mac (Homebrew):
brew install mysql php

# Windows: Download MySQL from https://dev.mysql.com/downloads/
```

**Step 3: Import schema**
```bash
mysql -u root -p < sql/schema.sql
# Enter your MySQL password when prompted
```

**Step 4: Update DB credentials**
Edit `includes/config.php`:
```php
define('DB_PASS', 'your_mysql_password'); // Update this
```

**Step 5: Run PHP built-in server**
```bash
cd lab21_student_crud
php -S localhost:8000
```

**Step 6: Open browser**
```
http://localhost:8000
```

---

## 🖥️ Expected Output (Show to Examiner)

### Page 1: `index.php` — Student Records List

```
┌─────────────────────────────────────────────────────────────┐
│ 🎓 StudentMS              Lab Q21 · PHP CRUD · Edit & Delete │  ← Blue navbar
├──────────────┬──────────────────────────────────────────────┤
│  📋 Students │  👥 Student Records                           │
│  ➕ Add      │  [10 students]          🔍 Search...          │
│              │ ─────────────────────────────────────────────│
│              │ # │Roll│ Name      │Email│Dept │Yr│CGPA│Phone│Actions│
│              │ 1 │CS01│ Alice     │...  │CS   │3 │8.75│     │[Edit][Del]│
│              │ 2 │CS02│ Bob       │...  │CS   │2 │7.50│     │[Edit][Del]│
│              │ ...                                           │
└──────────────┴──────────────────────────────────────────────┘
```

**Stats cards at top:**
- 👥 10 — Total Students
- 📊 7.86 — Average CGPA
- 🏆 Computer Science — Top Department (4 students)

### Page 2: `edit.php?id=1` — Edit Student Form

```
┌─────────────────────────────────────────────────────────────┐
│ ✏️ Edit Student                                              │
│ Breadcrumb: Home > Students > Edit: Alice Patel             │
├─────────────────────────────────────────────────────────────┤
│ ℹ️ Editing record for Alice Patel (ID: 1 | Roll No: CS001)  │
│                                                             │
│ [Full Name *]        [Roll Number *]                        │
│  Alice Patel          CS001                                 │
│                                                             │
│ [Email Address *]    [Phone (optional)]                     │
│  alice@college.edu    9876543210                            │
│                                                             │
│ [Department *]  [Year *]     [CGPA]                        │
│  Comp Science   3rd Year     8.75                          │
│                                                             │
│ [✅ Update Student]  [Cancel]              [↩ Reset Changes]│
└─────────────────────────────────────────────────────────────┘
```

**After clicking Update:**
- Redirects to index.php
- Green alert: "✅ Student Alice Patel updated successfully!"

### Page 3: Delete Confirmation Modal

When clicking Delete button, a popup appears:

```
┌─────────────────────────────┐
│ ⚠️ Confirm Delete           │
├─────────────────────────────┤
│         🗑️                  │
│  You are about to delete:   │
│  "Alice Patel"              │
│  This CANNOT be undone.     │
├─────────────────────────────┤
│ [Cancel]   [Yes, Delete]    │
└─────────────────────────────┘
```

**After confirming:**
- Student removed from DB
- Redirects to index.php
- Flash message: "🗑️ Student Alice Patel has been permanently deleted."

### Live Search Demo:
Type "CS" in search box → only Computer Science students visible
Type "alice" → only Alice's row shown

### Mobile View:
On screen width < 768px:
- Sidebar collapses (hamburger menu ☰)
- Table converts to card layout (one card per student)
- Each cell shows "Label: Value" format

---

## 📖 Complete Theory (WT Syllabus Mapped)

---

### Unit III: PHP Introduction + Features

**What is PHP?**
PHP (Hypertext Preprocessor) is a server-side scripting language.
- Code runs on the **server** (your computer/XAMPP)
- Browser only receives the **output HTML** — it never sees PHP code
- PHP file has `.php` extension
- Can be **mixed with HTML**

```php
<?php              // PHP opening tag
echo "Hello!";    // echo outputs text to HTML
?>                 // PHP closing tag

<?= $var ?>       // Shorthand for <?php echo $var; ?>
```

**Key PHP Features:**
1. Open source and free
2. Server-side execution
3. Embeds directly in HTML
4. Built-in MySQL support (MySQLi, PDO)
5. Session and cookie management
6. File handling
7. Cross-platform (Windows, Linux, Mac)

---

### Unit III: PHP + MySQL (Core of this Lab)

**MySQLi** = MySQL Improved — PHP extension for connecting to MySQL

```php
// ── 1. CONNECT ────────────────────────────────────────────
$conn = new mysqli('localhost', 'root', '', 'student_db');
// Arguments: host, username, password, database name

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ── 2. SELECT (Read / Fetch) ──────────────────────────────
$result = $conn->query("SELECT * FROM students ORDER BY roll_no");
$students = $result->fetch_all(MYSQLI_ASSOC);
// fetch_all() → returns ALL rows as array
// MYSQLI_ASSOC → each row is ['column' => 'value'] format

// Fetch one row:
$student = $result->fetch_assoc();

// ── 3. PREPARED STATEMENT (Safe way to use user input) ────
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
// ? = placeholder (prevents SQL injection)
$stmt->bind_param("i", $id);   // "i" = integer type
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ── 4. INSERT (Create new record) ────────────────────────
$stmt = $conn->prepare(
    "INSERT INTO students (name, roll_no, email, department, year, cgpa)
     VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("ssssid", $name, $roll_no, $email, $dept, $year, $cgpa);
// "ssssid" = string, string, string, string, int, double
$stmt->execute();
$newId = $conn->insert_id;  // ID of newly inserted row

// ── 5. UPDATE (Edit existing record) ─────────────────────
$stmt = $conn->prepare(
    "UPDATE students SET name = ?, email = ? WHERE id = ?"
);
$stmt->bind_param("ssi", $name, $email, $id);
$stmt->execute();
echo $stmt->affected_rows; // 1 = success, 0 = no change

// ── 6. DELETE (Remove record) ────────────────────────────
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
echo $stmt->affected_rows; // 1 = row deleted
```

**bind_param type codes:**
| Code | Type | Example |
|------|------|---------|
| `s` | string | name, email, roll_no |
| `i` | integer | id, year |
| `d` | double (float) | cgpa (8.75) |
| `b` | blob | binary data |

---

### Unit III: PHP Form Handling

```php
// HTML Form:
// <form method="POST" action="edit.php">
// <input name="name" value="Alice">
// <button type="submit">Save</button>

// PHP receives it:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];     // 'Alice'
    $id   = $_GET['id'];        // From URL: edit.php?id=1
}
```

**GET vs POST:**
| | GET | POST |
|---|---|---|
| Data location | URL (`?id=1`) | Request body (hidden) |
| Visible to user | Yes | No |
| Bookmarkable | Yes | No |
| Use for | Reading, IDs, delete links | Saving, updating forms |
| Max data | ~2000 chars | Unlimited |

---

### Unit III: PHP Sessions (Flash Messages)

```php
session_start(); // MUST be first line

// Store message in session (survives redirect)
$_SESSION['flash'] = ['msg' => 'Saved!', 'type' => 'success'];

// After redirect, read and display it:
$flash = $_SESSION['flash'];
unset($_SESSION['flash']); // Delete so it shows only once
echo "<div class='alert alert-{$flash['type']}'>{$flash['msg']}</div>";
```

---

### Unit III: PRG Pattern (Post/Redirect/Get)

```
User submits form → PHP processes → PHP redirects → Browser GETs clean URL
                                    header('Location: index.php');
                                    exit;
```

**Why?** Without redirect:
- User presses F5 after submitting → form re-submits → duplicate records!

With PRG: F5 just reloads the clean list page — no resubmission.

---

### Unit III: PHP Validation

**Two types:**
1. **Client-side** (JavaScript) — fast, runs in browser, can be disabled
2. **Server-side** (PHP) — always runs, cannot be bypassed

```php
// Server-side validation (PHP):
$errors = [];

if (empty($name)) $errors[] = "Name is required.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
if (!preg_match('/^[0-9]{10}$/', $phone)) $errors[] = "Phone must be 10 digits.";
// preg_match = Regular Expression matching

if (empty($errors)) {
    // All valid — run the database query
} else {
    // Show errors to user
}
```

---

### Unit III: Security — SQL Injection Prevention

**VULNERABLE (NEVER DO THIS):**
```php
// Attacker enters: 1; DROP TABLE students;--
$id = $_GET['id'];
$conn->query("SELECT * FROM students WHERE id = $id");
// Becomes: SELECT * FROM students WHERE id = 1; DROP TABLE students;--
// DELETES YOUR ENTIRE TABLE!
```

**SAFE (Prepared Statements):**
```php
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
// The ? is NEVER treated as SQL — it's just data
// Attacker's input can't inject SQL code
```

**XSS Prevention:**
```php
// User stored: <script>alert('hacked')</script> in database
// WITHOUT protection:
echo $student['name']; // Script executes!

// WITH htmlspecialchars():
echo htmlspecialchars($student['name']);
// Outputs: &lt;script&gt;alert('hacked')&lt;/script&gt;
// Browser displays as text, does NOT execute
```

---

### Unit I: HTML5 (Front-End Structure)

```html
<!DOCTYPE html>           <!-- HTML5 declaration (not HTML4) -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- viewport = makes page responsive on mobile phones -->
    <title>Student Records</title>
    <link rel="stylesheet" href="bootstrap.min.css">  <!-- External CSS -->
</head>
<body>
    <!-- Semantic HTML5 elements: -->
    <header>...</header>   <!-- Top navigation -->
    <nav>...</nav>         <!-- Sidebar navigation -->
    <main>...</main>       <!-- Main content area -->
    <table>               <!-- Data table -->
        <thead><tr><th>Name</th></tr></thead>
        <tbody><tr><td>Alice</td></tr></tbody>
    </table>
    <form method="POST">   <!-- HTML5 Form -->
        <input type="text" name="name" required>
        <input type="email" name="email">
        <input type="number" min="0" max="10" step="0.01">
        <select name="dept"><option>CS</option></select>
        <button type="submit">Save</button>
    </form>
</body>
</html>
```

**HTML5 New Input Types Used:**
| Type | Browser Behavior |
|------|-----------------|
| `type="email"` | Validates @ symbol |
| `type="number"` | Numeric spinner, min/max |
| `type="tel"` | Phone keypad on mobile |
| `type="date"` | Date picker calendar |

---

### Unit I: Bootstrap 5 (Responsive CSS Framework)

**Grid System** — 12 column layout:
```html
<div class="row">
    <div class="col-md-6">Half width (medium screens)</div>
    <div class="col-md-6">Half width</div>
</div>
<!-- On mobile (< 768px): both cols become full width automatically -->

<!-- Breakpoints:
     col-sm = ≥576px (landscape phone)
     col-md = ≥768px (tablet)
     col-lg = ≥992px (laptop)
     col-xl = ≥1200px (desktop)
-->
```

**Utility Classes Used:**
```html
d-flex          ← display: flex
align-items-center  ← vertical center in flex
justify-content-between ← space between items
gap-2           ← gap: 0.5rem between items
mb-4            ← margin-bottom: 1.5rem
p-4             ← padding: 1.5rem all sides
text-primary    ← color: blue
text-danger     ← color: red
table-responsive ← horizontal scroll on small screens
```

**Components Used:**
```html
<!-- Alert (flash message) -->
<div class="alert alert-success alert-dismissible fade show">
    Saved! <button class="btn-close" data-bs-dismiss="alert"></button>
</div>

<!-- Modal (delete confirmation) -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">...</div>
            <div class="modal-body">...</div>
            <div class="modal-footer">...</div>
        </div>
    </div>
</div>
```

---

### Unit I: CSS (Custom Styles)

```css
/* CSS Custom Properties (Variables) */
:root {
    --primary: #2563eb;   /* Use: color: var(--primary) */
    --danger: #dc2626;
}

/* CSS Grid for layout */
.grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 equal columns */
    gap: 1rem;
}

/* CSS Flexbox for alignment */
.flex-center {
    display: flex;
    align-items: center;    /* Vertical center */
    justify-content: center; /* Horizontal center */
}

/* CSS Transitions (smooth animations) */
.btn {
    transition: background 0.2s, transform 0.1s;
}
.btn:hover {
    transform: translateY(-2px); /* Lift effect */
}

/* Media Query (Responsive Design) */
@media (max-width: 768px) {
    .sidebar { display: none; }    /* Hide sidebar on mobile */
    .main { margin-left: 0; }      /* Full width on mobile */
}
```

---

### Unit II: JavaScript + jQuery (Client-Side)

```javascript
// ── Vanilla JavaScript (DOM Manipulation) ─────────────────

// Select elements
document.getElementById('myId')         // By ID
document.querySelector('.myClass')      // First match
document.querySelectorAll('tr')         // All matches (NodeList)

// Modify elements
element.textContent = 'New text';       // Change text
element.setAttribute('href', 'url');   // Change attribute
element.classList.add('active');       // Add CSS class
element.classList.remove('active');    // Remove CSS class
element.classList.toggle('open');      // Toggle CSS class

// Events
element.addEventListener('click', function(e) {
    e.preventDefault();    // Stop default behavior (link navigation)
    e.stopPropagation();   // Stop event bubbling up
});

// ── jQuery ────────────────────────────────────────────────
// jQuery makes DOM manipulation shorter and cross-browser compatible

$('#id')           // Select by ID (same as getElementById)
$('.class')        // Select by class
$('tag')           // Select by tag name

$('#input').val()           // Get input value
$('#div').text('Hello')     // Set text content
$('#el').addClass('red')    // Add class
$('#el').hide()             // Hide element
$('#el').fadeOut(400)       // Fade out over 400ms

// Event handling
$('#btn').on('click', function() { ... });
$('#search').on('keyup', function() {
    const val = $(this).val().toLowerCase();
    // $(this) = the element that triggered the event
});

// jQuery .each() — loop through elements
$('tr').each(function() {
    const text = $(this).text().toLowerCase();
    $(this).toggle(text.includes(searchTerm));
    // .toggle(bool) → show if true, hide if false
});
```

---

### Unit II: DOM (Document Object Model)

**DOM = the browser's representation of HTML as a tree:**

```
document
└── html
    ├── head
    │   ├── title
    │   └── link
    └── body
        ├── nav (sidebar)
        ├── main
        │   ├── div.card
        │   │   └── table
        │   │       ├── thead
        │   │       │   └── tr → th, th, th...
        │   │       └── tbody
        │   │           └── tr → td, td, td...
        └── div#deleteModal
```

**DOM Levels:**
- **DOM Level 0**: Event handlers as attributes (`onclick="..."`)
- **DOM Level 1**: `getElementById()`, `getElementsByTagName()`
- **DOM Level 2**: `addEventListener()`, `removeEventListener()`
- **DOM Level 3**: `textContent`, `setAttribute()`, keyboard events

---

### SQL Concepts Used

```sql
-- CREATE TABLE with constraints
CREATE TABLE students (
    id          INT AUTO_INCREMENT PRIMARY KEY,  -- Unique auto-number
    name        VARCHAR(100) NOT NULL,           -- Cannot be empty
    roll_no     VARCHAR(20) NOT NULL UNIQUE,     -- Must be unique
    email       VARCHAR(150) NOT NULL UNIQUE,    -- Must be unique
    department  ENUM('CS','IT','EC') NOT NULL,   -- Only allowed values
    year        TINYINT NOT NULL,                -- Small int (1 byte)
    cgpa        DECIMAL(4,2) DEFAULT 0.00,       -- 8.75 (4 digits, 2 decimal)
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Auto date/time
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- CRUD Operations:
SELECT * FROM students WHERE id = 1;            -- Read
INSERT INTO students (name, email) VALUES (?,?); -- Create
UPDATE students SET name = ? WHERE id = ?;       -- Update (ALWAYS use WHERE!)
DELETE FROM students WHERE id = ?;               -- Delete (ALWAYS use WHERE!)

-- Aggregate Functions:
SELECT COUNT(*) FROM students;          -- Total count
SELECT AVG(cgpa) FROM students;         -- Average CGPA
SELECT department, COUNT(*) as cnt
  FROM students GROUP BY department;    -- Group by dept
```

---

## ❓ Viva Questions + Answers (In Depth)

### Basic (Q1–Q10)

**Q1. What is CRUD? How does this lab demonstrate it?**
CRUD = **C**reate, **R**ead, **U**pdate, **D**elete — the 4 basic database operations:
- **C**reate → `add.php` runs `INSERT INTO students...`
- **R**ead → `index.php` runs `SELECT * FROM students`
- **U**pdate → `edit.php` runs `UPDATE students SET... WHERE id=?`
- **D**elete → `delete.php` runs `DELETE FROM students WHERE id=?`

**Q2. What does "responsive website" mean? How is it achieved here?**
A responsive website adapts its layout to different screen sizes (desktop, tablet, mobile).

Achieved using:
- **Bootstrap 5 grid**: `col-md-6` = 2 columns on desktop, 1 column on mobile
- **CSS Media queries**: `@media (max-width: 768px)` hides sidebar on mobile
- **Viewport meta tag**: `<meta name="viewport" content="width=device-width">`
- **Fluid widths**: `width: 100%` instead of fixed pixel widths

**Q3. Explain the SQL UPDATE query structure.**
```sql
UPDATE students          -- Which table to update
SET name = ?,            -- Columns to change
    email = ?,
    cgpa = ?
WHERE id = ?;            -- Which row(s) to update
-- ⚠️ WITHOUT WHERE: ALL rows in the table get updated!
```
- `SET` lists columns and new values
- `WHERE` filters which rows to update
- Always use prepared statements (`?`) to prevent SQL injection

**Q4. Explain the SQL DELETE query structure.**
```sql
DELETE FROM students     -- Table to delete from
WHERE id = ?;            -- Which row to delete
-- ⚠️ WITHOUT WHERE: ALL rows deleted! (empty table)
```
- Permanently removes the row
- `affected_rows` tells you if deletion succeeded (1) or row didn't exist (0)

**Q5. What is a prepared statement? Why is it used?**
A prepared statement separates SQL code from data:
```php
// Template prepared once:
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
// Data bound separately — never interpreted as SQL:
$stmt->bind_param("i", $id);
$stmt->execute();
```
Purpose: **Prevents SQL Injection** — malicious input like `1; DROP TABLE students` is treated as data only, not SQL code.

**Q6. What is the difference between `fetch_assoc()` and `fetch_all()`?**
```php
// fetch_assoc(): Returns ONE row at a time as associative array
$row = $result->fetch_assoc();
// $row = ['id' => 1, 'name' => 'Alice', 'email' => '...']

// fetch_all(MYSQLI_ASSOC): Returns ALL rows at once as 2D array
$rows = $result->fetch_all(MYSQLI_ASSOC);
// $rows = [
//   ['id' => 1, 'name' => 'Alice'],
//   ['id' => 2, 'name' => 'Bob'],
//   ...
// ]

// Use fetch_assoc() when: fetching one specific student (by ID)
// Use fetch_all()   when: fetching all students for the table
```

**Q7. What is `htmlspecialchars()` and why is it important?**
It converts special HTML characters to safe entities, preventing XSS attacks:

```php
$input = '<script>alert("hacked")</script>';

echo $input;                        // DANGEROUS: script executes in browser!
echo htmlspecialchars($input);      // SAFE: outputs as visible text
// &lt;script&gt;alert(&quot;hacked&quot;)&lt;/script&gt;
```

**Always use when outputting user data into HTML.**

**Q8. What is the PRG (Post/Redirect/Get) pattern?**
After processing a form (POST), redirect to a GET request:

```php
// edit.php processes the form...
$stmt->execute();                  // UPDATE student in DB
flash("Updated!", 'success');      // Store message in session
header('Location: index.php');     // Redirect to GET request
exit;                              // Stop execution
```

**Why?** Without redirect: pressing F5 (page refresh) re-sends the POST → duplicate database operation. With PRG: F5 just reloads the clean GET page.

**Q9. How does the delete confirmation modal work?**
1. HTML: Modal is hidden on the page, waiting
2. User clicks Delete → JavaScript `confirmDelete(id, name)` is called
3. JS fills in student name: `document.getElementById('deleteStudentName').textContent = name`
4. JS sets the confirm link: `confirmDeleteBtn.setAttribute('href', 'delete.php?id=' + id)`
5. JS shows modal: `new bootstrap.Modal(deleteModal).show()`
6. User clicks "Yes, Delete" → browser follows `delete.php?id=5`
7. PHP deletes the record, redirects with flash message

**Q10. What is `session_start()` and why must it be first?**
`session_start()` initializes the session — it must be the VERY FIRST thing in the PHP file, before ANY output (even blank lines), because it needs to send HTTP headers (specifically the `Set-Cookie: PHPSESSID=...` header) to the browser, and headers must be sent before any HTML content.

```php
<?php
session_start();  // ✅ Line 1 — correct
?>
<!DOCTYPE html>   // Headers already sent above

// ❌ WRONG — will cause "Cannot send session cookie - headers already sent"
<!DOCTYPE html>
<?php session_start(); ?>
```

---

### Intermediate (Q11–Q20)

**Q11. Explain client-side vs server-side validation. Why do we need both?**

**Client-side (JavaScript):**
```javascript
form.addEventListener('submit', function(e) {
    if (email.value === '') {
        e.preventDefault(); // Stop form submission
        email.classList.add('is-invalid'); // Show error
    }
});
```
- Runs in browser (fast, no page reload)
- Gives instant feedback to user
- **Can be bypassed**: user can disable JavaScript or use tools like curl/Postman

**Server-side (PHP):**
```php
if (empty($form['email'])) $errors[] = "Email required.";
if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
```
- Runs on server (cannot be bypassed)
- ALWAYS runs regardless of client
- Protects against malicious users

**Both are needed because:** client-side for UX, server-side for security.

**Q12. What is `filter_var()` used for?**
Built-in PHP function for validating and sanitizing data:
```php
// Validate:
filter_var('test@email.com', FILTER_VALIDATE_EMAIL)  // true
filter_var('not-an-email', FILTER_VALIDATE_EMAIL)    // false
filter_var('192.168.1.1', FILTER_VALIDATE_IP)        // true
filter_var('3.14', FILTER_VALIDATE_FLOAT)            // true

// Sanitize (clean):
filter_var('<b>Hello!</b>', FILTER_SANITIZE_STRING)  // "Hello!"
filter_var('  5  ', FILTER_SANITIZE_NUMBER_INT)      // "5"
```

**Q13. What is `preg_match()` and what is a Regular Expression?**
A Regular Expression (regex) is a pattern for matching strings.

```php
preg_match('/^[A-Z0-9]{2,10}$/', $roll_no)
// ^ = start of string
// [A-Z0-9] = uppercase letters OR digits
// {2,10} = between 2 and 10 characters
// $ = end of string
// Returns: 1 (match found), 0 (no match), false (error)

preg_match('/^[0-9]{10}$/', $phone) // Exactly 10 digits

// In JavaScript:
const regex = /^[A-Za-z0-9]{2,10}$/;
regex.test(roll_no); // true or false
```

**Q14. What is the difference between `require_once` and `include`?**
```php
require_once 'config.php';  // If file missing: FATAL ERROR, stop execution
                             // _once: only loads file once (prevents duplicates)

include 'file.php';          // If file missing: WARNING, continues execution
                             // Without _once: can load same file multiple times

// In this project we use require_once because config.php is CRITICAL
// (we need the database connection — can't continue without it)
```

**Q15. What is a Bootstrap Modal and how is it triggered?**
A Modal is a popup dialog that overlays the main page.

```html
<!-- 1. Define the modal (hidden by default) -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">Are you sure?</div>
            <div class="modal-footer">
                <button data-bs-dismiss="modal">Cancel</button>
                <a id="confirmBtn" href="#">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- 2. Show via JavaScript -->
<script>
const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
modal.show();  // Show
modal.hide();  // Hide
</script>

<!-- 3. Or via data attributes -->
<button data-bs-toggle="modal" data-bs-target="#deleteModal">
    Show Modal
</button>
```

**Q16. What is `AUTO_INCREMENT` in MySQL?**
Automatically generates a unique increasing integer for the primary key:
```sql
id INT AUTO_INCREMENT PRIMARY KEY
-- First insert: id = 1
-- Second insert: id = 2
-- Even if you delete row 3, next insert gets id = 4 (never reuses)
```

PHP: `$conn->insert_id` gives the ID of the last inserted row.

**Q17. What is the `UNIQUE` constraint in SQL?**
```sql
roll_no VARCHAR(20) NOT NULL UNIQUE
-- Prevents two students from having the same roll number
-- Attempting to insert duplicate → MySQL Error #1062: Duplicate entry
```

In PHP, we check uniqueness before inserting/updating:
```php
$stmt = $conn->prepare("SELECT id FROM students WHERE roll_no = ? AND id != ?");
// AND id != ? → exclude current student (their own roll_no is OK)
```

**Q18. What is ENUM in MySQL and when to use it?**
```sql
department ENUM('Computer Science', 'IT', 'Electronics') NOT NULL
-- Stores only one of the predefined values
-- Internally stored as 1-byte integer (very efficient)
-- Attempting to insert invalid value → Warning/Error

-- Use when: column has a fixed, known set of possible values
-- Alternatives: VARCHAR + CHECK constraint, or lookup table
```

**Q19. Explain jQuery's `.toggle()` used in live search.**
```javascript
$('tr').each(function() {
    const rowText = $(this).text().toLowerCase();
    const searchTerm = $('#searchInput').val().toLowerCase();
    $(this).toggle(rowText.includes(searchTerm));
    // .toggle(true)  → shows the element  (display: block)
    // .toggle(false) → hides the element  (display: none)
    // If rowText contains the search term → show; else → hide
});
```
This gives instant filtering WITHOUT a page reload or database query — all done in the browser's memory.

**Q20. What is the `data-label` attribute used for in the table?**
```html
<td data-label="Student Name">Alice Patel</td>
```
A custom HTML5 data attribute (`data-*`) that stores metadata. Used in CSS for mobile:
```css
/* On mobile screens: */
td::before {
    content: attr(data-label); /* Shows "Student Name: " before value */
    font-weight: bold;
}
```
This converts the table into a readable card format on small screens, since the header row is hidden on mobile.

---

### Advanced (Q21–Q28)

**Q21. What is CSS `position: fixed` and how is it used in this layout?**
```css
.topbar {
    position: fixed;   /* Stays in place even when scrolling */
    top: 0;            /* Anchored to the top of the viewport */
    left: 0;
    right: 0;
    z-index: 1000;     /* Higher z-index = appears above other elements */
}
.sidebar {
    position: fixed;   /* Also stays fixed */
    top: 60px;         /* Below the topbar */
    left: 0;
    bottom: 0;         /* Stretches to bottom of screen */
}
.main-wrap {
    margin-left: 240px;    /* Offset to avoid hiding behind fixed sidebar */
    margin-top: 60px;      /* Offset to avoid hiding behind fixed topbar */
}
```

**Q22. How would you add pagination to the student list?**
```php
// Pagination logic in PHP:
$perPage   = 5;                              // Records per page
$page      = (int)($_GET['page'] ?? 1);      // Current page number
$offset    = ($page - 1) * $perPage;         // SQL offset
$totalRows = $conn->query("SELECT COUNT(*) FROM students")->fetch_row()[0];
$totalPages = ceil($totalRows / $perPage);   // Total pages needed

// Paginated query:
$stmt = $conn->prepare("SELECT * FROM students LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $perPage, $offset);

// HTML page links:
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='?page=$i'>$i</a>";
}
```

**Q23. How would you implement this with AJAX (no page reload)?**
```javascript
// Frontend: send update via fetch API
async function updateStudent(id, data) {
    const response = await fetch('api/update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, ...data })
    });
    const result = await response.json();
    if (result.success) {
        // Update DOM without page reload
        document.querySelector(`#row-${id} .name`).textContent = data.name;
    }
}

// Backend: api/update.php
// $data = json_decode(file_get_contents('php://input'), true);
// Run UPDATE query → return json_encode(['success' => true])
```

**Q24. What is Laravel and how would this app look in Laravel?**
Laravel is a PHP framework that follows MVC (Model-View-Controller) pattern:

```php
// Route (web.php):
Route::get('/students', [StudentController::class, 'index']);
Route::put('/students/{id}', [StudentController::class, 'update']);
Route::delete('/students/{id}', [StudentController::class, 'destroy']);

// Controller (StudentController.php):
public function update(Request $request, $id) {
    $request->validate(['name' => 'required|max:100', 'email' => 'required|email|unique:students,email,'.$id]);
    Student::findOrFail($id)->update($request->all());
    return redirect()->route('students.index')->with('success', 'Updated!');
}

// Model (Student.php):
class Student extends Model {
    protected $fillable = ['name', 'roll_no', 'email', 'department', 'year', 'cgpa'];
}

// View (students/index.blade.php):
@foreach($students as $student)
    <a href="{{ route('students.edit', $student->id) }}">Edit</a>
@endforeach
```

**Q25. What is SQL `DECIMAL(4,2)` vs `FLOAT` for CGPA?**
```sql
cgpa DECIMAL(4,2)
-- DECIMAL = exact precision (no rounding errors)
-- 4 = total digits, 2 = digits after decimal
-- Range: 00.00 to 99.99 (for 4,2)
-- Our CGPA: 0.00 to 10.00

-- FLOAT = approximate (can have rounding errors)
-- 8.75 stored as FLOAT might be 8.749999... internally
-- OK for scientific calculations, BAD for money/grades

-- ALWAYS use DECIMAL for: money, grades, measurements
-- Use FLOAT for: scientific data where precision is less critical
```

**Q26. How does `ON UPDATE CURRENT_TIMESTAMP` work?**
```sql
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
-- DEFAULT CURRENT_TIMESTAMP → Set to NOW() when row is INSERTED
-- ON UPDATE CURRENT_TIMESTAMP → Automatically updated to NOW() when row is MODIFIED
-- No PHP code needed — MySQL handles this automatically!
```

**Q27. What is POSTMAN and how would you use it with this project?**
Postman is an API testing tool. For this project, you could test the PHP scripts:

```
GET  http://localhost/lab21_student_crud/index.php
     → Returns HTML page with all students

POST http://localhost/lab21_student_crud/edit.php?id=1
     Body (form-data):
     name: Alice Patel Updated
     email: alice.new@college.edu
     department: Computer Science
     year: 3
     cgpa: 9.00
     → Updates student and redirects

GET http://localhost/lab21_student_crud/delete.php?id=10
    → Deletes student with id=10
```

**Q28. What improvements would make this production-ready?**
1. **Authentication**: Login page, session-based auth before accessing student records
2. **Authorization**: Admin vs viewer roles (only admin can edit/delete)
3. **HTTPS**: Encrypt data in transit (SSL/TLS certificate)
4. **CSRF tokens**: Prevent Cross-Site Request Forgery on forms
5. **Input rate limiting**: Prevent brute force attacks
6. **Pagination**: Don't load 10,000 students at once — load 20 per page
7. **Soft deletes**: Mark as deleted instead of permanent removal (`deleted_at` column)
8. **Audit log**: Track who changed what and when
9. **PDO instead of MySQLi**: Database-agnostic, supports more databases
10. **Export**: CSV/PDF export of student records

---

## 🔑 Quick Reference Card

| Concept | Code |
|---|---|
| Connect DB | `new mysqli('localhost','root','','db')` |
| Safe query | `$stmt = $conn->prepare("... WHERE id=?")` |
| Bind params | `$stmt->bind_param("i", $id)` |
| Fetch all | `$result->fetch_all(MYSQLI_ASSOC)` |
| Fetch one | `$result->fetch_assoc()` |
| Count rows | `$stmt->affected_rows` |
| Last insert ID | `$conn->insert_id` |
| Prevent XSS | `htmlspecialchars($var)` |
| Validate email | `filter_var($e, FILTER_VALIDATE_EMAIL)` |
| Regex match | `preg_match('/^[A-Z]{3}$/', $v)` |
| Flash message | `$_SESSION['flash'] = ['msg'=>'...']` |
| Redirect | `header('Location: url'); exit;` |
| Live search | `$('tr').each() → $(this).toggle(match)` |
| DOM by ID | `document.getElementById('id')` |
| jQuery select | `$('#id')` or `$('.class')` |
| Bootstrap modal | `new bootstrap.Modal(el).show()` |
