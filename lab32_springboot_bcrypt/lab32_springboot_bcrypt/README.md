# Step 1: Build
mvn clean install -DskipTests

# Step 2: Run
mvn spring-boot:run


# 🔐 Lab Q32 – Spring Boot BCrypt Password Encryption
## Secure User Authentication with Spring Security

---

## 📁 Complete File Structure

```
lab32_springboot_bcrypt/
│
├── pom.xml                                    ← Maven build file (dependencies)
│
├── src/main/java/com/lab32/security/
│   ├── Lab32SecurityApplication.java          ← Main class (@SpringBootApplication)
│   │
│   ├── config/
│   │   ├── SecurityConfig.java               ← BCrypt config + HTTP security rules
│   │   └── DataInitializer.java              ← Seeds demo users on startup
│   │
│   ├── model/
│   │   ├── AppUser.java                      ← JPA Entity (maps to DB table)
│   │   └── RegisterRequest.java              ← DTO for registration form
│   │
│   ├── repository/
│   │   └── AppUserRepository.java            ← Database access (JPA queries)
│   │
│   ├── service/
│   │   ├── UserService.java                  ← Business logic (hash + save)
│   │   └── UserDetailsServiceImpl.java       ← Spring Security auth bridge
│   │
│   └── controller/
│       └── AuthController.java               ← HTTP request handlers (MVC)
│
├── src/main/resources/
│   ├── application.properties               ← App configuration (DB, server, etc.)
│   └── templates/                           ← Thymeleaf HTML templates
│       ├── login.html                       ← Login form
│       ├── register.html                    ← Registration form
│       ├── dashboard.html                   ← Post-login page (Task 5)
│       ├── users.html                       ← All users + their BCrypt hashes
│       └── demo.html                        ← Interactive BCrypt encoder/verifier
│
└── src/test/
    └── java/com/lab32/security/             ← Unit tests (optional)
```

---

## ⚙️ Setup and Run Commands (Complete Step-by-Step)

### Prerequisites — Install These First

**Step 1: Install Java 17 (JDK)**
```bash
# Check if Java is installed:
java -version
# Should show: openjdk 17.x.x or similar

# If NOT installed:
# Windows: Download from https://adoptium.net/ → Java 17
# Ubuntu:  sudo apt install openjdk-17-jdk
# Mac:     brew install openjdk@17
```

**Step 2: Install Maven**
```bash
# Check if Maven is installed:
mvn -version
# Should show: Apache Maven 3.x.x

# If NOT installed:
# Windows: https://maven.apache.org/download.cgi → extract → add to PATH
# Ubuntu:  sudo apt install maven
# Mac:     brew install maven
```

**Step 3: VS Code Extensions (recommended)**
Install these from VS Code Extensions tab (Ctrl+Shift+X):
- **Extension Pack for Java** (Microsoft) — Java language support
- **Spring Boot Extension Pack** (VMware) — Spring Boot tools
- **Lombok Annotations Support** — Required for @Getter, @Setter etc.

---

### Run the Application

**Method A: Terminal (Simplest)**
```bash
# 1. Extract the zip
# 2. Open terminal in the lab32_springboot_bcrypt folder
cd lab32_springboot_bcrypt

# 3. Download dependencies and compile (first time takes 2-5 minutes)
mvn clean install -DskipTests

# 4. Run the application
mvn spring-boot:run
```

 http://localhost:8080/login
You should see a login form with demo credentials.

Try the register page:

   http://localhost:8080/register
You should see a registration form.

If those work, test the root:

   http://localhost:8080

   Username Password 
   admin admin123
   alice  alice123  
   bob   bob123

**You should see:**
```
  .   ____          _            __ _ _
 /\\ / ___'_ __ _ _(_)_ __  __ _ \ \ \ \
...
Tomcat started on port 8080
Started Lab32SecurityApplication in 3.456 seconds
===================================
  Lab Q32: BCrypt Password Demo

  Open: http://localhost:8080
  H2 DB: http://localhost:8080/h2-console
===================================
```

**Method B: VS Code**
1. Open folder in VS Code: `File → Open Folder → lab32_springboot_bcrypt`
2. Wait for Java extension to index (bottom right shows "Java" loading)
3. Open `Lab32SecurityApplication.java`
4. Click the ▶ **Run** button above `public static void main`

**Method C: Run as JAR**
```bash
mvn clean package -DskipTests
java -jar target/security-0.0.1-SNAPSHOT.jar
```

**Stop the server:** Press `Ctrl + C` in terminal

---

### Access the Application

