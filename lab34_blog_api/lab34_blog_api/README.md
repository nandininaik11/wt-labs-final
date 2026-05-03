# Step 1: Reinstall packages
npm install

# Step 2: Run
npm start

# 📘 Lab Q34 — Blog Management REST API (Express.js)
## Web Technology — Complete Notes

---

## ⚙️ SETUP COMMANDS (Do This First)

### Step 1 — Open project in VS Code
```
Extract ZIP → Open VS Code → File → Open Folder → select lab34_blog_api
```

### Step 2 — Open Terminal in VS Code
```
Press Ctrl + ` (backtick) to open integrated terminal
```

### Step 3 — Install dependencies
```bash
npm install
```
This reads package.json and installs express + cors + nodemon into node_modules/.

### Step 4 — Start the server
```bash
npm start
```
OR for auto-restart on file changes:
```bash
npm run dev
```

### Step 5 — Verify it works
Open browser → visit: **http://localhost:3000/**
You should see JSON with API endpoints listed.

### Step 6 — Import Postman collection
1. Open Postman
2. Click **Import** → **Upload Files**
3. Select `lab34_blog_api.postman_collection.json`
4. All 10 requests appear in the left sidebar

---

## 📁 File Structure

```
lab34_blog_api/
│
├── server.js                              ← Main Express app (all routes + logic)
├── package.json                           ← Project metadata + npm dependencies
├── lab34_blog_api.postman_collection.json ← Import into Postman for testing
└── README.md                              ← This file
```

### What's inside node_modules/ (after npm install):
```
node_modules/
├── express/     ← Express.js framework
├── cors/        ← CORS middleware
└── nodemon/     ← Dev server auto-restarter
```

---

## 🖥️ Expected Output — Show the Examiner

### Terminal after `npm start`:
```
╔══════════════════════════════════════════════╗
║   🚀  Blog API Server — Lab 34               ║
╠══════════════════════════════════════════════╣
║   URL  : http://localhost:3000               ║
║   Stop : Ctrl + C                            ║
╚══════════════════════════════════════════════╝

📋 Endpoints:
  GET    http://localhost:3000/api/posts
  GET    http://localhost:3000/api/posts/1
  POST   http://localhost:3000/api/posts
  PUT    http://localhost:3000/api/posts/1
  DELETE http://localhost:3000/api/posts/1
  GET    http://localhost:3000/api/stats

📦 3 sample posts loaded in memory
```

---

### Postman Demo Sequence (show each one):

#### 1. GET /api/posts — Get All
```
Method: GET
URL:    http://localhost:3000/api/posts
```
**Expected Response (Status 200 OK):**
```json
{
  "success": true,
  "count": 3,
  "total": 3,
  "data": [
    {
      "id": 3,
      "title": "Express.js — The Node.js Framework",
      "content": "Express.js is a minimal...",
      "author": "Amit Kumar",
      "category": "Technology",
      "createdAt": "2025-01-20T00:00:00.000Z",
      "updatedAt": "2025-01-20T00:00:00.000Z"
    },
    { ... },
    { ... }
  ]
}
```

---

#### 2. GET /api/posts/1 — Get Single
```
Method: GET
URL:    http://localhost:3000/api/posts/1
```
**Expected Response (Status 200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Getting Started with Node.js",
    ...
  }
}
```

---

#### 3. POST /api/posts — Create New Post
```
Method:  POST
URL:     http://localhost:3000/api/posts
Headers: Content-Type: application/json
Body (raw JSON):
{
  "title":    "My Express Blog Post",
  "content":  "This is the content of my new blog post.",
  "author":   "Rahul Sharma",
  "category": "Technology"
}
```
**Expected Response (Status 201 Created):**
```json
{
  "success": true,
  "message": "Blog post created successfully",
  "data": {
    "id": 4,
    "title": "My Express Blog Post",
    "author": "Rahul Sharma",
    "createdAt": "2025-01-30T10:30:00.000Z",
    ...
  }
}
```

---

#### 4. PUT /api/posts/1 — Update Post
```
Method:  PUT
URL:     http://localhost:3000/api/posts/1
Headers: Content-Type: application/json
Body (raw JSON):
{
  "title":    "Node.js — UPDATED TITLE",
  "content":  "Updated content here...",
  "author":   "Rahul Sharma",
  "category": "Technology"
}
```
**Expected Response (Status 200 OK):**
```json
{
  "success": true,
  "message": "Blog post updated successfully",
  "data": { "id": 1, "title": "Node.js — UPDATED TITLE", "updatedAt": "..." }
}
```

---

#### 5. DELETE /api/posts/3 — Delete Post
```
Method: DELETE
URL:    http://localhost:3000/api/posts/3
```
**Expected Response (Status 200 OK):**
```json
{
  "success": true,
  "message": "Blog post \"Express.js — The Node.js Framework\" deleted successfully",
  "data": { "id": 3, ... }
}
```
Now GET /api/posts shows only 2 posts.

---

#### 6. Error Testing — 404 Not Found
```
Method: GET
URL:    http://localhost:3000/api/posts/999
```
**Expected Response (Status 404):**
```json
{
  "success": false,
  "message": "Blog post with ID 999 not found"
}
```

