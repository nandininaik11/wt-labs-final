mvn clean install -DskipTests
mvn spring-boot:run
mvn -version



### **1. Install Postman**
- Download from: https://www.postman.com/downloads/
- Or use **Postman Web** (no installation): https://web.postman.com/

---

## **🚀 Order Management System - Typical REST Endpoints**

Based on your tasks, you likely have these endpoints:

| Method | Endpoint | Description |
|--------|----------|-------------|
| **POST** | `http://localhost:8080/api/orders` | Create new order |
| **GET** | `http://localhost:8080/api/orders` | Get all orders |
| **GET** | `http://localhost:8080/api/orders/{id}` | Get order by ID |
| **PUT** | `http://localhost:8080/api/orders/{id}` | Update order |
| **DELETE** | `http://localhost:8080/api/orders/{id}` | Delete order |

---

## **📝 Step-by-Step Postman Testing**

### **Step 1: Start Your Spring Boot App**

```powershell
cd "order-management-system"
mvn spring-boot:run
```

Wait for:
```
Tomcat started on port(s): 8080
```

---

### **Step 2: Test in Postman**

#### **A) CREATE Order (POST)**

1. Open Postman
2. Set method to **POST**
3. URL: `http://localhost:8080/api/orders`
4. Go to **Headers** tab:
   - Key: `Content-Type`
   - Value: `application/json`
5. Go to **Body** tab:
   - Select **raw**
   - Select **JSON** from dropdown
6. Enter JSON:

```json
{
  "customerName": "John Doe",
  "productName": "Laptop",
  "quantity": 2,
  "price": 50000.00,
  "orderDate": "2026-05-02"
}
```

7. Click **Send**

**Expected Response (201 Created):**
```json
{
  "id": 1,
  "customerName": "John Doe",
  "productName": "Laptop",
  "quantity": 2,
  "price": 50000.00,
  "totalAmount": 100000.00,
  "orderDate": "2026-05-02",
  "status": "PENDING"
}
```

---

#### **B) GET All Orders (GET)**

1. Method: **GET**
2. URL: `http://localhost:8080/api/orders`
3. Click **Send**

**Expected Response (200 OK):**
```json
[
  {
    "id": 1,
    "customerName": "John Doe",
    "productName": "Laptop",
    "quantity": 2,
    "price": 50000.00,
    "totalAmount": 100000.00
  },
  {
    "id": 2,
    "customerName": "Jane Smith",
    "productName": "Phone",
    "quantity": 1,
    "price": 30000.00,
    "totalAmount": 30000.00
  }
]
```

---

#### **C) GET Order by ID (GET)**

1. Method: **GET**
2. URL: `http://localhost:8080/api/orders/1`
3. Click **Send**

**Expected Response (200 OK):**
```json
{
  "id": 1,
  "customerName": "John Doe",
  "productName": "Laptop",
  "quantity": 2,
  "price": 50000.00,
  "totalAmount": 100000.00
}
```

---

#### **D) UPDATE Order (PUT)**

1. Method: **PUT**
2. URL: `http://localhost:8080/api/orders/1`
3. **Headers**: `Content-Type: application/json`
4. **Body** (raw JSON):

```json
{
  "customerName": "John Doe Updated",
  "productName": "Laptop Pro",
  "quantity": 3,
  "price": 55000.00,
  "orderDate": "2026-05-02",
  "status": "CONFIRMED"
}
```

5. Click **Send**

**Expected Response (200 OK):**
```json
{
  "id": 1,
  "customerName": "John Doe Updated",
  "productName": "Laptop Pro",
  "quantity": 3,
  "price": 55000.00,
  "totalAmount": 165000.00,
  "status": "CONFIRMED"
}
```

---

#### **E) DELETE Order (DELETE)**

1. Method: **DELETE**
2. URL: `http://localhost:8080/api/orders/1`
3. Click **Send**

**Expected Response (200 OK or 204 No Content):**
```json
{
  "message": "Order deleted successfully"
}
```

Or just status **204** with no body.

---

## **📸 Screenshots for Practical Exam**

Take screenshots showing:

1. **POST request** creating an order → 201 response
2. **GET all** showing list of orders → 200 response
3. **GET by ID** showing single order → 200 response
4. **PUT request** updating an order → 200 response
5. **DELETE request** removing an order → 200/204 response
6. **GET all again** showing order is deleted

