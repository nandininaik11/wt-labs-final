# 📋 Complaint Management System — PHP
### Lab Question 18 | Web Technology (WT)

---

## 📁 FILE STRUCTURE

```
complaint_system/
│
├── index.php              ← Entry point (auto-redirects)
├── register.php           ← User registration
├── login.php              ← User login
├── dashboard.php          ← Main dashboard (view complaints)
├── submit_complaint.php   ← File new complaint
├── view_complaint.php     ← View a single complaint in detail
├── admin.php              ← Admin panel (update status)
├── logout.php             ← Destroy session & logout
│
├── database.sql           ← SQL to create DB & tables
│
├── includes/
│   ├── db.php             ← PDO database connection
│   └── auth.php           ← Session / login helper functions
│
└── css/
    └── style.css          ← All CSS styles (CSS3 / responsive)
```

---

## ⚙️ SETUP INSTRUCTIONS (Step-by-Step)

### Step 1 — Install XAMPP
Download from: https://www.apachefriends.org/
- XAMPP gives you: Apache (web server) + PHP + MySQL — all in one

### Step 2 — Copy project to XAMPP
Copy the entire `complaint_system` folder to:
- **Windows:** `C:\xampp\htdocs\complaint_system\`
- **Mac/Linux:** `/Applications/XAMPP/htdocs/complaint_system/`

### Step 3 — Start XAMPP
Open XAMPP Control Panel → Start **Apache** and **MySQL**

### Step 4 — Create the Database
Open browser → go to: `http://localhost/phpmyadmin`
1. Click **"New"** (left sidebar)
2. Database name: `complaint_db` → click **Create**
3. Click **Import** tab
4. Click **Choose File** → select `database.sql` from your project
5. Click **Go** — tables will be created automatically

### Step 5 — Run the project
Open browser → `http://localhost/complaint_system/`

You will see the Login page. Click "Create an account" to register.

---

## 🖥️ EXPECTED OUTPUT (What to Show Examiner)

| Step | Page | What You See |
|------|------|-------------|
| 1 | `login.php` | Login form with email + password |
| 2 | `register.php` | Registration form — enter name, email, password |
| 3 | `dashboard.php` | Stats cards (Total, Pending, Resolved...) + complaints table |
| 4 | `submit_complaint.php` | Dropdown (PMC/PMT etc.), subject, description fields |
| 5 | After submit | Redirects to dashboard with "✅ Complaint submitted!" message |
| 6 | `view_complaint.php` | Full complaint details + status timeline |
| 7 | `admin.php` | All users' complaints, dropdown to update status |

