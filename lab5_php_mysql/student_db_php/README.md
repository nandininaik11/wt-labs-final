# 🎓 Lab Question 5 — PHP + MySQL Database Operations
### Student Database Manager | Web Technology (WT) Lab

---

## 📁 File Structure

```
student_db_php/
├── includes/
│   └── db.php          ← Database connection (mysqli)
├── css/
│   └── style.css       ← Custom CSS styles
├── index.php           ← Display all + Delete student
├── insert.php          ← Add new student (INSERT)
├── update.php          ← Edit student (UPDATE)
├── setup.php           ← Creates DB + Table + Sample data
└── README.md           ← This file
```

---

## ⚙️ Setup & Run Instructions (Step by Step)

### Prerequisites
- Install **XAMPP** (includes Apache + MySQL + PHP)
  - Download: https://www.apachefriends.org/

### Steps:

**1. Start XAMPP**
- Open XAMPP Control Panel
- Click **Start** next to `Apache`
- Click **Start** next to `MySQL`

**2. Copy project folder**
```
Copy the entire 'student_db_php' folder into:
C:\xampp\htdocs\student_db_php\
```

**3. Open browser and run setup**
```
http://localhost/student_db_php/setup.php
```
This creates the database, table, and sample records.

**4. Open the main app**
```
http://localhost/student_db_php/index.php
```

---

## 📖 Theory — WT Syllabus Explained Simply

### What is PHP?
PHP (Hypertext Preprocessor) is a **server-side scripting language** used to create dynamic web pages.
- PHP runs on the **server** (not in browser)
- It processes logic (like database operations) and sends HTML to the browser
- Files use `.php` extension
- PHP code is written inside `<?php ... ?>` tags

### What is MySQL?
MySQL is a **Relational Database Management System (RDBMS)**
- Stores data in **tables** (like Excel sheets)
- Each table has **columns** (fields) and **rows** (records)
- Uses **SQL** (Structured Query Language) to work with data

### What is mysqli?
- `mysqli` = MySQL Improved Extension
- A built-in PHP library to connect PHP with MySQL
- `mysqli_connect()` = establishes connection
- `mysqli_query()` = runs SQL on the database
- `mysqli_fetch_assoc()` = fetches one row as array

### CRUD Operations
| Letter | Meaning | SQL Command | PHP page |
|--------|---------|-------------|----------|
| C | Create | INSERT INTO | insert.php |
| R | Read | SELECT | index.php |
| U | Update | UPDATE SET | update.php |
| D | Delete | DELETE FROM | index.php |

### PHP Form Handling
- `method="POST"` → data sent via HTTP body (secure, not visible in URL)
- `method="GET"` → data sent via URL (visible, used for reading)
- `$_POST['field_name']` → reads POST form data
- `$_GET['param']` → reads URL parameter

### SQL Injection Prevention
- `mysqli_real_escape_string($conn, $value)` → escapes special characters like `'`, `"`, `\`
- Prevents malicious SQL like: `' OR 1=1 --`
- Always sanitize user input before using in SQL!

### Session vs Cookie (for viva)
| Feature | Session | Cookie |
|---------|---------|--------|
| Stored on | Server | Client browser |
| Security | More secure | Less secure |
| Size limit | No limit | 4KB |
| Lifespan | Until browser closes | Set by programmer |

---

## 🖥️ Expected Output

### setup.php shows:
```
✅ Database 'student_db' created successfully!
✅ Table 'students' created successfully!
✅ Inserted student: Alice Johnson
✅ Inserted student: Bob Smith
✅ Inserted student: Carol White

🎉 Setup complete! Go to Student Manager →
```

### index.php shows:
- A table with 3 students (ID, Name, Email, Actions)
- "Edit" button → goes to update.php
- "Delete" button → removes record, shows alert
- "Add New Student" button → goes to insert.php

### insert.php shows:
- A form with Name and Email fields
- Submit saves to DB and redirects to index
- Success alert: "✅ Student added successfully!"

### update.php shows:
- Form pre-filled with existing student data
- Submit updates DB → redirects to index
- Info alert: "✏️ Student updated successfully!"

---

## ❓ Likely Viva Questions + Answers

**Q1: What is mysqli and why use it instead of mysql?**
A: `mysqli` is MySQL Improved Extension in PHP. The old `mysql_*` functions were deprecated in PHP 5.5 and removed in PHP 7. `mysqli` is faster, supports prepared statements, and is more secure.

