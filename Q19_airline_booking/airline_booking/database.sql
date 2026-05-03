-- ============================================================
-- Airline Seat Booking System - Database Setup
-- Run in phpMyAdmin: Import this file after creating DB
-- OR run: mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS airline_db;
USE airline_db;

-- ── Flights table ──
-- Stores available flights (like a flight schedule)
CREATE TABLE IF NOT EXISTS flights (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    flight_no   VARCHAR(20)  NOT NULL,          -- e.g. AI-101
    origin      VARCHAR(100) NOT NULL,          -- departure city
    destination VARCHAR(100) NOT NULL,          -- arrival city
    depart_time DATETIME     NOT NULL,          -- when it departs
    total_seats INT          NOT NULL DEFAULT 60, -- total seats (6 cols × 10 rows)
    price       DECIMAL(8,2) NOT NULL           -- ticket price in ₹
);

-- ── Seats table ──
-- Each row = one physical seat in one flight
-- 10 rows × 6 seats (A-F) = 60 seats per flight
CREATE TABLE IF NOT EXISTS seats (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    flight_id INT         NOT NULL,             -- which flight
    seat_no   VARCHAR(5)  NOT NULL,             -- e.g. 1A, 5C, 10F
    row_num   INT         NOT NULL,             -- row number 1-10
    col_label CHAR(1)     NOT NULL,             -- A B C  |aisle| D E F
    class     ENUM('Business','Economy') NOT NULL, -- first 2 rows = Business
    is_booked TINYINT(1)  NOT NULL DEFAULT 0,   -- 0=available, 1=booked
    FOREIGN KEY (flight_id) REFERENCES flights(id) ON DELETE CASCADE,
    UNIQUE KEY unique_seat (flight_id, seat_no)  -- no duplicate seats per flight
);

-- ── Bookings table ──
-- Stores passenger info for each booked seat
CREATE TABLE IF NOT EXISTS bookings (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    flight_id    INT          NOT NULL,
    seat_id      INT          NOT NULL,
    passenger    VARCHAR(100) NOT NULL,         -- passenger full name
    email        VARCHAR(100) NOT NULL,         -- passenger email
    phone        VARCHAR(15)  NOT NULL,         -- passenger phone
    booked_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    pnr          VARCHAR(10)  NOT NULL UNIQUE,  -- unique booking reference code
    FOREIGN KEY (flight_id) REFERENCES flights(id),
    FOREIGN KEY (seat_id)   REFERENCES seats(id)
);

-- ── Insert sample flights ──
INSERT INTO flights (flight_no, origin, destination, depart_time, total_seats, price) VALUES
('AI-101', 'Mumbai',    'Delhi',     '2026-06-01 06:00:00', 60, 4500.00),
('AI-202', 'Delhi',     'Bangalore', '2026-06-01 09:30:00', 60, 5200.00),
('6E-303', 'Chennai',   'Hyderabad', '2026-06-02 14:00:00', 60, 3800.00),
('SG-404', 'Pune',      'Mumbai',    '2026-06-02 17:45:00', 60, 2500.00);

-- ── Generate seats for each flight ──
-- Rows 1-2: Business class | Rows 3-10: Economy class
-- Columns: A, B, C (window/middle/aisle), D, E, F (aisle/middle/window)

-- We use a stored procedure to generate 60 seats per flight
DELIMITER //
CREATE PROCEDURE GenerateSeats()
BEGIN
    DECLARE fid INT;
    DECLARE r   INT;
    DECLARE done INT DEFAULT 0;
    DECLARE cur CURSOR FOR SELECT id FROM flights;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;
    flight_loop: LOOP
        FETCH cur INTO fid;
        IF done THEN LEAVE flight_loop; END IF;
        
        SET r = 1;
        WHILE r <= 10 DO
            -- Insert 6 seats per row (A-F)
            INSERT IGNORE INTO seats (flight_id, seat_no, row_num, col_label, class) VALUES
            (fid, CONCAT(r,'A'), r, 'A', IF(r<=2,'Business','Economy')),
            (fid, CONCAT(r,'B'), r, 'B', IF(r<=2,'Business','Economy')),
            (fid, CONCAT(r,'C'), r, 'C', IF(r<=2,'Business','Economy')),
            (fid, CONCAT(r,'D'), r, 'D', IF(r<=2,'Business','Economy')),
            (fid, CONCAT(r,'E'), r, 'E', IF(r<=2,'Business','Economy')),
            (fid, CONCAT(r,'F'), r, 'F', IF(r<=2,'Business','Economy'));
            SET r = r + 1;
        END WHILE;
    END LOOP;
    CLOSE cur;
END//
DELIMITER ;

CALL GenerateSeats();
DROP PROCEDURE GenerateSeats;

-- ── Pre-book a few seats so map looks realistic ──
UPDATE seats SET is_booked=1 WHERE flight_id=1 AND seat_no IN ('1A','1B','2C','3D','4A','5F','6B','7C','8E');
UPDATE seats SET is_booked=1 WHERE flight_id=2 AND seat_no IN ('1A','2B','3A','4C','5D','6F');
