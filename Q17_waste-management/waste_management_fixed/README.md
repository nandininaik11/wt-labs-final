# ♻️ Waste Collection Management System
### PHP + MySQL | Web Technology Lab Question 17

---

## 📁 FILE STRUCTURE
```
waste-management/
├── index.php             → Entry point — redirects to report.php
├── report.php            → Public: citizens submit waste reports
├── track.php             → Public: citizens track report status
├── admin_login.php       → Admin: secure login page
├── admin_dashboard.php   → Admin: view all reports, assign authority, update status
├── logout.php            → Destroys session, redirects to login
├── db.php                → Database connection (included by all pages)
├── setup.sql             → Run ONCE in phpMyAdmin to create DB + tables
├── css/
│   └── style.css         → All custom CSS styles
└── uploads/              → Folder for uploaded images (kept empty in ZIP)
```

---

## ⚙️ SETUP & RUN COMMANDS

### Step 1 — Install XAMPP
1. Download from: https://www.apachefriends.org
2. Install XAMPP (default path: `C:\xampp`)
3. Open **XAMPP Control Panel**
4. Start **Apache** → click Start button
5. Start **MySQL** → click Start button
   → Both should show green "Running" status

### Step 2 — Place Project Files
1. Extract the ZIP file
2. Copy the `waste-management` folder to:
   - **Windows**: `C:\xampp\htdocs\waste-management`
   - **Mac**: `/Applications/XAMPP/htdocs/waste-management`

### Step 3 — Create the Database
1. Open browser → go to: `http://localhost/phpmyadmin`
2. Click **SQL** tab at the top
3. Copy-paste all contents of `setup.sql`
4. Click **Go**
   → Database `waste_management` + all tables will be created
   → Sample data will be inserted automatically

### Step 4 — Open Project in VS Code (for reading code)
1. Open VS Code
2. **File → Open Folder** → Select `waste-management`
3. You can browse and read all PHP files
4. Install extension: **PHP Intelephense** (for PHP syntax highlighting)

### Step 5 — Run the Application
Open browser → go to:
```
http://localhost/waste-management/
```
You'll be redirected to the public waste reporting page.

---

## 🖥️ EXPECTED OUTPUT (Show the Examiner This)

### Page 1 — Public Report Form (`report.php`)
- URL: `http://localhost/waste-management/`
- Shows green gradient hero banner: "Report Waste in Your Area"
- How it works: 3 steps — Report Location → Authority Dispatched → Waste Collected
- Form with sections:
  - **Your Information**: Name, Phone, Email (optional)
  - **Waste Details**: Type dropdown, Quantity dropdown, Description
  - **Waste Location**: Address, Landmark, City
  - Declaration checkbox
  - Submit button

**Demo steps for examiner:**
1. Fill Name: "Rahul Sharma", Phone: "9876543210"
2. Waste Type: Plastic, Quantity: Large
3. Location: "Near Bus Stop, MG Road", City: Pune
4. Check declaration → Submit
5. Green success alert appears: "Report #6 submitted! Authorities notified"

### Page 2 — Track Report (`track.php`)
- URL: `http://localhost/waste-management/track.php`
- Enter Report ID (e.g., 1)
- Shows report details with visual status timeline:
  - Pending ─── Assigned ─── In Progress ─── Collected ─── Closed
- Shows assigned authority team name

### Page 3 — Admin Login (`admin_login.php`)
- URL: `http://localhost/waste-management/admin_login.php`
- Red/dark login card
- **Username**: admin | **Password**: admin123

### Page 4 — Admin Dashboard (`admin_dashboard.php`)
- Stats cards: Total / Pending / Assigned / In Progress / Collected / Urgent
- Filter bar: filter by Status, City, Waste Type, Priority
- Table showing ALL reports from ALL citizens
- Each row has Edit (pencil) button → opens popup modal
  - Can change Status, Priority, Assign authority, Add notes
- Delete button with confirmation dialog

---

## 📖 COMPLETE THEORY (WT Syllabus Mapped)

### UNIT I — HTML5 & CSS & Bootstrap

