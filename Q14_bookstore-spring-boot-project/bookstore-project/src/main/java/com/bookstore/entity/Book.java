package com.bookstore.entity;

import jakarta.persistence.*;
import jakarta.validation.constraints.*;
import lombok.*;

import java.math.BigDecimal;  // For precise decimal numbers (prices)
import java.time.LocalDate;   // For publication dates

/**
 * BOOK ENTITY CLASS
 * =================
 * Represents the 'books' table in database
 * Stores all information about books available in the bookstore
 */

@Data                    // Generates getters, setters, toString, equals, hashCode
@NoArgsConstructor       // Generates no-argument constructor
@AllArgsConstructor      // Generates constructor with all fields
@Entity                  // Marks as JPA entity
@Table(name = "books")   // Maps to 'books' table
public class Book {
    
    /*
     * PRIMARY KEY
     * -----------
     */
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "book_id")
    private Long id;
    
    
    /*
     * BOOK TITLE
     * ----------
     */
    @Column(nullable = false, length = 200)
    @NotBlank(message = "Book title is required")
    @Size(max = 200, message = "Title cannot exceed 200 characters")
    private String title;
    
    
    /*
     * AUTHOR NAME
     * -----------
     */
    @Column(nullable = false, length = 100)
    @NotBlank(message = "Author name is required")
    @Size(max = 100, message = "Author name cannot exceed 100 characters")
    private String author;
    
    
    /*
     * ISBN (International Standard Book Number)
     * ------------------------------------------
     * Unique identifier for books
     * Format: ISBN-13 (13 digits) or ISBN-10 (10 digits)
     * Example: 978-0-13-601970-1
     */
    @Column(unique = true, length = 20)
    
    /*
     * ISBN pattern validation:
     * - Can contain digits, hyphens, and spaces
     * - Typically 10-17 characters (with formatting)
     */
    @Pattern(regexp = "^[0-9\\-\\s]{10,17}$", 
             message = "Please provide a valid ISBN number")
    private String isbn;
    
    
    /*
     * BOOK DESCRIPTION
     * ----------------
     * Longer text field for book summary/description
     */
    
    /*
     * @Column(columnDefinition = "TEXT")
     * - TEXT type in MySQL can store up to 65,535 characters
     * - Better than VARCHAR for long descriptions
     * - Stored separately from the table data (more efficient)
     */
    @Column(columnDefinition = "TEXT")
    @Size(max = 2000, message = "Description cannot exceed 2000 characters")
    private String description;
    
    
    /*
     * BOOK CATEGORY/GENRE
     * -------------------
     * Examples: Fiction, Non-Fiction, Science, Technology, etc.
     */
    @Column(length = 50)
    @NotBlank(message = "Category is required")
    private String category;
    
    
    /*
     * PUBLISHER NAME
     * --------------
     */
    @Column(length = 100)
    private String publisher;
    
    
    /*
     * PUBLICATION DATE
     * ----------------
     * When the book was published
     * LocalDate stores only date (no time component)
     * Format: YYYY-MM-DD (e.g., 2024-01-15)
     */
    @Column(name = "publication_date")
    private LocalDate publicationDate;
    
    
    /*
     * BOOK PRICE
     * ----------
     * Using BigDecimal for monetary values
     * 
     * Why BigDecimal instead of double?
     * ---------------------------------
     * double: 19.99 might be stored as 19.989999... (floating point error)
     * BigDecimal: Always precise, perfect for money calculations
     * 
     * precision = 10: Total digits (including decimals)
     * scale = 2: Digits after decimal point
     * Example: 12345678.90 (8 digits before, 2 after)
     */
    @Column(nullable = false, precision = 10, scale = 2)
    
    /*
     * @NotNull vs @NotBlank:
     * - @NotNull: Just checks if value exists (works for numbers)
     * - @NotBlank: Checks for non-empty strings (works for text)
     */
    @NotNull(message = "Price is required")
    
    /*
     * @DecimalMin: Minimum value allowed
     * value = "0.01": Price must be at least 1 cent
     * This prevents negative or zero prices
     */
    @DecimalMin(value = "0.01", message = "Price must be greater than 0")
    private BigDecimal price;
    
    
    /*
     * STOCK QUANTITY
     * --------------
     * Number of copies available in inventory
     */
    @Column(nullable = false)
    
    /*
     * @Min: Minimum value for integer
     * Stock cannot be negative
     */
    @Min(value = 0, message = "Stock quantity cannot be negative")
    private Integer stockQuantity = 0;  // Default: 0 (out of stock)
    
    
    /*
     * BOOK COVER IMAGE URL
     * --------------------
     * Path or URL to the book cover image
     * Example: /images/books/book-cover-1.jpg
     */
    @Column(name = "image_url", length = 500)
    private String imageUrl;
    
    
    /*
     * NUMBER OF PAGES
     * ---------------
     */
    @Column(name = "page_count")
    @Min(value = 1, message = "Page count must be at least 1")
    private Integer pageCount;
    
    
    /*
     * BOOK LANGUAGE
     * -------------
     */
    @Column(length = 50)
    private String language = "English";  // Default language
    
    
    /*
     * AVAILABILITY STATUS
     * -------------------
     * true = book is available for purchase
     * false = book is discontinued or unavailable
     */
    @Column(nullable = false)
    private Boolean available = true;  // Default: available
    
    
    /*
     * RATING
     * ------
     * Average customer rating (0.0 to 5.0)
     * Example: 4.5 stars
     */
    @Column(precision = 2, scale = 1)
    
    /*
     * @DecimalMin and @DecimalMax:
     * Ensures rating is between 0.0 and 5.0
     */
    @DecimalMin(value = "0.0", message = "Rating must be between 0 and 5")
    @DecimalMax(value = "5.0", message = "Rating must be between 0 and 5")
    private BigDecimal rating;
    
    
    /*
     * CREATED DATE
     * ------------
     * When this book entry was added to the system
     */
    @Column(name = "created_date", nullable = false, updatable = false)
    private LocalDate createdDate;
    
    
    /*
     * @PrePersist - Lifecycle callback
     * Called automatically before saving new entity to database
     */
    @PrePersist
    protected void onCreate() {
        // Set creation date to today
        createdDate = LocalDate.now();
        
        // Set default values if not provided
        if (stockQuantity == null) {
            stockQuantity = 0;
        }
        
        if (available == null) {
            available = true;
        }
        
        if (language == null || language.isEmpty()) {
            language = "English";
        }
    }
    
    
    /*
     * CUSTOM METHOD: Check if book is in stock
     * -----------------------------------------
     * Business logic method (not a database column)
     * Returns true if at least one copy is available
     */
    public boolean isInStock() {
        return stockQuantity != null && stockQuantity > 0;
    }
    
    
    /*
     * CUSTOM METHOD: Get formatted price
     * -----------------------------------
     * Returns price with currency symbol
     * Example: ₹499.00
     */
    public String getFormattedPrice() {
        if (price == null) {
            return "₹0.00";
        }
        return "₹" + price.toString();
    }
    
    
    /*
     * THEORY: BigDecimal Best Practices
     * ----------------------------------
     * 
     * 1. Creation:
     *    BigDecimal price = new BigDecimal("19.99");  // ✓ Correct (String)
     *    BigDecimal price = new BigDecimal(19.99);    // ✗ Wrong (double)
     * 
     * 2. Arithmetic:
     *    price.add(new BigDecimal("5.00"))       // Addition
     *    price.subtract(new BigDecimal("2.00"))  // Subtraction
     *    price.multiply(new BigDecimal("2"))     // Multiplication
     *    price.divide(new BigDecimal("2"))       // Division
     * 
     * 3. Comparison:
     *    price.compareTo(otherPrice) == 0  // Equal
     *    price.compareTo(otherPrice) > 0   // Greater than
     *    price.compareTo(otherPrice) < 0   // Less than
     * 
     * NOTE: Never use == to compare BigDecimal values!
     * 
     * 
     * THEORY: Entity Relationships
     * ----------------------------
     * 
     * In a real bookstore, you might have relationships like:
     * 
     * @OneToMany:
     *   One Book → Many Reviews
     *   One Author → Many Books
     * 
     * @ManyToOne:
     *   Many Books → One Publisher
     *   Many Reviews → One Book
     * 
     * @ManyToMany:
     *   Many Books ↔ Many Categories
     *   Many Books ↔ Many Orders
     * 
     * Example:
     * @OneToMany(mappedBy = "book")
     * private List<Review> reviews;
     * 
     * This creates a one-to-many relationship between Book and Review
     */
}
