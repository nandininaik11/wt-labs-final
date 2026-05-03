package com.security.logintracker.repository;

// JpaRepository gives us free CRUD methods: save(), findById(), findAll(), delete()
import com.security.logintracker.model.User;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import java.util.Optional;

@Repository  // Marks this as a Spring-managed DB access component
public interface UserRepository extends JpaRepository<User, Long> {
    // Spring Data JPA auto-implements this method from the name:
    // "find one User where username = ?" — no SQL needed!
    Optional<User> findByUsername(String username);
}
