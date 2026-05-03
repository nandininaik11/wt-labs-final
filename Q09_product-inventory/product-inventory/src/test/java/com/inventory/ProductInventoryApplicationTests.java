package com.inventory;

// ============================================================
// ProductInventoryApplicationTests.java - Task 7: Testing
//
// Basic test to verify the application context loads correctly.
// Run with: mvn test
// ============================================================

import org.junit.jupiter.api.Test;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.test.context.TestPropertySource;

// @SpringBootTest loads the full application context for testing
@SpringBootTest
// Use a test MongoDB URI to avoid depending on real MongoDB during tests
@TestPropertySource(properties = {
    "spring.data.mongodb.uri=mongodb://localhost:27017/inventorydb-test"
})
class ProductInventoryApplicationTests {

    // This test simply checks that the Spring application context loads without errors
    @Test
    void contextLoads() {
        // If this test passes, all beans (Controller, Repository, Security) loaded correctly
        System.out.println("✅ Application context loaded successfully!");
    }
}
