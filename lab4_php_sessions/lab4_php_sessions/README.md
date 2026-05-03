Download XAMPP
Install it
Open XAMPP Control Panel
Start:
✅ Apache

Move Your Project Folder

Copy your extracted folder into:

C:\xampp\htdocs\

run on browser

http://localhost/lab4_php_sessions/index.php

# 📘 Lab 4: Process User Input & Manage Sessions
## Web Technology — Complete Guide (Theory + Viva + Setup)

---

## 📁 FILE STRUCTURE

```
lab4_php_sessions/
│
├── index.php          ← Main login/registration form (HTML + PHP)
├── form_handler.php   ← Processes POST/GET, validates, sets cookie + session
├── dashboard.php      ← Protected page (requires session login)
├── logout.php         ← Destroys session and redirects
├── get_demo.php       ← Demonstrates GET method with URL parameters
├── clear_cookie.php   ← Deletes the username cookie
├── css/
│   └── style.css      ← Custom CSS styles
└── README.md          ← This file
```

---

## ⚙️ HOW TO RUN (VS Code Setup)

### Prerequisites
You need PHP installed. Check with: `php -v`

### Install PHP (if not installed)
- **Windows**: Download from https://windows.php.net/download/
  Or use XAMPP: https://www.apachefriends.org/ (easiest!)
- **Mac**: `brew install php`
- **Linux**: `sudo apt install php`

### Steps to Run

1. **Open terminal in VS Code** (`Ctrl + ~`)

2. **Navigate to the project folder:**
   ```
   cd path/to/lab4_php_sessions
   ```

3. **Start PHP's built-in server:**
   ```
   php -S localhost:8080
   ```

4. **Open browser and go to:**
   ```
   http://localhost:8080
   ```

5. **You should see the registration/login form!**

### To Stop the Server
Press `Ctrl + C` in the terminal.

---

## 🖥️ EXPECTED OUTPUT (What to Show Examiner)

### Step 1 — Open http://localhost:443
- You see a Bootstrap-styled form with Name, Email, Password fields
- There are two buttons: "Use POST" and "Use GET"

### Step 2 — Test POST (default)
- Fill in: Name = "John Doe", Email = "john@gmail.com", Password = "pass123"
- Check "Remember my name"
- Click "Submit Form"
- → Redirects to dashboard.php
- → Shows session data (username, email, login time)
- → Shows cookie was created for 30 days

### Step 3 — Test Validation
- Leave email blank or type "notanemail" → error shown
- Leave password less than 6 chars → error shown
- All validation errors appear with red highlights

### Step 4 — Test GET Method
- Click "Use GET (See URL)" button
- Submit form
- → Watch the URL change to: `form_handler.php?name=John&email=...&password=...`
- This shows GET is NOT safe for passwords!

### Step 5 — Test GET Demo Page
- Click "See GET Method Demo"
- Type in the search box and submit
- → URL shows: `get_demo.php?name=John&search=hello`

### Step 6 — Logout
- Click "Logout (Destroy Session)"
- → Session is destroyed, redirected to login
- → Try visiting dashboard.php directly — it redirects you back to login!

---

## 📖 COMPLETE THEORY (Viva Preparation)

---

### 1. HTML FORMS

**What is an HTML form?**
An HTML form is a UI element that collects user input and sends it to a server for processing.

**Syntax:**
```html
<form action="process.php" method="POST">
  <input type="text" name="username">
  <input type="submit" value="Submit">
</form>
```

**Key Attributes:**
| Attribute | Purpose | Example |
|-----------|---------|---------|
| `action`  | Where to send form data | `action="form_handler.php"` |
| `method`  | How to send (GET/POST) | `method="POST"` |
| `name`    | Key used in PHP to access value | `name="email"` |
| `required`| HTML5 built-in validation | `required` |
| `type`    | Input type (text/email/password) | `type="email"` |

**Input Types:**
- `type="text"` — Single-line text input
- `type="email"` — Email input with basic format check
- `type="password"` — Text with characters hidden
- `type="checkbox"` — On/off toggle (sends value only when checked)
- `type="hidden"` — Hidden data field (not visible to user)
- `type="submit"` — Button that submits the form

---

### 2. GET vs POST METHODS

