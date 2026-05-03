package com.bookstore.controller;

import com.bookstore.entity.User;
import com.bookstore.service.UserService;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

/**
 * AUTHENTICATION CONTROLLER
 * ==========================
 * Handles user login and registration
 */

@Controller
public class AuthController {
    
    @Autowired
    private UserService userService;
    
    
    /*
     * ============================
     * LOGIN PAGE
     * ============================
     */
    
    /**
     * SHOW LOGIN PAGE
     * ---------------
     * Displays the login form
     * 
     * @param error - Optional parameter if login failed
     * @param logout - Optional parameter if just logged out
     * @param model - Spring Model for passing data to view
     * @return login.html template
     * 
     * URL Parameters:
     * /login?error=true → Login failed
     * /login?logout=true → Successfully logged out
     */
    @GetMapping("/login")
    public String showLoginPage(
            @RequestParam(value = "error", required = false) String error,
            @RequestParam(value = "logout", required = false) String logout,
            Model model) {
        
        /*
         * @RequestParam Annotation
         * ------------------------
         * Extracts parameters from URL query string
         * 
         * value = "error" → Looks for ?error=...
         * required = false → Parameter is optional
         * 
         * If parameter exists:
         *   error = "true" (the value)
         * If parameter doesn't exist:
         *   error = null
         */
        
        // Add error message if login failed
        if (error != null) {
            model.addAttribute("error", "Invalid username or password!");
        }
        
        // Add success message if just logged out
        if (logout != null) {
            model.addAttribute("message", "You have been logged out successfully!");
        }
        
        /*
         * Model Interface
         * ---------------
         * - Container for data passed to view
         * - Key-value pairs
         * 
         * model.addAttribute("key", value)
         * 
         * In Thymeleaf template:
         * <p th:text="${error}"></p>
         * → Displays the error message
         */
        
        return "login";  // Returns login.html
    }
    
    
    /*
     * NOTE: Login processing is handled by Spring Security
     * We configured it in SecurityConfig:
     *   .loginProcessingUrl("/login-process")
     * 
     * Spring Security automatically:
     * 1. Extracts username and password
     * 2. Authenticates using UserDetailsService
     * 3. Redirects on success/failure
     * 
     * We don't need a @PostMapping for login!
     */
    
    
    /*
     * ============================
     * REGISTRATION PAGE
     * ============================
     */
    