#### HTML5 Forms (Used in report.php)
HTML5 introduced new input types and validation attributes:
- `type="text"` — plain text input
- `type="email"` — validates email format (browser built-in)
- `type="tel"` — shows numeric keyboard on mobile
- `type="number"` — numeric only, allows min/max
- `type="file"` — file picker dialog
- `type="checkbox"` — check box
- `<select>` — dropdown list
- `<textarea>` — multi-line text input
- `required` — HTML5 attribute: form won't submit if empty
- `minlength`, `maxlength` — character count validation
- `pattern="[0-9]{10,15}"` — regex pattern validation

HTML5 form attributes:
- `method="POST"` — sends data in HTTP body (not URL)
- `method="GET"` — sends data in URL (for searches)
- `action=""` — submit to same page
- `enctype="multipart/form-data"` — REQUIRED for file uploads

#### CSS Concepts Used
```css
/* Selectors */
.class-name { }         /* Class selector */
#id-name { }            /* ID selector */
element { }             /* Element selector */
.parent .child { }      /* Descendant selector */
.btn:hover { }          /* Pseudo-class — applies on hover */
.input:focus { }        /* Pseudo-class — applies when active */
@media (max-width:768px) { } /* Media query for responsive */
@keyframes name { }     /* Animation keyframes */

/* Box Model */
margin → border → padding → content

/* Flexbox */
display: flex;
flex-direction: column; /* Stack vertically */
justify-content: center; /* Horizontal alignment */
align-items: center;     /* Vertical alignment */
gap: 1rem;               /* Space between children */

/* Units */
px  — pixels (fixed)
rem — relative to root font size
%   — relative to parent
vh  — viewport height
```

#### Bootstrap Components Used
- `navbar`, `navbar-dark` — Navigation bar
- `container`, `row`, `col-md-6` — Responsive grid (12 columns)
- `card`, `card-body`, `card-header` — Card component
- `btn`, `btn-success`, `btn-danger` — Buttons
- `form-control`, `form-select` — Styled inputs
- `alert alert-success/danger` — Alert messages
- `badge bg-warning` — Status badges
- `table-responsive`, `table-hover` — Tables
- `modal fade` — Popup modal dialog
- `d-flex`, `gap-3`, `ms-auto` — Flexbox utilities

### UNIT II — JavaScript & DOM

#### JavaScript Concepts Used
```javascript
// Data Types
let name = "Rahul";    // String
let count = 5;         // Number
let active = true;     // Boolean
let items = [];        // Array
let obj = {};          // Object

// Control Structures
if (phone.length < 10) { ... }     // if statement
for (let i = 0; i < 5; i++) { }   // for loop
array.forEach(el => { ... });      // forEach on array

// Functions
function validateForm() { return false; }  // Named function
const fn = (x) => x * 2;                  // Arrow function

// DOM Manipulation (Unit II)
document.getElementById('myId')           // Get element by ID
document.querySelector('.class')          // CSS selector
element.value                             // Read input value
element.textContent = "text"              // Change text
element.style.display = 'none'           // Change CSS
element.classList.add('active')          // Add CSS class
element.classList.remove('active')       // Remove CSS class

// Events
element.addEventListener('click', fn)    // Attach event listener
onclick="myFunc()"                        // Inline event handler
window.addEventListener('scroll', fn)    // Browser scroll event

// Built-in APIs
alert("message")                         // Browser alert dialog
confirm("Are you sure?")                // Confirmation dialog
document.createElement('div')           // Create new DOM element
parent.appendChild(child)               // Add element to DOM
JSON.parse('{"key":"val"}')             // Parse JSON string
JSON.stringify({key:"val"})             // Convert to JSON string
```

#### DOM Levels (Unit II)
- **DOM Level 0**: Basic event handling (onclick attribute)
- **DOM Level 1**: getElementById, getElementsByTagName
- **DOM Level 2**: addEventListener, removeEventListener
- **DOM Level 3**: Keyboard events, text processing

### UNIT III — PHP (Server-Side)

#### PHP Superglobals Used
```php
$_POST['field']    // Form data (POST method)
$_GET['param']     // URL query parameters (?id=5)
$_SESSION['key']   // Session data (server-stored, cross-page)
$_FILES['upload']  // Uploaded file data
$_SERVER['REQUEST_METHOD']  // 'GET' or 'POST'
```

#### PHP Form Handling Flow
1. User submits HTML form (POST)
2. Browser sends HTTP POST request with form data
3. PHP receives data in `$_POST[]` array
4. PHP validates and sanitizes the data
5. PHP runs INSERT SQL query to store in MySQL
6. PHP sends HTTP redirect response
7. Browser follows redirect (GET request)
8. PHP reads session flash message and shows success

