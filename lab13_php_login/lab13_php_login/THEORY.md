# 📚 COMPLETE WEB TECHNOLOGY SYLLABUS THEORY
## Simple Explanations for Viva Preparation

---

## 📖 UNIT-I: FRONT END TOOLS

### **1. Internet and WWW**

**Internet:**
- Global network of interconnected computers
- Uses TCP/IP protocol to communicate
- Decentralized (no single owner)

**WWW (World Wide Web):**
- System of interlinked hypertext documents
- Accessed via the Internet using browsers
- Invented by Tim Berners-Lee (1989)

**Key Differences:**
```
Internet = Infrastructure (roads)
WWW = Service on Internet (cars on roads)
```

**How Web Works:**
```
1. User enters URL in browser
2. DNS converts domain to IP address
3. Browser sends HTTP request to server
4. Server processes request
5. Server sends HTTP response (HTML/CSS/JS)
6. Browser renders the page
```

---

### **2. HTML5 Structure**

**Basic HTML5 Document:**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Title</title>
</head>
<body>
    <!-- Content here -->
</body>
</html>
```

**New HTML5 Elements:**
- **Semantic:** `<header>`, `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>`
- **Media:** `<video>`, `<audio>`, `<canvas>`
- **Forms:** `<input type="email">`, `<input type="date">`, `<datalist>`

**Example with Semantic HTML:**
```html
<header>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
        </ul>
    </nav>
</header>

<main>
    <article>
        <h1>Article Title</h1>
        <p>Content...</p>
    </article>
</main>

<footer>
    <p>&copy; 2024</p>
</footer>
```

---

### **3. HTML Forms**

**Form Elements:**
```html
<form action="submit.php" method="POST">
    <!-- Text Input -->
    <input type="text" name="username" required>
    
    <!-- Email Input (HTML5 validation) -->
    <input type="email" name="email" required>
    
    <!-- Password -->
    <input type="password" name="pass">
    
    <!-- Radio Buttons -->
    <input type="radio" name="gender" value="male"> Male
    <input type="radio" name="gender" value="female"> Female
    
    <!-- Checkbox -->
    <input type="checkbox" name="terms"> I agree
    
    <!-- Dropdown -->
    <select name="country">
        <option value="india">India</option>
        <option value="usa">USA</option>
    </select>
    
    <!-- Textarea -->
    <textarea name="message"></textarea>
    
    <!-- File Upload -->
    <input type="file" name="document">
    
    <!-- Submit Button -->
    <button type="submit">Submit</button>
</form>
```

**Form Attributes:**
- `action` = Where to send data
- `method` = GET or POST
- `enctype` = For file uploads: `multipart/form-data`

---

### **4. CSS (Cascading Style Sheets)**

**Three Ways to Include CSS:**

**1. Inline CSS**
```html
<p style="color: red; font-size: 16px;">Red text</p>
```

**2. Internal CSS**
```html
<head>
    <style>
        p { color: red; }
    </style>
</head>
```

**3. External CSS (Best Practice)**
```html
<head>
    <link rel="stylesheet" href="style.css">
</head>
```

**CSS Selectors:**
```css
/* Element selector */
p { color: blue; }

/* Class selector */
.myClass { color: red; }

/* ID selector */
#myId { color: green; }

/* Descendant selector */
div p { color: purple; }

/* Attribute selector */
input[type="text"] { border: 1px solid black; }

/* Pseudo-class */
a:hover { color: red; }
```

**CSS Box Model:**
```css
.box {
    width: 200px;        /* Content width */
    padding: 20px;       /* Inside spacing */
    border: 5px solid;   /* Border */
    margin: 10px;        /* Outside spacing */
}
```

**CSS Layout:**
```css
/* Flexbox (1D layout) */
.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Grid (2D layout) */
.grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 20px;
}
```

---

### **5. Bootstrap**

**What is Bootstrap?**
- CSS framework for responsive design
- Pre-built components
- 12-column grid system

**Grid System:**
```html
<div class="container">
    <div class="row">
        <div class="col-md-4">Column 1</div>
        <div class="col-md-4">Column 2</div>
        <div class="col-md-4">Column 3</div>
    </div>
</div>
```

**Common Components:**
```html
<!-- Button -->
<button class="btn btn-primary">Click Me</button>

<!-- Alert -->
<div class="alert alert-success">Success!</div>

