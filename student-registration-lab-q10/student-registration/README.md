# Lab Q10 – Student Registration System
## Node.js + Express + SQLite | Complete Guide

---

## 📁 PROJECT FILE STRUCTURE

```
student-registration/
├── server.js                                  ← Express app entry point
├── package.json                               ← npm project config + dependencies
├── students.db                                ← SQLite database (auto-created on first run)
├── StudentRegistration_Postman_Collection.json ← Import into Postman for testing
├── README.md                                  ← This file
├── config/
│   └── database.js                            ← Task 1: DB connection + Task 2: Table creation
├── routes/
│   └── students.js                            ← Tasks 3, 4, 5: All API routes
└── public/
    └── index.html                             ← Task 5: Browser UI (form + student list)
```

---

## ⚙️ SETUP AND RUN COMMANDS

### Prerequisites
- Node.js v18+ installed → download from https://nodejs.org

### Step 1 – Install Dependencies
```bash
# Navigate to project folder
cd student-registration

# Install all packages listed in package.json
npm install
```
This creates a `node_modules/` folder and installs:
- `express` – web framework
- `better-sqlite3` – SQLite database driver
- `cors` – Cross-Origin Resource Sharing
- `morgan` – HTTP request logger
- `nodemon` (dev) – auto-restarts server on file changes

### Step 2 – Start the Server
```bash
# Production (normal start)
npm start

# Development (auto-restart on code changes)
npm run dev
```

### Step 3 – Open in Browser
```
http://localhost:3000
```
You will see the Student Registration form + student list table.

### Step 4 – Test with Postman
1. Open Postman
2. Click **Import** → Select `StudentRegistration_Postman_Collection.json`
3. Run requests 1 → 13 in order
4. Request #1 auto-saves `{{studentId}}` for later requests

---

## 🖥️ REST API ENDPOINTS

| # | Method | Endpoint | Description | Status |
|---|--------|----------|-------------|--------|
| 1 | POST | /api/students | Register new student | 201 |
| 2 | GET | /api/students | Get all students | 200 |
| 3 | GET | /api/students/:id | Get student by ID | 200/404 |
| 4 | GET | /api/students?search=name | Search students | 200 |
| 5 | GET | /api/students?course=CS | Filter by course | 200 |
| 6 | GET | /api/students?sort=name&order=asc | Sort results | 200 |
| 7 | GET | /api/students/stats | Course-wise statistics | 200 |
| 8 | PUT | /api/students/:id | Update student | 200/404 |
| 9 | DELETE | /api/students/:id | Delete student | 200/404 |

---

## 🖥️ EXPECTED OUTPUT

### POST /api/students – 201 Created
```json
{
  "success": true,
  "message": "Student registered successfully!",
  "data": {
    "id": 4,
    "name": "Arjun Patel",
    "email": "arjun.patel@example.com",
    "course": "Computer Science",
    "created_at": "2024-04-10 10:30:00"
  }
}
```

### GET /api/students – 200 OK
```json
{
  "success": true,
  "count": 4,
  "data": [
    { "id": 1, "name": "Aarav Sharma", "email": "aarav@example.com", "course": "Computer Science", "created_at": "..." },
    { "id": 2, "name": "Priya Mehta",  "email": "priya@example.com",  "course": "Information Technology", "created_at": "..." },
    { "id": 3, "name": "Rohan Joshi",  "email": "rohan@example.com",  "course": "Data Science", "created_at": "..." },
    { "id": 4, "name": "Arjun Patel",  "email": "arjun.patel@example.com", "course": "Computer Science", "created_at": "..." }
  ]
}
```

