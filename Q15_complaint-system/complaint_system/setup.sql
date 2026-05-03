-- ============================================================
-- Complete College Complaint System Database Setup
-- ============================================================

-- Step 1: Create database
CREATE DATABASE IF NOT EXISTS college_complaints;
USE college_complaints;

-- Step 2: Create students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    roll_no VARCHAR(20) NOT NULL UNIQUE,
    department VARCHAR(100) DEFAULT NULL,
    year INT DEFAULT 1,
    phone VARCHAR(15) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Step 3: Create admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Step 4: Create complaints table
CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    category VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Pending','In Progress','Resolved') DEFAULT 'Pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- ============================================================
-- Insert Sample Data
-- ============================================================

-- Insert Admin (username: admin, password: admin123)
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFpJzLwVBJPQQzzLGp3jZ9wqgRZLJGJa');

-- Insert Sample Students
-- All passwords are: student123
INSERT INTO students (name, email, password, roll_no, department, year, phone) VALUES
('Rahul Sharma', 'rahul@student.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFpJzLwVBJPQQzzLGp3jZ9wqgRZLJGJa', 'CS001', 'Computer Science', 3, '9876543210'),
('Priya Patel', 'priya@student.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFpJzLwVBJPQQzzLGp3jZ9wqgRZLJGJa', 'CS002', 'Computer Science', 2, '9876543211'),
('Amit Kumar', 'amit@student.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFpJzLwVBJPQQzzLGp3jZ9wqgRZLJGJa', 'EC001', 'Electronics', 3, '9876543212'),
('Sneha Verma', 'sneha@student.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFpJzLwVBJPQQzzLGp3jZ9wqgRZLJGJa', 'ME001', 'Mechanical', 2, '9876543213'),
('Rohan Desai', 'rohan@student.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFpJzLwVBJPQQzzLGp3jZ9wqgRZLJGJa', 'CS003', 'Computer Science', 1, '9876543214'),
('Ananya Singh', 'ananya@student.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFpJzLwVBJPQQzzLGp3jZ9wqgRZLJGJa', 'IT001', 'Information Technology', 3, '9876543215');

-- Insert Sample Complaints
INSERT INTO complaints (student_id, category, subject, description, status) VALUES
(1, 'Library', 'Late Hours Request', 'Request to extend library hours during exams', 'Pending'),
(2, 'Hostel', 'WiFi Not Working', 'WiFi connectivity issues in hostel room 301', 'In Progress'),
(3, 'Canteen', 'Food Quality', 'Food quality has degraded in the past week', 'Pending'),
(4, 'Classroom', 'AC Not Working', 'Air conditioning not functional in Room 204', 'Resolved'),
(1, 'Sports', 'Equipment Request', 'Need new badminton rackets for sports complex', 'Pending');

-- ============================================================
-- Verify the data
-- ============================================================
SELECT * FROM admins;
SELECT * FROM students;
SELECT * FROM complaints;