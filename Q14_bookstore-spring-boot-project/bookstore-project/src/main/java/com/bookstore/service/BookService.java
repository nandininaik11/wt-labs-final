package com.bookstore.service;

import com.bookstore.entity.Book;
import com.bookstore.repository.BookRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.util.List;
import java.util.Optional;

/**
 * BOOK SERVICE CLASS
 * ==================
 * Business logic for Book operations
 */

@Service
@Transactional
public class BookService {
    
    @Autowired
    private BookRepository bookRepository;
    
    
    /*
     * ============================
     * CREATE BOOK
     * ============================
     */
    
    /**
     * ADD NEW BOOK TO CATALOG
     * -----------------------
     * @param book - Book object with details
     * @return Saved book with generated ID
     */
    public Book addBook(Book book) {
        // Business validation
        if (book.getTitle() == null || book.getTitle().isEmpty()) {
            throw new RuntimeException("Book title is required!");
        }
        
        if (book.getPrice() == null || book.getPrice().compareTo(BigDecimal.ZERO) <= 0) {
            throw new RuntimeException("Price must be greater than 0!");
        }
        
        // Check if ISBN already exists
        if (book.getIsbn() != null && !book.getIsbn().isEmpty()) {
            Optional<Book> existingBook = bookRepository.findByIsbn(book.getIsbn());
            if (existingBook.isPresent()) {
                throw new RuntimeException("Book with this ISBN already exists!");
            }
        }
        
        return bookRepository.save(book);
    }
    
    
    /*
     * ============================
     * RETRIEVE BOOKS
     * ============================
     */
    
    /**
     * GET ALL BOOKS
     * -------------
     * @return List of all books in catalog
     */
    public List<Book> getAllBooks() {
        return bookRepository.findAll();
    }
    
    
    /**
     * GET BOOK BY ID
     * --------------
     * @param id - Book ID
     * @return Optional<Book>
     */
    public Optional<Book> getBookById(Long id) {
        return bookRepository.findById(id);
    }
    
    
    /**
     * GET AVAILABLE BOOKS
     * -------------------
     * Only books marked as available
     */
    public List<Book> getAvailableBooks() {
        return bookRepository.findByAvailable(true);
    }
    
    
    /**
     * GET BOOKS BY CATEGORY
     * ---------------------
     * @param category - Category name
     * @return Books in that category
     */
    public List<Book> getBooksByCategory(String category) {
        return bookRepository.findByCategory(category);
    }
    
    
    /**
     * SEARCH BOOKS
     * ------------
     * Search by title or author
     * @param keyword - Search term
     * @return Matching books
     */
    public List<Book> searchBooks(String keyword) {
        if (keyword == null || keyword.trim().isEmpty()) {
            return getAllBooks();
        }
        return bookRepository.searchBooks(keyword.trim());
    }
    
    
    /**
     * GET BOOKS BY PRICE RANGE
     * ------------------------
     * @param minPrice - Minimum price
     * @param maxPrice - Maximum price
     * @return Books within price range
     */
    public List<Book> getBooksByPriceRange(BigDecimal minPrice, BigDecimal maxPrice) {
        return bookRepository.findByPriceBetween(minPrice, maxPrice);
    }
    
    
    /**
     * GET BOOKS IN STOCK
     * ------------------
     * Books with available stock
     */
    public List<Book> getBooksInStock() {
        return bookRepository.findByStockQuantityGreaterThan(0);
    }
    
    
    /**
     * GET ALL CATEGORIES
     * ------------------
     * @return List of unique categories
     */
    public List<String> getAllCategories() {
        return bookRepository.findDistinctCategories();
    }
    
    
    /**
     * GET TOP RATED BOOKS
     * -------------------
     * Books with rating >= 4.0
     */
    public List<Book> getTopRatedBooks() {
        return bookRepository.findTopRatedBooks(new BigDecimal("4.0"));
    }
    
    
    /*
     * ============================
     * UPDATE BOOK
     * ============================
     */
    