### Demo Flow for Viva:
1. Register a new user → Login → Submit 2-3 complaints (PMC, PMT)
2. Show dashboard with stats counting correctly
3. Open `admin.php` (you must be user #1 = first registered) → change one complaint to "Resolved"
4. Go back to user dashboard → show it now shows "Resolved" with green badge

---

## 📖 THEORY — WT Syllabus Concepts Used

### 1. HTML5 (Unit I)
- **Form elements:** `<form>`, `<input>`, `<select>`, `<textarea>`, `<button>`
- **Form attributes:** `method="post"`, `action=""`, `required`, `type="email"`, `type="password"`
- **Semantic structure:** `<nav>`, `<div>`, `<table>`, `<thead>`, `<tbody>`
- **HTML5 validation:** `required`, `type="email"`, `maxlength` attributes

### 2. CSS (Unit I)
- **CSS Variables (Custom Properties):** `--primary: #2563eb` — define once, use anywhere
- **Flexbox:** `display:flex`, `justify-content`, `align-items` — for nav and layout
- **CSS Grid:** `display:grid`, `grid-template-columns: repeat(auto-fit, minmax(...))` — for stats
- **Responsive design:** `@media (max-width:600px)` — mobile-friendly layout
- **CSS Transitions:** `transition: border-color 0.2s` — smooth hover effects
- **Box model:** `padding`, `margin`, `border`, `border-radius`, `box-shadow`
- **Pseudo-classes:** `tr:hover`, `input:focus`, `.btn:active`

### 3. JavaScript (Unit II)
- **Event Listener:** `form.addEventListener('submit', fn)` — intercepts form submission
- **DOM Manipulation:** `document.getElementById()` to get elements
- **Client-side Validation:** check empty fields, minimum length before sending to server
- **`preventDefault()`:** stops form from submitting when validation fails

### 4. PHP (Unit III)
- **PHP Syntax:** `<?php ... ?>` tags, `echo`, variables with `$`
- **Superglobals:** `$_POST`, `$_GET`, `$_SESSION`, `$_SERVER`
- **Conditions & Loops:** `if/else`, `foreach`, `while`
- **Functions:** `requireLogin()`, `currentUser()`, `currentUserId()`
- **String Functions:** `trim()`, `strlen()`, `substr()`, `htmlspecialchars()`
- **Array Functions:** `in_array()`, `array_search()`
- **Form Handling:** reading `$_POST` data, validation, error messages
- **Cookies & Sessions:**
  - `session_start()` — starts a PHP session
  - `$_SESSION['key'] = value` — stores data server-side
  - `session_destroy()` — ends session on logout
- **MySQL with PHP (PDO):**
  - `new PDO(...)` — connect to database
  - `$pdo->prepare("SQL ?")` — prepared statement (prevents SQL injection)
  - `$stmt->execute([values])` — safely bind values
  - `$stmt->fetch()` — get one row
  - `$stmt->fetchAll()` — get all rows
  - `$pdo->lastInsertId()` — get the auto-incremented ID of last INSERT
- **Password Security:**
  - `password_hash($pass, PASSWORD_DEFAULT)` — hash before storing
  - `password_verify($input, $hash)` — verify on login
- **Exception Handling:** `try { } catch (PDOException $e) { }`
- **`header("Location: page.php")`** — HTTP redirect
- **`filter_var($email, FILTER_VALIDATE_EMAIL)`** — email validation

### 5. MySQL Concepts
- **DDL:** `CREATE DATABASE`, `CREATE TABLE`, `PRIMARY KEY`, `FOREIGN KEY`
- **DML:** `INSERT INTO`, `SELECT`, `UPDATE`, `DELETE`
- **Clauses:** `WHERE`, `ORDER BY DESC`, `GROUP BY`
- **Aggregate Functions:** `COUNT(*)`, `SUM(condition)`
- **JOIN:** `INNER JOIN users u ON c.user_id = u.id`
- **ENUM:** `status ENUM('Pending','In Progress','Resolved','Rejected')`
- **Auto Increment:** `id INT AUTO_INCREMENT PRIMARY KEY`

---

## ❓ LIKELY VIVA QUESTIONS + DETAILED ANSWERS

### PHP Questions

**Q1: What is PHP? What are its features?**
PHP (Hypertext Preprocessor) is a server-side scripting language. Features:
- Open source, free
- Runs on server (Apache/Nginx), output is HTML
- Supports MySQL, PostgreSQL, etc.
- Embedded in HTML using `<?php ?>`
- Has huge community and frameworks (Laravel)

**Q2: Difference between GET and POST methods?**
| GET | POST |
|-----|------|
| Data in URL: `?id=5` | Data in HTTP body (not visible in URL) |
| Limited data (2048 chars) | Large data allowed |
| Cached by browser | Not cached |
| Use for: reading/searching | Use for: forms, login, insert |
| `$_GET['id']` | `$_POST['email']` |

**Q3: What are sessions? How are they different from cookies?**
- **Session:** Data stored on SERVER. Browser only gets a session ID (PHPSESSID cookie). More secure. Created with `session_start()`, stored in `$_SESSION`.
- **Cookie:** Data stored on CLIENT (browser). Can be tampered with by user. Created with `setcookie()`. Use for: "remember me", preferences.
- In our system: we use sessions to remember who is logged in.

**Q4: What is SQL Injection? How does PDO prevent it?**
SQL Injection: attacker types SQL code into a form field to manipulate the database. Example: entering `' OR '1'='1` as password.

PDO Prepared Statements prevent it by separating SQL code from data:
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]); // value is bound safely, never mixed with SQL
```
The `?` is a placeholder; PDO escapes the data before inserting it.

**Q5: What is `password_hash()` and why do we use it?**
`password_hash()` converts a plain-text password into a secure hash using bcrypt algorithm. You can never reverse a hash back to the original password. On login, `password_verify($entered, $storedHash)` checks if they match.
We never store plain passwords because if the database is hacked, user passwords remain safe.

**Q6: Explain the session lifecycle in your project.**
1. User submits login form (POST)
2. PHP checks email/password against DB
3. If valid: `session_start()` then `$_SESSION['user_id'] = $user['id']`
4. Browser receives PHPSESSID cookie
5. On every page: `session_start()` reads PHPSESSID, loads session data from server
6. On logout: `session_unset()` clears variables, `session_destroy()` deletes session

**Q7: What is the PRG pattern (Post-Redirect-Get)?**
After a form submission (POST), we redirect to another page (GET). This prevents the browser from re-submitting the form if the user refreshes. In our project: after complaint is inserted, we do `header("Location: dashboard.php")` + `exit()`.

**Q8: What is `htmlspecialchars()` and why is it used?**
It converts special HTML characters to their HTML entities:
- `<` → `&lt;`
- `>` → `&gt;`
- `"` → `&quot;`
- `&` → `&amp;`
This prevents XSS (Cross-Site Scripting) attacks where user could inject `<script>` tags into the page.

