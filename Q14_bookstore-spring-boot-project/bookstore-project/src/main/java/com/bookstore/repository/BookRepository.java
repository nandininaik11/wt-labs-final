package com.bookstore.repository;

import com.bookstore.entity.Book;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.math.BigDecimal;
import java.util.List;
import java.util.Optional;

/**
 * BOOK REPOSITORY INTERFACE
 * ==========================
 * Provides database operations for Book entity
 * Spring Data JPA implements this automatically
 */

@Repository
public interface BookRepository extends JpaRepository<Book, Long> {
    
    /*
     * ============================================
     * BASIC FIND METHODS (Generated Automatically)
     * ============================================
     */
    
    /**
     * FIND BOOKS BY TITLE
     * -------------------
     * Exact match (case-sensitive)
     * SQL: SELECT * FROM books WHERE title = ?
     */
    List<Book> findByTitle(String title);
    
    
    /**
     * FIND BOOKS BY TITLE (Case-Insensitive, Partial Match)
     * ------------------------------------------------------
     * Uses SQL LIKE operator with % wildcards
     * SQL: SELECT * FROM books WHERE LOWER(title) LIKE LOWER(?)
     * 
     * @param title - Part of the title to search
     * @return List of books whose title contains the search term
     * 
     * Example: findByTitleContainingIgnoreCase("java")
     * Matches: "Java Programming", "Advanced Java", "JAVA Guide"
     */
    List<Book> findByTitleContainingIgnoreCase(String title);
    
    
    /**
     * FIND BOOKS BY AUTHOR
     * --------------------
     * Partial match, case-insensitive
     * SQL: SELECT * FROM books WHERE LOWER(author) LIKE LOWER(?)
     * 
     * Example: findByAuthorContainingIgnoreCase("martin")
     * Matches: "Robert Martin", "George R.R. Martin"
     */
    List<Book> findByAuthorContainingIgnoreCase(String author);
    
    
    /**
     * FIND BOOKS BY CATEGORY
     * ----------------------
     * Exact match
     * SQL: SELECT * FROM books WHERE category = ?
     * 
     * Use case: Show all books in "Fiction" category
     */
    List<Book> findByCategory(String category);
    
    
    /**
     * FIND BOOK BY ISBN
     * -----------------
     * ISBN is unique, so returns single book
     * SQL: SELECT * FROM books WHERE isbn = ?
     */
    Optional<Book> findByIsbn(String isbn);
    
    
    /**
     * FIND AVAILABLE BOOKS
     * --------------------
     * Only books marked as available
     * SQL: SELECT * FROM books WHERE available = true
     * 
     * Use case: Show only books that can be purchased
     */
    List<Book> findByAvailable(Boolean available);
    
    
    /*
     * ============================================
     * PRICE-BASED QUERIES
     * ============================================
     */
    
    /**
     * FIND BOOKS BY PRICE RANGE
     * -------------------------
     * SQL: SELECT * FROM books WHERE price BETWEEN ? AND ?
     * 
     * @param minPrice - Minimum price
     * @param maxPrice - Maximum price
     * @return Books in the price range
     * 
     * Example: findByPriceBetween(100, 500)
     * Returns books priced between ₹100 and ₹500
     */
    List<Book> findByPriceBetween(BigDecimal minPrice, BigDecimal maxPrice);
    
    
    /**
     * FIND BOOKS CHEAPER THAN
     * -----------------------
     * SQL: SELECT * FROM books WHERE price < ?
     */
    List<Book> findByPriceLessThan(BigDecimal price);
    
    
    /**
     * FIND BOOKS MORE EXPENSIVE THAN
     * ------------------------------
     * SQL: SELECT * FROM books WHERE price > ?
     */
    List<Book> findByPriceGreaterThan(BigDecimal price);
    
    
    /*
     * ============================================
     * STOCK-BASED QUERIES
     * ============================================
     */
    
    /**
     * FIND BOOKS IN STOCK
     * -------------------
     * Books with at least one copy available
     * SQL: SELECT * FROM books WHERE stock_quantity > 0
     */
    List<Book> findByStockQuantityGreaterThan(Integer quantity);
    
    
    /**
     * FIND OUT-OF-STOCK BOOKS
     * -----------------------
     * SQL: SELECT * FROM books WHERE stock_quantity = 0
     */
    List<Book> findByStockQuantity(Integer quantity);
    
    
    /*
     * ============================================
     * COMPLEX QUERIES WITH MULTIPLE CONDITIONS
     * ============================================
     */
    
    /**
     * FIND AVAILABLE BOOKS BY CATEGORY
     * --------------------------------
     * SQL: SELECT * FROM books WHERE category = ? AND available = true
     * 
     * Use case: Show available books in a specific category
     */
    List<Book> findByCategoryAndAvailable(String category, Boolean available);
    
    
    /**
     * FIND BOOKS BY CATEGORY AND PRICE RANGE
     * --------------------------------------
     * SQL: SELECT * FROM books WHERE category = ? 
     *      AND price BETWEEN ? AND ?
     * 
     * Use case: "Show me Fiction books under ₹500"
     */
    List<Book> findByCategoryAndPriceBetween(
        String category, 
        BigDecimal minPrice, 
        BigDecimal maxPrice
    );
    
    
    /*
     * ============================================
     * CUSTOM JPQL QUERIES
     * ============================================
     * JPQL (Java Persistence Query Language)
     * - Similar to SQL but works with entity objects
     * - Database-independent
     */
    
