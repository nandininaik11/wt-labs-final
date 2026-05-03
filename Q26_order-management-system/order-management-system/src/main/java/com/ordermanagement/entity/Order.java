package com.ordermanagement.entity;

// =============================================================================
// IMPORTS SECTION
// Importing necessary classes and annotations from Java and Spring frameworks
// =============================================================================

// Jakarta Persistence API (formerly javax.persistence) - JPA annotations
import jakarta.persistence.*; // * means import all from this package

// Lombok annotations - reduces boilerplate code
import lombok.AllArgsConstructor;  // Generates constructor with all fields
import lombok.Data;                 // Generates getters, setters, toString, equals, hashCode
import lombok.NoArgsConstructor;    // Generates constructor with no arguments

// Bean Validation annotations - for input validation
import jakarta.validation.constraints.NotBlank;  // Validates string is not null or empty
import jakarta.validation.constraints.NotNull;   // Validates field is not null
import jakarta.validation.constraints.Positive;  // Validates number is positive
import jakarta.validation.constraints.DecimalMin; // Validates decimal minimum value

// Java time API - for date/time handling
import java.time.LocalDateTime;

// Math precision for currency values
import java.math.BigDecimal;

/**
 * =============================================================================
 * ORDER ENTITY CLASS
 * =============================================================================
 * 
 * This class represents an Order in the database
 * 
 * @Entity annotation marks this class as a JPA entity (database table)
 * Each instance of this class represents one row in the "orders" table
 * 
 * THEORY CONCEPTS:
 * 1. Entity: A lightweight persistent domain object
 * 2. ORM (Object-Relational Mapping): Maps Java objects to database tables
 * 3. JPA: Java standard for ORM, Hibernate is the implementation
 * 
 * Lombok annotations reduce code:
 * - @Data: Auto-generates getters, setters, toString, equals, hashCode
 * - @NoArgsConstructor: Creates empty constructor: new Order()
 * - @AllArgsConstructor: Creates constructor with all fields: new Order(id, customerName, ...)
 */

@Entity  // JPA annotation - marks this class as a database entity
@Table(name = "orders")  // Specifies the table name in database (optional, defaults to class name)
@Data  // Lombok: generates getters, setters, toString, equals, hashCode
@NoArgsConstructor  // Lombok: generates no-argument constructor
@AllArgsConstructor // Lombok: generates constructor with all fields
public class Order {

    // =========================================================================
    // PRIMARY KEY FIELD
    // =========================================================================
    
    /**
     * id: Primary key of the table
     * 
     * @Id - Marks this field as the primary key
     * @GeneratedValue - Specifies how the primary key should be generated
     * 
     * GENERATION STRATEGIES:
     * - AUTO: JPA provider (Hibernate) chooses the strategy
     * - IDENTITY: Database auto-increment (MySQL, PostgreSQL)
     * - SEQUENCE: Database sequence (Oracle, PostgreSQL)
     * - TABLE: Uses a separate table to generate IDs
     * 
     * IDENTITY strategy: Database automatically generates ID when row is inserted
     */
    @Id  // Marks this field as primary key
    @GeneratedValue(strategy = GenerationType.IDENTITY)  // Auto-increment strategy
    @Column(name = "order_id")  // Column name in database (optional)
    private Long id;  // Long type for ID (can handle large numbers, allows null before save)

    // =========================================================================
    // CUSTOMER INFORMATION FIELDS
    // =========================================================================
    
    /**
     * customerName: Name of the customer who placed the order
     * 
     * @NotBlank - Bean Validation annotation
     * Validates that:
     * - Field is not null
     * - String is not empty ("")
     * - String contains at least one non-whitespace character
     * 
     * message: Custom error message shown when validation fails
     * 
     * @Column:
     * - name: Column name in database
     * - nullable: false means NOT NULL constraint in database
     * - length: maximum string length (creates VARCHAR(100))
     */
    @NotBlank(message = "Customer name is required")  // Validation: must not be blank
    @Column(name = "customer_name", nullable = false, length = 100)
    private String customerName;
    
    /**
     * customerEmail: Email address of the customer
     * 
     * Validation ensures valid email format
     * unique = true: Creates UNIQUE constraint in database (no duplicate emails)
     */
    @NotBlank(message = "Customer email is required")
    @Column(name = "customer_email", nullable = false, unique = true, length = 100)
    private String customerEmail;

    // =========================================================================
    // ORDER DETAILS FIELDS
    // =========================================================================
    
    /**
     * productName: Name of the product ordered
     */
    @NotBlank(message = "Product name is required")
    @Column(name = "product_name", nullable = false, length = 200)
    private String productName;
    
    /**
     * quantity: Number of items ordered
     * 
     * @Positive - Validates that the number is greater than 0
     * Integer type: whole numbers only
     */
    @NotNull(message = "Quantity is required")
    @Positive(message = "Quantity must be positive")
    @Column(name = "quantity", nullable = false)
    private Integer quantity;
    
    /**
     * price: Price per unit of the product
     * 
     * BigDecimal: Best type for money/currency (avoids floating-point errors)
     * Example: 2.0 - 1.1 = 0.89999... (double/float issue)
     *          2.0 - 1.1 = 0.9 (BigDecimal correct)
     * 
     * @DecimalMin: Ensures price is at least 0.01
     * precision = 10: Total number of digits (e.g., 12345678.90 = 10 digits)
     * scale = 2: Number of digits after decimal point (e.g., .90 = 2 digits)
     */
    @NotNull(message = "Price is required")
    @DecimalMin(value = "0.01", message = "Price must be greater than 0")
    @Column(name = "price", nullable = false, precision = 10, scale = 2)
    private BigDecimal price;
    
