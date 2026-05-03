package com.lab32.security.controller;

// ================================================================
// AuthController.java — Web Layer (MVC Controller)
// ================================================================
// THEORY (Unit IV – Spring Boot MVC):
//
// Spring MVC follows the Model-View-Controller pattern:
//   MODEL    = data (AppUser, RegisterRequest)
//   VIEW     = HTML templates (Thymeleaf .html files)
//   CONTROLLER = handles HTTP requests, prepares data, returns view name
//
// @Controller = Spring-managed web controller
//   Methods return VIEW NAMES (template filenames without .html)
//   Model adds data that Thymeleaf renders in the template
//
// vs @RestController = returns JSON/XML (no HTML templates)
//   Used for REST APIs (Postman testing)
//
// Request mapping annotations:
//   @GetMapping("/url")    → handles HTTP GET  (view page)
//   @PostMapping("/url")   → handles HTTP POST (submit form)
//   @RequestParam          → reads URL parameter (?error=true)
//   @Valid @ModelAttribute → binds + validates form data to DTO
// ================================================================

import com.lab32.security.model.AppUser;
import com.lab32.security.model.RegisterRequest;
import com.lab32.security.service.UserService;
import jakarta.validation.Valid;
import lombok.extern.slf4j.Slf4j;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.List;

@Slf4j
@Controller   // Marks this class as a Spring MVC Controller
public class AuthController {

    private final UserService userService;

    // Constructor injection
    public AuthController(UserService userService) {
        this.userService = userService;
    }

    // ─────────────────────────────────────────────────────────
    // HOME PAGE
    // GET / → redirect to /dashboard if logged in, else /login
    // ─────────────────────────────────────────────────────────
    @GetMapping("/")
    public String home() {
        // Check if user is already authenticated
        Authentication auth = SecurityContextHolder.getContext().getAuthentication();
        if (auth != null && auth.isAuthenticated()
                && !auth.getName().equals("anonymousUser")) {
            return "redirect:/dashboard";  // Go to dashboard if logged in
        }
        return "redirect:/login";          // Go to login if not
    }

    // ─────────────────────────────────────────────────────────
    // LOGIN PAGE
    // GET /login → show login form
    // POST /login → handled automatically by Spring Security
    //   (we configured loginProcessingUrl("/login") in SecurityConfig)
    // ─────────────────────────────────────────────────────────
    @GetMapping("/login")
    public String loginPage(
            @RequestParam(value = "error",  required = false) String error,
            @RequestParam(value = "logout", required = false) String logout,
            Model model) {

        // @RequestParam reads URL query parameters:
        // /login?error=true  → error = "true"
        // /login?logout=true → logout = "true"

        if (error != null) {
            // Task 4: Display authentication result (failure)
            model.addAttribute("errorMsg",
                "❌ Invalid username or password. Please check your credentials.");
        }
        if (logout != null) {
            model.addAttribute("successMsg", "✅ You have been logged out successfully.");
        }

        return "login";  // → renders templates/login.html
    }

    // ─────────────────────────────────────────────────────────
    // DASHBOARD (Protected page — requires login)
    // Task 5: Display authentication results
    // ─────────────────────────────────────────────────────────
    @GetMapping("/dashboard")
    public String dashboard(Model model, Authentication authentication) {
        // Authentication = Spring Security's object containing
        // the currently logged-in user's info
        // It's injected automatically by Spring (method parameter injection)

        String username = authentication.getName();  // Who is logged in?
        String role     = authentication.getAuthorities().iterator().next().getAuthority();

        log.info("Dashboard accessed by: {} with role: {}", username, role);

        // Find this user in database to get full details
        userService.findByUsername(username).ifPresent(user -> {
            model.addAttribute("currentUser", user);
            // Task 5: Show the BCrypt hash stored in DB for this user
            model.addAttribute("storedHash", user.getPassword());
        });

        model.addAttribute("username", username);
        model.addAttribute("role", role);

        // Stats for dashboard
        model.addAttribute("totalUsers", userService.getUserCount());

        return "dashboard";  // → renders templates/dashboard.html
    }

    // ─────────────────────────────────────────────────────────
    // REGISTRATION PAGE
    // GET /register → show registration form
    // ─────────────────────────────────────────────────────────
    @GetMapping("/register")
    public String registerPage(Model model) {
        // Add empty RegisterRequest to the model so Thymeleaf
        // can bind the form fields to it (th:object="${registerRequest}")
        model.addAttribute("registerRequest", new RegisterRequest());
        return "register";  // → renders templates/register.html
    }

