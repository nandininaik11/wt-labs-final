package com.lab32.security.model;

// ================================================================
// AppUser.java — JPA Entity (Database Table Mapping)
// ================================================================
// THEORY (Unit IV – Spring Boot / JPA):
//
// JPA (Java Persistence API) = standard way to map Java objects to DB tables
// @Entity → tells JPA "this class = one table in the database"
// Each field = one column in the table
// JPA + Hibernate automatically creates the table from this class
//
// This is the ORM (Object-Relational Mapping) concept:
//   Java Object  ←→  Database Table Row
//   AppUser      ←→  APP_USER table
//   id field     ←→  ID column
//   username     ←→  USERNAME column
//   password     ←→  PASSWORD column (stores BCrypt hash)
//
// LOMBOK annotations auto-generate boilerplate Java code:
// @Getter → generates all getters (getId(), getUsername() etc.)
// @Setter → generates all setters
// @NoArgsConstructor → generates AppUser() constructor
// @AllArgsConstructor → generates AppUser(id, username, password, role) constructor
// ================================================================

import jakarta.persistence.*;          // JPA annotations
import jakarta.validation.constraints.*; // Validation annotations
import lombok.*;                       // Code generation annotations

@Entity                                // This Java class maps to a DB table
@Table(name = "app_user")             // Table name in the database
@Getter                                // Lombok: generate getId(), getUsername() etc.
@Setter                                // Lombok: generate setId(), setUsername() etc.
@NoArgsConstructor                     // Lombok: generate AppUser() constructor
@AllArgsConstructor                    // Lombok: generate AppUser(all fields) constructor
@Builder                               // Lombok: enables builder pattern: AppUser.builder().username("alice").build()
public class AppUser {

    // ── Primary Key ─────────────────────────────────────────
    @Id                                // This field is the PRIMARY KEY
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    // IDENTITY = auto-increment (1, 2, 3...) — like AUTO_INCREMENT in MySQL
    private Long id;

    // ── Username ─────────────────────────────────────────────
    @Column(unique = true, nullable = false, length = 50)
    // unique = only one user can have this username
    // nullable = false → NOT NULL in SQL
    // length = 50 → VARCHAR(50)
    @NotBlank(message = "Username is required")
    // @NotBlank = validation: cannot be null or empty string
    @Size(min = 3, max = 50, message = "Username must be 3-50 characters")
    private String username;

    // ── Password ─────────────────────────────────────────────
    // IMPORTANT: This stores the BCrypt HASH, NOT the plain password!
    // Example value: "$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy"
    // It NEVER stores "password123" directly — always the hashed version
    @Column(nullable = false, length = 255)
    // BCrypt hash is always 60 characters, but 255 gives safety margin
    @NotBlank(message = "Password is required")
    private String password;

    // ── Email ─────────────────────────────────────────────────
    @Column(unique = true, nullable = false, length = 100)
    @NotBlank(message = "Email is required")
    @Email(message = "Please provide a valid email address")
    // @Email = validates email format (must contain @ and domain)
    private String email;

    // ── Role ──────────────────────────────────────────────────
    // Spring Security uses roles for authorization
    // Common values: "ROLE_USER", "ROLE_ADMIN"
    // The "ROLE_" prefix is required by Spring Security
    @Column(nullable = false, length = 20)
    private String role = "ROLE_USER"; // Default role for all users

    // ── Enabled flag ──────────────────────────────────────────
    // If false, user cannot log in (account disabled)
    @Column(nullable = false)
    private boolean enabled = true;

    // ── toString() for logging (excludes password for security!) ──
    @Override
    public String toString() {
        // NEVER include password in toString/logs — security risk!
        return "AppUser{id=" + id + ", username='" + username
               + "', email='" + email + "', role='" + role + "'}";
    }
}
