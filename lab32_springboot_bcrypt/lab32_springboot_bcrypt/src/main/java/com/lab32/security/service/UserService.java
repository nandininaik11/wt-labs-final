package com.lab32.security.service;

// ================================================================
// UserService.java — Business Logic Layer
// ================================================================
// THEORY:
// The Service layer contains BUSINESS LOGIC.
// It sits between the Controller (web layer) and Repository (DB layer).
//
// Controller → calls → Service → calls → Repository → talks to → Database
//
// Why a separate Service layer?
// - Controllers handle HTTP (request/response)
// - Repositories handle data access (SQL)
// - Services handle BUSINESS RULES (validation, transformation, etc.)
// - Separation of Concerns: each layer has one job
//
// KEY TASK for Lab Q32 — Task 2:
// "Store encrypted passwords in database"
// This happens in the registerUser() method:
//   1. Receive plain password from form
//   2. Encode with BCrypt → hash
//   3. Save HASH to database (never the plain password)
// ================================================================

import com.lab32.security.model.AppUser;
import com.lab32.security.model.RegisterRequest;
import com.lab32.security.repository.AppUserRepository;
import lombok.extern.slf4j.Slf4j;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Slf4j
@Service
public class UserService {

    // Inject dependencies via constructor
    private final AppUserRepository userRepository;
    private final PasswordEncoder passwordEncoder;  // = BCryptPasswordEncoder

    public UserService(AppUserRepository userRepository,
                       PasswordEncoder passwordEncoder) {
        this.userRepository = userRepository;
        this.passwordEncoder = passwordEncoder;
    }

    // ─────────────────────────────────────────────────────────
    // TASK 2: Store encrypted passwords in database
    // registerUser() — Create new user with BCrypt hashed password
    // ─────────────────────────────────────────────────────────
    @Transactional   // If any step fails, rollback ALL DB changes
    public AppUser registerUser(RegisterRequest request) {

        // Check if username already exists
        if (userRepository.existsByUsername(request.getUsername())) {
            throw new IllegalArgumentException(
                    "Username '" + request.getUsername() + "' is already taken.");
        }

        // Check if email already exists
        if (userRepository.existsByEmail(request.getEmail())) {
            throw new IllegalArgumentException(
                    "Email '" + request.getEmail() + "' is already registered.");
        }

        // ── THE CRITICAL STEP: Hash the password with BCrypt ──
        // request.getPassword() = "password123" (plain text from form)
        // passwordEncoder.encode() → BCrypt hash
        // Example output: "$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy"
        //
        // BCrypt hash structure: $2a $ 10 $ [22 char salt][31 char hash]
        //   $2a  = BCrypt version
        //   $10  = cost factor (2^10 rounds)
        //   Next 22 chars = random salt (different every time!)
        //   Remaining 31 chars = actual hash value
        String rawPassword   = request.getPassword();
        String encodedPassword = passwordEncoder.encode(rawPassword);

        log.info("Registering user: {}", request.getUsername());
        log.debug("Plain password: [HIDDEN FOR SECURITY]");
        log.debug("BCrypt hash: {}", encodedPassword);
        // Note: We log the hash but NEVER the plain password

        // Build the AppUser entity with the HASHED password
        AppUser newUser = AppUser.builder()
                .username(request.getUsername())
                .email(request.getEmail())
                .password(encodedPassword)    // ← Store HASH, not plain password!
                .role("ROLE_USER")
                .enabled(true)
                .build();

        // Save to database — JPA generates INSERT SQL
        AppUser savedUser = userRepository.save(newUser);
        log.info("User registered successfully with ID: {}", savedUser.getId());

        return savedUser;
    }

    // ─────────────────────────────────────────────────────────
    // TASK 4: Verify password validation (manual demo method)
    // This demonstrates how BCrypt.matches() works programmatically
    // Spring Security calls this internally during login
    // ─────────────────────────────────────────────────────────
    public boolean verifyPassword(String rawPassword, String encodedPassword) {
        // BCryptPasswordEncoder.matches():
        //   1. Extracts the salt from encodedPassword
        //   2. Re-hashes rawPassword with the SAME salt
        //   3. Compares the two hashes
        //   4. Returns true if they match
        //
        // This works even though the SAME password produces DIFFERENT hashes
        // each time — because the salt is embedded in the stored hash!
        return passwordEncoder.matches(rawPassword, encodedPassword);
    }

    // Get all users (for admin/demo display)
    public List<AppUser> getAllUsers() {
        return userRepository.findAll();
    }

    // Find user by username
    public Optional<AppUser> findByUsername(String username) {
        return userRepository.findByUsername(username);
    }

    // Count total users
    public long getUserCount() {
        return userRepository.count();
    }

    // Demo method: encode a password and return the hash
    // Used in the demo page to show BCrypt in action
    public String encodePasswordDemo(String rawPassword) {
        return passwordEncoder.encode(rawPassword);
    }

    // Demo method: verify a password against a hash
    // Used in the demo page for Task 4 demonstration
    public boolean matchesDemo(String rawPassword, String hash) {
        return passwordEncoder.matches(rawPassword, hash);
    }
}
