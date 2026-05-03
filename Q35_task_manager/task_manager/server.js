// ============================================================
// server.js — Task Manager REST API
// WT Syllabus Unit VI: Node.js, Express.js, REST API
//
// REST API = Representational State Transfer
// Uses HTTP methods to perform CRUD operations:
//   POST   → Create  (add task)
//   GET    → Read    (get tasks)
//   PATCH  → Update  (change status)
//   DELETE → Delete  (remove task)
//
// All responses are in JSON format (Task 5)
// ============================================================

// ── require() = Node.js module import system ──
const express = require('express'); // web framework for Node.js
const cors    = require('cors');    // allow browser requests from other origins
const { v4: uuidv4 } = require('uuid'); // generates unique IDs like "a1b2-c3d4-..."
const path    = require('path');    // built-in: handles file paths

// ── Create the Express application ──
const app  = express();
const PORT = 3000;

// ============================================================
// IN-MEMORY DATA STORE
// No database needed — tasks stored in a JavaScript array.
// Data resets when server restarts (fine for lab demo).
// In production: use MongoDB, MySQL, etc.
// ============================================================

// Tasks array — each task is a JavaScript object
// Array: ordered list of objects  [ {}, {}, {} ]
let tasks = [
  {
    id:          uuidv4(),               // unique identifier (UUID)
    title:       'Complete WT Lab Assignment',
    description: 'Finish all pending lab questions',
    status:      'pending',              // 'pending' | 'completed'
    priority:    'high',                 // 'low' | 'medium' | 'high'
    category:    'Study',
    createdAt:   new Date().toISOString(), // ISO 8601 format: "2025-01-01T10:00:00.000Z"
    updatedAt:   new Date().toISOString()
  },
  {
    id:          uuidv4(),
    title:       'Buy groceries',
    description: 'Milk, bread, eggs, vegetables',
    status:      'pending',
    priority:    'medium',
    category:    'Personal',
    createdAt:   new Date(Date.now() - 86400000).toISOString(), // yesterday
    updatedAt:   new Date(Date.now() - 86400000).toISOString()
  },
  {
    id:          uuidv4(),
    title:       'Study React Hooks',
    description: 'Learn useState, useEffect, useCallback',
    status:      'completed',
    priority:    'high',
    category:    'Study',
    createdAt:   new Date(Date.now() - 172800000).toISOString(), // 2 days ago
    updatedAt:   new Date().toISOString()
  },
  {
    id:          uuidv4(),
    title:       'Morning exercise',
    description: '30 minutes jogging in the park',
    status:      'completed',
    priority:    'low',
    category:    'Health',
    createdAt:   new Date(Date.now() - 3600000).toISOString(), // 1 hour ago
    updatedAt:   new Date().toISOString()
  },
  {
    id:          uuidv4(),
    title:       'Read Node.js documentation',
    description: 'Focus on Express.js and REST API concepts',
    status:      'pending',
    priority:    'medium',
    category:    'Study',
    createdAt:   new Date().toISOString(),
    updatedAt:   new Date().toISOString()
  }
];

// ============================================================
// MIDDLEWARE (runs before every route handler)
// Middleware = functions that process request → response pipeline
// app.use(fn) registers global middleware
// ============================================================

// cors(): Cross-Origin Resource Sharing
// Without this: browser blocks requests from http://localhost:3000
// to a different origin (security policy called Same-Origin Policy)
app.use(cors());

// express.json(): parses incoming JSON request bodies
// Without this: req.body is undefined for POST/PATCH requests
// Adds Content-Type: application/json parsing
app.use(express.json());

// express.urlencoded(): parses HTML form data (key=value pairs)
// extended:true allows nested objects in form data
app.use(express.urlencoded({ extended: true }));

// express.static(): serves all files inside /public folder
// index.html → http://localhost:3000/
// style.css   → http://localhost:3000/style.css
app.use(express.static(path.join(__dirname, 'public')));

