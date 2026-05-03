package com.ordermanagement;

// =============================================================================
// IMPORTS SECTION
// =============================================================================

// Spring Boot annotation and class
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

/**
 * =============================================================================
 * MAIN APPLICATION CLASS - SPRING BOOT ENTRY POINT
 * =============================================================================
 * 
 * This is the main class that starts the Spring Boot application
 * 
 * @SpringBootApplication:
 * - This is a composite annotation that combines three annotations:
 * 
 * 1. @Configuration:
 *    - Marks this class as a source of bean definitions
 *    - Beans are objects managed by Spring container
 * 
 * 2. @EnableAutoConfiguration:
 *    - Enables Spring Boot's auto-configuration mechanism
 *    - Automatically configures Spring application based on dependencies
 *    - Example: Detects H2 database and auto-configures DataSource
 * 
 * 3. @ComponentScan:
 *    - Scans for Spring components in current package and sub-packages
 *    - Finds and registers: @Controller, @Service, @Repository, @Component
 * 
 * COMPONENT SCANNING:
 * - Base package: com.ordermanagement
 * - Scanned packages:
 *   - com.ordermanagement (this package)
 *   - com.ordermanagement.entity (entity classes)
 *   - com.ordermanagement.repository (repository interfaces)
 *   - com.ordermanagement.controller (REST controllers)
 *   - All other sub-packages
 * 
 * SPRING BOOT STARTUP PROCESS:
 * 1. main() method is called
 * 2. SpringApplication.run() is executed
 * 3. Spring Boot scans for components
 * 4. Creates Spring ApplicationContext
 * 5. Auto-configures beans (DataSource, EntityManager, etc.)
 * 6. Starts embedded Tomcat server
 * 7. Deploys application on configured port (8080)
 * 8. Application is ready to accept HTTP requests
 * 
 * EMBEDDED SERVER:
 * - Tomcat server is embedded in application
 * - No external server installation needed
 * - Application runs as standalone JAR
 * - Server starts automatically with application
 */

@SpringBootApplication  // Marks this as Spring Boot application
                        // Equivalent to: @Configuration + @EnableAutoConfiguration + @ComponentScan
public class OrderManagementSystemApplication {

    /**
     * MAIN METHOD - Application Entry Point
     * 
     * This is the standard Java main method
     * JVM starts execution from here
     * 
     * @param args - Command line arguments (if any)
     * 
     * EXECUTION FLOW:
     * 1. JVM calls main() method
     * 2. SpringApplication.run() is invoked
     * 3. Spring Boot initializes application
     * 4. Web server starts
     * 5. Application becomes ready
     * 
     * CONSOLE OUTPUT:
     * You'll see logs like:
     * - Starting OrderManagementSystemApplication
     * - Started OrderManagementSystemApplication in X seconds
     * - Tomcat started on port(s): 8080 (http)
     */
    public static void main(String[] args) {
        /**
         * SpringApplication.run():
         * - Static method that launches Spring Boot application
         * - Parameters:
         *   1. Class with @SpringBootApplication annotation
         *   2. Command line arguments
         * 
         * Returns: ConfigurableApplicationContext
         * - Spring IoC container
         * - Contains all application beans
         * 
         * WHAT HAPPENS INSIDE run():
         * - Creates SpringApplication instance
         * - Prepares environment (loads properties)
         * - Creates ApplicationContext
         * - Scans and registers beans
         * - Auto-configures features
         * - Starts embedded web server
         * - Returns running application context
         */
        SpringApplication.run(OrderManagementSystemApplication.class, args);
        
        // After this line executes, application is running and ready
        // Server listens on http://localhost:8080
        // Application continues running until stopped (Ctrl+C)
    }

}

/**
 * =============================================================================
 * THEORY SUMMARY - SPRING BOOT ARCHITECTURE
 * =============================================================================
 * 
 * 1. SPRING BOOT:
 *    - Framework built on top of Spring Framework
 *    - Simplifies Spring application development
 *    - "Convention over Configuration" approach
 *    - Production-ready applications quickly
 * 
 * 2. KEY FEATURES:
 *    - Auto-configuration: Configures Spring automatically
 *    - Starter dependencies: Pre-configured dependency sets
 *    - Embedded server: Tomcat/Jetty/Undertow included
 *    - Production-ready features: Metrics, health checks
 *    - No XML configuration: Java-based configuration
 * 
 * 3. SPRING BOOT STARTERS:
 *    - spring-boot-starter-web: Web applications (REST APIs)
 *    - spring-boot-starter-data-jpa: JPA/Hibernate
 *    - spring-boot-starter-test: Testing frameworks
 *    - Many more for different purposes
 * 
 * 4. APPLICATION CONTEXT:
 *    - Spring IoC (Inversion of Control) container
 *    - Manages application components (beans)
 *    - Handles dependency injection
 *    - Controls bean lifecycle
 * 
 * 5. COMPONENT SCAN:
 *    - Automatically finds Spring components
 *    - Registers them as beans
 *    - Makes them available for dependency injection
 * 
 * 6. AUTO-CONFIGURATION:
 *    - Spring Boot detects dependencies
 *    - Configures beans automatically
 *    - Example: H2 dependency → DataSource bean created
 * 
 * 7. EMBEDDED SERVER:
 *    - No WAR deployment needed
 *    - Application runs as JAR
 *    - java -jar application.jar
 *    - Server included in JAR
 * 
 * 8. APPLICATION LAYERS:
 *    - Controller Layer: REST API endpoints (@RestController)
 *    - Service Layer: Business logic (@Service)
 *    - Repository Layer: Data access (@Repository)
 *    - Entity Layer: Domain models (@Entity)
 * 
 * 9. SPRING FRAMEWORK VS SPRING BOOT:
 *    Spring Framework:
 *    - Comprehensive Java framework
 *    - Manual configuration needed
 *    - XML or Java configuration
 *    - External server deployment
 *    
 *    Spring Boot:
 *    - Built on Spring Framework
 *    - Auto-configuration
 *    - Minimal configuration
 *    - Embedded server
 *    - Faster development
 * 
 * 10. BENEFITS OF SPRING BOOT:
 *     - Rapid development
 *     - Less boilerplate code
 *     - Production-ready defaults
 *     - Easy testing
 *     - Microservices-friendly
 *     - Large community support
 * 
 * =============================================================================
 * 
 * COMPARISON: TRADITIONAL JAVA WEB APP vs SPRING BOOT
 * 
 * Traditional:
 * - Install Tomcat server
 * - Write lots of configuration (XML)
 * - Create WAR file
 * - Deploy to server
 * - Configure database manually
 * - Write JDBC code
 * 
 * Spring Boot:
 * - No server installation
 * - Minimal/no configuration
 * - Create JAR file
 * - Run with java -jar
 * - Database auto-configured
 * - JPA/Hibernate auto-configured
 * 
 * =============================================================================
 */
