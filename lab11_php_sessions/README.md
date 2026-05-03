# 🔐 Lab Q11 – PHP Session Manager
## Max 3 Concurrent Sessions + 5-Minute Timeout

---

## 📁 File Structure

```
lab11_php_sessions/
├── index.php              ← Login page (main entry point)
├── dashboard.php          ← Post-login dashboard (shows timer, sessions)
├── admin.php              ← Admin panel (all users + sessions)
├── keepalive.php          ← AJAX endpoint to refresh session timer
├── includes/
│   └── SessionManager.php ← Core class (all session logic)
├── data/
│   └── sessions.json      ← Auto-created; persistent session store
└── README.md              ← This file
```

---

## ⚙️ SETUP & RUN COMMANDS

### Prerequisites

**Required Software:**
- XAMPP (includes Apache and PHP)
- Web browser (Chrome, Firefox, Edge)
- Text editor (VS Code recommended)

### Installation Steps

**Step 1: Install XAMPP**
```bash
# Download from: https://www.apachefriends.org/download.html
# Install to default location: C:\xampp (Windows) or /opt/lampp (Linux)
```

**Step 2: Extract This Lab**
```bash
# Extract lab6_electricity_bill.zip to:
C:\xampp\htdocs\lab11_php_sessions


```

**Step 3: Start Apache Server**
```bash
# Windows: Open XAMPP Control Panel
xampp-control.exe
# Click "Start" next to Apache

```

**Step 4: Access Application**
```bash

http://localhost/lab11_php_sessions/index.php


### Test the 3-session limit
1. Open `http://localhost/lab11_php_sessions/index.php` in **3 different browser tabs**
2. Login with the same username (e.g., `alice`) in all 3 tabs ✅
3. Try a 4th login — it should be **denied** ❌
4. Wait 5 minutes (or open `admin.php` → Clear All to reset)

---

## 📖 Complete Theory (WT Syllabus – Unit III: PHP Cookies & Sessions)

### 1. What is a Session?
A **session** is a way to store information (variables) to be used across multiple pages on a server. Unlike cookies (which are stored client-side in the browser), session data is stored **server-side**.

How it works:
1. Server creates a unique **Session ID** (e.g., `abc123xyz`)
2. Session ID is sent to the browser via a **cookie** (`PHPSESSID`)
3. On every subsequent request, browser sends the Session ID back
4. Server looks up session data using that ID

### 2. PHP Session Functions (Key Ones)

| Function | Purpose |
|---|---|
| `session_start()` | Start or resume a session (must be first line before any HTML) |
| `$_SESSION['key'] = val` | Store data in session |
| `$_SESSION['key']` | Read session data |
| `session_unset()` | Remove all session variables |
| `session_destroy()` | Destroy the session completely |
| `session_id()` | Get current session ID |
| `session_regenerate_id()` | Create new session ID (security) |

### 3. Session Configuration (php.ini)

```ini
session.gc_maxlifetime = 300    ; Session garbage collection (5 minutes)
session.cookie_lifetime = 0     ; Cookie lives until browser closes
session.save_path = "/tmp"      ; Where session files are stored
```

You can also set these in code:
```php
ini_set('session.gc_maxlifetime', 300);
```

### 4. How This Lab Implements Concurrent Session Limiting

The standard PHP `$_SESSION` only tracks ONE session per browser tab.
To track **multiple concurrent sessions across devices**, we need a **custom store**.

**Our approach:**
```
Browser 1 ──────┐
Browser 2 ───── ├──► data/sessions.json  ← tracks all active sessions
Browser 3 ──────┘         per username
```

**data/sessions.json structure:**
```json
{
  "alice": [
    { "session_id": "abc123", "last_active": 1700000000, "ip": "127.0.0.1" },
    { "session_id": "def456", "last_active": 1700000050, "ip": "192.168.1.5" }
  ],
  "bob": [
    { "session_id": "ghi789", "last_active": 1700000100, "ip": "10.0.0.2" }
  ]
}
```

**Algorithm for login:**
```
1. Load sessions.json
2. Remove expired sessions (last_active + 300s < now)
3. Count active sessions for this user
4. If count >= 3 → DENY login
5. Else → Create new session entry, save file, set $_SESSION
```

### 5. Session Expiration
Two levels of expiration:
- **PHP built-in**: `session.gc_maxlifetime` (garbage collection, probabilistic)
- **Custom check** (our lab): We compare `time() - last_active` to 300 seconds

```php
if ((time() - $_SESSION['last_active']) >= 300) {
    session_destroy(); // force logout
}
$_SESSION['last_active'] = time(); // update on every page load
```

### 6. Cookies vs Sessions

| Feature | Cookie | Session |
|---|---|---|
| Storage | Client (browser) | Server |
| Security | Less (visible to user) | More (only ID sent to browser) |
| Expiry | Set by developer | On browser close or timeout |
| Size Limit | ~4KB | No practical limit |
| Use Case | "Remember Me", preferences | Login state, cart, user data |