### HTML/CSS/JS Questions

**Q9: What is Flexbox? Where did you use it?**
Flexbox is a CSS layout model. You set `display: flex` on a container, and child elements arrange themselves in a row (or column). Used in:
- `<nav>` to place logo and links on opposite sides using `justify-content: space-between`
- Auth box centering using `align-items: center; justify-content: center`

**Q10: What are CSS Custom Properties (Variables)?**
Defined with `--name: value` in `:root {}`, used with `var(--name)`. Benefit: change one value and it updates everywhere. Example: changing `--primary` color updates all buttons and links.

**Q11: What is client-side vs server-side validation?**
- **Client-side (JS):** Runs in browser before sending to server. Faster feedback. Can be disabled/bypassed by user.
- **Server-side (PHP):** Runs on server. Cannot be bypassed. Always required for security.
We do BOTH: JS for user experience, PHP for security.

**Q12: What is the DOM?**
Document Object Model — browser's in-memory representation of the HTML page as a tree of objects. JavaScript can manipulate it using:
- `document.getElementById('id')` — select element
- `element.value` — get input value
- `element.addEventListener('event', fn)` — listen for events

### Database Questions

**Q13: What is a Foreign Key?**
A column that references the Primary Key of another table. In our DB:
```sql
user_id INT, FOREIGN KEY (user_id) REFERENCES users(id)
```
This ensures every complaint has a valid user. `ON DELETE CASCADE` means if user is deleted, their complaints are also deleted.

**Q14: What is a JOIN? What type did you use?**
JOIN combines rows from two tables based on a related column. We used INNER JOIN:
```sql
SELECT c.*, u.name FROM complaints c JOIN users u ON c.user_id = u.id
```
INNER JOIN returns only rows where there's a match in both tables.

**Q15: Explain the difference between `fetch()` and `fetchAll()`.**
- `fetch()` — returns ONE row as an array (or `false` if no rows)
- `fetchAll()` — returns ALL matching rows as an array of arrays

---

## 🔗 Technologies Mapping to Syllabus

| Feature | Syllabus Unit |
|---------|---------------|
| HTML forms, tables, structure | Unit I — HTML5 |
| CSS, Flexbox, Grid, responsive | Unit I — CSS |
| JavaScript form validation, DOM | Unit II — JavaScript |
| PHP form handling, functions | Unit III — PHP |
| Sessions, Cookies | Unit III — PHP |
| MySQL with PHP (PDO) | Unit III — MySQL with PHP |
| File organization, includes | Unit III — PHP |

---

*Good luck with your viva! 🎯*
