<?php
// ============================================================
// CLEAR_COOKIE.PHP
// THEORY: To delete a cookie, set its expiry to a PAST timestamp
// The browser will see it's expired and remove it automatically
// Setting expiry to time() - 3600 means "1 hour ago" = expired
// ============================================================

// Delete the 'username' cookie by setting past expiry
// Parameters: (name, value, expiry, path)
setcookie("username", "", time() - 3600, "/");
// "" = empty value, time()-3600 = past timestamp (expired)
// "/" = applies to entire website

// Redirect back to login page with a message
header("Location: index.php?msg=cookie_cleared");
exit();
?>
