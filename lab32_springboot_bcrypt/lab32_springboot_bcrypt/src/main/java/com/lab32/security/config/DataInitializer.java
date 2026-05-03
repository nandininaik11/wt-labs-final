package com.lab32.security.config;

// ================================================================
// DataInitializer.java — Seed Demo Data on Startup
// ================================================================
// THEORY:
// CommandLineRunner = an interface Spring Boot runs AFTER
// the application context is fully initialized.
// Perfect for seeding initial data.
//
// On startup, this creates 3 demo users:
//   - admin  / admin123  → ROLE_ADMIN
//   - alice  / alice123  → ROLE_USER
//   - bob    / bob123    → ROLE_USER
//
// All passwords are BCrypt-hashed before storing.
// You can verify in H2 console: SELECT * FROM APP_USER;
// ================================================================

import com.lab32.security.model.AppUser;
import com.lab32.security.repository.AppUserRepository;
import lombok.extern.slf4j.Slf4j;
import org.springframework.boot.CommandLineRunner;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.crypto.password.PasswordEncoder;

@Slf4j
@Configuration
public class DataInitializer {

    // @Bean CommandLineRunner: runs once after Spring Boot starts
    @Bean
    public CommandLineRunner initData(AppUserRepository repo,
                                      PasswordEncoder encoder) {
        return args -> {
            log.info("=== Initializing demo users ===");

            // Only seed if no users exist (prevents duplicate on restart if using file DB)
            if (repo.count() > 0) {
                log.info("Users already exist, skipping seed.");
                return;
            }

            // ── Create Admin User ─────────────────────────────
            String adminPlain = "admin123";
            String adminHash  = encoder.encode(adminPlain);
            AppUser admin = AppUser.builder()
                    .username("admin")
                    .email("admin@lab32.com")
                    .password(adminHash)       // BCrypt hash stored in DB
                    .role("ROLE_ADMIN")
                    .enabled(true)
                    .build();
            repo.save(admin);
            log.info("Created admin | plain: {} | hash: {}", adminPlain, adminHash);

            // ── Create Alice (regular user) ───────────────────
            String alicePlain = "alice123";
            String aliceHash  = encoder.encode(alicePlain);
            AppUser alice = AppUser.builder()
                    .username("alice")
                    .email("alice@lab32.com")
                    .password(aliceHash)
                    .role("ROLE_USER")
                    .enabled(true)
                    .build();
            repo.save(alice);
            log.info("Created alice | plain: {} | hash: {}", alicePlain, aliceHash);

            // ── Create Bob (regular user) ─────────────────────
            String bobPlain = "bob123";
            String bobHash  = encoder.encode(bobPlain);
            AppUser bob = AppUser.builder()
                    .username("bob")
                    .email("bob@lab32.com")
                    .password(bobHash)
                    .role("ROLE_USER")
                    .enabled(true)
                    .build();
            repo.save(bob);
            log.info("Created bob | plain: {} | hash: {}", bobPlain, bobHash);

            log.info("=== Demo users created. Total users: {} ===", repo.count());
            log.info("Login credentials:");
            log.info("  admin / admin123 (ROLE_ADMIN)");
            log.info("  alice / alice123 (ROLE_USER)");
            log.info("  bob   / bob123   (ROLE_USER)");
        };
    }
}