### Validation Error – 400 Bad Request
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": [
    "Name must be at least 2 characters.",
    "A valid email address is required.",
    "Course must be at least 2 characters."
  ]
}
```

### Duplicate Email – 409 Conflict
```json
{
  "success": false,
  "message": "A student with this email already exists."
}
```

### Browser View (http://localhost:3000)
- Stats bar showing total students and courses count
- Registration form on the left (Name, Email, Course dropdown)
- Student table on the right with search and filter
- Toast notification on successful registration
- Delete button per student row

---

## 📖 THEORY – WT SYLLABUS UNIT VI: NODE.JS

---

### 1. Introduction to Node.js
Node.js is a **JavaScript runtime environment** built on Chrome's V8 JavaScript engine.

Key characteristics:
- **Server-side JavaScript**: Runs JS outside the browser (on the server)
- **Asynchronous / Non-blocking I/O**: Does not wait for file/DB/network operations
- **Event-driven**: Uses an Event Loop to handle concurrent requests
- **Single-threaded**: One thread serves many requests (no thread-per-request overhead)

Traditional server vs Node.js:
| Traditional (PHP, Java) | Node.js |
|------------------------|---------|
| Blocking I/O | Non-blocking I/O |
| New thread per request | Event loop handles all |
| Heavy memory use | Lightweight |
| Different language for frontend & backend | JavaScript everywhere |

### 2. Installation of Node.js
```bash
# Check if installed:
node --version      # e.g. v20.11.0
npm --version       # e.g. 10.2.4

# Install Node.js from: https://nodejs.org
# LTS version recommended for production
```

### 3. Node.js Modules
Node.js uses CommonJS module system.

**Three types of modules:**

a) **Core/Built-in Modules** (no install needed):
```js
const http = require('http');     // create HTTP server
const fs   = require('fs');       // file system operations
const path = require('path');     // file path utilities
const os   = require('os');       // operating system info
```

b) **Third-party Modules** (installed via npm):
```js
const express = require('express');   // web framework
const cors    = require('cors');      // CORS middleware
```

c) **Custom Modules** (your own files):
```js
// In database.js:
module.exports = db;              // export

// In server.js:
const db = require('./config/database');   // import
```

### 4. Node Package Manager (NPM)
NPM is the package manager for Node.js – manages dependencies.

Key commands:
| Command | Description |
|---------|-------------|
| `npm init` | Create package.json |
| `npm install express` | Install a package |
| `npm install` | Install all packages in package.json |
| `npm install -D nodemon` | Install as dev dependency |
| `npm start` | Run the "start" script |
| `npm run dev` | Run the "dev" script |
| `npm list` | List installed packages |
| `npm uninstall express` | Remove a package |

**package.json** – project configuration file:
```json
{
  "name": "student-registration",
  "version": "1.0.0",
  "scripts": {
    "start": "node server.js",
    "dev": "nodemon server.js"
  },
  "dependencies": {
    "express": "^4.18.3",
    "better-sqlite3": "^9.4.3"
  }
}
```

### 5. Creating a Web Server in Node.js (without Express)
```js
const http = require('http');

const server = http.createServer((req, res) => {
    res.writeHead(200, { 'Content-Type': 'text/plain' });
    res.end('Hello, World!');
});

server.listen(3000, () => {
    console.log('Server running on port 3000');
});
```

### 6. Express.js
Express is a **minimal, flexible web framework** for Node.js.

Why Express over raw http module?
- Routing: handle different URLs easily
- Middleware: chainable request processors
- JSON parsing, static files, CORS built-in
- Huge ecosystem of plugins

**Basic Express app:**
```js
const express = require('express');
const app = express();

app.use(express.json());          // parse JSON bodies

app.get('/hello', (req, res) => {
    res.json({ message: 'Hello World' });
});

app.listen(3000);
```

**Request Object (req):**
- `req.body` – request body (JSON/form data)
- `req.params` – URL parameters: `/students/:id` → `req.params.id`
- `req.query` – query string: `?name=Aarav` → `req.query.name`
- `req.method` – HTTP method (GET, POST, etc.)
- `req.headers` – HTTP headers

**Response Object (res):**
- `res.json(data)` – send JSON response
- `res.status(201).json(data)` – set status + send JSON
- `res.send(text)` – send text response
- `res.sendFile(path)` – send a file
- `res.redirect(url)` – redirect to another URL

### 7. Middleware in Express
Middleware = functions that have access to `req`, `res`, and `next`.
They run in a chain before reaching the route handler.

```js
// Custom middleware (logging example)
app.use((req, res, next) => {
    console.log(`${req.method} ${req.url}`);
    next();   // call next middleware/route
});

