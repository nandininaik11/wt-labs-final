package com.lab32.security.model;

// ================================================================
// RegisterRequest.java — Data Transfer Object (DTO)
// ================================================================
// THEORY:
// A DTO (Data Transfer Object) carries data between layers.
// This class holds the FORM DATA from the registration page.
// It is NOT an @Entity (not stored directly) — it's just a container
// that holds what the user typed in the form.
//
// Flow:
// HTML Form → RegisterRequest (DTO) → Service → AppUser (Entity saved to DB)
//
// Why separate DTO from Entity?
// - Entity has 'password' stored as BCrypt hash
// - DTO has 'password' + 'confirmPassword' as plain text (before hashing)
// - We validate the DTO, hash the password, THEN save as Entity
// ================================================================

import jakarta.validation.constraints.*;
import lombok.*;

// @Data = Lombok: generates @Getter + @Setter + @ToString + @EqualsAndHashCode
@Data
@NoArgsConstructor
@AllArgsConstructor
public class RegisterRequest {

    @NotBlank(message = "Username is required")
    @Size(min = 3, max = 50, message = "Username: 3 to 50 characters")
    private String username;

    @NotBlank(message = "Email is required")
    @Email(message = "Please enter a valid email address")
    private String email;

    // Plain text password (will be hashed before storing)
    @NotBlank(message = "Password is required")
    @Size(min = 6, message = "Password must be at least 6 characters")
    private String password;

    // Confirm password (compared with password field — must match)
    @NotBlank(message = "Please confirm your password")
    private String confirmPassword;

    // Custom method to check if passwords match
    // Called in the controller before saving
    public boolean passwordsMatch() {
        return password != null && password.equals(confirmPassword);
    }
}
