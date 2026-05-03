package com.inventory.model;

// ============================================================
// Product.java - Task 2: Create Document Class
// 
// A "Document" in MongoDB is like a row in SQL, but stored as JSON.
// @Document maps this Java class to a MongoDB collection.
// This is called an "Entity" in the ORM (Object-Relational Mapping) world.
// ============================================================

import org.springframework.data.annotation.Id;        // Spring Data annotation for primary key
import org.springframework.data.mongodb.core.mapping.Document; // Maps class to MongoDB collection
import lombok.AllArgsConstructor;  // Lombok: auto-generates constructor with ALL fields
import lombok.Data;                // Lombok: auto-generates getters, setters, toString, equals, hashCode
import lombok.NoArgsConstructor;   // Lombok: auto-generates empty constructor

@Data                              // Lombok: generates all getters/setters automatically
@AllArgsConstructor                // Lombok: generates constructor(id, name, category, price, quantity, description)
@NoArgsConstructor                 // Lombok: generates constructor() - required by MongoDB
@Document(collection = "products") // MongoDB: store these objects in a collection called "products"
public class Product {

    // @Id tells MongoDB/Spring: this field is the unique identifier (_id in MongoDB)
    // MongoDB will auto-generate a unique string ID if we don't provide one
    @Id
    private String id;

    // Product name (e.g., "Laptop", "Mouse")
    private String name;

    // Product category (e.g., "Electronics", "Furniture")
    private String category;

    // Product price (e.g., 49999.99)
    private double price;

    // How many items are in stock
    private int quantity;

    // Optional product description
    private String description;
}

/*
 * HOW THIS MAPS TO MONGODB:
 * 
 * Java Object:                     MongoDB Document (JSON):
 * Product {                   →    {
 *   id = "abc123"                     "_id": "abc123",
 *   name = "Laptop"                   "name": "Laptop",
 *   category = "Electronics"          "category": "Electronics",
 *   price = 49999.99                  "price": 49999.99,
 *   quantity = 5                      "quantity": 5,
 *   description = "Dell Laptop"       "description": "Dell Laptop"
 * }                              →  }
 * 
 * VIVA TIP: MongoDB stores data as BSON (Binary JSON).
 * Spring Data MongoDB automatically converts Java objects to BSON and back.
 */
