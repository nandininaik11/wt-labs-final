package com.bookstore.entity;

/*
 * IMPORTS
 * -------
 * JPA annotations for mapping Java classes to database tables
 */
import jakarta.persistence.*;  // JPA annotations (@Entity, @Table, @Id, etc.)
import jakarta.validation.constraints.*;  // Validation annotations
import lombok.*;  // Lombok annotations to reduce boilerplate code

import java.time.LocalDateTime;  // For storing registration date/time

/**
 * USER ENTITY CLASS
 * =================
 * This class represents the 'users' table in the database
 * Each instance of this class corresponds to one row in the table
 * JPA (Java Persistence API) will automatically create/update the table
 */

/*
 * LOMBOK ANNOTATIONS
 * ------------------
 * These generate code automatically at compile time:
 * 
 * @Data - Generates:
 *   - Getters for all fields
 *   - Setters for all non-final fields
 *   - toString() method
 *   - equals() and hashCode() methods
 * 
 * @NoArgsConstructor - Generates a no-argument constructor
 *   Required by JPA for entity instantiation
 * 
 * @AllArgsConstructor - Generates a constructor with all fields
 *   Useful for creating objects with all values at once
 */
@Data
@NoArgsConstructor
@AllArgsConstructor

/*
 * JPA ANNOTATIONS
 * ---------------
 */

/*
 * @Entity - Marks this class as a JPA entity
 * JPA will manage the lifecycle of objects of this class
 * It will map this class to a database table
 */
@Entity

/*
 * @Table - Specifies table details
 * name = "users" - The table name in database will be 'users'
 * If not specified, default table name would be 'user' (lowercase class name)
 */
@Table(name = "users")
public class User {
    
    /*
     * PRIMARY KEY FIELD
     * -----------------
     */
    
    /*
     * @Id - Marks this field as the primary key
     * Primary key uniquely identifies each row in the table
     */
    @Id
    
    /*
     * @GeneratedValue - Specifies how primary key is generated
     * strategy = GenerationType.IDENTITY:
     *   - Database auto-generates the value (AUTO_INCREMENT in MySQL)
     *   - Each new user gets next available ID (1, 2, 3, ...)
     */
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    
    /*
     * @Column - Specifies column details
     * name = "user_id" - Column name in database
     */
    @Column(name = "user_id")
    private Long id;  // Long is used for IDs (can handle large numbers)
    
    
    /*
     * USERNAME FIELD
     * --------------
     */
    
    /*
     * @Column configuration:
     * - nullable = false: This field cannot be NULL (required)
     * - unique = true: No two users can have same username
     * - length = 50: Maximum 50 characters allowed
     */
    @Column(nullable = false, unique = true, length = 50)
    
    /*
     * VALIDATION ANNOTATIONS
     * ----------------------
     * These work with Spring Validation framework
     * They validate data BEFORE it's saved to database
     */
    
    /*
     * @NotBlank - Ensures the field is not null, empty, or whitespace
     * message = shown to user if validation fails
     */
    @NotBlank(message = "Username is required")
    
    /*
     * @Size - Restricts the length of the string
     * min = minimum characters, max = maximum characters
     */
    @Size(min = 3, max = 50, message = "Username must be between 3 and 50 characters")
    private String username;
    
    
    /*
     * EMAIL FIELD
     * -----------
     */
    @Column(nullable = false, unique = true, length = 100)
    
    @NotBlank(message = "Email is required")
    
    /*
     * @Email - Validates email format (must contain @, domain, etc.)
     * Uses regex pattern to check email structure
     */
    @Email(message = "Please provide a valid email address")
    private String email;
    
    
    /*
     * PASSWORD FIELD
     * --------------
     */
    @Column(nullable = false)
    
    @NotBlank(message = "Password is required")
    
    /*
     * Password should be at least 6 characters
     * NOTE: In real applications, password should be hashed (encrypted)
     * We'll use BCrypt in the service layer
     */
    @Size(min = 6, message = "Password must be at least 6 characters")
    private String password;
    
    
    /*
     * FULL NAME FIELD
     * ---------------
     */
    @Column(name = "full_name", length = 100)
    
    @NotBlank(message = "Full name is required")
    private String fullName;
    
    
    /*
     * PHONE NUMBER FIELD
     * ------------------
     */
    @Column(name = "phone_number", length = 15)
    
    /*
     * @Pattern - Validates against regex pattern
     * This pattern allows: +91-1234567890 or 1234567890 format
     * \\d{10} = exactly 10 digits
     */
    @Pattern(regexp = "^(\\+\\d{1,3}[- ]?)?\\d{10}$", 
             message = "Please provide a valid phone number")
    private String phoneNumber;
    
    
    /*
     * ADDRESS FIELD
     * -------------
     */
    @Column(length = 500)  // Longer text for address
    private String address;
    
    
    /*
     * REGISTRATION DATE FIELD
     * -----------------------
     */
    @Column(name = "registration_date", nullable = false, updatable = false)
    
    /*
     * updatable = false: This field cannot be changed after creation
     * Once registered, registration date is permanent
     */
    private LocalDateTime registrationDate;
    
    
    /*
     * ENABLED FLAG
     * ------------
     * Used to activate/deactivate user accounts
     * If enabled = false, user cannot login
     */
    @Column(nullable = false)
    private Boolean enabled = true;  // Default: account is active
    
    
    /*
     * ROLE FIELD
     * ----------
     * Determines user permissions (ROLE_USER, ROLE_ADMIN, etc.)
     * Used by Spring Security for authorization
     */
    @Column(nullable = false)
    private String role = "ROLE_USER";  // Default: regular user
    
    
    /*
     * @PrePersist LIFECYCLE CALLBACK
     * ------------------------------
     * This method is automatically called BEFORE saving to database
     * Used to set default values
     */
    @PrePersist
    protected void onCreate() {
        // Set registration date to current date/time
        registrationDate = LocalDateTime.now();
        
        // Ensure enabled flag is set (if not already)
        if (enabled == null) {
            enabled = true;
        }
        
        // Ensure role is set (if not already)
        if (role == null || role.isEmpty()) {
            role = "ROLE_USER";
        }
    }
    
    /*
     * THEORY: Entity Lifecycle
     * -------------------------
     * 
     * 1. NEW (Transient):
     *    User user = new User(); // Object created, not in database
     * 
     * 2. MANAGED (Persistent):
     *    userRepository.save(user); // Now tracked by JPA
     *    Any changes are automatically saved to database
     * 
     * 3. DETACHED:
     *    After transaction ends, object is detached
     *    Changes are not automatically saved
     * 
     * 4. REMOVED:
     *    userRepository.delete(user); // Marked for deletion
     *    Will be deleted from database at end of transaction
     * 
     * 
     * THEORY: JPA vs Hibernate
     * -------------------------
     * 
     * JPA (Java Persistence API):
     *   - It's a SPECIFICATION (set of rules/interfaces)
     *   - Defines how ORM should work in Java
     *   - Provides annotations like @Entity, @Table, @Id
     * 
     * Hibernate:
     *   - It's an IMPLEMENTATION of JPA
     *   - Actual code that makes JPA work
     *   - Generates SQL statements
     *   - Manages database connections
     *   - Handles caching and lazy loading
     * 
     * Think of it like:
     *   JPA = Interface (contract)
     *   Hibernate = Implementation (actual code)
     */
}
