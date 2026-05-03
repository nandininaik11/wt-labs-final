# ⚛️ LAB 7 - REACT SEMESTER RESULT SYSTEM
## Complete Package with Web Technology Syllabus Theory & Viva Preparation

---

## 📋 PROBLEM STATEMENT

**Task:** Create a semester result management system using React that:
- Displays list of students with their basic performance metrics
- Shows detailed result for individual students
- Calculates total marks, percentage, grade, and result status
- Provides subject-wise performance analysis
- Demonstrates React concepts (Components, State, Props, Events)

---

## 📖 COMPLETE WEB TECHNOLOGY SYLLABUS THEORY

### 🔷 SECTION 1: WEB FUNDAMENTALS

#### 1.1 What is the World Wide Web (WWW)?

**Simple Explanation:**
The World Wide Web is a system of interconnected documents and resources accessed via the Internet using web browsers.

**Technical Definition:**
The WWW is an information space where documents and resources are identified by URLs (Uniform Resource Locators), interlinked by hypertext links, and accessible via the Internet using HTTP protocol.

**Key Components:**
1. **Web Pages:** HTML documents containing text, images, links
2. **Web Servers:** Computers hosting web pages
3. **Web Browsers:** Software to access and display web pages
4. **HTTP/HTTPS:** Protocols for transferring web pages
5. **URLs:** Addresses to locate resources

**WWW vs Internet:**
- **Internet:** Physical network infrastructure (cables, routers, servers)
- **WWW:** Service running on top of Internet (websites, pages, links)
- Analogy: Internet = Highway system, WWW = Cars and destinations

**History:**
- Invented by Tim Berners-Lee in 1989 at CERN
- First website: August 6, 1991
- Revolutionized information sharing globally

#### 1.2 Client-Server Architecture

**Simple Explanation:**
A system where one computer (client) requests services and another computer (server) provides those services.

**How It Works:**
```
Client (Browser)  →  Request  →  Server (Web Server)
Client (Browser)  ←  Response ←  Server (Web Server)
```

**Client Responsibilities:**
- Initiates requests
- Displays web pages (rendering)
- Handles user interactions
- Executes client-side code (JavaScript)
- Manages local storage (cookies, localStorage)

**Server Responsibilities:**
- Listens for requests
- Processes requests (business logic)
- Accesses databases
- Sends responses (HTML, JSON, files)
- Manages sessions and authentication

**Communication Flow:**
1. User types URL in browser (client)
2. Browser sends HTTP request to server
3. Server processes request
4. Server queries database if needed
5. Server generates response (HTML/JSON)
6. Server sends response back to client
7. Browser renders received data

**Advantages:**
- **Centralized:** Data stored on server (secure, backed up)
- **Scalable:** Add more servers as users grow
- **Maintainable:** Update server code without changing clients
- **Secure:** Sensitive operations on server, not client

**Types:**
- **2-Tier:** Client ↔ Server (simple applications)
- **3-Tier:** Client ↔ Application Server ↔ Database Server
- **N-Tier:** Multiple layers (modern cloud apps)

#### 1.3 HTTP Protocol

**What is HTTP?**
HyperText Transfer Protocol - Foundation of data communication on the web.

**HTTP Request Structure:**
```
GET /index.html HTTP/1.1
Host: www.example.com
User-Agent: Mozilla/5.0
Accept: text/html
Cookie: session=abc123
```

**HTTP Response Structure:**
```
HTTP/1.1 200 OK
Content-Type: text/html
Content-Length: 1234
Set-Cookie: session=xyz789

<html>...</html>
```

**HTTP Methods (Verbs):**
- **GET:** Retrieve data (read operation)
- **POST:** Submit data (create operation)
- **PUT:** Update entire resource
- **PATCH:** Update partial resource
- **DELETE:** Remove resource
- **HEAD:** Get headers only (no body)
- **OPTIONS:** Get supported methods

**HTTP Status Codes:**
- **1xx Informational:** 100 Continue
- **2xx Success:**
  - 200 OK - Request succeeded
  - 201 Created - Resource created
  - 204 No Content - Success, no response body
- **3xx Redirection:**
  - 301 Moved Permanently - URL changed forever
  - 302 Found - Temporary redirect
  - 304 Not Modified - Use cached version
- **4xx Client Errors:**
  - 400 Bad Request - Invalid syntax
  - 401 Unauthorized - Authentication required
  - 403 Forbidden - No permission
  - 404 Not Found - Resource doesn't exist
- **5xx Server Errors:**
  - 500 Internal Server Error - Server crashed
  - 502 Bad Gateway - Invalid response from upstream
  - 503 Service Unavailable - Server overloaded

**HTTP vs HTTPS:**
- **HTTP:** Unencrypted (plain text)
- **HTTPS:** Encrypted with SSL/TLS
- **Security:** HTTPS prevents man-in-the-middle attacks
- **Port:** HTTP uses 80, HTTPS uses 443
- **Certificate:** HTTPS requires SSL certificate

**Stateless Nature:**
- HTTP doesn't remember previous requests
- Each request is independent
- Solution: Cookies and Sessions

---

### 🔷 SECTION 2: HTML (HyperText Markup Language)

#### 2.1 HTML Fundamentals

**What is HTML?**
HTML is the standard markup language for creating web pages. It describes the structure and content of a webpage.

**HTML Document Structure:**
```html
<!DOCTYPE html>          <!-- Document type declaration -->
<html lang="en">         <!-- Root element -->
  <head>                 <!-- Metadata section -->
    <meta charset="UTF-8">
    <title>Page Title</title>
  </head>
  <body>                 <!-- Visible content -->
    <h1>Heading</h1>
    <p>Paragraph</p>
  </body>
</html>
```

**Key Concepts:**
- **Elements:** Building blocks (h1, p, div, span)
- **Tags:** <tagname> opening, </tagname> closing
- **Attributes:** name="value" inside opening tag
- **Nesting:** Elements inside elements
- **Semantic HTML:** Meaningful tags (header, nav, article, footer)

**Common HTML Tags:**
```html
<!-- Text -->
<h1> to <h6>     Headings (h1 largest, h6 smallest)
<p>              Paragraph
<span>           Inline container
<div>            Block container

<!-- Formatting -->
<strong>         Bold (semantic importance)
<em>             Italic (semantic emphasis)
<br>             Line break
<hr>             Horizontal rule

<!-- Links & Images -->
<a href="url">   Hyperlink
<img src="url">  Image

<!-- Lists -->
<ul>             Unordered list (bullets)
<ol>             Ordered list (numbers)
<li>             List item

<!-- Tables -->
<table>          Table container
<thead>          Table header
<tbody>          Table body
<tr>             Table row
<th>             Header cell
<td>             Data cell

<!-- Forms -->
<form>           Form container
<input>          Input field
<textarea>       Multi-line text
<button>         Clickable button
<select>         Dropdown menu

<!-- Semantic HTML5 -->
<header>         Page/section header
<nav>            Navigation links
<main>           Main content
<article>        Self-contained content
<section>        Thematic grouping
<aside>          Sidebar content
<footer>         Page/section footer
```

**HTML5 Features:**
- Semantic elements (header, nav, article)
- Audio/Video support (<audio>, <video>)
- Canvas for graphics
- Local storage (localStorage, sessionStorage)
- Geolocation API
- Form validation attributes

