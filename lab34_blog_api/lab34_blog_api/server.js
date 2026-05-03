/*
  ================================================================
  LAB 34 — Blog Management REST API
  Technology Stack: Node.js + Express.js
  
  FIVE TASKS COVERED:
    Task 1: Setup Express server
    Task 2: Routes for Create / View / Edit / Delete blog posts
    Task 3: In-memory array as data store (no DB needed)
    Task 4: All responses in JSON format
    Task 5: Test with Postman (use the .postman_collection.json file)
  
  SYLLABUS → Unit VI: Node JS, Express JS, Creating Web server,
             REST API, JSON responses, NPM
  ================================================================
*/


// ================================================================
// STEP 1 — IMPORT MODULES
//
// require() is Node.js's built-in function to import modules.
// In ES6 this would be: import express from 'express';
// But Node.js traditionally uses CommonJS: require()
//
// 'express' → the npm package installed via: npm install express
// 'cors'    → allows requests from Postman / browser on any origin
//
// SYLLABUS → Unit VI: Node JS Modules, NPM
// ================================================================
const express = require('express');
const cors    = require('cors');


// ================================================================
// STEP 2 — CREATE EXPRESS APPLICATION
//
// express() returns an Application object.
// All routes and middleware are registered on this 'app' object.
//
// SYLLABUS → Unit VI: Express JS
// ================================================================
const app = express();

/*
  PORT — which network port to listen on.
  process.env.PORT → reads from environment (used in deployment).
  || 3000          → fallback for local development.
*/
const PORT = process.env.PORT || 3000;


// ================================================================
// STEP 3 — REGISTER MIDDLEWARE
//
// Middleware = functions that run on EVERY request BEFORE routes.
// app.use(fn) registers middleware globally.
// Order matters — middleware runs top to bottom.
//
// SYLLABUS → Unit VI: Express JS
// ================================================================

// express.json() — parses JSON request bodies.
// Without this, req.body is undefined in POST/PUT routes.
// Example: client sends {"title":"Hello"} → req.body.title = "Hello"
app.use(express.json());

// express.urlencoded() — parses HTML form encoded bodies.
// extended: false uses the built-in querystring library.
app.use(express.urlencoded({ extended: false }));

// cors() — sets Access-Control-Allow-Origin: * header.
// Required for Postman and browser clients to call this API.
app.use(cors());


// ================================================================
// TASK 3 — IN-MEMORY DATA STORE
//
// A plain JavaScript Array acting as our "database".
// Pre-loaded with 3 sample posts for the examiner to see.
//
// Each blog post object has:
//   id        : unique number (auto-incremented)
//   title     : string
//   content   : string (post body)
//   author    : string
//   category  : string
//   createdAt : ISO date string
//   updatedAt : ISO date string
//
// LIMITATION: Data resets on server restart (in-memory).
// For persistence, connect MongoDB or MySQL (see README).
//
// SYLLABUS → Unit I: JSON, Unit II: Objects in JS, Arrays
// ================================================================
let blogs = [
  {
    id:        1,
    title:     'Getting Started with Node.js',
    content:   'Node.js is a JavaScript runtime built on Chrome\'s V8 engine. It lets you run JavaScript on the server-side. This post covers installation, modules, and building your first HTTP server.',
    author:    'Rahul Sharma',
    category:  'Technology',
    createdAt: new Date('2025-01-10').toISOString(),
    updatedAt: new Date('2025-01-10').toISOString(),
  },
  {
    id:        2,
    title:     'Understanding REST APIs',
    content:   'REST stands for Representational State Transfer. It is an architectural style using HTTP methods: GET (read), POST (create), PUT (update), DELETE (remove). REST APIs return JSON responses.',
    author:    'Priya Patel',
    category:  'Web Development',
    createdAt: new Date('2025-01-15').toISOString(),
    updatedAt: new Date('2025-01-15').toISOString(),
  },
  {
    id:        3,
    title:     'Express.js — The Node.js Framework',
    content:   'Express.js is a minimal, flexible Node.js web framework. It simplifies building REST APIs by providing easy routing, middleware support, and request/response helpers.',
    author:    'Amit Kumar',
    category:  'Technology',
    createdAt: new Date('2025-01-20').toISOString(),
    updatedAt: new Date('2025-01-20').toISOString(),
  },
];

