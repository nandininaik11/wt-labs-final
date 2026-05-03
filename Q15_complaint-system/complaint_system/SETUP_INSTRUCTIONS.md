# 🔧 SETUP INSTRUCTIONS - College Complaint System

## ✅ QUICK FIX FOR ADMIN LOGIN ISSUE

**Problem:** Password hash in `setup.sql` doesn't match "admin123"

The hash in line 50 of setup.sql is a generic placeholder that equals "password", NOT "admin123".

---

## 🚀 Quick Setup (3 Steps)

### **Step 1: Import Database**
```
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click "Import" tab
3. Choose file: setup.sql
4. Click "Go"
```

This creates `college_complaints` database with:
- `admins` table - Admin login credentials
- `students` table - Student accounts
- `complaints` table - Student complaints

---

### **Step 2: Fix Admin Password**
```
1. Visit: http://localhost/complaint_system/generate_password_hash.php
2. You'll see an SQL UPDATE command
3. Copy the entire SQL command
4. Go to phpMyAdmin → college_complaints → SQL tab
5. Paste and click "Go"
6. DELETE generate_password_hash.php file (security!)
```

---

### **Step 3: Test Admin Login**
```
URL: http://localhost/complaint_system/admin_login.php
http://localhost/complaint_system/student_login.php

Username: admin
Password: admin123
```

Should redirect to: `admin_dashboard.php`

---

## 🔑 Login Credentials

### **Admin:**
- **URL:** admin_login.php
- **Username:** admin
- **Password:** admin123

### **Students:**
- **URL:** student_login.php
- Students must register first at: student_register.php
- No default student accounts

---

## 📁 File Structure

```
complaint-system/
├── index.php                       # Landing page
│
├── 👨‍🎓 Student Pages:
│   ├── student_login.php          # Student login
│   ├── student_register.php       # Student registration
│   └── complaint.php              # View/submit complaints
│
├── 👨‍💼 Admin Pages:
│   ├── admin_login.php            # Admin login (SEPARATE from students)
│   └── admin_dashboard.php        # Manage all complaints
│
├── logout.php                      # Logout (clears session)
├── db.php                         # Database connection
├── setup.sql                      # Database schema
│
└── 🔧 Debug Tools (DELETE AFTER USE):
    ├── generate_password_hash.php # Fix admin password
    └── test_admin_login.php       # Test admin login
```

---

## 🐛 Troubleshooting

### Problem: "Invalid password" (Admin Login)

**Solution:**
1. Visit: `http://localhost/complaint-system/test_admin_login.php`
2. It will show if password verification works
3. If failed, run generate_password_hash.php
4. Copy SQL command and run in phpMyAdmin

### Problem: "Admin account not found"

**Solution:**
1. Check phpMyAdmin → college_complaints → admins table
2. Should have 1 row with username='admin'
3. If empty, re-run setup.sql

### Problem: "Database connection failed"

**Solution:**
1. Check `db.php`:
   ```php
   $conn = mysqli_connect("localhost", "root", "YOUR_PASSWORD", "college_complaints");
   ```
2. Change "YOUR_PASSWORD" to your MySQL password
3. Make sure MySQL is running (XAMPP)

---

## 🎯 Key Features

### **Student Features:**
- ✅ Self-registration with roll number
- ✅ Login with email and password
- ✅ Submit complaints with category and description
- ✅ View own complaints
- ✅ Track complaint status (Pending/In Progress/Resolved)

### **Admin Features:**
- ✅ Separate admin login (admin_login.php)
- ✅ View all student complaints
- ✅ Update complaint status
- ✅ Filter by status or category
- ✅ View student details

---

## 📊 Database Schema

### **admins table:**
```sql
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
```

### **students table:**
```sql
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    roll_no VARCHAR(20) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### **complaints table:**
```sql
CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    category VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Pending','In Progress','Resolved'),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id)
);
```

---

## 🔐 Important Differences from Other Systems

**This System:**
- Separate login pages: `admin_login.php` vs `student_login.php`
- Separate tables: `admins` vs `students`
- Admin uses **username** (not email)
- Students use **email** for login

**vs. Unified Login System:**
- Single login page for both roles
- Single users table with role column
- Both use email for login

---

## ⚠️ Security Notes

1. **DELETE these files after setup:**
   - generate_password_hash.php
   - test_admin_login.php

2. **Change admin password in production:**
   ```sql
   UPDATE admins SET password = 'NEW_HASH' WHERE username = 'admin';
   ```

3. **Never commit db.php with real passwords to Git**

---

## 🎯 Testing Checklist

- [ ] Database imported (setup.sql)
- [ ] Admin password hash updated
- [ ] Admin can login (admin/admin123)
- [ ] Students can register
- [ ] Students can login
- [ ] Students can submit complaints
- [ ] Admin can view all complaints
- [ ] Admin can update complaint status
- [ ] Debug files deleted

---

**🎉 Your complaint system is ready to use!**