<!-- Card -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Title</h5>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Logo</a>
</nav>
```

---

### **6. XML (Extensible Markup Language)**

**What is XML?**
- Markup language for storing/transporting data
- Self-descriptive
- Human-readable

**Example:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<students>
    <student id="1">
        <name>John Doe</name>
        <email>john@example.com</email>
        <age>20</age>
    </student>
    <student id="2">
        <name>Jane Smith</name>
        <email>jane@example.com</email>
        <age>22</age>
    </student>
</students>
```

**XML vs HTML:**
```
XML:
- Stores data
- Custom tags
- Case-sensitive
- Closing tags mandatory

HTML:
- Displays data
- Predefined tags
- Not case-sensitive
- Some tags optional
```

---

### **7. JSON (JavaScript Object Notation)**

**Why JSON?**
- Lightweight data format
- Easier than XML
- Native to JavaScript

**Example:**
```json
{
    "students": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "age": 20
        },
        {
            "id": 2,
            "name": "Jane Smith",
            "email": "jane@example.com",
            "age": 22
        }
    ]
}
```

**JSON vs XML (Same Data):**
```
JSON (Shorter):
{"name": "John", "age": 30}

XML (Longer):
<person>
    <name>John</name>
    <age>30</age>
</person>
```

---

## 📖 UNIT-II: CLIENT-SIDE TECHNOLOGIES

### **1. JavaScript Basics**