---

## 🖥️ Expected Output

### Login Page (index.php)
- Form with Username + Password fields
- Live status panel showing active sessions with countdown bars
- Auto-refreshes every 10 seconds

**Successful Login (< 3 sessions):**
```
✅ Login successful! Session created (Session 2 of 3).
→ Redirects to dashboard.php
```

**Denied Login (≥ 3 sessions):**
```
❌ Login denied: Maximum of 3 concurrent sessions reached for user 'alice'.
   Please logout from another device.
```

### Dashboard (dashboard.php)
- Welcome message with Session ID
- Countdown timer (starts at 300 seconds, counts down live)
- List of all active sessions for the user
- "Keep Session Alive" button (AJAX call to keepalive.php)
- Logout button

### Admin Panel (admin.php)
- Stats: Active Users, Total Sessions, Max Per User, Timeout
- Table showing all sessions across all users
- Progress bars for each session's remaining time
- Raw JSON dump of sessions.json

---

## ❓ Likely Viva Questions + Answers

### Basic (Q1–Q10)

**Q1. What is a session in PHP?**
A session is a mechanism to persist user-specific data across multiple HTTP requests. PHP assigns a unique Session ID to each user, stores data server-side (in files/DB), and sends the ID to the browser via a cookie (`PHPSESSID`).

**Q2. What function starts a session in PHP?**
`session_start()`. It must be called before any HTML output and before accessing `$_SESSION`.

**Q3. Where is session data stored by default in PHP?**
In temporary files on the server, usually in `/tmp/` on Linux. The path is controlled by `session.save_path` in php.ini.

**Q4. What is `$_SESSION`?**
It's a PHP superglobal array that stores session variables for the current user. Data persists across page loads until the session expires or is destroyed.

**Q5. How do you destroy a session completely?**
```php
session_start();
session_unset();   // Clear all $_SESSION variables
session_destroy(); // Delete session file from server
```

**Q6. What is the difference between `session_unset()` and `session_destroy()`?**
- `session_unset()`: Clears all variables stored in `$_SESSION` (like emptying a box)
- `session_destroy()`: Deletes the session storage entirely (like destroying the box)
- You typically call both together for a complete logout.

**Q7. How do you set a session timeout of 5 minutes?**
```php
// Method 1: php.ini / ini_set
ini_set('session.gc_maxlifetime', 300);

// Method 2: Manual check (more reliable)
if (isset($_SESSION['last_active']) &&
    (time() - $_SESSION['last_active'] > 300)) {
    session_destroy(); // Expired!
}
$_SESSION['last_active'] = time(); // Update timestamp
```

**Q8. Why is the manual timeout check more reliable than gc_maxlifetime?**
`gc_maxlifetime` uses **garbage collection** which runs probabilistically (not on every request). It depends on `session.gc_probability` and `session.gc_divisor` settings. The manual check is deterministic — it runs every time.

**Q9. What is `PHPSESSID`?**
It's the name of the cookie that PHP sends to the browser to store the Session ID. The browser sends this cookie back with every request so the server can identify the session.

**Q10. What happens if a user deletes their browser cookies?**
The Session ID (`PHPSESSID`) is lost. The user will appear "logged out" even though the session file still exists on the server (until garbage collected).

---

### Intermediate (Q11–Q20)

**Q11. Why can't we just use `$_SESSION` to limit concurrent sessions?**
`$_SESSION` only tracks the session for a single browser connection. If the same user logs in from two different devices, each gets its own `$_SESSION` with no awareness of the other. We need a **shared, server-side store** (like our JSON file or a database) to track ALL sessions for a user simultaneously.

**Q12. How does this lab limit sessions to 3?**
On every login:
1. Load `sessions.json` (our custom store)
2. Purge expired sessions (older than 300 seconds)
3. Count sessions for the username
4. If count >= 3, return error and do NOT create a session
5. If count < 3, add the new session to the JSON

**Q13. What is `session_regenerate_id()` and why is it important?**
It creates a new Session ID while keeping the session data. Used after login to prevent **Session Fixation attacks** — where an attacker pre-sets a known Session ID and waits for a victim to log in with it.

**Q14. What is a Session Fixation attack?**
1. Attacker gets a valid (unauthenticated) Session ID from the server
2. Tricks the victim into using that Session ID (e.g., via URL parameter)
3. Victim logs in → server now associates that known Session ID with the authenticated user
4. Attacker uses the same Session ID to hijack the authenticated session

**Prevention:** Call `session_regenerate_id(true)` immediately after successful login.

**Q15. What is a Session Hijacking attack?**
The attacker steals a valid Session ID (via XSS, packet sniffing, etc.) and uses it to impersonate the authenticated user.

