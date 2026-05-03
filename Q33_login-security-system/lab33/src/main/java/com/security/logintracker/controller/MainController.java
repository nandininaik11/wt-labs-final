package com.security.logintracker.controller;

import com.security.logintracker.model.User;
import com.security.logintracker.repository.UserRepository;
import com.security.logintracker.service.LoginAttemptService;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import java.util.List;
import java.util.Optional;

/**
 * MainController:
 * Handles HTTP GET/POST requests and returns Thymeleaf template names.
 * @Controller = MVC Controller (returns view names, not JSON)
 */
@Controller
public class MainController {

    private final UserRepository userRepository;
    private final PasswordEncoder passwordEncoder;
    private final LoginAttemptService loginAttemptService;

    public MainController(UserRepository userRepository,
                          PasswordEncoder passwordEncoder,
                          LoginAttemptService loginAttemptService) {
        this.userRepository = userRepository;
        this.passwordEncoder = passwordEncoder;
        this.loginAttemptService = loginAttemptService;
    }

    // ---- HOME ----
    @GetMapping("/")
    public String home() {
        return "redirect:/dashboard"; // redirect to dashboard (requires auth)
    }

    // ---- LOGIN PAGE ----
    // GET /login — show the login form
    @GetMapping("/login")
    public String loginPage(@RequestParam(required = false) String error,
                            @RequestParam(required = false) String username,
                            @RequestParam(required = false) String logout,
                            Model model) {

        // Add error message based on error type passed in URL query param
        if ("bad_credentials".equals(error)) {
            // Try to find how many attempts remain
            Optional<User> userOpt = userRepository.findByUsername(username != null ? username : "");
            if (userOpt.isPresent()) {
                int attempts = userOpt.get().getFailedAttempts();
                int remaining = loginAttemptService.getMaxFailedAttempts() - attempts;
                if (remaining > 0) {
                    model.addAttribute("errorMsg",
                        "Invalid username or password. " + remaining + " attempt(s) remaining before lock.");
                } else {
                    model.addAttribute("errorMsg",
                        "Account has been locked. Please try after " +
                        loginAttemptService.getLockDurationMinutes() + " minutes.");
                }
            } else {
                model.addAttribute("errorMsg", "Invalid username or password.");
            }
        } else if ("locked".equals(error)) {
            model.addAttribute("errorMsg",
                "Your account is locked due to multiple failed attempts. " +
                "It will auto-unlock after " + loginAttemptService.getLockDurationMinutes() + " minutes.");
        } else if ("disabled".equals(error)) {
            model.addAttribute("errorMsg", "Your account is disabled. Contact administrator.");
        } else if ("error".equals(error)) {
            model.addAttribute("errorMsg", "Login failed. Please try again.");
        }

        if (logout != null) {
            model.addAttribute("logoutMsg", "You have been logged out successfully.");
        }

        return "login"; // returns src/main/resources/templates/login.html
    }

    // ---- DASHBOARD (after successful login) ----
    @GetMapping("/dashboard")
    public String dashboard(@AuthenticationPrincipal UserDetails userDetails, Model model) {
        // @AuthenticationPrincipal injects the currently logged-in user
        String username = userDetails.getUsername();
        Optional<User> userOpt = userRepository.findByUsername(username);

        model.addAttribute("username", username);
        model.addAttribute("role", userDetails.getAuthorities());
        userOpt.ifPresent(u -> model.addAttribute("email", u.getEmail()));

        return "dashboard";
    }

    // ---- REGISTER PAGE ----
    @GetMapping("/register")
    public String registerPage() {
        return "register";
    }

    @PostMapping("/register")
    public String registerUser(@RequestParam String username,
                               @RequestParam String password,
                               @RequestParam String email,
                               Model model) {

        // Check if username already taken
        if (userRepository.findByUsername(username).isPresent()) {
            model.addAttribute("errorMsg", "Username already exists. Choose another.");
            return "register";
        }

        // Hash the password before saving (NEVER store plain text)
        User newUser = new User(username, passwordEncoder.encode(password), email);
        userRepository.save(newUser);

        model.addAttribute("successMsg", "Registration successful! Please login.");
        return "redirect:/login";
    }

    // ---- ADMIN: VIEW ALL USERS (only accessible by ADMIN) ----
    @GetMapping("/admin/users")
    public String adminUsers(Model model) {
        List<User> users = userRepository.findAll();
        model.addAttribute("users", users);
        model.addAttribute("maxAttempts", loginAttemptService.getMaxFailedAttempts());
        model.addAttribute("lockMinutes", loginAttemptService.getLockDurationMinutes());
        return "admin-users";
    }

    // ---- ADMIN: MANUALLY UNLOCK A USER ----
    @PostMapping("/admin/unlock/{id}")
    public String unlockUser(@PathVariable Long id) {
        userRepository.findById(id).ifPresent(user -> {
            user.setAccountNonLocked(true);
            user.setFailedAttempts(0);
            user.setLockTime(null);
            userRepository.save(user);
            System.out.println("[ADMIN] Manually unlocked user: " + user.getUsername());
        });
        return "redirect:/admin/users";
    }
}
