package com.inventory.security;

// ============================================================
// SecurityConfig.java - Task 4 & 5: Spring Security + Basic Authentication
//
// Spring Security by default protects ALL endpoints.
// We configure it here to use HTTP Basic Authentication.
//
// Basic Auth = username:password sent as Base64 in the HTTP header
// Example Header: Authorization: Basic YWRtaW46YWRtaW4xMjM=
//                 (Base64 of "admin:admin123")
// ============================================================

import org.springframework.context.annotation.Bean;           // @Bean = register this as a Spring-managed object
import org.springframework.context.annotation.Configuration;  // @Configuration = this class has Spring config
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.core.userdetails.User;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;   // Hashes passwords securely
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.provisioning.InMemoryUserDetailsManager; // Stores users in memory (not DB)
import org.springframework.security.web.SecurityFilterChain;
import org.springframework.http.HttpMethod;

@Configuration         // Marks this as a configuration class
@EnableWebSecurity     // Activates Spring Security's web security support
public class SecurityConfig {

    // ---- Task 5: Configure HTTP Basic Authentication ----
    // SecurityFilterChain: A chain of security filters applied to every HTTP request
    @Bean
    public SecurityFilterChain securityFilterChain(HttpSecurity http) throws Exception {
        http
            // Disable CSRF (Cross-Site Request Forgery) for REST APIs
            // CSRF is needed for browser forms, but REST APIs use tokens/Basic Auth instead
            .csrf(csrf -> csrf.disable())

            // Define access rules for different endpoints
            .authorizeHttpRequests(auth -> auth
                // Allow GET requests (read-only) for anyone with valid credentials
                .requestMatchers(HttpMethod.GET, "/api/products/**").authenticated()
                // All other requests (POST, PUT, DELETE) require authentication too
                .anyRequest().authenticated()
            )

            // Enable HTTP Basic Authentication
            // This makes the browser/Postman show a login popup or accept Authorization header
            .httpBasic(httpBasic -> {});

        return http.build();
    }

    // ---- Define Users (In-Memory for simplicity) ----
    // In production, you'd load users from a database!
    @Bean
    public UserDetailsService userDetailsService() {
        // Admin user: can do everything (CRUD)
        UserDetails admin = User.builder()
            .username("admin")                                  // Username: admin
            .password(passwordEncoder().encode("admin123"))     // Password: admin123 (stored as hash)
            .roles("ADMIN")                                     // Role: ADMIN
            .build();

        // Regular user: read-only access
        UserDetails user = User.builder()
            .username("user")
            .password(passwordEncoder().encode("user123"))
            .roles("USER")
            .build();

        // InMemoryUserDetailsManager stores these users in application memory
        // (not in the database - just for demo purposes)
        return new InMemoryUserDetailsManager(admin, user);
    }

    // ---- Password Encoder: BCrypt hashing ----
    // Never store plain-text passwords! BCrypt hashes them securely.
    // "admin123" → "$2a$10$..." (irreversible hash)
    @Bean
    public PasswordEncoder passwordEncoder() {
        return new BCryptPasswordEncoder();
    }
}

/*
 * HOW BASIC AUTH WORKS (for Viva):
 * 
 * 1. Client sends request with header:
 *    Authorization: Basic Base64("admin:admin123")
 *    = Authorization: Basic YWRtaW46YWRtaW4xMjM=
 *
 * 2. Spring Security decodes it, looks up the user, verifies password hash
 *
 * 3. If valid → request proceeds to the controller
 *    If invalid → 401 Unauthorized response
 *
 * Security Layers (Filter Chain):
 * Request → CSRF Filter → Auth Filter → Authorization Filter → Controller
 */
