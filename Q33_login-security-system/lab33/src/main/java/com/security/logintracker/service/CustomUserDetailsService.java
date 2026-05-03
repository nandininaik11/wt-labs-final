package com.security.logintracker.service;

import com.security.logintracker.model.User;
import com.security.logintracker.repository.UserRepository;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.*;
import org.springframework.stereotype.Service;
import java.util.List;

/**
 * CustomUserDetailsService:
 * Spring Security calls loadUserByUsername() during every login attempt.
 * We load the user from DB and return a UserDetails object.
 * The "accountNonLocked" flag here drives the "Account Locked" error.
 */
@Service
public class CustomUserDetailsService implements UserDetailsService {

    private final UserRepository userRepository;
    private final LoginAttemptService loginAttemptService;

    public CustomUserDetailsService(UserRepository userRepository,
                                    LoginAttemptService loginAttemptService) {
        this.userRepository = userRepository;
        this.loginAttemptService = loginAttemptService;
    }

    @Override
    public UserDetails loadUserByUsername(String username) throws UsernameNotFoundException {
        // Find user in DB; throw exception if not found
        User user = userRepository.findByUsername(username)
            .orElseThrow(() -> new UsernameNotFoundException("User not found: " + username));

        // If account is locked, check if the lock duration has expired
        if (!user.isAccountNonLocked()) {
            loginAttemptService.unlockWhenTimeExpired(user);
            // Re-fetch to get updated state after possible auto-unlock
            user = userRepository.findByUsername(username).orElseThrow();
        }

        // Return Spring's UserDetails object with role, lock status
        return new org.springframework.security.core.userdetails.User(
            user.getUsername(),
            user.getPassword(),
            true,                         // enabled
            true,                         // accountNonExpired
            true,                         // credentialsNonExpired
            user.isAccountNonLocked(),    // accountNonLocked ← KEY FIELD
            List.of(new SimpleGrantedAuthority(user.getRole()))
        );
    }
}
