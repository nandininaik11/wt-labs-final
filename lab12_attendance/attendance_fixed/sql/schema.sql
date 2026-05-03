-- ============================================================
-- Lab Q12: Attendance System Database Schema
-- Run this in phpMyAdmin or: mysql -u root -p < schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS attendance_db;
USE attendance_db;

-- Users table (both students and teachers)
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)        NOT NULL,
    email       VARCHAR(150)        UNIQUE NOT NULL,
    password    VARCHAR(255)        NOT NULL,       -- bcrypt hash
    role        ENUM('student','teacher') NOT NULL DEFAULT 'student',
    roll_no     VARCHAR(20)         DEFAULT NULL,   -- Only for students
    department  VARCHAR(100)        DEFAULT NULL,
    created_at  TIMESTAMP           DEFAULT CURRENT_TIMESTAMP
);

-- Attendance records
CREATE TABLE IF NOT EXISTS attendance (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    student_id  INT                 NOT NULL,
    date        DATE                NOT NULL,
    status      ENUM('present','absent') NOT NULL DEFAULT 'absent',
    subject     VARCHAR(100)        DEFAULT 'General',
    marked_by   INT                 NOT NULL,       -- teacher's user id
    created_at  TIMESTAMP           DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_attendance (student_id, date, subject),
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (marked_by)  REFERENCES users(id) ON DELETE CASCADE
);

-- ──────────────────────────────────────────────────────────────
-- Seed data: 1 teacher + 3 demo students
-- Passwords are bcrypt of "password123"
-- ──────────────────────────────────────────────────────────────
INSERT INTO users (name, email, password, role, roll_no, department) VALUES
('Prof. Sharma',  'teacher@college.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uZutLiPi2', 'teacher', NULL,    'Computer Science'),
('Alice Patel',   'alice@student.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uZutLiPi2', 'student', 'CS001', 'Computer Science'),
('Bob Kulkarni',  'bob@student.com',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uZutLiPi2', 'student', 'CS002', 'Computer Science'),
('Carol Mehta',   'carol@student.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uZutLiPi2', 'student', 'CS003', 'Computer Science');

-- NOTE: All seed passwords = "password123"