| URL | Description |
|-----|-------------|
| `http://localhost:8080` | Home → redirects to login |
| `http://localhost:8080/login` | Login page |
| `http://localhost:8080/register` | Register new user |
| `http://localhost:8080/dashboard` | Dashboard (requires login) |
| `http://localhost:8080/users` | All users + BCrypt hashes |
| `http://localhost:8080/demo` | Interactive BCrypt demo |
| `http://localhost:8080/h2-console` | View database (H2 console) |

---

### View Database (H2 Console)

1. Open `http://localhost:8080/h2-console`
2. Fill in:
   - **JDBC URL:** `jdbc:h2:mem:lab32db`
   - **Username:** `sa`
   - **Password:** *(leave blank)*
3. Click **Connect**
4. Run SQL to see users:
```sql
SELECT * FROM APP_USER;
```

**You'll see the BCrypt hashes in the PASSWORD column — never the plain passwords!**

---

## 🖥️ Expected Output (Show to Examiner)

### 1. Application Startup Console
```
Tomcat started on port(s): 8080
Started Lab32SecurityApplication in 4.234 seconds

Creating admin | plain: admin123 | hash: $2a$10$xyz...
Creating alice | plain: alice123 | hash: $2a$10$abc...
Creating bob   | plain: bob123   | hash: $2a$10$def...
Demo users created. Total users: 3
```

### 2. Login Page (`/login`)
- Blue gradient background
- Login form with username/password
- Show-password eye toggle button
- Demo credentials panel: admin/admin123, alice/alice123, bob/bob123

**Wrong password attempt:**
```
❌ Invalid username or password. Please check your credentials.
```

### 3. Dashboard (`/dashboard`) — Task 5
```
✅ Authentication Successful! — Task 5
Welcome, alice! You are authenticated with role: ROLE_USER

Your Stored BCrypt Hash (Task 2):
$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy

BCrypt Steps: [Configure] → [Encode] → [Authenticate] → [Verify]
```

### 4. Users Page (`/users`) — Task 2
Table showing all users with:
```
ID | Username | Email          | Role       | BCrypt Hash (60 chars)
1  | admin    | admin@lab32.com| ROLE_ADMIN | $2a$10$[abc...xyz]
2  | alice    | alice@lab32.com| ROLE_USER  | $2a$10$[def...uvw]  ← Different hash!
3  | bob      | bob@lab32.com  | ROLE_USER  | $2a$10$[ghi...rst]
```

### 5. BCrypt Demo Page (`/demo`) — Tasks 1 & 4
Encode "password123" → shows TWO DIFFERENT hashes each time:
```
Hash #1: $2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy
Hash #2: $2a$10$7lhRB.kf4xKGFh2Z8z5WT.jX8xH2xnGKLJI.zxVMuvRhGqOmNLe4C
```
(Same input, DIFFERENT output — because of random salt!)

Verify "password123" against Hash#1:
```
✅ PASSWORD MATCHES!
BCrypt.matches() returned true
Login would SUCCEED
```

---

## 📖 Complete Theory (WT Syllabus – Unit IV Spring Boot)

---

### 1. Spring Framework Overview

Spring is a Java application framework based on:
- **IoC (Inversion of Control)**: Objects don't create dependencies — Spring creates and injects them
- **DI (Dependency Injection)**: Dependencies passed via constructor/setter/field
- **AOP (Aspect-Oriented Programming)**: Cross-cutting concerns (security, logging) separated from business logic

```java
// Without IoC (you create dependencies):
public class UserService {
    private UserRepository repo = new UserRepository(); // tightly coupled
}

// With IoC (Spring injects dependencies):
public class UserService {
    private final UserRepository repo;  // Spring provides this
    
    @Autowired  // or constructor injection (preferred)
    public UserService(UserRepository repo) {
        this.repo = repo;
    }
}
```

### 2. Spring Boot Overview

Spring Boot = Spring + Auto-configuration + Embedded Server

**Key Features:**
1. **Auto-configuration**: Detects libraries on classpath, configures automatically
   - Sees H2 on classpath → auto-configures DataSource
   - Sees Spring Security → auto-configures security filters
2. **Embedded Server**: Tomcat bundled inside the JAR (no separate installation)
3. **Starter dependencies**: `spring-boot-starter-security` bundles everything for security
4. **No XML config**: Everything in Java annotations + application.properties
5. **@SpringBootApplication** = @Configuration + @ComponentScan + @EnableAutoConfiguration

### 3. Spring Security Architecture

