<?php
// ============================================================
// THEORY: SERVER-SIDE FORM HANDLING IN PHP
//
// When a form is submitted:
//   - POST method → data goes in HTTP request BODY → $_POST array
//   - GET method  → data goes in URL string      → $_GET array
//   - $_REQUEST   → contains BOTH $_GET and $_POST
//
// PHP Script Flow:
// 1. Receive raw form data
// 2. Sanitize (clean) the data
// 3. Validate the data
// 4. Process (set cookies, sessions, etc.)
// 5. Redirect or show response
// ============================================================

session_start(); // ALWAYS start session before any output

// ============================================================
// STEP 1: DETERMINE REQUEST METHOD
// $_SERVER is a PHP superglobal with server/environment info
// $_SERVER['REQUEST_METHOD'] = 'GET' or 'POST'
// ============================================================
$method = $_SERVER['REQUEST_METHOD']; // Store which method was used

// Choose the correct superglobal based on method
// THEORY: $_POST = data from POST request body (not visible in URL)
//         $_GET  = data from URL query string (visible in URL)
if ($method === 'POST') {
    $data = $_POST;   // Data came via POST body
} else {
    $data = $_GET;    // Data came via URL parameters
}

// ============================================================
// STEP 2: SANITIZE INPUT
// THEORY: Never trust raw user input! Users can type anything.
// filter_input() fetches and sanitizes data from INPUT sources
// FILTER_SANITIZE_SPECIAL_CHARS removes/encodes harmful chars
// trim() removes leading/trailing whitespace
// ============================================================

// isset() checks if array key exists to avoid PHP notices
$name     = isset($data['name'])     ? trim(htmlspecialchars($data['name']))     : '';
$email    = isset($data['email'])    ? trim(htmlspecialchars($data['email']))    : '';
$password = isset($data['password']) ? $data['password']                         : '';
$remember = isset($data['remember']) ? $data['remember']                         : '';

// ============================================================
// STEP 3: VALIDATE (SERVER-SIDE)
// THEORY: Server-side validation is MANDATORY.
//         JavaScript validation can be bypassed by disabling JS!
//         PHP validation runs on the server — impossible to bypass.
// ============================================================
$errors = []; // Array to collect all error messages

// Validate Name: must not be empty
if (empty($name)) {
    $errors[] = "Name is required.";
} elseif (strlen($name) < 2) {
    $errors[] = "Name must be at least 2 characters long.";
}

// Validate Email: PHP has a built-in filter for this!
// FILTER_VALIDATE_EMAIL checks proper email format: user@domain.com
// Returns the email if valid, or FALSE if invalid
if (empty($email)) {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // filter_var with FILTER_VALIDATE_EMAIL validates RFC 5322 format
    $errors[] = "Email format is invalid. Please use format: user@domain.com";
}

// Validate Password: minimum 6 characters
if (empty($password)) {
    $errors[] = "Password is required.";
} elseif (strlen($password) < 6) {
    // strlen() returns the number of characters in a string
    $errors[] = "Password must be at least 6 characters long.";
}

// ============================================================
// STEP 4: IF ERRORS — GO BACK WITH ERROR MESSAGES
// ============================================================
if (!empty($errors)) {
    // Store errors in SESSION to pass to next page
    // THEORY: $_SESSION persists data across page requests on the SERVER
    //         Unlike cookies, session data is NOT stored in the browser
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data']   = ['name' => $name, 'email' => $email]; // Keep form data

    // header() sends HTTP header to browser
    // Location: tells browser to redirect to this URL
    header("Location: index.php?error=1");
    exit(); // IMPORTANT: Always call exit() after header redirect!
}

// ============================================================
// STEP 5: ALL VALID — PROCESS THE FORM
// ============================================================

// --- 5A: SET COOKIE ---
// THEORY: Cookies are small text files stored in the USER'S BROWSER
// setcookie() syntax: setcookie(name, value, expiry_time, path)
// time() = current Unix timestamp (seconds since Jan 1, 1970)
// time() + 30*24*60*60 = current time + 30 days in seconds

if ($remember === 'yes') {
    // Cookie will expire 30 days from now
    $cookie_expiry = time() + (30 * 24 * 60 * 60); // = 2,592,000 seconds
    setcookie("username", $name, $cookie_expiry, "/");
    // "/" means cookie is available for ALL pages on this site
    $cookie_set = true;
} else {
    // If "remember me" not checked, delete the cookie (if it exists)
    // Setting expiry in the PAST deletes the cookie
    setcookie("username", "", time() - 3600, "/");
    $cookie_set = false;
}

// --- 5B: CREATE SESSION ---
// THEORY: Sessions store data on the SERVER with a unique Session ID
// The Session ID is sent to browser as a cookie: PHPSESSID=abc123...
// PHP automatically handles storing and retrieving session data
// Sessions expire when browser closes (default) or after timeout

$_SESSION['username']    = $name;           // Store name in session
$_SESSION['email']       = $email;          // Store email in session
$_SESSION['login_time']  = date('Y-m-d H:i:s'); // Store login timestamp
$_SESSION['method_used'] = $method;         // Store which HTTP method was used
$_SESSION['logged_in']   = true;            // Login flag

// --- 5C: STORE PROCESSING INFO for display on dashboard ---
$_SESSION['cookie_was_set'] = $cookie_set;
$_SESSION['password_hash']  = password_hash($password, PASSWORD_BCRYPT);
// password_hash() creates a secure one-way hash — never store plain passwords!

// --- 5D: REDIRECT TO DASHBOARD ---
header("Location: dashboard.php");
exit();
?>
