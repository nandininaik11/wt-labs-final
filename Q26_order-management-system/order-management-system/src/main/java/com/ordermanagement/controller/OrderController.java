package com.ordermanagement.controller;

// =============================================================================
// IMPORTS SECTION
// =============================================================================

// Spring Framework annotations
import org.springframework.beans.factory.annotation.Autowired;  // Dependency injection
import org.springframework.http.HttpStatus;                      // HTTP status codes
import org.springframework.http.ResponseEntity;                  // HTTP response wrapper
import org.springframework.web.bind.annotation.*;                // REST annotations

// Bean validation
import jakarta.validation.Valid;  // Validates request body

// Our application classes
import com.ordermanagement.entity.Order;
import com.ordermanagement.entity.Order.OrderStatus;
import com.ordermanagement.repository.OrderRepository;

// Java utility classes
import java.util.List;
import java.util.Optional;
import java.util.HashMap;
import java.util.Map;

/**
 * =============================================================================
 * ORDER CONTROLLER - REST API ENDPOINTS
 * =============================================================================
 * 
 * This class defines REST API endpoints for Order Management
 * 
 * REST (Representational State Transfer):
 * - Architectural style for web services
 * - Uses HTTP methods (GET, POST, PUT, DELETE)
 * - Stateless communication
 * - Resource-based URLs
 * 
 * HTTP METHODS:
 * - GET: Retrieve data (Read)
 * - POST: Create new resource (Create)
 * - PUT: Update entire resource (Update)
 * - PATCH: Update part of resource (Partial Update)
 * - DELETE: Remove resource (Delete)
 * 
 * ANNOTATIONS USED:
 * 
 * @RestController:
 * - Combination of @Controller + @ResponseBody
 * - Automatically converts return values to JSON
 * - Marks this class as REST controller
 * 
 * @RequestMapping:
 * - Base URL path for all methods in this controller
 * - Example: /api/orders
 * 
 * @GetMapping, @PostMapping, @PutMapping, @DeleteMapping:
 * - HTTP method-specific variants of @RequestMapping
 * - Maps HTTP requests to handler methods
 * 
 * @PathVariable:
 * - Extracts value from URL path
 * - Example: /orders/{id} -> @PathVariable Long id
 * 
 * @RequestBody:
 * - Binds HTTP request body to method parameter
 * - Automatically deserializes JSON to Java object
 * 
 * @Valid:
 * - Triggers bean validation
 * - Validates @NotNull, @NotBlank, etc. annotations
 * 
 * ResponseEntity:
 * - Wrapper for HTTP response
 * - Contains: status code, headers, body
 * - Allows full control of HTTP response
 */

@RestController  // Marks this as a REST controller (combines @Controller + @ResponseBody)
@RequestMapping("/api/orders")  // Base URL: http://localhost:8080/api/orders
@CrossOrigin(origins = "*")  // Allows requests from any origin (CORS - Cross-Origin Resource Sharing)
                              // In production, specify allowed origins: @CrossOrigin(origins = "http://localhost:3000")
public class OrderController {

    // =========================================================================
    // DEPENDENCY INJECTION
    // =========================================================================
    
    /**
     * OrderRepository dependency
     * 
     * @Autowired: Spring's dependency injection annotation
     * 
     * DEPENDENCY INJECTION (DI):
     * - Design pattern for loose coupling
     * - Spring creates and injects dependencies
     * - No need for 'new' keyword
     * 
     * BENEFITS:
     * - Easier testing (can inject mocks)
     * - Better maintainability
     * - Promotes interface-based programming
     * 
     * INJECTION TYPES:
     * - Field injection: @Autowired on field (used here)
     * - Constructor injection: @Autowired on constructor (recommended)
     * - Setter injection: @Autowired on setter method
     */
    @Autowired  // Spring automatically injects OrderRepository instance
    private OrderRepository orderRepository;

    // =========================================================================
    // CREATE - POST METHOD
    // =========================================================================
    