```
HTTP Request → Filter Chain → Authentication → Authorization → Controller
                  ↑
         SecurityFilterChain (our SecurityConfig)
         
Login Flow:
1. POST /login (username + password)
2. UsernamePasswordAuthenticationFilter intercepts
3. Calls UserDetailsService.loadUserByUsername(username)
4. Returns UserDetails (username, encoded password, roles)
5. BCryptPasswordEncoder.matches(rawPassword, encodedPassword)
6. true  → creates SecurityContext → redirects to /dashboard
7. false → redirects to /login?error
```

### 4. BCrypt Password Hashing

**Why NOT use MD5 or SHA-256 for passwords?**
- MD5/SHA is FAST → attackers can try billions per second (brute force)
- No salt → same password always produces same hash (rainbow tables)
- BCrypt is designed to be SLOW and salted

**BCrypt Algorithm:**
```
BCrypt(password, salt, cost_factor) → 60-char hash
```

**BCrypt Hash Format:**
```
$2a$10$N9qo8uLOickgx2ZMRZoMye IjZAgcfl7p92ldGxad68LJZdL17lhWy
│   │  │──────────────────────│─────────────────────────────────│
│   │  └── 22-char base64 salt └── 31-char base64 hash
│   └── cost factor (2^10 = 1024 rounds)  
└── BCrypt version ($2a = current standard)
```

**Java code:**
```java
// Task 1: Configure
BCryptPasswordEncoder encoder = new BCryptPasswordEncoder(10);

// Task 2: Store encrypted password
String raw  = "password123";
String hash = encoder.encode(raw);
// hash = "$2a$10$..." (different every time due to random salt)
user.setPassword(hash);     // NEVER store 'raw'!
userRepo.save(user);        // BCrypt hash stored in DB

// Task 3 & 4: Authenticate (Spring Security does this internally)
boolean match = encoder.matches("password123", hash);  // true
boolean match2 = encoder.matches("wrongpass",  hash);  // false

// Same password, different hashes:
String h1 = encoder.encode("hello");  // "$2a$10$abc..."
String h2 = encoder.encode("hello");  // "$2a$10$xyz..." ← DIFFERENT!
// But:
encoder.matches("hello", h1) // true
encoder.matches("hello", h2) // true — both work!
```

### 5. JPA / Hibernate (Database ORM)

```java
// Entity → maps to DB table automatically
@Entity
@Table(name = "app_user")
public class AppUser {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;           // AUTO_INCREMENT column
    
    @Column(unique = true, nullable = false)
    private String username;   // VARCHAR(255) NOT NULL UNIQUE
    
    private String password;   // Stores BCrypt hash
}
```

```java
// Repository → auto-generates SQL from method names
interface AppUserRepository extends JpaRepository<AppUser, Long> {
    Optional<AppUser> findByUsername(String username);
    // Spring generates: SELECT * FROM app_user WHERE username = ?
    
    boolean existsByEmail(String email);
    // Spring generates: SELECT COUNT(*) > 0 FROM app_user WHERE email = ?
}
```

**JPA ddl-auto modes:**
| Mode | Behavior |
|------|----------|
| `create-drop` | Creates tables on startup, drops on shutdown |
| `create` | Creates tables on startup (keeps on shutdown) |
| `update` | Updates schema to match entities |
| `validate` | Only validates — throws error if schema doesn't match |
| `none` | Does nothing — use in production with migration tools |

### 6. Thymeleaf Templates

Thymeleaf is a server-side template engine — processes HTML on the server before sending to browser.

```html
<!-- th:text = set element text from model variable -->
<h2 th:text="${username}">Placeholder</h2>
<!-- Rendered: <h2>alice</h2> -->

<!-- th:each = loop (like PHP foreach) -->
<tr th:each="user : ${users}">
    <td th:text="${user.username}">-</td>
    <td th:text="${user.email}">-</td>
</tr>

<!-- th:if / th:unless = conditional rendering -->
<span th:if="${user.enabled}" class="badge bg-success">Active</span>
<span th:unless="${user.enabled}" class="badge bg-danger">Disabled</span>

<!-- th:href = link with URL expression -->
<a th:href="@{/users}">All Users</a>
<!-- @{/url} = Thymeleaf URL expression, adds context path -->

<!-- th:action = form action URL (also adds CSRF token!) -->
<form th:action="@{/login}" method="post">
<!-- Thymeleaf auto-adds: <input type="hidden" name="_csrf" value="..."> -->
```

### 7. MVC Pattern in Spring Boot