// ── Custom logger middleware ──
// This runs for EVERY request before route handlers
// next() passes control to the next middleware/route
app.use((req, res, next) => {
  // new Date().toLocaleTimeString() = "10:30:45 AM"
  console.log(`[${new Date().toLocaleTimeString()}] ${req.method} ${req.url}`);
  next(); // IMPORTANT: must call next() or request hangs!
});

// ============================================================
// HELPER: VALIDATION FUNCTION
// Returns an array of error messages (empty = valid)
// ============================================================
function validateTask(body) {
  const errors = [];

  // Check required fields
  if (!body.title || typeof body.title !== 'string') {
    errors.push('Title is required and must be a string.');
  } else if (body.title.trim().length < 2) {
    errors.push('Title must be at least 2 characters.');
  } else if (body.title.trim().length > 200) {
    errors.push('Title must not exceed 200 characters.');
  }

  // Validate priority if provided
  const validPriorities = ['low', 'medium', 'high'];
  if (body.priority && !validPriorities.includes(body.priority)) {
    errors.push(`Priority must be one of: ${validPriorities.join(', ')}.`);
  }

  return errors; // empty array = no errors = valid
}

// ============================================================
// API ROUTES
// Route syntax: app.METHOD(PATH, HANDLER_FUNCTION)
// req = request object  (what browser sent to us)
// res = response object (what we send back)
// ============================================================

// ── ROUTE 1: GET /api/tasks — Task 2: Retrieve all tasks ──
// GET is for READING data — no body, data in URL query params
app.get('/api/tasks', (req, res) => {
  // req.query = URL query parameters
  // /api/tasks?status=pending → req.query.status = "pending"
  // /api/tasks?priority=high  → req.query.priority = "high"
  // /api/tasks?search=buy     → req.query.search = "buy"
  const { status, priority, category, search } = req.query;

  // Start with all tasks (spread to avoid mutating original array)
  let filtered = [...tasks]; // spread operator: creates a shallow copy

  // Apply filters if query params exist
  // Array.filter() returns new array with only elements that pass the test
  if (status)   filtered = filtered.filter(t => t.status === status);
  if (priority) filtered = filtered.filter(t => t.priority === priority);
  if (category) filtered = filtered.filter(t => t.category === category);

  // Search in title or description (case-insensitive)
  // String.toLowerCase() + String.includes() for case-insensitive search
  if (search) {
    const term = search.toLowerCase();
    filtered = filtered.filter(t =>
      t.title.toLowerCase().includes(term) ||
      t.description.toLowerCase().includes(term)
    );
  }

  // Sort: incomplete tasks first, then by createdAt newest-first
  // Array.sort() with compare function: negative = a before b, positive = b before a
  filtered.sort((a, b) => {
    if (a.status !== b.status) {
      return a.status === 'pending' ? -1 : 1; // pending first
    }
    return new Date(b.createdAt) - new Date(a.createdAt); // newest first
  });

  // Calculate summary statistics
  const stats = {
    total:     tasks.length,
    pending:   tasks.filter(t => t.status === 'pending').length,
    completed: tasks.filter(t => t.status === 'completed').length,
    high:      tasks.filter(t => t.priority === 'high').length
  };

  // Task 5: Return data in JSON format
  // res.json() sends HTTP 200 response with JSON body
  // Sets Content-Type: application/json header automatically
  res.json({
    success: true,              // boolean flag — easy to check in frontend
    count:   filtered.length,  // number of tasks in this response
    stats:   stats,            // summary numbers
    tasks:   filtered          // the array of task objects
  });
});

// ── ROUTE 2: GET /api/tasks/:id — Get ONE task by ID ──
// :id is a URL parameter (dynamic segment)
// /api/tasks/abc-123 → req.params.id = "abc-123"
app.get('/api/tasks/:id', (req, res) => {
  const { id } = req.params; // destructure from params object

  // Array.find() returns first element matching the condition, or undefined
  const task = tasks.find(t => t.id === id);

  if (!task) {
    // HTTP 404 = Not Found (client asked for something that doesn't exist)
    return res.status(404).json({
      success: false,
      message: `Task with ID "${id}" not found.`
    });
  }

  // HTTP 200 = OK (default, but being explicit is good practice)
  res.status(200).json({ success: true, task });
  // shorthand property: { task } = { task: task }
});