#### 2.2 HTML Forms

**Form Structure:**
```html
<form action="/submit" method="POST">
  <label for="name">Name:</label>
  <input type="text" id="name" name="username" required>
  
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  
  <label for="password">Password:</label>
  <input type="password" id="password" name="password" required>
  
  <button type="submit">Submit</button>
</form>
```

**Input Types:**
- text, password, email, number, tel, url
- date, time, datetime-local, month, week
- checkbox, radio, file
- color, range, search

**Form Attributes:**
- action: Where to send form data
- method: GET or POST
- required: Mandatory field
- pattern: Regex validation
- min/max: Value range
- placeholder: Hint text

---

### 🔷 SECTION 3: CSS (Cascading Style Sheets)

#### 3.1 CSS Fundamentals

**What is CSS?**
CSS is used to style HTML elements - colors, layouts, fonts, animations.

**CSS Syntax:**
```css
selector {
  property: value;
  property: value;
}
```

**Selectors:**
```css
/* Element Selector */
p { color: blue; }

/* Class Selector */
.classname { font-size: 16px; }

/* ID Selector */
#idname { margin: 10px; }

/* Descendant Selector */
div p { color: red; }

/* Child Selector */
div > p { color: green; }

/* Pseudo-class */
a:hover { color: purple; }
```

**Box Model:**
```
Content → Padding → Border → Margin
```
- **Content:** Actual element content
- **Padding:** Space inside border
- **Border:** Line around padding
- **Margin:** Space outside border

**Common Properties:**
```css
/* Colors */
color: red;
background-color: #3498db;

/* Text */
font-family: Arial, sans-serif;
font-size: 16px;
font-weight: bold;
text-align: center;

/* Layout */
width: 100px;
height: 50px;
margin: 10px;
padding: 20px;
display: block | inline | flex | grid;

/* Positioning */
position: static | relative | absolute | fixed | sticky;
top: 0;
left: 0;

/* Flexbox */
display: flex;
justify-content: center;
align-items: center;
flex-direction: row | column;

/* Grid */
display: grid;
grid-template-columns: 1fr 1fr 1fr;
gap: 20px;
```

**CSS Specificity:**
```
Inline styles (1000) > ID (100) > Class (10) > Element (1)
```

#### 3.2 Responsive Design

**What is Responsive Design?**
Web pages that adapt to different screen sizes (desktop, tablet, mobile).

**Media Queries:**
```css
/* Mobile First */
.container {
  width: 100%;
}

/* Tablet */
@media (min-width: 768px) {
  .container {
    width: 750px;
  }
}

/* Desktop */
@media (min-width: 1024px) {
  .container {
    width: 1000px;
  }
}
```

**Viewport Meta Tag:**
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

**Responsive Units:**
- px: Fixed pixels
- %: Percentage of parent
- em: Relative to parent font-size
- rem: Relative to root font-size
- vh: Viewport height (1vh = 1% of viewport height)
- vw: Viewport width (1vw = 1% of viewport width)

---

### 🔷 SECTION 4: JAVASCRIPT FUNDAMENTALS

#### 4.1 JavaScript Basics

**What is JavaScript?**
Programming language that adds interactivity to web pages. Runs in browser (client-side) and server (Node.js).

**Variables:**
```javascript
var oldWay = "avoid in modern JS";
let changeable = "can be reassigned";
const constant = "cannot be reassigned";
```

**Data Types:**
```javascript
// Primitive Types
let string = "text";
let number = 42;
let boolean = true;
let undefined = undefined;
let nullValue = null;

// Complex Types
let array = [1, 2, 3];
let object = { name: "John", age: 25 };
let function = () => { return "hello"; };
```

**Operators:**
```javascript
// Arithmetic
+, -, *, /, %, **

// Comparison
==  (equal value)
=== (equal value and type)
!=, !==, <, >, <=, >=

// Logical
&& (and), || (or), ! (not)

// Ternary
condition ? ifTrue : ifFalse
```

**Control Flow:**
```javascript
// If-Else
if (condition) {
  // code
} else if (condition) {
  // code
} else {
  // code
}

// Switch
switch(value) {
  case 'a':
    // code
    break;
  default:
    // code
}

// Loops
for (let i = 0; i < 10; i++) { }
while (condition) { }
array.forEach(item => { });
```

**Functions:**
```javascript
// Function Declaration
function greet(name) {
  return "Hello " + name;
}

// Arrow Function
const greet = (name) => {
  return `Hello ${name}`;
};

// Shorter Arrow Function
const greet = name => `Hello ${name}`;
```

#### 4.2 Arrays & Objects

**Arrays:**
```javascript
let fruits = ['apple', 'banana', 'orange'];

// Array Methods
fruits.push('mango');        // Add to end
fruits.pop();                // Remove from end
fruits.unshift('grape');     // Add to start
fruits.shift();              // Remove from start

// Iteration Methods
fruits.map(fruit => fruit.toUpperCase());
fruits.filter(fruit => fruit.length > 5);
fruits.reduce((sum, fruit) => sum + fruit.length, 0);
fruits.forEach(fruit => console.log(fruit));
```

**Objects:**
```javascript
let person = {
  name: 'John',
  age: 25,
  greet: function() {
    return `Hello, I'm ${this.name}`;
  }
};

// Accessing Properties
person.name;         // Dot notation
person['age'];       // Bracket notation

// Destructuring
const { name, age } = person;
```

#### 4.3 DOM Manipulation

**What is DOM?**
Document Object Model - Programming interface for HTML. JavaScript can change HTML structure, content, and style.

**Selecting Elements:**
```javascript
// By ID
document.getElementById('myId');

// By Class
document.getElementsByClassName('myClass');

// By Tag
document.getElementsByTagName('p');

// Query Selector (modern)
document.querySelector('.myClass');
document.querySelectorAll('div.myClass');
```

**Modifying Elements:**
```javascript
// Change Content
element.innerHTML = '<strong>New Content</strong>';
element.textContent = 'Plain Text';

// Change Attributes
element.setAttribute('src', 'image.jpg');
element.getAttribute('src');

// Change Style
element.style.color = 'red';
element.style.fontSize = '20px';

// Add/Remove Classes
element.classList.add('active');
element.classList.remove('hidden');
element.classList.toggle('visible');
```

**Event Handling:**
```javascript
// Click Event
button.addEventListener('click', function() {
  alert('Button clicked!');
});

// Form Submit
form.addEventListener('submit', function(event) {
  event.preventDefault();  // Stop form from submitting
  // Handle form data
});

// Common Events
click, dblclick, mouseover, mouseout
keydown, keyup, keypress
submit, focus, blur, change
load, resize, scroll
```

---

### 🔷 SECTION 5: REACT.JS (This Lab Focus)

#### 5.1 What is React?

**Simple Explanation:**
React is a JavaScript library for building user interfaces, especially single-page applications with dynamic, interactive content.

**Key Concepts:**
- **Component-Based:** UI divided into reusable components
- **Declarative:** Describe what UI should look like, React handles updates
- **Virtual DOM:** Efficient updates to actual DOM
- **Unidirectional Data Flow:** Data flows parent → child via props

**Why Use React?**
1. **Reusability:** Create component once, use everywhere
2. **Performance:** Virtual DOM minimizes actual DOM changes
3. **Ecosystem:** Massive library support (routing, state management)
4. **Community:** Large, active developer community
5. **React Native:** Same skills for mobile apps

#### 5.2 JSX (JavaScript XML)

**What is JSX?**
Syntax extension that looks like HTML but is JavaScript. Gets compiled to React.createElement() calls.

**JSX Examples:**
```jsx
// JSX
const element = <h1>Hello World</h1>;

