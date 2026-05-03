# 📚 COMPLETE WEB TECHNOLOGY SYLLABUS THEORY

## For Lab 26 Viva Preparation

---

## TABLE OF CONTENTS

1. [Unit I: Front End Tools](#unit-i-front-end-tools)
2. [Unit II: Client-Side Technologies](#unit-ii-client-side-technologies)
3. [Unit III: Server-Side Technologies](#unit-iii-server-side-technologies)
4. [Unit IV: Spring Boot](#unit-iv-spring-boot-detailed)
5. [Unit V: React](#unit-v-react)
6. [Unit VI: Node.js](#unit-vi-nodejs)

---

## UNIT I: FRONT END TOOLS

### 1. Internet and WWW

**Internet:**
- **Definition:** A global network of interconnected computers communicating using TCP/IP protocol
- **Components:**
  - Routers: Direct data packets
  - Servers: Store and serve data
  - DNS (Domain Name System): Translates domain names to IP addresses
  - ISP (Internet Service Provider): Provides internet access

**World Wide Web (WWW):**
- **Definition:** Information system built on the internet using HTTP/HTTPS
- **Created by:** Tim Berners-Lee (1989 at CERN)
- **Components:**
  - Web Browser: Client software (Chrome, Firefox, etc.)
  - Web Server: Serves web pages (Apache, Nginx, etc.)
  - URL: Uniform Resource Locator (web address)
  - HTML: HyperText Markup Language

**Internet vs WWW:**
| Internet | WWW |
|----------|-----|
| Infrastructure | Service |
| Network of networks | Information system |
| Uses various protocols | Uses HTTP/HTTPS |
| Older (1960s ARPANET) | Newer (1989) |

**Website Planning:**
1. **Define Purpose:** Business goals, target audience
2. **Structure:** Sitemap, navigation
3. **Technology Choice:** HTML/CSS/JS, frameworks
4. **Design:** User experience, accessibility
5. **SEO:** Search engine optimization

### 2. HTML5

**Structure:**
```html
<!DOCTYPE html>         <!-- HTML5 document type -->
<html lang="en">        <!-- Root element -->
  <head>                <!-- Metadata -->
    <meta charset="UTF-8">
    <title>Page Title</title>
  </head>
  <body>                <!-- Visible content -->
    <h1>Heading</h1>
  </body>
</html>
```

**Key Elements:**

**Headings:** `<h1>` to `<h6>` (hierarchical)

**Paragraphs:** `<p>Content</p>`

**Line Breaks:** `<br>` (void element)

**Links:**
```html
<a href="https://example.com">External Link</a>
<a href="page.html">Internal Link</a>
<a href="#section">Anchor Link</a>
```

**Lists:**
```html
<!-- Ordered (numbered) -->
<ol>
  <li>First</li>
  <li>Second</li>
</ol>

<!-- Unordered (bullets) -->
<ul>
  <li>Item 1</li>
  <li>Item 2</li>
</ul>

<!-- Description -->
<dl>
  <dt>Term</dt>
  <dd>Definition</dd>
</dl>
```

**Tables:**
```html
<table>
  <thead>
    <tr>
      <th>Header 1</th>
      <th>Header 2</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Data 1</td>
      <td>Data 2</td>
    </tr>
  </tbody>
</table>
```

**Forms:**
```html
<form action="/submit" method="POST">
  <input type="text" name="username" required>
  <input type="email" name="email">
  <input type="password" name="pass">
  <input type="number" min="1" max="100">
  <select name="country">
    <option>India</option>
    <option>USA</option>
  </select>
  <textarea name="message"></textarea>
  <button type="submit">Submit</button>
</form>
```

**HTML5 Semantic Elements:**
- `<header>`: Page/section header
- `<nav>`: Navigation links
- `<main>`: Main content
- `<article>`: Independent content
- `<section>`: Thematic grouping
- `<aside>`: Sidebar content
- `<footer>`: Footer information

### 3. CSS (Cascading Style Sheets)

**Inclusion Methods:**

1. **Inline:** `<p style="color:blue;">Text</p>`
2. **Internal:** `<style>p { color: blue; }</style>` in `<head>`
3. **External:** `<link rel="stylesheet" href="style.css">`

**Selectors:**
```css
/* Element selector */
p { color: blue; }

/* Class selector */
.error { color: red; }

/* ID selector */
#header { background: gray; }

/* Descendant */
div p { margin: 10px; }

/* Child */
div > p { padding: 5px; }

/* Pseudo-class */
a:hover { color: red; }

/* Pseudo-element */
p::first-line { font-weight: bold; }
```

**Box Model:**
```
┌─────────────────────────┐
│       MARGIN            │
│  ┌──────────────────┐   │
│  │   BORDER         │   │
│  │  ┌───────────┐   │   │
│  │  │  PADDING  │   │   │
│  │  │ ┌───────┐ │   │   │
│  │  │ │CONTENT│ │   │   │
│  │  │ └───────┘ │   │   │
│  │  └───────────┘   │   │
│  └──────────────────┘   │
└─────────────────────────┘
```

**Flexbox:**
```css
.container {
  display: flex;
  justify-content: center;  /* horizontal */
  align-items: center;      /* vertical */
  flex-direction: row;      /* or column */
}
```

**Grid:**
```css
.grid {
  display: grid;
  grid-template-columns: 1fr 2fr 1fr;
  gap: 10px;
}
```

### 4. Bootstrap

**What:** CSS framework for responsive design

**Grid System:**
```html
<div class="container">
  <div class="row">
    <div class="col-md-6">Half width on medium+</div>
    <div class="col-md-6">Half width on medium+</div>
  </div>
</div>
```

**Breakpoints:**
- xs: < 576px
- sm: ≥ 576px
- md: ≥ 768px
- lg: ≥ 992px
- xl: ≥ 1200px

**Components:**
```html
<!-- Button -->
<button class="btn btn-primary">Click</button>

<!-- Alert -->
<div class="alert alert-success">Success!</div>

<!-- Card -->
<div class="card">
  <div class="card-body">Content</div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <!-- navbar content -->
</nav>
```

### 5. XML

**Characteristics:**
- Self-descriptive tags
- Hierarchical structure
- Case-sensitive
- Must be well-formed

**Example:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<library>
  <book id="1">
    <title>Java Programming</title>
    <author>John Doe</author>
    <price currency="INR">500</price>
  </book>
</library>
```

**Well-formed Rules:**
1. Must have root element
2. Tags must be properly nested
3. All tags must be closed
4. Attribute values must be quoted

### 6. JSON

**Syntax:**
```json
{
  "name": "John",
  "age": 30,
  "active": true,
  "address": {
    "city": "Mumbai",
    "pin": 400001
  },
  "skills": ["Java", "Python", "JavaScript"]
}
```

**Data Types:**
- String: "text"
- Number: 123, 45.67
- Boolean: true, false
- Null: null
- Array: []
- Object: {}

**JSON vs XML:**
| JSON | XML |
|------|-----|
| Lighter weight | More verbose |
| Faster parsing | Slower parsing |
| No attributes | Has attributes |
| Native to JavaScript | Not native |
| Data transport focus | Document focus |

---

## UNIT II: CLIENT-SIDE TECHNOLOGIES

### 1. JavaScript Basics

**Variables:**
```javascript
// var - function scope (avoid)
var x = 10;

// let - block scope (use for variables)
let y = 20;

// const - block scope, immutable (use for constants)
const PI = 3.14;
```

**Data Types:**
```javascript
// Primitive
let str = "Hello";           // String
let num = 42;                // Number
let bool = true;             // Boolean
let nothing = null;          // Null
let undef;                   // Undefined
let sym = Symbol("id");      // Symbol

// Reference
let obj = { name: "John" };  // Object
let arr = [1, 2, 3];         // Array
```

### 2. Control Structures

**Conditionals:**
```javascript
// if-else
if (age >= 18) {
  console.log("Adult");
} else if (age >= 13) {
  console.log("Teen");
} else {
  console.log("Child");
}

// switch
switch (day) {
  case 1:
    console.log("Monday");
    break;
  case 2:
    console.log("Tuesday");
    break;
  default:
    console.log("Other day");
}

// Ternary
let status = (age >= 18) ? "Adult" : "Minor";
```

**Loops:**
```javascript
// for
for (let i = 0; i < 5; i++) {
  console.log(i);
}

// while
let i = 0;
while (i < 5) {
  console.log(i);
  i++;
}

// do-while (runs at least once)
let j = 0;
do {
  console.log(j);
  j++;
} while (j < 5);

// for...of (arrays)
for (let item of [1, 2, 3]) {
  console.log(item);
}

// for...in (objects)
for (let key in {a: 1, b: 2}) {
  console.log(key);
}
```

### 3. Arrays

```javascript
// Creation
let arr = [1, 2, 3];

// Methods
arr.push(4);              // Add to end
arr.pop();                // Remove from end
arr.unshift(0);           // Add to start
arr.shift();              // Remove from start
arr.splice(1, 2);         // Remove/insert at index

// Iteration
arr.forEach(x => console.log(x));

// Transformation
let doubled = arr.map(x => x * 2);
let evens = arr.filter(x => x % 2 === 0);
let sum = arr.reduce((acc, x) => acc + x, 0);

// Search
let found = arr.find(x => x > 2);
let index = arr.indexOf(3);
let has = arr.includes(3);
```

### 4. Functions

```javascript
// Function declaration
function greet(name) {
  return "Hello " + name;
}

// Function expression
let greet = function(name) {
  return "Hello " + name;
};

// Arrow function (ES6)
let greet = (name) => "Hello " + name;

// Multiple parameters
let add = (a, b) => a + b;

// Default parameters
function greet(name = "Guest") {
  return "Hello " + name;
}

// Rest parameters
function sum(...numbers) {
  return numbers.reduce((a, b) => a + b, 0);
}
```

### 5. Objects

```javascript
// Object literal
let person = {
  name: "John",
  age: 30,
  greet: function() {
    return "Hello " + this.name;
  }
};

// Accessing
person.name;              // Dot notation
person["name"];           // Bracket notation

// ES6 shorthand
let name = "John";
let person = { name };    // Same as { name: name }

// Destructuring
let { name, age } = person;

// Constructor
function Person(name, age) {
  this.name = name;
  this.age = age;
}
let john = new Person("John", 30);

// ES6 Class
class Person {
  constructor(name, age) {
    this.name = name;
    this.age = age;
  }
  
  greet() {
    return `Hello ${this.name}`;
  }
}
```

### 6. DOM Manipulation

**Selecting:**
```javascript
// By ID
document.getElementById("myId");

// By class
document.getElementsByClassName("myClass");

// By tag
document.getElementsByTagName("div");

// CSS selectors (modern)
document.querySelector("#myId");
document.querySelectorAll(".myClass");
```

**Creating & Modifying:**
```javascript
// Create
let div = document.createElement("div");
div.textContent = "Hello";
div.className = "box";
div.id = "myBox";

// Append
document.body.appendChild(div);

// Remove
div.remove();

// Modify
div.style.color = "red";
div.setAttribute("data-id", "123");
div.innerHTML = "<p>HTML</p>";
```

**Events:**
```javascript
// Method 1: Inline
<button onclick="alert('Hi')">Click</button>

// Method 2: Property
elem.onclick = function() {
  console.log("Clicked");
};

// Method 3: addEventListener (best)
elem.addEventListener("click", function(e) {
  console.log("Clicked", e.target);
});

// Event types
// Mouse: click, dblclick, mouseover, mouseout, mousedown, mouseup
// Keyboard: keydown, keyup, keypress
// Form: submit, change, input, focus, blur
// Window: load, resize, scroll
```

### 7. jQuery

**Basics:**
```javascript
// Document ready
$(document).ready(function() {
  // Code here
});

// Shorthand
$(function() {
  // Code here
});

// Selecting
$("#myId");
$(".myClass");
$("div");
$("div.myClass");
$("div > p");

// Chaining
$("p").css("color", "red").show().fadeIn();
```

**Manipulation:**
```javascript
// Content
$("p").text("New text");
$("p").html("<b>Bold</b>");

// Attributes
$("img").attr("src", "image.jpg");
$("input").val("New value");

// CSS
$("p").css("color", "red");
$("p").addClass("highlight");
$("p").removeClass("old");
$("p").toggleClass("active");

// DOM
$("<div>").text("New").appendTo("body");
$("p").remove();
$("p").empty();
```

**Events:**
```javascript
// Click
$("button").click(function() {
  alert("Clicked");
});

// Hover
$("div").hover(
  function() { $(this).addClass("hover"); },
  function() { $(this).removeClass("hover"); }
);

// Multiple events
$("p").on("click mouseover", function() {
  console.log("Event");
});
```

**AJAX:**
```javascript
// GET
$.get("api/data", function(data) {
  console.log(data);
});

// POST
$.post("api/save", { name: "John" }, function(response) {
  console.log(response);
});

// AJAX
$.ajax({
  url: "api/data",
  method: "GET",
  success: function(data) {
    console.log(data);
  },
  error: function(err) {
    console.error(err);
  }
});
```

---

## UNIT III: SERVER-SIDE TECHNOLOGIES

### 1. PHP Fundamentals

**Basic Syntax:**
```php
<?php
  // Variables
  $name = "John";
  $age = 30;
  $price = 99.99;
  $active = true;
  
  // Output
  echo "Hello " . $name;
  print("Age: $age");
  
  // Comments
  // Single line
  /* Multi
     line */
?>
```

**Operators:**
```php
// Arithmetic
$sum = 10 + 5;
$diff = 10 - 5;
$prod = 10 * 5;
$quot = 10 / 5;
$mod = 10 % 3;

// Comparison
$equal = (10 == "10");        // true (type coercion)
$identical = (10 === "10");   // false (strict)
$greater = (10 > 5);

// Logical
$and = ($a && $b);
$or = ($a || $b);
$not = !$a;

// String
$full = $first . " " . $last;  // Concatenation
```

**Control Structures:**
```php
// if-else
if ($age >= 18) {
  echo "Adult";
} elseif ($age >= 13) {
  echo "Teen";
} else {
  echo "Child";
}

// switch
switch ($day) {
  case 1:
    echo "Monday";
    break;
  case 2:
    echo "Tuesday";
    break;
  default:
    echo "Other";
}

// for
for ($i = 0; $i < 5; $i++) {
  echo $i;
}

// while
while ($i < 5) {
  echo $i;
  $i++;
}

// foreach
foreach ($arr as $value) {
  echo $value;
}

foreach ($arr as $key => $value) {
  echo "$key: $value";
}
```

### 2. PHP Functions

```php
// Basic function
function greet($name) {
  return "Hello " . $name;
}

// Default parameter
function greet($name = "Guest") {
  return "Hello " . $name;
}

// Type hints (PHP 7+)
function add(int $a, int $b): int {
  return $a + $b;
}

// Variable functions
function test() {
  return "Hello";
}
$func = "test";
echo $func();  // Calls test()

// Anonymous function
$greet = function($name) {
  return "Hello " . $name;
};
```

### 3. PHP Arrays

```php
// Indexed array
$fruits = array("Apple", "Banana", "Orange");
$fruits = ["Apple", "Banana", "Orange"];  // Short syntax

// Associative array
$person = array(
  "name" => "John",
  "age" => 30
);
$person = ["name" => "John", "age" => 30];

// Access
echo $fruits[0];        // Apple
echo $person["name"];   // John

// Array functions
count($fruits);                    // 3
array_push($fruits, "Mango");      // Add
array_pop($fruits);                // Remove last
in_array("Apple", $fruits);        // Check exists
sort($fruits);                     // Sort
array_merge($arr1, $arr2);         // Combine
```

### 4. String Manipulation

```php
// Functions
strlen("Hello");                   // 5
strtoupper("hello");               // HELLO
strtolower("HELLO");               // hello
ucfirst("hello");                  // Hello
ucwords("hello world");            // Hello World

// Substring
substr("Hello", 0, 3);             // Hel
substr("Hello", -2);               // lo

// Search/Replace
strpos("Hello World", "World");    // 6
str_replace("o", "a", "Hello");    // Hella

// Trim
trim("  hello  ");                 // "hello"
ltrim("  hello");                  // "hello"
rtrim("hello  ");                  // "hello"

// Split/Join
explode(" ", "Hello World");       // ["Hello", "World"]
implode("-", ["a", "b", "c"]);     // "a-b-c"
```

### 5. Form Handling

```php
<!-- form.html -->
<form action="process.php" method="POST">
  <input type="text" name="username">
  <input type="email" name="email">
  <button type="submit">Submit</button>
</form>

<?php
// process.php

// POST data
$username = $_POST['username'];
$email = $_POST['email'];

// GET data (from URL query string)
$page = $_GET['page'];

// Validation
if (empty($username)) {
  echo "Username required";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo "Invalid email";
}

// Sanitization
$clean = filter_var($username, FILTER_SANITIZE_STRING);
$clean = htmlspecialchars($username);  // Prevent XSS
?>
```

### 6. Cookies & Sessions

**Cookies:**
```php
// Set cookie (expires in 1 hour)
setcookie("user", "John", time() + 3600, "/");

// Access cookie
if (isset($_COOKIE['user'])) {
  $user = $_COOKIE['user'];
}

// Delete cookie
setcookie("user", "", time() - 3600, "/");
```

**Sessions:**
```php
// Start session (required before using $_SESSION)
session_start();

// Set session variable
$_SESSION['user_id'] = 123;
$_SESSION['username'] = "John";

// Access session variable
$user_id = $_SESSION['user_id'];

// Check if set
if (isset($_SESSION['username'])) {
  echo "Logged in as " . $_SESSION['username'];
}

// Unset specific variable
unset($_SESSION['username']);

// Destroy all session data
session_destroy();
```

### 7. File Handling

```php
// Read file
$content = file_get_contents("file.txt");

// Write file (overwrites)
file_put_contents("file.txt", "Hello World");

// Append
file_put_contents("file.txt", "More text", FILE_APPEND);

// Read line by line
$file = fopen("file.txt", "r");
while (!feof($file)) {
  $line = fgets($file);
  echo $line;
}
fclose($file);

// Write with fopen
$file = fopen("file.txt", "w");
fwrite($file, "Hello");
fclose($file);

// File info
file_exists("file.txt");
filesize("file.txt");
is_file("file.txt");
is_dir("folder");
```

### 8. MySQL with PHP

```php
// Connect (mysqli)
$conn = new mysqli("localhost", "username", "password", "database");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Select query
$sql = "SELECT * FROM users WHERE age > 18";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo $row["name"] . "<br>";
  }
}

// Insert
$sql = "INSERT INTO users (name, email) VALUES ('John', 'john@example.com')";
$conn->query($sql);

// Prepared statements (prevent SQL injection)
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Close
$conn->close();
```

### 9. Exception Handling

```php
try {
  // Code that might throw exception
  if ($age < 0) {
    throw new Exception("Age cannot be negative");
  }
  
  // Division by zero
  if ($divisor == 0) {
    throw new DivisionByZeroError("Cannot divide by zero");
  }
  
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
  
} catch (DivisionByZeroError $e) {
  echo "Math error: " . $e->getMessage();
  
} finally {
  // Always executes
  echo "Cleanup code";
}

// Custom exception
class CustomException extends Exception {
  public function errorMessage() {
    return "Custom error: " . $this->getMessage();
  }
}
```

### 10. Laravel Overview

**MVC Architecture:**
- **Model:** Database interaction (Eloquent ORM)
- **View:** Presentation (Blade templates)
- **Controller:** Business logic

**Routing:**
```php
// routes/web.php
Route::get('/', function () {
  return view('welcome');
});

Route::get('/user/{id}', function ($id) {
  return "User " . $id;
});

Route::post('/submit', 'UserController@store');
```

**Eloquent ORM:**
```php
// Model
class User extends Model {
  protected $table = 'users';
}

// CRUD
$users = User::all();                      // Get all
$user = User::find(1);                     // Find by ID
$user = User::where('age', '>', 18)->get(); // Query

$user = new User();
$user->name = "John";
$user->save();                             // Create

$user->delete();                           // Delete
```

**Blade Templates:**
```php
<!-- view.blade.php -->
<h1>{{ $title }}</h1>  <!-- Echo (escaped) -->
<div>{!! $html !!}</div>  <!-- Unescaped -->

@if($age >= 18)
  <p>Adult</p>
@else
  <p>Minor</p>
@endif

@foreach($users as $user)
  <p>{{ $user->name }}</p>
@endforeach
```

---

## UNIT IV: SPRING BOOT (DETAILED)

### 1. Spring Framework Core Concepts

**Dependency Injection (DI):**
- Pattern where objects receive dependencies from external source
- Objects don't create their own dependencies
- Spring container manages object creation and wiring

**Inversion of Control (IoC):**
- Framework controls program flow
- Objects are created and managed by Spring container
- Opposite of traditional programming where app controls flow

**Types of DI:**
1. **Constructor Injection (recommended):**
```java
@RestController
public class OrderController {
  private final OrderService orderService;
  
  public OrderController(OrderService orderService) {
    this.orderService = orderService;
  }
}
```

2. **Field Injection:**
```java
@Autowired
private OrderService orderService;
```

3. **Setter Injection:**
```java
private OrderService orderService;

@Autowired
public void setOrderService(OrderService orderService) {
  this.orderService = orderService;
}
```

**Bean Scopes:**
- **Singleton (default):** One instance per Spring container
- **Prototype:** New instance for each request
- **Request:** One per HTTP request (web apps)
- **Session:** One per HTTP session
- **Application:** One per ServletContext

### 2. Spring Boot Advantages

**Auto-Configuration:**
```java
// Spring Boot detects H2 on classpath
// Automatically configures:
// - DataSource bean
// - EntityManagerFactory
// - TransactionManager
// No manual configuration needed!
```

**Starter Dependencies:**
```xml
<!-- One starter includes multiple dependencies -->
<dependency>
  <groupId>org.springframework.boot</groupId>
  <artifactId>spring-boot-starter-web</artifactId>
  <!-- Includes: Spring MVC, Tomcat, Jackson -->
</dependency>
```

**Embedded Server:**
- Tomcat/Jetty/Undertow included in JAR
- No separate server installation
- Run with: `java -jar app.jar`

### 3. Annotations Explained

**@SpringBootApplication:**
- Combines @Configuration, @EnableAutoConfiguration, @ComponentScan
- Marks main class

**@RestController:**
- @Controller + @ResponseBody
- Returns data (JSON), not views
- Every method returns data directly

**@RequestMapping:**
```java
@RequestMapping(value = "/api/orders", method = RequestMethod.GET)
// Or use shortcuts:
@GetMapping("/api/orders")
@PostMapping("/api/orders")
@PutMapping("/api/orders/{id}")
@DeleteMapping("/api/orders/{id}")
```

**@PathVariable:**
```java
@GetMapping("/orders/{id}")
public Order getOrder(@PathVariable Long id) {
  // Extracts 'id' from URL
}
```

**@RequestParam:**
```java
@GetMapping("/orders")
public List<Order> getOrders(
  @RequestParam(defaultValue = "0") int page
) {
  // Extracts query parameter: /orders?page=1
}
```

**@RequestBody:**
```java
@PostMapping("/orders")
public Order create(@RequestBody Order order) {
  // Binds JSON from request body to Order object
}
```

**@Valid:**
```java
@PostMapping("/orders")
public Order create(@Valid @RequestBody Order order) {
  // Validates @NotNull, @NotBlank, etc.
  // Returns 400 if validation fails
}
```

### 4. JPA & Hibernate

**Entity Mapping:**
```java
@Entity                    // Marks as JPA entity
@Table(name = "orders")    // Table name
public class Order {
  
  @Id                      // Primary key
  @GeneratedValue(strategy = GenerationType.IDENTITY)
  private Long id;
  
  @Column(nullable = false, length = 100)
  private String customerName;
  
  @Enumerated(EnumType.STRING)
  private OrderStatus status;
  
  @Temporal(TemporalType.TIMESTAMP)
  private Date createdAt;
}
```

**Relationships:**
```java
// One-to-Many
@Entity
public class Customer {
  @OneToMany(mappedBy = "customer", cascade = CascadeType.ALL)
  private List<Order> orders;
}

@Entity
public class Order {
  @ManyToOne
  @JoinColumn(name = "customer_id")
  private Customer customer;
}

// Many-to-Many
@Entity
public class Student {
  @ManyToMany
  @JoinTable(
    name = "student_course",
    joinColumns = @JoinColumn(name = "student_id"),
    inverseJoinColumns = @JoinColumn(name = "course_id")
  )
  private List<Course> courses;
}
```

**Lifecycle Callbacks:**
```java
@PrePersist    // Before INSERT
@PostPersist   // After INSERT
@PreUpdate     // Before UPDATE
@PostUpdate    // After UPDATE
@PreRemove     // Before DELETE
@PostRemove    // After DELETE

@PrePersist
protected void onCreate() {
  createdAt = LocalDateTime.now();
}
```

### 5. Spring Data JPA

**Repository Hierarchy:**
```
Repository (marker)
  └─ CrudRepository (basic CRUD)
      └─ PagingAndSortingRepository (pagination + sorting)
          └─ JpaRepository (JPA specific + batch operations)
```

**Query Methods:**
```java
// Method name parsing
List<Order> findByCustomerName(String name);
// SQL: SELECT * FROM orders WHERE customer_name = ?

List<Order> findByCustomerNameAndOrderStatus(String name, OrderStatus status);
// SQL: WHERE customer_name = ? AND order_status = ?

List<Order> findByPriceGreaterThan(BigDecimal price);
// SQL: WHERE price > ?

List<Order> findByProductNameContaining(String name);
// SQL: WHERE product_name LIKE %?%

List<Order> findByCustomerNameOrderByCreatedAtDesc(String name);
// SQL: WHERE customer_name = ? ORDER BY created_at DESC
```

**Custom Queries:**
```java
@Query("SELECT o FROM Order o WHERE o.totalAmount > :amount")
List<Order> findExpensiveOrders(@Param("amount") BigDecimal amount);

@Query(value = "SELECT * FROM orders WHERE total_amount > ?1", nativeQuery = true)
List<Order> findExpensiveOrdersNative(BigDecimal amount);

@Modifying
@Query("UPDATE Order o SET o.orderStatus = :status WHERE o.id = :id")
void updateStatus(@Param("id") Long id, @Param("status") OrderStatus status);
```

### 6. Exception Handling

**@ControllerAdvice:**
```java
@ControllerAdvice
public class GlobalExceptionHandler {
  
  @ExceptionHandler(ResourceNotFoundException.class)
  public ResponseEntity<ErrorResponse> handleNotFound(
    ResourceNotFoundException ex
  ) {
    ErrorResponse error = new ErrorResponse(
      HttpStatus.NOT_FOUND.value(),
      ex.getMessage(),
      LocalDateTime.now()
    );
    return ResponseEntity.status(HttpStatus.NOT_FOUND).body(error);
  }
  
  @ExceptionHandler(ValidationException.class)
  public ResponseEntity<ErrorResponse> handleValidation(
    ValidationException ex
  ) {
    // Return 400 Bad Request
  }
  
  @ExceptionHandler(Exception.class)
  public ResponseEntity<ErrorResponse> handleGeneral(Exception ex) {
    // Return 500 Internal Server Error
  }
}
```

### 7. Validation

**Bean Validation Annotations:**
```java
@Entity
public class Order {
  
  @NotNull(message = "Customer name is required")
  @NotBlank
  @Size(min = 2, max = 100)
  private String customerName;
  
  @Email(message = "Invalid email format")
  private String email;
  
  @Positive(message = "Quantity must be positive")
  @Min(1)
  @Max(1000)
  private Integer quantity;
  
  @DecimalMin("0.01")
  @Digits(integer = 8, fraction = 2)
  private BigDecimal price;
  
  @Pattern(regexp = "^[0-9]{10}$", message = "Invalid phone")
  private String phone;
}
```

**Custom Validator:**
```java
@Target({ElementType.FIELD})
@Retention(RetentionPolicy.RUNTIME)
@Constraint(validatedBy = PhoneValidator.class)
public @interface ValidPhone {
  String message() default "Invalid phone number";
  Class<?>[] groups() default {};
  Class<? extends Payload>[] payload() default {};
}

public class PhoneValidator implements ConstraintValidator<ValidPhone, String> {
  @Override
  public boolean isValid(String phone, ConstraintValidatorContext context) {
    return phone != null && phone.matches("^[0-9]{10}$");
  }
}
```

### 8. Configuration Properties

**application.properties:**
```properties
# Server
server.port=8080
server.servlet.context-path=/api

# Database
spring.datasource.url=jdbc:mysql://localhost:3306/orderdb
spring.datasource.username=root
spring.datasource.password=secret

# JPA
spring.jpa.show-sql=true
spring.jpa.hibernate.ddl-auto=update
spring.jpa.properties.hibernate.format_sql=true

# Logging
logging.level.root=INFO
logging.level.com.ordermanagement=DEBUG
logging.file.name=app.log

# Custom
app.name=Order Management System
app.version=1.0.0
```

**Using Properties:**
```java
@Component
public class AppConfig {
  
  @Value("${app.name}")
  private String appName;
  
  @Value("${server.port}")
  private int port;
}
```

---

## UNIT V: REACT

### 1. React Basics

**What is React:**
- JavaScript library for building UIs
- Component-based architecture
- Virtual DOM for performance
- Declarative programming

**JSX (JavaScript XML):**
```jsx
// JSX allows HTML-like syntax in JavaScript
const element = <h1>Hello, React!</h1>;

// With JavaScript expressions
const name = "John";
const element = <h1>Hello, {name}!</h1>;

// Attributes
const element = <img src={imageUrl} alt="Description" />;

// Children
const element = (
  <div>
    <h1>Title</h1>
    <p>Content</p>
  </div>
);
```

### 2. Components

**Function Component:**
```jsx
function Welcome(props) {
  return <h1>Hello, {props.name}</h1>;
}

// Arrow function
const Welcome = (props) => {
  return <h1>Hello, {props.name}</h1>;
};

// Usage
<Welcome name="John" />
```

**Class Component:**
```jsx
class Welcome extends React.Component {
  render() {
    return <h1>Hello, {this.props.name}</h1>;
  }
}
```

### 3. State & Props

**Props (Properties):**
- Read-only data passed from parent to child
- Cannot be modified by child

```jsx
// Parent
<Greeting name="John" age={30} />

// Child
function Greeting(props) {
  return <p>Hello {props.name}, age {props.age}</p>;
}

// Destructuring
function Greeting({ name, age }) {
  return <p>Hello {name}, age {age}</p>;
}
```

**State (useState Hook):**
```jsx
import { useState } from 'react';

function Counter() {
  const [count, setCount] = useState(0);
  
  return (
    <div>
      <p>Count: {count}</p>
      <button onClick={() => setCount(count + 1)}>
        Increment
      </button>
    </div>
  );
}
```

### 4. Lifecycle & Hooks

**useEffect Hook:**
```jsx
import { useEffect } from 'react';

function Component() {
  // Run after every render
  useEffect(() => {
    console.log("Component rendered");
  });
  
  // Run only once (on mount)
  useEffect(() => {
    console.log("Component mounted");
  }, []);
  
  // Run when dependency changes
  useEffect(() => {
    console.log("Count changed");
  }, [count]);
  
  // Cleanup function
  useEffect(() => {
    const timer = setInterval(() => {}, 1000);
    
    return () => {
      clearInterval(timer);  // Cleanup on unmount
    };
  }, []);
}
```

**Other Hooks:**
```jsx
// useContext (global state)
const value = useContext(MyContext);

// useRef (DOM reference)
const inputRef = useRef(null);
<input ref={inputRef} />

// useMemo (memoization)
const expensiveValue = useMemo(() => {
  return computeExpensiveValue(a, b);
}, [a, b]);

// useCallback (memoize function)
const memoizedCallback = useCallback(() => {
  doSomething(a, b);
}, [a, b]);
```

### 5. Event Handling

```jsx
function Component() {
  // Event handler
  const handleClick = (e) => {
    e.preventDefault();
    console.log("Clicked");
  };
  
  const handleSubmit = (e) => {
    e.preventDefault();
    // Submit form
  };
  
  const handleChange = (e) => {
    setValue(e.target.value);
  };
  
  return (
    <div>
      <button onClick={handleClick}>Click</button>
      
      <form onSubmit={handleSubmit}>
        <input onChange={handleChange} />
      </form>
    </div>
  );
}
```

### 6. Forms

```jsx
function LoginForm() {
  const [formData, setFormData] = useState({
    email: '',
    password: ''
  });
  
  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };
  
  const handleSubmit = (e) => {
    e.preventDefault();
    console.log(formData);
  };
  
  return (
    <form onSubmit={handleSubmit}>
      <input
        name="email"
        value={formData.email}
        onChange={handleChange}
      />
      <input
        name="password"
        type="password"
        value={formData.password}
        onChange={handleChange}
      />
      <button type="submit">Login</button>
    </form>
  );
}
```

### 7. Conditional Rendering

```jsx
function Component({ isLoggedIn }) {
  // if-else
  if (isLoggedIn) {
    return <Dashboard />;
  } else {
    return <Login />;
  }
  
  // Ternary
  return (
    <div>
      {isLoggedIn ? <Dashboard /> : <Login />}
    </div>
  );
  
  // Logical AND
  return (
    <div>
      {isLoggedIn && <Dashboard />}
      {error && <ErrorMessage />}
    </div>
  );
}
```

### 8. Lists & Keys

```jsx
function TodoList({ todos }) {
  return (
    <ul>
      {todos.map((todo) => (
        <li key={todo.id}>
          {todo.text}
        </li>
      ))}
    </ul>
  );
}

// Keys help React identify which items changed
// Must be unique among siblings
// Don't use index as key if list can change
```

---

## UNIT VI: NODE.JS

### 1. Node.js Basics

**What is Node.js:**
- JavaScript runtime built on Chrome's V8 engine
- Server-side JavaScript
- Event-driven, non-blocking I/O
- Single-threaded with event loop

**Why Node.js:**
- JavaScript on both frontend and backend
- Fast (V8 engine)
- Large ecosystem (NPM)
- Real-time applications (WebSockets)
- Scalable

### 2. Modules

**Built-in Modules:**
```javascript
// File system
const fs = require('fs');

// Read file
fs.readFile('file.txt', 'utf8', (err, data) => {
  if (err) throw err;
  console.log(data);
});

// Write file
fs.writeFile('file.txt', 'Hello', (err) => {
  if (err) throw err;
});

// HTTP
const http = require('http');

// Path
const path = require('path');
const fullPath = path.join(__dirname, 'file.txt');

// OS
const os = require('os');
console.log(os.platform());
console.log(os.cpus());
```

**Creating Modules:**
```javascript
// math.js
function add(a, b) {
  return a + b;
}

module.exports = { add };

// app.js
const math = require('./math');
console.log(math.add(2, 3));
```

### 3. NPM (Node Package Manager)

**Commands:**
```bash
# Initialize project
npm init
npm init -y  # Skip questions

# Install package
npm install express
npm install --save-dev nodemon  # Dev dependency
npm install -g pm2  # Global install

# Uninstall
npm uninstall express

# Update
npm update

# List packages
npm list
npm list -g  # Global packages
```

**package.json:**
```json
{
  "name": "myapp",
  "version": "1.0.0",
  "description": "My app",
  "main": "app.js",
  "scripts": {
    "start": "node app.js",
    "dev": "nodemon app.js",
    "test": "jest"
  },
  "dependencies": {
    "express": "^4.18.0"
  },
  "devDependencies": {
    "nodemon": "^2.0.0"
  }
}
```

### 4. Express.js

**Basic Server:**
```javascript
const express = require('express');
const app = express();

// Middleware
app.use(express.json());  // Parse JSON bodies

// Routes
app.get('/', (req, res) => {
  res.send('Hello World');
});

app.get('/api/users', (req, res) => {
  res.json({ users: ['John', 'Jane'] });
});

app.post('/api/users', (req, res) => {
  const user = req.body;
  res.status(201).json(user);
});

app.put('/api/users/:id', (req, res) => {
  const id = req.params.id;
  res.json({ updated: true });
});

app.delete('/api/users/:id', (req, res) => {
  res.json({ deleted: true });
});

// Start server
app.listen(3000, () => {
  console.log('Server running on port 3000');
});
```

**Middleware:**
```javascript
// Logger middleware
app.use((req, res, next) => {
  console.log(`${req.method} ${req.url}`);
  next();  // Pass to next middleware
});

// Error handling
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).send('Something broke!');
});
```

**Static Files:**
```javascript
app.use(express.static('public'));
// Serves files from 'public' directory
// Access: http://localhost:3000/style.css
```

### 5. Database Connectivity

**MongoDB (Mongoose):**
```javascript
const mongoose = require('mongoose');

// Connect
mongoose.connect('mongodb://localhost:27017/mydb');

// Schema
const userSchema = new mongoose.Schema({
  name: String,
  email: { type: String, required: true, unique: true },
  age: Number
});

// Model
const User = mongoose.model('User', userSchema);

// Create
const user = new User({
  name: 'John',
  email: 'john@example.com',
  age: 30
});
user.save();

// Find
User.find({ age: { $gt: 18 } });
User.findById(id);
User.findOne({ email: 'john@example.com' });

// Update
User.findByIdAndUpdate(id, { age: 31 });

// Delete
User.findByIdAndDelete(id);
```

**MySQL:**
```javascript
const mysql = require('mysql');

const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: 'secret',
  database: 'mydb'
});

connection.connect();

connection.query('SELECT * FROM users', (err, results) => {
  if (err) throw err;
  console.log(results);
});

connection.end();
```

---

**END OF THEORY GUIDE**

This completes the comprehensive theory for all units. Review this along with the code in the project for complete viva preparation!