```
Browser → HTTP Request → DispatcherServlet → Controller → Service → Repository → DB
                                                                                    ↓
Browser ← HTTP Response ← View (Thymeleaf) ← Model (data) ←────────────────────────
```

```java
@Controller                     // Layer 3: Web (MVC Controller)
public class AuthController {
    
    @GetMapping("/dashboard")   // Maps GET /dashboard to this method
    public String dashboard(Model model, Authentication auth) {
        String username = auth.getName();     // Get logged-in user
        model.addAttribute("username", username); // Pass to template
        return "dashboard";                   // Render dashboard.html
    }
    
    @PostMapping("/register")   // Maps POST /register to this method
    public String register(@Valid @ModelAttribute RegisterRequest req,
                            BindingResult errors) {
        if (errors.hasErrors()) return "register"; // Show form again
        userService.registerUser(req);             // Layer 2: Service
        return "redirect:/login";                  // PRG pattern
    }
}
```

### 8. Maven Build Tool

```xml
<!-- pom.xml key sections: -->
<parent>
    <artifactId>spring-boot-starter-parent</artifactId>
    <!-- Inherits version management for all Spring libraries -->
</parent>

<dependencies>
    <dependency>
        <groupId>org.springframework.boot</groupId>
        <artifactId>spring-boot-starter-security</artifactId>
        <!-- No <version> needed — inherited from parent -->
    </dependency>
</dependencies>
```

**Common Maven commands:**
```bash
mvn clean              # Delete target/ folder (compiled files)
mvn compile            # Compile Java source files
mvn test               # Run unit tests
mvn package            # Create JAR file in target/
mvn install            # Install JAR to local Maven repository
mvn spring-boot:run    # Run Spring Boot application directly
mvn clean install -DskipTests  # Build without running tests (faster)
```

---

## ❓ Viva Questions + Answers

### Basic (Q1–Q10)

**Q1. What is Spring Boot? How is it different from Spring Framework?**

**Spring Framework** = A comprehensive Java application framework (IoC, DI, AOP, MVC, Security, Data). But it requires a lot of configuration (XML files, manual setup).

**Spring Boot** = Spring + Opinionated Defaults + Auto-configuration + Embedded Server.
- Reduces configuration: `@SpringBootApplication` replaces dozens of XML/Java config files
- Embedded Tomcat: no need to deploy WAR to external server
- Starter dependencies: `spring-boot-starter-security` bundles all needed security jars
- application.properties: single file for all config

**Analogy:** Spring = LEGO pieces. Spring Boot = pre-built LEGO set with most common structures assembled.

**Q2. What is BCrypt? Why is it preferred over MD5/SHA for passwords?**

BCrypt is a password hashing function designed by Niels Provos and David Mazières (1999) specifically for passwords.

**Why BCrypt beats MD5/SHA-256:**
| Feature | MD5/SHA-256 | BCrypt |
|---------|-------------|--------|
| Speed | Very fast (billions/sec) | Slow (by design) |
| Salt | Not built-in | Automatic random salt |
| Cost factor | Fixed | Adjustable (2^10 rounds) |
| Rainbow tables | Vulnerable | Immune (unique salt) |

BCrypt's slowness is intentional — attackers can only try ~100 hashes/second vs billions for MD5.

**Q3. Explain the 5 Tasks of Lab Q32.**

1. **Configure BCrypt** → `SecurityConfig.java`: `@Bean BCryptPasswordEncoder(10)`
2. **Store encrypted passwords** → `UserService.registerUser()`: `encoder.encode(plain)` → save hash
3. **Authenticate users** → `UserDetailsServiceImpl.loadUserByUsername()` + `DaoAuthenticationProvider`
4. **Verify password during login** → Spring Security automatically calls `encoder.matches(raw, hash)` during form login
5. **Display authentication results** → `dashboard.html` shows username, role, BCrypt hash, and success message

**Q4. What is `@SpringBootApplication`? What does it do?**

It's a combination of three annotations:
- `@Configuration` — This class can define Spring beans (`@Bean` methods)
- `@ComponentScan` — Scan this package and sub-packages for `@Component`, `@Service`, `@Repository`, `@Controller`
- `@EnableAutoConfiguration` — Spring Boot auto-detects libraries and configures them

When you run `SpringApplication.run(Lab32SecurityApplication.class, args)`:
1. Creates ApplicationContext (IoC container)
2. Runs auto-configuration
3. Starts embedded Tomcat on port 8080
4. Registers all beans found by ComponentScan
5. Runs `CommandLineRunner` beans (DataInitializer)

**Q5. What is `@Entity` and `@Table` in JPA?**