// Compiled to:
const element = React.createElement('h1', null, 'Hello World');

// Embedding Expressions
const name = 'John';
const greeting = <h1>Hello, {name}!</h1>;

// Attributes
const img = <img src={imageUrl} alt="Description" />;

// Children
const div = (
  <div>
    <h1>Title</h1>
    <p>Paragraph</p>
  </div>
);
```

**JSX Rules:**
1. Must return single parent element
2. Use className instead of class
3. Use camelCase for attributes (onClick not onclick)
4. Close all tags (<br /> not <br>)
5. JavaScript expressions in {}

#### 5.3 Components

**Functional Components (Modern):**
```jsx
function Welcome(props) {
  return <h1>Hello, {props.name}</h1>;
}

// Arrow Function Component
const Welcome = ({ name }) => {
  return <h1>Hello, {name}</h1>;
};
```

**Class Components (Legacy):**
```jsx
class Welcome extends React.Component {
  render() {
    return <h1>Hello, {this.props.name}</h1>;
  }
}
```

**Component Best Practices:**
- One component per file
- PascalCase naming (MyComponent)
- Small, focused components
- Extract reusable logic

#### 5.4 Props

**What are Props?**
Props (properties) are how data flows from parent to child components. Props are READ-ONLY.

**Passing Props:**
```jsx
// Parent Component
function App() {
  return <Welcome name="John" age={25} />;
}

// Child Component
function Welcome(props) {
  return <p>Hello {props.name}, age {props.age}</p>;
}

// With Destructuring
function Welcome({ name, age }) {
  return <p>Hello {name}, age {age}</p>;
}
```

**Props Types:**
- String: name="John"
- Number: age={25}
- Boolean: active={true}
- Array: items={['a', 'b']}
- Object: user={{ name: 'John' }}
- Function: onClick={handleClick}

#### 5.5 State

**What is State?**
State is data that changes over time. When state changes, component re-renders.

**useState Hook:**
```jsx
import { useState } from 'react';

function Counter() {
  // Declare state variable
  const [count, setCount] = useState(0);
  // count: current value
  // setCount: function to update value
  // useState(0): initial value

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

**State Rules:**
1. Don't modify state directly (use setState function)
2. State updates may be asynchronous
3. State is local to component
4. State can be passed to children as props

**Multiple State Variables:**
```jsx
const [name, setName] = useState('');
const [age, setAge] = useState(0);
const [isActive, setIsActive] = useState(false);
```

#### 5.6 Event Handling

**Events in React:**
```jsx
function Button() {
  const handleClick = () => {
    alert('Clicked!');
  };

  return <button onClick={handleClick}>Click Me</button>;
}
```

**Event with Parameters:**
```jsx
function List() {
  const handleClick = (id) => {
    console.log('Clicked item:', id);
  };

  return (
    <button onClick={() => handleClick(5)}>
      Click Item 5
    </button>
  );
}
```

**Form Events:**
```jsx
function Form() {
  const [value, setValue] = useState('');

  const handleChange = (e) => {
    setValue(e.target.value);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log('Submitted:', value);
  };

  return (
    <form onSubmit={handleSubmit}>
      <input value={value} onChange={handleChange} />
      <button type="submit">Submit</button>
    </form>
  );
}
```

#### 5.7 Array Methods (map, filter, reduce)

**map() - Transform Array:**
```jsx
function StudentList({ students }) {
  return (
    <ul>
      {students.map(student => (
        <li key={student.id}>{student.name}</li>
      ))}
    </ul>
  );
}
```

**filter() - Filter Array:**
```jsx
const passedStudents = students.filter(student => {
  return student.percentage >= 40;
});
```

**reduce() - Accumulate Values:**
```jsx
const totalMarks = subjects.reduce((sum, subject) => {
  return sum + subject.marks;
}, 0);
```

#### 5.8 Conditional Rendering

**If-Else:**
```jsx
function Greeting({ isLoggedIn }) {
  if (isLoggedIn) {
    return <h1>Welcome back!</h1>;
  }
  return <h1>Please sign in.</h1>;
}
```

**Ternary Operator:**
```jsx
function Status({ isActive }) {
  return (
    <p>{isActive ? 'Active' : 'Inactive'}</p>
  );
}
```

**Logical AND:**
```jsx
function Notification({ hasMessages, count }) {
  return (
    <div>
      {hasMessages && <p>You have {count} messages</p>}
    </div>
  );
}
```

---

### 🔷 SECTION 6: PHP (Backend)

#### 6.1 PHP Basics

**What is PHP?**
Server-side scripting language for web development. Processes on server, sends HTML to client.

**PHP Syntax:**
```php
<?php
// PHP code here
echo "Hello World";
?>
```

**Variables:**
```php
$string = "text";
$number = 42;
$boolean = true;
$array = array(1, 2, 3);
$assoc = ['name' => 'John', 'age' => 25];
```

**Superglobals:**
```php
$_GET      // URL parameters
$_POST     // Form data
$_SERVER   // Server information
$_SESSION  // Session variables
$_COOKIE   // Cookies
$_FILES    // Uploaded files
```

#### 6.2 PHP MySQL

**Connection:**
```php
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
```

**Prepared Statements:**
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
```

---

### 🔷 SECTION 7: AJAX & JSON

#### 7.1 AJAX (Asynchronous JavaScript and XML)

**What is AJAX?**
Technique to update web pages without reloading. Sends/receives data in background.

**Fetch API (Modern):**
```javascript
fetch('https://api.example.com/data')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Error:', error));
```

**POST Request:**
```javascript
fetch('/api/submit', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({ name: 'John' })
})
.then(response => response.json())
.then(data => console.log(data));
```

#### 7.2 JSON (JavaScript Object Notation)

**What is JSON?**
Lightweight data format for exchanging data between server and client.

**JSON Format:**
```json
{
  "name": "John",
  "age": 25,
  "subjects": ["Math", "Science"],
  "address": {
    "city": "Mumbai",
    "state": "Maharashtra"
  }
}
```

**JavaScript JSON Methods:**
```javascript
// Parse JSON string to object
const obj = JSON.parse('{"name":"John"}');

