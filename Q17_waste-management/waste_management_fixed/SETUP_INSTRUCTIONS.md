# 🔧 SETUP INSTRUCTIONS - Waste Management System

## ✅ QUICK FIX FOR LOGIN ISSUE

The problem is the same as your complaint system:
- **Password hash in database doesn't match "admin123"**
- The default hash in `setup.sql` is a placeholder that won't work

## 🚀 Quick Setup (3 Steps)

### **Step 1: Import Database**
```
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click "Import" tab
3. Choose file: setup.sql
4. Click "Go"
```

This creates `waste_management` database with these tables:
- `admins` - Admin login credentials
- `waste_reports` - Citizen waste complaints
- `authorities` - Waste collection teams

---

### **Step 2: Fix Admin Password**
```
1. Visit: http://localhost/waste_management_fixed/generate_password_hash.php
2. You'll see a page with an SQL UPDATE command
3. Copy the entire SQL command
4. Go to phpMyAdmin → waste_management → SQL tab
5. Paste and click "Go"
6. DELETE generate_password_hash.php file (security!)
```

---

### **Step 3: Test Login**
```
URL: http://localhost/waste_management_fixed/admin_login.php

Username: admin
Password: admin123
```

Should redirect to: `admin_dashboard.php`

---

## 🔑 Default Login Credentials

| Type | Username | Password | Access |
|------|----------|----------|--------|
| **Admin** | admin | admin123 | admin_dashboard.php |

---

## 🐛 Troubleshooting

### Problem: "Incorrect password"

**Solution:**
1. Visit: `http://localhost/waste-management/test_login.php`
2. It will diagnose the exact issue
3. Follow the instructions it shows
4. Delete `test_login.php` after fixing

### Problem: "No admin found with that username"

**Solution:**
1. Open phpMyAdmin
2. Check if `waste_management` database exists
3. Check if `admins` table has data
4. Re-run `setup.sql` if needed

### Problem: "Database connection failed"

**Solution:**
1. Check `db.php` file:
   ```php
   $conn = mysqli_connect("localhost", "root", "YOUR_PASSWORD", "waste_management");
   ```
2. Make sure MySQL password is correct
3. Make sure MySQL is running (XAMPP Control Panel)

---

## 📁 File Structure

```
waste-management/
├── index.php                       # Landing page
├── report.php                      # Public: Report waste (citizen)
├── track.php                       # Public: Track report status
├── admin_login.php                 # Admin login page
├── admin_dashboard.php             # Admin: View & manage reports
├── logout.php                      # Logout
├── db.php                         # Database connection
├── setup.sql                      # Database schema
├── generate_password_hash.php     # 🔧 Password hash generator (DELETE AFTER USE)
├── test_login.php                 # 🧪 Login diagnostic tool (DELETE AFTER USE)
└── css/
    └── style.css                  # Stylesheet
```

---

## 🎯 Testing Checklist

- [ ] Database imported (`setup.sql`)
- [ ] Password hash updated (via `generate_password_hash.php`)
- [ ] Admin login works (`admin` / `admin123`)
- [ ] Can access admin dashboard
- [ ] Citizens can report waste
- [ ] Citizens can track reports
- [ ] Admin can update report status
- [ ] Security files deleted (`generate_password_hash.php`, `test_login.php`)

---

## 📊 What Each Page Does

### **Public Pages:**
- `index.php` - Landing page
- `report.php` - Citizens report waste location
- `track.php` - Track report status by ID

### **Admin Pages:**
- `admin_login.php` - Admin login
- `admin_dashboard.php` - Manage all waste reports
- `logout.php` - Logout and clear session

---

## ⚠️ Security Notes

1. **DELETE these files after setup:**
   - `generate_password_hash.php`
   - `test_login.php`

2. **Change default password in production:**
   ```sql
   UPDATE admins SET password = 'NEW_HASH' WHERE username = 'admin';
   ```

3. **Never commit `db.php` with real passwords to Git**

---

## 🔐 Password Security

- Passwords are hashed using `password_hash()` with bcrypt
- Verification uses `password_verify()`
- Never store plain text passwords
- Hash is 60 characters long (bcrypt standard)

---

**🎉 Your waste management system is ready to use!**
