# Step 1: Start MongoDB
net start MongoDB

# Step 2: Build
mvn clean install -DskipTests

# Step 3: Run
mvn spring-boot:run


# 🛒 Product Inventory Management System
## Spring Boot + MongoDB + Spring Security

---

## 📁 FILE STRUCTURE

```
product-inventory/
├── pom.xml                                          ← Maven config (like package.json)
├── README.md                                        ← This file
└── src/
    ├── main/
    │   ├── java/com/inventory/
    │   │   ├── ProductInventoryApplication.java     ← Main class (app starts here)
    │   │   ├── model/
    │   │   │   └── Product.java                     ← Task 2: Document class
    │   │   ├── repository/
    │   │   │   └── ProductRepository.java           ← Task 3: MongoRepository
    │   │   ├── controller/
    │   │   │   └── ProductController.java           ← Task 6: REST APIs
    │   │   └── security/
    │   │       └── SecurityConfig.java              ← Task 4+5: Spring Security
    │   └── resources/
    │       └── application.properties              ← Task 1: MongoDB Config
    └── test/
        └── java/com/inventory/
            └── ProductInventoryApplicationTests.java ← Task 7: Tests
```

---

## ⚙️ SETUP AND RUN COMMANDS

### Prerequisites (install these first)
1. **Java 17+** → https://adoptopenjdk.net/
2. **Maven 3.6+** → https://maven.apache.org/download.cgi
3. **MongoDB** → https://www.mongodb.com/try/download/community
4. **VS Code** with extensions: "Extension Pack for Java", "Spring Boot Extension Pack"
5. **Postman** → https://www.postman.com/downloads/

### Step 1: Start MongoDB
```bash
# On Windows (run as Administrator):
net start MongoDB

# On Mac:
brew services start mongodb-community

# Or just run directly:
mongod --dbpath "C:/data/db"       # Windows
mongod --dbpath /usr/local/var/mongodb  # Mac
```

### Step 2: Verify MongoDB is running
```bash
# Open another terminal:
mongosh
# You should see: "connecting to: mongodb://127.0.0.1:27017/"
# Type: show dbs   (to see databases)
# Type: exit
```

### Step 3: Open project in VS Code
```bash
# Extract the zip, then:
cd product-inventory
code .
```

### Step 4: Build the project
```bash
# In VS Code terminal (Ctrl + `) or any terminal in project folder:
mvn clean install -DskipTests
```

### Step 5: Run the application
```bash
mvn spring-boot:run

# OR in VS Code: 
# Open ProductInventoryApplication.java → Click "Run" button above main()
```

### Step 6: Verify it's running
Look for this output in the console:
```
✅ Product Inventory System Started!
📍 API running at: http://localhost:8080/api/products
🔐 Use Basic Auth: admin/admin123 or user/user123
```

---

## 🖥️ TASK 7: TESTING WITH POSTMAN

### Setup Postman for Basic Auth:
1. Open Postman
2. Go to **Authorization** tab
3. Select **Type: Basic Auth**
4. Enter Username: `admin`, Password: `admin123`

---

### API 1: CREATE Product (POST)
- **Method:** POST
- **URL:** `http://localhost:8080/api/products`
- **Headers:** Content-Type: application/json
- **Body (raw JSON):**
```json
{
  "name": "Laptop",
  "category": "Electronics",
  "price": 55000.00,
  "quantity": 10,
  "description": "Dell Inspiron 15"
}
```
- **Expected Response (201 Created):**
```json
{
  "id": "64abc123def456",
  "name": "Laptop",
  "category": "Electronics",
  "price": 55000.0,
  "quantity": 10,
  "description": "Dell Inspiron 15"
}
```

---

### API 2: GET All Products
- **Method:** GET
- **URL:** `http://localhost:8080/api/products`
- **Expected Response (200 OK):**
```json
[
  {
    "id": "64abc123def456",
    "name": "Laptop",
    "category": "Electronics",
    "price": 55000.0,
    "quantity": 10,
    "description": "Dell Inspiron 15"
  }
]
```

---

### API 3: GET Product by ID
- **Method:** GET
- **URL:** `http://localhost:8080/api/products/64abc123def456`
  *(replace with actual ID from Create response)*
- **Expected Response (200 OK):** Single product JSON

---

### API 4: UPDATE Product (PUT)
- **Method:** PUT
- **URL:** `http://localhost:8080/api/products/64abc123def456`
- **Body:**
```json
{
  "name": "Laptop Pro",
  "category": "Electronics",
  "price": 65000.00,
  "quantity": 8,
  "description": "Dell XPS 15 Updated"
}
```
- **Expected Response (200 OK):** Updated product JSON

---

### API 5: DELETE Product
- **Method:** DELETE
- **URL:** `http://localhost:8080/api/products/64abc123def456`
- **Expected Response (200 OK):** `"Product deleted successfully!"`

---

### API 6: Test Without Auth (should fail!)
- Remove the Authorization from Postman
- Try any request
- **Expected Response (401 Unauthorized)**

---

### API 7: Search by Category
- **Method:** GET
- **URL:** `http://localhost:8080/api/products/category/Electronics`

### API 8: Search by Name
- **Method:** GET
- **URL:** `http://localhost:8080/api/products/search?name=lap`

---

## 📖 COMPLETE THEORY (WT Syllabus - Unit IV)

### 1. What is Spring Framework?
Spring is a Java framework that solves common enterprise problems:
- Dependency Injection (DI): "Don't call us, we'll call you" — Spring creates and injects objects
- Aspect-Oriented Programming (AOP): Cross-cutting concerns (logging, security) separate from business logic
- Without Spring: new ProductRepository(); (manual) → With Spring: @Autowired (automatic)