// Built-in middleware
app.use(express.json());              // parse JSON
app.use(express.static('public'));    // serve static files

// Third-party middleware
app.use(cors());     // allow cross-origin requests
app.use(morgan('dev'));  // request logging
```

### 8. Serving Static Resources
```js
// Serves all files in /public folder
app.use(express.static(path.join(__dirname, 'public')));

// Now: public/index.html → http://localhost:3000/
// Now: public/style.css  → http://localhost:3000/style.css
```

### 9. Database Connectivity (SQLite)
We use **SQLite** – a file-based SQL database:
- No separate server process needed
- Data stored in a single `.db` file
- Full SQL support

```js
const Database = require('better-sqlite3');
const db = new Database('students.db');

// Create table
db.exec(`CREATE TABLE IF NOT EXISTS students (
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    name    TEXT NOT NULL,
    email   TEXT NOT NULL UNIQUE,
    course  TEXT NOT NULL
)`);

// INSERT (prepared statement – prevents SQL injection)
const stmt = db.prepare('INSERT INTO students (name, email, course) VALUES (?, ?, ?)');
const result = stmt.run('Aarav', 'aarav@ex.com', 'CS');
console.log(result.lastInsertRowid);  // auto-generated id

// SELECT ALL
const students = db.prepare('SELECT * FROM students').all();

// SELECT ONE
const student = db.prepare('SELECT * FROM students WHERE id = ?').get(1);

// DELETE
db.prepare('DELETE FROM students WHERE id = ?').run(1);
```

### 10. SQL Concepts (used in this lab)
```sql
-- Create table
CREATE TABLE IF NOT EXISTS students (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    name       TEXT    NOT NULL,
    email      TEXT    NOT NULL UNIQUE,
    course     TEXT    NOT NULL,
    created_at TEXT    DEFAULT (datetime('now'))
);

-- Insert
INSERT INTO students (name, email, course) VALUES ('Aarav', 'a@b.com', 'CS');

-- Select all
SELECT * FROM students;

-- Select with condition
SELECT * FROM students WHERE course = 'Computer Science';

-- Search
SELECT * FROM students WHERE name LIKE '%aarav%';

-- Update
UPDATE students SET course = 'AI' WHERE id = 1;

-- Delete
DELETE FROM students WHERE id = 1;

-- Count
SELECT COUNT(*) as total FROM students;

-- Group by
SELECT course, COUNT(*) as count FROM students GROUP BY course;
```

### 11. Express Router
Organises routes into separate modules:
```js
// routes/students.js
const router = express.Router();
router.get('/', getAllStudents);
router.post('/', createStudent);
module.exports = router;

// server.js
const studentRoutes = require('./routes/students');
app.use('/api/students', studentRoutes);
// → all routes in students.js now prefixed with /api/students
```

### 12. REST API Design
REST = Representational State Transfer
- Resources are identified by URLs (nouns, not verbs)
- HTTP methods define the operation

```
POST   /api/students       → create
GET    /api/students       → read all
GET    /api/students/:id   → read one
PUT    /api/students/:id   → update
DELETE /api/students/:id   → delete
```

### 13. JSON (JavaScript Object Notation)
- Data exchange format between client and server
- Lightweight, human-readable
- Key-value pairs: `{ "name": "Aarav", "age": 20 }`
- Arrays: `[{ "id": 1 }, { "id": 2 }]`

```js
// JS Object → JSON string
const json = JSON.stringify({ name: 'Aarav' });  // '{"name":"Aarav"}'

