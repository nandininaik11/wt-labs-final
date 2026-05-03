// ============================================================
// server.js — Main Node.js Server (Entry Point)
// WT Syllabus Unit VI: Node.js, Express.js, REST API
//
// Express.js is a Node.js FRAMEWORK for building web servers.
// It makes creating routes (URLs) and handling HTTP requests easy.
// ============================================================

// ── Import Modules ──
// Node.js modules: built-in (path), npm packages (express, mysql2, cors)
const express = require('express'); // web framework
const cors    = require('cors');    // Cross-Origin Resource Sharing
const path    = require('path');    // built-in Node module for file paths
const db      = require('./db');    // our MySQL connection pool

// ── Create Express App ──
// express() returns an application object — our web server
const app  = express();
const PORT = 3000; // port number our server listens on

// ============================================================
// MIDDLEWARE
// Middleware = functions that run BEFORE route handlers
// They process the request, then pass it along with next()
// ============================================================

// cors() — allows the frontend (browser) to call this API
// Without CORS, browsers block cross-origin requests for security
app.use(cors());

// express.json() — parses incoming JSON request bodies
// Without this, req.body would be undefined for POST requests
app.use(express.json());

// express.urlencoded() — parses HTML form data (key=value pairs)
app.use(express.urlencoded({ extended: true }));

// express.static() — serves files from 'public' folder directly
// Any file in /public/ is accessible at the root URL
// e.g. /public/index.html → http://localhost:3000/index.html
app.use(express.static(path.join(__dirname, 'public')));

// ============================================================
// DATABASE INITIALISATION
// Creates the 'books' table if it doesn't exist yet
// and inserts sample data on first run
// ============================================================
async function initDatabase() {
    try {
        // CREATE TABLE IF NOT EXISTS — safe to run multiple times
        await db.execute(`
            CREATE TABLE IF NOT EXISTS books (
                book_id   INT AUTO_INCREMENT PRIMARY KEY,
                title     VARCHAR(200) NOT NULL,
                author    VARCHAR(150) NOT NULL,
                year      INT          NOT NULL,
                genre     VARCHAR(100) DEFAULT 'General',
                copies    INT          DEFAULT 1,
                added_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
            )
        `);
        // AUTO_INCREMENT = MySQL auto-generates unique IDs (1, 2, 3, ...)
        // VARCHAR(200) = variable-length string up to 200 chars
        // TIMESTAMP DEFAULT CURRENT_TIMESTAMP = auto-sets date when row inserted

        // Check if table already has data (avoid duplicate inserts)
        const [rows] = await db.execute('SELECT COUNT(*) AS cnt FROM books');
        // Destructuring: [rows] = await ... extracts first element of array
        // db.execute returns [rows, fields] — we only need rows

        if (rows[0].cnt === 0) {
            // Insert sample books only on first run
            await db.execute(`
                INSERT INTO books (title, author, year, genre, copies) VALUES
                ('The Great Gatsby',           'F. Scott Fitzgerald', 1925, 'Fiction',     3),
                ('To Kill a Mockingbird',      'Harper Lee',          1960, 'Fiction',     5),
                ('1984',                       'George Orwell',       1949, 'Dystopian',   4),
                ('A Brief History of Time',    'Stephen Hawking',     1988, 'Science',     2),
                ('Clean Code',                 'Robert C. Martin',    2008, 'Technology',  3),
                ('The Alchemist',              'Paulo Coelho',        1988, 'Fiction',     6),
                ('Sapiens',                    'Yuval Noah Harari',   2011, 'History',     4),
                ('Introduction to Algorithms', 'Cormen et al.',       2009, 'Technology',  2),
                ('Pride and Prejudice',        'Jane Austen',         1813, 'Classic',     3),
                ('Wings of Fire',              'A.P.J. Abdul Kalam',  1999, 'Biography',   7)
            `);
            console.log('✅ Sample books inserted into database.');
        }

        console.log('✅ Database initialized. Table "books" ready.');
    } catch (err) {
        // Error handling: if DB init fails, log and exit
        console.error('❌ Database init error:', err.message);
        process.exit(1); // exit with error code 1
    }
}

// ============================================================
// API ROUTES — REST API Endpoints
// REST = Representational State Transfer
// Each URL + HTTP method = one operation (CRUD)
//
// C = Create  → POST   /api/books
// R = Read    → GET    /api/books  or  GET /api/books/:id
// U = Update  → PUT    /api/books/:id
// D = Delete  → DELETE /api/books/:id
// ============================================================

// ── GET /api/books — Retrieve ALL books ──
// Called when browser/frontend wants to display all books
app.get('/api/books', async (req, res) => {
    try {
        // req = request object (contains URL, headers, body, query params)
        // res = response object (used to send data back to client)

        // Optional search/filter via query string: /api/books?search=orwell
        const search = req.query.search || ''; // req.query = URL ?param=value
        const genre  = req.query.genre  || '';

        // Build SQL dynamically based on filters
        let sql    = 'SELECT * FROM books WHERE 1=1'; // 1=1 is always true (placeholder)
        let params = [];

        if (search) {
            // LIKE '%term%' = contains term anywhere in the field
            // We search both title and author
            sql += ' AND (title LIKE ? OR author LIKE ?)';
            params.push(`%${search}%`, `%${search}%`); // push adds to array end
        }
        if (genre) {
            sql += ' AND genre = ?';
            params.push(genre);
        }

        sql += ' ORDER BY added_at DESC'; // newest books first

        // db.execute() runs the SQL query
        // Returns [rows, fields] — destructure to get rows array
        const [rows] = await db.execute(sql, params);

        // res.json() sends data as JSON response with Content-Type: application/json
        // HTTP status 200 = OK (default)
        res.json({
            success: true,
            count:   rows.length, // number of books returned
            books:   rows         // the actual data array
        });

    } catch (err) {
        console.error('GET /api/books error:', err);
        // HTTP status 500 = Internal Server Error
        res.status(500).json({ success: false, message: err.message });
    }
});