---

## **🎯 Complete Testing Sequence**

```
1. POST /api/orders         → Create order (ID: 1)
2. POST /api/orders         → Create order (ID: 2)
3. POST /api/orders         → Create order (ID: 3)
4. GET /api/orders          → Shows all 3 orders
5. GET /api/orders/2        → Shows order 2 details
6. PUT /api/orders/2        → Update order 2
7. GET /api/orders/2        → Verify update worked
8. DELETE /api/orders/1     → Delete order 1
9. GET /api/orders          → Shows only orders 2 and 3
```

---

## **🐛 Common Postman Errors**

### **Error: Connection Refused**
- ❌ App is not running
- ✅ Run: `mvn spring-boot:run`

### **Error: 404 Not Found**
- ❌ Wrong URL
- ✅ Check: `http://localhost:8080/api/orders` (not `/order`)

### **Error: 400 Bad Request**
- ❌ Invalid JSON format
- ✅ Check JSON syntax, use JSON validator

### **Error: 415 Unsupported Media Type**
- ❌ Missing `Content-Type: application/json` header
- ✅ Add it in Headers tab

---

## **💡 Pro Tip: Save Postman Collection**

1. Click **Collections** (left sidebar)
2. Click **+ New Collection**
3. Name it: "Order Management System"
4. Save each request in the collection
5. Export collection → Share with examiner if needed

---

**Yes, you MUST use Postman for Task 3!** It's the standard tool for testing REST APIs in Spring Boot projects. 🚀


# 🎓 ORDER MANAGEMENT SYSTEM - Spring Boot REST API

## Lab Question 26: Order Management System with REST APIs

---

## 📋 Quick Navigation

