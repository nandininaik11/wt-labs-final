package com.bookstore.repository;

/*
 * IMPORTS
 */
import com.bookstore.entity.User;  // User entity class
import org.springframework.data.jpa.repository.JpaRepository;  // JPA repository interface
import org.springframework.stereotype.Repository;  // Repository annotation

import java.util.Optional;  // Optional wrapper for nullable return values

/**
 * USER REPOSITORY INTERFACE
 * ==========================
 * This interface provides database operations for User entity
 * Spring Data JPA automatically implements this interface at runtime
 * No need to write implementation code!
 */

/*
 * @Repository Annotation
 * ----------------------
 * - Marks this as a Spring Data repository
 * - Enables exception translation (converts SQL exceptions to Spring exceptions)
 * - Makes it a Spring bean (can be @Autowired in other classes)
 */
@Repository

/*
 * EXTENDING JpaRepository
 * -----------------------
 * JpaRepository<User, Long>
 *   - User: The entity type this repository manages
 *   - Long: The type of the entity's primary key (id field)
 * 
 * By extending JpaRepository, we automatically get these methods:
 * 
 * CRUD Operations:
 * ----------------
 * save(User user)              - Insert or update user
 * findById(Long id)            - Find user by ID
 * findAll()                    - Get all users
 * deleteById(Long id)          - Delete user by ID
 * delete(User user)            - Delete specific user
 * count()                      - Count total users
 * existsById(Long id)          - Check if user exists
 * 
 * Pagination & Sorting:
 * ---------------------
 * findAll(Pageable pageable)   - Get users with pagination
 * findAll(Sort sort)           - Get sorted users
 * 
 * Batch Operations:
 * -----------------
 * saveAll(List<User> users)    - Save multiple users
 * deleteAll()                  - Delete all users
 */
public interface UserRepository extends JpaRepository<User, Long> {
    
    /*
     * CUSTOM QUERY METHODS
     * --------------------
     * Spring Data JPA creates implementations automatically
     * based on method names!
     * 
     * Method Naming Convention:
     * findBy + FieldName + Condition
     */
    
    
    /**
     * FIND USER BY USERNAME
     * ---------------------
     * Generated SQL: SELECT * FROM users WHERE username = ?
     * 
     * @param username - The username to search for
     * @return Optional<User> - User if found, empty Optional if not found
     * 
     * Why Optional?
     * - Avoids NullPointerException
     * - Explicit handling of "not found" case
     * - Modern Java best practice
     * 
     * Usage:
     * Optional<User> userOpt = userRepository.findByUsername("john");
     * if (userOpt.isPresent()) {
     *     User user = userOpt.get();
     *     // ... use user
     * } else {
     *     // User not found
     * }
     */
    Optional<User> findByUsername(String username);
    
    
    /**
     * FIND USER BY EMAIL
     * ------------------
     * Generated SQL: SELECT * FROM users WHERE email = ?
     * 
     * @param email - The email address to search for
     * @return Optional<User> - User if found, empty Optional if not found
     * 
     * Use case: Login with email, password reset, etc.
     */
    Optional<User> findByEmail(String email);
    
    
    /**
     * CHECK IF USERNAME EXISTS
     * ------------------------
     * Generated SQL: SELECT COUNT(*) > 0 FROM users WHERE username = ?
     * 
     * @param username - Username to check
     * @return boolean - true if exists, false otherwise
     * 
     * More efficient than findByUsername() when you only need
     * to check existence (doesn't load the entire entity)
     * 
     * Use case: Registration validation (check if username is taken)
     */
    boolean existsByUsername(String username);
    
    
    /**
     * CHECK IF EMAIL EXISTS
     * ---------------------
     * Generated SQL: SELECT COUNT(*) > 0 FROM users WHERE email = ?
     * 
     * @param email - Email to check
     * @return boolean - true if exists, false otherwise
     * 
     * Use case: Registration validation (check if email is already registered)
     */
    boolean existsByEmail(String email);
    
    
    /**
     * FIND USER BY USERNAME AND PASSWORD
     * -----------------------------------
     * Generated SQL: SELECT * FROM users WHERE username = ? AND password = ?
     * 
     * @param username - Username
     * @param password - Password (should be hashed)
     * @return Optional<User> - User if credentials match
     * 
     * NOTE: In production, NEVER store plain text passwords!
     * This is for demonstration. Real apps use BCrypt or similar.
     * 
     * Better approach: Use Spring Security with password encoding
     */
    Optional<User> findByUsernameAndPassword(String username, String password);
    
    
    /*
     * THEORY: Spring Data JPA Query Methods
     * --------------------------------------
     * 
     * Spring Data JPA creates query implementations automatically
     * based on method names. Here are the patterns:
     * 
     * 1. FIND METHODS:
     *    findBy<FieldName>                  → Find by single field
     *    findBy<Field1>And<Field2>          → Find by multiple fields (AND)
     *    findBy<Field1>Or<Field2>           → Find by multiple fields (OR)
     * 
     * 2. EXISTS METHODS:
     *    existsBy<FieldName>                → Check if exists
     * 
     * 3. COUNT METHODS:
     *    countBy<FieldName>                 → Count matching records
     * 
     * 4. DELETE METHODS:
     *    deleteBy<FieldName>                → Delete matching records
     * 
     * 5. COMPARISON OPERATORS:
     *    findBy<Field>LessThan              → Less than (<)
     *    findBy<Field>GreaterThan           → Greater than (>)
     *    findBy<Field>Between               → Between two values
     *    findBy<Field>Like                  → Like (SQL LIKE operator)
     *    findBy<Field>In                    → In a collection
     *    findBy<Field>IsNull                → Is NULL
     *    findBy<Field>IsNotNull             → Is NOT NULL
     * 
     * Examples:
     * findByAgeGreaterThan(int age)
     * findByEmailLike(String pattern)
     * findByCategoryIn(List<String> categories)
     * 
     * 
     * THEORY: JpaRepository vs CrudRepository
     * ----------------------------------------
     * 
     * CrudRepository:
     *   - Basic CRUD operations
     *   - No pagination or sorting
     * 
     * PagingAndSortingRepository:
     *   - Extends CrudRepository
     *   - Adds pagination and sorting
     * 
     * JpaRepository:
     *   - Extends PagingAndSortingRepository
     *   - Adds JPA-specific methods (flush, batch operations)
     *   - Most feature-rich, recommended for most cases
     * 
     * Hierarchy:
     * JpaRepository → PagingAndSortingRepository → CrudRepository
     * 
     * 
     * THEORY: How Spring Data JPA Works
     * ----------------------------------
     * 
     * 1. AT COMPILE TIME:
     *    - Spring scans for interfaces extending JpaRepository
     *    - Creates proxy implementations automatically
     * 
     * 2. AT RUNTIME:
     *    - Method names are parsed
     *    - SQL queries are generated
     *    - Hibernate executes the queries
     *    - Results are mapped to entity objects
     * 
     * 3. EXAMPLE FLOW:
     *    userRepository.findByUsername("john")
     *    ↓
     *    Spring Data JPA parses method name
     *    ↓
     *    Generates: SELECT * FROM users WHERE username = ?
     *    ↓
     *    Hibernate executes query
     *    ↓
     *    Result is mapped to User object
     *    ↓
     *    Returns Optional<User>
     */
}
