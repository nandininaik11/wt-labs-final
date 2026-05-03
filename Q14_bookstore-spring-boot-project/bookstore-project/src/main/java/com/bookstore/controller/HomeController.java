package com.bookstore.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;

/**
 * HOME CONTROLLER
 * ===============
 * Handles requests for the home page
 */

/*
 * @Controller Annotation
 * ----------------------
 * - Marks this as a Spring MVC Controller
 * - Spring scans and creates instance of this class
 * - Can handle HTTP requests
 * - Returns view names (HTML pages)
 * 
 * @Controller vs @RestController:
 * -------------------------------
 * @Controller:
 *   - Returns view names (for Thymeleaf templates)
 *   - Example: return "home" → renders home.html
 * 
 * @RestController:
 *   - Returns data directly (JSON/XML)
 *   - Example: return user → converts to JSON
 *   - Combines @Controller + @ResponseBody
 */
@Controller
public class HomeController {
    
    /**
     * HOME PAGE HANDLER
     * -----------------
     * Handles GET requests to / and /home
     * 
     * @GetMapping Annotation:
     * -----------------------
     * - Maps HTTP GET requests to this method
     * - Shortcut for: @RequestMapping(method = RequestMethod.GET)
     * 
     * Value can be:
     * - Single path: @GetMapping("/home")
     * - Multiple paths: @GetMapping({"/", "/home"})
     * - With path variables: @GetMapping("/user/{id}")
     * - With parameters: @GetMapping("/search?q=java")
     * 
     * @return View name (home.html in templates folder)
     * 
     * How it works:
     * 1. User visits http://localhost:8080/
     * 2. Spring routes request to this method
     * 3. Method returns "home"
     * 4. Thymeleaf looks for templates/home.html
     * 5. Renders and sends HTML to browser
     */
    @GetMapping({"/", "/home"})
    public String home() {
        /*
         * Return value "home" means:
         * - Look for: src/main/resources/templates/home.html
         * - Process Thymeleaf expressions
         * - Return rendered HTML
         * 
         * View Resolver Configuration:
         * - Prefix: classpath:/templates/
         * - Suffix: .html
         * - Result: classpath:/templates/home.html
         */
        return "home";  // Returns home.html template
    }
    
    
    /*
     * THEORY: Spring MVC Request Processing
     * --------------------------------------
     * 
     * 1. INCOMING REQUEST:
     *    User → http://localhost:8080/home
     * 
     * 2. DISPATCHER SERVLET:
     *    - Front controller that receives all requests
     *    - Central entry point for Spring MVC
     * 
     * 3. HANDLER MAPPING:
     *    - Maps URL to controller method
     *    - Finds @GetMapping("/home")
     * 
     * 4. CONTROLLER:
     *    - Executes the mapped method
     *    - Returns view name or data
     * 
     * 5. VIEW RESOLVER:
     *    - Resolves view name to actual template
     *    - "home" → /templates/home.html
     * 
     * 6. VIEW (Thymeleaf):
     *    - Renders the template
     *    - Processes th: attributes
     *    - Generates HTML
     * 
     * 7. RESPONSE:
     *    - HTML sent back to browser
     * 
     * Flow Diagram:
     * Browser → DispatcherServlet → HandlerMapping → Controller
     *                                                      ↓
     * Browser ← HTML ← Thymeleaf ← ViewResolver ← "home" ←
     * 
     * 
     * THEORY: Common HTTP Methods
     * ----------------------------
     * 
     * @GetMapping - Retrieve data
     *   - Idempotent (same result every time)
     *   - Cacheable
     *   - Data in URL parameters
     *   - Example: View product, search results
     * 
     * @PostMapping - Submit data
     *   - Non-idempotent (can have side effects)
     *   - Not cacheable
     *   - Data in request body
     *   - Example: Create user, submit form
     * 
     * @PutMapping - Update entire resource
     *   - Idempotent
     *   - Example: Update user profile (all fields)
     * 
     * @PatchMapping - Partial update
     *   - Idempotent
     *   - Example: Update only email address
     * 
     * @DeleteMapping - Delete resource
     *   - Idempotent
     *   - Example: Delete user account
     * 
     * 
     * THEORY: MVC Pattern
     * -------------------
     * 
     * Model-View-Controller architecture:
     * 
     * MODEL:
     *   - Represents data and business logic
     *   - Entity classes (User, Book)
     *   - Service classes (UserService, BookService)
     * 
     * VIEW:
     *   - Presentation layer
     *   - Thymeleaf templates (home.html, catalog.html)
     *   - Displays data to user
     * 
     * CONTROLLER:
     *   - Handles user input
     *   - Controller classes (HomeController, BookController)
     *   - Processes requests, returns responses
     * 
     * Flow:
     * User → Controller → Service → Repository → Database
     *   ↑                    ↓
     *   └────── View ←── Model
     * 
     * Separation of Concerns:
     * - Controller: HTTP handling, routing
     * - Service: Business logic, transactions
     * - Repository: Database access
     * - View: HTML rendering
     * 
     * Benefits:
     * - Easy to test each layer independently
     * - Changes in one layer don't affect others
     * - Code reusability
     * - Better organization
     */
}
