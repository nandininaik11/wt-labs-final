package com.lab32.security.config;

// ================================================================
// SecurityConfig.java — Spring Security Configuration
// ================================================================
// THEORY (Unit IV – Spring Security):
//
// This is the MOST IMPORTANT file for Lab Q32.
// It configures:
//   1. BCryptPasswordEncoder — Task 1: Configure password encoder
//   2. UserDetailsService   — Task 3: Authenticate users
//   3. HTTP Security Rules  — Who can access what URLs
//   4. Login/Logout config  — Task 4: Verify password during login
//
// Spring Security intercepts EVERY HTTP request and checks:
//   a. Is the URL publicly accessible?
//   b. If protected: is the user authenticated?
//   c. If authenticated: do they have the right role?
//
// @Configuration = this class provides Spring Bean definitions
// @EnableWebSecurity = enables Spring Security for this app
// ================================================================

import com.lab32.security.service.UserDetailsServiceImpl;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.dao.DaoAuthenticationProvider;
import org.springframework.security.config.annotation.authentication.configuration.AuthenticationConfiguration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;

@Configuration            // This class contains @Bean method definitions
@EnableWebSecurity        // Enable Spring Security — intercept all HTTP requests
public class SecurityConfig {

    // Inject our UserDetailsService (loads user from DB during login)
    private final UserDetailsServiceImpl userDetailsService;

    // Constructor injection — preferred way in Spring (over @Autowired on field)
    public SecurityConfig(UserDetailsServiceImpl userDetailsService) {
        this.userDetailsService = userDetailsService;
    }

    // ────────────────────────────────────────────────────────
    // TASK 1: Configure BCryptPasswordEncoder
    // ────────────────────────────────────────────────────────
    // @Bean = register this object in the Spring IoC container
    // Other classes can @Autowired or inject this by type
    //
    // BCrypt is a password hashing function with these properties:
    // 1. SLOW by design (cost factor prevents brute force)
    // 2. Salted automatically (different hash for same password each time)
    // 3. One-way (cannot reverse the hash to get the password)
    // 4. Strength parameter: BCryptPasswordEncoder(12) → 2^12 rounds
    //    Default strength = 10 → 2^10 = 1024 hashing rounds
    //
    // Example:
    //   encode("password123")
    //   → "$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy"
    //   Every call produces a DIFFERENT hash (random salt embedded in the hash)
    //   But matches("password123", hash) always returns true!
    // ────────────────────────────────────────────────────────
    @Bean
    public PasswordEncoder passwordEncoder() {
        // Strength 10 = 2^10 rounds = default, good balance of security vs speed
        // Strength 12 = 2^12 rounds = more secure, ~4x slower
        return new BCryptPasswordEncoder(10);
    }

    // ────────────────────────────────────────────────────────
    // Authentication Provider
    // Wires together: UserDetailsService + PasswordEncoder
    // Spring Security uses this to authenticate login attempts:
    //   1. Load user from DB using UserDetailsService (by username)
    //   2. Compare submitted password with stored hash using BCrypt
    // ────────────────────────────────────────────────────────
    @Bean
    public DaoAuthenticationProvider authenticationProvider() {
        DaoAuthenticationProvider provider = new DaoAuthenticationProvider();

        // Tell it which service loads users from the database
        provider.setUserDetailsService(userDetailsService);

        // Tell it which encoder to use for password comparison
        // This is how Tasks 3 & 4 work:
        //   BCrypt.matches(submittedPassword, storedHash)
        provider.setPasswordEncoder(passwordEncoder());

        return provider;
    }

    // AuthenticationManager exposes the authentication mechanism
    // (needed for programmatic authentication in tests/API)
    @Bean
    public AuthenticationManager authenticationManager(
            AuthenticationConfiguration config) throws Exception {
        return config.getAuthenticationManager();
    }

    // ────────────────────────────────────────────────────────
    // HTTP Security — URL Access Rules, Login/Logout Config
    // SecurityFilterChain = the chain of security filters applied
    // to every HTTP request
    // ────────────────────────────────────────────────────────
    @Bean
    public SecurityFilterChain filterChain(HttpSecurity http) throws Exception {

        http
            // ── Authorization Rules ─────────────────────────
            .authorizeHttpRequests(auth -> auth
                // Publicly accessible URLs (no login required):
                .requestMatchers(
                    "/",           // Home page
                    "/register",   // Registration page
                    "/login",      // Login page
                    "/css/**",     // Static CSS files
                    "/js/**",      // Static JS files
                    "/h2-console/**" // H2 database console
                ).permitAll()

                // All other URLs require authentication:
                .anyRequest().authenticated()
            )

            // ── Login Configuration ─────────────────────────
            // Task 4: Verify password validation during login
            .formLogin(form -> form
                .loginPage("/login")              // Custom login page URL
                .loginProcessingUrl("/login")     // Form submits here (POST)
                .usernameParameter("username")    // HTML input name for username
                .passwordParameter("password")   // HTML input name for password
                .defaultSuccessUrl("/dashboard", true) // Redirect after login
                .failureUrl("/login?error=true")  // Redirect if login fails
                .permitAll()
            )

            // ── Logout Configuration ─────────────────────────
            .logout(logout -> logout
                .logoutUrl("/logout")             // POST to this URL to logout
                .logoutSuccessUrl("/login?logout=true") // After logout
                .invalidateHttpSession(true)      // Destroy session data
                .clearAuthentication(true)        // Clear security context
                .permitAll()
            )

            // ── H2 Console Configuration ─────────────────────
            // H2 console uses iframes, which CSRF blocks by default
            // frameOptions().sameOrigin() allows iframes from same domain
            .headers(headers -> headers
                .frameOptions(frame -> frame.sameOrigin())
            )

            // ── CSRF Configuration ───────────────────────────
            // CSRF = Cross-Site Request Forgery protection
            // Disabled only for H2 console (it doesn't send CSRF tokens)
            // In production: NEVER disable CSRF for your own pages
            .csrf(csrf -> csrf
                .ignoringRequestMatchers("/h2-console/**")
            )

            // Wire in our authentication provider
            .authenticationProvider(authenticationProvider());

        return http.build();
    }
}