---

#### 7. Error Testing — 400 Validation Error
```
Method:  POST
URL:     http://localhost:3000/api/posts
Body:    { "title": "", "content": "" }
```
**Expected Response (Status 400):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": ["title is required", "content is required", "author is required"]
}
```

---

## 📖 THEORY — WT Syllabus Mapped

### 1. What is Node.js? (Unit-VI)
Node.js is a **JavaScript runtime** built on Google Chrome's V8 JavaScript engine. It allows JavaScript to run outside the browser — on a server. Key features:
- **Event-driven**: doesn't wait for slow operations (file I/O, network)
- **Non-blocking I/O**: handles thousands of requests simultaneously
- **NPM**: Node Package Manager — world's largest software registry
- **Single-threaded**: uses an event loop for concurrency (not multiple threads)

---

### 2. What is Express.js? (Unit-VI)
Express is a **minimal, flexible Node.js web framework** that simplifies:
- Setting up an HTTP server
- Defining routes (URL → function mapping)
- Processing request/response objects
- Adding middleware (authentication, logging, parsing)

```javascript
const express = require('express');   // import
const app     = express();            // create app
app.get('/hello', (req, res) => {     // define route
  res.json({ message: 'Hello!' });
});
app.listen(3000);                     // start server
```

---

### 3. What is a REST API? (Unit-VI)
**REST** = Representational State Transfer. An architectural style for APIs.

| HTTP Method | CRUD Operation | Express Route          |
|---|---|---|
| GET         | Read           | app.get('/posts', ...)         |
| POST        | Create         | app.post('/posts', ...)        |
| PUT         | Update         | app.put('/posts/:id', ...)     |
| DELETE      | Delete         | app.delete('/posts/:id', ...) |

**REST Principles:**
1. **Stateless** — each request contains all information needed
2. **Uniform interface** — consistent URL patterns
3. **Resource-based** — URLs identify resources (/posts, /users)
4. **JSON** — data exchanged as JSON (most common)

---

### 4. JSON (Unit-I: JSON)
JSON = JavaScript Object Notation. Lightweight data exchange format.
```json
{
  "id":       1,
  "title":    "Hello World",
  "author":   "Rahul",
  "tags":     ["nodejs", "express"],
  "published": true
}
```
`res.json(object)` in Express automatically:
- Serializes JS object to JSON string
- Sets `Content-Type: application/json` header
- Sends the response

---

### 5. Middleware (Unit-VI: Express JS)
Middleware = functions that run between receiving a request and sending a response.

```javascript
// Middleware signature: function(req, res, next)
// next() passes control to the next middleware/route
app.use(express.json());       // parses JSON body → req.body
app.use(cors());               // adds CORS headers
app.use(myCustomMiddleware);   // your own middleware
```

Middleware chain: Request → MW1 → MW2 → Route Handler → Response

---

### 6. req and res Objects (Unit-VI)

| Property/Method | Purpose |
|---|---|
| `req.params.id`  | URL route parameter (/posts/:id) |
| `req.query.name` | URL query string (?name=Rahul) |
| `req.body.title` | Request body (POST/PUT data) |
| `res.json(data)` | Send JSON response |
| `res.status(404).json(...)` | Set status code + send JSON |
| `res.send('text')` | Send plain text |

---

### 7. HTTP Status Codes (Unit-VI)

| Code | Name | When to use |
|---|---|---|
| 200 | OK | Successful GET, PUT, DELETE |
| 201 | Created | Successful POST (new resource created) |
| 400 | Bad Request | Client sent invalid data (validation failed) |
| 404 | Not Found | Resource with given ID doesn't exist |
| 500 | Internal Server Error | Server-side bug/error |

---

### 8. Postman (Unit-II: Introduction to POSTMAN, Unit-IV: POSTMAN Tool)
Postman is an API testing tool.

**How to use for this lab:**
1. Open Postman → Import the `.postman_collection.json` file
2. Select a request (e.g., "POST — Create New Post")
3. Click **Send**
4. See the response in the bottom panel

**Key Postman concepts:**
- **Collection** — group of saved requests
- **Environment variables** — `{{base}}` = http://localhost:3000
- **Body → raw → JSON** — how to send POST/PUT data
- **Status code** — shown in response (200, 201, 404 etc.)
- **Response time** — how fast the server responded

---

### 9. In-memory vs Database storage (Task 3)
This lab uses an **in-memory JavaScript array**:
```javascript
let blogs = [{ id: 1, title: '...', ... }];
```
**Pros**: Simple, no setup, fast.
**Cons**: Data lost on server restart.

For persistence, you'd use:
```javascript
// MongoDB (NoSQL) with Mongoose:
const mongoose = require('mongoose');
mongoose.connect('mongodb://localhost:27017/blogdb');

// MySQL (SQL) with mysql2:
const mysql = require('mysql2');
const db = mysql.createConnection({ host: 'localhost', database: 'blogdb' });
```

---

### 10. NPM (Unit-VI: Node Package Manager)
```bash
npm init              # create package.json
npm install express   # install + add to dependencies
npm install -g nodemon # install globally