// ── ROUTE 3: POST /api/tasks — Task 1: Add a new task ──
// POST is for CREATING new resources
// Data comes in req.body (parsed by express.json() middleware)
app.post('/api/tasks', (req, res) => {
  // Destructure expected fields from request body
  const { title, description, priority, category } = req.body;

  // Validate input
  const errors = validateTask(req.body);
  if (errors.length > 0) {
    // HTTP 400 = Bad Request (client sent invalid data)
    return res.status(400).json({
      success: false,
      message: 'Validation failed.',
      errors               // shorthand: { errors: errors }
    });
  }

  // Create new task object
  const newTask = {
    id:          uuidv4(),           // generate unique ID
    title:       title.trim(),       // trim() removes leading/trailing spaces
    description: description ? description.trim() : '', // optional field
    status:      'pending',          // all new tasks start as pending
    priority:    priority || 'medium', // default to medium if not provided
    category:    category  || 'General',
    createdAt:   new Date().toISOString(), // current timestamp in ISO format
    updatedAt:   new Date().toISOString()
  };

  // Add to our in-memory array
  // Array.push() adds element to end of array — mutates the original array
  tasks.push(newTask);

  console.log(`✅ Task created: "${newTask.title}" [ID: ${newTask.id}]`);

  // HTTP 201 = Created (resource successfully created)
  res.status(201).json({
    success: true,
    message: 'Task created successfully!',
    task:    newTask
  });
});

// ── ROUTE 4: PATCH /api/tasks/:id — Task 3: Update task status ──
// PATCH = partial update (only update specified fields)
// PUT   = full replacement (replace entire resource)
// We use PATCH because we only update specific fields
app.patch('/api/tasks/:id', (req, res) => {
  const { id } = req.params;

  // Array.findIndex() returns INDEX of matching element, or -1 if not found
  const index = tasks.findIndex(t => t.id === id);

  if (index === -1) {
    return res.status(404).json({
      success: false,
      message: `Task with ID "${id}" not found.`
    });
  }

  // Extract updatable fields from request body
  const { status, title, description, priority, category } = req.body;

  // Validate status if being updated
  const validStatuses = ['pending', 'completed'];
  if (status && !validStatuses.includes(status)) {
    return res.status(400).json({
      success: false,
      message: `Status must be one of: ${validStatuses.join(', ')}.`
    });
  }

  // Validate title if being updated
  if (title !== undefined) {
    const errors = validateTask({ title, ...req.body });
    if (errors.length > 0) {
      return res.status(400).json({ success: false, errors });
    }
  }

  // Update only the fields that were provided in the request
  // Spread operator: { ...tasks[index] } copies all existing fields
  // Then we override specific ones
  tasks[index] = {
    ...tasks[index],                    // keep all existing fields
    // Conditional updates: only change if new value provided
    ...(status      && { status }),
    ...(title       && { title: title.trim() }),
    ...(description !== undefined && { description: description.trim() }),
    ...(priority    && { priority }),
    ...(category    && { category }),
    updatedAt: new Date().toISOString() // always update timestamp
  };

  console.log(`✏️  Task updated: ID ${id} → status: ${tasks[index].status}`);

  res.json({
    success: true,
    message: 'Task updated successfully!',
    task:    tasks[index]
  });
});

// ── ROUTE 5: DELETE /api/tasks/:id — Task 4: Delete a task ──
// DELETE removes a resource permanently
app.delete('/api/tasks/:id', (req, res) => {
  const { id } = req.params;

  const index = tasks.findIndex(t => t.id === id);

  if (index === -1) {
    return res.status(404).json({
      success: false,
      message: `Task with ID "${id}" not found.`
    });
  }

  // Save task before deleting (to include in response)
  const deletedTask = tasks[index];

  // Array.splice(startIndex, deleteCount) removes elements from array
  // splice(2, 1) removes 1 element at index 2
  tasks.splice(index, 1);

  console.log(`🗑️  Task deleted: "${deletedTask.title}" [ID: ${id}]`);

  // HTTP 200 (some APIs use 204 No Content for DELETE, but we return data)
  res.json({
    success: true,
    message: 'Task deleted successfully.',
    deletedTask
  });
});

