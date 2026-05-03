package com.inventory.controller;

// ============================================================
// ProductController.java - Task 6: REST API CRUD Operations
//
// Controller = The layer that handles HTTP requests.
// Each method maps to an HTTP method + URL path.
//
// REST API Conventions:
// GET    /api/products        → Get all products
// GET    /api/products/{id}   → Get one product
// POST   /api/products        → Create new product
// PUT    /api/products/{id}   → Update existing product
// DELETE /api/products/{id}   → Delete product
// ============================================================

import com.inventory.model.Product;
import com.inventory.repository.ProductRepository;
import org.springframework.beans.factory.annotation.Autowired;  // Dependency Injection
import org.springframework.http.HttpStatus;                      // HTTP status codes (200, 201, 404, etc.)
import org.springframework.http.ResponseEntity;                  // Wraps response body + status code
import org.springframework.web.bind.annotation.*;               // REST annotations
import java.util.List;
import java.util.Optional;

@RestController        // @Controller + @ResponseBody: returns JSON, not HTML views
@RequestMapping("/api/products")  // Base URL for all endpoints in this controller
public class ProductController {

    // @Autowired: Spring automatically injects (provides) the ProductRepository instance
    // We don't write: ProductRepository repo = new ProductRepository(); — Spring does it!
    @Autowired
    private ProductRepository productRepository;

    // ============================================================
    // CREATE - POST /api/products
    // Accepts JSON body, saves to MongoDB, returns saved product
    // ============================================================
    @PostMapping                           // Maps HTTP POST to this method
    public ResponseEntity<Product> createProduct(
            @RequestBody Product product)  // @RequestBody: reads JSON from request body and converts to Product object
    {
        // Save product to MongoDB (auto-generates ID if not provided)
        Product savedProduct = productRepository.save(product);

        // Return 201 Created status + the saved product (with generated ID)
        return new ResponseEntity<>(savedProduct, HttpStatus.CREATED);
    }

    // ============================================================
    // READ ALL - GET /api/products
    // Returns list of all products in the database
    // ============================================================
    @GetMapping   // Maps HTTP GET to this method
    public ResponseEntity<List<Product>> getAllProducts() {
        // findAll() is a free method from MongoRepository
        List<Product> products = productRepository.findAll();

        // Return 200 OK with the list
        return new ResponseEntity<>(products, HttpStatus.OK);
    }

    // ============================================================
    // READ ONE - GET /api/products/{id}
    // Returns a single product by its MongoDB ID
    // ============================================================
    @GetMapping("/{id}")                  // Maps GET /api/products/abc123
    public ResponseEntity<Product> getProductById(
            @PathVariable String id)       // @PathVariable: reads {id} from URL
    {
        // findById returns Optional<Product> (might be null if not found)
        Optional<Product> product = productRepository.findById(id);

        if (product.isPresent()) {
            return new ResponseEntity<>(product.get(), HttpStatus.OK);        // 200 OK
        } else {
            return new ResponseEntity<>(HttpStatus.NOT_FOUND);                // 404 Not Found
        }
    }

    // ============================================================
    // UPDATE - PUT /api/products/{id}
    // Updates an existing product with new data from request body
    // ============================================================
    @PutMapping("/{id}")                  // Maps HTTP PUT /api/products/abc123
    public ResponseEntity<Product> updateProduct(
            @PathVariable String id,
            @RequestBody Product updatedProduct)
    {
        // First, check if product exists
        Optional<Product> existingProduct = productRepository.findById(id);

        if (existingProduct.isPresent()) {
            // Set the same ID so MongoDB updates (not inserts)
            updatedProduct.setId(id);

            // save() with existing ID = UPDATE in MongoDB
            Product saved = productRepository.save(updatedProduct);
            return new ResponseEntity<>(saved, HttpStatus.OK);            // 200 OK
        } else {
            return new ResponseEntity<>(HttpStatus.NOT_FOUND);            // 404 Not Found
        }
    }

    // ============================================================
    // DELETE - DELETE /api/products/{id}
    // Removes a product from the database
    // ============================================================
    @DeleteMapping("/{id}")               // Maps HTTP DELETE /api/products/abc123
    public ResponseEntity<String> deleteProduct(
            @PathVariable String id)
    {
        if (productRepository.existsById(id)) {
            productRepository.deleteById(id);
            return new ResponseEntity<>("Product deleted successfully!", HttpStatus.OK);  // 200 OK
        } else {
            return new ResponseEntity<>("Product not found!", HttpStatus.NOT_FOUND);      // 404
        }
    }

    // ============================================================
    // BONUS ENDPOINTS (using custom repository queries)
    // ============================================================

    // GET /api/products/category/{category}  → Find by category
    @GetMapping("/category/{category}")
    public ResponseEntity<List<Product>> getByCategory(@PathVariable String category) {
        List<Product> products = productRepository.findByCategory(category);
        return new ResponseEntity<>(products, HttpStatus.OK);
    }

    // GET /api/products/search?name=laptop  → Search by name keyword
    @GetMapping("/search")
    public ResponseEntity<List<Product>> searchByName(
            @RequestParam String name)     // @RequestParam: reads ?name=value from URL
    {
        List<Product> products = productRepository.findByNameContainingIgnoreCase(name);
        return new ResponseEntity<>(products, HttpStatus.OK);
    }
}

/*
 * VIVA TIP - HTTP Methods and their meaning:
 * GET    = Read (safe, idempotent, no body)
 * POST   = Create (not idempotent - calling twice creates two records)
 * PUT    = Update/Replace (idempotent - calling twice gives same result)
 * DELETE = Remove (idempotent)
 * PATCH  = Partial Update (not shown here)
 *
 * HTTP Status Codes:
 * 200 OK           = Success (GET, PUT, DELETE)
 * 201 Created      = New resource created (POST)
 * 404 Not Found    = Resource doesn't exist
 * 401 Unauthorized = Not authenticated
 * 403 Forbidden    = Authenticated but not allowed
 */
