package com.bookstore.controller;

import com.bookstore.entity.Book;
import com.bookstore.service.BookService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestParam;

import java.math.BigDecimal;
import java.util.List;

/**
 * BOOK CONTROLLER
 * ===============
 * Handles all book-related requests
 * Displays catalog, search results, etc.
 */

@Controller
public class BookController {
    
    @Autowired
    private BookService bookService;
    
    
    /*
     * ============================
     * CATALOG PAGE
     * ============================
     */
    
    /**
     * DISPLAY CATALOG PAGE
     * --------------------
     * Shows all books or filtered books
     * 
     * @param category - Optional category filter
     * @param search - Optional search keyword
     * @param minPrice - Optional minimum price filter
     * @param maxPrice - Optional maximum price filter
     * @param model - Model for passing data to view
     * @return catalog.html template
     * 
     * URL Examples:
     * /catalog → All books
     * /catalog?category=Fiction → Fiction books only
     * /catalog?search=java → Books with "java" in title/author
     * /catalog?minPrice=100&maxPrice=500 → Books in price range
     * /catalog?category=Fiction&search=harry → Combined filters
     */
    @GetMapping("/catalog")
    public String showCatalog(
            @RequestParam(value = "category", required = false) String category,
            @RequestParam(value = "search", required = false) String search,
            @RequestParam(value = "minPrice", required = false) BigDecimal minPrice,
            @RequestParam(value = "maxPrice", required = false) BigDecimal maxPrice,
            Model model) {
        
        /*
         * FILTERING LOGIC
         * ---------------
         * Apply filters based on parameters
         * Priority: search > category > price range > all
         */
        
        List<Book> books;
        
        // 1. SEARCH FILTER (Highest Priority)
        if (search != null && !search.trim().isEmpty()) {
            /*
             * If search parameter exists:
             * - Search in title and author
             * - Case-insensitive partial match
             * 
             * Example: search=java
             * Matches: "Java Programming", "Advanced Java", "JavaScript"
             */
            books = bookService.searchBooks(search);
            
            // Add search keyword to model for displaying in UI
            model.addAttribute("searchKeyword", search);
        }
        
        // 2. CATEGORY FILTER
        else if (category != null && !category.trim().isEmpty()) {
            /*
             * If category parameter exists:
             * - Filter books by exact category match
             * 
             * Example: category=Fiction
             * Returns: All books where book.category = "Fiction"
             */
            books = bookService.getBooksByCategory(category);
            
            // Add selected category to model
            model.addAttribute("selectedCategory", category);
        }
        
        // 3. PRICE RANGE FILTER
        else if (minPrice != null && maxPrice != null) {
            /*
             * If both price parameters exist:
             * - Filter books within price range (inclusive)
             * 
             * Example: minPrice=100&maxPrice=500
             * Returns: Books where 100 <= price <= 500
             */
            books = bookService.getBooksByPriceRange(minPrice, maxPrice);
            
            // Add price range to model
            model.addAttribute("minPrice", minPrice);
            model.addAttribute("maxPrice", maxPrice);
        }
        
        // 4. DEFAULT: ALL BOOKS
        else {
            /*
             * No filters applied:
             * - Return all books in catalog
             */
            books = bookService.getAllBooks();
        }
        
        /*
         * ADD DATA TO MODEL
         * -----------------
         * Data added to model is available in Thymeleaf template
         */
        
        // Add books list to model
        // In template: th:each="book : ${books}"
        model.addAttribute("books", books);
        
        // Add all categories for filter dropdown
        // In template: th:each="cat : ${categories}"
        List<String> categories = bookService.getAllCategories();
        model.addAttribute("categories", categories);
        
        // Add book count for display
        model.addAttribute("bookCount", books.size());
        
        /*
         * RETURN VIEW NAME
         * ----------------
         * Spring resolves "catalog" to:
         * src/main/resources/templates/catalog.html
         */
        return "catalog";
    }
    
    
    /*
     * THEORY: Query Parameters in Spring MVC
     * ---------------------------------------
     * 
     * 1. SINGLE PARAMETER:
     *    URL: /catalog?category=Fiction
     *    
     *    @GetMapping("/catalog")
     *    public String catalog(@RequestParam String category) {
     *        // category = "Fiction"
     *    }
     * 
     * 2. OPTIONAL PARAMETER:
     *    URL: /catalog (no parameters)
     *    
     *    @GetMapping("/catalog")
     *    public String catalog(
     *        @RequestParam(required = false) String category) {
     *        // category = null (parameter not provided)
     *    }
     * 
     * 3. DEFAULT VALUE:
     *    URL: /catalog (no parameters)
     *    
     *    @GetMapping("/catalog")
     *    public String catalog(
     *        @RequestParam(defaultValue = "All") String category) {
     *        // category = "All" (default used)
     *    }
     * 
     * 4. DIFFERENT NAME:
     *    URL: /catalog?cat=Fiction
     *    
     *    @GetMapping("/catalog")
     *    public String catalog(
     *        @RequestParam(value = "cat") String category) {
     *        // Parameter name "cat" mapped to variable "category"
     *    }
     * 
     * 5. MULTIPLE PARAMETERS:
     *    URL: /catalog?category=Fiction&minPrice=100&maxPrice=500
     *    
     *    @GetMapping("/catalog")
     *    public String catalog(
     *        @RequestParam String category,
     *        @RequestParam BigDecimal minPrice,
     *        @RequestParam BigDecimal maxPrice) {
     *        // All parameters extracted
     *    }
     * 
     * 6. LIST PARAMETERS:
     *    URL: /catalog?category=Fiction&category=Science&category=History
     *    
     *    @GetMapping("/catalog")
     *    public String catalog(
     *        @RequestParam List<String> category) {
     *        // category = ["Fiction", "Science", "History"]
     *    }
     * 
     * 
     * THEORY: Model Attributes
     * -------------------------
     * 
     * The Model interface is used to pass data from controller to view
     * 
     * ADDING ATTRIBUTES:
     * 
     * model.addAttribute("books", booksList);
     * → Key: "books", Value: booksList object
     * 
     * model.addAttribute(booksList);
     * → Spring auto-generates key from object type
     * → Key: "bookList" (lowercase class name)
     * 
     * ACCESSING IN THYMELEAF:
     * 
     * Single object:
     * <p th:text="${bookCount}"></p>
     * → Displays the book count
     * 
     * Object property:
     * <p th:text="${user.username}"></p>
     * → Displays user.getUsername()
     * 
     * Iteration:
     * <div th:each="book : ${books}">
     *   <h3 th:text="${book.title}"></h3>
     *   <p th:text="${book.author}"></p>
     * </div>
     * → Loops through each book in books list
     * 
     * Conditional:
     * <p th:if="${bookCount > 0}">
     *   Found ${bookCount} books
     * </p>
     * → Shows only if condition is true
     * 
     * 
     * THEORY: RESTful URL Design
     * ---------------------------
     * 
     * Good URL patterns for bookstore:
     * 
     * RESOURCE-ORIENTED:
     * GET  /books          → List all books
     * GET  /books/123      → View book #123
     * POST /books          → Create new book
     * PUT  /books/123      → Update book #123
     * DELETE /books/123    → Delete book #123
     * 
     * NESTED RESOURCES:
     * GET  /categories/fiction/books  → Books in Fiction category
     * GET  /authors/123/books         → Books by author #123
     * 
     * FILTERING (Query Parameters):
     * GET  /books?category=Fiction
     * GET  /books?author=Martin
     * GET  /books?minPrice=100&maxPrice=500
     * 
     * SEARCHING:
     * GET  /books?search=java
     * GET  /books/search?q=java
     * 
     * PAGINATION:
     * GET  /books?page=1&size=20
     * 
     * SORTING:
     * GET  /books?sort=price,asc
     * GET  /books?sort=title,desc
     * 
     * COMBINED:
     * GET  /books?category=Fiction&minPrice=100&maxPrice=500&sort=price,asc&page=1
     * 
     * 
     * THEORY: Service Layer Calls
     * ----------------------------
     * 
     * Controller should be thin:
     * - Handle HTTP concerns (request/response)
     * - Validate input
     * - Call service methods
     * - Prepare view data
     * 
     * DON'T put business logic in controller!
     * 
     * BAD (Logic in Controller):
     * @GetMapping("/books")
     * public String getBooks(Model model) {
     *     List<Book> books = bookRepository.findAll();
     *     books = books.stream()
     *                  .filter(b -> b.getStockQuantity() > 0)
     *                  .filter(b -> b.getAvailable())
     *                  .collect(Collectors.toList());
     *     model.addAttribute("books", books);
     *     return "catalog";
     * }
     * 
     * GOOD (Logic in Service):
     * @GetMapping("/books")
     * public String getBooks(Model model) {
     *     List<Book> books = bookService.getAvailableBooksInStock();
     *     model.addAttribute("books", books);
     *     return "catalog";
     * }
     * 
     * Service method:
     * public List<Book> getAvailableBooksInStock() {
     *     return bookRepository.findByAvailableAndStockQuantityGreaterThan(true, 0);
     * }
     */
}