**What is JavaScript?**
- Programming language for web browsers
- Makes websites interactive
- Runs on client-side (user's computer)

**Variables:**
```javascript
// ES5
var name = "John";

// ES6 (Modern)
let age = 25;        // Can be reassigned
const PI = 3.14;     // Cannot be reassigned
```

**Data Types:**
```javascript
// Primitive
let num = 42;                    // Number
let str = "Hello";               // String
let bool = true;                 // Boolean
let nothing = null;              // Null
let notDefined = undefined;      // Undefined

// Reference
let arr = [1, 2, 3];            // Array
let obj = {name: "John"};       // Object
```

**Operators:**
```javascript
// Arithmetic
let sum = 5 + 3;        // 8
let diff = 5 - 3;       // 2
let prod = 5 * 3;       // 15
let quot = 6 / 3;       // 2
let rem = 5 % 2;        // 1 (modulo)

// Comparison
5 == "5"                // true (loose equality)
5 === "5"               // false (strict equality)
5 != "5"                // false
5 !== "5"               // true
5 > 3                   // true

// Logical
true && false           // false (AND)
true || false           // true (OR)
!true                   // false (NOT)
```

---

### **2. Control Structures**

**If-Else:**
```javascript
if (age >= 18) {
    console.log("Adult");
} else if (age >= 13) {
    console.log("Teenager");
} else {
    console.log("Child");
}
```

**Switch:**
```javascript
switch (day) {
    case "Monday":
        console.log("Start of week");
        break;
    case "Friday":
        console.log("Weekend soon!");
        break;
    default:
        console.log("Regular day");
}
```

**Loops:**
```javascript
// For loop
for (let i = 0; i < 5; i++) {
    console.log(i);  // 0, 1, 2, 3, 4
}

// While loop
let i = 0;
while (i < 5) {
    console.log(i);
    i++;
}

// Do-While (runs at least once)
let j = 0;
do {
    console.log(j);
    j++;
} while (j < 5);

// For-of (arrays)
let arr = [1, 2, 3];
for (let num of arr) {
    console.log(num);
}

// For-in (objects)
let obj = {a: 1, b: 2};
for (let key in obj) {
    console.log(key, obj[key]);
}
```

---

### **3. Functions**

**Function Declaration:**
```javascript
function greet(name) {
    return "Hello, " + name;
}

console.log(greet("John"));  // "Hello, John"
```

**Function Expression:**
```javascript
const greet = function(name) {
    return "Hello, " + name;
};
```

**Arrow Function (ES6):**
```javascript
const greet = (name) => {
    return "Hello, " + name;
};

// Shorter (implicit return)
const greet = name => "Hello, " + name;
```

**Parameters & Arguments:**
```javascript
// Default parameters
function greet(name = "Guest") {
    return "Hello, " + name;
}

// Rest parameters
function sum(...numbers) {
    return numbers.reduce((a, b) => a + b, 0);
}

console.log(sum(1, 2, 3, 4));  // 10
```

---

### **4. Arrays**

**Creating Arrays:**
```javascript
let arr1 = [1, 2, 3];
let arr2 = new Array(1, 2, 3);
```

**Array Methods:**
```javascript
let arr = [1, 2, 3, 4, 5];

// Add/Remove
arr.push(6);         // Add to end: [1,2,3,4,5,6]
arr.pop();           // Remove from end: [1,2,3,4,5]
arr.unshift(0);      // Add to start: [0,1,2,3,4,5]
arr.shift();         // Remove from start: [1,2,3,4,5]

// Transform
arr.map(x => x * 2);         // [2,4,6,8,10]
arr.filter(x => x > 3);      // [4,5]
arr.reduce((a,b) => a+b, 0); // 15 (sum)

// Search
arr.find(x => x > 3);        // 4 (first match)
arr.findIndex(x => x > 3);   // 3 (index of first match)
arr.includes(3);             // true

// Sort
arr.sort();                  // [1,2,3,4,5]
arr.reverse();               // [5,4,3,2,1]

// Slice (extract)
arr.slice(1, 3);             // [2,3] (from index 1 to 2)

// Splice (modify)
arr.splice(2, 1);            // Remove 1 item at index 2
arr.splice(2, 0, 99);        // Insert 99 at index 2
```

---

### **5. DOM Manipulation**

**Selecting Elements:**
```javascript
// By ID
let elem = document.getElementById("myId");

// By Class
let elems = document.getElementsByClassName("myClass");

// By Tag
let divs = document.getElementsByTagName("div");

// Query Selector (modern, recommended)
let elem = document.querySelector("#myId");
let elems = document.querySelectorAll(".myClass");
```

**Changing Content:**
```javascript
// Text content
elem.textContent = "New text";

// HTML content
elem.innerHTML = "<strong>Bold text</strong>";

// Attributes
elem.setAttribute("src", "image.jpg");
elem.getAttribute("src");
```

**Changing Styles:**
```javascript
elem.style.color = "red";
elem.style.backgroundColor = "blue";
elem.style.fontSize = "20px";

// Add/Remove classes
elem.classList.add("active");
elem.classList.remove("inactive");
elem.classList.toggle("highlight");
```

**Creating Elements:**
```javascript
// Create
let newDiv = document.createElement("div");
newDiv.textContent = "Hello!";
newDiv.classList.add("myClass");

// Append
document.body.appendChild(newDiv);

// Remove
elem.remove();
```

---

### **6. Event Handling**

**Adding Event Listeners:**
```javascript
// Click event
button.addEventListener("click", function() {
    console.log("Button clicked!");
});

// With arrow function
button.addEventListener("click", () => {
    console.log("Button clicked!");
});

// Multiple events
input.addEventListener("focus", handleFocus);
input.addEventListener("blur", handleBlur);
input.addEventListener("input", handleInput);
```

**Common Events:**
```javascript
// Mouse events
"click", "dblclick", "mousedown", "mouseup", "mousemove"

// Keyboard events
"keydown", "keyup", "keypress"

// Form events
"submit", "change", "input", "focus", "blur"

// Window events
"load", "resize", "scroll"
```

**Event Object:**
```javascript
button.addEventListener("click", function(event) {
    console.log(event.type);      // "click"
    console.log(event.target);    // Element that triggered
    event.preventDefault();       // Prevent default action
    event.stopPropagation();      // Stop bubbling
});
```

---

### **7. Form Validation (JavaScript)**

**Example:**
```javascript
let form = document.getElementById("myForm");

form.addEventListener("submit", function(event) {
    event.preventDefault();  // Stop submission
    
    let username = document.getElementById("username").value;
    let email = document.getElementById("email").value;
    
    // Validate username
    if (username.length < 3) {
        alert("Username must be at least 3 characters");
        return false;
    }
    
    // Validate email
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Invalid email format");
        return false;
    }
    
    // If all valid, submit
    form.submit();
});
```

---

### **8. jQuery**

**What is jQuery?**
- JavaScript library
- Simplifies DOM manipulation
- Cross-browser compatibility

**Syntax:**
```javascript
// jQuery
$(selector).action()

// Examples
$("#myId").hide();
$(".myClass").show();
$("p").css("color", "red");
```

**Common Methods:**
```javascript
// Selectors
$("#id")
$(".class")
$("element")

// DOM manipulation
.text()          // Get/Set text
.html()          // Get/Set HTML
.val()           // Get/Set form value
.attr()          // Get/Set attribute
.css()           // Get/Set CSS

// Effects
.hide()
.show()
.toggle()
.fadeIn()
.fadeOut()
.slideDown()
.slideUp()

// Events
.click()
.hover()
.focus()
.blur()
```

**Example:**
```javascript
$(document).ready(function() {
    $("#btn").click(function() {
        $("#box").fadeOut(1000);
    });
});
```

---

### **9. AJAX with jQuery**

**Basic AJAX:**
```javascript
$.ajax({
    url: "getData.php",
    type: "GET",
    dataType: "json",
    success: function(data) {
        console.log(data);
    },
    error: function(error) {
        console.error(error);
    }
});
```

**Shorthand Methods:**
```javascript
// GET request
$.get("getData.php", function(data) {
    console.log(data);
});

// POST request
$.post("saveData.php", {name: "John"}, function(response) {
    console.log(response);
});

// Load HTML
$("#result").load("content.html");
```

---

## 📖 UNIT-III: SERVER-SIDE TECHNOLOGIES (PHP)

### **1. PHP Introduction**

**What is PHP?**
- Server-side scripting language
- Embedded in HTML
- Processes on server before sending to browser
- File extension: `.php`

**Basic Syntax:**
```php
<?php
    // PHP code here
    echo "Hello, World!";
?>
```

**Variables:**
```php
<?php
$name = "John";           // String
$age = 25;                // Integer
$price = 99.99;           // Float
$isActive = true;         // Boolean
$arr = [1, 2, 3];         // Array
?>
```

**Output:**
```php
<?php
echo "Hello";             // Output text
print "World";            // Alternative
var_dump($variable);      // Debug output
print_r($array);          // Array debug
?>
```

---

### **2. PHP Conditions & Loops**

**If-Else:**
```php
<?php
if ($age >= 18) {
    echo "Adult";
} elseif ($age >= 13) {
    echo "Teen";
} else {
    echo "Child";
}
?>
```

**Switch:**
```php
<?php
switch ($day) {
    case "Monday":
        echo "Start of week";
        break;
    case "Friday":
        echo "TGIF!";
        break;
    default:
        echo "Regular day";
}
?>
```

**Loops:**
```php
<?php
// For loop
for ($i = 0; $i < 5; $i++) {
    echo $i;
}

// While loop
$i = 0;
while ($i < 5) {
    echo $i;
    $i++;
}

// Foreach (arrays)
$colors = ["red", "green", "blue"];
foreach ($colors as $color) {
    echo $color;
}

// Foreach with key
$person = ["name" => "John", "age" => 25];
foreach ($person as $key => $value) {
    echo "$key: $value";
}
?>
```

---

### **3. PHP Functions**

**Built-in Functions:**
```php
<?php
// String functions
strlen("Hello");              // 5
strtoupper("hello");          // "HELLO"
strtolower("HELLO");          // "hello"
str_replace("a", "b", "cat"); // "cbt"
substr("Hello", 0, 3);        // "Hel"

// Array functions
count([1,2,3]);               // 3
array_push($arr, 4);          // Add to end
array_pop($arr);              // Remove from end
in_array(2, $arr);            // true if exists
array_merge($arr1, $arr2);    // Combine arrays

// Math functions
round(3.7);                   // 4
ceil(3.2);                    // 4
floor(3.8);                   // 3
rand(1, 100);                 // Random 1-100
?>
```

**User-Defined Functions:**
```php
<?php
function greet($name) {
    return "Hello, $name!";
}

echo greet("John");  // "Hello, John!"

// Default parameter
function greet($name = "Guest") {
    return "Hello, $name!";
}

// Type declaration (PHP 7+)
function add(int $a, int $b): int {
    return $a + $b;
}
?>
```

---

### **4. PHP Arrays**

**Indexed Array:**
```php
<?php
$colors = ["red", "green", "blue"];
echo $colors[0];  // "red"
?>
```

**Associative Array:**
```php
<?php
$person = [
    "name" => "John",
    "age" => 25,
    "email" => "john@example.com"
];

echo $person["name"];  // "John"
?>
```

**Multidimensional Array:**
```php
<?php
$users = [
    ["name" => "John", "age" => 25],
    ["name" => "Jane", "age" => 30]
];

echo $users[0]["name"];  // "John"
?>
```

---

### **5. Form Handling**

**HTML Form:**
```html
<form method="POST" action="process.php">
    <input type="text" name="username">
    <input type="email" name="email">
    <button type="submit">Submit</button>
</form>
```

**PHP Processing:**
```php
<?php
// process.php

// POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    echo "Welcome, $username!";
}

// GET method (from URL parameters)
// URL: page.php?name=John&age=25
$name = $_GET['name'];
$age = $_GET['age'];
?>
```

**Validation:**
```php
<?php
$errors = [];

// Check empty
if (empty($_POST['username'])) {
    $errors[] = "Username required";
}

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email";
}

// Length check
if (strlen($password) < 6) {
    $errors[] = "Password too short";
}

if (empty($errors)) {
    // Process form
} else {
    // Display errors
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
}
?>
```

---

### **6. Cookies**

**Setting Cookies:**
```php
<?php
// setcookie(name, value, expiry, path, domain, secure, httponly)

// Simple cookie
setcookie("username", "John", time() + 86400);  // 1 day

// Secure cookie
setcookie("token", "abc123", time() + 86400, "/", "", true, true);
?>
```

**Reading Cookies:**
```php
<?php
if (isset($_COOKIE['username'])) {
    echo "Welcome back, " . $_COOKIE['username'];
}
?>
```

**Deleting Cookies:**
```php
<?php
setcookie("username", "", time() - 3600);  // Set expiry in past
?>
```

---

### **7. Sessions**

**Starting Session:**
```php
<?php
session_start();  // Must be called first!

// Store data
$_SESSION['user_id'] = 123;
$_SESSION['username'] = "john";
$_SESSION['role'] = "admin";
?>
```

**Accessing Session Data:**
```php
<?php
session_start();

if (isset($_SESSION['username'])) {
    echo "Logged in as: " . $_SESSION['username'];
}
?>
```

**Destroying Session:**
```php
<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy session
session_destroy();
?>
```

---

### **8. MySQL with PHP**

**Connection:**
```php
<?php
$conn = new mysqli("localhost", "root", "", "mydb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

**SELECT Query:**
```php
<?php
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo $row['name'] . "<br>";
}
?>
```

**INSERT Query:**
```php
<?php
$sql = "INSERT INTO users (name, email) VALUES ('John', 'john@example.com')";