```java
@Entity          // This Java class maps to a database table
@Table(name = "app_user")  // Table name (default = class name in lowercase)
public class AppUser {
    @Id                      // Primary key
    @GeneratedValue(strategy = GenerationType.IDENTITY) // AUTO_INCREMENT
    private Long id;
    
    @Column(unique = true, nullable = false, length = 50)
    private String username; // → VARCHAR(50) NOT NULL UNIQUE column
}
```

Hibernate reads these annotations and:
1. Creates the table `app_user` with the defined columns
2. Manages INSERT/UPDATE/DELETE/SELECT operations

**Q6. What is `JpaRepository`? What methods does it provide?**

`JpaRepository<AppUser, Long>` is a Spring Data interface providing:
- `save(entity)` → INSERT or UPDATE
- `findById(id)` → SELECT WHERE id = ?
- `findAll()` → SELECT * FROM table
- `delete(entity)` → DELETE WHERE id = ?
- `count()` → SELECT COUNT(*)
- `existsById(id)` → SELECT EXISTS(...)

**Magic:** Spring Data auto-generates queries from method names:
```java
findByUsername(String u)   → SELECT * FROM app_user WHERE username = u
existsByEmail(String e)    → SELECT COUNT(*) > 0 WHERE email = e
```

No implementation code needed — Spring creates a proxy class automatically!

**Q7. What is `UserDetailsService` and why do we implement it?**

`UserDetailsService` is a Spring Security interface with ONE method:
```java
UserDetails loadUserByUsername(String username) throws UsernameNotFoundException;
```

Spring Security calls this during EVERY login attempt to:
1. Find the user in YOUR database (by username)
2. Return their details (username, password hash, roles)
3. Spring then compares the submitted password with the hash using BCrypt

We implement it because our users are in OUR database (H2), not in Spring's in-memory store. Without this, Spring would use a random generated password (printed in console).

**Q8. Explain the login flow step by step.**

```
1. User submits: POST /login  (username="alice", password="alice123")
2. UsernamePasswordAuthenticationFilter intercepts
3. Creates UsernamePasswordAuthenticationToken(username, password)
4. Delegates to DaoAuthenticationProvider
5. DaoAuthenticationProvider calls: loadUserByUsername("alice")
6. Our UserDetailsServiceImpl: SELECT * FROM app_user WHERE username = 'alice'
7. Returns UserDetails with: username="alice", password="$2a$10$...(BCrypt hash)"
8. DaoAuthenticationProvider calls: encoder.matches("alice123", "$2a$10$...")
9. BCrypt extracts salt from hash, rehashes "alice123", compares
10. true → authentication success → SecurityContextHolder stores auth
11. Redirect to /dashboard (configured: .defaultSuccessUrl("/dashboard"))
```

**Q9. What is `@Transactional` in Spring?**

```java
@Transactional
public AppUser registerUser(RegisterRequest request) {
    // Step 1: Check username
    // Step 2: Hash password
    // Step 3: Save user
    // If ANY step throws exception → ALL DB changes are rolled back
}
```

`@Transactional` wraps the method in a database transaction:
- Starts transaction at method entry
- Commits on successful return
- **Rolls back** on unchecked exception (RuntimeException)
- Ensures data consistency — either ALL operations succeed or NONE

**Q10. What is the PRG (Post/Redirect/Get) pattern?**

```java
@PostMapping("/register")
public String register(...) {
    userService.registerUser(request);
    // After POST, REDIRECT (don't return a view directly)
    redirectAttributes.addFlashAttribute("success", "Registered!");
    return "redirect:/login";  // → GET /login
}
```

**Why?** Without redirect: browser caches the POST. Pressing F5 shows "Confirm resubmission?" → resubmits form → duplicate user registered!

With PRG: Browser GET /login on F5 → safe, no resubmission.

---

### Intermediate (Q11–Q20)

**Q11. What is `@Valid` and `BindingResult`?**

```java
@PostMapping("/register")
public String register(
    @Valid @ModelAttribute RegisterRequest req,  // Triggers validation
    BindingResult result) {                       // Captures errors
    
    if (result.hasErrors()) {
        return "register";  // Show form with error messages
    }
}
```

`@Valid` triggers Bean Validation (JSR-303) annotations on the DTO:
```java
@NotBlank(message = "Username required")
@Size(min = 3, max = 50)
private String username;
```

`BindingResult` holds the validation result — must come IMMEDIATELY after `@Valid` parameter.

**Q12. What is `Optional<T>` and why do we use it?**