// ── GET /api/books/:id — Get ONE book by ID ──
// :id is a URL parameter (dynamic segment)
// /api/books/3 → req.params.id = "3"
app.get('/api/books/:id', async (req, res) => {
    try {
        const bookId = parseInt(req.params.id); // convert string "3" to number 3

        if (isNaN(bookId)) {
            // HTTP 400 = Bad Request (client sent invalid data)
            return res.status(400).json({ success: false, message: 'Invalid book ID' });
        }

        const [rows] = await db.execute('SELECT * FROM books WHERE book_id = ?', [bookId]);

        if (rows.length === 0) {
            // HTTP 404 = Not Found
            return res.status(404).json({ success: false, message: 'Book not found' });
        }

        res.json({ success: true, book: rows[0] }); // rows[0] = first (only) result

    } catch (err) {
        res.status(500).json({ success: false, message: err.message });
    }
});

// ── POST /api/books — Add a NEW book ──
// HTTP POST = creating a new resource
// Data comes in req.body (parsed by express.json() middleware)
app.post('/api/books', async (req, res) => {
    try {
        // Destructuring: extract named properties from req.body object
        const { title, author, year, genre, copies } = req.body;

        // ── Server-side Validation ──
        // Always validate on server — client-side JS can be bypassed
        if (!title || !author || !year) {
            return res.status(400).json({
                success: false,
                message: 'Title, author, and year are required.'
            });
        }

        const yearNum = parseInt(year);
        if (isNaN(yearNum) || yearNum < 1000 || yearNum > new Date().getFullYear()) {
            return res.status(400).json({
                success: false,
                message: `Year must be between 1000 and ${new Date().getFullYear()}.`
            });
        }

        // Prepared statement: ? placeholders prevent SQL Injection
        const [result] = await db.execute(
            'INSERT INTO books (title, author, year, genre, copies) VALUES (?, ?, ?, ?, ?)',
            [title.trim(), author.trim(), yearNum, genre || 'General', copies || 1]
        );
        // result.insertId = the auto-generated book_id of the new row

        // Fetch the newly created book to return it
        const [newBook] = await db.execute('SELECT * FROM books WHERE book_id = ?', [result.insertId]);

        // HTTP 201 = Created (resource successfully created)
        res.status(201).json({
            success: true,
            message: 'Book added successfully!',
            book:    newBook[0]
        });

    } catch (err) {
        res.status(500).json({ success: false, message: err.message });
    }
});

// ── DELETE /api/books/:id — Delete a book ──
app.delete('/api/books/:id', async (req, res) => {
    try {
        const bookId = parseInt(req.params.id);

        const [result] = await db.execute('DELETE FROM books WHERE book_id = ?', [bookId]);

        if (result.affectedRows === 0) {
            // affectedRows = how many rows were changed; 0 means ID didn't exist
            return res.status(404).json({ success: false, message: 'Book not found' });
        }

        res.json({ success: true, message: 'Book deleted successfully.' });

    } catch (err) {
        res.status(500).json({ success: false, message: err.message });
    }
});

// ── GET /api/stats — Dashboard stats ──
app.get('/api/stats', async (req, res) => {
    try {
        // Multiple queries using Promise.all() — run them in PARALLEL (faster)
        // Promise.all([p1, p2, p3]) waits for ALL promises to resolve
        const [
            [totalRows],      // total book titles
            [copiesRows],     // total copies
            [genreRows],      // books per genre
            [recentRows]      // 5 most recently added
        ] = await Promise.all([
            db.execute('SELECT COUNT(*) AS total FROM books'),
            db.execute('SELECT SUM(copies) AS total_copies FROM books'),
            db.execute('SELECT genre, COUNT(*) AS count FROM books GROUP BY genre ORDER BY count DESC'),
            db.execute('SELECT * FROM books ORDER BY added_at DESC LIMIT 5')
        ]);

        res.json({
            success:       true,
            totalTitles:   totalRows[0].total,
            totalCopies:   copiesRows[0].total_copies,
            byGenre:       genreRows,
            recentlyAdded: recentRows
        });

    } catch (err) {
        res.status(500).json({ success: false, message: err.message });
    }
});

// ── Catch-all: serve index.html for any unknown route ──
// This makes the frontend handle routing for non-API routes
app.get('*', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// ============================================================
// START SERVER
// Initialize DB first, then start listening for HTTP requests
// ============================================================
initDatabase().then(() => {
    // app.listen(PORT, callback) — start server on given port
    app.listen(PORT, () => {
        console.log(`
╔══════════════════════════════════════════╗
║  📚 Library App Server Running           ║
║  URL:  http://localhost:${PORT}              ║
║  API:  http://localhost:${PORT}/api/books    ║
╚══════════════════════════════════════════╝
        `);
    });
});
