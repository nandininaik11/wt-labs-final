package com.ordermanagement.repository;

// =============================================================================
// IMPORTS SECTION
// =============================================================================

// Spring Data JPA repository interface
import org.springframework.data.jpa.repository.JpaRepository;

// Spring stereotype annotation
import org.springframework.stereotype.Repository;

// Our entity class
import com.ordermanagement.entity.Order;
import com.ordermanagement.entity.Order.OrderStatus;

// Java utility classes
import java.util.List;           // For returning multiple results
import java.util.Optional;       // For single result that might not exist

/**
 * =============================================================================
 * ORDER REPOSITORY INTERFACE
 * =============================================================================
 * 
 * This interface provides database operations for Order entity
 * 
 * REPOSITORY PATTERN:
 * - Abstraction layer between business logic and data access
 * - Hides database implementation details
 * - Makes code testable (can mock repository)
 * 
 * SPRING DATA JPA:
 * - Eliminates need to write SQL queries
 * - Auto-implements CRUD methods at runtime
 * - Just declare interface, Spring creates implementation
 * 
 * THEORY CONCEPTS:
 * 
 * 1. JpaRepository Interface:
 *    - Extends PagingAndSortingRepository and CrudRepository
 *    - Provides methods for CRUD operations
 *    - Automatic implementation by Spring (no code needed!)
 * 
 * 2. Generic Types <Order, Long>:
 *    - Order: The entity type this repository manages
 *    - Long: The type of the entity's primary key (@Id field)
 * 
 * 3. Built-in Methods (provided by JpaRepository):
 *    - save(entity): Insert or update
 *    - findById(id): Find by primary key
 *    - findAll(): Get all records
 *    - deleteById(id): Delete by primary key
 *    - count(): Count total records
 *    - existsById(id): Check if exists
 *    + many more...
 * 
 * 4. Custom Query Methods:
 *    - Method names follow naming convention
 *    - Spring auto-generates queries from method names
 *    - Example: findByCustomerName -> SELECT * FROM orders WHERE customer_name = ?
 * 
 * METHOD NAMING CONVENTION:
 * - findBy: SELECT query
 * - countBy: COUNT query
 * - deleteBy: DELETE query
 * - Keywords: And, Or, Between, LessThan, GreaterThan, Like, etc.
 */

@Repository  // Spring annotation - marks this as a Data Access Object (DAO)
             // @Repository enables exception translation (converts DB exceptions to Spring exceptions)
public interface OrderRepository extends JpaRepository<Order, Long> {
    
    /**
     * NO CODE NEEDED FOR BASIC CRUD!
     * 
     * JpaRepository provides these methods automatically:
     * 
     * 1. save(Order order) - Insert or update order
     * 2. findById(Long id) - Find order by ID
     * 3. findAll() - Get all orders
     * 4. deleteById(Long id) - Delete order by ID
     * 5. count() - Count total orders
     * 6. existsById(Long id) - Check if order exists
     * 
     * These methods are inherited and implemented automatically by Spring Data JPA
     */

    // =========================================================================
    // CUSTOM QUERY METHODS
    // =========================================================================
    
    /**
     * Find orders by customer name
     * 
     * METHOD NAME PARSING:
     * - findBy: SELECT query
     * - CustomerName: Field name in Order entity (camelCase)
     * 
     * GENERATED SQL:
     * SELECT * FROM orders WHERE customer_name = ?
     * 
     * @param customerName - The customer name to search for
     * @return List of orders for that customer (empty list if none found)
     * 
     * THEORY: Query Derivation
     * - Spring Data JPA parses method name
     * - Generates SQL query automatically
     * - No @Query annotation needed for simple queries
     */
    List<Order> findByCustomerName(String customerName);
    
    /**
     * Find orders by customer email
     * 
     * GENERATED SQL:
     * SELECT * FROM orders WHERE customer_email = ?
     * 
     * @param email - The customer email to search for
     * @return Optional containing order if found, empty Optional if not found
     * 
     * Optional vs List:
     * - Optional: Use when expecting 0 or 1 result
     * - List: Use when expecting 0 or more results
     */
    Optional<Order> findByCustomerEmail(String email);
    
    /**
     * Find orders by status
     * 
     * GENERATED SQL:
     * SELECT * FROM orders WHERE order_status = ?
     * 
     * @param status - OrderStatus enum value (PENDING, CONFIRMED, etc.)
     * @return List of orders with that status
     * 
     * ENUM HANDLING:
     * - Spring Data JPA automatically converts enum to string
     * - Matches with VARCHAR column in database
     */
    List<Order> findByOrderStatus(OrderStatus status);
    
    /**
     * Find orders by product name
     * 
     * CASE-INSENSITIVE SEARCH using IgnoreCase keyword
     * 
     * GENERATED SQL:
     * SELECT * FROM orders WHERE UPPER(product_name) = UPPER(?)
     * 
     * @param productName - Product name to search (case doesn't matter)
     * @return List of orders for that product
     * 
     * KEYWORDS SUPPORTED:
     * - IgnoreCase: Case-insensitive comparison
     * - Containing: LIKE %value%
     * - StartingWith: LIKE value%
     * - EndingWith: LIKE %value
     */
    List<Order> findByProductNameIgnoreCase(String productName);
    