```java
Optional<AppUser> user = repo.findByUsername("alice");

// Without Optional: NullPointerException risk
AppUser u = repo.findByUsername("alice"); // null if not found
u.getEmail(); // NullPointerException!

// With Optional:
user.ifPresent(u -> log.info(u.getEmail()));  // Safe
user.orElseThrow(() -> new UsernameNotFoundException("Not found")); // Throw if absent
user.orElse(null);  // Return null if absent (safe null)
user.isPresent();   // Check if value exists
```

`Optional` makes the possibility of null values explicit in the type system.

**Q13. What is `@Bean` and how does Spring IoC work?**

```java
@Configuration
public class SecurityConfig {
    
    @Bean  // Register this object in the Spring IoC container
    public PasswordEncoder passwordEncoder() {
        return new BCryptPasswordEncoder(10);
    }
    
    // Other beans can now @Autowired PasswordEncoder:
    @Bean
    public DaoAuthenticationProvider authProvider() {
        DaoAuthenticationProvider p = new DaoAuthenticationProvider();
        p.setPasswordEncoder(passwordEncoder()); // Spring injects the bean
        return p;
    }
}
```

IoC Container = Spring manages object lifecycle. You declare WHAT you need; Spring creates and provides it.

**Q14. What is the difference between `@Controller` and `@RestController`?**

```java
@Controller  // Returns VIEW NAME → Thymeleaf renders HTML
public class AuthController {
    @GetMapping("/dashboard")
    public String dashboard(Model model) {
        return "dashboard";  // → templates/dashboard.html
    }
}

@RestController  // Returns data → converted to JSON automatically
public class ApiController {
    @GetMapping("/api/users")
    public List<AppUser> getUsers() {
        return userService.getAllUsers();
        // → [{"id":1,"username":"alice",...}]
    }
}
```

Use `@Controller` for web pages (HTML). Use `@RestController` for REST APIs (Postman testing).

**Q15. What is CSRF and how does Spring Security protect against it?**

**CSRF (Cross-Site Request Forgery):** A malicious website tricks a logged-in user's browser into making unintended requests to your site.

```
1. User is logged into bank.com (has session cookie)
2. User visits evil.com
3. evil.com's page: <form action="bank.com/transfer" method="post">
4. Form auto-submits → bank.com receives request WITH user's session cookie
5. Bank processes transfer (user didn't intend this!)
```

**Spring Security's CSRF protection:**
- Generates a unique token per session
- Includes it in every form (Thymeleaf auto-adds `<input name="_csrf" value="...">`)
- Validates token on every POST/PUT/DELETE
- If token missing/wrong → 403 Forbidden

We disabled CSRF only for `/h2-console` because H2 console doesn't support it.

**Q16. What is Thymeleaf and how is it different from JSP?**

Both are server-side template engines that generate HTML.

**Thymeleaf advantages over JSP:**
- Valid HTML (can open in browser without server)
- Natural templating: `<p th:text="${name}">Default text</p>` — browser shows "Default text", server shows actual value
- Better Spring Security integration
- Spring Boot's default choice

```html
<!-- Thymeleaf -->
<p th:text="${username}">Placeholder</p>  <!-- Server replaces with actual value -->
<tr th:each="user : ${users}">           <!-- Loop -->
<a th:href="@{/dashboard}">Go</a>        <!-- URL expression -->
```

**Q17. What are Lombok annotations? Why use them?**

Lombok generates boilerplate Java code at compile time:

```java
// Without Lombok (you write all this manually):
public class AppUser {
    private Long id;
    private String username;
    
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }
    public String getUsername() { return username; }
    public void setUsername(String u) { this.username = u; }
    public AppUser() {}
    public AppUser(Long id, String username) { ... }
    @Override public String toString() { ... }
}

// With Lombok (annotations do all of the above!):
@Getter @Setter @NoArgsConstructor @AllArgsConstructor
public class AppUser {
    private Long id;
    private String username;
}
```

Common Lombok annotations:
- `@Getter` → generate all getters
- `@Setter` → generate all setters
- `@Data` → @Getter + @Setter + @ToString + @EqualsAndHashCode
- `@Builder` → builder pattern: `AppUser.builder().username("alice").build()`
- `@Slf4j` → `private static final Logger log = LoggerFactory.getLogger(ThisClass.class)`

**Q18. What is `CommandLineRunner` used for in DataInitializer?**

