package com.inventory.repository;

// ============================================================
// ProductRepository.java - Task 3: Create MongoRepository Interface
//
// Repository = The layer that talks to the database.
// MongoRepository gives us FREE CRUD methods without writing any SQL/queries!
// Just by extending it, we get: save(), findAll(), findById(), deleteById(), etc.
// ============================================================

import com.inventory.model.Product;
import org.springframework.data.mongodb.repository.MongoRepository;
import org.springframework.stereotype.Repository;
import java.util.List;

// @Repository tells Spring: "This is a database layer bean, manage it for me"
@Repository
// MongoRepository<Product, String>:
//   - Product = the document type we're managing
//   - String  = the type of the ID field (we used String @Id in Product.java)
public interface ProductRepository extends MongoRepository<Product, String> {

    // ---- FREE methods inherited from MongoRepository (no code needed!) ----
    // save(product)          → INSERT or UPDATE
    // findAll()              → SELECT * FROM products
    // findById(id)           → SELECT * WHERE _id = id
    // deleteById(id)         → DELETE WHERE _id = id
    // existsById(id)         → Check if a product exists
    // count()                → COUNT(*)

    // ---- Custom Query Methods (Spring Data auto-generates SQL/queries for these!) ----
    // Spring reads the method name and figures out the query automatically!

    // Find products by category: db.products.find({ category: "Electronics" })
    List<Product> findByCategory(String category);

    // Find products whose name contains a keyword (case-insensitive)
    // Equivalent to: db.products.find({ name: /keyword/i })
    List<Product> findByNameContainingIgnoreCase(String name);

    // Find products below a certain price: db.products.find({ price: { $lt: maxPrice } })
    List<Product> findByPriceLessThan(double price);
}

/*
 * VIVA TIP: This is called the "Repository Pattern".
 * It separates database logic from business logic.
 * 
 * Spring Data MongoDB uses "Query Derivation" - it reads method names like
 * findByCategory → generates → db.products.find({ "category": value })
 * findByPriceLessThan → generates → db.products.find({ "price": { $lt: value } })
 * 
 * No manual queries needed!
 */