    /**
     * totalAmount: Total cost of the order (quantity * price)
     * 
     * This could be calculated automatically, but storing it improves query performance
     */
    @Column(name = "total_amount", precision = 10, scale = 2)
    private BigDecimal totalAmount;

    // =========================================================================
    // ORDER STATUS FIELD
    // =========================================================================
    
    /**
     * orderStatus: Current status of the order
     * 
     * @Enumerated(EnumType.STRING) - Stores enum as string in database
     * Alternative: EnumType.ORDINAL stores enum position (0, 1, 2...)
     * 
     * STRING is better because:
     * - More readable in database
     * - Adding new enum values doesn't break existing data
     */
    @Enumerated(EnumType.STRING)  // Store enum as string ("PENDING", "CONFIRMED", etc.)
    @Column(name = "order_status", nullable = false, length = 20)
    private OrderStatus orderStatus;
    
    /**
     * OrderStatus Enum: Defines possible order states
     * 
     * Enum (Enumeration): A special data type with predefined constant values
     * Benefits:
     * - Type safety: Can't assign invalid status
     * - Auto-completion in IDE
     * - Self-documenting code
     */
    public enum OrderStatus {
        PENDING,      // Order received but not processed
        CONFIRMED,    // Order confirmed by seller
        SHIPPED,      // Order dispatched for delivery
        DELIVERED,    // Order delivered to customer
        CANCELLED     // Order cancelled
    }

    // =========================================================================
    // TIMESTAMP FIELDS (AUDIT FIELDS)
    // =========================================================================
    
    /**
     * createdAt: When the order was created
     * 
     * LocalDateTime: Java 8+ date-time API (better than old Date class)
     * Format: 2024-01-15T10:30:45
     * 
     * updatable = false: This field cannot be changed after creation
     */
    @Column(name = "created_at", nullable = false, updatable = false)
    private LocalDateTime createdAt;
    
    /**
     * updatedAt: When the order was last modified
     */
    @Column(name = "updated_at")
    private LocalDateTime updatedAt;

    // =========================================================================
    // JPA LIFECYCLE CALLBACKS
    // =========================================================================
    
    /**
     * @PrePersist: Called before entity is saved to database for first time
     * 
     * LIFECYCLE EVENTS:
     * - @PrePersist: Before INSERT
     * - @PostPersist: After INSERT
     * - @PreUpdate: Before UPDATE
     * - @PostUpdate: After UPDATE
     * - @PreRemove: Before DELETE
     * - @PostRemove: After DELETE
     * 
     * This method automatically sets createdAt and initializes fields
     */
    @PrePersist  // Executed before entity is persisted (saved) to database
    protected void onCreate() {
        // Set creation timestamp to current time
        createdAt = LocalDateTime.now();
        updatedAt = LocalDateTime.now();
        
        // Set default status if not specified
        if (orderStatus == null) {
            orderStatus = OrderStatus.PENDING;
        }
        
        // Calculate total amount if not set
        if (totalAmount == null && price != null && quantity != null) {
            // BigDecimal.valueOf converts int to BigDecimal
            // multiply() multiplies two BigDecimal values
            totalAmount = price.multiply(BigDecimal.valueOf(quantity));
        }
    }
    
    /**
     * @PreUpdate: Called before entity is updated in database
     * 
     * This method automatically updates the updatedAt timestamp
     */
    @PreUpdate  // Executed before entity is updated in database
    protected void onUpdate() {
        // Update the last modified timestamp
        updatedAt = LocalDateTime.now();
        
        // Recalculate total amount on update
        if (price != null && quantity != null) {
            totalAmount = price.multiply(BigDecimal.valueOf(quantity));
        }
    }
}

/**
 * =============================================================================
 * THEORY SUMMARY - ORDER ENTITY
 * =============================================================================
 * 
 * KEY CONCEPTS:
 * 
 * 1. JPA ENTITY:
 *    - Java class mapped to database table
 *    - Each instance = one database row
 *    - @Entity annotation makes it persistent
 * 
 * 2. PRIMARY KEY:
 *    - Uniquely identifies each record
 *    - @Id marks the primary key field
 *    - @GeneratedValue for auto-increment
 * 
 * 3. COLUMNS:
 *    - Class fields map to table columns
 *    - @Column annotation for customization
 *    - Constraints: nullable, unique, length
 * 
 * 4. BEAN VALIDATION:
 *    - @NotNull, @NotBlank, @Positive, etc.
 *    - Validates data before database operations
 *    - Prevents invalid data from being saved
 * 
 * 5. LOMBOK:
 *    - Reduces boilerplate code
 *    - @Data generates getters/setters
 *    - @NoArgsConstructor, @AllArgsConstructor for constructors
 * 
 * 6. LIFECYCLE CALLBACKS:
 *    - @PrePersist, @PreUpdate
 *    - Automatic timestamp management
 *    - Business logic before save/update
 * 
 * 7. ENUMS:
 *    - Type-safe constants
 *    - @Enumerated for database storage
 *    - STRING vs ORDINAL storage
 * 
 * =============================================================================
 */
