package com.security.logintracker.model;

// @Entity tells JPA: "Map this Java class to a database table"
import jakarta.persistence.*;
import lombok.Data;
import lombok.NoArgsConstructor;
import java.time.LocalDateTime;

@Entity                        // This class = a DB table called "app_user"
@Table(name = "app_user")      // Explicit table name (avoid clash with SQL keyword "user")
@Data                          // Lombok: auto-generates getters, setters, toString, equals, hashCode
@NoArgsConstructor             // Lombok: generates empty constructor (required by JPA)
public class User {

    @Id                                                    // Primary key
    @GeneratedValue(strategy = GenerationType.IDENTITY)    // Auto-increment ID
    private Long id;

    @Column(nullable = false, unique = true)  // username must be unique in DB
    private String username;

    @Column(nullable = false)
    private String password;                  // Stored as BCrypt hash, never plain text

    @Column(nullable = false)
    private String email;

    // ---- ACCOUNT LOCK FIELDS ----

    private int failedAttempts;              // Counter: increments on each wrong password

    private boolean accountNonLocked = true; // true = unlocked, false = locked

    private LocalDateTime lockTime;          // Timestamp when account was locked

    // ---- ROLE ----
    private String role = "ROLE_USER";       // Spring Security needs "ROLE_" prefix

    // Custom constructor for easy creation
    public User(String username, String password, String email) {
        this.username = username;
        this.password = password;
        this.email = email;
    }
}
