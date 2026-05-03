package com.bookstore.config;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;

/**
 * SECURITY CONFIGURATION CLASS
 * =============================
 * Configures Spring Security for the application
 * Handles authentication and authorization
 */

/*
 * @Configuration Annotation
 * -------------------------
 * - Marks this as a Spring configuration class
 * - Contains @Bean methods
 * - Spring loads this class during startup
 */
@Configuration

/*
 * @EnableWebSecurity Annotation
 * -----------------------------
 * - Enables Spring Security's web security support
 * - Provides Spring MVC integration
 * - Allows customization of security settings
 */
@EnableWebSecurity
public class SecurityConfig {
    
    /**
     * SECURITY FILTER CHAIN CONFIGURATION
     * ------------------------------------
     * Defines security rules for the application
     * 
     * @param http - HttpSecurity object for configuration
     * @return Configured SecurityFilterChain
     * @throws Exception if configuration fails
     * 
     * This method configures:
     * 1. Which URLs require authentication
     * 2. Which URLs are publicly accessible
     * 3. Login/logout behavior
     * 4. CSRF protection
     */
    @Bean
    public SecurityFilterChain securityFilterChain(HttpSecurity http) throws Exception {
        
        /*
         * CONFIGURE HTTP SECURITY
         * -----------------------
         */
        http
            /*
             * AUTHORIZE REQUESTS
             * ------------------
             * Defines which URLs require authentication
             */
            .authorizeHttpRequests(auth -> auth
                
                /*
                 * PUBLIC URLs (No authentication required)
                 * ----------------------------------------
                 * These paths are accessible to everyone
                 * 
                 * requestMatchers() - Matches URL patterns
                 * permitAll() - Allows access without login
                 */
                
                // Home page - accessible to all
                .requestMatchers("/", "/home").permitAll()
                
                // Registration page and process
                .requestMatchers("/register", "/register-process").permitAll()
                
                // Login page
                .requestMatchers("/login").permitAll()
                
                // Static resources (CSS, JS, images)
                .requestMatchers("/css/**", "/js/**", "/images/**").permitAll()
                
                // Catalog page - public (users can browse without login)
                .requestMatchers("/catalog").permitAll()
                
                // Error pages
                .requestMatchers("/error").permitAll()
                
                /*
                 * PROTECTED URLs (Authentication required)
                 * ----------------------------------------
                 * These paths require user to be logged in
                 * 
                 * anyRequest() - All other requests
                 * authenticated() - Requires authentication
                 */
                .anyRequest().authenticated()
            )
            
            /*
             * FORM LOGIN CONFIGURATION
             * ------------------------
             * Customizes the login process
             */
            .formLogin(form -> form
                
                /*
                 * loginPage() - Custom login page URL
                 * Default is /login (Spring Security's default)
                 */
                .loginPage("/login")
                
                /*
                 * loginProcessingUrl() - URL that processes login form
                 * This is where the form submits to
                 * Spring Security intercepts this URL
                 */
                .loginProcessingUrl("/login-process")
                
                /*
                 * usernameParameter() - Name of username field in form
                 * Must match: <input name="username" />
                 */
                .usernameParameter("username")
                
                /*
                 * passwordParameter() - Name of password field in form
                 * Must match: <input name="password" />
                 */
                .passwordParameter("password")
                
                /*
                 * defaultSuccessUrl() - Where to go after successful login
                 * true = always redirect here
                 * false = go to originally requested page
                 */
                .defaultSuccessUrl("/catalog", true)
                
                /*
                 * failureUrl() - Where to go if login fails
                 * ?error parameter indicates login failure
                 */
                .failureUrl("/login?error=true")
                
                /*
                 * permitAll() - Allow access to login page for everyone
                 */
                .permitAll()
            )
            
            /*
             * LOGOUT CONFIGURATION
             * --------------------
             */
            .logout(logout -> logout
                
                /*
                 * logoutUrl() - URL to trigger logout
                 * Can be a link: <a href="/logout">Logout</a>
                 */
                .logoutUrl("/logout")
                
                /*
                 * logoutSuccessUrl() - Where to go after logout
                 */
                .logoutSuccessUrl("/login?logout=true")
                
                /*
                 * invalidateHttpSession() - Destroy session on logout
                 */
                .invalidateHttpSession(true)
                
                /*
                 * deleteCookies() - Remove these cookies on logout
                 */
                .deleteCookies("JSESSIONID")
                
                .permitAll()
            )
            
            /*
             * CSRF PROTECTION
             * ---------------
             * Cross-Site Request Forgery protection
             * 
             * CSRF Attack Example:
             * 1. User logs into banksite.com
             * 2. User visits malicious.com
             * 3. malicious.com contains:
             *    <form action="banksite.com/transfer" method="POST">
             *      <input name="amount" value="1000">
             *      <input name="to" value="hacker">
             *    </form>
             * 4. Form auto-submits using user's session
             * 5. Money transferred without user knowing
             * 
             * CSRF Token prevents this:
             * - Spring generates random token for each session
             * - Token must be included in forms
             * - Server validates token before processing
             * - Malicious site can't get the token
             * 
             * For development/testing, we can disable CSRF
             * In production, KEEP IT ENABLED!
             */
            .csrf(csrf -> csrf
                .disable()  // Disabled for simplicity in this demo
                            // In production: csrf.csrfTokenRepository(...)
            );
        
        return http.build();
    }
    
    
    /**
     * PASSWORD ENCODER BEAN
     * ---------------------
     * Creates and configures the password encoder
     * 
     * @return PasswordEncoder instance (BCrypt)
     * 
     * @Bean annotation tells Spring to:
     * 1. Create this object at startup
     * 2. Manage it as a singleton
     * 3. Make it available for dependency injection
     * 
     * This bean can be @Autowired in UserService
     */
    @Bean
    public PasswordEncoder passwordEncoder() {
        /*
         * BCryptPasswordEncoder
         * ---------------------
         * - Industry-standard password hashing
         * - Uses Blowfish cipher
         * - Automatically generates salt
         * - Configurable cost factor (default: 10)
         * 
         * Constructor:
         * BCryptPasswordEncoder()           - Default strength (10)
         * BCryptPasswordEncoder(strength)   - Custom strength (4-31)
         * 
         * Higher strength = More secure but slower
         * Strength 10 = ~0.1 seconds to hash
         * Strength 12 = ~0.4 seconds to hash
         * Strength 15 = ~3 seconds to hash
         * 
         * Recommended: 10-12 for web applications
         */
        return new BCryptPasswordEncoder();
    }
    
    
    /*
     * THEORY: Spring Security Architecture
     * -------------------------------------
     * 
     * 1. FILTER CHAIN:
     *    Every HTTP request passes through a chain of filters
     *    
     *    Request → [Filter 1] → [Filter 2] → ... → Controller
     *    
     *    Security Filters:
     *    - UsernamePasswordAuthenticationFilter (handles login)
     *    - LogoutFilter (handles logout)
     *    - ExceptionTranslationFilter (handles security exceptions)
     *    - FilterSecurityInterceptor (enforces authorization)
     * 
     * 2. AUTHENTICATION:
     *    Process of verifying who you are
     *    
     *    Flow:
     *    a) User submits username/password
     *    b) UsernamePasswordAuthenticationFilter intercepts
     *    c) Creates Authentication object
     *    d) AuthenticationManager authenticates
     *    e) UserDetailsService loads user from database
     *    f) PasswordEncoder verifies password
     *    g) If successful, creates authenticated Principal
     *    h) Stores in SecurityContext
     * 
     * 3. AUTHORIZATION:
     *    Process of checking what you can access
     *    
     *    Flow:
     *    a) User tries to access /admin/users
     *    b) FilterSecurityInterceptor checks SecurityContext
     *    c) Extracts user's authorities (roles)
     *    d) Compares with required authorities
     *    e) Allows or denies access
     * 
     * 4. SECURITY CONTEXT:
     *    Stores authentication information for current user
     *    
     *    SecurityContextHolder
     *      └── SecurityContext
     *            └── Authentication
     *                  ├── Principal (User details)
     *                  ├── Credentials (Password)
     *                  └── Authorities (Roles/Permissions)
     * 
     * 5. SESSION MANAGEMENT:
     *    Spring Security creates HttpSession after login
     *    
     *    Session Contents:
     *    - JSESSIONID cookie (identifies session)
     *    - SecurityContext (user authentication)
     *    - CSRF token
     * 
     * 
     * THEORY: CSRF Protection Explained
     * ----------------------------------
     * 
     * How CSRF Token Works:
     * 
     * 1. User logs in
     * 2. Server generates random token (e.g., "abc123xyz")
     * 3. Token stored in session
     * 4. Token included in forms as hidden field:
     *    <input type="hidden" name="_csrf" value="abc123xyz">
     * 5. When form submitted, server checks:
     *    - Does token match session?
     *    - If yes → Process request
     *    - If no → Reject (403 Forbidden)
     * 
     * With Thymeleaf, token is auto-added to forms:
     * <form th:action="@{/submit}" method="post">
     *   <!-- CSRF token auto-added by Thymeleaf -->
     * </form>
     * 
     * 
     * THEORY: URL Pattern Matching
     * -----------------------------
     * 
     * requestMatchers() supports various patterns:
     * 
     * Exact match:
     *   "/login" → Only /login
     * 
     * Wildcard (*):
     *   "/css/*" → /css/style.css, /css/main.css
     *              But NOT /css/admin/style.css
     * 
     * Recursive wildcard (**):
     *   "/css/**" → /css/style.css, /css/admin/style.css
     *               Matches all subdirectories
     * 
     * Multiple patterns:
     *   requestMatchers("/login", "/register", "/home")
     * 
     * HTTP Methods:
     *   requestMatchers(HttpMethod.POST, "/api/**")
     *   → Only POST requests to /api/**
     */
}
