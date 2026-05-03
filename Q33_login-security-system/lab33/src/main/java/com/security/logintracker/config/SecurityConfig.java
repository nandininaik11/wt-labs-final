package com.security.logintracker.config;

import com.security.logintracker.service.CustomUserDetailsService;
import com.security.logintracker.service.LoginAttemptService;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.authentication.*;
import org.springframework.security.config.annotation.authentication.configuration.AuthenticationConfiguration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.AuthenticationException;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;
import org.springframework.security.web.authentication.*;

import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.io.IOException;

/**
 * SecurityConfig:
 * This is the HEART of Spring Security.
 * We define: who can access what, what happens on login success/failure,
 * and how passwords are encoded.
 */
@Configuration       // Tells Spring: this class contains @Bean definitions
@EnableWebSecurity   // Activates Spring Security for this app
public class SecurityConfig {

    private final CustomUserDetailsService userDetailsService;
    private final LoginAttemptService loginAttemptService;

    public SecurityConfig(CustomUserDetailsService userDetailsService,
                          LoginAttemptService loginAttemptService) {
        this.userDetailsService = userDetailsService;
        this.loginAttemptService = loginAttemptService;
    }

    /**
     * PasswordEncoder Bean:
     * BCrypt is a one-way hashing algorithm — passwords are NEVER stored in plain text.
     * BCrypt adds a "salt" automatically to prevent rainbow table attacks.
     */
    @Bean
    public PasswordEncoder passwordEncoder() {
        return new BCryptPasswordEncoder();
    }

    /**
     * SecurityFilterChain:
     * Defines the HTTP security rules — which URLs need auth, custom login/logout.
     */
    @Bean
    public SecurityFilterChain filterChain(HttpSecurity http) throws Exception {
        http
            // ---- URL ACCESS RULES ----
            .authorizeHttpRequests(auth -> auth
                .requestMatchers("/register", "/login", "/css/**", "/h2-console/**").permitAll() // public pages
                .anyRequest().authenticated()  // all other pages need login
            )

            // ---- CUSTOM LOGIN FORM ----
            .formLogin(form -> form
                .loginPage("/login")                      // our custom login page URL
                .usernameParameter("username")
                .passwordParameter("password")
                .successHandler(authenticationSuccessHandler())   // custom success handler
                .failureHandler(authenticationFailureHandler())   // custom failure handler
                .permitAll()
            )

            // ---- LOGOUT ----
            .logout(logout -> logout
                .logoutUrl("/logout")
                .logoutSuccessUrl("/login?logout")
                .permitAll()
            )

            // ---- H2 CONSOLE (disable security restrictions for it) ----
            .csrf(csrf -> csrf
                .ignoringRequestMatchers("/h2-console/**")
            )
            .headers(headers -> headers
                .frameOptions(frame -> frame.sameOrigin()) // H2 console uses iframes
            );

        return http.build();
    }

    /**
     * Success Handler:
     * Called when login credentials are CORRECT.
     * We reset the failed counter and redirect to /dashboard.
     */
    @Bean
    public AuthenticationSuccessHandler authenticationSuccessHandler() {
        return new SimpleUrlAuthenticationSuccessHandler("/dashboard") {
            @Override
            public void onAuthenticationSuccess(HttpServletRequest request,
                                                HttpServletResponse response,
                                                Authentication authentication)
                    throws IOException, jakarta.servlet.ServletException {
                // Reset counter on successful login
                loginAttemptService.loginSucceeded(authentication.getName());
                super.onAuthenticationSuccess(request, response, authentication);
            }
        };
    }

    /**
     * Failure Handler:
     * Called when login FAILS (wrong password, locked account, etc.)
     * We inspect the exception type to show the correct error message.
     */
    @Bean
    public AuthenticationFailureHandler authenticationFailureHandler() {
        return (request, response, exception) -> {
            String username = request.getParameter("username");
            String errorParam;

            if (exception instanceof LockedException) {
                // Account is locked — don't increment counter again
                errorParam = "locked";
            } else if (exception instanceof BadCredentialsException) {
                // Wrong password — increment failed attempt counter
                loginAttemptService.loginFailed(username);
                errorParam = "bad_credentials";
            } else if (exception instanceof DisabledException) {
                errorParam = "disabled";
            } else {
                errorParam = "error";
            }

            response.sendRedirect("/login?error=" + errorParam + "&username=" + username);
        };
    }

    /**
     * AuthenticationManager Bean:
     * Required to wire together UserDetailsService + PasswordEncoder
     */
    @Bean
    public AuthenticationManager authenticationManager(
            AuthenticationConfiguration authConfig) throws Exception {
        return authConfig.getAuthenticationManager();
    }
}