if ($conn->query($sql) === TRUE) {
    echo "Record inserted";
}
?>
```

**Prepared Statements (Secure):**
```php
<?php
// Prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>
```

---

### **9. File Handling**

**Reading File:**
```php
<?php
// Read entire file
$content = file_get_contents("file.txt");

// Read line by line
$file = fopen("file.txt", "r");
while (!feof($file)) {
    $line = fgets($file);
    echo $line;
}
fclose($file);
?>
```

**Writing File:**
```php
<?php
// Write to file
file_put_contents("file.txt", "Hello World");

// Append to file
file_put_contents("log.txt", "New entry\n", FILE_APPEND);

// Using fopen
$file = fopen("file.txt", "w");
fwrite($file, "Hello World");
fclose($file);
?>
```

**File Upload:**
```html
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="document">
    <button type="submit">Upload</button>
</form>
```

```php
<?php
if (isset($_FILES['document'])) {
    $file = $_FILES['document'];
    $target = "uploads/" . basename($file['name']);
    
    if (move_uploaded_file($file['tmp_name'], $target)) {
        echo "File uploaded successfully";
    }
}
?>
```

---

### **10. Exception Handling**

**Try-Catch:**
```php
<?php
try {
    // Code that may throw exception
    if (!file_exists("file.txt")) {
        throw new Exception("File not found");
    }
    
    $content = file_get_contents("file.txt");
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    
} finally {
    // Always executes
    echo "Cleanup code";
}
?>
```

---

### **11. Email Sending**

**Using mail() Function:**
```php
<?php
$to = "user@example.com";
$subject = "Test Email";
$message = "This is a test email";
$headers = "From: admin@example.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully";
}
?>
```

---

## 📚 KEY PHP SECURITY CONCEPTS

**1. SQL Injection Prevention:**
```php
// Bad
$sql = "SELECT * FROM users WHERE id = " . $_GET['id'];

// Good
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
```

**2. XSS Prevention:**
```php
// Always sanitize output
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');
```

**3. Password Security:**
```php
// Hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// Verify password
password_verify($input_password, $stored_hash);
```

**4. Session Security:**
```php
session_start();
session_regenerate_id(true);  // Prevent session fixation
```

---

**Continue to study UNIT-IV (Spring Boot), UNIT-V (React), and UNIT-VI (Node.js) from your class notes or textbook.**

---

**Good luck with your viva! Practice explaining these concepts in your own words. 🎓**
