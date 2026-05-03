package com.security.logintracker;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

/**
 * LoginTrackerApplication:
 * The entry point of the entire Spring Boot application.
 *
 * @SpringBootApplication is a combo annotation that includes:
 *   - @Configuration     → this class is a Spring config file
 *   - @EnableAutoConfiguration → Spring Boot auto-configures beans based on classpath
 *   - @ComponentScan     → scans this package and sub-packages for Spring components
 */
@SpringBootApplication
public class LoginTrackerApplication {

    public static void main(String[] args) {
        // SpringApplication.run() bootstraps the entire Spring context
        SpringApplication.run(LoginTrackerApplication.class, args);
        System.out.println("\n===========================================");
        System.out.println("  Lab 33 - Login Tracker App Started!");
        System.out.println("  Visit: http://localhost:8080/login");
        System.out.println("  H2 DB:  http://localhost:8080/h2-console");
        System.out.println("===========================================\n");
    }
}
