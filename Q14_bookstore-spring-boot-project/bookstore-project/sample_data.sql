-- ================================================================
-- SAMPLE DATA FOR ONLINE BOOKSTORE DATABASE
-- ================================================================
-- This script populates the database with sample books
-- Run this after the application creates the tables
-- ================================================================

USE bookstore_db;

-- ----------------------------------------------------------------
-- INSERT SAMPLE BOOKS
-- ----------------------------------------------------------------
-- Adding 20+ sample books across different categories
-- ----------------------------------------------------------------

INSERT INTO books (title, author, isbn, description, category, publisher, price, stock_quantity, image_url, page_count, language, available, rating, created_date) VALUES

-- TECHNOLOGY BOOKS
('Java Programming Masterclass', 'John Smith', '978-0-13-601970-1', 
'Complete guide to Java programming from basics to advanced concepts. Covers Java 17 features, OOP, collections, streams, and more.', 
'Technology', 'Tech Publishers', 499.00, 15, 
'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=300&h=400&fit=crop', 
650, 'English', TRUE, 4.5, CURDATE()),

('Python for Data Science', 'Jane Doe', '978-0-13-601971-2', 
'Learn Python programming with focus on data analysis, NumPy, Pandas, and machine learning basics.', 
'Technology', 'Data Press', 599.00, 12, 
'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=300&h=400&fit=crop', 
550, 'English', TRUE, 4.7, CURDATE()),

('Web Development with Spring Boot', 'Bob Johnson', '978-0-13-601972-3', 
'Master modern web development using Spring Boot framework. Build scalable applications with REST APIs.', 
'Technology', 'Spring House', 699.00, 10, 
'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=300&h=400&fit=crop', 
720, 'English', TRUE, 4.6, CURDATE()),

('Machine Learning Essentials', 'Alice Williams', '978-0-13-601973-4', 
'Introduction to machine learning algorithms, neural networks, and deep learning fundamentals.', 
'Technology', 'AI Publishers', 799.00, 8, 
'https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=300&h=400&fit=crop', 
680, 'English', TRUE, 4.8, CURDATE()),

('Database Systems', 'Charlie Brown', '978-0-13-601974-5', 
'Comprehensive guide to database design, SQL, normalization, and database management systems.', 
'Technology', 'Data Press', 650.00, 0, 
'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=300&h=400&fit=crop', 
600, 'English', FALSE, 4.4, CURDATE()),

-- FICTION BOOKS
('The Mystery of Echo Lake', 'Sarah Mitchell', '978-0-14-101234-5', 
'A thrilling mystery novel set in a small town where secrets from the past resurface.', 
'Fiction', 'Mystery House', 350.00, 20, 
'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=300&h=400&fit=crop', 
380, 'English', TRUE, 4.3, CURDATE()),

('Echoes of Tomorrow', 'Michael Chen', '978-0-14-101235-6', 
'Science fiction adventure exploring time travel and parallel universes.', 
'Fiction', 'Sci-Fi Press', 420.00, 18, 
'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=300&h=400&fit=crop', 
450, 'English', TRUE, 4.6, CURDATE()),

('The Last Summer', 'Emma Davis', '978-0-14-101236-7', 
'A heartwarming coming-of-age story about friendship, love, and growing up.', 
'Fiction', 'Modern Fiction', 380.00, 15, 
'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=300&h=400&fit=crop', 
320, 'English', TRUE, 4.5, CURDATE()),

-- BUSINESS BOOKS
('Startup Success Secrets', 'David Thompson', '978-0-15-202345-6', 
'Essential strategies for building and scaling a successful startup in the modern economy.', 
'Business', 'Business Weekly', 550.00, 12, 
'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=400&fit=crop', 
400, 'English', TRUE, 4.4, CURDATE()),

('The Lean Manager', 'Lisa Anderson', '978-0-15-202346-7', 
'Learn lean management principles to optimize business processes and increase efficiency.', 
'Business', 'Management Press', 480.00, 10, 
'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=300&h=400&fit=crop', 
350, 'English', TRUE, 4.6, CURDATE()),

('Digital Marketing Mastery', 'Robert Kumar', '978-0-15-202347-8', 
'Complete guide to digital marketing strategies, SEO, social media, and content marketing.', 
'Business', 'Marketing Hub', 620.00, 14, 
'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=300&h=400&fit=crop', 
480, 'English', TRUE, 4.7, CURDATE()),

-- SCIENCE BOOKS
('Cosmos Explained', 'Dr. Neil Peterson', '978-0-16-303456-7', 
'Journey through the universe exploring stars, galaxies, and the mysteries of space.', 
'Science', 'Cosmos Press', 720.00, 8, 
'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=300&h=400&fit=crop', 
520, 'English', TRUE, 4.9, CURDATE()),