    /**
     * Create a new order
     * 
     * HTTP METHOD: POST
     * URL: POST http://localhost:8080/api/orders
     * 
     * REQUEST BODY (JSON):
     * {
     *   "customerName": "John Doe",
     *   "customerEmail": "john@example.com",
     *   "productName": "Laptop",
     *   "quantity": 2,
     *   "price": 50000.00
     * }
     * 
     * RESPONSE: 201 CREATED
     * {
     *   "id": 1,
     *   "customerName": "John Doe",
     *   "customerEmail": "john@example.com",
     *   "productName": "Laptop",
     *   "quantity": 2,
     *   "price": 50000.00,
     *   "totalAmount": 100000.00,
     *   "orderStatus": "PENDING",
     *   "createdAt": "2024-01-15T10:30:45",
     *   "updatedAt": "2024-01-15T10:30:45"
     * }
     * 
     * @param order - Order object from JSON request body
     * @return ResponseEntity with created order and 201 status
     */
    @PostMapping  // Maps HTTP POST requests to this method
                  // Full URL: POST /api/orders
    public ResponseEntity<Order> createOrder(
            @Valid @RequestBody Order order  // @Valid triggers validation, @RequestBody binds JSON to Order object
    ) {
        // Save order to database
        // save() method from JpaRepository
        // Returns the saved entity with generated ID
        Order savedOrder = orderRepository.save(order);
        
        // Return HTTP 201 CREATED with the saved order in response body
        // ResponseEntity.status() creates ResponseEntity with specific status code
        // .body() sets the response body
        return ResponseEntity.status(HttpStatus.CREATED).body(savedOrder);
        
        // ALTERNATIVE WAYS TO RETURN:
        // return new ResponseEntity<>(savedOrder, HttpStatus.CREATED);
        // return ResponseEntity.ok(savedOrder);  // Returns 200 OK
    }

    // =========================================================================
    // READ ALL - GET METHOD
    // =========================================================================
    
    /**
     * Get all orders
     * 
     * HTTP METHOD: GET
     * URL: GET http://localhost:8080/api/orders
     * 
     * RESPONSE: 200 OK
     * [
     *   {order1},
     *   {order2},
     *   {order3}
     * ]
     * 
     * @return List of all orders
     */
    @GetMapping  // Maps HTTP GET requests to this method
                 // Full URL: GET /api/orders
    public ResponseEntity<List<Order>> getAllOrders() {
        // Fetch all orders from database
        // findAll() method from JpaRepository
        List<Order> orders = orderRepository.findAll();
        
        // Return HTTP 200 OK with list of orders
        return ResponseEntity.ok(orders);
        
        // If list is empty, still returns 200 OK with empty array []
        // Alternative: Return 204 NO CONTENT if list is empty
        // if (orders.isEmpty()) {
        //     return ResponseEntity.noContent().build();
        // }
    }

    // =========================================================================
    // READ ONE - GET METHOD WITH PATH VARIABLE
    // =========================================================================
    
    /**
     * Get order by ID
     * 
     * HTTP METHOD: GET
     * URL: GET http://localhost:8080/api/orders/1
     * 
     * PATH VARIABLE:
     * - {id} in URL is extracted as method parameter
     * - @PathVariable binds URL variable to method parameter
     * 
     * RESPONSE: 200 OK (if found) or 404 NOT FOUND (if not found)
     * 
     * @param id - Order ID from URL path
     * @return Order if found, 404 if not found
     */
    @GetMapping("/{id}")  // Maps GET /api/orders/{id}
                          // {id} is a path variable - placeholder in URL
    public ResponseEntity<Order> getOrderById(
            @PathVariable Long id  // @PathVariable extracts {id} from URL
                                    // URL: /api/orders/123 -> id = 123
    ) {
        // Find order by ID
        // findById() returns Optional<Order> (may or may not contain value)
        Optional<Order> order = orderRepository.findById(id);
        
        // Check if order exists
        // Optional.isPresent() returns true if value exists
        if (order.isPresent()) {
            // Order found - return 200 OK with order
            // Optional.get() retrieves the value
            return ResponseEntity.ok(order.get());
        } else {
            // Order not found - return 404 NOT FOUND
            // ResponseEntity.notFound().build() creates 404 response with no body
            return ResponseEntity.notFound().build();
        }
        
        // ALTERNATIVE USING LAMBDA (more concise):
        // return order
        //     .map(ResponseEntity::ok)  // If present, return 200 OK
        //     .orElse(ResponseEntity.notFound().build());  // If absent, return 404
    }

    // =========================================================================
    // UPDATE - PUT METHOD
    // =========================================================================
    
