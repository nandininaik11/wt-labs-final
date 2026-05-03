package com.lab32.security.service;

// ================================================================
// UserDetailsServiceImpl.java — Authentication Bridge
// ================================================================
// THEORY (Unit IV – Spring Security):
//
// UserDetailsService is a Spring Security INTERFACE with one method:
//   loadUserByUsername(String username)
//
// Spring Security calls this method during EVERY login attempt:
//   1. User submits login form (username + password)
//   2. Spring Security calls loadUserByUsername(submittedUsername)
//   3. This method finds the user in OUR database
//   4. Returns UserDetails object wrapping our AppUser
//   5. Spring Security compares submittedPassword with stored hash
//      using BCryptPasswordEncoder.matches()
//   6. If match → Authentication successful → redirect to dashboard
//   7. If no match → Authentication failed → redirect to /login?error
//
// @Service = marks this as a Spring-managed service bean
// Spring auto-detects this via @ComponentScan
// ================================================================

import com.lab32.security.model.AppUser;
import com.lab32.security.repository.AppUserRepository;
import lombok.extern.slf4j.Slf4j;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.User;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;

import java.util.List;

// @Slf4j = Lombok: generates a Logger field named 'log'
// Equivalent to: private static final Logger log = LoggerFactory.getLogger(...);
@Slf4j
@Service
public class UserDetailsServiceImpl implements UserDetailsService {

    // Inject the repository (Spring handles this automatically)
    private final AppUserRepository userRepository;

    public UserDetailsServiceImpl(AppUserRepository userRepository) {
        this.userRepository = userRepository;
    }

    // ─────────────────────────────────────────────────────────
    // loadUserByUsername — Called by Spring Security on every login
    // This is how Task 3 works: "Authenticate users with encrypted passwords"
    // ─────────────────────────────────────────────────────────
    @Override
    public UserDetails loadUserByUsername(String username)
            throws UsernameNotFoundException {

        log.debug("Loading user by username: {}", username);

        // Find user in database using our repository
        // Optional.orElseThrow() → if not found, throw exception
        // Spring Security catches UsernameNotFoundException
        // and shows "Bad credentials" on the login page
        AppUser user = userRepository.findByUsername(username)
                .orElseThrow(() -> {
                    log.warn("User not found: {}", username);
                    return new UsernameNotFoundException(
                            "User not found: " + username);
                });

        log.debug("Found user: {}, enabled: {}", username, user.isEnabled());

        // Convert our AppUser → Spring Security's UserDetails interface
        // User.builder() = Spring Security's User class (not our AppUser)
        // This wrapper tells Spring Security:
        //   - What is the username?       → user.getUsername()
        //   - What is the hashed password? → user.getPassword()  [BCrypt hash]
        //   - What roles does the user have? → user.getRole()
        //
        // Spring Security will then call:
        //   BCryptPasswordEncoder.matches(submittedPassword, user.getPassword())
        // If true → login succeeds!
        return User.builder()
                .username(user.getUsername())
                // This is the BCRYPT HASH stored in DB — NOT the plain password!
                // Spring Security auto-calls passwordEncoder.matches() internally
                .password(user.getPassword())
                // Grant authority based on role (e.g. "ROLE_USER", "ROLE_ADMIN")
                .authorities(List.of(new SimpleGrantedAuthority(user.getRole())))
                // Account status flags
                .disabled(!user.isEnabled())
                .build();
    }
}