#### PHP Functions Used
```php
session_start()                          // Initialize session
trim($str)                               // Remove whitespace
strlen($str)                             // Count characters
substr($str, 0, 60)                      // Extract substring
implode("<br>", $array)                  // Join array to string
array_search($val, $arr)                 // Find value's index in array
in_array($val, $arr)                     // Check if value in array
empty($var)                              // Check if empty
isset($var)                              // Check if set/not null
filter_var($email, FILTER_VALIDATE_EMAIL)// Validate email format
password_hash($pw, PASSWORD_DEFAULT)     // Hash password (bcrypt)
password_verify($pw, $hash)             // Check password vs hash
json_encode($array)                      // PHP array → JSON string
date('d M Y', strtotime($datetime))     // Format date
mysqli_real_escape_string($conn, $str)  // Escape for SQL safety
```

#### PHP Sessions vs Cookies
| Sessions | Cookies |
|----------|---------|
| Data stored on SERVER | Data stored in BROWSER |
| More secure | Less secure |
| Lost when browser closes (default) | Can persist long-term |
| Referenced via $_SESSION | Referenced via $_COOKIE |
| Started with session_start() | Set with setcookie() |

#### SQL Queries Used
```sql
-- INSERT: Add new record
INSERT INTO waste_reports (name, location) VALUES ('Rahul', 'MG Road');

-- SELECT: Read records
SELECT * FROM waste_reports WHERE status='Pending' ORDER BY reported_at DESC;

-- UPDATE: Modify existing record
UPDATE waste_reports SET status='Assigned', assigned_to='Green Team' WHERE id=5;

-- DELETE: Remove record
DELETE FROM waste_reports WHERE id=5;

-- COUNT: Count rows
SELECT COUNT(*) as cnt FROM waste_reports WHERE status='Pending';

-- JOIN: Combine two tables
SELECT r.*, a.name as auth_name FROM reports r JOIN authorities a ON r.auth_id=a.id;
```

#### PHP Security Practices
1. **SQL Injection Prevention**: `mysqli_real_escape_string()` or prepared statements
2. **XSS Prevention**: `htmlspecialchars()` on all output
3. **Password Security**: `password_hash()` / `password_verify()` (never plain text)
4. **Session Security**: Store auth state server-side in sessions
5. **Input Validation**: Both client-side (JS) AND server-side (PHP)

---

## ❓ VIVA QUESTIONS & ANSWERS

**Q1: What is PHP? What does server-side mean?**
A: PHP (Hypertext Preprocessor) is a scripting language that runs on the WEB SERVER — not in the browser. When a browser requests a .php file, the server processes the PHP code, generates HTML, and sends ONLY the HTML to the browser. The browser never sees PHP code. This is different from JavaScript which runs in the browser (client-side).

**Q2: What is the difference between GET and POST methods?**
A: GET sends data in the URL (e.g., `?id=5&city=Pune`). It is visible in the browser address bar, can be bookmarked, and is used for reading/searching data. POST sends data in the HTTP request body — not visible in URL. It is used for submitting forms with sensitive data (like passwords) or data that changes the server (INSERT/UPDATE). Our report form uses POST; our search/filter forms use GET.

**Q3: Why do we use sessions in PHP?**
A: HTTP is stateless — each request is independent; the server doesn't remember the previous request. Sessions solve this by storing user data on the server and giving the browser a session ID (via a cookie called PHPSESSID). On each request, the browser sends this ID, and PHP loads the saved data. We use sessions to maintain admin login state across all admin pages.