    /**
     * Update an existing order
     * 
     * HTTP METHOD: PUT
     * URL: PUT http://localhost:8080/api/orders/1
     * 
     * REQUEST BODY: Complete order object (all fields)
     * 
     * PUT vs PATCH:
     * - PUT: Replace entire resource (all fields required)
     * - PATCH: Update specific fields (only changed fields sent)
     * 
     * RESPONSE: 200 OK (if updated) or 404 NOT FOUND (if ID doesn't exist)
     * 
     * @param id - Order ID from URL
     * @param orderDetails - Updated order data from request body
     * @return Updated order or 404
     */
    @PutMapping("/{id}")  // Maps PUT /api/orders/{id}
    public ResponseEntity<Order> updateOrder(
            @PathVariable Long id,           // Extract ID from URL
            @Valid @RequestBody Order orderDetails  // Extract order from JSON body
    ) {
        // First, check if order exists
        Optional<Order> existingOrder = orderRepository.findById(id);
        
        if (existingOrder.isPresent()) {
            // Order exists - update it
            Order order = existingOrder.get();
            
            // Update all fields
            // We don't update ID (it's immutable)
            order.setCustomerName(orderDetails.getCustomerName());
            order.setCustomerEmail(orderDetails.getCustomerEmail());
            order.setProductName(orderDetails.getProductName());
            order.setQuantity(orderDetails.getQuantity());
            order.setPrice(orderDetails.getPrice());
            order.setOrderStatus(orderDetails.getOrderStatus());
            
            // Note: totalAmount, createdAt, updatedAt are set automatically
            // by @PreUpdate lifecycle callback in Order entity
            
            // Save updated order
            Order updatedOrder = orderRepository.save(order);
            
            // Return 200 OK with updated order
            return ResponseEntity.ok(updatedOrder);
        } else {
            // Order not found - return 404
            return ResponseEntity.notFound().build();
        }
        
        // ALTERNATIVE: Create if not exists (upsert behavior)
        // orderDetails.setId(id);  // Set ID
        // Order updatedOrder = orderRepository.save(orderDetails);
        // return ResponseEntity.ok(updatedOrder);
    }

    // =========================================================================
    // PARTIAL UPDATE - PATCH METHOD
    // =========================================================================
    
    /**
     * Partially update order (e.g., just update status)
     * 
     * HTTP METHOD: PATCH
     * URL: PATCH http://localhost:8080/api/orders/1/status?status=CONFIRMED
     * 
     * QUERY PARAMETER:
     * - ?status=CONFIRMED
     * - @RequestParam extracts query parameter
     * 
     * @param id - Order ID
     * @param status - New status from query parameter
     * @return Updated order or 404
     */
    @PatchMapping("/{id}/status")  // Maps PATCH /api/orders/{id}/status
    public ResponseEntity<Order> updateOrderStatus(
            @PathVariable Long id,
            @RequestParam OrderStatus status  // @RequestParam extracts ?status=VALUE from URL
    ) {
        Optional<Order> existingOrder = orderRepository.findById(id);
        
        if (existingOrder.isPresent()) {
            Order order = existingOrder.get();
            
            // Update only the status field
            order.setOrderStatus(status);
            
            // Save changes
            Order updatedOrder = orderRepository.save(order);
            
            return ResponseEntity.ok(updatedOrder);
        } else {
            return ResponseEntity.notFound().build();
        }
    }

    // =========================================================================
    // DELETE - DELETE METHOD
    // =========================================================================
    
    /**
     * Delete order by ID
     * 
     * HTTP METHOD: DELETE
     * URL: DELETE http://localhost:8080/api/orders/1
     * 
     * RESPONSE: 200 OK with message or 404 NOT FOUND
     * 
     * @param id - Order ID to delete
     * @return Success message or 404
     */
    @DeleteMapping("/{id}")  // Maps DELETE /api/orders/{id}
    public ResponseEntity<Map<String, String>> deleteOrder(
            @PathVariable Long id
    ) {
        // Check if order exists
        if (orderRepository.existsById(id)) {
            // Order exists - delete it
            // deleteById() method from JpaRepository
            orderRepository.deleteById(id);
            
            // Create success message
            Map<String, String> response = new HashMap<>();
            response.put("message", "Order deleted successfully");
            response.put("id", id.toString());
            
            // Return 200 OK with message
            return ResponseEntity.ok(response);
        } else {
            // Order not found
            Map<String, String> response = new HashMap<>();
            response.put("error", "Order not found");
            response.put("id", id.toString());
            
            // Return 404 NOT FOUND with error message
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(response);
        }
        
        // ALTERNATIVE: Return 204 NO CONTENT (no response body)
        // orderRepository.deleteById(id);
        // return ResponseEntity.noContent().build();
    }

    // =========================================================================
    // CUSTOM QUERIES - SEARCH/FILTER ENDPOINTS
    // =========================================================================
    
