/**
 * config/database.js  –  Database Configuration (Tasks 1 & 2)
 *
 * We use SQLite via 'better-sqlite3' package.
 *
 * WHY SQLITE?
 *   - Zero configuration – no separate database server to install
 *   - Entire database stored in a single file (students.db)
 *   - Perfect for lab/demo projects
 *   - Same SQL syntax as MySQL/PostgreSQL
 *
 * HOW better-sqlite3 WORKS:
 *   - Synchronous API (unlike mysql2 which is async/callback)
 *   - db.prepare(sql) → creates a prepared statement
 *   - stmt.run(params) → executes INSERT/UPDATE/DELETE
 *   - stmt.all(params) → executes SELECT and returns all rows
 *   - stmt.get(params) → executes SELECT and returns first row
 *
 * TASK 1: Configure database connection
 * TASK 2: Create Student table with id, name, email, course fields
 */

const Database = require('better-sqlite3');
const path = require('path');

// ─── Task 1: Configure the Database Connection ────────────────────────────────
// Database file will be created in project root as 'students.db'
const DB_PATH = path.join(__dirname, '..', 'students.db');

let db;

try {
    // Open (or create) the SQLite database file
    db = new Database(DB_PATH, {
        verbose: (msg) => console.log('[SQL]', msg)   // logs every SQL query
    });

    // Enable WAL mode for better performance
    db.pragma('journal_mode = WAL');

    console.log(`✅ Database connected: ${DB_PATH}`);
} catch (err) {
    console.error('❌ Database connection error:', err.message);
    process.exit(1);   // stop app if DB fails
}

// ─── Task 2: Create the Students Table ───────────────────────────────────────
/**
 * CREATE TABLE IF NOT EXISTS
 *   - Creates the table only if it doesn't already exist
 *   - Safe to call every time the app starts
 *
 * Fields:
 *   id      INTEGER PRIMARY KEY AUTOINCREMENT  → auto-generated unique id
 *   name    TEXT NOT NULL                      → student's full name
 *   email   TEXT NOT NULL UNIQUE               → unique email address
 *   course  TEXT NOT NULL                      → enrolled course name
 *   created_at TEXT DEFAULT (datetime('now'))  → registration timestamp
 */
const createTableSQL = `
    CREATE TABLE IF NOT EXISTS students (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        name       TEXT    NOT NULL,
        email      TEXT    NOT NULL UNIQUE,
        course     TEXT    NOT NULL,
        created_at TEXT    DEFAULT (datetime('now', 'localtime'))
    )
`;

try {
    db.exec(createTableSQL);
    console.log('✅ Students table ready.');
} catch (err) {
    console.error('❌ Error creating table:', err.message);
}

// ─── Seed Sample Data (only if table is empty) ───────────────────────────────
const count = db.prepare('SELECT COUNT(*) as cnt FROM students').get();
if (count.cnt === 0) {
    const insertSample = db.prepare(`
        INSERT INTO students (name, email, course) VALUES (?, ?, ?)
    `);
    const insertMany = db.transaction((students) => {
        for (const s of students) insertSample.run(s.name, s.email, s.course);
    });
    insertMany([
        { name: 'Aarav Sharma',   email: 'aarav@example.com',   course: 'Computer Science' },
        { name: 'Priya Mehta',    email: 'priya@example.com',    course: 'Information Technology' },
        { name: 'Rohan Joshi',    email: 'rohan@example.com',    course: 'Data Science' },
    ]);
    console.log('✅ Sample data seeded.');
}

module.exports = db;
