# 🛠️ Complaint Management System - FIXED VERSION

## ✅ What Was Fixed

Your original system had a **login issue** where admin accounts couldn't login because:
1. `login.php` only checked the `users` table
2. Admin accounts are stored in a separate `admins` table
3. Password hashes in database.sql were generic placeholders

### **All Fixed!** ✅

---

## 🚀 Quick Start (3 Steps)

### **Step 1: Import Database**
```bash
# Open phpMyAdmin: http://localhost/phpmyadmin
# Click Import → Choose database.sql → Click Go
```

### **Step 2: Fix Password Hashes**
```bash
# Visit: http://localhost/complaint_system/generate_password_hash.php
# Copy the SQL commands shown
# Run them in phpMyAdmin SQL tab
```

### **Step 3: Test Login**
```bash
# Admin Login
Email: admin@complaint.com
Password: admin123

# User Login  
Email: rajesh@example.com
Password: user123
```

---

## 🔑 Default Login Credentials

| Role | Email | Password | Dashboard |
|------|-------|----------|-----------|
| Super Admin | superadmin@complaint.com | super123 | admin.php |
| Admin | admin@complaint.com | admin123 | admin.php |
| Moderator | moderator@complaint.com | mod123 | admin.php |
| User 1 | rajesh@example.com | user123 | dashboard.php |
| User 2 | priya@example.com | user123 | dashboard.php |
| User 3 | amit@example.com | user123 | dashboard.php |

---

## 🔧 What Changed (Technical Details)

### **1. login.php - Two-Stage Authentication**

**Before:**
```php
// Only checked users table
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
```

**After:**
```php
// Step 1: Check admins table first
$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? AND is_active = TRUE");
// ... if admin found, set admin session and redirect to admin.php

// Step 2: If not admin, check users table
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
// ... if user found, set user session and redirect to dashboard.php
```

### **2. auth.php - Enhanced Session Handling**

**New Functions:**
- `requireLogin()` - Checks both user_id and admin_id
- `isAdmin()` - Returns true if logged in as admin
- `requireAdmin()` - Forces admin access (used in admin.php)
- `currentUser()` - Returns username (works for both users and admins)

### **3. admin.php - Proper Admin Check**

**Before:**
```php
if ($_SESSION['user_id'] != 1) { // Only user ID 1 could access
```

**After:**
```php
requireAdmin(); // Checks if $_SESSION['is_admin'] === true
```

---

## ⚠️ IMPORTANT: Delete These Files After Setup

1. `generate_password_hash.php` - Security risk
2. `test_login.php` - Debug tool only

---

For detailed setup instructions, see: **SETUP_INSTRUCTIONS.md**

**🎉 Your complaint system is now fully functional!**
