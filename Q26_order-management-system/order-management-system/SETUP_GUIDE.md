# Step 1: Build
mvn clean install -DskipTests

# Step 2: Run
mvn spring-boot:run


mvn spring-boot:run
http://localhost:8080/api/orders



# 🚀 Order Management System - Complete Setup Guide

## ✅ Your Project is Ready!

The project has been reviewed and is **error-free**. Follow these steps to run it.

---

## 📋 Prerequisites

### **1. Java JDK 17 or Higher**

Check if installed:
```powershell
java -version
```

Should show: `openjdk version "21.0.x"` or `"17.0.x"`

---

### **2. Maven**

Check if installed:
```powershell
mvn -version
```

Should show: `Apache Maven 3.9.15`

✅ **You already have both installed!**

---

## 🎯 Step-by-Step: How to Run

### **Step 1: Open Project in VS Code**

1. Extract the `order-management-system` folder
2. Open VS Code
3. **File → Open Folder** → Select `order-management-system`

---

### **Step 2: Open Terminal in VS Code**

Press **Ctrl + `** (backtick) to open integrated terminal

Or: **Terminal → New Terminal**

---

### **Step 3: Download Dependencies (First Time Only)**

```powershell
mvn clean install -DskipTests
```

**What this does:**
- Downloads all Spring Boot dependencies (takes 2-5 minutes first time)
- Compiles the project
- Creates JAR file in `target/` folder

**Expected output:**
```
[INFO] BUILD SUCCESS
[INFO] Total time: 02:15 min
```

---

### **Step 4: Run the Application**

```powershell
mvn spring-boot:run
```

**Wait for this message:**
```
Tomcat started on port(s): 8080 (http)
Started OrderManagementSystemApplication in X.XXX seconds
```

✅ **Application is running!**

---

## 🌐 Access the Application

### **Base URL:**
```
http://localhost:8080
```

### **H2 Database Console:**
```
http://localhost:8080/h2-console
```

**Login credentials:**
- JDBC URL: `jdbc:h2:mem:orderdb`
- Username: `sa`
- Password: *(leave blank)*

---

## 📬 Testing with Postman

### **Import Postman Collection (Recommended)**

The project includes a ready-made Postman collection!

1. Open Postman
2. Click **Import**
3. Select file: `Order_Management_Postman_Collection.json`
4. All endpoints are pre-configured! ✨

---

### **Manual Postman Testing**

#### **1. CREATE Order (POST)**

**URL:** `POST http://localhost:8080/api/orders`

**Headers:**
```
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
  "customerName": "John Doe",
  "customerEmail": "john.doe@example.com",
  "productName": "Laptop",
  "quantity": 2,
  "price": 50000.00
}
```

**Expected Response (201 Created):**
```json
{
  "id": 1,
  "customerName": "John Doe",
  "customerEmail": "john.doe@example.com",
  "productName": "Laptop",
  "quantity": 2,
  "price": 50000.00,
  "totalAmount": 100000.00,
  "orderStatus": "PENDING",
  "createdAt": "2026-05-02T16:30:45.123",
  "updatedAt": "2026-05-02T16:30:45.123"
}
```

---

#### **2. GET All Orders**

**URL:** `GET http://localhost:8080/api/orders`

**Expected Response (200 OK):**
```json
[
  {
    "id": 1,
    "customerName": "John Doe",
    "customerEmail": "john.doe@example.com",
    "productName": "Laptop",
    "quantity": 2,
    "price": 50000.00,
    "totalAmount": 100000.00,
    "orderStatus": "PENDING"
  }
]
```

---

#### **3. GET Order by ID**

**URL:** `GET http://localhost:8080/api/orders/1`

**Expected Response (200 OK):**
Returns single order with ID 1

---

#### **4. UPDATE Order (PUT)**

**URL:** `PUT http://localhost:8080/api/orders/1`

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
  "customerName": "John Doe Updated",
  "customerEmail": "john.updated@example.com",
  "productName": "Gaming Laptop",
  "quantity": 3,
  "price": 75000.00
}
```

**Expected Response (200 OK):**
Returns updated order with new totalAmount (225000.00)

---

#### **5. UPDATE Order Status (PATCH)**

**URL:** `PATCH http://localhost:8080/api/orders/1/status?status=CONFIRMED`

**Expected Response (200 OK):**
Order status changed to CONFIRMED

---