// JSON string → JS Object
const obj = JSON.parse('{"name":"Aarav"}');       // { name: 'Aarav' }

// Express res.json() does JSON.stringify automatically
res.json({ success: true, data: students });
```

### 14. DOM Manipulation (Client-side JS)
Our index.html uses Fetch API + DOM manipulation:
```js
// Fetch API – modern way to make HTTP requests from browser
const response = await fetch('/api/students');
const data     = await response.json();

// DOM manipulation – dynamically build HTML
const tbody = document.getElementById('table-body');
tbody.innerHTML = data.data.map(s => `
    <tr><td>${s.id}</td><td>${s.name}</td></tr>
`).join('');
```

---

## ❓ VIVA QUESTIONS + ANSWERS

### Q1: What is Node.js?
**A:** Node.js is an open-source, cross-platform **JavaScript runtime environment** built on Chrome's V8 engine. It allows JavaScript to run on the server side. Key feature: **non-blocking, event-driven I/O**, which makes it efficient for handling concurrent requests without creating multiple threads.

### Q2: What is the difference between Node.js and a browser?
**A:**
- Browser: runs JS for UI, has access to DOM/window/document
- Node.js: runs JS on server, has access to file system, network, OS. No DOM.
- Same JS language, different environments and available APIs.

### Q3: What is NPM? What is package.json?
**A:** NPM (Node Package Manager) is the default package manager for Node.js. It:
- Downloads and manages third-party packages
- `package.json` is the project manifest that lists all dependencies, scripts, and metadata
- `npm install` reads package.json and downloads all listed packages into `node_modules/`

### Q4: What is Express.js? Why do we use it?
**A:** Express is a minimal, unopinionated web framework for Node.js. We use it because:
- Simplifies routing (otherwise we'd manually parse URLs)
- Provides middleware support
- Handles JSON parsing, static file serving
- Makes REST API development much faster than raw `http` module

### Q5: What is middleware in Express?
**A:** Middleware are functions that execute in the request-response cycle. They have access to `req`, `res`, and `next`. They're used for:
- Logging (`morgan`)
- Parsing request bodies (`express.json()`)
- CORS headers (`cors()`)
- Authentication
- Error handling

`next()` must be called to pass control to the next middleware. Without it, the request hangs.

### Q6: What is the difference between require() and import?
**A:**
- `require()` → CommonJS module system (default in Node.js): `const x = require('./module')`
- `import` → ES Modules (newer standard): `import x from './module'`
- Node.js supports both, but older code uses `require`. To use `import`, set `"type": "module"` in package.json.

### Q7: What is SQLite? Why did we use it instead of MySQL?
**A:** SQLite is a self-contained, serverless, file-based SQL database. We use it because:
- **No installation** of a separate database server needed
- Entire database stored in one `.db` file
- Same SQL syntax as MySQL/PostgreSQL
- Perfect for development and small/medium apps

In production, we'd use MySQL or PostgreSQL which support multiple concurrent writers and network access.

### Q8: What is a Prepared Statement? Why use it?
**A:** A prepared statement is a pre-compiled SQL template with `?` placeholders:
```js
const stmt = db.prepare('INSERT INTO students (name, email) VALUES (?, ?)');
stmt.run('Aarav', 'aarav@ex.com');
```
Benefits:
- **Prevents SQL Injection**: user input never directly embedded in SQL string
- **Performance**: SQL is compiled once, executed many times
- **Clarity**: separates SQL logic from data

### Q9: What is CORS? Why do we need it?
**A:** CORS (Cross-Origin Resource Sharing) is a security mechanism in browsers. By default, a browser blocks AJAX requests to a different origin (different domain/port).

Example: Frontend on port 5500 calling API on port 3000 → browser blocks it.

The `cors()` middleware adds `Access-Control-Allow-Origin: *` headers to responses, telling the browser to allow such requests. This is why we added `app.use(cors())`.

### Q10: What is the Event Loop in Node.js?
**A:** The Event Loop is Node's mechanism for handling asynchronous operations:
1. JavaScript is single-threaded
2. When an async operation (file I/O, DB query, network request) starts, Node.js sends it to the background (libuv)
3. Node.js continues executing other code (doesn't block/wait)
4. When the async operation completes, its callback is placed in the Event Queue
5. Event Loop picks up callbacks from the queue and executes them

This allows Node.js to handle thousands of concurrent connections with a single thread.

### Q11: What is req.body, req.params, req.query?
**A:**
- `req.body` → data from request body (POST/PUT JSON or form data). Needs `express.json()` middleware.
- `req.params` → URL path parameters: `/students/:id` → `req.params.id`
- `req.query` → URL query string: `/students?search=aarav` → `req.query.search`

### Q12: What are HTTP status codes? Give examples.
**A:**
| Code | Meaning | When used |
|------|---------|-----------|
| 200 | OK | Successful GET/PUT |
| 201 | Created | Successful POST (resource created) |
| 400 | Bad Request | Validation failed |
| 404 | Not Found | Resource doesn't exist |
| 409 | Conflict | Duplicate email (UNIQUE constraint) |
| 500 | Internal Server Error | Unexpected server crash |

### Q13: What is async/await?
**A:** `async/await` is modern JavaScript syntax for handling Promises (asynchronous operations) in a readable, synchronous-looking way:
```js
// Without async/await (callback hell)
fetch('/api/students').then(res => res.json()).then(data => { ... });

