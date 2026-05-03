<?php
/**
 * SessionManager.php
 * Manages concurrent sessions per user with a max limit of 3
 * and a session expiration timeout of 5 minutes (300 seconds).
 */

class SessionManager {
    const MAX_SESSIONS   = 3;          // Maximum concurrent sessions per user
    const SESSION_TIMEOUT = 300;        // 5 minutes in seconds
    const SESSIONS_FILE  = __DIR__ . '/../data/sessions.json';

    /**
     * Load all active sessions from the JSON store.
     */
    public static function loadSessions(): array {
        if (!file_exists(self::SESSIONS_FILE)) {
            return [];
        }
        $content = file_get_contents(self::SESSIONS_FILE);
        return json_decode($content, true) ?? [];
    }

    /**
     * Save sessions back to the JSON store.
     */
    public static function saveSessions(array $sessions): void {
        $dir = dirname(self::SESSIONS_FILE);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents(self::SESSIONS_FILE, json_encode($sessions, JSON_PRETTY_PRINT));
    }

    /**
     * Remove expired sessions for ALL users.
     */
    public static function purgeExpired(array &$sessions): void {
        $now = time();
        foreach ($sessions as $username => &$userSessions) {
            $userSessions = array_filter($userSessions, function($s) use ($now) {
                return ($now - $s['last_active']) < self::SESSION_TIMEOUT;
            });
            $userSessions = array_values($userSessions); // re-index
        }
        // Remove users with no sessions left
        $sessions = array_filter($sessions, fn($s) => count($s) > 0);
    }

    /**
     * Attempt to create a new session for a user.
     * Returns ['success' => bool, 'message' => string, 'session_id' => string|null]
     */
    public static function createSession(string $username): array {
        $sessions = self::loadSessions();
        self::purgeExpired($sessions);

        $userSessions = $sessions[$username] ?? [];

        if (count($userSessions) >= self::MAX_SESSIONS) {
            self::saveSessions($sessions);
            return [
                'success'    => false,
                'message'    => "❌ Login denied: Maximum of " . self::MAX_SESSIONS .
                                " concurrent sessions reached for user '$username'. " .
                                "Please logout from another device.",
                'session_id' => null,
                'count'      => count($userSessions),
            ];
        }

        // Create a unique session ID
        $sessionId = bin2hex(random_bytes(16));

        $userSessions[] = [
            'session_id'  => $sessionId,
            'created_at'  => time(),
            'last_active' => time(),
            'ip'          => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        ];

        $sessions[$username] = $userSessions;
        self::saveSessions($sessions);

        // Store in PHP session
        session_start();
        $_SESSION['username']   = $username;
        $_SESSION['session_id'] = $sessionId;
        $_SESSION['last_active'] = time();

        return [
            'success'    => true,
            'message'    => "✅ Login successful! Session created (Session " .
                            count($userSessions) . " of " . self::MAX_SESSIONS . ").",
            'session_id' => $sessionId,
            'count'      => count($userSessions),
        ];
    }

    /**
     * Destroy a specific session for a user.
     */
    public static function destroySession(string $username, string $sessionId): void {
        $sessions = self::loadSessions();

        if (isset($sessions[$username])) {
            $sessions[$username] = array_values(array_filter(
                $sessions[$username],
                fn($s) => $s['session_id'] !== $sessionId
            ));
            if (empty($sessions[$username])) {
                unset($sessions[$username]);
            }
        }

        self::saveSessions($sessions);

        // Destroy PHP session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

    /**
     * Update last_active timestamp to keep session alive.
     */
    public static function touch(string $username, string $sessionId): bool {
        $sessions = self::loadSessions();
        self::purgeExpired($sessions);

        $found = false;
        if (isset($sessions[$username])) {
            foreach ($sessions[$username] as &$s) {
                if ($s['session_id'] === $sessionId) {
                    $s['last_active'] = time();
                    $found = true;
                    break;
                }
            }
        }

        self::saveSessions($sessions);
        return $found;
    }

    /**
     * Get all active sessions for a user.
     */
    public static function getUserSessions(string $username): array {
        $sessions = self::loadSessions();
        self::purgeExpired($sessions);
        self::saveSessions($sessions);
        return $sessions[$username] ?? [];
    }

    /**
     * Get all sessions (for admin/debug view).
     */
    public static function getAllSessions(): array {
        $sessions = self::loadSessions();
        self::purgeExpired($sessions);
        self::saveSessions($sessions);
        return $sessions;
    }
}
?>
