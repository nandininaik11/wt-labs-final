# 📚 ONLINE BOOKSTORE - Spring Boot Application
## Lab Question 14: Complete Web Technology Project

---

## 📋 TABLE OF CONTENTS

1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [Setup Instructions](#setup-instructions)
4. [Running the Application](#running-the-application)
5. [Complete Theory Explanations](#complete-theory-explanations)
6. [Viva Questions & Answers](#viva-questions--answers)
7. [File Structure](#file-structure)
8. [Features Implemented](#features-implemented)

---

## 🎯 PROJECT OVERVIEW

This is a **responsive online bookstore web application** built using **Spring Boot** and **MySQL**. The application includes:

✅ **Home Page** - Landing page with bookstore information  
✅ **Login Page** - User authentication with Spring Security  
✅ **Registration Page** - User signup with database storage  
✅ **Catalog Page** - Browse books with search and filter functionality  

**Database:** MySQL with JPA/Hibernate for ORM  
**Security:** Spring Security with BCrypt password encryption  
**Frontend:** Thymeleaf templates with Bootstrap 5 (responsive design)

---

## 🛠️ TECHNOLOGY STACK

| Layer | Technology | Purpose |
|-------|------------|---------|
| **Backend Framework** | Spring Boot 3.1.5 | Application framework |
| **Database** | MySQL 8.0+ | Data storage |
| **ORM** | JPA/Hibernate | Object-relational mapping |
| **Security** | Spring Security | Authentication & authorization |
| **Template Engine** | Thymeleaf | Server-side HTML rendering |
| **Frontend** | Bootstrap 5.3.0 | Responsive UI components |
| **Build Tool** | Maven | Dependency management |
| **Java Version** | Java 17 | Programming language |

---

## 📦 SETUP INSTRUCTIONS

### STEP 1: Prerequisites

Install the following software:

1. **Java Development Kit (JDK) 17 or higher**
   - Download from: https://www.oracle.com/java/technologies/downloads/
   - Verify installation: `java -version`

2. **MySQL Server 8.0+**
   - Download from: https://dev.mysql.com/downloads/installer/
   - During installation, set root password (remember this!)
   - Verify installation: `mysql --version`

3. **Visual Studio Code**
   - Download from: https://code.visualstudio.com/
   - Install extensions:
     - Extension Pack for Java (Microsoft)
     - Spring Boot Extension Pack (VMware)

4. **Maven** (Usually included with VS Code Java extensions)
   - Verify: `mvn -version`

---

### STEP 2: Database Setup

1. **Start MySQL Server**
   - Windows: Services → MySQL → Start
   - Mac/Linux: `sudo service mysql start`

2. **Create Database**

Open MySQL Command Line or MySQL Workbench and run:

```sql
-- Connect to MySQL (password is what you set during installation)
mysql -u root -p

-- Create the database
CREATE DATABASE bookstore_db;

-- Verify database was created
SHOW DATABASES;

-- Exit MySQL
EXIT;
```

3. **Configure Database Credentials**

Open `src/main/resources/application.properties` and update:

```properties
# Change these if your MySQL username/password is different
spring.datasource.username=root
spring.datasource.password=YOUR_MYSQL_PASSWORD
```

---

### STEP 3: Import Project in VS Code

1. **Extract the project folder** (this folder)

2. **Open VS Code**
   - File → Open Folder
   - Select the `bookstore-project` folder

3. **Wait for Maven to download dependencies**
   - VS Code will automatically detect `pom.xml`
   - Dependencies download in background (check bottom-right progress)
   - This may take 5-10 minutes on first run

4. **Verify Project Structure**

You should see:
```
bookstore-project/
├── src/
│   ├── main/
│   │   ├── java/
│   │   │   └── com/bookstore/
│   │   │       ├── entity/          (User.java, Book.java)
│   │   │       ├── repository/      (UserRepository.java, BookRepository.java)
│   │   │       ├── service/         (UserService.java, BookService.java)
│   │   │       ├── controller/      (HomeController.java, etc.)
│   │   │       ├── config/          (SecurityConfig.java)
│   │   │       └── BookstoreApplication.java
│   │   └── resources/
│   │       ├── templates/           (home.html, login.html, etc.)
│   │       ├── static/css/          (style.css)
│   │       └── application.properties
├── pom.xml
└── README.md (this file)
```

---

## 🚀 RUNNING THE APPLICATION

### METHOD 1: Using VS Code (Recommended)

1. **Open `BookstoreApplication.java`**
   - Located in: `src/main/java/com/bookstore/BookstoreApplication.java`

2. **Click "Run" button**
   - You'll see a "Run | Debug" option above the `main` method
   - Click "Run"

3. **Wait for application to start**
   - Console output will show:
   ```
   Started BookstoreApplication in X seconds
   Tomcat started on port(s): 8080
   ```

4. **Open browser and navigate to:**
   ```
   http://localhost:8080
   ```

### METHOD 2: Using Maven Command

1. **Open Terminal in VS Code**
   - View → Terminal (or Ctrl + `)

2. **Navigate to project root**
   ```bash
   cd /path/to/bookstore-project
   ```

3. **Run Maven command**
   ```bash
   mvn spring-boot:run
   ```

4. **Access application:**
   ```
   http://localhost:8080
   ```

### METHOD 3: Build JAR and Run

1. **Build the project**
   ```bash
   mvn clean package
   ```

2. **Run the JAR file**
   ```bash
   java -jar target/online-bookstore-1.0.0.jar
   ```

---

## 🔄 TESTING THE APPLICATION

### 1. Register a New User

1. Navigate to: `http://localhost:8080/register`
2. Fill in the registration form:
   - Full Name: John Doe
   - Username: john
   - Email: john@example.com
   - Password: password123
   - Phone: 1234567890
   - Address: 123 Main St
3. Click "Register"
4. You'll be redirected to login page

### 2. Login

1. Navigate to: `http://localhost:8080/login`
2. Enter credentials:
   - Username: john
   - Password: password123
3. Click "Login"
4. You'll be redirected to catalog page

### 3. Browse Catalog

1. The catalog page shows all books
2. Use search box to find books by title/author
3. Filter by category using sidebar
4. Each book shows:
   - Title, Author, Category
   - Price
   - Stock status
   - Add to Cart button (disabled if out of stock)

---

## 📊 ADDING SAMPLE DATA

To test the catalog page, you need to add books to the database.

### Option 1: Using MySQL Workbench

```sql
USE bookstore_db;

-- Insert sample books
INSERT INTO books (title, author, isbn, description, category, price, stock_quantity, image_url, available, created_date) VALUES
('Java Programming', 'John Smith', '978-0-13-601970-1', 'Complete guide to Java programming', 'Technology', 499.00, 10, 'https://via.placeholder.com/300x400?text=Java', TRUE, CURDATE()),
('Python Basics', 'Jane Doe', '978-0-13-601971-2', 'Learn Python from scratch', 'Technology', 399.00, 15, 'https://via.placeholder.com/300x400?text=Python', TRUE, CURDATE()),
('Web Development', 'Bob Johnson', '978-0-13-601972-3', 'Master web development', 'Technology', 599.00, 8, 'https://via.placeholder.com/300x400?text=Web+Dev', TRUE, CURDATE()),
('Data Science', 'Alice Williams', '978-0-13-601973-4', 'Data science fundamentals', 'Technology', 699.00, 5, 'https://via.placeholder.com/300x400?text=Data+Science', TRUE, CURDATE()),
('Machine Learning', 'Charlie Brown', '978-0-13-601974-5', 'Introduction to ML', 'Technology', 799.00, 0, 'https://via.placeholder.com/300x400?text=ML', FALSE, CURDATE());
```

### Option 2: Using Command Line

```bash
mysql -u root -p bookstore_db < sample_data.sql
```

---

## 📚 COMPLETE THEORY EXPLANATIONS

### 🌐 **UNIT I: Front-End Tools**

#### HTML5 Structure

**What is HTML5?**
- HTML = HyperText Markup Language
- HTML5 = Latest version (2014)
- Defines structure and content of web pages

**Basic HTML5 Document:**
```html
<!DOCTYPE html>              <!-- Declares HTML5 document -->
<html lang="en">             <!-- Root element, language -->
<head>                       <!-- Metadata section -->
    <meta charset="UTF-8">   <!-- Character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Responsive -->
    <title>Page Title</title>
</head>
<body>                       <!-- Visible content -->
    <h1>Heading</h1>         <!-- Main heading -->
    <p>Paragraph</p>         <!-- Text paragraph -->
</body>
</html>
```

**HTML Elements Used in Project:**

1. **Headings:** `<h1>` to `<h6>`
   - `<h1>` = Most important
   - `<h6>` = Least important
   - Search engines use for page structure

2. **Paragraphs:** `<p>`
   - Block-level element
   - Creates text paragraphs

3. **Links:** `<a href="...">`
   - Creates hyperlinks
   - `href` attribute = destination URL

4. **Forms:** `<form>`, `<input>`, `<button>`
   - Used for login and registration
   - Collects user input

5. **Lists:** `<ul>`, `<li>`
   - Used for navigation menus
   - Unordered list (bullet points)

6. **Images:** `<img src="..." alt="...">`
   - Displays images
   - `alt` = Alternative text (accessibility)

7. **Div/Span:** `<div>`, `<span>`
   - Container elements for grouping
   - `<div>` = Block-level
   - `<span>` = Inline

**New HTML5 Elements:**
- `<header>`, `<footer>`, `<nav>`, `<main>`, `<section>`, `<article>`
- Semantic elements (meaningful names)
- Better for SEO and accessibility

---

#### CSS (Cascading Style Sheets)

**What is CSS?**
- Controls presentation and layout
- Separates content (HTML) from design (CSS)
- Three ways to add CSS:
  1. Inline: `<p style="color: red;">Text</p>`
  2. Internal: `<style>` tag in `<head>`
  3. External: Separate `.css` file (used in project)

**CSS Syntax:**
```css
selector {
    property: value;
}
```

**CSS Selectors:**
```css
/* Element selector */
p { color: blue; }

/* Class selector */
.navbar { background: dark; }

/* ID selector */
#header { font-size: 24px; }

/* Descendant selector */
.card img { border-radius: 8px; }

/* Pseudo-class */
a:hover { color: red; }
```

**CSS Box Model:**
```
Margin (outside spacing)
  ↓
Border
  ↓
Padding (inside spacing)
  ↓
Content
```

**CSS in Project:**
- Bootstrap provides pre-built CSS classes
- Custom CSS in `style.css` for additional styling
- Responsive design using media queries

---

#### Bootstrap

**What is Bootstrap?**
- **Front-end framework** for responsive web design
- Developed by Twitter
- Provides pre-built CSS classes and JavaScript components
- **Version used in project:** Bootstrap 5.3.0

**Key Bootstrap Features:**

1. **Grid System** (12-column layout)
```html
<div class="row">
    <div class="col-md-6">50% width on medium+ screens</div>
    <div class="col-md-6">50% width</div>
</div>
```

2. **Responsive Breakpoints:**
- `col-sm-*` ≥ 576px (phones landscape)
- `col-md-*` ≥ 768px (tablets)
- `col-lg-*` ≥ 992px (desktops)
- `col-xl-*` ≥ 1200px (large desktops)

3. **Components Used:**
- Navbar: `navbar navbar-expand-lg`
- Cards: `card card-body`
- Buttons: `btn btn-primary`
- Forms: `form-control form-label`
- Alerts: `alert alert-danger`

4. **Utility Classes:**
- Spacing: `m-3` (margin), `p-4` (padding)
- Text: `text-center`, `text-white`
- Display: `d-flex`, `d-none`
- Colors: `bg-primary`, `text-success`

**Bootstrap Grid Example:**
```html
<!-- Mobile: 1 column, Tablet: 2 columns, Desktop: 3 columns -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
    <div class="col">Column 1</div>
    <div class="col">Column 2</div>
    <div class="col">Column 3</div>
</div>
```

---

#### XML & JSON

**XML (eXtensible Markup Language):**
```xml
<book>
    <title>Java Programming</title>
    <author>John Smith</author>
    <price>499.00</price>
</book>
```
- Used for data storage and transfer
- Self-descriptive (tags describe data)
- Verbose (lots of closing tags)

**JSON (JavaScript Object Notation):**
```json
{
    "title": "Java Programming",
    "author": "John Smith",
    "price": 499.00
}
```
- Lightweight data format
- Easier to read/write than XML
- Native JavaScript support
- Used in REST APIs (not in this project, but important for WT)

---

### ⚙️ **UNIT II: Client-Side Technologies**

#### JavaScript Basics

**What is JavaScript?**
- Programming language for web browsers
- Adds interactivity to web pages
- Runs on client-side (user's browser)

**JavaScript Fundamentals:**

1. **Variables:**
```javascript
var name = "John";      // Old way (function scope)
let age = 25;           // Modern (block scope)
const PI = 3.14159;     // Constant (cannot change)
```

2. **Data Types:**
```javascript
let string = "Hello";           // String
let number = 42;                // Number
let boolean = true;             // Boolean
let array = [1, 2, 3];          // Array
let object = {name: "John"};    // Object
let nothing = null;             // Null
let undefined;                  // Undefined
```

3. **Control Structures:**
```javascript
// If statement
if (age >= 18) {
    console.log("Adult");
} else {
    console.log("Minor");
}

// Switch statement
switch (day) {
    case "Monday":
        console.log("Start of week");
        break;
    case "Friday":
        console.log("End of week");
        break;
    default:
        console.log("Midweek");
}

// For loop
for (let i = 0; i < 5; i++) {
    console.log(i);
}

// While loop
while (count < 10) {
    count++;
}
```

4. **Functions:**
```javascript
// Function declaration
function add(a, b) {
    return a + b;
}

// Arrow function (modern)
const multiply = (a, b) => a * b;

// Function call
let result = add(5, 3);  // Returns 8
```

5. **Arrays & Methods:**
```javascript
let fruits = ["apple", "banana", "orange"];

// Array methods
fruits.push("grape");        // Add to end
fruits.pop();                // Remove from end
fruits.length;               // Get length
fruits[0];                   // Access element
fruits.forEach(f => console.log(f));  // Iterate
```

**JavaScript in Project:**
- Bootstrap uses JavaScript for interactive components
- Navbar toggle, alerts, modals require Bootstrap JS
- Included via CDN: `bootstrap.bundle.min.js`

---

#### DOM (Document Object Model)

**What is DOM?**
- Programming interface for HTML documents
- Represents page as tree of objects
- JavaScript can manipulate DOM to change page dynamically

**DOM Tree:**
```
document
  └── html
      ├── head
      │   ├── title
      │   └── meta
      └── body
          ├── div
          ├── h1
          └── p
```

**DOM Manipulation:**
```javascript
// Select elements
document.getElementById("myId");
document.querySelector(".myClass");
document.querySelectorAll("p");

// Change content
element.innerHTML = "New content";
element.textContent = "Plain text";

// Change styles
element.style.color = "red";
element.classList.add("active");
element.classList.remove("hidden");

// Create elements
let newDiv = document.createElement("div");
newDiv.textContent = "Hello";
document.body.appendChild(newDiv);

// Event listeners
button.addEventListener("click", function() {
    alert("Button clicked!");
});
```

**DOM Levels:**
- Level 0: Basic (old browsers)
- Level 1: Core + HTML (W3C standard)
- Level 2: Events, CSS, traversal
- Level 3: XPath, keyboard events

---

#### jQuery (Brief Overview)

**What is jQuery?**
- JavaScript library
- Simplifies DOM manipulation
- "Write less, do more"

**jQuery vs JavaScript:**
```javascript
// JavaScript
document.getElementById("myDiv").innerHTML = "Hello";

// jQuery
$("#myDiv").html("Hello");
```

**Note:** Not used in this project (modern JavaScript + Bootstrap 5 don't require jQuery)

---

### 🖥️ **UNIT III: Server-Side Technologies**

#### PHP (Not used in this project, but in syllabus)

**What is PHP?**
- Server-side scripting language
- Stands for: PHP: Hypertext Preprocessor
- Runs on server, generates HTML sent to browser

**Basic PHP:**
```php
<?php
// Variables
$name = "John";
$age = 25;

// Echo output
echo "Hello, $name!";

// Conditional
if ($age >= 18) {
    echo "Adult";
}

// Function
function add($a, $b) {
    return $a + $b;
}
?>
```

**PHP vs Spring Boot:**
| Feature | PHP | Spring Boot |
|---------|-----|-------------|
| Language | PHP | Java |
| Type | Scripting | Framework |
| Learning Curve | Easy | Moderate |
| Enterprise Use | Medium | High |
| Performance | Good | Excellent |

---

### 🌱 **UNIT IV: Spring Boot** (Used in Project)

#### Spring Framework Overview

**What is Spring Framework?**
- Java application framework
- Provides infrastructure for enterprise applications
- Based on Dependency Injection (DI) and Inversion of Control (IoC)

**Core Concepts:**

1. **Dependency Injection (DI)**
```java
// Without DI (tight coupling)
public class UserService {
    private UserRepository repo = new UserRepository();  // Creates dependency
}

// With DI (loose coupling)
public class UserService {
    @Autowired
    private UserRepository repo;  // Spring injects dependency
}
```

2. **Inversion of Control (IoC)**
- Framework controls object creation
- Application doesn't create objects manually
- Spring IoC Container manages beans

3. **Beans**
- Objects managed by Spring
- Created, configured, and managed by Spring Container
- Defined using `@Component`, `@Service`, `@Repository`, etc.

---

#### Spring Boot Framework

**What is Spring Boot?**
- Built on top of Spring Framework
- **Auto-configuration:** Automatically configures based on dependencies
- **Embedded server:** No need for external Tomcat
- **Starter dependencies:** Simplified dependency management
- **Production-ready:** Built-in monitoring, metrics

**Spring Boot Advantages:**
1. Rapid development
2. Less configuration (convention over configuration)
3. Microservices-friendly
4. Easy deployment (single JAR file)

**Spring Boot Architecture:**
```
Application
    ↓
Controllers (Handle HTTP requests)
    ↓
Services (Business logic)
    ↓
Repositories (Database access)
    ↓
Database
```

---

#### Maven (Build Tool)

**What is Maven?**
- Build automation tool for Java projects
- Manages dependencies automatically
- Uses `pom.xml` (Project Object Model)

**POM.xml Structure:**
```xml
<project>
    <groupId>com.bookstore</groupId>       <!-- Organization -->
    <artifactId>online-bookstore</artifactId>  <!-- Project name -->
    <version>1.0.0</version>                <!-- Version -->
    
    <dependencies>
        <!-- Spring Boot dependencies -->
        <dependency>
            <groupId>org.springframework.boot</groupId>
            <artifactId>spring-boot-starter-web</artifactId>
        </dependency>
    </dependencies>
</project>
```

**Maven Lifecycle:**
1. `mvn clean` - Delete target folder
2. `mvn compile` - Compile source code
3. `mvn test` - Run tests
4. `mvn package` - Create JAR/WAR
5. `mvn install` - Install to local repository

---

#### Spring Boot Core Features

**1. Spring Security**

**What is Spring Security?**
- Authentication and authorization framework
- Protects applications from security threats

**Key Concepts:**

- **Authentication:** Who are you? (Login)
- **Authorization:** What can you do? (Permissions)
- **Principal:** Currently logged-in user
- **Authority/Role:** User's permissions

**Security Configuration (from project):**
```java
@Configuration
@EnableWebSecurity
public class SecurityConfig {
    
    @Bean
    public SecurityFilterChain securityFilterChain(HttpSecurity http) {
        http
            .authorizeHttpRequests(auth -> auth
                .requestMatchers("/", "/login", "/register").permitAll()  // Public
                .anyRequest().authenticated()  // Requires login
            )
            .formLogin(form -> form
                .loginPage("/login")
                .defaultSuccessUrl("/catalog")
            )
            .logout(logout -> logout
                .logoutUrl("/logout")
            );
        return http.build();
    }
}
```

**Password Encoding:**
```java
@Bean
public PasswordEncoder passwordEncoder() {
    return new BCryptPasswordEncoder();  // One-way encryption
}
```

**BCrypt Workflow:**
1. User enters password: "password123"
2. BCrypt generates salt: "a#k$9x..."
3. Hash = BCrypt(password + salt)
4. Stored in DB: "$2a$10$N9qo8uLOickgx2ZMRZoMye..."

Login verification:
1. User enters password
2. BCrypt extracts salt from stored hash
3. Hashes entered password with same salt
4. Compares hashes

---

**2. JPA (Java Persistence API)**

**What is JPA?**
- Specification for ORM (Object-Relational Mapping)
- Maps Java objects to database tables
- Hibernate = JPA implementation

**Entity Mapping:**
```java
@Entity                              // Marks as database table
@Table(name = "users")              // Table name
public class User {
    
    @Id                              // Primary key
    @GeneratedValue(strategy = GenerationType.IDENTITY)  // Auto-increment
    private Long id;
    
    @Column(nullable = false, unique = true)  // Column constraints
    private String username;
    
    @Column(nullable = false)
    private String password;
}
```

**JPA Annotations:**
- `@Entity` - Database table
- `@Table` - Table details
- `@Id` - Primary key
- `@GeneratedValue` - Auto-generate value
- `@Column` - Column configuration
- `@OneToMany` - One-to-many relationship
- `@ManyToOne` - Many-to-one relationship
- `@ManyToMany` - Many-to-many relationship

**Repository Pattern:**
```java
public interface UserRepository extends JpaRepository<User, Long> {
    // Spring Data JPA creates implementation automatically!
    
    Optional<User> findByUsername(String username);  // Custom query
    boolean existsByEmail(String email);             // Check existence
}
```

**Spring Data JPA Magic:**
- `findBy<FieldName>` → Generates SELECT query
- `existsBy<FieldName>` → Generates COUNT query
- `deleteBy<FieldName>` → Generates DELETE query

---

**3. Thymeleaf Template Engine**

**What is Thymeleaf?**
- Server-side template engine
- Processes HTML templates on server
- Generates dynamic HTML

**Thymeleaf Attributes:**
```html
<!-- Variable expression -->
<p th:text="${user.username}">Username</p>

<!-- URL expression -->
<a th:href="@{/catalog}">Catalog</a>

<!-- Conditional rendering -->
<div th:if="${error}">Error message</div>

<!-- Iteration -->
<div th:each="book : ${books}">
    <h3 th:text="${book.title}"></h3>
</div>

<!-- Form binding -->
<form th:object="${user}" th:action="@{/register}" method="post">
    <input th:field="*{username}" />
</form>
```

**Thymeleaf vs JSP:**
| Feature | Thymeleaf | JSP |
|---------|-----------|-----|
| Natural templates | Yes | No |
| Can view in browser | Yes | No |
| Learning curve | Easy | Moderate |
| Performance | Good | Better |

---

#### Spring Boot Application Structure

**Layered Architecture:**

1. **Presentation Layer** (Controllers)
   - Handles HTTP requests/responses
   - Returns view names
   - Example: `HomeController`, `BookController`

2. **Business Logic Layer** (Services)
   - Contains business rules
   - Transaction management
   - Example: `UserService`, `BookService`

3. **Data Access Layer** (Repositories)
   - Database operations
   - CRUD operations
   - Example: `UserRepository`, `BookRepository`

4. **Database Layer**
   - MySQL database
   - Stores persistent data

**Data Flow:**
```
Browser
   ↓ HTTP Request
Controller (Receive & route)
   ↓
Service (Business logic)
   ↓
Repository (Database query)
   ↓
Database (MySQL)
   ↓
Repository (Return data)
   ↓
Service (Process data)
   ↓
Controller (Prepare view)
   ↓ HTTP Response
Thymeleaf (Render HTML)
   ↓
Browser
```

---

#### POSTMAN (API Testing Tool)

**What is POSTMAN?**
- Tool for testing REST APIs
- Sends HTTP requests and views responses
- Not directly used in this project but important for WT

**HTTP Methods:**
- `GET` - Retrieve data
- `POST` - Create data
- `PUT` - Update entire resource
- `PATCH` - Update partial resource
- `DELETE` - Delete resource

**Example REST API:**
```
GET    /api/books          → Get all books
GET    /api/books/123      → Get book with ID 123
POST   /api/books          → Create new book
PUT    /api/books/123      → Update book 123
DELETE /api/books/123      → Delete book 123
```

---

### ⚛️ **UNIT V: React** (Not in project, but in syllabus)

**What is React?**
- JavaScript library for building UIs
- Developed by Facebook
- Component-based architecture
- Virtual DOM for performance

**React Basics:**
```jsx
// Component
function Welcome(props) {
    return <h1>Hello, {props.name}</h1>;
}

// JSX (JavaScript XML)
const element = <h1>Hello, World!</h1>;

// State
const [count, setCount] = useState(0);

// Props
<Welcome name="John" />
```

**React vs Thymeleaf:**
| Feature | React | Thymeleaf |
|---------|-------|-----------|
| Rendering | Client-side | Server-side |
| Type | SPA | Multi-page |
| SEO | Needs SSR | Native |
| Learning | Steep | Easy |

---

### 🟢 **UNIT VI: Node.js** (Not in project)

**What is Node.js?**
- JavaScript runtime for server-side
- Built on Chrome's V8 engine
- Event-driven, non-blocking I/O

**Node.js vs Spring Boot:**
| Feature | Node.js | Spring Boot |
|---------|---------|-------------|
| Language | JavaScript | Java |
| Performance | Excellent for I/O | Excellent overall |
| Use Case | Real-time apps | Enterprise apps |
| Ecosystem | npm | Maven |

---

## ❓ VIVA QUESTIONS & ANSWERS

### 🌐 General Web Technology Questions

**Q1: What is the difference between GET and POST methods?**

**Answer:**
- **GET:**
  - Retrieves data from server
  - Parameters in URL: `/search?q=java`
  - Can be bookmarked
  - Limited data size (URL length limit)
  - Not secure (visible in URL)
  - Idempotent (same result every time)
  - Used for: Search, pagination, filters

- **POST:**
  - Sends data to server
  - Data in request body (not visible)
  - Cannot be bookmarked
  - No size limit
  - More secure
  - Non-idempotent (can have side effects)
  - Used for: Login, registration, form submission

**In this project:**
- GET: `/catalog?category=Fiction` (browse books)
- POST: `/register-process` (user registration)

---

**Q2: Explain the MVC pattern.**

**Answer:**
MVC = Model-View-Controller (architectural pattern)

**Model:**
- Represents data and business logic
- Database entities
- In project: `User.java`, `Book.java`

**View:**
- Presentation layer (what user sees)
- HTML templates
- In project: `home.html`, `catalog.html`

**Controller:**
- Handles user input
- Updates model, selects view
- In project: `HomeController`, `BookController`

**Flow:**
```
User interacts → Controller → Updates Model → Selects View → Renders to User
```

**Benefits:**
- Separation of concerns
- Easier to test
- Multiple views for same model
- Independent development

---

**Q3: What is responsive web design?**

**Answer:**
Responsive design = Website adapts to different screen sizes

**Techniques:**
1. **Fluid Grid:** Percentage-based widths
2. **Flexible Images:** `max-width: 100%`
3. **Media Queries:** CSS for different screens

**Bootstrap Responsiveness:**
```html
<!-- 1 column on mobile, 2 on tablet, 3 on desktop -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
```

**Mobile-First Approach:**
1. Design for mobile first
2. Add features for larger screens
3. Better performance on mobile

**In this project:**
- Navigation collapses to hamburger menu on mobile
- Book grid: 1 column (mobile) → 2 columns (tablet) → 3 columns (desktop)
- Forms stack vertically on small screens

---

**Q4: What are the benefits of using Bootstrap?**

**Answer:**
1. **Rapid Development:**
   - Pre-built components (navbar, cards, forms)
   - No need to write CSS from scratch

2. **Responsive by Default:**
   - Grid system handles responsiveness
   - Mobile-first approach

3. **Cross-Browser Compatibility:**
   - Works on all modern browsers
   - Consistent look and feel

4. **Customizable:**
   - Can override default styles
   - Sass variables for theming

5. **Large Community:**
   - Extensive documentation
   - Third-party themes and plugins

6. **Accessibility:**
   - ARIA attributes included
   - Keyboard navigation support

**Disadvantages:**
- All sites look similar (Bootstrap-ish)
- Larger file size (if not customized)
- Learning curve for grid system

---

### 🌱 Spring Boot Specific Questions

**Q5: What is Spring Boot and how is it different from Spring Framework?**

**Answer:**
**Spring Framework:**
- Core Java framework
- Requires extensive configuration (XML/annotations)
- Need external server (Tomcat)
- Manual dependency management

**Spring Boot:**
- Built on Spring Framework
- **Auto-configuration:** Configures based on classpath
- **Embedded server:** Tomcat included
- **Starter dependencies:** Simplified deps
- **Production-ready features:** Metrics, health checks

**Key Differences:**
| Feature | Spring | Spring Boot |
|---------|--------|-------------|
| Configuration | Manual (XML) | Auto-config |
| Server | External Tomcat | Embedded |
| Setup Time | Hours | Minutes |
| Dependencies | Manual | Starters |

**Example:**
Spring Framework (XML config):
```xml
<bean id="dataSource" class="org.apache.commons.dbcp.BasicDataSource">
    <property name="url" value="jdbc:mysql://localhost/db" />
    <property name="username" value="root" />
    ...
</bean>
```

Spring Boot (application.properties):
```properties
spring.datasource.url=jdbc:mysql://localhost/bookstore_db
spring.datasource.username=root
```

---

**Q6: Explain Dependency Injection with an example.**

**Answer:**
**Dependency Injection (DI)** = Design pattern where objects receive their dependencies from external source

**Without DI (Tight Coupling):**
```java
public class UserService {
    private UserRepository repo = new UserRepository();  // Creates dependency
    
    public void saveUser(User user) {
        repo.save(user);
    }
}
```
Problems:
- Hard to test (can't mock repository)
- Hard to change implementation
- UserService controls creation

**With DI (Loose Coupling):**
```java
@Service
public class UserService {
    @Autowired  // Spring injects dependency
    private UserRepository repo;
    
    public void saveUser(User user) {
        repo.save(user);
    }
}
```
Benefits:
- Easy to test (can inject mock)
- Easy to change implementation
- Spring manages lifecycle

**Types of DI:**
1. **Constructor Injection** (recommended)
```java
@Service
public class UserService {
    private final UserRepository repo;
    
    @Autowired
    public UserService(UserRepository repo) {
        this.repo = repo;
    }
}
```

2. **Field Injection** (used in project)
```java
@Autowired
private UserRepository repo;
```

3. **Setter Injection**
```java
@Autowired
public void setRepository(UserRepository repo) {
    this.repo = repo;
}
```

---

**Q7: What is JPA and Hibernate? How are they related?**

**Answer:**
**JPA (Java Persistence API):**
- **Specification** (set of interfaces/rules)
- Defines how ORM should work in Java
- Provides annotations: `@Entity`, `@Table`, `@Id`

**Hibernate:**
- **Implementation** of JPA
- Actual code that makes JPA work
- Most popular JPA provider

**Relationship:**
```
JPA (Interface/Specification)
    ↓ Implementation
Hibernate (Concrete implementation)
```

**Analogy:**
- JPA = Blueprint/Contract
- Hibernate = Actual building

**Other JPA Implementations:**
- EclipseLink
- OpenJPA

**ORM (Object-Relational Mapping):**
- Maps Java objects to database tables
- No need to write SQL queries

**Example:**
```java
@Entity  // JPA annotation
@Table(name = "users")  // JPA annotation
public class User {
    @Id  // JPA annotation
    private Long id;
    private String username;
}

// Hibernate generates SQL:
// CREATE TABLE users (id BIGINT, username VARCHAR(255))
```

---

**Q8: Explain the repository pattern used in the project.**

**Answer:**
**Repository Pattern** = Abstraction layer between business logic and data access

**Purpose:**
- Encapsulates database operations
- Makes code testable
- Centralizes data access logic

**In Project:**
```java
public interface UserRepository extends JpaRepository<User, Long> {
    Optional<User> findByUsername(String username);
    boolean existsByEmail(String email);
}
```

**How it works:**
1. Define interface extending `JpaRepository`
2. Spring Data JPA creates implementation at runtime
3. No need to write implementation code!

**JpaRepository Methods (built-in):**
```java
save(entity)           // Insert or update
findById(id)           // Find by primary key
findAll()              // Get all records
deleteById(id)         // Delete by ID
count()                // Count records
existsById(id)         // Check if exists
```

**Custom Query Methods:**
Spring Data JPA parses method name and generates SQL:
```java
// Method name → SQL
findByUsername(String username)
→ SELECT * FROM users WHERE username = ?

existsByEmail(String email)
→ SELECT COUNT(*) > 0 FROM users WHERE email = ?

findByCategoryAndPriceBetween(String cat, BigDecimal min, BigDecimal max)
→ SELECT * FROM books WHERE category = ? AND price BETWEEN ? AND ?
```

**Benefits:**
- Less boilerplate code
- Automatic implementation
- Type-safe queries
- Consistent API

---

**Q9: How does Spring Security work in this project?**

**Answer:**
**Spring Security** provides authentication and authorization

**Configuration (`SecurityConfig.java`):**
```java
@Configuration
@EnableWebSecurity
public class SecurityConfig {
    
    @Bean
    public SecurityFilterChain securityFilterChain(HttpSecurity http) {
        http
            // Which URLs require login?
            .authorizeHttpRequests(auth -> auth
                .requestMatchers("/", "/login", "/register").permitAll()  // Public
                .anyRequest().authenticated()  // All others need login
            )
            // How to login?
            .formLogin(form -> form
                .loginPage("/login")              // Custom login page
                .defaultSuccessUrl("/catalog")    // Where to go after login
            )
            // How to logout?
            .logout(logout -> logout
                .logoutUrl("/logout")
                .logoutSuccessUrl("/login?logout=true")
            );
        return http.build();
    }
}
```

**Authentication Flow:**
1. User visits protected page (e.g., `/catalog`)
2. Not logged in → Redirect to `/login`
3. User enters username/password
4. Spring Security:
   - Finds user in database
   - Verifies password using BCrypt
   - Creates authentication token
   - Stores in session
5. Redirects to `/catalog`
6. User can now access protected pages

**Password Encryption:**
```java
@Bean
public PasswordEncoder passwordEncoder() {
    return new BCryptPasswordEncoder();
}

// Usage in UserService:
String encrypted = passwordEncoder.encode("password123");
// Result: $2a$10$N9qo8uLOickgx2ZMRZoMye...

// Verification:
boolean matches = passwordEncoder.matches("password123", encrypted);
```

**Security Features:**
- Password hashing (BCrypt)
- Session management
- CSRF protection (disabled for demo)
- Remember-me functionality

---

**Q10: What is the difference between @Controller and @RestController?**

**Answer:**
**@Controller:**
- Returns **view names**
- Used for web applications (HTML pages)
- Thymeleaf processes view and generates HTML

```java
@Controller
public class HomeController {
    @GetMapping("/")
    public String home() {
        return "home";  // Returns home.html
    }
}
```

**@RestController:**
- Returns **data directly** (JSON/XML)
- Combination of `@Controller + @ResponseBody`
- Used for REST APIs

```java
@RestController
public class BookApiController {
    @GetMapping("/api/books")
    public List<Book> getBooks() {
        return bookService.getAllBooks();
        // Returns: [{"title":"Java","author":"John"}]
    }
}
```

**Comparison:**
| Feature | @Controller | @RestController |
|---------|-------------|-----------------|
| Returns | View name | Data (JSON) |
| Use case | Web pages | REST APIs |
| Response | HTML | JSON/XML |
| Needs | Thymeleaf | Jackson |

**In this project:**
- Used `@Controller` for web pages
- Returns HTML rendered by Thymeleaf
- Could add `@RestController` for mobile app API

---

### 🗄️ Database & MySQL Questions

**Q11: What is the difference between SQL and NoSQL databases?**

**Answer:**
**SQL (Relational):**
- Structured data (tables, rows, columns)
- Fixed schema
- ACID properties
- Examples: MySQL, PostgreSQL, Oracle
- Use: Structured data, transactions

**NoSQL (Non-relational):**
- Flexible schema
- Document, key-value, graph, column-family
- BASE properties
- Examples: MongoDB, Redis, Cassandra
- Use: Big data, real-time apps

**In this project:**
- MySQL (SQL database)
- Structured data (users, books)
- Relationships between tables

---

**Q12: Explain ACID properties.**

**Answer:**
**ACID** = Database transaction properties

**A - Atomicity:**
- All or nothing
- Transaction either completes fully or not at all
- Example: Bank transfer
  - Debit account A ✓
  - Credit account B ✗ (fails)
  - Both operations rolled back

**C - Consistency:**
- Database remains in valid state
- Constraints maintained
- Example: Foreign key constraints enforced

**I - Isolation:**
- Concurrent transactions don't interfere
- Each transaction sees consistent data
- Example: Two users updating same book stock

**D - Durability:**
- Committed data is permanent
- Survives system crash
- Saved to disk, not just memory

**In Spring Boot:**
- `@Transactional` provides ACID properties
- All operations in method succeed or all rollback

```java
@Transactional
public void processOrder(Order order) {
    // If any fails, all rollback
    orderRepository.save(order);
    bookService.decreaseStock(order.getBookId(), order.getQuantity());
    emailService.sendConfirmation(order.getUserEmail());
}
```

---

**Q13: What are the different types of relationships in databases?**

**Answer:**
**1. One-to-One (1:1):**
- One record in Table A → One record in Table B
- Example: User ↔ UserProfile

**2. One-to-Many (1:N):**
- One record in Table A → Many records in Table B
- Example: Author → Books (one author, many books)

**3. Many-to-One (N:1):**
- Many records in Table A → One record in Table B
- Example: Books → Publisher (many books, one publisher)

**4. Many-to-Many (M:N):**
- Many in Table A → Many in Table B
- Requires junction table
- Example: Books ↔ Categories

**JPA Annotations:**
```java
// One-to-Many
@Entity
public class Author {
    @OneToMany(mappedBy = "author")
    private List<Book> books;
}

// Many-to-One
@Entity
public class Book {
    @ManyToOne
    @JoinColumn(name = "author_id")
    private Author author;
}

// Many-to-Many
@Entity
public class Book {
    @ManyToMany
    @JoinTable(
        name = "book_category",
        joinColumns = @JoinColumn(name = "book_id"),
        inverseJoinColumns = @JoinColumn(name = "category_id")
    )
    private Set<Category> categories;
}
```

---

**Q14: What is normalization? Explain with example.**

**Answer:**
**Normalization** = Process of organizing data to reduce redundancy

**Why Normalize?**
- Eliminate duplicate data
- Ensure data dependencies make sense
- Easier to maintain

**Normal Forms:**

**1NF (First Normal Form):**
- Each column has atomic (single) value
- No repeating groups

**Before 1NF:**
```
| User ID | Name | Phone Numbers          |
|---------|------|------------------------|
| 1       | John | 1234567890, 9876543210 |
```

**After 1NF:**
```
| User ID | Name | Phone Number |
|---------|------|--------------|
| 1       | John | 1234567890   |
| 1       | John | 9876543210   |
```

**2NF (Second Normal Form):**
- Must be in 1NF
- No partial dependencies

**3NF (Third Normal Form):**
- Must be in 2NF
- No transitive dependencies

**Example in Project:**
Instead of storing author details in books table:

**Denormalized (BAD):**
```
books
| id | title | author_name | author_email | author_phone |
```
- Author data repeated for each book
- Update anomaly (author changes email)

**Normalized (GOOD):**
```
authors
| id | name | email | phone |

books
| id | title | author_id |
```
- Author data stored once
- Easy to update author info

---

### 🎨 Front-End Questions

**Q15: What is the box model in CSS?**

**Answer:**
**Box Model** = How HTML elements are rendered

**Components (from inside out):**
1. **Content** - Text/image
2. **Padding** - Space inside border
3. **Border** - Border around padding
4. **Margin** - Space outside border

**Visual:**
```
┌─────────────────────────────┐
│   Margin (transparent)      │
│  ┌───────────────────────┐  │
│  │ Border                │  │
│  │ ┌──────────────────┐  │  │
│  │ │ Padding          │  │  │
│  │ │ ┌──────────────┐ │  │  │
│  │ │ │   Content    │ │  │  │
│  │ │ │   (text/img) │ │  │  │
│  │ │ └──────────────┘ │  │  │
│  │ └──────────────────┘  │  │
│  └───────────────────────┘  │
└─────────────────────────────┘
```

**CSS Example:**
```css
div {
    width: 200px;        /* Content width */
    padding: 20px;       /* Padding all sides */
    border: 5px solid;   /* Border */
    margin: 10px;        /* Margin all sides */
}

/* Total width = 200 + 20*2 + 5*2 + 10*2 = 270px */
```

**box-sizing Property:**
```css
/* Default */
box-sizing: content-box;
/* width = content only */

/* Better for responsive design */
box-sizing: border-box;
/* width = content + padding + border */
```

**In Bootstrap:**
- Uses `border-box` by default
- Easier responsive calculations

---

**Q16: Explain Flexbox.**

**Answer:**
**Flexbox** = CSS layout module for responsive layouts

**Enable Flexbox:**
```css
.container {
    display: flex;
}
```

**Main Concepts:**
1. **Flex Container** - Parent element
2. **Flex Items** - Children elements
3. **Main Axis** - Primary direction
4. **Cross Axis** - Perpendicular to main

**Container Properties:**
```css
.container {
    display: flex;
    
    /* Direction */
    flex-direction: row;        /* left to right (default) */
    flex-direction: column;     /* top to bottom */
    
    /* Horizontal alignment (main axis) */
    justify-content: flex-start;    /* left */
    justify-content: center;        /* center */
    justify-content: space-between; /* space between items */
    
    /* Vertical alignment (cross axis) */
    align-items: flex-start;   /* top */
    align-items: center;       /* middle */
    align-items: flex-end;     /* bottom */
    
    /* Wrap */
    flex-wrap: wrap;           /* wrap to next line */
}
```

**Item Properties:**
```css
.item {
    flex-grow: 1;      /* grow to fill space */
    flex-shrink: 1;    /* shrink if needed */
    flex-basis: 200px; /* base size */
    
    /* Shorthand */
    flex: 1;  /* flex-grow: 1, shrink: 1, basis: 0 */
}
```

**Example:**
```html
<div class="container">
    <div class="item">1</div>
    <div class="item">2</div>
    <div class="item">3</div>
</div>

<style>
.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
```

**In Project:**
- Hero section uses flexbox
- Navbar uses flexbox (Bootstrap)
- Responsive layouts

---

**Q17: What are media queries?**

**Answer:**
**Media Queries** = CSS technique for responsive design

**Syntax:**
```css
@media (condition) {
    /* CSS rules */
}
```

**Common Breakpoints:**
```css
/* Mobile (default styles) */
body {
    font-size: 14px;
}

/* Tablet and up */
@media (min-width: 768px) {
    body {
        font-size: 16px;
    }
}

/* Desktop and up */
@media (min-width: 992px) {
    body {
        font-size: 18px;
    }
}
```

**Media Query Conditions:**
```css
/* Width */
@media (max-width: 768px) { }    /* Up to 768px */
@media (min-width: 768px) { }    /* 768px and above */

/* Height */
@media (max-height: 600px) { }

/* Orientation */
@media (orientation: landscape) { }
@media (orientation: portrait) { }

/* Device type */
@media screen { }   /* Screens */
@media print { }    /* Printers */

/* Resolution */
@media (min-resolution: 2dppx) { }  /* Retina displays */

/* Multiple conditions */
@media (min-width: 768px) and (max-width: 992px) { }
```

**In Project (style.css):**
```css
/* Mobile styles */
@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 2rem;  /* Smaller heading */
    }
    
    .btn {
        width: 100%;  /* Full-width buttons */
    }
}
```

**Bootstrap's Approach:**
- Mobile-first
- Uses `min-width` media queries
- Pre-defined breakpoints (sm, md, lg, xl)

---

### 🔒 Security Questions

**Q18: What is CSRF and how does Spring Security prevent it?**

**Answer:**
**CSRF (Cross-Site Request Forgery):**
- Attack where malicious site performs action on victim's behalf

**Attack Example:**
1. User logs into `banksite.com`
2. User visits `malicious.com` (without logging out)
3. `malicious.com` contains:
```html
<form action="banksite.com/transfer" method="POST">
    <input name="amount" value="10000">
    <input name="to" value="hacker">
</form>
<script>document.forms[0].submit();</script>
```
4. Form auto-submits using victim's session
5. Money transferred without victim knowing!

**Spring Security Prevention:**
**CSRF Token:**
- Server generates unique token for each session
- Token included in forms
- Server validates token before processing request

**How it works:**
1. User logs in
2. Server generates token: "abc123xyz..."
3. Token stored in session
4. Token included in forms:
```html
<input type="hidden" name="_csrf" value="abc123xyz" />
```
5. Form submitted
6. Server checks: Does token match session?
   - Yes → Process request
   - No → Reject (403 Forbidden)

**Malicious site cannot:**
- Access user's session
- Get the CSRF token
- Submit valid request

**In Project:**
```java
http.csrf(csrf -> csrf.disable());  // Disabled for demo
```

**In Production:**
```java
http.csrf(csrf -> csrf
    .csrfTokenRepository(CookieCsrfTokenRepository.withHttpOnlyFalse())
);
```

**Thymeleaf Auto-adds Token:**
```html
<form th:action="@{/submit}" method="post">
    <!-- CSRF token auto-added by Thymeleaf -->
</form>
```

---

**Q19: Why should passwords never be stored in plain text?**

**Answer:**
**Risks of Plain Text Passwords:**

1. **Database Breach:**
   - Hacker gains database access
   - All passwords exposed
   - Users use same password everywhere

2. **Insider Threat:**
   - Database admins can see passwords
   - Developers can access during debugging

3. **Logging:**
   - Passwords might appear in logs
   - Error messages, debug output

4. **Legal/Compliance:**
   - GDPR, PCI-DSS require encryption
   - Legal liability for breaches

**Solution: Password Hashing**

**Hashing:**
- One-way function
- Cannot reverse (decrypt)
- Same input → Same hash
- Different input → Different hash

**BCrypt Algorithm:**
```java
// Plain text
String password = "password123";

// Hashed
String hash = passwordEncoder.encode(password);
// Result: $2a$10$N9qo8uLOickgx2ZMRZoMye...

// Verification
boolean matches = passwordEncoder.matches("password123", hash);
// Returns: true
```

**BCrypt Features:**
1. **Salt:** Random data added to password
2. **Cost Factor:** Controls hash strength (default: 10)
3. **Different hashes:** Same password → Different hashes

**Example:**
```
password123 → $2a$10$abc... (first hash)
password123 → $2a$10$xyz... (second hash - different!)
```

**Why Different?**
- Random salt each time
- Prevents rainbow table attacks

**In Project:**
```java
@Bean
public PasswordEncoder passwordEncoder() {
    return new BCryptPasswordEncoder();
}

// UserService
String encrypted = passwordEncoder.encode(user.getPassword());
user.setPassword(encrypted);
```

---

**Q20: Explain the difference between authentication and authorization.**

**Answer:**
**Authentication:**
- **Who are you?**
- Verifies identity
- Login process
- Example: Username/password, fingerprint, OTP

**Authorization:**
- **What can you do?**
- Determines permissions
- Access control
- Example: Admin can delete, user cannot

**Analogy:**
- **Authentication** = Showing ID at airport
- **Authorization** = Boarding pass for specific flight

**In Spring Security:**

**Authentication:**
```java
// Login form
username: john
password: password123

// Spring Security verifies:
1. User exists?
2. Password correct?
3. Account enabled?
```

**Authorization:**
```java
http.authorizeHttpRequests(auth -> auth
    .requestMatchers("/admin/**").hasRole("ADMIN")  // Only admins
    .requestMatchers("/user/**").hasRole("USER")     // All users
    .requestMatchers("/", "/login").permitAll()      // Everyone
);
```

**Roles vs Authorities:**
- **Role:** `ROLE_ADMIN`, `ROLE_USER`
- **Authority:** `READ_BOOKS`, `WRITE_BOOKS`
- Roles are high-level, authorities are granular

**Example Workflow:**
1. **Authentication:** User logs in → Spring creates authentication token
2. **Authorization:** User tries `/admin/users` → Spring checks role
   - Has `ROLE_ADMIN`? → Allow
   - Doesn't have? → Deny (403 Forbidden)

**In Project:**
```java
@Entity
public class User {
    private String role = "ROLE_USER";  // Default role
}

// SecurityConfig
.anyRequest().authenticated()  // All pages need login (authentication)
```

**Could Add Authorization:**
```java
.requestMatchers("/admin/**").hasRole("ADMIN")
.requestMatchers("/catalog").hasAnyRole("USER", "ADMIN")
```

---

### 📝 General Project Questions

**Q21: Walk me through what happens when a user registers.**

**Answer:**
**User Registration Flow:**

**1. User Visits Registration Page**
```
Browser → GET /register → AuthController.showRegistrationPage()
```
- Controller creates empty `User` object
- Adds to model
- Returns "registration" view

**2. Thymeleaf Renders Form**
```html
<form th:object="${user}" th:action="@{/register-process}" method="post">
```
- Form bound to User object
- Fields: username, email, password, etc.

**3. User Fills Form and Submits**
```
Browser → POST /register-process
Data: {username: "john", email: "john@example.com", password: "password123", ...}
```

**4. Controller Receives Request**
```java
@PostMapping("/register-process")
public String registerUser(
    @Valid @ModelAttribute("user") User user,
    BindingResult bindingResult) {
```
- Spring binds form data to User object
- `@Valid` triggers validation

**5. Validation**
```java
// Entity annotations checked
@NotBlank(message = "Username is required")
@Email(message = "Please provide a valid email")
@Size(min = 6, message = "Password must be at least 6 characters")
```
- If errors → Return to registration form with error messages
- If valid → Continue

**6. Service Layer Processing**
```java
userService.registerUser(user);
```

**UserService.registerUser():**
```java
// Check if username exists
if (userRepository.existsByUsername(user.getUsername())) {
    throw new RuntimeException("Username already exists!");
}

// Check if email exists
if (userRepository.existsByEmail(user.getEmail())) {
    throw new RuntimeException("Email already registered!");
}

// Encrypt password
String encrypted = passwordEncoder.encode(user.getPassword());
user.setPassword(encrypted);

// Set defaults
user.setRole("ROLE_USER");
user.setEnabled(true);

// Save to database
return userRepository.save(user);
```

**7. Database Operation**
- JPA/Hibernate generates SQL:
```sql
INSERT INTO users (username, email, password, full_name, ...) 
VALUES ('john', 'john@example.com', '$2a$10$...', 'John Doe', ...);
```
- User record created in `users` table
- ID auto-generated

**8. Response to User**
```java
redirectAttributes.addFlashAttribute("message", "Registration successful!");
return "redirect:/login";
```
- Success message stored in flash scope
- Redirects to login page
- POST-Redirect-GET pattern (prevents duplicate submission)

**9. User Logs In**
- Can now login with created credentials
- Spring Security authenticates against database

**Complete Data Flow:**
```
Browser (Registration Form)
    ↓ POST /register-process
Controller (@Valid, BindingResult)
    ↓ Validation OK
Service (Business logic, password encryption)
    ↓
Repository (JPA/Hibernate)
    ↓ SQL INSERT
Database (MySQL users table)
    ↓ Success
Service
    ↓
Controller (redirect to login)
    ↓ 302 Redirect
Browser (Login Page with success message)
```

---

**Q22: How does the catalog page display books from the database?**

**Answer:**
**Catalog Page Flow:**

**1. User Visits Catalog**
```
Browser → GET /catalog → BookController.showCatalog()
```

**2. Controller Method**
```java
@GetMapping("/catalog")
public String showCatalog(
    @RequestParam(value = "category", required = false) String category,
    @RequestParam(value = "search", required = false) String search,
    Model model) {
```
- Extracts query parameters from URL
- Example: `/catalog?category=Fiction`

**3. Service Layer Call**
```java
List<Book> books;

if (search != null && !search.trim().isEmpty()) {
    books = bookService.searchBooks(search);
} else if (category != null) {
    books = bookService.getBooksByCategory(category);
} else {
    books = bookService.getAllBooks();
}
```

**4. Repository Query**
```java
// BookService.getAllBooks()
public List<Book> getAllBooks() {
    return bookRepository.findAll();
}
```

**5. JPA/Hibernate Generates SQL**
```sql
SELECT * FROM books;
```
- Hibernate executes query
- Maps result rows to Book objects
- Returns List<Book>

**6. Add Data to Model**
```java
model.addAttribute("books", books);
model.addAttribute("categories", bookService.getAllCategories());
model.addAttribute("bookCount", books.size());
```
- Model acts as container for view data

**7. Return View Name**
```java
return "catalog";  // Returns catalog.html
```

**8. Thymeleaf Processes Template**
```html
<div th:each="book : ${books}" class="col">
    <div class="card">
        <img th:src="${book.imageUrl}" />
        <h5 th:text="${book.title}">Title</h5>
        <p th:text="${book.author}">Author</p>
        <h4 th:text="${book.formattedPrice}">Price</h4>
    </div>
</div>
```

**Thymeleaf Processing:**
```
${books} → [Book{id=1, title="Java", ...}, Book{id=2, ...}]
    ↓ th:each loops
Book #1 → Creates card with Java book details
Book #2 → Creates card with next book details
...
```

**9. HTML Generated**
```html
<div class="col">
    <div class="card">
        <img src="https://..." />
        <h5>Java Programming</h5>
        <p>John Smith</p>
        <h4>₹499.00</h4>
    </div>
</div>
<!-- Repeated for each book -->
```

**10. Response to Browser**
- Complete HTML page sent to browser
- Browser renders books in responsive grid

**Filter Example:**
```
User clicks "Fiction" category
    ↓
GET /catalog?category=Fiction
    ↓
BookController extracts category="Fiction"
    ↓
Calls bookService.getBooksByCategory("Fiction")
    ↓
Repository: SELECT * FROM books WHERE category = 'Fiction'
    ↓
Returns List of Fiction books
    ↓
Model: books = [Fiction books]
    ↓
Thymeleaf renders only Fiction books
    ↓
Browser displays filtered results
```

---

**Q23: Explain the login process in detail.**

**Answer:**
**Complete Login Flow:**

**1. User Accesses Protected Page**
```
User → GET /catalog (requires authentication)
    ↓
Spring Security Filter Chain intercepts
    ↓
User not authenticated?
    ↓
Redirect → GET /login
```

**2. Login Page Displayed**
```java
@GetMapping("/login")
public String showLoginPage(
    @RequestParam(value = "error", required = false) String error,
    Model model) {
    if (error != null) {
        model.addAttribute("error", "Invalid username or password!");
    }
    return "login";
}
```

**3. User Enters Credentials**
```html
<form th:action="@{/login-process}" method="post">
    <input name="username" value="john" />
    <input name="password" value="password123" />
    <button type="submit">Login</button>
</form>
```

**4. Form Submission**
```
Browser → POST /login-process
Data: username=john&password=password123
```

**5. Spring Security Filter Chain**

**a) UsernamePasswordAuthenticationFilter intercepts:**
```java
// Extracts credentials from request
String username = request.getParameter("username");  // "john"
String password = request.getParameter("password");  // "password123"

// Creates authentication token (unauthenticated)
Authentication authRequest = 
    new UsernamePasswordAuthenticationToken(username, password);
```

**b) AuthenticationManager authenticates:**
```java
Authentication result = authenticationManager.authenticate(authRequest);
```

**c) UserDetailsService loads user:**
```java
// Spring Security calls (implemented automatically by Spring Data JPA)
UserDetails user = userDetailsService.loadUserByUsername("john");
```

Internally:
```java
Optional<User> userOpt = userRepository.findByUsername("john");
if (!userOpt.isPresent()) {
    throw new UsernameNotFoundException("User not found");
}

User user = userOpt.get();

// Create UserDetails object
return new org.springframework.security.core.userdetails.User(
    user.getUsername(),
    user.getPassword(),  // Encrypted password from DB
    Collections.singleton(new SimpleGrantedAuthority(user.getRole()))
);
```

**d) PasswordEncoder verifies password:**
```java
boolean matches = passwordEncoder.matches(
    "password123",              // Entered password (plain text)
    "$2a$10$N9qo8uLO..."        // Stored hash from database
);
```

**How BCrypt verification works:**
1. Extract salt from stored hash
2. Hash entered password with same salt
3. Compare hashes
4. Return true if match

**e) If credentials valid:**
```java
// Create authenticated token
Authentication authenticated = 
    new UsernamePasswordAuthenticationToken(
        user,                // Principal
        password,            // Credentials
        user.getAuthorities() // Roles
    );

// Store in SecurityContext
SecurityContextHolder.getContext().setAuthentication(authenticated);

// Store SecurityContext in session
HttpSession session = request.getSession();
session.setAttribute("SPRING_SECURITY_CONTEXT", securityContext);
```

**6. Redirect to Success URL**
```
302 Redirect → /catalog
```

**7. User Accesses Catalog**
```
GET /catalog
    ↓
Spring Security Filter checks session
    ↓
SecurityContext contains authentication?
    ↓
Yes → Allow access
    ↓
BookController.showCatalog() executes
    ↓
Returns catalog page
```

**8. Session Management**
- Session ID stored in cookie: `JSESSIONID`
- Browser sends cookie with every request
- Spring Security checks session for authentication

**Login Failure:**
```
Password incorrect
    ↓
AuthenticationException thrown
    ↓
Spring Security catches
    ↓
Redirect → /login?error=true
    ↓
Error message displayed
```

**Logout:**
```
User clicks Logout
    ↓
GET /logout
    ↓
Spring Security:
  - Invalidates session
  - Deletes JSESSIONID cookie
  - Clears SecurityContext
    ↓
Redirect → /login?logout=true
    ↓
"Logged out successfully" message
```

**Security Architecture:**
```
HTTP Request
    ↓
SecurityFilterChain
    ├─ ChannelProcessingFilter
    ├─ SecurityContextPersistenceFilter (load SecurityContext)
    ├─ LogoutFilter
    ├─ UsernamePasswordAuthenticationFilter (login)
    ├─ ExceptionTranslationFilter (handle security exceptions)
    └─ FilterSecurityInterceptor (authorization)
    ↓
Controller
```

---

**Q24: What testing strategies would you use for this application?**

**Answer:**
**Testing Pyramid:**

**1. Unit Tests (Base - Most tests)**
- Test individual methods
- Fast, isolated
- Mock dependencies

**Example: UserService Test**
```java
@ExtendWith(MockitoExtension.class)
class UserServiceTest {
    
    @Mock
    private UserRepository userRepository;
    
    @Mock
    private PasswordEncoder passwordEncoder;
    
    @InjectMocks
    private UserService userService;
    
    @Test
    void testRegisterUser_Success() {
        // Arrange
        User user = new User();
        user.setUsername("john");
        user.setPassword("password123");
        
        when(userRepository.existsByUsername("john")).thenReturn(false);
        when(passwordEncoder.encode("password123")).thenReturn("encrypted");
        when(userRepository.save(any(User.class))).thenReturn(user);
        
        // Act
        User result = userService.registerUser(user);
        
        // Assert
        assertNotNull(result);
        assertEquals("encrypted", result.getPassword());
        verify(userRepository).save(any(User.class));
    }
    
    @Test
    void testRegisterUser_UsernameExists() {
        // Arrange
        User user = new User();
        user.setUsername("john");
        
        when(userRepository.existsByUsername("john")).thenReturn(true);
        
        // Act & Assert
        assertThrows(RuntimeException.class, () -> {
            userService.registerUser(user);
        });
    }
}
```

**2. Integration Tests (Middle)**
- Test multiple components together
- Database involved (H2 in-memory)

**Example: Repository Test**
```java
@DataJpaTest
class UserRepositoryTest {
    
    @Autowired
    private UserRepository userRepository;
    
    @Test
    void testFindByUsername() {
        // Arrange
        User user = new User();
        user.setUsername("john");
        user.setEmail("john@example.com");
        user.setPassword("encrypted");
        userRepository.save(user);
        
        // Act
        Optional<User> found = userRepository.findByUsername("john");
        
        // Assert
        assertTrue(found.isPresent());
        assertEquals("john@example.com", found.get().getEmail());
    }
}
```

**3. End-to-End Tests (Top - Least tests)**
- Test entire application
- Simulate user interactions
- Selenium/TestContainers

**Example: Controller Test**
```java
@SpringBootTest
@AutoConfigureMockMvc
class AuthControllerTest {
    
    @Autowired
    private MockMvc mockMvc;
    
    @Test
    void testShowLoginPage() throws Exception {
        mockMvc.perform(get("/login"))
            .andExpect(status().isOk())
            .andExpect(view().name("login"));
    }
    
    @Test
    void testRegisterUser_Success() throws Exception {
        mockMvc.perform(post("/register-process")
                .param("username", "john")
                .param("email", "john@example.com")
                .param("password", "password123")
                .param("fullName", "John Doe"))
            .andExpect(status().is3xxRedirection())
            .andExpect(redirectedUrl("/login"));
    }
}
```

**Test Coverage Goals:**
- Unit Tests: 80%+ coverage
- Integration Tests: Critical paths
- E2E Tests: Happy paths

**Testing Tools:**
- **JUnit 5:** Test framework
- **Mockito:** Mocking framework
- **MockMvc:** Test Spring MVC controllers
- **H2:** In-memory database for tests
- **Selenium:** Browser automation
- **TestContainers:** Docker containers for integration tests

---

**Q25: How would you deploy this application to production?**

**Answer:**
**Deployment Options:**

**Option 1: Traditional Server (Tomcat)**

**1. Build WAR File**
```xml
<!-- Change in pom.xml -->
<packaging>war</packaging>
```

```bash
mvn clean package
```

**2. Deploy to Tomcat**
- Copy `target/bookstore.war` to Tomcat `webapps/` folder
- Start Tomcat
- Access: `http://server-ip:8080/bookstore`

---

**Option 2: Standalone JAR (Recommended)**

**1. Build JAR**
```bash
mvn clean package
```

**2. Run JAR**
```bash
java -jar target/online-bookstore-1.0.0.jar
```

**3. Run as Service (Linux)**
```bash
# Create systemd service
sudo nano /etc/systemd/system/bookstore.service

[Unit]
Description=Bookstore Application
After=network.target

[Service]
User=bookstore
ExecStart=/usr/bin/java -jar /opt/bookstore/app.jar
SuccessExitStatus=143
Restart=always

[Install]
WantedBy=multi-user.target

# Enable and start
sudo systemctl enable bookstore
sudo systemctl start bookstore
```

---

**Option 3: Docker Container**

**1. Create Dockerfile**
```dockerfile
FROM eclipse-temurin:17-jre
WORKDIR /app
COPY target/online-bookstore-1.0.0.jar app.jar
EXPOSE 8080
ENTRYPOINT ["java", "-jar", "app.jar"]
```

**2. Build Image**
```bash
docker build -t bookstore:1.0 .
```

**3. Run Container**
```bash
docker run -d \
  -p 8080:8080 \
  -e SPRING_DATASOURCE_URL=jdbc:mysql://mysql:3306/bookstore_db \
  -e SPRING_DATASOURCE_USERNAME=root \
  -e SPRING_DATASOURCE_PASSWORD=password \
  --name bookstore \
  bookstore:1.0
```

**4. Docker Compose (with MySQL)**
```yaml
version: '3.8'
services:
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: password
      MYSQL_DATABASE: bookstore_db
    volumes:
      - mysql-data:/var/lib/mysql
  
  bookstore:
    image: bookstore:1.0
    ports:
      - "8080:8080"
    environment:
      SPRING_DATASOURCE_URL: jdbc:mysql://mysql:3306/bookstore_db
      SPRING_DATASOURCE_USERNAME: root
      SPRING_DATASOURCE_PASSWORD: password
    depends_on:
      - mysql

volumes:
  mysql-data:
```

Run:
```bash
docker-compose up -d
```

---

**Option 4: Cloud Deployment**

**AWS Elastic Beanstalk:**
1. Create Elastic Beanstalk application
2. Upload JAR file
3. Configure environment (Java 17, MySQL RDS)
4. Deploy

**Heroku:**
```bash
# Install Heroku CLI
heroku create bookstore-app
git push heroku main
```

**Google Cloud Run:**
```bash
# Build container
docker build -t gcr.io/project-id/bookstore .

# Push to registry
docker push gcr.io/project-id/bookstore

# Deploy
gcloud run deploy bookstore \
  --image gcr.io/project-id/bookstore \
  --platform managed
```

---

**Production Checklist:**

**1. Configuration**
```properties
# application-prod.properties
server.port=80
spring.datasource.url=jdbc:mysql://production-db:3306/bookstore_db
spring.jpa.hibernate.ddl-auto=validate  # Don't auto-modify schema
logging.level.root=WARN
```

**2. Security**
- Enable HTTPS (SSL certificate)
- Enable CSRF protection
- Use strong database passwords
- Regular security updates

**3. Performance**
- Enable caching
- Connection pooling (HikariCP)
- Gzip compression
- CDN for static files

**4. Monitoring**
- Application logs (Logback/SLF4J)
- Health checks (`/actuator/health`)
- Metrics (`/actuator/metrics`)
- Error tracking (Sentry, Rollbar)

**5. Backup**
- Database backups (daily)
- Application backups
- Disaster recovery plan

**6. Scaling**
- Load balancer (Nginx, HAProxy)
- Multiple instances
- Database replication
- Caching (Redis, Memcached)

---

## 📂 FILE STRUCTURE

```
bookstore-project/
│
├── src/
│   ├── main/
│   │   ├── java/
│   │   │   └── com/bookstore/
│   │   │       ├── entity/
│   │   │       │   ├── User.java             # User entity (database model)
│   │   │       │   └── Book.java             # Book entity
│   │   │       │
│   │   │       ├── repository/
│   │   │       │   ├── UserRepository.java   # User database operations
│   │   │       │   └── BookRepository.java   # Book database operations
│   │   │       │
│   │   │       ├── service/
│   │   │       │   ├── UserService.java      # User business logic
│   │   │       │   └── BookService.java      # Book business logic
│   │   │       │
│   │   │       ├── controller/
│   │   │       │   ├── HomeController.java   # Home page controller
│   │   │       │   ├── AuthController.java   # Login/registration
│   │   │       │   └── BookController.java   # Catalog page
│   │   │       │
│   │   │       ├── config/
│   │   │       │   └── SecurityConfig.java   # Spring Security config
│   │   │       │
│   │   │       └── BookstoreApplication.java # Main application class
│   │   │
│   │   └── resources/
│   │       ├── templates/                    # Thymeleaf HTML templates
│   │       │   ├── home.html                 # Home page
│   │       │   ├── login.html                # Login page
│   │       │   ├── registration.html         # Registration page
│   │       │   └── catalog.html              # Catalog page
│   │       │
│   │       ├── static/                       # Static resources
│   │       │   └── css/
│   │       │       └── style.css             # Custom CSS
│   │       │
│   │       └── application.properties        # Application configuration
│   │
│   └── test/                                 # Test files (optional)
│
├── pom.xml                                   # Maven dependencies
├── README.md                                 # This file
└── sample_data.sql                           # Sample data SQL
```

**Layer Responsibilities:**

| Layer | Files | Purpose |
|-------|-------|---------|
| **Entity** | User.java, Book.java | Database models (tables) |
| **Repository** | UserRepository, BookRepository | Database queries |
| **Service** | UserService, BookService | Business logic |
| **Controller** | HomeController, AuthController, BookController | HTTP request handling |
| **Config** | SecurityConfig | Application configuration |
| **Templates** | *.html | HTML views |
| **Static** | *.css | Stylesheets |

---

## ✅ FEATURES IMPLEMENTED

### ✓ Home Page
- Responsive landing page
- Hero section with call-to-action
- Feature cards (Wide Selection, Fast Delivery, Secure Payment)
- Navigation bar
- Footer

### ✓ Login Page
- User authentication form
- Error message display
- Success message after logout/registration
- "Remember me" checkbox
- Link to registration

### ✓ Registration Page
- User signup form
- Form validation (client + server side)
- Password encryption (BCrypt)
- Duplicate username/email check
- Success message after registration

### ✓ Catalog Page
- Display all books from database
- Search functionality (title/author)
- Category filter
- Responsive book grid
- Book details (title, author, price, stock)
- "Add to Cart" button (disabled if out of stock)

### ✓ Database Integration
- MySQL database
- JPA/Hibernate ORM
- Automatic table creation
- CRUD operations

### ✓ Security
- Spring Security integration
- Password encryption (BCrypt)
- Session management
- Protected pages (require login)
- Public pages (no login needed)

### ✓ Responsive Design
- Bootstrap 5 framework
- Mobile-friendly navigation
- Responsive grid system
- Works on all devices

---

## 🎓 FOR VIVA PREPARATION

**Key Points to Remember:**

1. **Project Overview:**
   - Technology: Spring Boot + MySQL
   - Pages: Home, Login, Registration, Catalog
   - Security: Spring Security with BCrypt

2. **Architecture:**
   - MVC pattern
   - Layered architecture (Controller → Service → Repository → Database)
   - RESTful URL design

3. **Technologies:**
   - Backend: Spring Boot, JPA/Hibernate
   - Frontend: Thymeleaf, Bootstrap 5
   - Database: MySQL
   - Build: Maven

4. **Key Features:**
   - User authentication and authorization
   - Responsive design
   - Form validation
   - Database operations

**Demo Flow:**
1. Show home page
2. Register new user
3. Login with created credentials
4. Browse catalog
5. Search/filter books
6. Explain code structure

---

## 📞 SUPPORT

If you face any issues:

1. **Check Prerequisites:**
   - Java 17 installed?
   - MySQL running?
   - Database created?

2. **Common Issues:**
   - **Port 8080 in use:** Change port in `application.properties`
   - **Database connection failed:** Check MySQL credentials
   - **Dependencies not downloading:** Check internet connection

3. **Logs:**
   - Check console output for errors
   - Look for stack traces

---

**Created for:** Web Technology Lab - Question 14  
**Institution:** [Your Institution Name]  
**Academic Year:** 2024-25  
**Developed by:** [Your Name]

---

**Good luck with your viva! 🎉**