// Auto-increment counter for assigning unique IDs
let nextId = 4;   // starts at 4 since sample data uses 1, 2, 3


// ================================================================
// HELPER FUNCTIONS
// ================================================================

/*
  findById(id) — searches blogs array for a post with matching id.
  Returns the post object, or undefined if not found.
  parseInt() converts the URL param string "1" to the number 1.
*/
function findById(id) {
  return blogs.find(b => b.id === parseInt(id));
}

/*
  validate(data) — checks required fields.
  Returns an array of error strings (empty = no errors).
*/
function validate(data) {
  const errors = [];
  if (!data.title   || !data.title.trim())   errors.push('title is required');
  if (!data.content || !data.content.trim()) errors.push('content is required');
  if (!data.author  || !data.author.trim())  errors.push('author is required');
  return errors;
}


// ================================================================
// TASK 2 — REST API ROUTES
//
// Route syntax: app.METHOD(PATH, HANDLER)
//   METHOD  — HTTP verb (get, post, put, delete)
//   PATH    — URL string, e.g. '/api/posts' or '/api/posts/:id'
//   HANDLER — function(req, res) { ... }
//
// HTTP Methods for CRUD:
//   POST   → Create   (C)
//   GET    → Read     (R)
//   PUT    → Update   (U)
//   DELETE → Delete   (D)
//
// SYLLABUS → Unit VI: Express JS, REST API
//            Unit I:  JSON (Task 4 — all responses are JSON)
// ================================================================


// ----------------------------------------------------------------
// ROOT — GET /
// API welcome message and endpoint listing.
// ----------------------------------------------------------------
app.get('/', function(req, res) {
  /*
    res.json(object) does two things:
      1. Sets Content-Type: application/json header
      2. Sends the object serialized as a JSON string
    HTTP status 200 (OK) is the default.
    SYLLABUS → Unit I: JSON
  */
  res.json({
    message:  '🚀 Blog Management REST API — Lab 34',
    version:  '1.0.0',
    endpoints: {
      'GET    /api/posts':      'Get all blog posts',
      'GET    /api/posts/:id':  'Get one blog post by ID',
      'POST   /api/posts':      'Create a new blog post',
      'PUT    /api/posts/:id':  'Update a blog post',
      'DELETE /api/posts/:id':  'Delete a blog post',
      'GET    /api/stats':      'Blog statistics',
    },
    note: 'Import lab34_blog_api.postman_collection.json in Postman to test all routes',
  });
});


// ----------------------------------------------------------------
// ROUTE 1 — GET /api/posts
// READ ALL blog posts.
// Supports optional query parameters for filtering + sorting.
// HTTP GET: safe (read-only), idempotent (same result every call).
// ----------------------------------------------------------------
app.get('/api/posts', function(req, res) {

  /*
    req.query — URL query string parameters.
    URL: GET /api/posts?author=Rahul&category=Technology&sort=oldest
    req.query.author   → "Rahul"
    req.query.category → "Technology"
    req.query.sort     → "oldest"
  */
  const { author, category, sort } = req.query;

  // Start with a copy of all blogs (spread prevents mutating original)
  let result = [...blogs];

  // Filter by author (partial, case-insensitive match)
  if (author) {
    result = result.filter(b =>
      b.author.toLowerCase().includes(author.toLowerCase())
    );
  }

  // Filter by category (exact, case-insensitive)
  if (category) {
    result = result.filter(b =>
      b.category.toLowerCase() === category.toLowerCase()
    );
  }

  // Sort: newest first by default, or oldest first if ?sort=oldest
  if (sort === 'oldest') {
    result.sort((a, b) => new Date(a.createdAt) - new Date(b.createdAt));
  } else {
    result.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
  }

  // TASK 4: Return JSON response
  res.json({
    success: true,
    count:   result.length,
    total:   blogs.length,
    data:    result,
  });
});