### 2. What is Spring Boot?
Spring Boot = Spring Framework + Auto-configuration + Embedded Server
- No need for web.xml, applicationContext.xml
- Just add @SpringBootApplication and it configures everything
- Embedded Tomcat: no need to deploy WAR to separate server
- "Opinionated defaults": sensible defaults that you can override

### 3. What is Maven?
Maven is a build tool (like npm for Node.js):
- pom.xml = package.json (defines dependencies)
- mvn install = npm install (downloads jars)
- mvn spring-boot:run = node server.js (runs app)

### 4. What is MongoDB?
MongoDB is a NoSQL database:
- Stores data as JSON-like documents (BSON)
- No fixed schema (flexible structure)
- vs SQL: No tables, no rows, no JOINs
- Collections ≈ Tables, Documents ≈ Rows, Fields ≈ Columns
- Scales horizontally (add more servers easily)

### 5. What is Spring Data MongoDB?
- Abstraction layer over MongoDB Java Driver
- MongoRepository provides CRUD methods automatically
- Maps Java objects (@Document) to MongoDB documents
- Query derivation: method names → MongoDB queries

### 6. What is Spring Security?
- Authentication = Who are you? (login with username/password)
- Authorization = What can you do? (ADMIN vs USER roles)
- Filter Chain: Every request passes through security filters
- Basic Auth: Credentials in Authorization header as Base64
- BCrypt: Password hashing algorithm (one-way, cannot be reversed)

### 7. What is REST API?
REST = Representational State Transfer
- Stateless: Each request is independent (no session on server)
- Uses HTTP methods as verbs (GET=read, POST=create, PUT=update, DELETE=remove)
- Resources identified by URLs (/api/products/123)
- Returns JSON (or XML)

### 8. What is Postman?
- API testing tool
- Send HTTP requests without writing code
- Test all CRUD operations, set auth headers, view responses
- Can save requests as Collections for reuse

---

## ❓ LIKELY VIVA QUESTIONS + ANSWERS

**Q: What is Spring Boot? How is it different from Spring?**
A: Spring Boot is an opinionated extension of Spring that provides auto-configuration, embedded servers (Tomcat), and production-ready features. Spring requires manual XML configuration; Spring Boot eliminates boilerplate with @SpringBootApplication and smart defaults.

**Q: What is @SpringBootApplication?**
A: It's a meta-annotation combining three annotations: @Configuration (marks the class as a source of bean definitions), @EnableAutoConfiguration (auto-configures based on classpath), and @ComponentScan (scans for beans in the package).

**Q: What is MongoDB? Why NoSQL?**
A: MongoDB is a document-oriented NoSQL database storing data as BSON (binary JSON). Advantages: flexible schema, horizontal scaling, handles unstructured data, faster reads for document-centric data. Disadvantage: no ACID transactions by default (though newer versions support it).

**Q: What is @Document annotation?**
A: @Document(collection="products") maps a Java class to a MongoDB collection. Like @Entity in JPA for SQL databases. It tells Spring Data MongoDB how to serialize/deserialize the object.

**Q: What does MongoRepository provide?**
A: It's a Spring Data interface that auto-generates CRUD operations: save(), findAll(), findById(), deleteById(), existsById(), count(). We also get query derivation: custom methods like findByCategory() are automatically implemented by Spring based on the method name.

**Q: What is Basic Authentication?**
A: HTTP Basic Auth sends credentials as Base64-encoded "username:password" in the Authorization header. Example: Authorization: Basic YWRtaW46YWRtaW4xMjM=. It's simple but should always be used over HTTPS since Base64 is reversible.

**Q: What is BCrypt?**
A: BCrypt is a password hashing function. It's one-way (cannot decrypt), includes a salt (random data to prevent rainbow table attacks), and has an adjustable cost factor (slows down brute-force attacks). Never store plain-text passwords!

**Q: Difference between @Controller and @RestController?**
A: @Controller returns view names (for HTML templates like Thymeleaf). @RestController = @Controller + @ResponseBody, meaning it returns JSON/XML directly instead of view names. Used for REST APIs.

**Q: What is @Autowired?**
A: @Autowired enables Dependency Injection. Instead of manually creating objects with new, Spring automatically finds and injects the required bean. This promotes loose coupling and testability.

**Q: What are HTTP status codes?**
A: 200 OK (success), 201 Created (new resource), 400 Bad Request (invalid input), 401 Unauthorized (not authenticated), 403 Forbidden (no permission), 404 Not Found, 500 Internal Server Error.

**Q: What is @PathVariable vs @RequestParam?**
A: @PathVariable reads from URL path: /products/{id} → @PathVariable String id. @RequestParam reads from query string: /products?name=laptop → @RequestParam String name.

**Q: What is the SecurityFilterChain?**
A: It's a series of filters applied to every HTTP request before it reaches the controller. Filters check authentication, CSRF tokens, headers, etc. Spring Security's filter chain intercepts requests and enforces security rules.

**Q: What is CSRF and why did we disable it?**
A: CSRF (Cross-Site Request Forgery) is an attack where a malicious site tricks a user into making unwanted requests. CSRF protection adds tokens to forms. For REST APIs (stateless, no browser forms), we disable CSRF because we use token-based (Basic Auth/JWT) authentication instead.

**Q: What is the difference between PUT and PATCH?**
A: PUT replaces the entire resource with the new data. PATCH updates only specific fields. PUT is idempotent (same result every time); PATCH may not be. In our app, PUT requires sending all fields.

**Q: What is Maven and what is pom.xml?**
A: Maven is a build automation tool for Java. pom.xml (Project Object Model) is the config file listing all dependencies, build plugins, and project metadata. Maven downloads JAR files from the Maven Central repository.