    /**
     * SHOW REGISTRATION PAGE
     * ----------------------
     * Displays the registration form
     * 
     * @param model - Model to pass empty User object
     * @return registration.html template
     */
    @GetMapping("/register")
    public String showRegistrationPage(Model model) {
        
        /*
         * Create empty User object for form binding
         * ------------------------------------------
         * Thymeleaf will bind form fields to this object
         * 
         * In template:
         * <form th:object="${user}">
         *   <input th:field="*{username}" />
         *   <input th:field="*{email}" />
         * </form>
         * 
         * When form is submitted, Spring fills this User object
         * with form data automatically!
         */
        model.addAttribute("user", new User());
        
        return "registration";  // Returns registration.html
    }
    
    
    /**
     * PROCESS REGISTRATION
     * --------------------
     * Handles registration form submission
     * 
     * @param user - User object filled with form data
     * @param bindingResult - Validation results
     * @param redirectAttributes - For passing messages after redirect
     * @return Redirect to login page or back to registration
     * 
     * @PostMapping - Handles HTTP POST requests
     * @Valid - Triggers validation on User object
     * @ModelAttribute - Binds form data to User object
     */
    @PostMapping("/register-process")
    public String registerUser(
            @Valid @ModelAttribute("user") User user,
            BindingResult bindingResult,
            RedirectAttributes redirectAttributes) {
        
        /*
         * @ModelAttribute Annotation
         * --------------------------
         * - Binds form data to Java object
         * - Automatically maps form fields to object properties
         * 
         * Example:
         * Form field: <input name="username" value="john" />
         * Maps to: user.setUsername("john")
         * 
         * The name "user" must match:
         *   model.addAttribute("user", new User())
         */
        
        /*
         * @Valid Annotation
         * ----------------
         * - Triggers Bean Validation
         * - Checks @NotBlank, @Email, @Size annotations in User entity
         * - Results stored in BindingResult
         */
        
        /*
         * BindingResult
         * -------------
         * - Contains validation errors (if any)
         * - Must come immediately after validated object
         * 
         * Methods:
         * - hasErrors() → Returns true if validation failed
         * - getFieldErrors() → List of field-specific errors
         * - getGlobalErrors() → List of object-level errors
         */
        
        // Check for validation errors
        if (bindingResult.hasErrors()) {
            /*
             * If validation fails:
             * - Return to registration form
             * - Errors are automatically displayed in template
             * - User object retains entered values (no data loss)
             */
            return "registration";
        }
        
        /*
         * TRY-CATCH Block
         * ---------------
         * Handle runtime exceptions from service layer
         */
        try {
            // Call service to register user
            userService.registerUser(user);
            
            /*
             * RedirectAttributes
             * ------------------
             * - Passes data after redirect (POST-Redirect-GET pattern)
             * - Prevents duplicate form submissions
             * 
             * addFlashAttribute():
             * - Stores data in session temporarily
             * - Available for next request only
             * - Automatically removed after use
             * 
             * Without flash attributes:
             * POST /register → redirect /login
             * Message is lost! ✗
             * 
             * With flash attributes:
             * POST /register → redirect /login
             * Message survives redirect! ✓
             */
            redirectAttributes.addFlashAttribute("message", 
                "Registration successful! Please login.");
            
            /*
             * Redirect
             * --------
             * return "redirect:/login"
             * 
             * This sends HTTP 302 redirect response:
             * - Browser makes new GET request to /login
             * - URL in browser changes to /login
             * - Prevents form resubmission on page refresh
             * 
             * POST-Redirect-GET Pattern:
             * 1. User submits form (POST)
             * 2. Server processes form
             * 3. Server redirects (GET)
             * 4. Browser loads new page
             * 
             * Benefits:
             * - Refresh doesn't resubmit form
             * - Back button works correctly
             * - Clean URLs
             */
            return "redirect:/login";
            
        } catch (RuntimeException e) {
            // If registration fails (username/email exists)
            
            /*
             * addFlashAttribute() for errors
             * -------------------------------
             * Pass error message to next request
             */
            redirectAttributes.addFlashAttribute("error", e.getMessage());
            
            // Redirect back to registration page
            return "redirect:/register";
        }
    }
    
    
    /*
     * THEORY: Form Binding and Validation
     * ------------------------------------
     * 
     * 1. FORM BINDING:
     *    HTML Form → Java Object (automatic)
     *    
     *    <form th:object="${user}" th:action="@{/register-process}" method="post">
     *      <input th:field="*{username}" />
     *      <input th:field="*{email}" />
     *      <input th:field="*{password}" />
     *    </form>
     *    
     *    Spring automatically:
     *    - Creates User object
     *    - Sets user.username = form value
     *    - Sets user.email = form value
     *    - Sets user.password = form value
     * 
     * 2. VALIDATION:
     *    Entity Annotations → Validation Errors
     *    
     *    User entity:
     *    @NotBlank(message = "Username is required")
     *    private String username;
     *    
     *    If username is empty:
     *    - BindingResult.hasErrors() returns true
     *    - Error message: "Username is required"
     * 
     * 3. DISPLAYING ERRORS:
     *    BindingResult → Thymeleaf Template
     *    
     *    <div th:if="${#fields.hasErrors('username')}">
     *      <span th:errors="*{username}"></span>
     *    </div>
     *    
     *    Displays: "Username is required"
     * 
     * 
     * THEORY: POST-Redirect-GET Pattern
     * ----------------------------------
     * 
     * Problem without PRG:
     * 1. User submits form (POST /register)
     * 2. Server processes and shows success page
     * 3. User refreshes page
     * 4. Browser asks: "Resend form data?"
     * 5. Form submitted again! (duplicate registration)
     * 
     * Solution with PRG:
     * 1. User submits form (POST /register)
     * 2. Server processes
     * 3. Server redirects (GET /login)
     * 4. Browser loads login page
     * 5. User refreshes page
     * 6. Only GET /login is repeated (safe!)
     * 
     * Implementation:
     * @PostMapping("/register-process")
     * public String register(...) {
     *     // Process registration
     *     return "redirect:/login";  // PRG pattern
     * }
     * 
     * 
     * THEORY: Model vs RedirectAttributes
     * ------------------------------------
     * 
     * MODEL:
     * - Data for current request only
     * - Lost after redirect
     * - Use for: Rendering views
     * 
     * model.addAttribute("user", user);
     * return "registration";  // Data available in registration.html
     * 
     * REDIRECT ATTRIBUTES:
     * - Data survives redirect
     * - Stored in session temporarily
     * - Use for: Success/error messages after redirect
     * 
     * redirectAttributes.addFlashAttribute("message", "Success!");
     * return "redirect:/login";  // Message available in login page
     * 
     * 
     * THEORY: @RequestParam vs @PathVariable
     * ---------------------------------------
     * 
     * @RequestParam - Query parameters
     * /login?error=true&logout=true
     * 
     * @GetMapping("/login")
     * public String login(
     *     @RequestParam("error") String error,
     *     @RequestParam("logout") String logout) {
     *   ...
     * }
     * 
     * @PathVariable - URL path segments
     * /user/123/books/456
     * 
     * @GetMapping("/user/{userId}/books/{bookId}")
     * public String getBook(
     *     @PathVariable Long userId,
     *     @PathVariable Long bookId) {
     *   ...
     * }
     */
}