    // ─────────────────────────────────────────────────────────
    // REGISTRATION FORM SUBMISSION
    // POST /register → validate, hash password, save user
    // Task 2: Store encrypted passwords in database
    // ─────────────────────────────────────────────────────────
    @PostMapping("/register")
    public String processRegister(
            @Valid @ModelAttribute("registerRequest") RegisterRequest request,
            // @Valid triggers validation annotations (@NotBlank, @Size, @Email)
            // @ModelAttribute binds HTML form fields to RegisterRequest object
            BindingResult bindingResult,
            // BindingResult holds validation errors from @Valid
            Model model,
            RedirectAttributes redirectAttributes) {
            // RedirectAttributes passes data to the page after redirect

        // Check if JSR-303 validation failed (@NotBlank, @Size, @Email etc.)
        if (bindingResult.hasErrors()) {
            log.warn("Registration validation errors: {}", bindingResult.getAllErrors());
            return "register";  // Return to form with errors shown
        }

        // Check passwords match (custom validation not handled by annotations)
        if (!request.passwordsMatch()) {
            model.addAttribute("passwordError", "Passwords do not match!");
            return "register";
        }

        try {
            // Call service to hash password and save user
            AppUser saved = userService.registerUser(request);
            log.info("Registration successful for: {}", saved.getUsername());

            // RedirectAttributes adds flash attributes (survive one redirect)
            redirectAttributes.addFlashAttribute("successMsg",
                    "✅ Registration successful! Welcome, " + saved.getUsername()
                    + "! Please log in.");

            return "redirect:/login";  // PRG pattern: redirect after POST

        } catch (IllegalArgumentException e) {
            // Username or email already exists
            model.addAttribute("errorMsg", "❌ " + e.getMessage());
            return "register";
        }
    }

    // ─────────────────────────────────────────────────────────
    // USERS LIST PAGE (Admin only in real app — open for demo)
    // Shows all users with their BCrypt hashes stored in DB
    // Task 2 + Task 5 demonstration
    // ─────────────────────────────────────────────────────────
    @GetMapping("/users")
    public String usersList(Model model, Authentication authentication) {
        List<AppUser> users = userService.getAllUsers();
        model.addAttribute("users", users);
        model.addAttribute("username", authentication.getName());
        return "users";  // → renders templates/users.html
    }

    // ─────────────────────────────────────────────────────────
    // BCrypt DEMO PAGE — Interactive demonstration
    // Task 1, 4: Show encoder config and password verification
    // ─────────────────────────────────────────────────────────
    @GetMapping("/demo")
    public String demoPage(Model model, Authentication authentication) {
        model.addAttribute("username", authentication.getName());
        // Pre-populate with an example
        model.addAttribute("sampleHash",
                userService.encodePasswordDemo("demo123"));
        return "demo";
    }

    // POST handler for BCrypt demo form
    @PostMapping("/demo")
    public String processDemoForm(
            @RequestParam("rawPassword") String rawPassword,
            @RequestParam(value = "hashToVerify", required = false) String hashToVerify,
            @RequestParam("action") String action,
            Model model,
            Authentication authentication) {

        model.addAttribute("username", authentication.getName());
        model.addAttribute("rawPassword", rawPassword);

        if ("encode".equals(action)) {
            // Encode the submitted password
            String hash1 = userService.encodePasswordDemo(rawPassword);
            String hash2 = userService.encodePasswordDemo(rawPassword);
            // Encode same password TWICE — show they produce DIFFERENT hashes!
            // (Different random salts each time — but both verify correctly)
            model.addAttribute("hash1", hash1);
            model.addAttribute("hash2", hash2);
            model.addAttribute("sameInput", rawPassword);
            model.addAttribute("showEncodeResult", true);

        } else if ("verify".equals(action) && hashToVerify != null) {
            // Task 4: Verify password against provided hash
            boolean matches = userService.matchesDemo(rawPassword, hashToVerify);
            model.addAttribute("hashToVerify", hashToVerify);
            model.addAttribute("verifyResult", matches);
            model.addAttribute("showVerifyResult", true);
        }

        model.addAttribute("sampleHash",
                userService.encodePasswordDemo("demo123"));
        return "demo";
    }
}