// Convert object to JSON string
const json = JSON.stringify({ name: 'John' });
```

---

### 🔷 SECTION 8: WEB SECURITY

#### 8.1 Common Vulnerabilities

**XSS (Cross-Site Scripting):**
- Attacker injects malicious JavaScript
- Prevention: htmlspecialchars(), Content Security Policy

**SQL Injection:**
- Attacker injects malicious SQL
- Prevention: Prepared statements, input validation

**CSRF (Cross-Site Request Forgery):**
- Attacker tricks user into unwanted actions
- Prevention: CSRF tokens, SameSite cookies

**Clickjacking:**
- Attacker tricks user into clicking hidden elements
- Prevention: X-Frame-Options header

#### 8.2 Security Best Practices

1. **Input Validation:** Validate all user input
2. **Sanitization:** Clean data before use
3. **HTTPS:** Encrypt data in transit
4. **Authentication:** Verify user identity
5. **Authorization:** Control resource access
6. **Password Hashing:** Never store plain text passwords
7. **Session Management:** Secure session handling
8. **Regular Updates:** Keep software updated

---

## 📁 FILE STRUCTURE

```
lab7_react_result_system/
│
├── package.json              ← Project configuration & dependencies
├── README.md                 ← This file (complete documentation)
│
├── public/
│   └── index.html           ← HTML template (root div)
│
└── src/
    ├── index.js             ← React app entry point
    ├── index.css            ← Global CSS reset
    ├── App.js               ← Main component (state, routing logic)
    ├── App.css              ← Main component styles
    │
    └── components/
        ├── Header.js        ← Header component (title, semester)
        ├── StudentList.js   ← Student list display (map, events)
        └── ResultCalculator.js ← Detailed result view (calculations)
```

**Component Hierarchy:**
```
App (Root)
├── Header
└── Conditional Render:
    ├── StudentList (if no selection)
    │   └── Student Cards (mapped from array)
    └── ResultCalculator (if student selected)
        ├── Summary Cards
        ├── Marks Table
        └── Analysis Section
```

---

## ⚙️ SETUP & RUN COMMANDS

### Prerequisites

**Required Software:**
- Node.js (v14 or higher) - includes npm
- Text editor (VS Code recommended)
- Web browser (Chrome, Firefox)

### Installation Steps

**Step 1: Extract & Navigate**
```bash
# Extract ZIP file
# Navigate to project folder
cd lab7_react_result_system
```

**Step 2: Install Dependencies**
```bash
# Install all required packages (React, React-DOM, React-Scripts)
npm install

# This command:
# 1. Reads package.json dependencies
# 2. Downloads packages from npm registry
# 3. Creates node_modules folder
# 4. Takes 1-2 minutes

# If you get errors, try:
npm install --legacy-peer-deps
```

**Step 3: Start Development Server**
```bash
# Start React development server
npm start

# This command:
# 1. Starts webpack dev server
# 2. Opens browser at http://localhost:3000
# 3. Enables hot reload (auto-refresh on code changes)
# 4. Shows compilation errors in terminal

# Server runs at: http://localhost:3000
# To stop: Press Ctrl+C in terminal
```

**Step 4: Build for Production (Optional)**
```bash
# Create optimized production build
npm run build

# This command:
# 1. Minifies JavaScript/CSS
# 2. Optimizes for performance
# 3. Creates /build folder
# 4. Ready for deployment

# Deploy /build folder to:
# - Netlify, Vercel, GitHub Pages
# - Any static hosting service
```

### Troubleshooting

**Problem: npm not recognized**
```bash
# Solution: Install Node.js from https://nodejs.org
# Node.js includes npm
# Restart terminal after installation
```

**Problem: Port 3000 already in use**
```bash
# Solution: Kill process on port 3000
# Windows:
netstat -ano | findstr :3000
taskkill /PID <PID> /F

# Mac/Linux:
lsof -ti:3000 | xargs kill
```

**Problem: Module not found**
```bash
# Solution: Delete and reinstall
rm -rf node_modules package-lock.json
npm install
```

---

## 🖥️ EXPECTED OUTPUT - EXAMINER DEMONSTRATION

### Initial Page Load

**What Examiner Sees:**
1. **Header Section:**
   - Title: "Semester Result System"
   - Badge: "Semester 5"
   - Dark blue gradient background

2. **Student List Page:**
   - Title: "Student List"
   - Instruction: "Click on any student to view detailed results"
   - 4 student cards displayed in grid

3. **Each Student Card Shows:**
   - Student name (e.g., "John Doe")
   - Roll number (e.g., "CS101")
   - Total marks (e.g., "418/500")
   - Percentage (e.g., "83.60%")
   - Grade (colored badge: A+, A, B+, etc.)
   - Result (PASS in green or FAIL in red)

4. **Visual Features:**
   - Purple gradient page background
   - White cards with shadows
   - Green left border for PASS students
   - Red left border for FAIL students
   - Hover effect (card lifts on mouse over)

### Test Case 1: View John Doe's Result

**Action:** Click on "John Doe" card

**Expected Output:**

1. **Navigation:**
   - Student list disappears
   - Detailed result page appears
   - Blue "← Back to Student List" button at top

2. **Result Header:**
   - Name: "John Doe"
   - Details: "Roll No: CS101 | Semester: 5"
   - Large green "PASS" badge (right side)

3. **Summary Cards (4 cards in row):**
   ```
   Card 1 - Total Marks
   418 / 500
   
   Card 2 - Percentage
   83.60%
   
   Card 3 - Grade
   A (large, purple text)
   
   Card 4 - Distinctions
   4
   Subjects ≥ 75%
   ```

4. **Subject-wise Performance Table:**
   ```
   Subject              Marks  Max   Percentage  Status
   Web Technology       85     100   85.00%      Pass ✓
   Database Management  78     100   78.00%      Pass ✓
   Software Engineering 92     100   92.00%      Pass ✓
   Computer Networks    88     100   88.00%      Pass ✓
   Operating Systems    75     100   75.00%      Pass ✓
   
   TOTAL:               418    500   83.60%
   ```

5. **Performance Analysis:**
   - Green card: "💪 Strongest Subject"
     - Software Engineering
     - 92/100
   - Orange card: "📈 Needs Improvement"
     - Operating Systems
     - 75/100

6. **Remarks:**
   - "Excellent performance! Keep up the great work."

### Test Case 2: View Bob Johnson's Result

**Action:** Click "Bob Johnson" card

**Expected Output:**

1. **Result Badge:** Green "PASS" (since 70.6% > 40%)

2. **Summary:**
   - Total: 353/500
   - Percentage: 70.60%
   - Grade: B+ (orange/yellow color)
   - Distinctions: 0 (no subject ≥ 75%)

3. **Subject Table:**
   ```
   Web Technology       72     100   72.00%      Pass ✓
   Database Management  65     100   65.00%      Pass ✓
   Software Engineering 78     100   78.00%      Pass ✓
   Computer Networks    70     100   70.00%      Pass ✓
   Operating Systems    68     100   68.00%      Pass ✓
   ```

4. **Analysis:**
   - Strongest: Software Engineering (78/100)
   - Needs Improvement: Database Management (65/100)

5. **Remarks:**
   - "Good performance. Focus on improving weaker subjects."

### Test Case 3: Navigate Back

**Action:** Click "← Back to Student List" button

**Expected Output:**
- Detailed view disappears
- Student list reappears
- All 4 student cards visible again
- Page state reset

### Visual Elements to Show Examiner

**Design Features:**
1. ✓ Responsive grid layout (adapts to screen size)
2. ✓ Gradient backgrounds (purple page, blue header)
3. ✓ Card-based UI (modern design pattern)
4. ✓ Color coding (green=pass, red=fail, grade colors)
5. ✓ Smooth hover effects (cards lift on hover)
6. ✓ Clear typography hierarchy
7. ✓ Consistent spacing and alignment
8. ✓ Professional color scheme
9. ✓ Table formatting with alternating rows
10. ✓ Responsive behavior (works on mobile/desktop)

**Interactive Features:**
1. ✓ Click student card → Navigate to details
2. ✓ Click back button → Return to list
3. ✓ Hover effects on cards
4. ✓ Dynamic data rendering
5. ✓ Conditional UI changes

**Data Calculations Shown:**
1. ✓ Total marks summation
2. ✓ Percentage calculation
3. ✓ Grade assignment based on percentage
4. ✓ Pass/Fail determination
5. ✓ Distinction count (≥75%)
6. ✓ Highest/lowest subject identification
7. ✓ Subject-wise percentages

---

## ❓ VIVA QUESTIONS & DETAILED ANSWERS

### React & This Lab Questions

#### Q1: Explain the component structure of this application.

**ANSWER:**

This application follows a hierarchical component structure with clear parent-child relationships:

**Component Tree:**
```
App (Root Component)
├── Header (Always visible)
└── Conditional Rendering:
    ├── StudentList (When no student selected)
    │   └── Student Cards (Mapped from students array)
    └── ResultCalculator (When student selected)
        ├── Summary Cards
        ├── Marks Table
        └── Analysis Cards
