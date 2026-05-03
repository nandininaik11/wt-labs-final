package com.lab32.security.repository;

// ================================================================
// AppUserRepository.java — Data Access Layer (Repository)
// ================================================================
// THEORY (Unit IV – Spring Boot / JPA):
//
// Repository pattern = a clean interface between your application
// and the database. You don't write SQL — Spring generates it!
//
// JpaRepository<AppUser, Long>:
//   AppUser = the Entity class this repo manages
//   Long    = the type of the primary key (id field)
//
// Spring Data JPA automatically provides these methods:
//   save(entity)        → INSERT or UPDATE
//   findById(id)        → SELECT WHERE id = ?
//   findAll()           → SELECT * FROM app_user
//   delete(entity)      → DELETE WHERE id = ?
//   count()             → SELECT COUNT(*)
//   existsById(id)      → SELECT EXISTS(...)
//
// MAGIC: Spring generates the SQL at runtime just from
// the METHOD NAME! No implementation class needed.
// "findByUsername" → Spring sees "find...By...Username"
// → generates: SELECT * FROM app_user WHERE username = ?
// ================================================================

import com.lab32.security.model.AppUser;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

// @Repository marks this as a Spring-managed Data Access Object
// Spring creates a proxy implementation automatically
@Repository
public interface AppUserRepository extends JpaRepository<AppUser, Long> {

    // Spring generates SQL from method name:
    // findByUsername → SELECT * FROM app_user WHERE username = ?
    // Returns Optional<AppUser> — empty if user not found
    // Optional prevents NullPointerException
    Optional<AppUser> findByUsername(String username);

    // Spring generates: SELECT * FROM app_user WHERE email = ?
    Optional<AppUser> findByEmail(String email);

    // Spring generates: SELECT COUNT(*) > 0 FROM app_user WHERE username = ?
    boolean existsByUsername(String username);

    // Spring generates: SELECT COUNT(*) > 0 FROM app_user WHERE email = ?
    boolean existsByEmail(String email);

    // Note: No implementation code needed!
    // Spring Data JPA implements all these automatically.
}