**GET Method:**
- Data is appended to the URL: `form_handler.php?name=John&email=john@mail.com`
- Visible in browser address bar, history, server logs
- Can be bookmarked and shared
- Maximum ~2000 characters
- Use for: search queries, filters, pagination

**POST Method:**
- Data is sent in the HTTP request body (hidden)
- Not visible in URL
- No size limit (depends on server config)
- Cannot be bookmarked
- Use for: login, registration, payment, sensitive data

**PHP Access:**
```php
$name = $_POST['name'];   // Access POST data
$name = $_GET['name'];    // Access GET data
$name = $_REQUEST['name']; // Access both
```

---

### 3. EMAIL VALIDATION

**Two Levels of Validation:**

**Level 1 — Client-Side (JavaScript):**
```javascript
const emailRegex = /^\S+@\S+\.\S+$/;
if (!emailRegex.test(email)) {
    // Show error without page reload
}
```
- Runs in browser BEFORE form submits
- Gives instant feedback
- Can be bypassed (disable JS or use Postman)
- Never rely only on this!

**Level 2 — Server-Side (PHP):**
```php
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}
```
- Runs on server AFTER form submits
- Cannot be bypassed by user
- MANDATORY for security
- PHP's `filter_var()` checks RFC 5322 standard

**Why both?**
- JS: Better user experience (instant feedback)
- PHP: Actual security (fallback if JS is off)

---

### 4. COOKIES

**What is a Cookie?**
A cookie is a small text file (max 4KB) stored in the user's BROWSER. The server can read it on subsequent requests.

**Setting a Cookie in PHP:**
```php
// Syntax: setcookie(name, value, expiry, path, domain, secure, httponly)
setcookie("username", "John", time() + (30 * 24 * 60 * 60), "/");
//                             └── 30 days from now in seconds
```

**Reading a Cookie:**
```php
if (isset($_COOKIE['username'])) {
    $name = $_COOKIE['username'];
}
```

**Deleting a Cookie:**
```php
setcookie("username", "", time() - 3600, "/");
// Setting expiry in the past deletes the cookie
```

**Cookie Properties:**
| Property | Meaning |
|----------|---------|
| Name | Identifier for the cookie |
| Value | Data stored (string, max 4KB) |
| Expiry | When it auto-deletes (0 = session) |
| Path | Which URLs can access it (/ = all) |
| Secure | Only over HTTPS |
| HttpOnly | JS cannot access it (prevents XSS) |

**When to use cookies:**
- "Remember me" functionality
- User preferences (theme, language)
- Shopping cart items
- Tracking (analytics)

---

### 5. SESSIONS

**What is a Session?**
A session stores user data on the SERVER. PHP assigns each user a unique Session ID (like a locker number), which is stored in the browser as a cookie called `PHPSESSID`.

**How Sessions Work (Step by Step):**
1. User logs in → PHP runs `session_start()`
2. PHP creates a unique Session ID: `abc123def456...`
3. PHP stores user data in a file on the server (`/tmp/sess_abc123...`)
4. PHP sends Session ID to browser as cookie: `PHPSESSID=abc123...`
5. Next request: browser sends `PHPSESSID=abc123` back
6. PHP reads server file and restores `$_SESSION` data

**Using Sessions:**
```php
session_start(); // Must be FIRST line, before any output

// Store data
$_SESSION['username'] = "John";
$_SESSION['logged_in'] = true;

// Read data
$name = $_SESSION['username'];

// Check if session variable exists
if (isset($_SESSION['logged_in'])) {
    // User is logged in
}

// Destroy session (logout)
$_SESSION = [];          // Clear data
session_destroy();       // Delete server file
```

**Session vs Cookie:**
| Feature | Cookie | Session |
|---------|--------|---------|
| Storage | Browser | Server |
| Security | Lower (user can edit) | Higher |
| Expiry | Developer-controlled | Browser close / timeout |
| Size Limit | 4KB | Server disk space |
| Visibility | Visible in DevTools | Hidden on server |
| Use Case | Remember me, preferences | Login state, cart |

---

### 6. INPUT SANITIZATION

**Why sanitize?**
To prevent attacks like XSS (Cross-Site Scripting) and SQL Injection.

**PHP Functions:**
```php
htmlspecialchars($input)   // Convert < > & " ' to safe HTML entities
trim($input)               // Remove leading/trailing spaces
strip_tags($input)         // Remove all HTML/PHP tags
filter_var($input, FILTER_SANITIZE_EMAIL)  // Sanitize email
```

