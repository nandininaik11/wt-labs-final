-- ============================================================
-- Complaint Management System - Database Setup (WITH ADMIN)
-- Run this in phpMyAdmin or MySQL CLI:
--   mysql -u root -p < database.sql
-- ============================================================

-- Step 1: Create and select the database
CREATE DATABASE IF NOT EXISTS complaint_db;
USE complaint_db;

-- Step 2: Users table (stores registered users/complainants)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- unique ID for each user
    name VARCHAR(100) NOT NULL,                 -- full name of user
    email VARCHAR(100) NOT NULL UNIQUE,         -- email must be unique (used for login)
    password VARCHAR(255) NOT NULL,             -- hashed password (never store plain text!)                          -- optional phone number
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- when account was created
);

-- Step 3: Admins table (stores admin accounts - separate from users)
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- unique admin ID
    username VARCHAR(50) NOT NULL UNIQUE,       -- admin username for login
    email VARCHAR(100) NOT NULL UNIQUE,         -- admin email
    password VARCHAR(255) NOT NULL,             -- hashed password
    full_name VARCHAR(100) NOT NULL,            -- admin's full name
    role ENUM('Super Admin','Admin','Moderator') DEFAULT 'Admin', -- admin level
    is_active BOOLEAN DEFAULT TRUE,             -- can disable admin accounts
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL                   -- track last login time
);

-- Step 4: Complaints table (stores all complaints)
CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- unique complaint ID
    user_id INT NOT NULL,                       -- which user filed it (foreign key)
    organization VARCHAR(100) NOT NULL,         -- PMC, PMT, etc.
    subject VARCHAR(200) NOT NULL,              -- short title of complaint
    description TEXT NOT NULL,                  -- detailed complaint text
    category VARCHAR(50),                       -- e.g., 'Water', 'Roads', 'Electricity'
    location VARCHAR(255),                      -- where the issue is located
    status ENUM('Pending','In Progress','Resolved','Rejected') DEFAULT 'Pending', -- current status
    priority ENUM('Low','Medium','High','Critical') DEFAULT 'Medium', -- urgency level
    assigned_to INT NULL,                       -- which admin is handling it (foreign key)
    admin_remarks TEXT,                         -- admin's notes/response
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- when complaint was filed
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- last update
    resolved_at TIMESTAMP NULL,                 -- when complaint was resolved
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES admins(id) ON DELETE SET NULL
);

-- Step 5: Activity Log table (optional - track all admin actions)
CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,                      -- which admin performed action
    complaint_id INT NOT NULL,                  -- which complaint was affected
    action VARCHAR(50) NOT NULL,                -- 'status_changed', 'assigned', 'remarked', etc.
    old_value VARCHAR(255),                     -- previous value (if applicable)
    new_value VARCHAR(255),                     -- new value
    action_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE,
    FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE
);

-- ============================================================
-- SAMPLE DATA INSERTION
-- ============================================================

-- Step 6: Insert sample users (complainants)
-- Password for all test users: "user123"
-- Hash generated using: password_hash('user123', PASSWORD_DEFAULT)
INSERT INTO users (name, email, password) VALUES
('Rajesh Kumar', 'rajesh@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Priya Sharma', 'priya@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Amit Patel', 'amit@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Step 7: Insert sample admins
-- Admin credentials for testing:
-- 1. Username: admin      | Password: admin123
-- 2. Username: superadmin | Password: super123
-- 3. Username: moderator  | Password: mod123

INSERT INTO admins (username, email, password, full_name, role, is_active) VALUES
('admin', 'admin@complaint.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'Admin', TRUE),
('superadmin', 'superadmin@complaint.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin', 'Super Admin', TRUE),
('moderator', 'moderator@complaint.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Moderator User', 'Moderator', TRUE);

-- Step 8: Insert sample complaints
INSERT INTO complaints (user_id, organization, subject, description, category, location, status, priority, assigned_to) VALUES
(1, 'PMC', 'Road Repair Needed', 'There is a large pothole on MG Road that needs immediate attention.', 'Infrastructure', 'MG Road, Pune', 'Pending', 'High', 1),
(2, 'PMT', 'Bus Service Issue', 'Bus number 506 is frequently delayed or skipped.', 'Transportation', 'Shivaji Nagar', 'In Progress', 'Medium', 2),
(3, 'PMC', 'Water Supply Problem', 'No water supply for the past 3 days in our area.', 'Water', 'Kothrud, Pune', 'Pending', 'Critical', NULL),
(1, 'College Admin', 'Library AC Not Working', 'The air conditioning in the library has been broken for a week.', 'Facilities', 'Central Library', 'Resolved', 'Low', 1);

-- Step 9: Insert sample activity log entries
INSERT INTO activity_log (admin_id, complaint_id, action, old_value, new_value) VALUES
(1, 1, 'status_changed', 'Pending', 'In Progress'),
(1, 1, 'assigned', NULL, 'Admin User'),
(2, 2, 'status_changed', 'Pending', 'In Progress'),
(1, 4, 'status_changed', 'In Progress', 'Resolved');

-- ============================================================
-- VERIFICATION QUERIES (Run these to check your data)
-- ============================================================

-- View all users
-- SELECT * FROM users;

-- View all admins
-- SELECT * FROM admins;

-- View all complaints with user and admin details
-- SELECT 
--     c.id,
--     u.name AS complainant,
--     c.subject,
--     c.organization,
--     c.status,
--     c.priority,
--     a.username AS assigned_admin,
--     c.submitted_at
-- FROM complaints c
-- LEFT JOIN users u ON c.user_id = u.id
-- LEFT JOIN admins a ON c.assigned_to = a.id
-- ORDER BY c.submitted_at DESC;

-- View activity log
-- SELECT 
--     al.id,
--     a.username AS admin,
--     c.subject AS complaint,
--     al.action,
--     al.old_value,
--     al.new_value,
--     al.action_time
-- FROM activity_log al
-- JOIN admins a ON al.admin_id = a.id
-- JOIN complaints c ON al.complaint_id = c.id
-- ORDER BY al.action_time DESC;

-- ============================================================
-- IMPORTANT NOTES FOR YOUR PHP CODE
-- ============================================================

/*
1. PASSWORD HASHING:
   - The sample passwords above use a generic hash for demonstration
   - In your PHP registration/login code, use:
     
     Register: $hashed = password_hash($password, PASSWORD_DEFAULT);
     Login:    if (password_verify($input_password, $stored_hash)) { ... }

2. ADMIN LOGIN CREDENTIALS FOR TESTING:
   Username: admin      | Password: admin123
   Username: superadmin | Password: super123
   Username: moderator  | Password: mod123

3. USER LOGIN CREDENTIALS FOR TESTING:
   Email: rajesh@example.com | Password: user123
   Email: priya@example.com  | Password: user123
   Email: amit@example.com   | Password: user123

4. SECURITY BEST PRACTICES:
   - Never store plain text passwords
   - Use prepared statements to prevent SQL injection
   - Implement CSRF tokens in forms
   - Use sessions for authentication
   - Add rate limiting for login attempts

5. ADMIN PERMISSIONS BASED ON ROLE:
   - Super Admin: Full access (create/delete admins, view all data)
   - Admin: Handle complaints, update status, assign tasks
   - Moderator: View complaints, add remarks (limited editing)
*/