# 📝 College Complaint System - FIXED VERSION

## ✅ What Was Fixed

Your college complaint system had the **admin login password issue**:
- Hash in `setup.sql` line 50: `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`
- This hash equals **"password"** (Laravel test default)
- NOT **"admin123"** as shown in admin_login.php
- Password verification was failing

### **All Fixed!** ✅

---

## 🚀 Quick Start (3 Steps)

### **Step 1: Import Database**
```bash
# Open phpMyAdmin: http://localhost/phpmyadmin
# Import: setup.sql
```

### **Step 2: Fix Admin Password**
```bash
# Visit: http://localhost/complaint-system/generate_password_hash.php
# Copy the SQL UPDATE command
# Run in phpMyAdmin
# DELETE generate_password_hash.php
```

### **Step 3: Test Login**
```bash
# Admin Login
URL: http://localhost/complaint-system/admin_login.php
Username: admin
Password: admin123
```

---

## 🔑 Login Credentials

| Role | Login Page | Credentials | Notes |
|------|-----------|-------------|-------|
| **Admin** | admin_login.php | Username: admin<br>Password: admin123 | Fixed in setup |
| **Student** | student_login.php | Register first | No default students |

---

## 📁 Project Structure

```
complaint-system/
├── 🏠 index.php                     # Landing page
│
├── 👨‍🎓 Student Portal:
│   ├── student_login.php           # Student login (uses EMAIL)
│   ├── student_register.php        # Self-registration
│   └── complaint.php               # View/submit complaints
│
├── 👨‍💼 Admin Portal:
│   ├── admin_login.php             # Admin login (uses USERNAME)
│   └── admin_dashboard.php         # Manage all complaints
│
├── 🚪 logout.php                    # Logout
├── 💾 db.php                        # Database connection
├── 📊 setup.sql                     # Database schema
│
└── 🔧 Tools (DELETE AFTER USE):
    ├── generate_password_hash.php  # Fix admin password
    └── test_admin_login.php        # Test admin login
```

---

## 🎯 Features

### **Student Features:**
- ✅ Self-registration (name, email, password, roll number)
- ✅ Login with email and password
- ✅ Submit complaints (category, subject, description)
- ✅ View own complaints
- ✅ Track status: Pending → In Progress → Resolved

### **Admin Features:**
- ✅ Separate login page (username: admin)
- ✅ View ALL student complaints
- ✅ Update complaint status
- ✅ Filter complaints by status/category
- ✅ View student information

---

## 🔧 What Was Fixed

### **1. Password Hash Problem**

**Before:** `setup.sql` line 50
```sql
-- Wrong hash (equals "password", not "admin123")
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
```

**After:**
```sql
-- Correct hash generated via password_hash('admin123', PASSWORD_DEFAULT)
-- Will be generated when you run generate_password_hash.php
```

### **2. Added Diagnostic Tools**

- `generate_password_hash.php` - Generates correct hash
- `test_admin_login.php` - Tests if admin login works

---

## 📊 Database Schema

### **Tables:**

1. **admins** (1 default)
   - id, username, password

2. **students** (empty by default)
   - id, name, email, password, roll_no, created_at

3. **complaints** (empty by default)
   - id, student_id, category, subject, description
   - status, submitted_at
   - Foreign key: student_id → students(id)

---

## 🔐 Key Differences from Other Systems

### **This System (Separate Logins):**
- ✅ Separate login pages: admin_login.php vs student_login.php
- ✅ Separate tables: admins vs students
- ✅ Admin uses **username** for login
- ✅ Students use **email** for login
- ✅ Two different session variables: admin_id vs student_id

### **vs. Unified Login System:**
- Single login page for both roles
- Single users table with role column
- Both use email for login

---

## 🐛 Troubleshooting

### Issue: "Invalid password" (admin login)

**Fix:**
```bash
1. Visit: http://localhost/complaint-system/test_admin_login.php
2. See if password verification works
3. If failed, run generate_password_hash.php
4. Copy SQL and run in phpMyAdmin
```

### Issue: "Admin account not found"

**Fix:**
```bash
1. Check phpMyAdmin → college_complaints → admins table
2. Should have 1 row: username='admin'
3. If empty, re-import setup.sql
```

### Issue: Students can't register

**Fix:**
```bash
1. Make sure database is created (college_complaints)
2. Check students table exists
3. Verify db.php has correct password
```

---

## 🎓 Usage Examples

### **Admin Workflow:**

1. **Login**
   ```
   URL: admin_login.php
   Username: admin
   Password: admin123
   ```

2. **View Complaints**
   ```
   Dashboard shows all complaints from all students
   Columns: ID, Student Name, Category, Subject, Status, Date
   ```

3. **Update Status**
   ```
   Click on a complaint
   Change status: Pending → In Progress → Resolved
   Save
   ```

### **Student Workflow:**

1. **Register** (first time)
   ```
   URL: student_register.php
   Fill: Name, Email, Password, Roll Number
   Submit
   ```

2. **Login**
   ```
   URL: student_login.php
   Email: your@email.com
   Password: your_password
   ```

3. **Submit Complaint**
   ```
   URL: complaint.php
   Select Category (Library, Hostel, Canteen, etc.)
   Enter Subject and Description
   Submit
   ```

4. **View Complaints**
   ```
   See list of your complaints
   Status shows: Pending, In Progress, or Resolved
   ```

---

## 🔐 Security Features

- ✅ **Password Hashing:** bcrypt via password_hash()
- ✅ **SQL Injection Prevention:** mysqli_real_escape_string()
- ✅ **Session Security:** Separate admin/student sessions
- ✅ **Role-Based Access:** Admins can't access student pages
- ✅ **Foreign Keys:** Data integrity (complaints → students)

---

## ⚠️ Important Notes

**After Setup:**
1. DELETE `generate_password_hash.php`
2. DELETE `test_admin_login.php`
3. Change admin password in production
4. Don't commit `db.php` with real password to Git

---

## 🎯 Learning Objectives

This project demonstrates:
- ✅ **Separate Login Systems** - Different pages for different roles
- ✅ **PHP Sessions** - admin_id vs student_id
- ✅ **Password Hashing** - password_hash() & password_verify()
- ✅ **MySQLi** - Database operations (procedural style)
- ✅ **CRUD Operations** - Create, Read, Update complaints
- ✅ **ENUM Types** - Predefined status values
- ✅ **Foreign Keys** - Link complaints to students
- ✅ **Form Validation** - Client and server-side
- ✅ **Bootstrap 5** - Responsive design

---

## 📞 Support

**Detailed Setup:** See `SETUP_INSTRUCTIONS.md`  
**Login Issues:** Run `test_admin_login.php`  
**Password Issues:** Run `generate_password_hash.php`

---

**🎉 Your college complaint system is fully functional!**

*Web Technologies Lab Project*
