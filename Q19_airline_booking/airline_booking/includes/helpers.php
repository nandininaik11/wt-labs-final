<?php
// ============================================================
// includes/helpers.php — Reusable Helper Functions
// DRY principle: Don't Repeat Yourself — define once, call anywhere
// ============================================================

// ── Generate a unique PNR (Passenger Name Record) ──
// PNR = the booking reference code (like "AX7K2P")
// strtoupper() = converts to uppercase letters
// substr(str_shuffle(...)) = randomly picks 6 chars from the alphabet+digits
function generatePNR() {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // no confusing 0,O,1,I
    return strtoupper(substr(str_shuffle($chars), 0, 6)); // random 6-char string
}

// ── Format price as Indian Rupee ──
// number_format(num, decimals, decimal_sep, thousands_sep)
function formatPrice($amount) {
    return '₹' . number_format($amount, 2);
}

// ── Sanitize user input ──
// htmlspecialchars() prevents XSS (Cross-Site Scripting) attacks
// trim() removes whitespace from both ends
function clean($str) {
    return htmlspecialchars(trim($str));
}

// ── Validate Indian phone number ──
// preg_match() uses regular expressions to match patterns
// ^[6-9] means starts with 6,7,8, or 9 (Indian mobile)
// \d{9}$ means followed by exactly 9 more digits = 10 total
function validPhone($phone) {
    return preg_match('/^[6-9]\d{9}$/', $phone);
}

// ── Get seat class label with emoji ──
function seatClassLabel($class) {
    return $class === 'Business' ? '💼 Business' : '🪑 Economy';
}

// ── Get available seat count for a flight ──
function availableSeats($pdo, $flightId) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM seats WHERE flight_id=? AND is_booked=0");
    $stmt->execute([$flightId]);
    return $stmt->fetchColumn(); // fetchColumn() returns single value
}
?>
