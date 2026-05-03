package com.security.logintracker.config;

import com.security.logintracker.model.User;
import com.security.logintracker.repository.UserRepository;
import org.springframework.boot.CommandLineRunner;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.crypto.password.PasswordEncoder;

/**
 * DataInitializer:
 * Runs once on startup to pre-populate the H2 database with demo users.
 * CommandLineRunner executes after the Spring context is fully loaded.
 */
@Configuration
public class DataInitializer {

    @Bean
    public CommandLineRunner initData(UserRepository userRepository,
                                      PasswordEncoder passwordEncoder) {
        return args -> {
            // Only add users if DB is empty (prevents duplicates on restart)
            if (userRepository.count() == 0) {

                // Admin user
                User admin = new User("admin",
                    passwordEncoder.encode("admin123"),  // BCrypt hash stored, not plain text
                    "admin@example.com");
                admin.setRole("ROLE_ADMIN");
                userRepository.save(admin);

                // Regular user
                User user = new User("john",
                    passwordEncoder.encode("john123"),
                    "john@example.com");
                userRepository.save(user);

                // Another user for testing
                User alice = new User("alice",
                    passwordEncoder.encode("alice123"),
                    "alice@example.com");
                userRepository.save(alice);

                System.out.println("=== Demo users created ===");
                System.out.println("admin / admin123");
                System.out.println("john  / john123");
                System.out.println("alice / alice123");
            }
        };
    }
}
