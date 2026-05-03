-- ========================================
-- DATABASE SETUP FOR PHP LOGIN SYSTEM
-- Lab Question 13: PHP Login Module
-- ========================================

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS login_system;

-- Use the database
USE login_system;

-- Drop table if exists (for fresh setup)
DROP TABLE IF EXISTS users;

-- Create users table
-- This table stores user registration information
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,           -- Unique identifier for each user (Primary Key)
    username VARCHAR(50) NOT NULL UNIQUE,            -- Username (must be unique, cannot be null)
    email VARCHAR(100) NOT NULL UNIQUE,              -- Email address (must be unique)
    password VARCHAR(255) NOT NULL,                  -- Password (hashed using password_hash())
    full_name VARCHAR(100) NOT NULL,                 -- User's full name
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Account creation timestamp
    last_login TIMESTAMP NULL,                       -- Last login timestamp
    login_count INT(11) DEFAULT 0                    -- Track number of logins
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create sessions table to track active sessions
DROP TABLE IF EXISTS user_sessions;

CREATE TABLE user_sessions (
    session_id VARCHAR(255) PRIMARY KEY,             -- PHP session ID
    user_id INT(11) NOT NULL,                        -- Foreign key to users table
    ip_address VARCHAR(45),                          -- User's IP address
    user_agent VARCHAR(255),                         -- Browser/device information
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Session creation time
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample users for testing (passwords are hashed)
-- Default password for all test users: "password123"
INSERT INTO users (username, email, password, full_name) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator'),
('testuser', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test User');

-- Theory Explanation:
-- ==================
-- 1. PRIMARY KEY: Uniquely identifies each record in the table
-- 2. AUTO_INCREMENT: Automatically generates sequential numbers
-- 3. NOT NULL: Field must contain a value
-- 4. UNIQUE: No duplicate values allowed
-- 5. VARCHAR(n): Variable-length string with max length n
-- 6. INT(11): Integer with display width of 11 digits
-- 7. TIMESTAMP: Stores date and time information
-- 8. DEFAULT: Sets default value if none provided
-- 9. FOREIGN KEY: Creates relationship between tables
-- 10. ON DELETE CASCADE: Deletes related records when parent is deleted
-- 11. ENGINE=InnoDB: Storage engine supporting transactions and foreign keys
-- 12. CHARSET=utf8mb4: Character set supporting emojis and international characters