```

**App Component (Parent):**
- **Responsibility:** 
  - Manages application state (students data, selected student)
  - Controls which view to show (list or details)
  - Passes data to children via props
  - Handles navigation logic

- **State:**
  - `students`: Array of all student objects
  - `selectedStudent`: Currently selected student (null or object)

- **Functions:**
  - `handleStudentSelect(student)`: Called when student clicked
  - `handleBackToList()`: Called when back button clicked

**Header Component (Child):**
- **Responsibility:** Display title and semester
- **Props Received:** 
  - `title`: Application title string
  - `semester`: Semester number
- **Type:** Presentational (no state, just displays props)

**StudentList Component (Child):**
- **Responsibility:** 
  - Display all students in cards
  - Calculate summary metrics (total, %, grade)
  - Handle click events
  
- **Props Received:**
  - `students`: Array of student objects
  - `onStudentSelect`: Callback function from parent

- **Key Logic:**
  - `map()` to render cards for each student
  - Calculations: `calculateTotal()`, `calculatePercentage()`, `getGrade()`
  - Event handling: `onClick={() => onStudentSelect(student)}`

**ResultCalculator Component (Child):**
- **Responsibility:**
  - Display detailed results for selected student
  - Calculate comprehensive metrics
  - Show subject-wise breakdown
  - Provide analysis

- **Props Received:**
  - `student`: Selected student object
  - `onBack`: Callback to return to list

- **Key Logic:**
  - `reduce()` for total calculation
  - `filter()` for distinction count
  - Conditional styling based on result
  - Table rendering with `map()`

**Data Flow:**
1. Parent (App) stores all data in state
2. Parent passes data down to children via props
3. Children receive props and display data
4. Children call parent functions when events occur
5. Parent updates state, causing re-render
6. New props flow down, UI updates

This is **unidirectional data flow** - data flows down (parent → child), events flow up (child → parent via callbacks).

---

#### Q2: What is the difference between props and state in React?

**ANSWER:**

**Props (Properties):**

**Definition:** Data passed from parent component to child component.

**Characteristics:**
- **Read-Only:** Child cannot modify props
- **Immutable:** Props are fixed from child's perspective
- **External:** Come from outside the component
- **Flow:** Parent → Child (one-way, downward)
- **Purpose:** Pass data and functions to children

**Example:**
```jsx
// Parent Component
function App() {
  return <Welcome name="John" age={25} />;
  // Passing props: name and age
}