    /**
     * Get orders by customer name
     * 
     * URL: GET http://localhost:8080/api/orders/customer/John Doe
     * 
     * @param customerName - Customer name from URL path
     * @return List of orders for that customer
     */
    @GetMapping("/customer/{customerName}")
    public ResponseEntity<List<Order>> getOrdersByCustomerName(
            @PathVariable String customerName
    ) {
        // Use custom repository method
        List<Order> orders = orderRepository.findByCustomerName(customerName);
        
        return ResponseEntity.ok(orders);
    }
    
    /**
     * Get orders by status
     * 
     * URL: GET http://localhost:8080/api/orders/status/PENDING
     * 
     * @param status - Order status from URL
     * @return List of orders with that status
     */
    @GetMapping("/status/{status}")
    public ResponseEntity<List<Order>> getOrdersByStatus(
            @PathVariable OrderStatus status  // Spring automatically converts string to enum
    ) {
        List<Order> orders = orderRepository.findByOrderStatus(status);
        
        return ResponseEntity.ok(orders);
    }
    
    /**
     * Search orders by product name
     * 
     * URL: GET http://localhost:8080/api/orders/search?product=Laptop
     * 
     * @param product - Product name from query parameter
     * @return List of matching orders
     */
    @GetMapping("/search")
    public ResponseEntity<List<Order>> searchOrders(
            @RequestParam(name = "product") String productName
            // @RequestParam extracts ?product=VALUE
            // name attribute specifies parameter name in URL
    ) {
        // Case-insensitive search
        List<Order> orders = orderRepository.findByProductNameIgnoreCase(productName);
        
        return ResponseEntity.ok(orders);
    }
    
    /**
     * Get count of orders by status
     * 
     * URL: GET http://localhost:8080/api/orders/count/PENDING
     * 
     * @param status - Order status
     * @return Count of orders
     */
    @GetMapping("/count/{status}")
    public ResponseEntity<Map<String, Object>> getOrderCountByStatus(
            @PathVariable OrderStatus status
    ) {
        long count = orderRepository.countByOrderStatus(status);
        
        Map<String, Object> response = new HashMap<>();
        response.put("status", status.toString());
        response.put("count", count);
        
        return ResponseEntity.ok(response);
    }

}

/**
 * =============================================================================
 * THEORY SUMMARY - REST API & SPRING MVC
 * =============================================================================
 * 
 * 1. REST PRINCIPLES:
 *    - Client-Server architecture
 *    - Stateless communication
 *    - Cacheable responses
 *    - Uniform interface
 *    - Layered system
 * 
 * 2. HTTP METHODS (CRUD):
 *    - POST: Create new resource
 *    - GET: Read/retrieve resource
 *    - PUT: Update entire resource
 *    - PATCH: Partial update
 *    - DELETE: Remove resource
 * 
 * 3. HTTP STATUS CODES:
 *    - 200 OK: Success
 *    - 201 CREATED: Resource created
 *    - 204 NO CONTENT: Success, no response body
 *    - 400 BAD REQUEST: Invalid input
 *    - 404 NOT FOUND: Resource doesn't exist
 *    - 500 INTERNAL SERVER ERROR: Server error
 * 
 * 4. SPRING MVC FLOW:
 *    Request → DispatcherServlet → Controller → Service → Repository → Database
 *    Database → Repository → Service → Controller → DispatcherServlet → Response
 * 
 * 5. KEY ANNOTATIONS:
 *    - @RestController: REST controller
 *    - @RequestMapping: URL mapping
 *    - @GetMapping, @PostMapping, etc.: HTTP method mapping
 *    - @PathVariable: URL path variable
 *    - @RequestParam: Query parameter
 *    - @RequestBody: Request body (JSON)
 *    - @Valid: Trigger validation
 * 
 * 6. DEPENDENCY INJECTION:
 *    - @Autowired: Inject dependencies
 *    - Promotes loose coupling
 *    - Spring manages object lifecycle
 * 
 * 7. RESPONSE ENTITY:
 *    - Full control of HTTP response
 *    - Set status code, headers, body
 *    - Type-safe response handling
 * 
 * 8. JSON CONVERSION:
 *    - Jackson library (included in Spring Boot)
 *    - Automatic serialization (Java → JSON)
 *    - Automatic deserialization (JSON → Java)
 * 
 * 9. EXCEPTION HANDLING:
 *    - @ExceptionHandler for controller-specific
 *    - @ControllerAdvice for global handling
 *    - Return appropriate HTTP status codes
 * 
 * 10. BEST PRACTICES:
 *     - Use proper HTTP methods
 *     - Return appropriate status codes
 *     - Validate input (@Valid)
 *     - Use ResponseEntity for flexibility
 *     - Follow RESTful URL conventions
 *     - Version your APIs (/api/v1/orders)
 * 
 * =============================================================================
 */
