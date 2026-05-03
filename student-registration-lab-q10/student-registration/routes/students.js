/**
 * routes/students.js  –  Student API Routes (Tasks 3, 4, 5)
 *
 * Express Router organises API endpoints for /api/students
 *
 * REST API Design:
 *   POST   /api/students          → Insert new student    (Task 3)
 *   GET    /api/students          → Get all students      (Task 4)
 *   GET    /api/students/:id      → Get student by ID
 *   DELETE /api/students/:id      → Delete a student
 *   GET    /api/students/search   → Search by name/course
 *
 * HTTP Methods:
 *   GET    → retrieve data   (safe, idempotent)
 *   POST   → create data     (not idempotent)
 *   PUT    → update data     (idempotent)
 *   DELETE → delete data     (idempotent)
 */

const express = require('express');
const router = express.Router();
const db = require('../config/database');

// ─── Helper: Validate Student Fields ─────────────────────────────────────────
function validateStudent({ name, email, course }) {
    const errors = [];

    if (!name || name.trim().length < 2)
        errors.push('Name must be at least 2 characters.');

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email || !emailRegex.test(email))
        errors.push('A valid email address is required.');

    if (!course || course.trim().length < 2)
        errors.push('Course must be at least 2 characters.');

    return errors;
}

// ─────────────────────────────────────────────────────────────────────────────
//  TASK 3: INSERT New Student Record
//  POST /api/students
// ─────────────────────────────────────────────────────────────────────────────
/**
 * Accepts JSON body: { name, email, course }
 * Validates input → inserts into SQLite → returns saved record
 *
 * Prepared Statement:
 *   db.prepare(sql) – pre-compiles the SQL for performance & prevents SQL injection
 *   stmt.run(name, email, course) – executes with bound parameters
 *   result.lastInsertRowid – auto-generated id of the new row
 */
router.post('/', (req, res) => {
    try {
        const { name, email, course } = req.body;

        // Validate input
        const errors = validateStudent({ name, email, course });
        if (errors.length > 0) {
            return res.status(400).json({
                success: false,
                message: 'Validation failed',
                errors
            });
        }

        // Prepared statement – ? placeholders prevent SQL injection
        const stmt = db.prepare(`
            INSERT INTO students (name, email, course)
            VALUES (?, ?, ?)
        `);

        const result = stmt.run(
            name.trim(),
            email.trim().toLowerCase(),
            course.trim()
        );

        // Fetch the newly inserted record
        const newStudent = db.prepare('SELECT * FROM students WHERE id = ?')
                             .get(result.lastInsertRowid);

        console.log(`[INSERT] New student registered: ${name} (id: ${result.lastInsertRowid})`);

        return res.status(201).json({
            success: true,
            message: 'Student registered successfully!',
            data: newStudent
        });

    } catch (err) {
        // UNIQUE constraint error → email already exists
        if (err.message.includes('UNIQUE constraint failed')) {
            return res.status(409).json({
                success: false,
                message: 'A student with this email already exists.'
            });
        }
        console.error('[POST /students] Error:', err.message);
        return res.status(500).json({ success: false, message: 'Server error.' });
    }
});

// ─────────────────────────────────────────────────────────────────────────────
//  TASK 4: RETRIEVE All Student Records
//  GET /api/students
// ─────────────────────────────────────────────────────────────────────────────
/**
 * Returns all students from the database ordered by registration date.
 *
 * Optional query params:
 *   ?course=Computer Science  → filter by course
 *   ?search=aarav             → search by name or email
 *   ?sort=name                → sort by field (id, name, course, created_at)
 *   ?order=asc|desc           → sort order
 */