**Q2: What is the difference between mysqli and PDO?**
A: `mysqli` works only with MySQL databases. `PDO` (PHP Data Objects) supports multiple databases (MySQL, PostgreSQL, SQLite etc.). PDO uses prepared statements which are safer against SQL injection.

**Q3: What is SQL Injection? How do you prevent it?**
A: SQL Injection is an attack where a malicious user enters SQL code in a form field to manipulate the database. Example: entering `' OR 1=1 --` in a password field could log in without a password.
Prevention:
1. `mysqli_real_escape_string()` — escapes special characters
2. Prepared Statements with `?` placeholders
3. Input validation before using data

**Q4: Explain the difference between POST and GET methods.**
A: GET sends data in the URL (visible, limited size, used for reading). POST sends data in the HTTP request body (hidden, no size limit, used for creating/updating). For sensitive data like passwords, always use POST.

**Q5: What is AUTO_INCREMENT in MySQL?**
A: AUTO_INCREMENT automatically assigns a unique incrementing number to a column each time a new row is inserted. Used for primary keys. First row gets id=1, next gets id=2, etc.

**Q6: What is PRIMARY KEY?**
A: A PRIMARY KEY uniquely identifies each row in a table. It must be unique and cannot be NULL. In our students table, `id` is the primary key.

**Q7: What does mysqli_fetch_assoc() return?**
A: It returns one row from the result set as an associative array. Keys are column names. Example: `$row['name']` gives the student's name.

**Q8: Why do we use htmlspecialchars() when displaying data?**
A: `htmlspecialchars()` converts special HTML characters like `<`, `>`, `&` into safe HTML entities. This prevents XSS (Cross-Site Scripting) attacks where users insert malicious JavaScript in the database.

**Q9: What is the difference between DELETE and TRUNCATE in MySQL?**
A: `DELETE FROM students WHERE id=1` removes specific rows (can use WHERE clause). `TRUNCATE TABLE students` removes ALL rows and resets AUTO_INCREMENT counter. DELETE is DML, TRUNCATE is DDL.

**Q10: What does the WHERE clause do? What happens without it?**
A: WHERE filters which rows are affected. In UPDATE without WHERE, ALL rows are updated. In DELETE without WHERE, ALL rows are deleted. It is critical for safe database operations.

**Q11: Explain the connection steps in PHP-MySQL.**
A: 
1. `mysqli_connect(host, user, pass, db)` — creates connection
2. `mysqli_query($conn, $sql)` — executes SQL
3. `mysqli_fetch_assoc($result)` — reads data row by row
4. `mysqli_close($conn)` — closes connection

**Q12: What is the difference between VARCHAR and TEXT in MySQL?**
A: `VARCHAR(n)` stores strings up to n characters (max 65535). `TEXT` stores longer strings (up to 65535 bytes). VARCHAR is faster for shorter strings. We use VARCHAR(100) for name and email.

**Q13: What is Bootstrap and why use it?**
A: Bootstrap is a CSS framework with pre-built classes for layout, buttons, tables, forms etc. Using `class="btn btn-success"` gives a green button without writing custom CSS. It also makes websites responsive (work on mobile).

**Q14: What does filter_var() do?**
A: `filter_var($email, FILTER_VALIDATE_EMAIL)` checks if the email format is valid (has @, domain, etc.). It's a built-in PHP function for validation. Returns false if invalid.

**Q15: What is `include()` in PHP?**
A: `include('file.php')` imports and executes another PHP file. We use it to include `db.php` in every page so we don't repeat the connection code. Using `include` keeps code DRY (Don't Repeat Yourself).

---

## 💡 Key SQL Commands Summary

```sql
-- Create Database
CREATE DATABASE IF NOT EXISTS student_db;

-- Create Table
CREATE TABLE IF NOT EXISTS students (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Insert Record
INSERT INTO students (name, email) VALUES ('Alice', 'alice@mail.com');

-- Read All Records
SELECT * FROM students ORDER BY id ASC;

-- Read One Record
SELECT * FROM students WHERE id = 1;

-- Update Record
UPDATE students SET name='Bob', email='bob@mail.com' WHERE id = 1;

-- Delete Record
DELETE FROM students WHERE id = 1;
```