    /**
     * UPDATE BOOK DETAILS
     * -------------------
     * @param id - Book ID
     * @param updatedBook - Book with new data
     * @return Updated book
     */
    public Book updateBook(Long id, Book updatedBook) {
        Book existingBook = bookRepository.findById(id)
            .orElseThrow(() -> new RuntimeException("Book not found!"));
        
        // Update fields
        if (updatedBook.getTitle() != null && !updatedBook.getTitle().isEmpty()) {
            existingBook.setTitle(updatedBook.getTitle());
        }
        
        if (updatedBook.getAuthor() != null && !updatedBook.getAuthor().isEmpty()) {
            existingBook.setAuthor(updatedBook.getAuthor());
        }
        
        if (updatedBook.getDescription() != null) {
            existingBook.setDescription(updatedBook.getDescription());
        }
        
        if (updatedBook.getPrice() != null) {
            existingBook.setPrice(updatedBook.getPrice());
        }
        
        if (updatedBook.getStockQuantity() != null) {
            existingBook.setStockQuantity(updatedBook.getStockQuantity());
        }
        
        if (updatedBook.getCategory() != null) {
            existingBook.setCategory(updatedBook.getCategory());
        }
        
        if (updatedBook.getImageUrl() != null) {
            existingBook.setImageUrl(updatedBook.getImageUrl());
        }
        
        if (updatedBook.getAvailable() != null) {
            existingBook.setAvailable(updatedBook.getAvailable());
        }
        
        return bookRepository.save(existingBook);
    }
    
    
    /**
     * UPDATE STOCK QUANTITY
     * ---------------------
     * @param bookId - Book ID
     * @param quantity - New stock quantity
     */
    public void updateStock(Long bookId, Integer quantity) {
        Book book = bookRepository.findById(bookId)
            .orElseThrow(() -> new RuntimeException("Book not found!"));
        
        if (quantity < 0) {
            throw new RuntimeException("Stock quantity cannot be negative!");
        }
        
        book.setStockQuantity(quantity);
        bookRepository.save(book);
    }
    
    
    /**
     * DECREASE STOCK (After Purchase)
     * --------------------------------
     * @param bookId - Book ID
     * @param quantity - Quantity sold
     */
    public void decreaseStock(Long bookId, Integer quantity) {
        Book book = bookRepository.findById(bookId)
            .orElseThrow(() -> new RuntimeException("Book not found!"));
        
        if (book.getStockQuantity() < quantity) {
            throw new RuntimeException("Insufficient stock!");
        }
        
        book.setStockQuantity(book.getStockQuantity() - quantity);
        bookRepository.save(book);
    }
    
    
    /*
     * ============================
     * DELETE BOOK
     * ============================
     */
    
    /**
     * DELETE BOOK FROM CATALOG
     * ------------------------
     * @param id - Book ID
     */
    public void deleteBook(Long id) {
        if (!bookRepository.existsById(id)) {
            throw new RuntimeException("Book not found!");
        }
        bookRepository.deleteById(id);
    }
    
    
    /*
     * THEORY: Business Logic in Service Layer
     * ----------------------------------------
     * 
     * The service layer contains:
     * 1. Validation logic
     * 2. Business rules
     * 3. Complex operations involving multiple repositories
     * 4. Transaction boundaries
     * 
     * Example: Order Processing (would involve multiple services)
     * 
     * public void processOrder(Order order) {
     *     // 1. Check stock
     *     Book book = bookService.getBookById(order.getBookId());
     *     if (book.getStockQuantity() < order.getQuantity()) {
     *         throw new RuntimeException("Out of stock!");
     *     }
     *     
     *     // 2. Calculate total
     *     BigDecimal total = book.getPrice()
     *                           .multiply(new BigDecimal(order.getQuantity()));
     *     
     *     // 3. Save order
     *     order.setTotal(total);
     *     orderRepository.save(order);
     *     
     *     // 4. Decrease stock
     *     bookService.decreaseStock(order.getBookId(), order.getQuantity());
     *     
     *     // All of above happens in ONE transaction
     *     // If any step fails, all changes are rolled back
     * }
     */
}