router.get('/', (req, res) => {
    try {
        const { course, search, sort = 'id', order = 'asc' } = req.query;

        // Whitelist valid sort columns to prevent SQL injection via query params
        const validSortCols = ['id', 'name', 'email', 'course', 'created_at'];
        const sortCol = validSortCols.includes(sort) ? sort : 'id';
        const sortOrder = order.toLowerCase() === 'desc' ? 'DESC' : 'ASC';

        let sql = 'SELECT * FROM students';
        const params = [];

        if (course && search) {
            sql += ' WHERE course LIKE ? AND (name LIKE ? OR email LIKE ?)';
            params.push(`%${course}%`, `%${search}%`, `%${search}%`);
        } else if (course) {
            sql += ' WHERE course LIKE ?';
            params.push(`%${course}%`);
        } else if (search) {
            sql += ' WHERE name LIKE ? OR email LIKE ? OR course LIKE ?';
            params.push(`%${search}%`, `%${search}%`, `%${search}%`);
        }

        sql += ` ORDER BY ${sortCol} ${sortOrder}`;

        const students = db.prepare(sql).all(...params);

        console.log(`[SELECT] Fetched ${students.length} students.`);

        return res.status(200).json({
            success: true,
            count: students.length,
            data: students
        });

    } catch (err) {
        console.error('[GET /students] Error:', err.message);
        return res.status(500).json({ success: false, message: 'Server error.' });
    }
});

// ─────────────────────────────────────────────────────────────────────────────
//  GET /api/students/stats  –  Course-wise Statistics
// ─────────────────────────────────────────────────────────────────────────────
router.get('/stats', (req, res) => {
    try {
        const total  = db.prepare('SELECT COUNT(*) as total FROM students').get().total;
        const byCourseSql = `
            SELECT course, COUNT(*) as count
            FROM students
            GROUP BY course
            ORDER BY count DESC
        `;
        const byCourse = db.prepare(byCourseSql).all();

        return res.status(200).json({
            success: true,
            data: { totalStudents: total, byCourse }
        });
    } catch (err) {
        return res.status(500).json({ success: false, message: err.message });
    }
});

// ─────────────────────────────────────────────────────────────────────────────
//  GET /api/students/:id  –  Get Single Student by ID
// ─────────────────────────────────────────────────────────────────────────────
router.get('/:id', (req, res) => {
    try {
        const { id } = req.params;
        const student = db.prepare('SELECT * FROM students WHERE id = ?').get(id);

        if (!student) {
            return res.status(404).json({
                success: false,
                message: `Student with id ${id} not found.`
            });
        }

        return res.status(200).json({ success: true, data: student });

    } catch (err) {
        return res.status(500).json({ success: false, message: err.message });
    }
});

// ─────────────────────────────────────────────────────────────────────────────
//  PUT /api/students/:id  –  Update Student
// ─────────────────────────────────────────────────────────────────────────────
router.put('/:id', (req, res) => {
    try {
        const { id } = req.params;
        const { name, email, course } = req.body;

        // Check if student exists
        const existing = db.prepare('SELECT * FROM students WHERE id = ?').get(id);
        if (!existing) {
            return res.status(404).json({ success: false, message: `Student with id ${id} not found.` });
        }

        const errors = validateStudent({ name, email, course });
        if (errors.length > 0) {
            return res.status(400).json({ success: false, message: 'Validation failed', errors });
        }

        db.prepare(`
            UPDATE students SET name = ?, email = ?, course = ? WHERE id = ?
        `).run(name.trim(), email.trim().toLowerCase(), course.trim(), id);

        const updated = db.prepare('SELECT * FROM students WHERE id = ?').get(id);

        return res.status(200).json({
            success: true,
            message: 'Student updated successfully.',
            data: updated
        });

    } catch (err) {
        if (err.message.includes('UNIQUE constraint failed')) {
            return res.status(409).json({ success: false, message: 'Email already in use.' });
        }
        return res.status(500).json({ success: false, message: err.message });
    }
});

// ─────────────────────────────────────────────────────────────────────────────
//  DELETE /api/students/:id  –  Delete Student
// ─────────────────────────────────────────────────────────────────────────────
router.delete('/:id', (req, res) => {
    try {
        const { id } = req.params;
        const existing = db.prepare('SELECT * FROM students WHERE id = ?').get(id);

        if (!existing) {
            return res.status(404).json({ success: false, message: `Student with id ${id} not found.` });
        }

        db.prepare('DELETE FROM students WHERE id = ?').run(id);

        return res.status(200).json({
            success: true,
            message: `Student '${existing.name}' deleted successfully.`
        });

    } catch (err) {
        return res.status(500).json({ success: false, message: err.message });
    }
});

module.exports = router;