```java
@Bean
public CommandLineRunner initData(AppUserRepository repo, PasswordEncoder enc) {
    return args -> {
        // This code runs ONCE after Spring Boot fully starts
        // Perfect for seeding initial data
        repo.save(AppUser.builder()
            .username("admin")
            .password(enc.encode("admin123"))  // Hash before storing!
            .build());
    };
}
```

`CommandLineRunner` is a functional interface with `run(String... args)`. Spring Boot runs all `CommandLineRunner` beans after the application context is ready. Great for: DB seeding, startup validation, printing startup info.

**Q19. What is `SecurityContextHolder` and how do you get the logged-in user?**

```java
// In a Controller with Spring MVC:
@GetMapping("/dashboard")
public String dashboard(Authentication auth) {
    // Spring injects the Authentication object automatically
    String username = auth.getName();
    Collection<? extends GrantedAuthority> roles = auth.getAuthorities();
}

// Anywhere in the app (less preferred):
Authentication auth = SecurityContextHolder.getContext().getAuthentication();
String username = auth.getName();
boolean isAdmin = auth.getAuthorities().stream()
    .anyMatch(a -> a.getAuthority().equals("ROLE_ADMIN"));
```

`SecurityContextHolder` stores the security context for the current thread. After successful login, Spring stores the `Authentication` object here.

**Q20. What is H2 and why use it for this lab?**

H2 is a Java SQL database that runs INSIDE the JVM:
- **In-memory mode** (`jdbc:h2:mem:db`): Data stored in RAM, lost on restart
- **File mode** (`jdbc:h2:file:./data/db`): Persisted to disk
- **No installation**: Just add dependency in pom.xml
- **H2 Console**: Web UI at `/h2-console` (like phpMyAdmin for H2)

**For production**, use MySQL or PostgreSQL:
```properties
# Switch to MySQL:
spring.datasource.url=jdbc:mysql://localhost:3306/mydb
spring.datasource.username=root
spring.datasource.password=secret
spring.datasource.driver-class-name=com.mysql.cj.jdbc.Driver
spring.jpa.hibernate.ddl-auto=update  # Don't drop on shutdown!
```

---

### Advanced (Q21–Q28)

**Q21. What is the Spring Security Filter Chain?**

Every HTTP request passes through a chain of filters before reaching your controller:

```
Request → 
  SecurityContextPersistenceFilter (load session)
  → UsernamePasswordAuthenticationFilter (check /login POST)
  → BasicAuthenticationFilter (check Authorization header)
  → ExceptionTranslationFilter (handle auth exceptions)
  → FilterSecurityInterceptor (check access rules)
  → Your @Controller
```

We configure this chain in `SecurityConfig.filterChain(HttpSecurity http)`.

**Q22. What is the difference between authentication and authorization?**

| | Authentication | Authorization |
|---|---|---|
| **Question** | Who are you? | What can you do? |
| **How** | Username + password | Roles and permissions |
| **Spring** | `UsernamePasswordAuthenticationFilter` | `FilterSecurityInterceptor` |
| **Lab Q32** | BCrypt password verification | `hasRole('ADMIN')` |

```java
// Authentication: verify identity
.formLogin(form -> form.loginPage("/login").defaultSuccessUrl("/dashboard"))

// Authorization: restrict access based on role
.authorizeHttpRequests(auth -> auth
    .requestMatchers("/admin/**").hasRole("ADMIN")  // Only admins
    .requestMatchers("/dashboard").authenticated()   // Any logged-in user
    .requestMatchers("/login").permitAll()           // Anyone
)
```

**Q23. How would you add role-based access control?**

```java
// In SecurityConfig:
.authorizeHttpRequests(auth -> auth
    .requestMatchers("/admin/**").hasRole("ADMIN")   // Only ROLE_ADMIN
    .requestMatchers("/api/**").hasAnyRole("USER","ADMIN")
    .anyRequest().authenticated()
)

// In Thymeleaf templates:
<div sec:authorize="hasRole('ADMIN')">
    <!-- Only visible to admins -->
    <a href="/admin">Admin Panel</a>
</div>

// In Java methods:
@PreAuthorize("hasRole('ADMIN')")  // Method-level security
public void deleteUser(Long id) { ... }
```

**Q24. What is JWT and how does it compare to session-based auth (used in this lab)?**

| | Session-based (This Lab) | JWT (JSON Web Token) |
|---|---|---|
| State | Stored on server (session) | Stateless (client stores token) |
| Storage | HttpSession (RAM/file) | Client (localStorage/cookie) |
| Scalability | Hard (sticky sessions needed) | Easy (any server can verify) |
| Use case | Web apps with Thymeleaf | REST APIs, mobile apps |
| Security | CSRF needed | XSS risk with localStorage |

