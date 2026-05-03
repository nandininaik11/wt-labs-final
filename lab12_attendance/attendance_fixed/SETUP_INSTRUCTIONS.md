# 🔧 SETUP INSTRUCTIONS - Attendance System

## ✅ QUICK FIX FOR LOGIN ISSUE

Same problem as your previous projects:
- **Password hash in schema.sql doesn't match "password123"**
- Generic placeholder hash that won't work
- Need to generate correct hash and update database

## 🚀 Quick Setup (3 Steps)

### **Step 1: Import Database**
```
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click "Import" tab
3. Choose file: sql/schema.sql
4. Click "Go"
```

This creates `attendance_db` database with:
- `users` table - Students and teachers
- `attendance` table - Attendance records

---

### **Step 2: Fix Password Hashes**
```
1. Visit: http://localhost/attendance_fixed/generate_password_hash.php
2. You'll see SQL UPDATE commands
3. Copy ALL 4 UPDATE statements
4. Go to phpMyAdmin → attendance_db → SQL tab
5. Paste and click "Go"
6. DELETE generate_password_hash.php file (security!)
```

---

### **Step 3: Test Login**
```
URL: http://localhost/attendance_fixed/login.php

Teacher Login:
Email: teacher@college.com
Password: password123

Student Login:
Email: alice@student.com
Password: password123
```

---

## 🔑 Default Login Credentials

| Role | Email | Password | Roll No |
|------|-------|----------|---------|
| **Teacher** | teacher@college.com | password123 | - |
| **Student** | alice@student.com | password123 | CS001 |
| **Student** | bob@student.com | password123 | CS002 |
| **Student** | carol@student.com | password123 | CS003 |

---

## 📁 File Structure

```
lab12_attendance/
├── index.php                       # Dashboard (shows different content for teacher/student)
├── login.php                       # Unified login for teachers and students
├── register.php                    # Student self-registration
├── logout.php                      # Logout
│
├── 👨‍🏫 Teacher Features:
│   ├── students.php               # View all students
│   ├── take_attendance.php        # Mark attendance
│   └── view_attendance.php        # View attendance records
│
├── 👩‍🎓 Student Features:
│   ├── my_attendance.php          # View own attendance
│   └── profile.php                # View profile
│
├── includes/
│   ├── config.php                 # Database connection + auth helpers
│   └── layout.php                 # Reusable header/footer
│
├── sql/
│   └── schema.sql                 # Database setup
│
├── 🔧 Debug Tools (DELETE AFTER USE):
│   ├── generate_password_hash.php # Password hash generator
│   └── test_login.php             # Login diagnostic tool
```

---

## 🐛 Troubleshooting

### Problem: "Invalid email or password"

**Solution:**
1. Visit: `http://localhost/lab12_attendance/test_login.php`
2. It will test all accounts and show which ones work
3. If failed, follow the instructions to fix

### Problem: User not found

**Solution:**
1. Check phpMyAdmin → attendance_db → users table
2. Should have 4 rows (1 teacher + 3 students)
3. If empty, re-import sql/schema.sql

### Problem: "Database connection failed"

**Solution:**
1. Check `includes/config.php` lines 7-10:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Your MySQL password here
   define('DB_NAME', 'attendance_db');
   ```
2. Make sure MySQL is running (XAMPP)

---

## 🎯 Features

### **Teacher Features:**
- ✅ View all registered students
- ✅ Mark attendance for students (present/absent)
- ✅ Select date and subject
- ✅ Bulk mark all as present
- ✅ View attendance history
- ✅ Filter by student, date, status

### **Student Features:**
- ✅ Self-registration
- ✅ View own attendance records
- ✅ See attendance percentage
- ✅ Filter by subject and date range

---

## 📊 Database Schema

### **users table:**
- id, name, email, password (bcrypt)
- role (student/teacher)
- roll_no (for students)
- department

### **attendance table:**
- id, student_id, date, status
- subject, marked_by (teacher id)
- Unique constraint: one record per student per date per subject

---

## 🎓 Testing Workflow

### **As Teacher:**
1. Login with teacher@college.com
2. Click "Students" to see all students
3. Click "Take Attendance"
4. Select date, subject
5. Mark students as present/absent
6. Submit
7. View "Attendance Records"

### **As Student:**
1. Login with alice@student.com
2. Dashboard shows attendance summary
3. Click "My Attendance" to see detailed records
4. Filter by subject or date

---

## 🔐 Security Features

- ✅ Password hashing with bcrypt
- ✅ Prepared statements (SQL injection prevention)
- ✅ Session-based authentication
- ✅ Role-based access control
- ✅ CSRF protection via session validation
- ✅ Input sanitization

---

## ⚠️ Important Notes

1. **DELETE these files after setup:**
   - generate_password_hash.php
   - test_login.php

2. **Change passwords in production:**
   ```sql
   UPDATE users SET password = 'NEW_HASH' WHERE email = 'user@email.com';
   ```

3. **Never commit config.php with real passwords to Git**

---

## 📝 Quick Reference

**Database:** attendance_db  
**Tables:** users, attendance  
**Default Password:** password123 (for all users)  

**Teacher Email:** teacher@college.com  
**Student Emails:** alice@student.com, bob@student.com, carol@student.com

---

**🎉 Your attendance system is ready to use!**