('Quantum Physics for Beginners', 'Dr. Anna Rodriguez', '978-0-16-303457-8', 
'Accessible introduction to quantum mechanics and particle physics concepts.', 
'Science', 'Physics Today', 680.00, 6, 
'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=300&h=400&fit=crop', 
450, 'English', TRUE, 4.5, CURDATE()),

-- HISTORY BOOKS
('Ancient Civilizations', 'Professor James Wilson', '978-0-17-404567-8', 
'Explore the great civilizations of ancient Egypt, Greece, Rome, and Mesopotamia.', 
'History', 'History Press', 580.00, 10, 
'https://images.unsplash.com/photo-1461360370896-922624d12aa1?w=300&h=400&fit=crop', 
600, 'English', TRUE, 4.6, CURDATE()),

('World War Chronicles', 'Dr. Margaret Foster', '978-0-17-404568-9', 
'Detailed account of World War II from multiple perspectives and theaters of war.', 
'History', 'War Studies', 650.00, 7, 
'https://images.unsplash.com/photo-1509021436665-8f07dbf5bf1d?w=300&h=400&fit=crop', 
720, 'English', TRUE, 4.7, CURDATE()),

-- SELF-HELP BOOKS
('The Power of Now', 'Amanda Green', '978-0-18-505678-9', 
'Transform your life by living in the present moment and breaking free from negative thought patterns.', 
'Self-Help', 'Mindful Living', 420.00, 25, 
'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=300&h=400&fit=crop', 
280, 'English', TRUE, 4.8, CURDATE()),

('Atomic Habits Guide', 'James Clear Jr.', '978-0-18-505679-0', 
'Practical strategies for building good habits and breaking bad ones for long-term success.', 
'Self-Help', 'Success Publishers', 450.00, 22, 
'https://images.unsplash.com/photo-1516414447565-b14be0adf13e?w=300&h=400&fit=crop', 
320, 'English', TRUE, 4.9, CURDATE()),

-- COOKING BOOKS
('The Complete Cookbook', 'Chef Maria Garcia', '978-0-19-606789-0', 
'500+ recipes from around the world with step-by-step instructions and beautiful photos.', 
'Cooking', 'Culinary Arts', 890.00, 15, 
'https://images.unsplash.com/photo-1481931715705-36f5f79f1f3d?w=300&h=400&fit=crop', 
650, 'English', TRUE, 4.7, CURDATE()),

('Healthy Eating Made Easy', 'Dr. Rachel nutritionistomson', '978-0-19-606790-1', 
'Nutritious recipes and meal planning guide for a healthier lifestyle.', 
'Cooking', 'Health Kitchen', 520.00, 18, 
'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=300&h=400&fit=crop', 
380, 'English', TRUE, 4.6, CURDATE()),

-- CHILDREN'S BOOKS
('Adventures in Wonderland', 'Sophie Turner', '978-0-20-707890-1', 
'Magical journey through an enchanted land filled with talking animals and amazing adventures.', 
'Children', 'Kids World', 280.00, 30, 
'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=300&h=400&fit=crop', 
150, 'English', TRUE, 4.9, CURDATE()),

('The Little Explorer', 'Oliver Bennett', '978-0-20-707891-2', 
'Join young Max on exciting explorations teaching kids about science and nature.', 
'Children', 'Young Readers', 320.00, 28, 
'https://images.unsplash.com/photo-1541963463532-d68292c34b19?w=300&h=400&fit=crop', 
180, 'English', TRUE, 4.8, CURDATE());

-- ----------------------------------------------------------------
-- VERIFY DATA
-- ----------------------------------------------------------------
-- Check if books were inserted successfully
-- ----------------------------------------------------------------

SELECT 
    COUNT(*) as total_books,
    COUNT(DISTINCT category) as total_categories
FROM books;

-- Show books by category
SELECT 
    category, 
    COUNT(*) as book_count,
    AVG(price) as avg_price
FROM books
GROUP BY category
ORDER BY book_count DESC;

-- ================================================================
-- NOTES:
-- ================================================================
-- 
-- This script inserts 21 sample books across 8 categories:
-- - Technology (5 books)
-- - Fiction (3 books)
-- - Business (3 books)
-- - Science (2 books)
-- - History (2 books)
-- - Self-Help (2 books)
-- - Cooking (2 books)
-- - Children (2 books)
-- 
-- Each book includes:
-- - Title, Author, ISBN
-- - Description
-- - Category, Publisher
-- - Price, Stock Quantity
-- - Image URL (using Unsplash placeholders)
-- - Page Count, Language
-- - Availability, Rating
-- - Created Date
-- 
-- Image URLs use Unsplash for placeholder images
-- Real production app would have actual book cover images
-- 
-- To run this script:
-- 1. Make sure MySQL is running
-- 2. Database bookstore_db exists
-- 3. Tables have been created by Spring Boot
-- 4. Run: mysql -u root -p bookstore_db < sample_data.sql
-- ================================================================