// ----------------------------------------------------------------
// ROUTE 2 — GET /api/posts/:id
// READ ONE blog post by numeric ID.
// :id is a URL route parameter captured in req.params.id.
// ----------------------------------------------------------------
app.get('/api/posts/:id', function(req, res) {

  /*
    req.params — route parameters from the URL pattern.
    Route: /api/posts/:id
    URL:   /api/posts/2
    req.params.id → "2"  (always a string — parseInt() to compare)
  */
  const blog = findById(req.params.id);

  if (!blog) {
    /*
      res.status(code) — sets HTTP status code.
      Chaining: .status(404).json({...})
      
      HTTP 404 Not Found — resource doesn't exist at this URL.
      
      Common status codes:
        200 OK              — success (default)
        201 Created         — new resource created (POST success)
        400 Bad Request     — invalid input from client
        404 Not Found       — resource not found
        500 Internal Error  — server-side error
    */
    return res.status(404).json({
      success: false,
      message: `Blog post with ID ${req.params.id} not found`,
    });
  }

  res.json({
    success: true,
    data:    blog,
  });
});


// ----------------------------------------------------------------
// ROUTE 3 — POST /api/posts
// CREATE a new blog post.
// Client sends JSON body with title, content, author, category.
// Returns 201 Created with the new post.
// ----------------------------------------------------------------
app.post('/api/posts', function(req, res) {

  /*
    req.body — parsed request body (requires express.json() middleware).
    
    Client request (in Postman):
      Method: POST
      URL: http://localhost:3000/api/posts
      Headers: Content-Type: application/json
      Body (raw JSON):
      {
        "title":    "My New Post",
        "content":  "Post body here...",
        "author":   "Rahul Sharma",
        "category": "Technology"
      }
    
    req.body.title   → "My New Post"
    req.body.content → "Post body here..."
  */
  const { title, content, author, category } = req.body;

  // Server-side validation
  const errors = validate({ title, content, author });
  if (errors.length > 0) {
    // HTTP 400 Bad Request — client sent invalid data
    return res.status(400).json({
      success: false,
      message: 'Validation failed',
      errors:  errors,
    });
  }

  // Build the new blog post object
  const newPost = {
    id:        nextId++,                           // unique ID, increment counter
    title:     title.trim(),
    content:   content.trim(),
    author:    author.trim(),
    category:  category ? category.trim() : 'General',
    createdAt: new Date().toISOString(),           // current timestamp
    updatedAt: new Date().toISOString(),
  };

  // TASK 3: Save to in-memory array
  blogs.push(newPost);

  // TASK 4: HTTP 201 Created + JSON response
  res.status(201).json({
    success: true,
    message: 'Blog post created successfully',
    data:    newPost,
  });
});


// ----------------------------------------------------------------
// ROUTE 4 — PUT /api/posts/:id
// UPDATE an existing blog post (full replace).
// Client sends the complete updated fields in request body.
// ----------------------------------------------------------------
app.put('/api/posts/:id', function(req, res) {

  // Find existing post
  const blog = findById(req.params.id);

  if (!blog) {
    return res.status(404).json({
      success: false,
      message: `Blog post with ID ${req.params.id} not found`,
    });
  }

  const { title, content, author, category } = req.body;

  // Validate required fields
  const errors = validate({ title, content, author });
  if (errors.length > 0) {
    return res.status(400).json({
      success: false,
      message: 'Validation failed',
      errors:  errors,
    });
  }

  // Find position in array and update
  const idx = blogs.findIndex(b => b.id === parseInt(req.params.id));

  /*
    Spread operator (...blog) copies all existing properties.
    Then we override with new values.
    This creates a NEW object — does not mutate the old one.
    
    SYLLABUS → Unit II: Objects in JS
  */
  blogs[idx] = {
    ...blog,                                // keep id, createdAt
    title:     title.trim(),
    content:   content.trim(),
    author:    author.trim(),
    category:  category ? category.trim() : blog.category,
    updatedAt: new Date().toISOString(),   // update timestamp
  };

  res.json({
    success: true,
    message: 'Blog post updated successfully',
    data:    blogs[idx],
  });
});


