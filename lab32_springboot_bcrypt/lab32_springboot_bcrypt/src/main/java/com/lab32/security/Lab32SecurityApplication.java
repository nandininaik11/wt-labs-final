package com.lab32.security;

// ================================================================
// Lab32SecurityApplication.java — Application Entry Point
// ================================================================
// This is the MAIN CLASS — the starting point of the Spring Boot app.
// When you run `mvn spring-boot:run`, Java calls main() here.
//
// THEORY (Unit IV – Spring Boot):
// @SpringBootApplication is a "meta-annotation" combining:
//   1. @Configuration     — This class defines Spring Beans (configs)
//   2. @EnableAutoConfiguration — Auto-configure based on classpath
//      (sees spring-security on classpath → auto-configures security)
//   3. @ComponentScan     — Scans this package and sub-packages
//      for @Component, @Service, @Repository, @Controller classes
// ================================================================

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

// @SpringBootApplication: The magic annotation that bootstraps everything
@SpringBootApplication
public class Lab32SecurityApplication {

    // main() is the Java entry point (like public static void main in any Java program)
    // SpringApplication.run() starts:
    //   1. Embedded Tomcat server (no separate Tomcat install needed!)
    //   2. Spring Application Context (the IoC container)
    //   3. Auto-configuration (security, JPA, thymeleaf etc.)
    //   4. CommandLineRunner beans (our DataInitializer below)
    public static void main(String[] args) {
        SpringApplication.run(Lab32SecurityApplication.class, args);
        System.out.println("\n===================================");
        System.out.println("  Lab Q32: BCrypt Password Demo");
        System.out.println("  Open: http://localhost:8080");
        System.out.println("  H2 DB: http://localhost:8080/h2-console");
        System.out.println("===================================\n");
    }
}
