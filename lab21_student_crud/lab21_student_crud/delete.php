<?php
/* ============================================================
   delete.php  –  Delete Student Record
   Lab Q21: Core feature — DELETE student from database

   THEORY (Unit III – PHP + MySQL):
   - Receives student ID via GET: delete.php?id=5
   - Runs SQL DELETE query with prepared statement
   - Redirects back to index.php with success/error message
   - This file does NOT display any HTML (it's a handler)
   ============================================================ */

session_start();
require_once __DIR__ . '/includes/config.php';

// ── Step 1: Get student ID from URL ──────────────────────────
// delete.php?id=5 → $_GET['id'] = '5'
// (int) cast: converts string '5' to integer 5 (security)
$id = (int)($_GET['id'] ?? 0);

// Validate: ID must be a positive integer
if ($id <= 0) {
    flash("❌ Invalid student ID.", 'error');
    header('Location: index.php');
    exit; // ALWAYS exit after header redirect
}

// ── Step 2: Fetch student name (to show in success message) ──
// Get name BEFORE deleting so we can show it in the flash message
$stmt = $conn->prepare("SELECT name FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

// If student doesn't exist
if (!$student) {
    flash("❌ Student not found (ID: $id). Already deleted?", 'error');
    header('Location: index.php');
    exit;
}

// ── Step 3: Execute DELETE query ─────────────────────────────
// SQL DELETE: permanently removes the row from the table
// WHERE id = ? : CRITICAL — without WHERE, ALL students would be deleted!
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Check if deletion was successful
if ($stmt->affected_rows === 1) {
    // 1 row deleted = success
    flash("🗑️ Student <strong>{$student['name']}</strong> has been permanently deleted.", 'success');
} else {
    flash("❌ Failed to delete student. Please try again.", 'error');
}

$stmt->close();
$conn->close(); // Close database connection

// ── Step 4: Redirect back to the list ────────────────────────
// PRG Pattern: After DELETE, redirect to GET request
// This prevents re-deletion if user presses F5
header('Location: index.php');
exit;
?>