**Q25. What is `@Transactional` and what happens if it fails?**

```java
@Transactional
public void registerUser(RegisterRequest req) {
    // All DB operations in ONE transaction:
    AppUser user = new AppUser(req.getUsername(), encoder.encode(req.getPassword()));
    userRepo.save(user);
    
    Profile profile = new Profile(user, req.getEmail());
    profileRepo.save(profile);
    
    emailService.sendWelcome(req.getEmail()); // If this throws exception...
    // ...BOTH save operations are ROLLED BACK
    // DB is left in clean state
}
```

Without `@Transactional`: user saved but profile not → inconsistent data.
With `@Transactional`: either BOTH saved or NEITHER → consistent data.

**Q26. How would you use Postman to test this Spring Boot app?**

```
# Test Registration (POST):
POST http://localhost:8080/register
Content-Type: application/x-www-form-urlencoded

username=testuser&email=test@test.com&password=test123&confirmPassword=test123
(Note: CSRF will block this from Postman — need to disable CSRF or get token first)

# Test Login (POST):
POST http://localhost:8080/login
Content-Type: application/x-www-form-urlencoded

username=alice&password=alice123

# Better: Create a REST API endpoint for Postman:
@RestController @RequestMapping("/api")
public class ApiController {
    @PostMapping("/register")
    public ResponseEntity<?> register(@RequestBody RegisterRequest req) {
        AppUser user = userService.registerUser(req);
        return ResponseEntity.ok(Map.of("id", user.getId(), "username", user.getUsername()));
    }
}
```

**Q27. What is Spring Boot Actuator and why is it useful?**

Spring Boot Actuator adds production-ready monitoring endpoints:
```xml
<dependency>
    <groupId>org.springframework.boot</groupId>
    <artifactId>spring-boot-starter-actuator</artifactId>
</dependency>
```

Endpoints:
- `/actuator/health` → Is the app running? Is DB connected?
- `/actuator/metrics` → JVM memory, HTTP request counts
- `/actuator/beans` → All registered Spring beans
- `/actuator/env` → Environment properties

**Q28. What is BCrypt's work factor and how does it affect security?**

```java
new BCryptPasswordEncoder(10)  // Work factor = 10 (default)
new BCryptPasswordEncoder(12)  // Stronger but 4x slower
new BCryptPasswordEncoder(15)  // Very strong (might be too slow for login)
```

**Work factor N**: BCrypt runs 2^N iterations.
- N=10: ~100ms per hash (comfortable for users, ~10 attacks/second for attacker)
- N=12: ~400ms per hash
- N=15: ~3 seconds per hash

**Rule of thumb**: Choose the highest N where login stays under 200ms. As hardware gets faster, increase N.

**Why slow is good**: An attacker with a GPU can try 10 billion MD5/second. With BCrypt strength 10, they can only try 10,000/second — making brute force practically impossible.

---

## 🔑 Quick Reference

| Annotation | Location | Purpose |
|---|---|---|
| `@SpringBootApplication` | Main class | Bootstrap everything |
| `@Entity` | AppUser | Maps class to DB table |
| `@Id @GeneratedValue` | id field | PRIMARY KEY AUTO_INCREMENT |
| `@Column(unique=true)` | username/email | UNIQUE constraint |
| `@Repository` | AppUserRepository | Spring Data JPA |
| `@Service` | UserService | Business logic bean |
| `@Controller` | AuthController | MVC web controller |
| `@GetMapping` | Controller methods | Handle HTTP GET |
| `@PostMapping` | Controller methods | Handle HTTP POST |
| `@Bean` | SecurityConfig | Register Spring bean |
| `@Configuration` | SecurityConfig | Contains @Bean methods |
| `@EnableWebSecurity` | SecurityConfig | Enable Spring Security |
| `@Valid` | Controller params | Trigger validation |
| `@Transactional` | Service methods | Database transaction |
| `@Slf4j` | Any class | Logger injection (Lombok) |

| Task | Key Code |
|---|---|
| Task 1: Configure BCrypt | `@Bean BCryptPasswordEncoder(10)` in SecurityConfig |
| Task 2: Store encrypted | `encoder.encode(raw)` in UserService.registerUser() |
| Task 3: Authenticate | `loadUserByUsername()` in UserDetailsServiceImpl |
| Task 4: Verify password | `encoder.matches(raw, hash)` — Spring calls this internally |
| Task 5: Display results | Dashboard shows username, role, and BCrypt hash from DB |
