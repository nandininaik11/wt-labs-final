package com.bookstore;

/*
 * IMPORTS EXPLANATION
 * -------------------
 * These are the libraries and classes we need for this file
 */

// Spring Boot application annotation and main class
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

/**
 * MAIN APPLICATION CLASS
 * ======================
 * This is the entry point of the Spring Boot application
 * When you run this class, it starts the embedded Tomcat server
 * and initializes the Spring application context
 */

/*
 * @SpringBootApplication Annotation
 * ---------------------------------
 * This is a convenience annotation that combines three annotations:
 * 
 * 1. @Configuration: Marks this class as a source of bean definitions
 * 2. @EnableAutoConfiguration: Tells Spring Boot to automatically configure 
 *    your application based on dependencies in classpath
 * 3. @ComponentScan: Tells Spring to scan this package and sub-packages 
 *    for components (Controllers, Services, Repositories, etc.)
 */
@SpringBootApplication
public class BookstoreApplication {
    
    /**
     * MAIN METHOD
     * -----------
     * The entry point of any Java application
     * JVM looks for this method to start the program
     * 
     * @param args - Command line arguments (not used in this application)
     */
    public static void main(String[] args) {
        /*
         * SpringApplication.run() does the following:
         * 1. Creates an ApplicationContext (Spring container)
         * 2. Registers beans defined in configuration classes
         * 3. Starts the embedded Tomcat server
         * 4. Initializes database connections
         * 5. Sets up auto-configurations
         * 
         * Parameters:
         * - BookstoreApplication.class: The configuration class to load
         * - args: Command line arguments to pass to the application
         */
        SpringApplication.run(BookstoreApplication.class, args);
        
        /*
         * After this line executes, you'll see console output like:
         * "Started BookstoreApplication in X seconds"
         * "Tomcat started on port(s): 8080 (http)"
         * 
         * Your application is now running and accessible at:
         * http://localhost:8080
         */
        
        System.out.println("\n========================================");
        System.out.println("✓ Bookstore Application Started Successfully!");
        System.out.println("✓ Access the application at: http://localhost:8080");
        System.out.println("========================================\n");
    }
    
    /*
     * THEORY: How Spring Boot Works
     * ------------------------------
     * 
     * 1. APPLICATION STARTUP:
     *    - JVM executes main() method
     *    - SpringApplication.run() is called
     *    - Spring creates the application context
     * 
     * 2. COMPONENT SCANNING:
     *    - Spring scans all packages under com.bookstore
     *    - Finds classes with @Component, @Service, @Repository, @Controller
     *    - Creates instances (beans) of these classes
     * 
     * 3. DEPENDENCY INJECTION:
     *    - Spring resolves dependencies between beans
     *    - Injects dependencies automatically using @Autowired
     * 
     * 4. AUTO-CONFIGURATION:
     *    - Based on dependencies in pom.xml
     *    - Configures DataSource for MySQL
     *    - Sets up JPA/Hibernate
     *    - Configures Thymeleaf template engine
     *    - Starts embedded Tomcat server
     * 
     * 5. READY STATE:
     *    - Application is now ready to handle HTTP requests
     *    - Controllers are mapped to URLs
     *    - Database connection is established
     */
}
