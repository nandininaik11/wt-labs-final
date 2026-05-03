// ============================================================
// db.js — MySQL Database Connection
// WT Syllabus Unit VI: Node.js — Database Connectivity
//
// mysql2 is a Node.js package (npm module) that lets us talk
// to a MySQL database from Node.js code.
// ============================================================

// require() = Node.js way to import a module (like import in Java)
// 'mysql2/promise' gives us the async/await (Promise) version
const mysql = require('mysql2/promise');

// createPool() creates a CONNECTION POOL
// Pool = a set of reusable DB connections (more efficient than
// opening/closing a new connection for every request)
const pool = mysql.createPool({
    host:     'localhost', // MySQL server address
    user:     'root',      // MySQL username (XAMPP default)
    password: 'WJ28@krhps',          // MySQL password (XAMPP default = empty)
    database: 'library_db',// our database name
    waitForConnections: true, // wait if all connections are busy
    connectionLimit:    10,   // max 10 simultaneous connections
    queueLimit:         0     // 0 = unlimited queue
});

// module.exports = what this file exposes to other files
// Other files do: const db = require('./db')  → they get pool
module.exports = pool;