// ── ROUTE 6: DELETE /api/tasks/completed/all — Delete all completed tasks ──
// Bulk operation: remove all tasks with status='completed'
// NOTE: Must be defined BEFORE /api/tasks/:id or "completed" gets treated as an ID
app.delete('/api/tasks/completed/all', (req, res) => {
  const completedCount = tasks.filter(t => t.status === 'completed').length;

  // Array.filter() creates NEW array with only non-completed tasks
  // This effectively removes all completed tasks
  tasks = tasks.filter(t => t.status !== 'completed');

  res.json({
    success: true,
    message: `Deleted ${completedCount} completed task(s).`,
    remaining: tasks.length
  });
});

// ── ROUTE 7: GET /api/stats — Dashboard statistics ──
app.get('/api/stats', (req, res) => {
  // Count tasks by different groupings
  // Array.reduce() builds a single value from array elements
  const byCategory = tasks.reduce((acc, task) => {
    // acc = accumulator object, task = current element
    acc[task.category] = (acc[task.category] || 0) + 1;
    return acc;
  }, {}); // {} = initial value of accumulator

  const byPriority = tasks.reduce((acc, task) => {
    acc[task.priority] = (acc[task.priority] || 0) + 1;
    return acc;
  }, {});

  res.json({
    success: true,
    stats: {
      total:          tasks.length,
      pending:        tasks.filter(t => t.status === 'pending').length,
      completed:      tasks.filter(t => t.status === 'completed').length,
      completionRate: tasks.length
                      ? Math.round((tasks.filter(t=>t.status==='completed').length / tasks.length) * 100)
                      : 0,
      byCategory,
      byPriority,
      highPriority:   tasks.filter(t => t.priority === 'high' && t.status === 'pending').length
    }
  });
});

// ── 404 Handler for unknown API routes ──
// This middleware runs if no route above matched
app.use('/api/*', (req, res) => {
  res.status(404).json({
    success: false,
    message: `API endpoint not found: ${req.method} ${req.url}`,
    availableRoutes: [
      'GET    /api/tasks',
      'GET    /api/tasks/:id',
      'POST   /api/tasks',
      'PATCH  /api/tasks/:id',
      'DELETE /api/tasks/:id',
      'DELETE /api/tasks/completed/all',
      'GET    /api/stats'
    ]
  });
});

// ── Serve frontend for all other routes ──
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// ── Global Error Handler ──
// 4-parameter middleware = error handler (Express detects this by arity)
// Called when next(error) is called in any route
app.use((err, req, res, next) => {
  console.error('Server error:', err.message);
  res.status(500).json({
    success: false,
    message: 'Internal server error.',
    error:   err.message
  });
});

// ============================================================
// START SERVER
// app.listen(PORT, callback) — starts HTTP server on given port
// ============================================================
app.listen(PORT, () => {
  console.log(`
╔═══════════════════════════════════════════════════╗
║       📋 Task Manager REST API — Running           ║
╠═══════════════════════════════════════════════════╣
║  Frontend:  http://localhost:${PORT}                   ║
║  API Base:  http://localhost:${PORT}/api               ║
╠═══════════════════════════════════════════════════╣
║  ENDPOINTS:                                       ║
║  GET    /api/tasks          → All tasks           ║
║  GET    /api/tasks/:id      → One task            ║
║  POST   /api/tasks          → Create task         ║
║  PATCH  /api/tasks/:id      → Update task         ║
║  DELETE /api/tasks/:id      → Delete task         ║
║  DELETE /api/tasks/completed/all → Bulk delete    ║
║  GET    /api/stats          → Statistics          ║
╚═══════════════════════════════════════════════════╝
  `);
});
