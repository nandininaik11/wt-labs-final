/**
 * server.js  –  Main Express Application Entry Point
 *
 * Express.js is a minimal, flexible Node.js web framework.
 *
 * Core Concepts:
 * ─────────────
 * 1. express()      → creates the application object
 * 2. Middleware     → functions that process req before it reaches the route handler
 * 3. Router         → organises routes into separate modules
 * 4. app.listen()   → starts the HTTP server on given port
 *
 * Middleware used:
 *   express.json()       → parses incoming JSON request bodies (req.body)
 *   express.urlencoded() → parses HTML form data (application/x-www-form-urlencoded)
 *   express.static()     → serves static files (HTML, CSS, JS) from /public folder
 *   cors()               → enables Cross-Origin Resource Sharing (for API access)
 *   morgan()             → HTTP request logger (shows method, URL, status, time)
 *
 * Request-Response cycle:
 *   Browser → HTTP Request → Middleware chain → Route handler → Response → Browser
 */

const express = require('express');
const cors    = require('cors');
const morgan  = require('morgan');
const path    = require('path');

// Import our database (initialises DB + creates table on first run)
const db = require('./config/database');

// Import student routes
const studentRoutes = require('./routes/students');

// ─── Create Express App ───────────────────────────────────────────────────────
const app  = express();
const PORT = process.env.PORT || 3000;

// ─── Middleware Stack ─────────────────────────────────────────────────────────
/**
 * Middleware = functions that run between request arrival and response sending.
 * app.use(fn) → add middleware to the stack.
 * Each middleware receives (req, res, next) – calls next() to pass to next middleware.
 */

// 1. Morgan: logs HTTP requests to console e.g. "GET /api/students 200 5ms"
app.use(morgan('dev'));

// 2. CORS: allows the API to be called from different origins (browsers, Postman)
app.use(cors());

// 3. Body parsers: allows reading JSON and form data from req.body
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// 4. Static files: serves everything in /public folder at root URL
//    e.g. /public/index.html is served at http://localhost:3000/
app.use(express.static(path.join(__dirname, 'public')));

// ─── Routes ───────────────────────────────────────────────────────────────────
/**
 * Mount student router at /api/students.
 * All routes defined in routes/students.js will be prefixed with /api/students.
 */
app.use('/api/students', studentRoutes);

// ─── Root Route (HTML page) ───────────────────────────────────────────────────
/**
 * Task 5: Display student list in browser
 * The public/index.html file handles the UI – this just ensures
 * the root URL serves the frontend.
 */
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// ─── 404 Handler ─────────────────────────────────────────────────────────────
app.use((req, res) => {
    res.status(404).json({
        success: false,
        message: `Route ${req.method} ${req.url} not found.`
    });
});

// ─── Global Error Handler ─────────────────────────────────────────────────────
/**
 * Express error handler: 4 params (err, req, res, next) → error middleware
 */
app.use((err, req, res, next) => {
    console.error('[ERROR]', err.stack);
    res.status(500).json({
        success: false,
        message: 'Internal server error.',
        error: err.message
    });
});

// ─── Start Server ─────────────────────────────────────────────────────────────
app.listen(PORT, () => {
    console.log('');
    console.log('╔══════════════════════════════════════════════════╗');
    console.log('║   Student Registration System is RUNNING! 🚀     ║');
    console.log(`║   URL  : http://localhost:${PORT}                   ║`);
    console.log(`║   API  : http://localhost:${PORT}/api/students       ║`);
    console.log('╚══════════════════════════════════════════════════╝');
    console.log('');
});

module.exports = app;