// ----------------------------------------------------------------
// ROUTE 5 — DELETE /api/posts/:id
// DELETE a blog post by ID.
// Returns the deleted post so client can confirm what was removed.
// ----------------------------------------------------------------
app.delete('/api/posts/:id', function(req, res) {

  const blog = findById(req.params.id);

  if (!blog) {
    return res.status(404).json({
      success: false,
      message: `Blog post with ID ${req.params.id} not found`,
    });
  }

  /*
    Array.filter() returns a NEW array excluding the deleted item.
    We reassign the blogs variable to this new array.
    
    SYLLABUS → Unit II: Arrays, Control Structures
  */
  blogs = blogs.filter(b => b.id !== parseInt(req.params.id));

  res.json({
    success: true,
    message: `Blog post "${blog.title}" deleted successfully`,
    data:    blog,   // return the deleted post for confirmation
  });
});


// ----------------------------------------------------------------
// BONUS — GET /api/posts/search?q=keyword
// Search title, content, and author fields.
// NOTE: Must be defined BEFORE /api/posts/:id to avoid conflict.
// ----------------------------------------------------------------
app.get('/api/posts/search', function(req, res) {
  const q = (req.query.q || '').toLowerCase().trim();

  if (!q) {
    return res.status(400).json({
      success: false,
      message: 'Provide a search term: /api/posts/search?q=your-keyword',
    });
  }

  const results = blogs.filter(b =>
    b.title.toLowerCase().includes(q)   ||
    b.content.toLowerCase().includes(q) ||
    b.author.toLowerCase().includes(q)
  );

  res.json({ success: true, query: q, count: results.length, data: results });
});


// ----------------------------------------------------------------
// BONUS — GET /api/stats
// Returns aggregate statistics about the blog store.
// ----------------------------------------------------------------
app.get('/api/stats', function(req, res) {
  const byCategory = blogs.reduce((acc, b) => {
    acc[b.category] = (acc[b.category] || 0) + 1;
    return acc;
  }, {});

  const byAuthor = blogs.reduce((acc, b) => {
    acc[b.author] = (acc[b.author] || 0) + 1;
    return acc;
  }, {});

  res.json({
    success:    true,
    totalPosts: blogs.length,
    byCategory,
    byAuthor,
    latestPost: blogs.length
      ? blogs.reduce((a, b) => new Date(a.createdAt) > new Date(b.createdAt) ? a : b)
      : null,
  });
});


// ----------------------------------------------------------------
// 404 HANDLER — catches any URL not matched above
// Must be placed AFTER all valid routes.
// ----------------------------------------------------------------
app.use(function(req, res) {
  res.status(404).json({
    success: false,
    message: `Route ${req.method} ${req.originalUrl} does not exist`,
    hint:    'Visit GET http://localhost:3000/ for available endpoints',
  });
});


// ----------------------------------------------------------------
// GLOBAL ERROR HANDLER
// Express calls this middleware when next(error) is called or
// a route throws. The 4-parameter signature is required.
// ----------------------------------------------------------------
app.use(function(err, req, res, next) {
  console.error('Unhandled error:', err.stack);
  res.status(500).json({
    success: false,
    message: 'Internal Server Error',
    error:   err.message,
  });
});


// ================================================================
// TASK 1 — START THE SERVER
//
// app.listen(PORT, callback)
//   → Binds to PORT and starts accepting HTTP connections.
//   → callback runs once the server is ready.
//
// SYLLABUS → Unit VI: Creating Web server with Node.js
// ================================================================
app.listen(PORT, function() {
  console.log('\n╔══════════════════════════════════════════════╗');
  console.log('║   🚀  Blog API Server — Lab 34               ║');
  console.log('╠══════════════════════════════════════════════╣');
  console.log(`║   URL  : http://localhost:${PORT}               ║`);
  console.log('║   Stop : Ctrl + C                            ║');
  console.log('╚══════════════════════════════════════════════╝');
  console.log('\n📋 Endpoints:');
  console.log(`  GET    http://localhost:${PORT}/api/posts`);
  console.log(`  GET    http://localhost:${PORT}/api/posts/1`);
  console.log(`  POST   http://localhost:${PORT}/api/posts`);
  console.log(`  PUT    http://localhost:${PORT}/api/posts/1`);
  console.log(`  DELETE http://localhost:${PORT}/api/posts/1`);
  console.log(`  GET    http://localhost:${PORT}/api/stats`);
  console.log(`\n📦 ${blogs.length} sample posts loaded in memory\n`);
});

module.exports = app;