    /**
     * Find orders by customer name containing a substring
     * 
     * PARTIAL MATCH using Containing keyword
     * 
     * GENERATED SQL:
     * SELECT * FROM orders WHERE customer_name LIKE %?%
     * 
     * @param name - Part of customer name
     * @return List of orders where customer name contains this text
     * 
     * EXAMPLE:
     * findByCustomerNameContaining("John")
     * Matches: "John Doe", "Johnny", "John Smith", "Mary Johnson"
     */
    List<Order> findByCustomerNameContaining(String name);
    
    /**
     * Find orders where quantity is greater than specified value
     * 
     * COMPARISON using GreaterThan keyword
     * 
     * GENERATED SQL:
     * SELECT * FROM orders WHERE quantity > ?
     * 
     * @param quantity - Minimum quantity (exclusive)
     * @return List of orders with quantity greater than this value
     * 
     * OTHER COMPARISON KEYWORDS:
     * - LessThan, LessThanEqual
     * - GreaterThan, GreaterThanEqual
     * - Between
     */
    List<Order> findByQuantityGreaterThan(Integer quantity);
    
    /**
     * Find orders by customer name AND order status
     * 
     * MULTIPLE CONDITIONS using And keyword
     * 
     * GENERATED SQL:
     * SELECT * FROM orders WHERE customer_name = ? AND order_status = ?
     * 
     * @param customerName - Customer name to match
     * @param status - Order status to match
     * @return List of orders matching BOTH conditions
     * 
     * LOGICAL OPERATORS:
     * - And: Both conditions must be true
     * - Or: Either condition can be true
     * 
     * EXAMPLE:
     * findByCustomerNameAndOrderStatus("John Doe", OrderStatus.PENDING)
     */
    List<Order> findByCustomerNameAndOrderStatus(String customerName, OrderStatus status);
    
    /**
     * Count orders by order status
     * 
     * COUNT QUERY using countBy prefix
     * 
     * GENERATED SQL:
     * SELECT COUNT(*) FROM orders WHERE order_status = ?
     * 
     * @param status - Order status to count
     * @return Number of orders with that status
     * 
     * RETURN TYPES:
     * - long: Returns count as primitive long
     * - Long: Returns count as Long object (can be null)
     */
    long countByOrderStatus(OrderStatus status);
    
    /**
     * Check if orders exist for a customer email
     * 
     * EXISTS QUERY using existsBy prefix
     * 
     * GENERATED SQL:
     * SELECT COUNT(*) > 0 FROM orders WHERE customer_email = ?
     * 
     * @param email - Customer email to check
     * @return true if orders exist, false otherwise
     * 
     * EFFICIENCY:
     * - More efficient than findBy().isEmpty()
     * - Database stops searching after first match
     */
    boolean existsByCustomerEmail(String email);

    /**
     * Delete orders by customer name
     * 
     * DELETE QUERY using deleteBy prefix
     * 
     * GENERATED SQL:
     * DELETE FROM orders WHERE customer_name = ?
     * 
     * @param customerName - Customer name to delete orders for
     * @return Number of orders deleted
     * 
     * WARNING:
     * - This deletes all matching orders!
     * - Use with caution in production
     * - Consider soft delete (status flag) instead
     */
    long deleteByCustomerName(String customerName);

}

/**
 * =============================================================================
 * THEORY SUMMARY - REPOSITORY PATTERN & SPRING DATA JPA
 * =============================================================================
 * 
 * 1. REPOSITORY PATTERN:
 *    - Design pattern for data access abstraction
 *    - Separates business logic from data access logic
 *    - Makes code more maintainable and testable
 * 
 * 2. SPRING DATA JPA:
 *    - Part of Spring Data project
 *    - Reduces boilerplate code for data access
 *    - Auto-implements repository interfaces at runtime
 * 
 * 3. JpaRepository HIERARCHY:
 *    JpaRepository (most features)
 *      └─ PagingAndSortingRepository (pagination & sorting)
 *          └─ CrudRepository (basic CRUD)
 *              └─ Repository (marker interface)
 * 
 * 4. QUERY DERIVATION:
 *    - Spring parses method names
 *    - Generates queries automatically
 *    - Follows naming convention
 * 
 * 5. METHOD NAMING KEYWORDS:
 *    Prefixes: find, get, query, read, count, delete, exists
 *    Conditions: By, And, Or, Between, Like, Containing
 *    Comparisons: LessThan, GreaterThan, Equal
 *    Modifiers: IgnoreCase, OrderBy, Top, First
 * 
 * 6. RETURN TYPES:
 *    - Single: Optional<T>, T (can throw exception if not found)
 *    - Multiple: List<T>, Set<T>, Stream<T>
 *    - Count: long, Long
 *    - Exists: boolean
 * 
 * 7. NO SQL NEEDED:
 *    - Spring Data JPA generates SQL
 *    - Works with any JPA-supported database
 *    - Database-independent code
 * 
 * 8. ADVANTAGES:
 *    - Less code to write
 *    - Type-safe queries
 *    - Compile-time checking
 *    - Easy to test with mocks
 *    - Consistent error handling
 * 
 * =============================================================================
 * 
 * EXAMPLE USAGE IN SERVICE CLASS:
 * 
 * @Autowired
 * private OrderRepository orderRepository;
 * 
 * // Save order
 * Order order = new Order();
 * orderRepository.save(order);
 * 
 * // Find by ID
 * Optional<Order> order = orderRepository.findById(1L);
 * 
 * // Find all
 * List<Order> orders = orderRepository.findAll();
 * 
 * // Custom query
 * List<Order> pendingOrders = orderRepository.findByOrderStatus(OrderStatus.PENDING);
 * 
 * =============================================================================
 */
