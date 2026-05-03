# 🔧 SETUP INSTRUCTIONS - Complaint Management System

## ✅ QUICK FIX FOR YOUR LOGIN ISSUE

The problem was that `login.php` was only checking the `users` table, but admin accounts are stored in the `admins` table. **This has been FIXED!**

---

## 📋 Step-by-Step Setup

### **Step 1: Import Database**

1. Open **phpMyAdmin** (http://localhost/phpmyadmin)
2. Click **"Import"** tab
3. Choose file: `database.sql`
4. Click **"Go"**

✅ This creates: `complaint_db` database with `users`, `admins`, and `complaints` tables

---

### **Step 2: Fix Password Hashes**

The default password hashes in `database.sql` might not work. Follow these steps:

#### **Option A: Use Password Generator (Recommended)**

1. Open your browser and visit:
   ```
   http://localhost/complaint_system_fixed/generate_password_hash.php
   ```

2. Copy the SQL UPDATE commands shown on the page

3. Go to phpMyAdmin → complaint_db → SQL tab

4. Paste and run the SQL commands

5. **DELETE** `generate_password_hash.php` file after use (security)

#### **Option B: Manual SQL Update**

Run this in phpMyAdmin SQL tab:

```sql
-- Use the hashes from generate_password_hash.php
-- Or run these queries (note: hashes change each time)

USE complaint_db;

-- You'll need to visit generate_password_hash.php to get fresh hashes
-- The hashes below are examples and may not work
```

---

### **Step 3: Test Login**

#### **Admin Login:**
- URL: `http://localhost/complaint_system_fixed/login.php`
- Email: `admin@complaint.com`
- Password: `admin123`
- Redirects to: `admin.php`

#### **User Login:**
- URL: `http://localhost/complaint_system_fixed/login.php`
- Email: `rajesh@example.com`
- Password: `user123`
- Redirects to: `dashboard.php`

---

## 🔑 All Login Credentials

| Type | Email | Password | Access |
|------|-------|----------|--------|
| **Super Admin** | superadmin@complaint.com | super123 | Full admin access |
| **Admin** | admin@complaint.com | admin123 | Admin dashboard |
| **Moderator** | moderator@complaint.com | mod123 | Limited admin access |
| **User 1** | rajesh@example.com | user123 | User dashboard |
| **User 2** | priya@example.com | user123 | User dashboard |
| **User 3** | amit@example.com | user123 | User dashboard |

---

## 🛠️ What Was Fixed

### **1. login.php - Now Checks BOTH Tables**

**Before:** Only checked `users` table
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
```

**After:** Checks `admins` table first, then `users` table
```php
// Step 1: Check admins table
$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? AND is_active = TRUE");
// ...
// Step 2: If not admin, check users table
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
```

### **2. Session Variables**

- **Admins**: Set `$_SESSION['is_admin'] = true` and redirect to `admin.php`
- **Users**: Set `$_SESSION['is_admin'] = false` and redirect to `dashboard.php`

---

## 🐛 Troubleshooting

### **Issue: "Invalid email or password"**

**Solution:**
1. Run `generate_password_hash.php` to get fresh password hashes
2. Update database with the SQL commands it provides
3. Try logging in again

### **Issue: "Database connection failed"**

**Solution:**
1. Check `includes/db.php`:
   ```php
   define('DB_PASS', ''); // Set your MySQL password here
   ```
2. Make sure MySQL is running (XAMPP Control Panel)

### **Issue: Redirects to wrong page**

**Solution:**
- Admins should go to `admin.php`
- Users should go to `dashboard.php`
- Check if `admin.php` file exists

### **Issue: Session not working**

**Solution:**
Check `includes/auth.php` - it should start the session:
```php
session_start();
```

---

## 📁 File Structure

```
complaint_system/
├── index.php                  # Landing page
├── login.php                  # ✅ FIXED - Checks both users & admins
├── register.php               # User registration
├── dashboard.php              # User dashboard
├── admin.php                  # Admin dashboard
├── submit_complaint.php       # Submit new complaint
├── view_complaint.php         # View complaint details
├── logout.php                 # Logout
├── generate_password_hash.php # ⚠️ Password hash generator (DELETE AFTER USE)
├── database.sql               # Database schema
├── includes/
│   ├── db.php                # Database connection
│   └── auth.php              # Session management
└── css/
    └── style.css             # Stylesheet
```

---

## 🔐 Security Notes

1. **Never commit `config.php` or `.env` files** to Git with real passwords
2. **Delete `generate_password_hash.php`** after setting up database
3. **Change default passwords** in production
4. All passwords are hashed using `password_hash()` and verified with `password_verify()`

---

## 🎯 Testing Checklist

- [ ] Database imported successfully
- [ ] Password hashes updated
- [ ] Admin login works (`admin@complaint.com` / `admin123`)
- [ ] User login works (`rajesh@example.com` / `user123`)
- [ ] Admin redirects to `admin.php`
- [ ] User redirects to `dashboard.php`
- [ ] Can submit complaints
- [ ] Can view complaints
- [ ] `generate_password_hash.php` deleted

---

## 📞 Need Help?

If you're still having issues:

1. Check browser console for JavaScript errors (F12)
2. Check `includes/db.php` for correct database credentials
3. Verify `complaint_db` database exists in phpMyAdmin
4. Make sure all tables have data (`admins`, `users`, `complaints`)

---

**🎉 Your complaint system is now ready to use!**
