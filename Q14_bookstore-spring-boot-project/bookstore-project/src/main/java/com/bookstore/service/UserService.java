package com.bookstore.service;

import com.bookstore.entity.User;
import com.bookstore.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

/**
 * USER SERVICE CLASS
 * ==================
 * Business logic layer for User operations
 * Sits between Controller (presentation) and Repository (data access)
 * Contains all business rules and validations
 */

/*
 * @Service Annotation
 * ------------------
 * - Marks this as a Spring Service component
 * - Spring creates a single instance (singleton) of this class
 * - Can be @Autowired in controllers
 * - Indicates this class contains business logic
 */
@Service

/*
 * @Transactional Annotation
 * -------------------------
 * - Makes all public methods transactional
 * - Transaction = A group of database operations that either:
 *   • ALL succeed (COMMIT), or
 *   • ALL fail and rollback (ROLLBACK)
 * 
 * Example: User registration
 *   1. Save user to database
 *   2. Send welcome email
 *   3. Create user profile
 *   
 *   If step 2 fails, step 1 is rolled back (no half-registered user)
 * 
 * ACID Properties:
 *   A - Atomicity: All or nothing
 *   C - Consistency: Database remains in valid state
 *   I - Isolation: Concurrent transactions don't interfere
 *   D - Durability: Committed data is permanent
 */
@Transactional
public class UserService {
    
    /*
     * DEPENDENCY INJECTION
     * --------------------
     * Instead of creating objects manually:
     *   UserRepository repo = new UserRepositoryImpl(); // ✗ Wrong
     * 
     * We let Spring inject dependencies:
     *   @Autowired
     *   private UserRepository userRepository; // ✓ Correct
     * 
     * Benefits:
     * - Loose coupling (easy to swap implementations)
     * - Easy testing (can inject mock objects)
     * - Spring manages object lifecycle
     */
    
    /*
     * @Autowired Annotation
     * --------------------
     * - Spring automatically injects the dependency
     * - Finds the bean of type UserRepository
     * - Injects it into this field
     * 
     * Three types of injection:
     * 1. Field Injection (used here - simple but less testable)
     * 2. Constructor Injection (recommended for required dependencies)
     * 3. Setter Injection (for optional dependencies)
     */
    @Autowired
    private UserRepository userRepository;
    
    
    /*
     * PASSWORD ENCODER
     * ----------------
     * Used for encrypting passwords
     * Never store plain text passwords!
     * 
     * Spring Security provides:
     * - BCryptPasswordEncoder (most common, recommended)
     * - Pbkdf2PasswordEncoder
     * - SCryptPasswordEncoder
     * 
     * BCrypt features:
     * - One-way encryption (cannot decrypt)
     * - Automatic salt generation
     * - Configurable strength
     * 
     * This will be configured in SecurityConfig class
     */
    @Autowired
    private PasswordEncoder passwordEncoder;
    
    
    /*
     * ============================
     * USER REGISTRATION
     * ============================
     */
    
    /**
     * REGISTER NEW USER
     * -----------------
     * Creates a new user account
     * 
     * @param user - User object with registration data
     * @return Saved user object (with generated ID)
     * @throws RuntimeException if username/email already exists
     * 
     * Steps:
     * 1. Check if username already exists
     * 2. Check if email already exists
     * 3. Encrypt the password
     * 4. Save to database
     */
    public User registerUser(User user) {
        // Validation: Check if username already taken
        if (userRepository.existsByUsername(user.getUsername())) {
            throw new RuntimeException("Username already exists!");
        }
        
        // Validation: Check if email already registered
        if (userRepository.existsByEmail(user.getEmail())) {
            throw new RuntimeException("Email already registered!");
        }
        
        // Encrypt password using BCrypt
        // Original: "password123"
        // Encrypted: "$2a$10$N9qo8uL..." (60 characters)
        String encryptedPassword = passwordEncoder.encode(user.getPassword());
        user.setPassword(encryptedPassword);
        
        // Set default role if not set
        if (user.getRole() == null || user.getRole().isEmpty()) {
            user.setRole("ROLE_USER");
        }
        
        // Enable account by default
        if (user.getEnabled() == null) {
            user.setEnabled(true);
        }
        
        // Save to database and return
        // JPA automatically generates ID and sets registrationDate
        return userRepository.save(user);
    }
    
    
    /*
     * ============================
     * USER AUTHENTICATION
     * ============================
     */
    
    /**
     * AUTHENTICATE USER (Login)
     * -------------------------
     * Validates username and password
     * 
     * @param username - User's username
     * @param password - User's password (plain text)
     * @return User object if authentication successful
     * @throws RuntimeException if authentication fails
     * 
     * Process:
     * 1. Find user by username
     * 2. Check if user exists
     * 3. Verify password using BCrypt
     * 4. Check if account is enabled
     */
    public User authenticate(String username, String password) {
        // Find user by username
        Optional<User> userOpt = userRepository.findByUsername(username);
        
        // Check if user exists
        if (!userOpt.isPresent()) {
            throw new RuntimeException("Invalid username or password!");
        }
        
        User user = userOpt.get();
        
        // Verify password
        // passwordEncoder.matches() compares plain text with encrypted
        // It re-encrypts the plain text and compares with stored hash
        if (!passwordEncoder.matches(password, user.getPassword())) {
            throw new RuntimeException("Invalid username or password!");
        }
        
        // Check if account is enabled
        if (!user.getEnabled()) {
            throw new RuntimeException("Account is disabled!");
        }
        
        return user;
    }
    
    
    /*
     * ============================
     * USER RETRIEVAL
     * ============================
     */
    
