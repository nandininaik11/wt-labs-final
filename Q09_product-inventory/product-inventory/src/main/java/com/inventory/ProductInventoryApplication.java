package com.inventory;

// ============================================================
// ProductInventoryApplication.java - Main Entry Point
//
// This is where the Spring Boot application starts.
// @SpringBootApplication does THREE things at once:
//   1. @Configuration      - marks this as a config class
//   2. @EnableAutoConfiguration - Spring auto-configures MongoDB, Security, etc.
//   3. @ComponentScan      - scans this package for @Controller, @Service, @Repository
// ============================================================

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

@SpringBootApplication  // Magic annotation that bootstraps the entire Spring Boot app
public class ProductInventoryApplication {

    public static void main(String[] args) {
        // SpringApplication.run() starts:
        // 1. Embedded Tomcat web server on port 8080
        // 2. Connects to MongoDB
        // 3. Registers all beans (Controller, Repository, Security)
        SpringApplication.run(ProductInventoryApplication.class, args);
        System.out.println("✅ Product Inventory System Started!");
        System.out.println("📍 API running at: http://localhost:8080/api/products");
        System.out.println("🔐 Use Basic Auth: admin/admin123 or user/user123");
    }
}

/*
 * VIVA TIP - Spring Boot vs Spring Framework:
 * Spring Framework = Manual configuration (XML files, lots of setup)
 * Spring Boot      = Auto-configuration (just add dependencies, it configures itself!)
 * 
 * Spring Boot uses "Convention over Configuration":
 * - Detects MongoDB on classpath → auto-configures MongoDB connection
 * - Detects Spring Security → auto-enables authentication
 * - Detects web starter → starts Tomcat automatically
 */