#### **6. DELETE Order**

**URL:** `DELETE http://localhost:8080/api/orders/1`

**Expected Response (200 OK):**
```json
{
  "message": "Order deleted successfully",
  "orderId": 1
}
```

---

## 🎯 Complete Testing Sequence

Run these in order to demonstrate CRUD operations:

```
1. POST /api/orders          → Create Order 1
2. POST /api/orders          → Create Order 2
3. POST /api/orders          → Create Order 3
4. GET /api/orders           → Shows all 3 orders
5. GET /api/orders/2         → Shows Order 2 details
6. PUT /api/orders/2         → Update Order 2
7. PATCH /api/orders/2/status?status=SHIPPED → Change status
8. GET /api/orders/2         → Verify update
9. DELETE /api/orders/1      → Delete Order 1
10. GET /api/orders          → Shows only Orders 2 and 3
```

---

## 📊 Sample Test Data

### **Order 1: Electronics**
```json
{
  "customerName": "Alice Smith",
  "customerEmail": "alice@example.com",
  "productName": "MacBook Pro M3",
  "quantity": 1,
  "price": 180000.00
}
```

### **Order 2: Furniture**
```json
{
  "customerName": "Bob Johnson",
  "customerEmail": "bob@example.com",
  "productName": "Office Chair",
  "quantity": 4,
  "price": 8500.00
}
```

### **Order 3: Books**
```json
{
  "customerName": "Charlie Brown",
  "customerEmail": "charlie@example.com",
  "productName": "Java Programming Books",
  "quantity": 5,
  "price": 1200.00
}
```

---

## 🛑 Stop the Application

In the terminal where the app is running:

Press **Ctrl + C**

---

## 🐛 Troubleshooting

### **Error: Port 8080 already in use**

**Solution:** Stop other applications using port 8080 or change port:

Edit `src/main/resources/application.properties`:
```properties
server.port=8081
```

Then access: `http://localhost:8081`

---

### **Error: Dependencies not downloading**

**Solution:**
```powershell
# Clear Maven cache
rmdir /s /q "%USERPROFILE%\.m2\repository"

# Re-download
mvn clean install -DskipTests -U
```

---

### **Error: Java version mismatch**

**Solution:** Make sure JAVA_HOME points to JDK 17 or higher

```powershell
echo $env:JAVA_HOME
```

Should show: `C:\Program Files\Java\jdk-21`

---

## 📁 Project Structure

```
order-management-system/
├── src/
│   ├── main/
│   │   ├── java/com/ordermanagement/
│   │   │   ├── OrderManagementSystemApplication.java  ← Main class
│   │   │   ├── entity/
│   │   │   │   └── Order.java                         ← Order entity
│   │   │   ├── repository/
│   │   │   │   └── OrderRepository.java               ← Data access
│   │   │   └── controller/
│   │   │       └── OrderController.java               ← REST endpoints
│   │   └── resources/
│   │       └── application.properties                 ← Configuration
│   └── test/                                          ← Test files
├── pom.xml                                            ← Maven config
├── Order_Management_Postman_Collection.json           ← Postman tests
├── README.md                                          ← Documentation
└── WT_THEORY_COMPLETE.md                              ← Theory notes
```

---

## 🎓 For Practical Exam

### **What to Show:**

1. **Project Structure** (show in VS Code)
2. **Code Walkthrough:**
   - Order.java (Entity annotations)
   - OrderController.java (REST endpoints)
   - OrderRepository.java (JPA repository)
3. **Run the application** (terminal output)
4. **Postman Testing:**
   - Create orders (POST)
   - View all orders (GET)
   - View single order (GET by ID)
   - Update order (PUT)
   - Change status (PATCH)
   - Delete order (DELETE)
5. **H2 Console** (show database table)

### **Screenshots to Take:**

1. VS Code with project open
2. Terminal showing "Tomcat started on port 8080"
3. Postman POST request (201 Created)
4. Postman GET request (all orders)
5. H2 Console with data
6. Each CRUD operation result

---

## ✅ Summary

**Your project is complete and working!** The only fix needed was adding the `customerEmail` field in Postman requests.

**Quick Start:**
```powershell
cd order-management-system
mvn clean install -DskipTests
mvn spring-boot:run
```

Then open Postman and import `Order_Management_Postman_Collection.json`

🚀 **You're ready to demonstrate your Order Management System!**