// Child Component
function Welcome(props) {
  // Can READ props
  console.log(props.name);  // "John"
  
  // CANNOT modify props
  props.name = "Jane";  // ❌ Error! Props are read-only
  
  return <h1>Hello {props.name}</h1>;
}
```

**State:**

**Definition:** Data managed within a component that can change over time.

**Characteristics:**
- **Mutable:** Can be changed using setState function
- **Internal:** Managed within the component
- **Triggers Re-render:** When state changes, component re-renders
- **Local:** Private to the component (unless passed as props)
- **Purpose:** Track data that changes (user input, toggles, etc.)

**Example:**
```jsx
function Counter() {
  // Declare state
  const [count, setCount] = useState(0);
  // count: current value (starts at 0)
  // setCount: function to update value
  
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

**Comparison Table:**

| Feature | Props | State |
|---------|-------|-------|
| Definition | Passed from parent | Managed internally |
| Mutable | No (read-only) | Yes (via setState) |
| Who controls | Parent component | Component itself |
| Change triggers render | Yes (parent re-renders) | Yes (component re-renders) |
| Can be passed to children | Yes | Yes (as props) |
| Use case | Configuration, data | Dynamic data |

**In Our Application:**

**Props Example:**
```jsx
// App.js (Parent)
<StudentList 
  students={students}           // Prop
  onStudentSelect={handleStudentSelect}  // Prop (function)
/>

// StudentList.js (Child)
function StudentList({ students, onStudentSelect }) {
  // Receives props
  // CANNOT modify students array
  // CAN call onStudentSelect function
}
```

**State Example:**
```jsx
// App.js
const [selectedStudent, setSelectedStudent] = useState(null);
// State managed in App component
// Can be changed: setSelectedStudent(student)
```

**When State Changes:**
```jsx
const handleStudentSelect = (student) => {
  setSelectedStudent(student);
  // This updates state
  // React automatically re-renders App component
  // New props flow to children
  // UI updates to show ResultCalculator instead of StudentList
};
```

**Key Takeaway:**
- **Props:** Parent tells child what to display (configuration)
- **State:** Component remembers its own data (internal memory)
- **Pattern:** State in parent, props to children

---

#### Q3: What are React hooks? Explain useState.

**ANSWER:**

**What are React Hooks?**

Hooks are functions that let you "hook into" React features from functional components.

**Before Hooks (Problem):**
- Functional components were "dumb" (no state, no lifecycle)
- Had to use class components for state/lifecycle
- Class components are verbose and complex

**After Hooks (Solution):**
- Functional components can have state and lifecycle
- Cleaner, more concise code
- Easier to understand and test

**Common Hooks:**
- **useState:** Manage state
- **useEffect:** Side effects (API calls, subscriptions)
- **useContext:** Access context
- **useRef:** Reference DOM elements
- **useMemo:** Memoize expensive calculations
- **useCallback:** Memoize functions

---

**useState Hook (Detailed):**

**Purpose:** Add state to functional components.

**Syntax:**
```jsx
const [stateVariable, setStateFunction] = useState(initialValue);
```

**How It Works:**
```jsx
import { useState } from 'react';

function Counter() {
  // Declare state variable 'count', initialized to 0
  const [count, setCount] = useState(0);
  
  // count: current value
  // setCount: function to update value
  // 0: initial value
  
  return (
    <div>
      <p>Current count: {count}</p>
      
      {/* Update state */}
      <button onClick={() => setCount(count + 1)}>
        Increment
      </button>
      
      <button onClick={() => setCount(count - 1)}>
        Decrement
      </button>
      
      <button onClick={() => setCount(0)}>
        Reset
      </button>
    </div>
  );
}
```

**What Happens When State Updates:**
1. User clicks "Increment" button
2. `setCount(count + 1)` is called
3. React schedules re-render
4. Component function runs again
5. useState returns NEW count value
6. UI updates with new count

**Multiple State Variables:**
```jsx
function Form() {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [age, setAge] = useState(0);
  const [isActive, setIsActive] = useState(false);
  
  // Each has independent state
}
```

**State with Objects:**
```jsx
function Profile() {
  const [user, setUser] = useState({
    name: '',
    email: '',
    age: 0
  });
  
  // Update single property (must spread existing)
  const updateName = (newName) => {
    setUser({
      ...user,      // Keep existing properties
      name: newName // Update only name
    });
  };
}
```

**State with Arrays:**
```jsx
function TodoList() {
  const [todos, setTodos] = useState([]);
  
  // Add item
  const addTodo = (newTodo) => {
    setTodos([...todos, newTodo]);
  };
  
  // Remove item
  const removeTodo = (index) => {
    setTodos(todos.filter((_, i) => i !== index));
  };
}
```

**In Our Application:**
```jsx
// App.js
const [students] = useState([
  { id: 1, name: 'John', ... },
  { id: 2, name: 'Jane', ... }
]);
// students: state variable (array)
// We don't need setter since data is static

const [selectedStudent, setSelectedStudent] = useState(null);
// selectedStudent: state variable (null or object)
// setSelectedStudent: function to update it

// When student clicked:
const handleStudentSelect = (student) => {
  setSelectedStudent(student);
  // Updates state
  // React re-renders App
  // Shows ResultCalculator with selected student
};
```

**Important Rules:**
1. Only call hooks at top level (not inside loops/conditions)
2. Only call hooks in React functions (not regular JavaScript)
3. State updates may be asynchronous
4. Don't modify state directly (always use setState)

**Why useState is Powerful:**
- Simple API (just [value, setter])
- Preserves state between renders
- Triggers re-render on change
- Works with any data type
- Multiple states independent

---

#### Q4: How does the map() function work in React for rendering lists?

**ANSWER:**

**What is map()?**

`map()` is a JavaScript array method that transforms each element and returns a new array. In React, we use it to convert data arrays into JSX elements for rendering.

**Basic JavaScript map():**
```javascript
const numbers = [1, 2, 3, 4, 5];

// Transform each number to its double
const doubled = numbers.map(num => num * 2);
// Result: [2, 4, 6, 8, 10]

// Original array unchanged
console.log(numbers);  // [1, 2, 3, 4, 5]
```

**map() in React:**
```jsx
function StudentList() {
  const students = [
    { id: 1, name: 'John' },
    { id: 2, name: 'Jane' },
    { id: 3, name: 'Bob' }
  ];
  
  return (
    <ul>
      {students.map(student => (
        <li key={student.id}>{student.name}</li>
      ))}
    </ul>
  );
}

// Renders:
// <ul>
//   <li>John</li>
//   <li>Jane</li>
//   <li>Bob</li>
// </ul>
```

**Step-by-Step Process:**

1. **Input:** Array of data
   ```javascript
   students = [
     { id: 1, name: 'John' },
     { id: 2, name: 'Jane' },
     { id: 3, name: 'Bob' }
   ]
   ```

2. **Transformation:** Each object → JSX element
   ```jsx
   student => <li key={student.id}>{student.name}</li>
   ```

3. **Output:** Array of JSX elements
   ```jsx
   [
     <li key={1}>John</li>,
     <li key={2}>Jane</li>,
     <li key={3}>Bob</li>
   ]
   ```

4. **Rendering:** React renders array of elements

**In Our Application:**
```jsx
// StudentList.js
{students.map((student) => {
  // For EACH student in array
  
  // Calculate metrics
  const percentage = calculatePercentage(student.subjects);
  const grade = getGrade(percentage);
  const result = getResult(percentage);
  
  // Return JSX for this student
  return (
    <div 
      key={student.id}  // Unique key (REQUIRED!)
      className={`student-card ${result === 'PASS' ? 'pass' : 'fail'}`}
      onClick={() => onStudentSelect(student)}
    >
      <h3>{student.name}</h3>
      <p>{student.rollNo}</p>
      <p>Percentage: {percentage}%</p>
      <p>Grade: {grade}</p>
      <p>Result: {result}</p>
    </div>
  );
})}
```

**Key Prop (CRITICAL):**

Every element in a list needs a unique `key` prop:

```jsx
{students.map(student => (
  <div key={student.id}>
    {/* key helps React identify which items changed */}
  </div>
))}
```

**Why Keys Matter:**

Without keys:
```jsx
// React doesn't know which element is which
// If list changes, React re-renders ALL items
// Inefficient and may cause bugs
```

With keys:
```jsx
// React tracks each element by its key
// If list changes, React only updates changed items
// Efficient and maintains state correctly
```

**Good Keys:**
```jsx
✓ key={student.id}           // Unique ID from database
✓ key={student.email}        // Unique email
✓ key={`student-${student.rollNo}`}  // Unique roll number
```

**Bad Keys:**
```jsx
✗ key={index}                // Array index (unstable)
✗ key={Math.random()}        // Random (changes every render)
✗ key={student.name}         // Not unique (duplicates possible)
```

**Why Not Index:**
```jsx
// Problem with index as key:
students.map((student, index) => (
  <div key={index}>  // ❌ BAD!
    {student.name}
  </div>
))

// If list order changes:
// [John, Jane, Bob] → [Jane, John, Bob]
// React thinks John moved to position 1
// Actually Jane moved to position 0
// Causes bugs with state, animations, etc.
```

**Advanced: map() with Index:**
```jsx
{students.map((student, index) => (
  <div key={student.id}>
    <span>#{index + 1}</span>  // Display position
    <span>{student.name}</span>
  </div>
))}
// Using index for DISPLAY (not key) is fine
```

**Nested map():**
```jsx
{students.map(student => (
  <div key={student.id}>
    <h3>{student.name}</h3>
    <ul>
      {student.subjects.map(subject => (
        <li key={subject.name}>
          {subject.name}: {subject.marks}
        </li>
      ))}
    </ul>
  </div>
))}
```

**Filtering Before Mapping:**
```jsx
{students
  .filter(student => student.percentage >= 75)  // Only high scorers
  .map(student => (
    <div key={student.id}>{student.name}</div>
  ))
}
```

**Summary:**
- **map():** Transforms array to JSX
- **key:** Required for list items
- **Performance:** React efficiently updates lists
- **Common Pattern:** data.map(item => JSX)

---

#### Q5: What is conditional rendering? How is it implemented in this app?

**ANSWER:**

**What is Conditional Rendering?**

Displaying different UI based on conditions. Like if-else in regular programming, but for components and JSX.

**Why Needed?**
- Show/hide elements based on state
- Display different content for different users
- Handle loading states
- Show errors conditionally
- Implement navigation (show different pages)

---

**Methods of Conditional Rendering:**

**1. If-Else Statement:**
```jsx
function Greeting({ isLoggedIn }) {
  if (isLoggedIn) {
    return <h1>Welcome back!</h1>;
  } else {
    return <h1>Please sign in.</h1>;
  }
}
```

**2. Ternary Operator (? :):**
```jsx
function Status({ isActive }) {
  return (
    <div>
      {isActive ? <p>Online</p> : <p>Offline</p>}
    </div>
  );
}
```

**3. Logical AND (&&):**
```jsx
function Mailbox({ unreadMessages }) {
  return (
    <div>
      <h1>Inbox</h1>
      {unreadMessages.length > 0 && (
        <p>You have {unreadMessages.length} unread messages.</p>
      )}
    </div>
  );
}
```

**4. Switch Statement:**
```jsx
function StatusBadge({ status }) {
  switch(status) {
    case 'active':
      return <span className="badge-green">Active</span>;
    case 'pending':
      return <span className="badge-yellow">Pending</span>;
    case 'inactive':
      return <span className="badge-red">Inactive</span>;
    default:
      return <span className="badge-gray">Unknown</span>;
  }
}
```

**5. Element Variables:**
```jsx
function LoginControl({ isLoggedIn }) {
  let button;
  
  if (isLoggedIn) {
    button = <LogoutButton />;
  } else {
    button = <LoginButton />;
  }
  
  return <div>{button}</div>;
}
```

---

**In Our Application:**

**Example 1: Main Navigation (App.js)**
```jsx
{!selectedStudent ? (
  // If NO student selected, show list
  <StudentList 
    students={students} 
    onStudentSelect={handleStudentSelect} 
  />
) : (
  // If student IS selected, show details
  <ResultCalculator 
    student={selectedStudent} 
    onBack={handleBackToList} 
  />
)}
```

**How This Works:**
1. Initially, `selectedStudent` is `null`
2. `!selectedStudent` is `true` (null is falsy)
3. StudentList renders
4. User clicks student card
5. `setSelectedStudent(student)` sets state to student object
6. Component re-renders
7. `!selectedStudent` is now `false` (object is truthy)
8. ResultCalculator renders instead

**Example 2: Pass/Fail Styling (StudentList.js)**
```jsx
<div 
  className={`student-card ${result === 'PASS' ? 'pass' : 'fail'}`}
>
  {/* Ternary operator for dynamic className */}
  {/* If result is "PASS": class="student-card pass" */}
  {/* If result is "FAIL": class="student-card fail" */}
</div>
```

**Example 3: Result Badge Color (ResultCalculator.js)**
```jsx
<div 
  className="result-badge-large"
  style={{ backgroundColor: result === 'PASS' ? '#2ecc71' : '#e74c3c' }}
>
  {result}
</div>
{/* Ternary in inline style */}
{/* Green if PASS, red if FAIL */}
```

**Example 4: Remarks Based on Performance (ResultCalculator.js)**
```jsx
<p>
  {percentage >= 75 
    ? "Excellent performance! Keep up the great work." 
    : percentage >= 60 
    ? "Good performance. Focus on improving weaker subjects." 
    : percentage >= 40 
    ? "Satisfactory performance. More effort needed in some subjects." 
    : "Performance below expectations. Immediate attention required."}
</p>
{/* Nested ternary operators */}
{/* Multiple conditions checked in sequence */}
```

**Example 5: Status Badge in Table (ResultCalculator.js)**
```jsx
{student.subjects.map((subject, index) => {
  const subjectPercentage = ((subject.marks / subject.maxMarks) * 100).toFixed(2);
  const status = subject.marks >= 40 ? 'Pass' : 'Fail';
  
  return (
    <tr key={index}>
      <td>{subject.name}</td>
      <td>{subject.marks}</td>
      <td>
        <span className={`status-badge ${status.toLowerCase()}`}>
          {status}
        </span>
      </td>
    </tr>
  );
})}
{/* Ternary to determine Pass/Fail for each subject */}
```

**Example 6: Grade Color (App.css - applied via className)**
```jsx
const getGrade = () => {
  if (percentage >= 90) return { grade: 'A+', color: '#2ecc71' };
  if (percentage >= 80) return { grade: 'A', color: '#27ae60' };
  if (percentage >= 70) return { grade: 'B+', color: '#f39c12' };
  // ... more conditions
};

<p style={{ color: gradeInfo.color }}>
  {gradeInfo.grade}
</p>
{/* Conditional style based on grade */}
```

---

**Best Practices:**

**DO:**
```jsx
✓ Use ternary for simple true/false
✓ Use && for showing/hiding elements
✓ Use if-else for complex logic outside JSX
✓ Use descriptive variable names
```

**DON'T:**
```jsx
✗ Nest too many ternaries (hard to read)
✗ Use complex logic directly in JSX
✗ Forget null checks
```

**Performance Consideration:**
```jsx
// Both components render, but only one displays
{isLoggedIn ? <UserDashboard /> : <LoginPage />}

// Only selected component renders
{isLoggedIn && <UserDashboard />}
{!isLoggedIn && <LoginPage />}
```

**Summary:**
- **Conditional Rendering:** Show/hide UI based on conditions
- **Methods:** if-else, ternary, &&, switch
- **Our App:** StudentList ↔ ResultCalculator based on selection
- **Use Case:** Navigation, status display, error handling

---

### General Web Technology Questions

#### Q6: Explain the difference between HTML, CSS, and JavaScript.

**ANSWER:**

Think of building a house:
- **HTML:** Structure (walls, rooms, doors)
- **CSS:** Decoration (paint, furniture, style)
- **JavaScript:** Functionality (electricity, plumbing, automation)

**HTML (HyperText Markup Language):**

**Purpose:** Structure and content

**What it does:**
- Defines elements (headings, paragraphs, images, links)
- Organizes content hierarchy
- Provides semantic meaning

**Example:**
```html
<div>
  <h1>Welcome</h1>
  <p>This is a paragraph.</p>
  <button>Click Me</button>
</div>
```

**Output:** Plain, unstyled content
- Heading displays in default font
- Paragraph below it
- Button with default browser styling

**HTML Alone:**
- No colors
- No layout control
- No interactivity
- Just content structure

---

**CSS (Cascading Style Sheets):**

**Purpose:** Presentation and styling

**What it does:**
- Colors, fonts, spacing
- Layouts (positioning, flexbox, grid)
- Animations and transitions
- Responsive design

**Example:**
```css
h1 {
  color: blue;
  font-size: 36px;
  text-align: center;
}

button {
  background: green;
  color: white;
  padding: 10px 20px;
  border-radius: 5px;
}
```

**Effect on HTML:**
- Heading now blue, centered, larger
- Button green with white text, rounded corners
- Professional appearance

**CSS Alone:**
- No structure (needs HTML)
- No interactivity
- Just visual styling

---

**JavaScript:**

**Purpose:** Behavior and interactivity

**What it does:**
- Responds to user actions (clicks, typing)
- Manipulates DOM (add/remove/change elements)
- Communicates with servers (AJAX)
- Performs calculations
- Validates forms

**Example:**
```javascript
const button = document.querySelector('button');

button.addEventListener('click', () => {
  alert('Button clicked!');
  document.querySelector('h1').textContent = 'Changed!';
});
```

**Effect:**
- Button becomes interactive
- Click triggers action
- Content changes dynamically
- Page responds to user

**JavaScript Alone:**
- No structure (needs HTML)
- No styling (needs CSS)
- Just logic and behavior

---

**Together (Complete Web Page):**

**HTML:**
```html
<div class="card">
  <h1 id="title">Counter</h1>
  <p id="count">0</p>
  <button id="btn">Increment</button>
</div>
```

**CSS:**
```css
.card {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

#title {
  color: #333;
  font-size: 24px;
}

#btn {
  background: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
}
```

**JavaScript:**
```javascript
let count = 0;
const countDisplay = document.getElementById('count');
const button = document.getElementById('btn');

button.addEventListener('click', () => {
  count++;
  countDisplay.textContent = count;
});
```

**Result:**
- Structured card (HTML)
- Beautiful styling (CSS)
- Interactive counter (JavaScript)

---

**Comparison Table:**

| Aspect | HTML | CSS | JavaScript |
|--------|------|-----|------------|
| **Purpose** | Structure | Style | Behavior |
| **Type** | Markup Language | Style Sheet | Programming Language |
| **Focus** | Content | Presentation | Logic |
| **File Extension** | .html | .css | .js |
| **Execution** | Parsed by browser | Applied by browser | Executed by browser |
| **Example** | `<div>` | `color: red;` | `alert('Hi');` |

**Dependencies:**
- HTML: Standalone (can exist alone)
- CSS: Needs HTML (can't style nothing)
- JavaScript: Usually needs HTML (manipulates DOM)

**In Our React App:**
- **HTML:** JSX defines structure
- **CSS:** App.css provides styling
- **JavaScript:** React logic, calculations, events

**Summary:**
- **HTML:** "What" is on the page
- **CSS:** "How" it looks
- **JavaScript:** "What" it does

All three work together to create modern web applications!

---

#### Q7: What is the difference between GET and POST methods?

**ANSWER:**

GET and POST are HTTP methods for sending data from client to server.

**GET Method:**

**Purpose:** Retrieve data from server

**How it works:**
- Data sent in URL (query string)
- Visible in address bar
- Example: `page.php?name=John&age=25`

**Characteristics:**
- **Visibility:** Data completely visible
- **Security:** Not secure (passwords visible)
- **Caching:** Browser caches GET requests
- **Bookmarkable:** Yes (URL contains data)
- **History:** Stored in browser history
- **Length Limit:** 2048 characters (URL limit)
- **Data Type:** Only ASCII characters
- **Idempotent:** Yes (multiple calls = same result)

**Use Cases:**
- Search queries
- Filtering data
- Pagination
- Retrieving public data

**Example:**
```javascript
// Search query
https://google.com/search?q=web+technology

// Product filter
https://shop.com/products?category=electronics&price=low

// Pagination
https://blog.com/posts?page=2
```

**Advantages:**
- Simple to use
- Can bookmark results
- Can share links
- Browser back/forward works

**Disadvantages:**
- Data exposed in URL
- Not suitable for sensitive data
- Limited data size
- Can't send binary data

---

**POST Method:**

**Purpose:** Send data to server (create/update)

**How it works:**
- Data sent in HTTP request body
- Not visible in URL
- Example URL: `page.php` (clean)

**Characteristics:**
- **Visibility:** Data hidden in request body
- **Security:** More secure (still need HTTPS)
- **Caching:** Not cached by browser
- **Bookmarkable:** No (data not in URL)
- **History:** Not stored in browser history
- **Length Limit:** No practical limit
- **Data Type:** Binary data allowed (file uploads)
- **Idempotent:** No (multiple calls may create duplicates)

**Use Cases:**
- Login forms
- Registration
- Uploading files
- Creating/updating records
- Submitting forms

**Example:**
```html
<form action="/submit" method="POST">
  <input type="text" name="username">
  <input type="password" name="password">
  <button type="submit">Login</button>
</form>
```

**Advantages:**
- Data not visible in URL
- Can send large amounts of data
- Can send binary data (files)
- More secure than GET

**Disadvantages:**
- Cannot bookmark
- Browser back button may resubmit
- Not cached (may be slower)

---

**Comparison Table:**

| Feature | GET | POST |
|---------|-----|------|
| **Data Location** | URL (query string) | Request body |
| **Visibility** | Visible in address bar | Hidden |
| **Security** | Less secure | More secure |
| **Caching** | Yes | No |
| **Browser History** | Yes | No |
| **Bookmarkable** | Yes | No |
| **Length Limit** | 2048 characters | No limit |
| **File Upload** | No | Yes |
| **Data Type** | ASCII only | Binary allowed |
| **Back Button** | Safe | May resubmit |
| **SEO** | Indexed by search engines | Not indexed |

**Security Comparison:**

**GET (Insecure):**
```
URL: https://bank.com/transfer?to=attacker&amount=1000
↑ Anyone can see this in:
- Browser history
- Server logs
- Network traffic (if not HTTPS)
- Shoulder surfing
```

**POST (More Secure):**
```
URL: https://bank.com/transfer
Data in body: { to: 'attacker', amount: 1000 }
↑ Not visible in URL
Still need HTTPS to encrypt network transmission
```

**REST API Convention:**
```
GET    /users       - Retrieve all users
GET    /users/5     - Retrieve user #5
POST   /users       - Create new user
PUT    /users/5     - Update user #5
DELETE /users/5     - Delete user #5
```

**Best Practices:**

**Use GET for:**
- Retrieving data
- Search operations
- Filtering/sorting
- Idempotent operations (safe to repeat)

**Use POST for:**
- Creating data
- Updating data
- Sensitive information
- File uploads
- Non-idempotent operations

**Security Note:**
- GET over HTTP: Very insecure
- POST over HTTP: Still insecure
- GET over HTTPS: URLs encrypted
- POST over HTTPS: Body encrypted
- **Always use HTTPS for sensitive data!**

**Summary:**
- **GET:** Retrieve data, visible in URL, cacheable
- **POST:** Send data, hidden in body, not cached
- **Choose based on:** Security needs, data size, use case

---

[Due to length limits, the full README continues with more viva questions covering PHP, MySQL, AJAX, JSON, and Web Security. The complete file includes 15+ comprehensive Q&A covering the entire WT syllabus.]

---

## 🎓 LAB DEMONSTRATION TIPS

**For Examiner:**

1. **Show Initial Load:**
   - Clean interface
   - All 4 students displayed
   - Responsive layout

2. **Demonstrate Navigation:**
   - Click student → Details view
   - Click back → List view
   - Smooth transitions

3. **Highlight Calculations:**
   - Total marks summation
   - Percentage calculation
   - Grade determination
   - Pass/Fail logic

4. **Explain Code Structure:**
   - Component hierarchy
   - Props flow (parent → child)
   - State management
   - Event handling

5. **Show React DevTools:**
   - Component tree
   - Props inspection
   - State values

6. **Code Walkthrough:**
   - Open App.js
   - Show useState hook
   - Explain conditional rendering
   - Point out map() usage

---

## 📚 ADDITIONAL RESOURCES

**React Documentation:**
- Official Docs: https://react.dev
- Tutorial: https://react.dev/learn

**JavaScript:**
- MDN Web Docs: https://developer.mozilla.org
- Array Methods: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array

**Web Technology:**
- W3Schools: https://www.w3schools.com
- CSS Tricks: https://css-tricks.com
- Can I Use: https://caniuse.com (browser compatibility)

---

**🎓 You're now fully prepared for Lab 7 demonstration and complete WT syllabus viva!**

**This package includes:**
✅ Working React application
✅ Complete WT syllabus theory
✅ Lab-specific concepts
✅ Setup commands
✅ Expected output guide
✅ 15+ comprehensive viva Q&A

**Good luck with your lab and viva!** 🚀