**Q4: What is SQL Injection? How do we prevent it?**
A: SQL Injection is when a user enters SQL code into a form field to manipulate the database. Example: entering `'; DROP TABLE users; --` in a name field could delete the users table. Prevention: use `mysqli_real_escape_string()` which escapes special characters (`'`, `"`, `\`) before putting them in SQL. Better: use Prepared Statements with `?` placeholders.

**Q5: Why use `password_hash()` instead of storing plain passwords?**
A: Plain passwords in the database are a huge security risk — if the database is stolen, all passwords are exposed. `password_hash()` uses bcrypt — a one-way mathematical function that converts "admin123" to a 60-character hash. Even knowing the hash, you can't get the original password. `password_verify()` checks if a plain password matches the stored hash without reversing it.

**Q6: What is XSS? How does htmlspecialchars() prevent it?**
A: XSS (Cross-Site Scripting) is when an attacker stores JavaScript code in the database (via a form), and it executes in other users' browsers. Example: entering `<script>alert('hacked')</script>` as a name. `htmlspecialchars()` converts `<` to `&lt;` and `>` to `&gt;`, so the browser displays it as text instead of executing it as code.

**Q7: What is Bootstrap? Name any 5 Bootstrap components used.**
A: Bootstrap is a free CSS/JS framework with ready-made responsive components. Instead of writing all CSS from scratch, we use pre-built classes. Components used: (1) Navbar — top navigation bar, (2) Card — white box with header/body/footer, (3) Alert — colored message boxes, (4) Badge — small status labels, (5) Modal — popup overlay dialog, (6) Table — styled data tables with striped/hover effects.

**Q8: What is the DOM? Explain DOM levels.**
A: DOM (Document Object Model) is the browser's representation of an HTML page as a tree of objects. JavaScript can read and manipulate this tree. DOM Level 0: basic event handlers (onclick). Level 1: getElementById, getElementsByTagName. Level 2: addEventListener, removeEventListener, createDocument. Level 3: Full document manipulation including keyboard events. We use `document.getElementById()` (Level 1) and `addEventListener()` (Level 2).

**Q9: What is the difference between `include` and `require` in PHP?**
A: Both include a file's code into the current file. `include` shows a WARNING if file not found and continues execution. `require` shows a FATAL ERROR and stops execution. We use `include 'db.php'` — if db.php is missing, the page will fail when trying to use `$conn`. For critical files like database connection, `require` would be safer.

**Q10: What is ENUM in MySQL? Where have you used it?**
A: ENUM is a string data type that restricts a column to a predefined list of values. Only those exact values can be inserted. We used ENUM for: `status ENUM('Pending','Assigned','In Progress','Collected','Closed')` — ensures status can only be one of these 5 values. And `quantity ENUM('Small','Medium','Large','Very Large')`. If you try to insert an invalid value, MySQL rejects it.

**Q11: What is the POST-Redirect-GET pattern? Why use it?**
A: After a form POST submission, instead of showing the success page directly, we do a redirect: `header("Location: report.php")`. If we showed the page directly after POST and the user pressed Refresh, the browser would resubmit the form, creating duplicate records. With PRG: POST → Redirect (303) → GET, a refresh just reloads the GET page with no duplicate submission.

**Q12: How does file upload work in PHP?**
A: Files uploaded via `<form enctype="multipart/form-data">` are accessed via the `$_FILES` superglobal. PHP temporarily stores the file at a temp path. We check `$_FILES['field']['error'] === 0` (success), validate the file type and size, then use `move_uploaded_file(temp_path, permanent_path)` to save it permanently. We also generate unique filenames using `uniqid()` to prevent overwriting.

**Q13: What is a Foreign Key in SQL?**
A: A foreign key links a column in one table to the primary key of another table. It enforces referential integrity — you can't reference a record that doesn't exist. Example: `waste_reports.assigned_to` could be a foreign key to `authorities.id`. `ON DELETE CASCADE` means deleting a parent record also deletes all child records automatically.

**Q14: What is JSON? Where is it used in this project?**
A: JSON (JavaScript Object Notation) is a lightweight text format for storing and exchanging data. In this project, we use `json_encode($row)` in PHP to convert a database row (PHP array) into a JSON string, which is then passed to a JavaScript function via the onclick attribute. JavaScript receives it as an object and reads properties like `report.status`, `report.location` to pre-fill the modal form.

**Q15: Explain the MVC concept. Does this project follow it?**
A: MVC (Model-View-Controller) separates code into three layers: Model (database logic), View (HTML/UI), Controller (business logic). This basic PHP project does NOT strictly follow MVC — the database queries, business logic, and HTML are mixed in the same .php file. Laravel (mentioned in syllabus) implements MVC properly: Models for database, Blade templates for views, Controllers for logic.

---

## 📌 QUICK REFERENCE

| Credential | Value |
|------------|-------|
| Admin Username | admin |
| Admin Password | admin123 |
| phpMyAdmin URL | http://localhost/phpmyadmin |
| App URL | http://localhost/waste-management/ |
| Admin URL | http://localhost/waste-management/admin_login.php |
