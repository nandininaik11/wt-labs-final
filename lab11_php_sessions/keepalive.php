<?php
/**
 * keepalive.php – Called by JS to refresh session timer
 */
require_once __DIR__ . '/includes/SessionManager.php';
session_start();

header('Content-Type: application/json');

$username  = $_SESSION['username']  ?? null;
$sessionId = $_SESSION['session_id'] ?? null;

if ($username && $sessionId) {
    $_SESSION['last_active'] = time();
    SessionManager::touch($username, $sessionId);
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Not logged in']);
}