// With async/await (clean, readable)
async function loadStudents() {
    const res  = await fetch('/api/students');
    const data = await res.json();
    // data is now available
}
```
`await` pauses execution inside the `async` function until the Promise resolves. The rest of the program (Event Loop) keeps running.

### Q14: What is the Fetch API?
**A:** Fetch API is a modern browser API for making HTTP requests from JavaScript:
```js
// GET request
const res  = await fetch('/api/students');
const data = await res.json();

// POST request
const res = await fetch('/api/students', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name: 'Aarav', email: 'a@b.com', course: 'CS' })
});
```
It replaces the older XMLHttpRequest (XHR) and is Promise-based.

### Q15: What is the difference between SQL and NoSQL?
**A:**
| SQL (SQLite, MySQL) | NoSQL (MongoDB) |
|--------------------|-----------------|
| Structured tables, fixed schema | Collections of flexible documents |
| Rows and columns | JSON-like documents |
| ACID transactions | Eventual consistency (usually) |
| JOINs for relations | Embedded documents or references |
| Vertical scaling | Horizontal scaling |

For this lab, SQLite (SQL) is used. Lab Q9 used MongoDB (NoSQL).

### Q16: What is module.exports in Node.js?
**A:** `module.exports` is how you expose a value from one Node.js file so another file can `require()` it:
```js
// database.js
const db = new Database('students.db');
module.exports = db;   // export the db connection

// server.js
const db = require('./config/database');   // import it
```
This is the CommonJS module system. It allows code reuse and separation of concerns.

---

## 🔥 DEMO STEPS FOR EXAMINER

### Step 1 – Show the Browser UI
- Open http://localhost:3000
- Point out: registration form (left) + student table (right) with sample data

### Step 2 – Register a New Student (Form)
- Fill Name, Email, Course → click Register
- Show toast notification "registered successfully"
- Table updates automatically (no page reload) → DOM manipulation via Fetch API

### Step 3 – Show in Postman
- POST /api/students → 201 Created with data
- GET /api/students → 200 OK with array
- GET /api/students/1 → single student
- PUT → update → GET again → show updated data
- DELETE → GET all → student removed

### Step 4 – Show Error Handling
- POST with empty name → 400 Bad Request with errors array
- POST duplicate email → 409 Conflict
- GET /api/students/99999 → 404 Not Found

### Step 5 – Show the Database File
- Point to `students.db` file in the project folder
- Explain: entire database stored in this one file (SQLite)