    /**
     * SEARCH BOOKS (Title OR Author)
     * ------------------------------
     * Custom JPQL query to search in multiple fields
     * 
     * @Query annotation defines custom query
     * :keyword is a named parameter (replaced by @Param value)
     * 
     * JPQL: Works with entity names (Book) not table names (books)
     * LOWER() - Makes search case-insensitive
     * LIKE - Partial matching with wildcards
     */
    @Query("SELECT b FROM Book b WHERE " +
           "LOWER(b.title) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(b.author) LIKE LOWER(CONCAT('%', :keyword, '%'))")
    List<Book> searchBooks(@Param("keyword") String keyword);
    
    
    /**
     * FIND TOP-RATED BOOKS
     * --------------------
     * Books with rating >= specified value
     * Sorted by rating in descending order
     * 
     * ORDER BY - Sorts results
     * DESC - Descending order (highest first)
     */
    @Query("SELECT b FROM Book b WHERE b.rating >= :minRating " +
           "ORDER BY b.rating DESC")
    List<Book> findTopRatedBooks(@Param("minRating") BigDecimal minRating);
    
    
    /**
     * GET DISTINCT CATEGORIES
     * -----------------------
     * Returns list of unique categories
     * 
     * DISTINCT - Removes duplicates
     * 
     * Use case: Populate category dropdown/filter
     */
    @Query("SELECT DISTINCT b.category FROM Book b ORDER BY b.category")
    List<String> findDistinctCategories();
    
    
    /**
     * COUNT BOOKS BY CATEGORY
     * -----------------------
     * Native SQL query (not JPQL)
     * 
     * @Query(nativeQuery = true) - Uses actual SQL
     * GROUP BY - Groups results by category
     * COUNT(*) - Counts books in each category
     * 
     * Returns: List of Object[] where:
     *   Object[0] = category name (String)
     *   Object[1] = count (Long)
     */
    @Query(value = "SELECT category, COUNT(*) FROM books " +
                   "GROUP BY category ORDER BY COUNT(*) DESC", 
           nativeQuery = true)
    List<Object[]> countBooksByCategory();
    
    
    /*
     * THEORY: JPQL vs Native SQL
     * ---------------------------
     * 
     * JPQL (Java Persistence Query Language):
     * - Uses entity and field names
     * - Database-independent
     * - Example: "SELECT b FROM Book b WHERE b.price > 100"
     * - Recommended for most cases
     * 
     * Native SQL:
     * - Uses actual table and column names
     * - Database-specific
     * - Example: "SELECT * FROM books WHERE price > 100"
     * - Use when JPQL can't express the query
     * 
     * 
     * THEORY: Query Method Keywords
     * ------------------------------
     * 
     * LOGICAL OPERATORS:
     * And        → WHERE field1 = ? AND field2 = ?
     * Or         → WHERE field1 = ? OR field2 = ?
     * 
     * COMPARISON:
     * LessThan           → WHERE field < ?
     * LessThanEqual      → WHERE field <= ?
     * GreaterThan        → WHERE field > ?
     * GreaterThanEqual   → WHERE field >= ?
     * Between            → WHERE field BETWEEN ? AND ?
     * 
     * STRING MATCHING:
     * Like               → WHERE field LIKE ?
     * NotLike            → WHERE field NOT LIKE ?
     * StartingWith       → WHERE field LIKE '?%'
     * EndingWith         → WHERE field LIKE '%?'
     * Containing         → WHERE field LIKE '%?%'
     * IgnoreCase         → LOWER(field) = LOWER(?)
     * 
     * NULL CHECKING:
     * IsNull             → WHERE field IS NULL
     * IsNotNull          → WHERE field IS NOT NULL
     * 
     * COLLECTION:
     * In                 → WHERE field IN (?)
     * NotIn              → WHERE field NOT IN (?)
     * 
     * BOOLEAN:
     * True               → WHERE field = true
     * False              → WHERE field = false
     * 
     * ORDERING:
     * OrderBy<Field>Asc  → ORDER BY field ASC
     * OrderBy<Field>Desc → ORDER BY field DESC
     * 
     * 
     * THEORY: @Query Parameters
     * --------------------------
     * 
     * 1. NAMED PARAMETERS (Recommended):
     *    @Query("SELECT b FROM Book b WHERE b.price > :minPrice")
     *    List<Book> findExpensive(@Param("minPrice") BigDecimal minPrice);
     * 
     * 2. POSITIONAL PARAMETERS:
     *    @Query("SELECT b FROM Book b WHERE b.price > ?1")
     *    List<Book> findExpensive(BigDecimal minPrice);
     *    (?1 = first parameter, ?2 = second parameter, etc.)
     * 
     * Named parameters are clearer and less error-prone
     */
}