**Prevention:** 
- Use HTTPS (prevents sniffing)
- Check `$_SERVER['REMOTE_ADDR']` matches session's stored IP
- Use `HttpOnly` and `Secure` cookie flags

**Q16. What are `HttpOnly` and `Secure` cookie flags?**
```php
session_set_cookie_params([
    'secure'   => true,    // Cookie only sent over HTTPS
    'httponly' => true,    // JS cannot access cookie (prevents XSS theft)
    'samesite' => 'Strict' // Prevents CSRF
]);
```

**Q17. What is the difference between a session and a cookie?**
| | Session | Cookie |
|---|---|---|
| Storage | Server-side | Client-side (browser) |
| Data limit | No practical limit | ~4 KB |
| Security | More secure | Less secure (user can read/modify) |
| Lifespan | Until destroyed or timeout | Set by `expires` attribute |

**Q18. Can sessions work without cookies?**
Yes! If cookies are disabled, the Session ID can be passed via URL: `page.php?PHPSESSID=abc123`. But this is a security risk (Session ID exposed in URLs, browser history, server logs).

**Q19. What is `bin2hex(random_bytes(16))` used for in this lab?**
It generates a **cryptographically secure random Session ID** (32 hex characters = 128 bits of entropy). This is important because predictable IDs can be guessed by attackers.

**Q20. How does the `keepalive.php` endpoint work?**
The dashboard's JavaScript calls `keepalive.php` via `fetch()` (AJAX) every time the user clicks "Keep Alive". The PHP endpoint updates `$_SESSION['last_active']` and the JSON store's `last_active` timestamp, effectively resetting the 5-minute countdown without a full page reload.

---

### Advanced (Q21–Q28)

**Q21. What are the security implications of storing session data in a JSON file?**
- **Race conditions**: Concurrent requests may overwrite each other (use file locking: `flock()`)
- **File permissions**: Must be readable/writable only by the web server process
- **Disk space**: Many sessions = large file; use a database for production
- **Better alternative**: Use Redis or a database (MySQL) for session storage

**Q22. How would you implement this in a database instead of a JSON file?**
```sql
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    session_id VARCHAR(64) UNIQUE,
    last_active TIMESTAMP,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
```php
// Check count
SELECT COUNT(*) FROM sessions WHERE username = ? AND last_active > NOW() - INTERVAL 5 MINUTE
```

**Q23. What is garbage collection in PHP sessions?**
PHP periodically deletes old session files. The frequency is controlled by:
- `session.gc_probability` (default: 1)
- `session.gc_divisor` (default: 100)
- So GC runs on ~1% of requests
- `session.gc_maxlifetime`: Session files older than this are deleted

**Q24. What is the difference between session timeout and session expiry?**
- **Timeout**: Idle time — session expires if user is inactive for N seconds (our approach)
- **Expiry**: Absolute time — session expires at a fixed time regardless of activity
- **Combination**: Most production apps use both

**Q25. How does HTTPS relate to sessions?**
Without HTTPS, session cookies can be intercepted by a man-in-the-middle attacker (packet sniffing). The attacker captures `PHPSESSID` and can impersonate the user. HTTPS encrypts all traffic, protecting the session cookie.

**Q26. What is CSRF and how do sessions help prevent it?**
**Cross-Site Request Forgery** tricks a logged-in user into unknowingly submitting a malicious request. Sessions alone don't prevent CSRF — you need a **CSRF token**:
```php
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
// Include in every form
// Validate on every POST: $_POST['csrf_token'] === $_SESSION['csrf_token']
```

**Q27. How would you handle the race condition in our JSON-based session store?**
Use PHP file locking:
```php
$fp = fopen('sessions.json', 'r+');
flock($fp, LOCK_EX);        // Exclusive lock
// Read, modify, write
flock($fp, LOCK_UN);        // Release lock
fclose($fp);
```

**Q28. What is Laravel's session management and how does it differ from raw PHP?**
Laravel wraps PHP sessions with a clean API and multiple drivers:
```php
// Store
session(['key' => 'value']);
Session::put('key', 'value');

// Read
session('key');
Session::get('key');

// Drivers: file, cookie, database, redis, memcached, array
// Configured in config/session.php
```
Laravel also handles CSRF tokens automatically and regenerates Session IDs after login.

---

## 🔑 Key Concepts Summary

| Concept | Implementation |
|---|---|
| Max 3 concurrent sessions | Count entries in JSON before allowing login |
| 5-minute timeout | Compare `time() - last_active` to 300 seconds |
| Session persistence | `$_SESSION` superglobal + custom JSON store |
| Session ID | `bin2hex(random_bytes(16))` — 128-bit random token |
| Purge expired | Filter sessions where `last_active + 300 < now` |
| Keep-alive | AJAX call updates `last_active` without page reload |