    /**
     * GET USER BY ID
     * --------------
     * @param id - User ID
     * @return Optional<User> - User if found
     */
    public Optional<User> getUserById(Long id) {
        return userRepository.findById(id);
    }
    
    
    /**
     * GET USER BY USERNAME
     * --------------------
     * @param username - Username to search
     * @return Optional<User> - User if found
     */
    public Optional<User> getUserByUsername(String username) {
        return userRepository.findByUsername(username);
    }
    
    
    /**
     * GET USER BY EMAIL
     * -----------------
     * @param email - Email to search
     * @return Optional<User> - User if found
     */
    public Optional<User> getUserByEmail(String email) {
        return userRepository.findByEmail(email);
    }
    
    
    /**
     * GET ALL USERS
     * -------------
     * @return List of all users
     * 
     * NOTE: In production, use pagination for large datasets
     */
    public List<User> getAllUsers() {
        return userRepository.findAll();
    }
    
    
    /*
     * ============================
     * USER UPDATE
     * ============================
     */
    
    /**
     * UPDATE USER PROFILE
     * -------------------
     * Updates user information
     * 
     * @param id - User ID
     * @param updatedUser - User object with new data
     * @return Updated user
     * @throws RuntimeException if user not found
     */
    public User updateUser(Long id, User updatedUser) {
        // Find existing user
        User existingUser = userRepository.findById(id)
            .orElseThrow(() -> new RuntimeException("User not found!"));
        
        // Update fields (only if not null/empty)
        if (updatedUser.getFullName() != null && !updatedUser.getFullName().isEmpty()) {
            existingUser.setFullName(updatedUser.getFullName());
        }
        
        if (updatedUser.getEmail() != null && !updatedUser.getEmail().isEmpty()) {
            // Check if new email is already taken by another user
            Optional<User> userWithEmail = userRepository.findByEmail(updatedUser.getEmail());
            if (userWithEmail.isPresent() && !userWithEmail.get().getId().equals(id)) {
                throw new RuntimeException("Email already in use!");
            }
            existingUser.setEmail(updatedUser.getEmail());
        }
        
        if (updatedUser.getPhoneNumber() != null) {
            existingUser.setPhoneNumber(updatedUser.getPhoneNumber());
        }
        
        if (updatedUser.getAddress() != null) {
            existingUser.setAddress(updatedUser.getAddress());
        }
        
        // Save and return
        return userRepository.save(existingUser);
    }
    
    
    /**
     * CHANGE PASSWORD
     * ---------------
     * @param userId - User ID
     * @param oldPassword - Current password
     * @param newPassword - New password
     * @throws RuntimeException if old password is incorrect
     */
    public void changePassword(Long userId, String oldPassword, String newPassword) {
        User user = userRepository.findById(userId)
            .orElseThrow(() -> new RuntimeException("User not found!"));
        
        // Verify old password
        if (!passwordEncoder.matches(oldPassword, user.getPassword())) {
            throw new RuntimeException("Current password is incorrect!");
        }
        
        // Validate new password
        if (newPassword == null || newPassword.length() < 6) {
            throw new RuntimeException("New password must be at least 6 characters!");
        }
        
        // Encrypt and save new password
        user.setPassword(passwordEncoder.encode(newPassword));
        userRepository.save(user);
    }
    
    
    /*
     * ============================
     * USER DELETION
     * ============================
     */
    
    /**
     * DELETE USER
     * -----------
     * @param id - User ID to delete
     */
    public void deleteUser(Long id) {
        userRepository.deleteById(id);
    }
    
    
    /*
     * THEORY: Service Layer Pattern
     * ------------------------------
     * 
     * WHY USE SERVICE LAYER?
     * 
     * Without Service Layer (Bad):
     * Controller → Repository → Database
     * - Business logic in controller (violates Single Responsibility)
     * - Code duplication across controllers
     * - Hard to test
     * - No transaction management
     * 
     * With Service Layer (Good):
     * Controller → Service → Repository → Database
     * - Clean separation of concerns
     * - Reusable business logic
     * - Easy to test (can mock repository)
     * - Centralized transaction management
     * 
     * 
     * THEORY: Exception Handling
     * ---------------------------
     * 
     * Current approach: throw RuntimeException
     * This is simple but not ideal
     * 
     * Better approach: Custom exceptions
     * 
     * public class UserAlreadyExistsException extends RuntimeException {
     *     public UserAlreadyExistsException(String message) {
     *         super(message);
     *     }
     * }
     * 
     * Then in controller:
     * @ExceptionHandler(UserAlreadyExistsException.class)
     * public ResponseEntity<?> handleUserExists(UserAlreadyExistsException ex) {
     *     return ResponseEntity.badRequest().body(ex.getMessage());
     * }
     * 
     * 
     * THEORY: Password Encryption
     * ----------------------------
     * 
     * BCrypt Algorithm:
     * 1. Generate random salt (29 characters)
     * 2. Combine password + salt
     * 3. Hash using Blowfish cipher
     * 4. Result: $2a$10$N9qo8uLOickgx2ZMRZoMye...
     * 
     * Format: $2a$[cost]$[salt][hash]
     *   $2a = BCrypt version
     *   $10 = Cost factor (2^10 = 1024 iterations)
     *   Next 22 chars = Salt
     *   Remaining = Hash
     * 
     * Same password → Different hashes (due to random salt)
     * password123 → $2a$10$abc...
     * password123 → $2a$10$xyz...
     * 
     * Verification:
     * 1. Extract salt from stored hash
     * 2. Hash input password with same salt
     * 3. Compare hashes
     */
}