1. [Project Overview](#-project-overview)
2. [Prerequisites](#-prerequisites)
3. [VS Code Setup](#-vs-code-setup--commands)
4. [File Structure](#-file-structure)
5. [Running the Application](#-running-the-application)
6. [Testing with Postman](#-testing-with-postman)
7. [Expected Output](#-expected-output)
8. [WT Syllabus Theory](#-wt-syllabus-brief-theory)
9. [Viva Questions & Answers](#-viva-questions--answers)

---

## 🎯 PROJECT OVERVIEW

A complete Spring Boot REST API application for managing customer orders.

### ✅ Features Implemented:
- **CREATE** - Add new orders (POST /api/orders)
- **READ** - Get all orders / Get specific order (GET)
- **UPDATE** - Modify orders (PUT, PATCH)
- **DELETE** - Remove orders (DELETE)
- **SEARCH** - Filter by customer, status, product

### 🛠 Tech Stack:
- Spring Boot 3.2.0
- Java 17
- Maven (Build Tool)
- H2 Database (In-Memory)
- JPA/Hibernate (ORM)
- Lombok (Reduce boilerplate)

---

## 📌 PREREQUISITES

Before running this project, you need:

1. **Java Development Kit (JDK) 17 or higher**
   - Download from: https://www.oracle.com/java/technologies/downloads/
   - Or use OpenJDK: https://adoptium.net/

2. **Maven 3.6+** (Build tool)
   - Download from: https://maven.apache.org/download.cgi

3. **VS Code** with extensions:
   - Extension Pack for Java
   - Spring Boot Extension Pack

4. **Postman** (For testing APIs)
   - Download from: https://www.postman.com/downloads/

---

## 🚀 VS CODE SETUP & COMMANDS

### Step 1: Install Java and Verify
```bash
# Check Java version (should be 17+)
java -version

# Check Maven version
mvn -version
```

### Step 2: Open Project in VS Code
```bash
# Navigate to project directory
cd order-management-system

# Open in VS Code
code .
```

### Step 3: Install Dependencies
```bash
# Install all Maven dependencies
mvn clean install

# This will:
# - Download all dependencies from Maven Central
# - Compile the code
# - Run tests (if any)
# - Create target folder with compiled classes
```

### Step 4: Run the Application

**Option 1: Using Maven Command**
```bash
mvn spring-boot:run
```

**Option 2: Using VS Code**
1. Open `OrderManagementSystemApplication.java`
2. Click "Run" button above the `main` method
3. Or press `F5`

**Option 3: Run JAR file**
```bash
# First build the JAR
mvn clean package

# Then run it
java -jar target/order-management-system-1.0.0.jar
```

### Step 5: Verify Application is Running
- Application starts on port 8080
- Look for this message in console:
```
Started OrderManagementSystemApplication in X.XXX seconds
Tomcat started on port(s): 8080 (http)
```

### Step 6: Access H2 Database Console (Optional)
- URL: http://localhost:8080/h2-console
- JDBC URL: jdbc:h2:mem:orderdb
- Username: sa
- Password: (leave empty)

---

## 📁 FILE STRUCTURE

```
order-management-system/
│
├── src/
│   └── main/
│       ├── java/com/ordermanagement/
│       │   ├── OrderManagementSystemApplication.java  ← Main entry point
│       │   ├── entity/
│       │   │   └── Order.java                         ← Database entity
│       │   ├── repository/
│       │   │   └── OrderRepository.java               ← Data access layer
│       │   └── controller/
│       │       └── OrderController.java               ← REST API endpoints
│       └── resources/
│           └── application.properties                  ← Configuration
│
├── pom.xml                                             ← Maven dependencies
├── README.md                                           ← This file
└── VIVA_GUIDE.md                                      ← Complete theory + Q&A
```

### File Explanations:

**1. pom.xml** - Maven Project Configuration
- Defines project dependencies (Spring Boot, JPA, H2, etc.)
- Specifies build plugins
- Contains project metadata

**2. application.properties** - Application Configuration
- Database connection settings
- Server port configuration
- JPA/Hibernate settings
- Logging configuration

**3. OrderManagementSystemApplication.java** - Main Application
- Entry point of Spring Boot application
- Contains `main()` method
- `@SpringBootApplication` annotation enables auto-configuration

**4. Order.java** - Entity Class (Database Table)
- Represents "orders" table
- Maps Java object to database row
- Contains fields: id, customerName, email, product, quantity, price, etc.
- Uses JPA annotations: @Entity, @Id, @Column

**5. OrderRepository.java** - Repository Interface
- Data access layer
- Extends JpaRepository (provides CRUD methods)
- Custom query methods
- No implementation needed - Spring creates it

**6. OrderController.java** - REST Controller
- Defines REST API endpoints
- Handles HTTP requests (GET, POST, PUT, DELETE)
- Maps URLs to Java methods
- Returns JSON responses

---

## ▶️ RUNNING THE APPLICATION

### Complete Steps:

1. **Open Terminal in VS Code** (Ctrl + `)

2. **Navigate to project folder**
   ```bash
   cd order-management-system
   ```

3. **Clean and install dependencies**
   ```bash
   mvn clean install
   ```
   
   **What this does:**
   - `clean`: Deletes previous build files
   - `install`: Downloads dependencies, compiles code
   - First time: Takes 2-5 minutes to download dependencies
   - Subsequent times: Much faster

4. **Run the application**
   ```bash
   mvn spring-boot:run
   ```

5. **Watch the console output:**
   ```
   Starting OrderManagementSystemApplication...
   Tomcat initialized with port(s): 8080 (http)
   Started OrderManagementSystemApplication in 3.456 seconds
   ```

6. **Application is ready!** You should see:
   - No errors in console
   - "Started OrderManagementSystemApplication" message
   - Server listening on http://localhost:8080

### Stopping the Application:
- Press `Ctrl + C` in terminal
- Or click "Stop" button in VS Code

---

## 🧪 TESTING WITH POSTMAN

### Setup Postman:

1. **Download and install Postman**
2. **Create a new Collection** named "Order Management"
3. **Add requests for each endpoint**

### API Endpoints:

#### 1. CREATE ORDER (POST)
```
Method: POST
URL: http://localhost:8080/api/orders
Headers: Content-Type: application/json
Body (raw JSON):
{
  "customerName": "John Doe",
  "customerEmail": "john@example.com",
  "productName": "Laptop",
  "quantity": 2,
  "price": 50000.00
}
```

#### 2. GET ALL ORDERS (GET)
```
Method: GET
URL: http://localhost:8080/api/orders
```

#### 3. GET ORDER BY ID (GET)
```
Method: GET
URL: http://localhost:8080/api/orders/1
```

#### 4. UPDATE ORDER (PUT)
```
Method: PUT
URL: http://localhost:8080/api/orders/1
Headers: Content-Type: application/json
Body (raw JSON):
{
  "customerName": "John Doe Updated",
  "customerEmail": "john@example.com",
  "productName": "Gaming Laptop",
  "quantity": 3,
  "price": 75000.00,
  "orderStatus": "CONFIRMED"
}
```

#### 5. UPDATE ORDER STATUS (PATCH)
```
Method: PATCH
URL: http://localhost:8080/api/orders/1/status?status=SHIPPED
```

#### 6. DELETE ORDER (DELETE)
```
Method: DELETE
URL: http://localhost:8080/api/orders/1
```

#### 7. SEARCH BY CUSTOMER (GET)
```
Method: GET
URL: http://localhost:8080/api/orders/customer/John Doe
```

#### 8. FILTER BY STATUS (GET)
```
Method: GET
URL: http://localhost:8080/api/orders/status/PENDING
```

#### 9. SEARCH BY PRODUCT (GET)
```
Method: GET
URL: http://localhost:8080/api/orders/search?product=Laptop
```

#### 10. COUNT ORDERS BY STATUS (GET)
```
Method: GET
URL: http://localhost:8080/api/orders/count/PENDING
```

---

## 📊 EXPECTED OUTPUT

### 1. Creating an Order (POST /api/orders)

**Request:**
```json
{
  "customerName": "John Doe",
  "customerEmail": "john@example.com",
  "productName": "Laptop",
  "quantity": 2,
  "price": 50000.00
}
```

**Response (201 CREATED):**
```json
{
  "id": 1,
  "customerName": "John Doe",
  "customerEmail": "john@example.com",
  "productName": "Laptop",
  "quantity": 2,
  "price": 50000.00,
  "totalAmount": 100000.00,
  "orderStatus": "PENDING",
  "createdAt": "2024-01-15T10:30:45.123",
  "updatedAt": "2024-01-15T10:30:45.123"
}
```

### 2. Getting All Orders (GET /api/orders)

**Response (200 OK):**
```json
[
  {
    "id": 1,
    "customerName": "John Doe",
    "customerEmail": "john@example.com",
    "productName": "Laptop",
    "quantity": 2,
    "price": 50000.00,
    "totalAmount": 100000.00,
    "orderStatus": "PENDING",
    "createdAt": "2024-01-15T10:30:45",
    "updatedAt": "2024-01-15T10:30:45"
  },
  {
    "id": 2,
    "customerName": "Jane Smith",
    "customerEmail": "jane@example.com",
    "productName": "Mobile",
    "quantity": 1,
    "price": 30000.00,
    "totalAmount": 30000.00,
    "orderStatus": "CONFIRMED",
    "createdAt": "2024-01-15T11:00:00",
    "updatedAt": "2024-01-15T11:00:00"
  }
]
```

### 3. Getting Single Order (GET /api/orders/1)

**Response (200 OK):**
```json
{
  "id": 1,
  "customerName": "John Doe",
  "customerEmail": "john@example.com",
  "productName": "Laptop",
  "quantity": 2,
  "price": 50000.00,
  "totalAmount": 100000.00,
  "orderStatus": "PENDING",
  "createdAt": "2024-01-15T10:30:45",
  "updatedAt": "2024-01-15T10:30:45"
}
```

**If order not found (404 NOT FOUND):** Empty response with 404 status

### 4. Updating Order Status (PATCH /api/orders/1/status?status=CONFIRMED)

**Response (200 OK):**
```json
{
  "id": 1,
  "customerName": "John Doe",
  "customerEmail": "john@example.com",
  "productName": "Laptop",
  "quantity": 2,
  "price": 50000.00,
  "totalAmount": 100000.00,
  "orderStatus": "CONFIRMED",  ← Updated
  "createdAt": "2024-01-15T10:30:45",
  "updatedAt": "2024-01-15T12:00:00"  ← Updated timestamp
}
```

### 5. Deleting Order (DELETE /api/orders/1)

**Response (200 OK):**
```json
{
  "message": "Order deleted successfully",
  "id": "1"
}
```

### 6. Counting Orders (GET /api/orders/count/PENDING)

**Response (200 OK):**
```json
{
  "status": "PENDING",
  "count": 5
}
```

---

## 📚 WT SYLLABUS BRIEF THEORY

### UNIT I - Front End Tools
**HTML5:** Structure of web pages (tags, elements, attributes)
**CSS:** Styling (selectors, box model, flexbox, grid)
**Bootstrap:** Responsive framework (grid system, components)
**XML:** Data storage with custom tags
**JSON:** Lightweight data format for APIs

### UNIT II - Client-Side Technologies
**JavaScript:** Programming language for browsers (variables, functions, DOM)
**jQuery:** JavaScript library (simplified DOM manipulation, AJAX)
**DOM:** Document Object Model (tree structure, manipulation, events)
**POSTMAN:** API testing tool (send HTTP requests, test responses)

### UNIT III - Server-Side Technologies
**PHP:** Server-side scripting (variables, loops, functions)
**Laravel:** PHP framework (MVC, routing, Eloquent ORM)
**Form Handling:** Process user input ($_POST, $_GET)
**Cookies & Sessions:** State management
**MySQL with PHP:** Database connectivity

### UNIT IV - Spring Boot (THIS PROJECT)
**Spring Framework:** Java enterprise framework (DI, IoC, AOP)
**Spring Boot:** Simplified Spring (auto-configuration, embedded server)
**JPA:** Java Persistence API (ORM for databases)
**REST APIs:** HTTP methods (GET, POST, PUT, DELETE)
**Maven:** Build tool and dependency management

### UNIT V - React
**Components:** Reusable UI pieces
**JSX:** JavaScript XML syntax
**State & Props:** Data management
**Lifecycle:** Component lifecycle methods
**Hooks:** useState, useEffect, etc.

### UNIT VI - Node.js
**Node.js:** JavaScript runtime (server-side JavaScript)
**NPM:** Node Package Manager
**Express.js:** Web framework for Node
**Modules:** Reusable code packages

---

## ❓ VIVA QUESTIONS & ANSWERS

### Basic Questions:

**Q1: What is Spring Boot?**
**A:** Spring Boot is a framework built on top of Spring Framework that simplifies the development of production-ready applications. It provides auto-configuration, embedded servers, and starter dependencies to quickly create stand-alone Spring applications.

**Q2: What is the difference between Spring and Spring Boot?**
**A:**
- **Spring:** Requires manual configuration (XML/Java), external server, complex setup
- **Spring Boot:** Auto-configuration, embedded server (Tomcat), minimal setup, opinionated defaults

**Q3: What is REST API?**
**A:** REST (Representational State Transfer) is an architectural style for building web services that use HTTP methods (GET, POST, PUT, DELETE) to perform CRUD operations on resources identified by URLs.

**Q4: What are the HTTP methods and their uses?**
**A:**
- **GET:** Retrieve data (Read)
- **POST:** Create new resource (Create)
- **PUT:** Update entire resource (Update)
- **PATCH:** Partial update
- **DELETE:** Remove resource (Delete)

**Q5: What is JPA?**
**A:** JPA (Java Persistence API) is a Java specification for ORM (Object-Relational Mapping). It maps Java objects to database tables, eliminating the need to write SQL queries manually. Hibernate is the most popular JPA implementation.

**Q6: What is ORM?**
**A:** ORM (Object-Relational Mapping) is a technique that maps object-oriented programming language objects to relational database tables. It allows developers to interact with databases using Java objects instead of SQL.

**Q7: What is Maven?**
**A:** Maven is a build automation and dependency management tool for Java projects. It uses pom.xml to define project dependencies, which are automatically downloaded from Maven Central Repository.

**Q8: What is dependency injection?**
**A:** Dependency Injection is a design pattern where an object receives its dependencies from external sources rather than creating them itself. Spring uses @Autowired annotation to inject dependencies, promoting loose coupling and easier testing.

**Q9: What is @SpringBootApplication annotation?**
**A:** It's a composite annotation that combines three annotations:
1. @Configuration - Marks class as configuration source
2. @EnableAutoConfiguration - Enables auto-configuration
3. @ComponentScan - Scans for Spring components

**Q10: What is H2 database?**
**A:** H2 is an in-memory relational database written in Java. Data is stored in RAM and lost when application stops. Perfect for development and testing. No installation required - embedded in application.

### Intermediate Questions:

**Q11: Explain the architecture of your Order Management System.**
**A:** 
- **Controller Layer:** OrderController handles HTTP requests, maps URLs to methods
- **Repository Layer:** OrderRepository provides data access methods
- **Entity Layer:** Order class represents database table
- **Database:** H2 in-memory database stores data
- Flow: Client → Controller → Repository → Database

**Q12: What is @Entity annotation?**
**A:** @Entity marks a Java class as a JPA entity (database table). Each instance represents a table row. Used with @Table to specify table name, @Id for primary key, @Column for column mapping.

**Q13: What is @RestController?**
**A:** @RestController is a combination of @Controller and @ResponseBody. It marks the class as a REST controller and automatically converts return values to JSON. Each method returns data directly, not view names.

**Q14: What is @Autowired?**
**A:** @Autowired is used for automatic dependency injection. Spring automatically injects the required dependency (bean) into the field, constructor, or setter method. Promotes loose coupling.

**Q15: What is ResponseEntity?**
**A:** ResponseEntity is a generic class that represents the entire HTTP response: status code, headers, and body. It provides full control over the HTTP response. Example: ResponseEntity.ok(data) returns 200 status with data.

**Q16: Explain @GetMapping, @PostMapping, etc.**
**A:**
- **@GetMapping:** Maps HTTP GET requests to handler method
- **@PostMapping:** Maps HTTP POST requests
- **@PutMapping:** Maps HTTP PUT requests
- **@DeleteMapping:** Maps HTTP DELETE requests
- **@PatchMapping:** Maps HTTP PATCH requests
These are shortcuts for @RequestMapping(method = RequestMethod.GET/POST/etc.)

**Q17: What is @PathVariable?**
**A:** @PathVariable extracts values from URI template. Example: /orders/{id} → @PathVariable Long id extracts the id value from URL. Used for RESTful URLs.

**Q18: What is @RequestBody?**
**A:** @RequestBody binds the HTTP request body to a method parameter. It deserializes JSON from request body to Java object. Used with POST/PUT requests to receive data.

**Q19: What is @Valid annotation?**
**A:** @Valid triggers Bean Validation on the annotated parameter. It validates constraints like @NotNull, @NotBlank, @Positive, etc. defined in the entity class. If validation fails, returns 400 Bad Request.

**Q20: What is JpaRepository?**
**A:** JpaRepository is a Spring Data interface that provides CRUD operations, pagination, and sorting. It extends PagingAndSortingRepository and CrudRepository. Spring automatically implements this interface at runtime - no code needed!

### Advanced Questions:

**Q21: How does Spring Data JPA generate queries from method names?**
**A:** Spring Data JPA parses method names following naming conventions:
- Prefix: findBy, countBy, deleteBy, existsBy
- Field names: findByCustomerName
- Keywords: And, Or, Between, Like, GreaterThan
- Example: findByCustomerNameAndOrderStatus → SELECT * FROM orders WHERE customer_name = ? AND order_status = ?

**Q22: What is the difference between @Component, @Service, @Repository?**
**A:**
- **@Component:** Generic stereotype for any Spring-managed component
- **@Service:** Specialization of @Component for service layer (business logic)
- **@Repository:** Specialization of @Component for persistence layer (data access). Enables exception translation.
All three make the class a Spring bean, but @Service and @Repository provide additional semantics.

**Q23: What are JPA lifecycle callbacks?**
**A:** JPA lifecycle callbacks are methods that execute at specific points in entity lifecycle:
- **@PrePersist:** Before entity is saved for first time
- **@PostPersist:** After entity is saved
- **@PreUpdate:** Before entity is updated
- **@PostUpdate:** After entity is updated
- **@PreRemove:** Before entity is deleted
- **@PostRemove:** After entity is deleted

**Q24: What is Lombok and why use it?**
**A:** Lombok is a Java library that reduces boilerplate code using annotations:
- **@Data:** Generates getters, setters, toString, equals, hashCode
- **@NoArgsConstructor:** Generates no-argument constructor
- **@AllArgsConstructor:** Generates constructor with all fields
- **@Getter/@Setter:** Generates individual getters/setters
Benefits: Less code, cleaner classes, easier maintenance.

**Q25: How does auto-configuration work in Spring Boot?**
**A:** Spring Boot's auto-configuration examines classpath dependencies and automatically configures beans:
1. Checks for specific classes in classpath
2. Reads application.properties
3. Creates beans with sensible defaults
4. Example: H2 dependency detected → DataSource bean created automatically
5. Can be customized or disabled if needed

**Q26: What is the difference between PUT and PATCH?**
**A:**
- **PUT:** Replaces entire resource. All fields must be sent. Idempotent.
- **PATCH:** Partial update. Only changed fields sent. May not be idempotent.
- Example: PUT /orders/1 → send complete order; PATCH /orders/1/status → update only status

**Q27: What HTTP status codes does your application use?**
**A:**
- **200 OK:** Successful GET, PUT, DELETE
- **201 CREATED:** Successful POST (resource created)
- **204 NO CONTENT:** Successful DELETE with no response body
- **400 BAD REQUEST:** Validation error
- **404 NOT FOUND:** Resource doesn't exist
- **500 INTERNAL SERVER ERROR:** Server error

**Q28: How do you handle exceptions in Spring Boot?**
**A:** Exception handling can be done with:
1. **@ExceptionHandler:** Method-level in controller
2. **@ControllerAdvice:** Global exception handling class
3. **ResponseEntityExceptionHandler:** Base class for global handler
Returns appropriate HTTP status codes and error messages.

**Q29: What is the purpose of application.properties?**
**A:** application.properties is the configuration file for Spring Boot application. It configures:
- Server port
- Database connection (URL, username, password)
- JPA/Hibernate settings
- Logging levels
- Custom application properties
Spring Boot reads this file at startup and configures the application accordingly.

**Q30: How would you secure this REST API?**
**A:** Security can be added using Spring Security:
1. Add spring-boot-starter-security dependency
2. Configure authentication (username/password, JWT, OAuth2)
3. Define authorization rules (which endpoints require authentication)
4. Use @PreAuthorize for method-level security
5. Implement CORS properly
6. Use HTTPS in production

### Project-Specific Questions:

**Q31: Walk me through the flow when a POST request is made to create an order.**
**A:**
1. Client sends POST request with JSON body
2. Spring DispatcherServlet receives request
3. Routes to OrderController.createOrder() method
4. @RequestBody deserializes JSON to Order object
5. @Valid validates Order object (checks @NotNull, @NotBlank, etc.)
6. Controller calls orderRepository.save(order)
7. JPA/Hibernate inserts record into database
8. @PrePersist callback sets createdAt, orderStatus, totalAmount
9. Saved order returned with generated ID
10. ResponseEntity wraps order with 201 CREATED status
11. Spring serializes order to JSON
12. JSON response sent to client

**Q32: How is totalAmount calculated automatically?**
**A:** In the Order entity, @PrePersist and @PreUpdate lifecycle callbacks calculate totalAmount:
```java
@PrePersist
protected void onCreate() {
    totalAmount = price.multiply(BigDecimal.valueOf(quantity));
}
```
This executes before saving to database, ensuring totalAmount is always correct.

**Q33: Why use BigDecimal for price instead of double?**
**A:** BigDecimal is used for precise decimal calculations:
- **double/float:** Floating-point imprecision (0.1 + 0.2 ≠ 0.3)
- **BigDecimal:** Exact decimal arithmetic
- Essential for financial calculations where precision matters
- Example: 2.0 - 1.1 = 0.8999999... (double) vs 0.9 (BigDecimal)

**Q34: What is the purpose of OrderStatus enum?**
**A:** OrderStatus enum defines allowed order states (PENDING, CONFIRMED, SHIPPED, DELIVERED, CANCELLED):
- **Type safety:** Can't assign invalid status
- **Self-documenting:** Clear what values are allowed
- **IDE support:** Auto-completion
- **Database:** Stored as string in database using @Enumerated(EnumType.STRING)

**Q35: How do you test this application without Postman?**
**A:** Alternative testing methods:
1. **curl command:** `curl -X GET http://localhost:8080/api/orders`
2. **Browser:** For GET requests, open URL in browser
3. **REST Client VS Code extension:** Create .http files
4. **Unit tests:** JUnit with @SpringBootTest
5. **Integration tests:** MockMvc for testing controllers

**Q36: What happens when the application stops?**
**A:** Since H2 is an in-memory database:
- All data is lost when application stops
- Database is recreated on next startup
- For persistent storage, use MySQL/PostgreSQL instead
- Change spring.datasource.url to use file-based H2: jdbc:h2:file:./data/orderdb

**Q37: How would you add pagination to get all orders?**
**A:** Use Spring Data JPA's Pageable:
```java
@GetMapping
public ResponseEntity<Page<Order>> getAllOrders(
    @RequestParam(defaultValue = "0") int page,
    @RequestParam(defaultValue = "10") int size
) {
    Pageable pageable = PageRequest.of(page, size);
    Page<Order> orders = orderRepository.findAll(pageable);
    return ResponseEntity.ok(orders);
}
```
URL: /api/orders?page=0&size=10

**Q38: How would you add a Service layer?**
**A:** Create OrderService between Controller and Repository:
```java
@Service
public class OrderService {
    @Autowired
    private OrderRepository orderRepository;
    
    public Order createOrder(Order order) {
        // Business logic here
        return orderRepository.save(order);
    }
}
```
Benefits: Separates business logic from controller logic, easier testing.

**Q39: What is CORS and why did you use @CrossOrigin?**
**A:** CORS (Cross-Origin Resource Sharing) is a security feature that restricts web pages from making requests to a different domain:
- Browser blocks requests from different origin by default
- @CrossOrigin(origins = "*") allows requests from any origin
- In production: Specify allowed origins @CrossOrigin(origins = "https://myapp.com")
- Necessary for frontend (React/Angular) to call backend API

**Q40: How would you deploy this application in production?**
**A:** Production deployment steps:
1. **Build JAR:** `mvn clean package`
2. **Run JAR:** `java -jar target/order-management-system-1.0.0.jar`
3. **Use production database:** Configure MySQL/PostgreSQL in application.properties
4. **Add security:** Enable Spring Security with authentication
5. **Use HTTPS:** Configure SSL certificate
6. **Environment variables:** Externalize sensitive config
7. **Deploy to cloud:** AWS, Heroku, Azure, or Docker container
8. **Set up monitoring:** Actuator endpoints for health checks

---

## 🎯 TIPS FOR VIVA SUCCESS

1. **Run the application before viva** - Show it working
2. **Understand the code** - Every line has a comment explaining it
3. **Know the flow** - Request → Controller → Repository → Database
4. **Be ready with Postman** - Have collection ready with all endpoints
5. **Explain annotations** - Know what each annotation does
6. **Understand Spring Boot** - Why it's better than traditional Spring
7. **Know REST principles** - HTTP methods, status codes, stateless
8. **Understand JPA** - How entities map to tables
9. **Be confident** - You have complete working code with documentation

---

## 📞 TROUBLESHOOTING

### Problem: Port 8080 already in use
**Solution:**
```bash
# Option 1: Change port in application.properties
server.port=8081

# Option 2: Kill process using port 8080
# Windows: netstat -ano | findstr :8080, then taskkill /PID <PID> /F
# Linux/Mac: lsof -i :8080, then kill -9 <PID>
```

### Problem: Maven dependencies not downloading
**Solution:**
```bash
# Clear Maven cache
rm -rf ~/.m2/repository

# Re-download dependencies
mvn clean install -U
```

### Problem: Java version error
**Solution:**
- Ensure Java 17+ is installed: `java -version`
- Set JAVA_HOME environment variable
- In pom.xml, check `<java.version>17</java.version>`

### Problem: Application starts but APIs return 404
**Solution:**
- Check if controller is in `com.ordermanagement` package or sub-package
- Verify @RestController annotation is present
- Check @RequestMapping("/api/orders") is correct
- Ensure application started successfully (no errors in console)

---

## ✅ CHECKLIST BEFORE VIVA

- [ ] Application runs without errors
- [ ] All API endpoints tested in Postman
- [ ] H2 console accessible
- [ ] Understand every annotation used
- [ ] Can explain REST principles
- [ ] Know Spring Boot advantages
- [ ] Familiar with HTTP status codes
- [ ] Can explain code flow
- [ ] Ready to demonstrate live
- [ ] Read all viva questions & answers

---

## 📝 ADDITIONAL RESOURCES

- [Spring Boot Documentation](https://spring.io/projects/spring-boot)
- [Spring Data JPA Reference](https://spring.io/projects/spring-data-jpa)
- [REST API Design Guide](https://restfulapi.net/)
- [Postman Learning Center](https://learning.postman.com/)

---

**Good Luck with your Viva! 🎓**
