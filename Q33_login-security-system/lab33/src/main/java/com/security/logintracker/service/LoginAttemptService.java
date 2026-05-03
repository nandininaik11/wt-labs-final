package com.security.logintracker.service;

import com.security.logintracker.model.User;
import com.security.logintracker.repository.UserRepository;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Service;
import java.time.LocalDateTime;

@Service  // Marks this as a Spring Service (business logic layer)
public class LoginAttemptService {

    // Read values from application.properties
    @Value("${app.security.max-failed-attempts}")
    private int maxFailedAttempts;   // Default: 3

    @Value("${app.security.lock-duration-minutes}")
    private int lockDurationMinutes; // Default: 15

    private final UserRepository userRepository;

    // Constructor injection (best practice over @Autowired on field)
    public LoginAttemptService(UserRepository userRepository) {
        this.userRepository = userRepository;
    }

    /**
     * Called when login FAILS.
     * Increments counter; locks account if threshold reached.
     */
    public void loginFailed(String username) {
        userRepository.findByUsername(username).ifPresent(user -> {
            int newAttempts = user.getFailedAttempts() + 1;
            user.setFailedAttempts(newAttempts);

            if (newAttempts >= maxFailedAttempts) {
                // Lock the account
                user.setAccountNonLocked(false);
                user.setLockTime(LocalDateTime.now()); // record when it was locked
                System.out.println("[SECURITY] Account LOCKED for user: " + username);
            }

            userRepository.save(user); // persist changes to DB
        });
    }

    /**
     * Called when login SUCCEEDS.
     * Resets the failed counter and unlocks.
     */
    public void loginSucceeded(String username) {
        userRepository.findByUsername(username).ifPresent(user -> {
            user.setFailedAttempts(0);          // reset counter
            user.setAccountNonLocked(true);     // ensure unlocked
            user.setLockTime(null);             // clear lock time
            userRepository.save(user);
        });
    }

    /**
     * Checks if a locked account has passed the lock duration.
     * If yes, auto-unlocks and returns true (allow login).
     * If still within lock period, returns false (keep locked).
     */
    public boolean unlockWhenTimeExpired(User user) {
        LocalDateTime lockTime = user.getLockTime();
        if (lockTime == null) return true; // not locked, allow

        LocalDateTime unlockTime = lockTime.plusMinutes(lockDurationMinutes);

        if (LocalDateTime.now().isAfter(unlockTime)) {
            // Lock duration has passed → auto-unlock
            user.setAccountNonLocked(true);
            user.setFailedAttempts(0);
            user.setLockTime(null);
            userRepository.save(user);
            System.out.println("[SECURITY] Account AUTO-UNLOCKED for: " + user.getUsername());
            return true; // allow login attempt
        }

        return false; // still locked
    }

    public int getMaxFailedAttempts() {
        return maxFailedAttempts;
    }

    public int getLockDurationMinutes() {
        return lockDurationMinutes;
    }
}