**Never do this:**
```php
echo $_POST['comment']; // DANGEROUS! XSS vulnerability
```

**Always do this:**
```php
echo htmlspecialchars($_POST['comment']); // Safe output
```

---

## ❓ LIKELY VIVA QUESTIONS + ANSWERS

**Q1: What is the difference between GET and POST methods?**
GET appends data to the URL (visible, bookmarkable, max 2000 chars). POST sends data in the HTTP request body (hidden, no limit, secure). Use POST for sensitive data like passwords.

**Q2: What is a cookie? How do you set and delete one in PHP?**
A cookie is a small text file stored in the browser. Set: `setcookie("name", "value", time()+86400, "/")`. Delete: `setcookie("name", "", time()-3600, "/")` — setting expiry to the past deletes it.

**Q3: What is a session? How is it different from a cookie?**
A session stores data on the SERVER. PHP gives the browser only a Session ID (as a cookie called PHPSESSID). Unlike cookies (stored in browser), sessions are more secure because user cannot modify them.

**Q4: How do you start and destroy a session in PHP?**
Start: `session_start()` (must be before any HTML output). Destroy: Empty `$_SESSION = []`, then `session_destroy()`, and optionally delete the PHPSESSID cookie.

**Q5: How do you validate email in PHP?**
Using `filter_var($email, FILTER_VALIDATE_EMAIL)`. Returns the email string if valid, or false if not valid. This checks proper format like user@domain.com.

**Q6: Why should we validate on the server-side even if we validate in JavaScript?**
JavaScript runs in the browser and can be disabled or bypassed. Server-side validation (PHP) cannot be bypassed, making it essential for security.

**Q7: What is `htmlspecialchars()` and why is it important?**
It converts special HTML characters like `<`, `>`, `&`, `"` to safe HTML entities. Without it, attackers can inject malicious scripts (XSS attack) by typing HTML in form fields.

**Q8: What is the purpose of `session_start()`?**
It must be called at the beginning of every PHP file that uses sessions. It creates a new session or resumes an existing one based on the PHPSESSID cookie.

**Q9: What is `$_SERVER['REQUEST_METHOD']`?**
It's a PHP superglobal that returns the HTTP method used ('GET' or 'POST'). Used to detect how the form was submitted.

**Q10: What is PHPSESSID?**
It's the cookie automatically created by PHP to store the Session ID in the browser. The actual data is stored on the server; this ID is just the key to find it.

**Q11: What is `filter_var()` in PHP?**
It's a PHP function for filtering and validating data. With `FILTER_VALIDATE_EMAIL`, it validates email format. With `FILTER_SANITIZE_SPECIAL_CHARS`, it removes harmful characters.

**Q12: Can cookies store passwords?**
NO. Cookies are stored in the browser and are readable by JavaScript and the user. Never store passwords in cookies. Use sessions or hashed tokens instead.

**Q13: What does `password_hash()` do?**
It creates a secure, one-way encrypted hash of a password using bcrypt or argon2. You cannot reverse it to get the original password, making it safe to store in databases.

**Q14: What is the difference between `isset()` and `empty()` in PHP?**
`isset()` returns true if a variable exists and is not null. `empty()` returns true if a variable is empty ("", 0, null, false, []). Use `isset()` to check cookie/session existence.

**Q15: What is `header("Location: page.php")`?**
It sends an HTTP redirect header to the browser, instructing it to navigate to a different URL. Must be called before any HTML output. Always follow with `exit()`.

---

## 🔧 TROUBLESHOOTING

**"Cannot modify header information — headers already sent"**
→ You output HTML/whitespace BEFORE `session_start()` or `setcookie()`.
→ Solution: Put `session_start()` as the very first line.

**Session data lost between pages**
→ Make sure `session_start()` is on EVERY page that uses sessions.
→ Check that `php.ini` has a valid `session.save_path`.

**Cookie not being set**
→ Cookies are set on the NEXT request. They won't appear in `$_COOKIE` on the same page they're set.
→ Use `header("Location: ...")` + `exit()` after setting cookie.

**Form not submitting**
→ Check that PHP server is running: `php -S localhost:8000`
→ Check file is saved with `.php` extension, not `.php.txt`