# package.json scripts:
"scripts": {
  "start": "node server.js",
  "dev":   "nodemon server.js"
}

npm start    # runs: node server.js
npm run dev  # runs: nodemon server.js
```

---

## ❓ LIKELY VIVA QUESTIONS + ANSWERS

**Q1. What is Express.js? Why use it over plain Node.js?**
A: Express is a Node.js web framework. Plain Node.js requires manually parsing URLs, query strings, and request bodies — complex and error-prone. Express provides `app.get()`, `app.post()` etc. for routing, built-in JSON parsing via `express.json()`, middleware support, and cleaner request/response APIs. It reduces boilerplate significantly.

---

**Q2. What is REST API? What are its principles?**
A: REST = Representational State Transfer. It's an architectural style for web services using HTTP. Principles: (1) Stateless — no session state stored on server. (2) Uniform interface — consistent URL patterns. (3) Resource-based — URLs represent resources (/posts, /users). (4) HTTP methods define operations: GET=read, POST=create, PUT=update, DELETE=remove. (5) JSON for data exchange.

---

**Q3. Explain the five HTTP methods used in REST.**
A: GET — retrieves data, read-only, safe and idempotent. POST — creates a new resource, NOT idempotent (repeated calls create multiple resources). PUT — updates/replaces a resource, idempotent. PATCH — partial update of a resource. DELETE — removes a resource, idempotent.

---

**Q4. What is middleware in Express? Give examples.**
A: Middleware is a function with signature `(req, res, next)` that runs before route handlers. `next()` passes control to the next function. Examples: `express.json()` parses JSON bodies, `cors()` adds CORS headers, logging middleware records requests. You can write custom middleware for authentication, validation, etc.

---

**Q5. What is req.params vs req.query vs req.body?**
A: `req.params` — captures dynamic segments from the URL pattern. Route `/posts/:id` + URL `/posts/5` → `req.params.id = "5"`. `req.query` — URL query string parameters. `/posts?author=Rahul` → `req.query.author = "Rahul"`. `req.body` — request body sent in POST/PUT. Requires `express.json()` middleware. Contains the JSON data the client sent.

---

**Q6. What is `require()` in Node.js?**
A: `require()` is Node.js's built-in function for importing modules. `require('express')` imports the express npm package. `require('./utils')` imports a local file. It implements the CommonJS module system. In modern ES6 you'd use `import express from 'express'`, but Node.js traditionally uses `require()`.

---

**Q7. What is NPM and what does `npm install` do?**
A: NPM = Node Package Manager. It's the default package manager for Node.js and the world's largest software registry with 2M+ packages. `npm install` reads `package.json`, downloads all listed dependencies from the NPM registry, and saves them to the `node_modules/` folder. `npm install express` downloads Express and adds it to `package.json` dependencies.

---

**Q8. What are HTTP status codes? Name important ones.**
A: Status codes are 3-digit numbers in HTTP responses indicating the result: 200 OK (success), 201 Created (POST success — new resource made), 400 Bad Request (invalid input), 401 Unauthorized (authentication required), 403 Forbidden (access denied), 404 Not Found (resource doesn't exist), 500 Internal Server Error (server-side bug). First digit indicates category: 2xx=success, 4xx=client error, 5xx=server error.

---

**Q9. How does Postman work? How do you test a POST request?**
A: Postman is an API testing tool. To test POST: (1) Set method to POST. (2) Enter URL e.g. http://localhost:3000/api/posts. (3) Go to Body tab → select raw → JSON. (4) Type the JSON body: `{"title":"Test","content":"Body","author":"Me"}`. (5) Add header `Content-Type: application/json`. (6) Click Send. (7) See response status code and JSON body in the bottom panel.

---

**Q10. What is JSON? How is it different from XML?**
A: JSON = JavaScript Object Notation. A lightweight text format for data exchange. Uses key-value pairs `{"key":"value"}`, arrays `[1,2,3]`, and nested objects. XML uses opening/closing tags `<title>text</title>`. JSON is simpler, smaller, and natively parsed by JavaScript with `JSON.parse()` and `JSON.stringify()`. REST APIs almost universally use JSON because of its simplicity and JS compatibility.

---

**Q11. Why is `let blogs = [...]` used instead of `const`?**
A: `let` is used because we reassign the entire `blogs` variable in the DELETE route: `blogs = blogs.filter(...)`. `filter()` returns a NEW array, so we need to reassign the variable. `const` would prevent reassignment and throw an error. If we used methods like `push()` or `splice()` that mutate in-place, `const` would work fine.

---

**Q12. What is CORS and why is it needed?**
A: CORS = Cross-Origin Resource Sharing. Browsers block requests from one origin (e.g., localhost:5500) to a different origin (localhost:3000) for security. `cors()` middleware adds `Access-Control-Allow-Origin: *` header to responses, telling the browser to allow cross-origin requests. Without it, browser-based clients (React apps, Postman's browser version) would be blocked — though the desktop Postman app bypasses this.

---

*Prepared for WT Lab Q34 — Blog Management REST API with Express.js*
