

# ⚡ ELECTRICITY BILL CALCULATOR - LAB 6
## Complete Lab Package with Theory, Code, Commands & Viva Preparation

---

## 📋 PROBLEM STATEMENT

**Task:** Create an electricity bill calculator using PHP that calculates bills based on tiered pricing system.

**Requirements:**
- Accept units consumed as input
- Calculate bill using tiered/slab rates
- Display detailed breakdown by slab
- Show total bill amount
- Validate user input
- Handle edge cases (negative, zero, non-numeric)

**Tiered Pricing Structure:**
- 0-100 units: ₹5 per unit
- 101-200 units: ₹7 per unit
- 201-300 units: ₹10 per unit
- Above 300 units: ₹15 per unit

---

## 📖 COMPLETE THEORY - VIVA PREPARATION

### 1. What is Tiered/Slab Pricing?

**Simple Explanation:**
Tiered pricing (also called slab pricing) is a billing system where the rate per unit changes based on consumption levels. Like tax brackets - you don't pay the highest rate on everything, only on the portion that falls in that tier.

**Technical Definition:**
A progressive pricing model where different rates apply to different consumption ranges, with each unit priced according to its tier. The total cost is the sum of costs across all applicable tiers.

**Real-World Examples:**
- **Electricity:** Higher usage = higher rate per unit (this lab)
- **Income Tax:** Higher income = higher tax percentage
- **Water Bills:** Excessive usage penalized with higher rates
- **Mobile Data:** First 2GB cheap, additional data expensive
- **Cloud Storage:** First 5GB free, next tiers progressively expensive

**Why Use Tiered Pricing?**
1. **Encourages Conservation:** Higher rates discourage excessive consumption
2. **Revenue Optimization:** Heavy users pay more per unit
3. **Fairness:** Basic needs charged lower, luxury usage charged higher
4. **Social Equity:** Makes essential services affordable for low consumers

### 2. How Tiered Pricing Works in This Lab

**Example Calculation (150 units):**

```
Units consumed: 150

Slab 1 (0-100):   100 units × ₹5  = ₹500
Slab 2 (101-200):  50 units × ₹7  = ₹350
-------------------------------------------------
Total Bill:                        ₹850
```

**Step-by-step Logic:**
1. Check which slabs the consumption falls into
2. Calculate cost for each slab separately
3. First 100 units always at ₹5 (if consumption > 100)
4. Next 100 units (101-200) always at ₹7 (if consumption > 200)
5. Next 100 units (201-300) at ₹10 (if consumption > 300)
6. Remaining units above 300 at ₹15
7. Sum all slab amounts for total bill

**Why NOT Simple Multiplication?**
❌ WRONG: 150 units × ₹7 = ₹1,050
✓ CORRECT: (100 × ₹5) + (50 × ₹7) = ₹850

The rate applies only to units in that tier, not all units!

### 3. PHP Concepts Used

**A. Form Handling**
- `$_SERVER["REQUEST_METHOD"]` - Detects if form submitted
- `$_POST` superglobal - Retrieves form data
- POST vs GET - POST for data submission (not visible in URL)

**B. Input Validation**
```php
empty($units)        // Checks if empty
is_numeric($units)   // Checks if number
$units < 0          // Checks if negative
```

**Why Validate?**
- Security: Prevent malicious input
- Data Integrity: Ensure calculations correct
- User Experience: Show helpful error messages

**C. Conditional Logic (if-elseif-else)**
```php
if ($units <= 100) {
    // Single slab
} elseif ($units <= 200) {
    // Two slabs
} elseif ($units <= 300) {
    // Three slabs
} else {
    // All four slabs
}
```

**D. Arrays for Data Storage**
```php
$breakdown[] = [
    'slab' => '0-100 units',
    'units' => 100,
    'rate' => 5,
    'amount' => 500
];
```
- Associative arrays: Key-value pairs
- Multidimensional: Array of arrays
- Used for structured data storage

**E. Loops (foreach)**
```php
foreach ($breakdown as $item) {
    echo $item['slab'];
    echo $item['amount'];
}
```
Iterates through each slab in breakdown

**F. Number Formatting**
```php
number_format($totalBill, 2)
// 1234.5 becomes "1,234.50"
// Makes numbers readable
```

**G. Security Functions**
```php
htmlspecialchars($input)
// Converts < > & " ' to HTML entities
// Prevents XSS (Cross-Site Scripting) attacks
```

### 4. Algorithm Explanation

**Progressive Calculation Algorithm:**

```
INPUT: units consumed
OUTPUT: total bill, breakdown by slab

IF units <= 100:
    bill = units × 5
    breakdown = [one slab]
    
ELSE IF units <= 200:
    slab1 = 100 × 5
    slab2 = (units - 100) × 7
    bill = slab1 + slab2
    breakdown = [two slabs]
    
ELSE IF units <= 300:
    slab1 = 100 × 5
    slab2 = 100 × 7
    slab3 = (units - 200) × 10
    bill = slab1 + slab2 + slab3
    breakdown = [three slabs]
    
ELSE (units > 300):
    slab1 = 100 × 5
    slab2 = 100 × 7
    slab3 = 100 × 10
    slab4 = (units - 300) × 15
    bill = slab1 + slab2 + slab3 + slab4
    breakdown = [four slabs]
    
RETURN bill, breakdown
```

**Time Complexity:** O(1) - Constant time (always 4 comparisons max)
**Space Complexity:** O(n) where n = number of slabs (max 4)

### 5. HTML/CSS Concepts

**Responsive Design:**
- Viewport meta tag for mobile
- Flexbox for centering
- Max-width for content constraint
- Percentage widths for flexibility

**Form Elements:**
- `<input type="number">` - Numeric input only
- `step="0.01"` - Allows decimals
- `required` - HTML5 validation
- `placeholder` - Hint text

**CSS Techniques:**
- Linear gradients for backgrounds
- Box shadows for depth
- Transitions for smooth animations
- Hover effects for interactivity
- Border-radius for rounded corners

---

## 📁 FILE STRUCTURE

```
lab6_electricity_bill/
│
├── index.php              ← Main calculator (HTML + PHP)
├── README.md              ← This file (theory, commands, viva)
└── screenshot.png         ← (Optional) Expected output image
```

**Single File Application:**
- All code in one file (index.php)
- Combines PHP logic and HTML presentation
- No database needed (stateless application)
- No external dependencies

**Why Single File?**
- Simple to deploy (just copy one file)
- Easy to understand (everything in one place)
- No complex file organization needed
- Perfect for lab demonstrations

---

## ⚙️ SETUP & RUN COMMANDS

### Prerequisites

**Required Software:**
- XAMPP (includes Apache and PHP)
- Web browser (Chrome, Firefox, Edge)
- Text editor (VS Code recommended)

### Installation Steps

**Step 1: Install XAMPP**
```bash
# Download from: https://www.apachefriends.org/download.html
# Install to default location: C:\xampp (Windows) or /opt/lampp (Linux)
```

**Step 2: Extract This Lab**
```bash
# Extract lab6_electricity_bill.zip to:
C:\xampp\htdocs\lab6_electricity_bill


```

**Step 3: Start Apache Server**
```bash
# Windows: Open XAMPP Control Panel
xampp-control.exe
# Click "Start" next to Apache

# Linux:
sudo /opt/lampp/lampp start

# Mac:
sudo /Applications/XAMPP/xamppfiles/xampp start
```

**Step 4: Access Application**
```bash
# Open web browser and navigate to:
http://localhost/lab6_electricity_bill/index.php

# Or simply:
http://localhost/lab6_electricity_bill
# (index.php loads automatically)
```

### Alternative: Using Built-in PHP Server

**If you have PHP installed separately:**
```bash
# Navigate to project folder
cd lab6_electricity_bill

# Start PHP built-in server
php -S localhost:8000

# Access at:
http://localhost:8000
```

### Stopping the Server

**XAMPP:**
```bash
# Click "Stop" next to Apache in XAMPP Control Panel
```

**Built-in PHP server:**
```bash
# Press Ctrl+C in terminal
```

### Troubleshooting

**Problem: "Object not found" or 404 error**
```bash
# Solution: Check file path
# Ensure index.php is in: C:\xampp\htdocs\lab6_electricity_bill\
# Access with: http://localhost/lab6_electricity_bill/index.php
```

**Problem: Apache won't start (Port 80 in use)**
```bash
# Solution: Check if Skype or other app using port 80
# Close conflicting application or change Apache port:
# Edit: C:\xampp\apache\conf\httpd.conf
# Change: Listen 80 → Listen 8080
# Restart Apache
# Access: http://localhost:8080/lab6_electricity_bill/
```

**Problem: Blank white page**
```bash
# Solution: Check PHP errors
# Enable error reporting in PHP:
# Edit: C:\xampp\php\php.ini
# Set: display_errors = On
# Restart Apache
```

---

## 🖥️ EXPECTED OUTPUT - EXAMINER DEMONSTRATION

### Initial Page Load

**What Examiner Sees:**
- ✓ Page title: "⚡ Electricity Bill Calculator"
- ✓ Subtitle: "Lab 6: PHP Tiered Pricing System"
- ✓ Pricing information table with 4 slabs clearly displayed
- ✓ Input field labeled "Enter Units Consumed:"
- ✓ Placeholder text: "e.g., 150"
- ✓ Blue "💰 Calculate Bill" button
- ✓ Purple gradient background
- ✓ Clean, professional interface
- ✓ No errors or warnings

### Test Case 1: Valid Input (50 units)

**Action:** Enter 50 units, click Calculate

**Expected Result:**
```
Total Bill Amount: ₹250.00
for 50.00 units

Bill Breakdown:
Slab              Units Used    Rate/Unit    Amount
0-100 units       50.00         ₹5.00        ₹250.00
---------------------------------------------------------
TOTAL:                                       ₹250.00
```

**Explanation:**
- All 50 units in Slab 1 (0-100)
- Rate: ₹5 per unit
- Calculation: 50 × 5 = ₹250

### Test Case 2: Valid Input (150 units)

**Action:** Enter 150 units, click Calculate

**Expected Result:**
```
Total Bill Amount: ₹850.00
for 150.00 units

Bill Breakdown:
Slab              Units Used    Rate/Unit    Amount
0-100 units       100.00        ₹5.00        ₹500.00
101-200 units     50.00         ₹7.00        ₹350.00
---------------------------------------------------------
TOTAL:                                       ₹850.00
```

**Explanation:**
- First 100 units: 100 × ₹5 = ₹500
- Next 50 units: 50 × ₹7 = ₹350
- Total: ₹850

### Test Case 3: Valid Input (250 units)

**Action:** Enter 250 units, click Calculate

**Expected Result:**
```
Total Bill Amount: ₹1,700.00
for 250.00 units

Bill Breakdown:
Slab              Units Used    Rate/Unit    Amount
0-100 units       100.00        ₹5.00        ₹500.00
101-200 units     100.00        ₹7.00        ₹700.00
201-300 units     50.00         ₹10.00       ₹500.00
---------------------------------------------------------
TOTAL:                                       ₹1,700.00
```

**Explanation:**
- First 100 units: 100 × ₹5 = ₹500
- Next 100 units: 100 × ₹7 = ₹700
- Next 50 units: 50 × ₹10 = ₹500
- Total: ₹1,700

### Test Case 4: Valid Input (350 units)

**Action:** Enter 350 units, click Calculate

**Expected Result:**
```
Total Bill Amount: ₹2,950.00
for 350.00 units

Bill Breakdown:
Slab              Units Used    Rate/Unit    Amount
0-100 units       100.00        ₹5.00        ₹500.00
101-200 units     100.00        ₹7.00        ₹700.00
201-300 units     100.00        ₹10.00       ₹1,000.00
Above 300 units   50.00         ₹15.00       ₹750.00
---------------------------------------------------------
TOTAL:                                       ₹2,950.00
```

**Explanation:**
- First 100 units: 100 × ₹5 = ₹500
- Next 100 units: 100 × ₹7 = ₹700
- Next 100 units: 100 × ₹10 = ₹1,000
- Remaining 50 units: 50 × ₹15 = ₹750
- Total: ₹2,950

### Test Case 5: Edge Case - Exactly 100 units

**Action:** Enter 100 units

**Expected Result:**
```
Total Bill Amount: ₹500.00
for 100.00 units

Bill Breakdown:
Slab              Units Used    Rate/Unit    Amount
0-100 units       100.00        ₹5.00        ₹500.00
---------------------------------------------------------
TOTAL:                                       ₹500.00
```

### Test Case 6: Decimal Input (125.50 units)

**Action:** Enter 125.50 units

**Expected Result:**
```
Total Bill Amount: ₹678.50
for 125.50 units

Bill Breakdown:
Slab              Units Used    Rate/Unit    Amount
0-100 units       100.00        ₹5.00        ₹500.00
101-200 units     25.50         ₹7.00        ₹178.50
---------------------------------------------------------
TOTAL:                                       ₹678.50
```

### Test Case 7: Validation - Empty Input

**Action:** Leave field empty, click Calculate

**Expected Result:**
- Red error box appears at top
- Error message: "⚠️ Please enter the number of units consumed."
- Input field has red border
- No calculation performed

### Test Case 8: Validation - Non-numeric Input

**Action:** Enter "abc" or "12.5.6", click Calculate

**Expected Result:**
- Red error box: "⚠️ Please enter a valid number."
- Input field highlighted
- Previous invalid input cleared

### Test Case 9: Validation - Negative Input

**Action:** Enter -50, click Calculate

**Expected Result:**
- Red error box: "⚠️ Units cannot be negative."
- Input field highlighted

### Test Case 10: Zero Input

**Action:** Enter 0, click Calculate

**Expected Result:**
- No breakdown shown (since bill is ₹0)
- Or handled as valid with ₹0 bill

### Visual Elements to Show Examiner

**Design Features:**
1. ✓ Responsive layout (works on mobile and desktop)
2. ✓ Purple gradient background (professional look)
3. ✓ White content box with shadow (depth effect)
4. ✓ Blue pricing information box
5. ✓ Pricing table with clear headers
6. ✓ Smooth hover effects on button
7. ✓ Input field focus effects (purple glow)
8. ✓ Formatted currency (₹1,234.50 with commas)
9. ✓ Color-coded sections (errors in red, info in blue)
10. ✓ Breakdown table with alternating row colors

---

## ❓ VIVA QUESTIONS & ANSWERS

### Q1: Explain how tiered pricing works in this calculator.

**ANSWER:**
Tiered pricing means different rates apply to different consumption ranges. The calculation happens progressively through slabs.

**Example with 150 units:**
- First 100 units are always charged at ₹5 (base rate): 100 × ₹5 = ₹500
- Remaining 50 units (101-150) charged at ₹7: 50 × ₹7 = ₹350
- Total: ₹500 + ₹350 = ₹850

**Key Points:**
- It's NOT 150 × ₹7 = ₹1,050 (common mistake!)
- Each tier only applies to units in that range
- Lower tiers always fill first
- Progressive calculation (like income tax)

**Algorithm:**
1. Determine which slabs consumption falls into
2. Calculate each slab separately
3. First 100 always at ₹5 (if consumption > 100)
4. Next 100 at ₹7 (if consumption > 200)
5. Next 100 at ₹10 (if consumption > 300)
6. Remainder at ₹15
7. Sum all slabs

**Real-world benefit:**
Encourages conservation - excessive usage penalized with higher rates per unit.

---

### Q2: Why do we validate user input? What validations are performed?

**ANSWER:**
Input validation is critical for three reasons:

**1. Security:**
- Prevents malicious input (SQL injection, XSS attacks)
- Even though no database here, good practice
- Example: User enters `<script>alert('hack')</script>`
- Without validation: Could execute JavaScript
- With htmlspecialchars(): Displays as text, doesn't execute

**2. Data Integrity:**
- Ensures calculations are correct
- Prevents garbage data
- Example: User enters "abc" as units
- Without validation: PHP error or wrong calculation
- With validation: Friendly error message

**3. User Experience:**
- Provides helpful feedback
- Prevents confusion
- Shows what's wrong and how to fix it

**Validations Performed:**

```php
// 1. Empty Check
if (empty($units)) {
    // Checks if nothing entered
    // Returns TRUE for: "", 0, "0", NULL, FALSE
}

// 2. Numeric Check
if (!is_numeric($units)) {
    // Ensures input is number
    // Rejects: "abc", "12.5.6", "12a"
    // Accepts: "123", 123, "12.5", 12.5
}

// 3. Negative Check
if ($units < 0) {
    // Negative units don't make sense
    // Real-world: Can't consume -50 units
}
```

**Validation Workflow:**
1. Check if empty → Error: "Please enter units"
2. Check if numeric → Error: "Please enter valid number"
3. Check if negative → Error: "Units cannot be negative"
4. If all pass → Proceed with calculation

**Additional Security:**
- `htmlspecialchars()` on all output
- POST method (not GET - data not in URL)
- No database queries (no SQL injection risk here)

---

### Q3: What is the difference between `empty()`, `isset()`, and `is_numeric()` in PHP?

**ANSWER:**

**empty($variable):**
- Purpose: Check if variable has meaningful value
- Returns TRUE if "empty" or falsy
- Returns FALSE if has value

Values considered empty:
- "" (empty string)
- "0" (string zero)
- 0 (integer zero)
- 0.0 (float zero)
- NULL
- FALSE
- array() (empty array)
- Undefined variable

**Example:**
```php
$units = "";
empty($units)  → TRUE

$units = 0;
empty($units)  → TRUE (careful! zero is "empty")

$units = "150";
empty($units)  → FALSE
```

**isset($variable):**
- Purpose: Check if variable exists and is not NULL
- Returns TRUE if declared and not NULL
- Returns FALSE if undefined or NULL

**Example:**
```php
$units = "";
isset($units)  → TRUE (exists, even if empty string)

$units = NULL;
isset($units)  → FALSE

isset($xyz)  → FALSE (never declared)
```

**is_numeric($variable):**
- Purpose: Check if value is number or numeric string
- Returns TRUE for numbers
- Returns FALSE for non-numeric strings

**Example:**
```php
is_numeric(123)      → TRUE
is_numeric("123")    → TRUE
is_numeric("12.5")   → TRUE
is_numeric("abc")    → FALSE
is_numeric("12a")    → FALSE
```

**When to Use Which:**

- `empty()`: Check if form field has meaningful value
  - `if (empty($_POST['units'])) { error }`

- `isset()`: Check if variable exists before using
  - `if (isset($_POST['units'])) { process }`

- `is_numeric()`: Validate that input is number
  - `if (!is_numeric($units)) { error }`

**Common Pattern:**
```php
if (empty($units)) {
    // Field is empty
} elseif (!is_numeric($units)) {
    // Field has value but not a number
} elseif ($units < 0) {
    // Number is negative
} else {
    // Valid input!
}
```

---

### Q4: Explain the foreach loop used to display the breakdown table.

**ANSWER:**

**Purpose:**
The `foreach` loop iterates through each slab in the `$breakdown` array and displays it as a table row.

**The Breakdown Array Structure:**
```php
$breakdown = [
    [
        'slab' => '0-100 units',
        'units' => 100,
        'rate' => 5,
        'amount' => 500
    ],
    [
        'slab' => '101-200 units',
        'units' => 50,
        'rate' => 7,
        'amount' => 350
    ]
];
```
This is a multidimensional array (array of arrays).

**The foreach Loop:**
```php
foreach ($breakdown as $item) {
    // $item is each slab array
    echo $item['slab'];
    echo $item['units'];
    echo $item['rate'];
    echo $item['amount'];
}
```

**How It Works:**
1. **First iteration:**
   - `$item` = `['slab' => '0-100 units', 'units' => 100, ...]`
   - Displays: First table row

2. **Second iteration:**
   - `$item` = `['slab' => '101-200 units', 'units' => 50, ...]`
   - Displays: Second table row

3. **Loop ends** when no more items in `$breakdown`

**Syntax Breakdown:**
```php
foreach ($breakdown as $item)
//       ^array      ^each element
```

**Alternative Syntax in HTML:**
```php
<?php foreach ($breakdown as $item): ?>
    <tr>
        <td><?php echo $item['slab']; ?></td>
    </tr>
<?php endforeach; ?>
```
This is cleaner when mixing PHP with HTML.

**Why Use foreach?**
- Automatic iteration (no manual counter)
- Works with any size array
- Easy to read and understand
- No risk of off-by-one errors

**Alternative (Manual Loop):**
```php
// Less elegant way:
for ($i = 0; $i < count($breakdown); $i++) {
    echo $breakdown[$i]['slab'];
}
```
foreach is preferred!

---

### Q5: What is the purpose of `htmlspecialchars()` and why is it important?

**ANSWER:**

**Purpose:**
`htmlspecialchars()` converts special HTML characters to HTML entities, preventing XSS (Cross-Site Scripting) attacks.

**What It Does:**
```php
htmlspecialchars("<script>alert('xss')</script>");
// Converts to:
// &lt;script&gt;alert('xss')&lt;/script&gt;

// Browser displays as text:
// <script>alert('xss')</script>
// Instead of executing the JavaScript!
```

**Characters Converted:**
- `<` becomes `&lt;`
- `>` becomes `&gt;`
- `&` becomes `&amp;`
- `"` becomes `&quot;`
- `'` becomes `&#039;` (or `&apos;`)

**Why Critical for Security:**

**Without htmlspecialchars():**
```php
$units = $_POST['units'];  // User enters: <script>alert('hacked')</script>
echo "You entered: " . $units;
// Browser executes: alert('hacked')
// DANGEROUS!
```

**With htmlspecialchars():**
```php
$units = $_POST['units'];  // User enters: <script>alert('hacked')</script>
echo "You entered: " . htmlspecialchars($units);
// Browser displays as text: <script>alert('hacked')</script>
// SAFE!
```

**XSS Attack Example:**
Attacker enters as units:
```html
<img src=x onerror="alert(document.cookie)">
```

Without protection:
- Browser tries to load image
- Image fails (src=x invalid)
- onerror JavaScript executes
- Steals user's cookies!

With htmlspecialchars():
- Converts to text
- Browser displays string, doesn't execute
- Attack prevented!

**When to Use:**
- Displaying ANY user input
- Displaying data from database
- Displaying URL parameters
- Displaying form values

**Example in Our Code:**
```php
echo htmlspecialchars($error);
echo htmlspecialchars($item['slab']);
echo htmlspecialchars($_SERVER["PHP_SELF"]);
```

**Best Practice:**
ALWAYS use htmlspecialchars() when echoing variables, even if you think they're safe. Better safe than sorry!

---

### Q6: Explain the difference between POST and GET methods.

**ANSWER:**

**GET Method:**
- **Data Location:** URL parameters (query string)
- **Visibility:** Completely visible in address bar
- **Example:** `page.php?units=150&name=John`
- **Security:** Not secure (passwords visible!)
- **Caching:** Browser caches GET requests
- **Bookmarkable:** Yes (URL contains data)
- **Length Limit:** 2048 characters (URL length limit)
- **Data Type:** Only ASCII characters
- **Use For:** Search, filtering, public data

**POST Method:**
- **Data Location:** HTTP request body
- **Visibility:** Hidden from URL
- **Example URL:** `page.php` (clean URL)
- **Security:** More secure (data not in URL)
- **Caching:** Not cached by browsers
- **Bookmarkable:** No (data separate from URL)
- **Length Limit:** No practical limit
- **Data Type:** Binary data allowed (file uploads)
- **Use For:** Forms, login, sensitive data

**Comparison Table:**

Feature        | GET              | POST
--------------|------------------|------------------
Data in URL   | Yes              | No
Visible       | Yes              | No
Secure        | No               | More secure
Cached        | Yes              | No
Bookmarkable  | Yes              | No
Size Limit    | 2KB              | No limit
File Upload   | No               | Yes

**Why We Use POST in This Lab:**
```php
<form method="POST">
```

1. **Cleaner URLs:**
   - GET: `calculator.php?units=150`
   - POST: `calculator.php`

2. **Privacy:**
   - Units value not visible in URL
   - Not stored in browser history

3. **No Limit:**
   - Can handle large numbers
   - Future expansion possible

4. **Professional:**
   - Standard for form submission
   - Better user experience

**GET Example:**
```php
<form method="GET" action="search.php">
    <input name="query">
</form>
// Submits to: search.php?query=electricity
```
Good for search (can bookmark search results)

**POST Example:**
```php
<form method="POST" action="calculator.php">
    <input name="units">
</form>
// Submits to: calculator.php (data in body)
```
Good for calculations (private, not bookmarked)

---

### Q7: What is `number_format()` and why do we use it?

**ANSWER:**

**Purpose:**
`number_format()` formats numbers with grouped thousands and decimal points for better readability.

**Syntax:**
```php
number_format($number, $decimals, $decimal_point, $thousands_separator)
```

**Examples:**

**Basic Usage:**
```php
$bill = 1234.5;
echo number_format($bill, 2);
// Output: 1,234.50

// Parameters:
// 1234.5 = number to format
// 2 = decimal places
```

**Different Formats:**
```php
number_format(1234.567, 2)
// Output: 1,234.57

number_format(1234.567, 0)
// Output: 1,235 (no decimals, rounded)

number_format(1234567.89, 2)
// Output: 1,234,567.89
```

**Custom Separators:**
```php
number_format(1234.56, 2, ',', ' ')
// Output: 1 234,56 (European format)

number_format(1234.56, 2, '.', ',')
// Output: 1,234.56 (US format - our default)
```

**Why Use It:**

**1. Readability:**
- 1234567 vs 1,234,567 (which is clearer?)
- Easy to see if it's thousands, millions, etc.

**2. Consistency:**
- All amounts shown same way
- ₹500.00 and ₹1,234.50 both 2 decimals

**3. Professional Appearance:**
- Looks polished and complete
- Shows attention to detail

**4. Currency Standards:**
- Money should always show 2 decimals
- ₹10 displays as ₹10.00

**In Our Code:**
```php
// Format total bill
number_format($totalBill, 2)
// 850 becomes "850.00"
// 1234.5 becomes "1,234.50"

// Format units
number_format($units, 2)
// 150 becomes "150.00"
// Shows consistency even for whole numbers
```

**Without number_format():**
```
Bill: 1234.5  ← Inconsistent decimals
Units: 150    ← No decimals
```

**With number_format():**
```
Bill: ₹1,234.50  ← Professional
Units: 150.00    ← Consistent
```

**Note:** number_format() returns STRING, not number
```php
$formatted = number_format(1234.5, 2);
// $formatted = "1,234.50" (string)
// Can't do math with this!

// For display: Use number_format()
// For calculation: Use original number
```

---

### Q8: How would you modify this calculator to add fixed charges or tax?

**ANSWER:**

**Scenario:** Add ₹100 fixed charge + 10% tax to bill

**Modified Calculation:**
```php
// After calculating $totalBill from slabs

// Add fixed charge
$fixedCharge = 100;
$subtotal = $totalBill + $fixedCharge;

// Add tax (10%)
$taxRate = 0.10;  // 10% = 0.10
$taxAmount = $subtotal * $taxRate;

// Calculate final bill
$finalBill = $subtotal + $taxAmount;

// OR in one line:
$finalBill = ($totalBill + 100) * 1.10;
```

**Example Calculation (150 units):**
```
Slab charges:         ₹850.00
Fixed charge:       + ₹100.00
-------------------------
Subtotal:             ₹950.00
Tax (10%):          + ₹95.00
-------------------------
Final Bill:           ₹1,045.00
```

**Updated Display Code:**
```php
<div class="breakdown-table">
    <!-- Existing slab rows -->
    
    <tr style="background: #f5f5f5;">
        <td colspan="3">Fixed Charge:</td>
        <td>₹<?php echo number_format($fixedCharge, 2); ?></td>
    </tr>
    
    <tr style="background: #f5f5f5;">
        <td colspan="3">Subtotal:</td>
        <td>₹<?php echo number_format($subtotal, 2); ?></td>
    </tr>
    
    <tr style="background: #f5f5f5;">
        <td colspan="3">Tax (10%):</td>
        <td>₹<?php echo number_format($taxAmount, 2); ?></td>
    </tr>
    
    <tr style="background: #e0e0e0; font-weight: bold;">
        <td colspan="3">FINAL BILL:</td>
        <td>₹<?php echo number_format($finalBill, 2); ?></td>
    </tr>
</div>
```

**Other Possible Enhancements:**

**1. Seasonal Discounts:**
```php
$month = date('n');  // Current month (1-12)
if ($month >= 6 && $month <= 9) {
    // Monsoon season - 5% discount
    $discount = $totalBill * 0.05;
    $totalBill -= $discount;
}
```

**2. Late Payment Penalty:**
```php
$dueDate = '2024-01-15';
$currentDate = date('Y-m-d');
if ($currentDate > $dueDate) {
    $penalty = 50;  // ₹50 late fee
    $totalBill += $penalty;
}
```

**3. Tiered Tax Rates:**
```php
if ($totalBill < 500) {
    $tax = 0;  // No tax for small bills
} elseif ($totalBill < 1000) {
    $tax = $totalBill * 0.05;  // 5% tax
} else {
    $tax = $totalBill * 0.10;  // 10% tax
}
```

**4. Environmental Charges:**
```php
if ($units > 300) {
    // High consumption - eco penalty
    $ecoCharge = 200;
    $totalBill += $ecoCharge;
}
```

All these can be added to the existing logic without breaking the tiered calculation!

---

### Q9: What are some real-world applications of tiered pricing?

**ANSWER:**

**1. Electricity Bills (This Lab):**
- Encourages conservation
- Makes basic power affordable
- Penalizes wasteful consumption
- Example: 
  - 0-100 units: ₹3-5 (basic needs)
  - 300+ units: ₹10-15 (luxury/waste)

**2. Income Tax:**
- Progressive taxation
- Low earners pay less percentage
- High earners pay more percentage
- Example (India):
  - ₹0-2.5L: 0% tax
  - ₹2.5L-5L: 5% tax
  - ₹10L+: 30% tax

**3. Mobile Data Plans:**
- First GB cheap/free
- Additional data expensive
- Encourages staying within limit
- Example:
  - 0-2GB: ₹199
  - 2-5GB: ₹10/GB
  - 5GB+: ₹20/GB

**4. Water Bills:**
- Essential water cheap
- Excessive usage penalized
- Promotes conservation
- Example:
  - 0-50L: ₹5/L
  - 50-100L: ₹8/L
  - 100L+: ₹15/L

**5. Cloud Storage (Dropbox, Google):**
- Free tier for casual users
- Paid tiers for heavy users
- Example:
  - 0-15GB: Free
  - 15-100GB: ₹130/month
  - 100GB-2TB: ₹650/month

**6. Parking Fees:**
- First hour cheap (shopping)
- Additional hours expensive (discourage long parking)
- Example:
  - 0-1 hour: ₹20
  - 1-3 hours: ₹50/hour
  - 3+ hours: ₹100/hour

**7. Airline Baggage:**
- First bag free/cheap
- Additional bags expensive
- Example:
  - 0-15kg: Free
  - 15-30kg: ₹1000
  - 30kg+: ₹3000

**8. Software Licensing (SaaS):**
- Free tier for individuals
- Paid tiers for businesses
- Example (Slack):
  - 0-10 users: Free
  - 10-50 users: ₹500/user
  - 50+ users: Enterprise pricing

**Benefits of Tiered Pricing:**
- **Economic Efficiency:** Heavy users pay more
- **Social Equity:** Basic needs affordable
- **Conservation:** Discourages waste
- **Revenue Optimization:** Maximizes profit
- **Market Segmentation:** Different users, different prices

**Implementation Challenges:**
- **Complexity:** Harder to calculate
- **Customer Confusion:** Need clear explanation
- **Fairness Perception:** "Why do I pay more?"
- **Administrative:** Tracking usage accurately

**Programming Perspective:**
Tiered pricing is a common algorithm in:
- Billing systems
- E-commerce platforms
- Subscription services
- Utility management
- Financial applications

Understanding this concept = valuable real-world skill!

---

### Q10: How does this calculator handle edge cases?

**ANSWER:**

**Edge Case 1: Exactly at Tier Boundary (100, 200, 300 units)**

**Example: 100 units**
```php
if ($units <= 100) {
    // This condition is TRUE
    $totalBill = 100 * 5 = ₹500
}
```
Falls in first tier only. Correct!

**Example: 200 units**
```php
elseif ($units <= 200) {
    // This condition is TRUE
    $slab1 = 100 * 5 = ₹500
    $slab2 = 100 * 7 = ₹700
    $total = ₹1200
}
```
Exactly fills two tiers. Correct!

**Why <= not <:**
- `$units <= 200` includes 200
- `$units < 200` would exclude 200
- We want 200 to be in second tier (101-200)

---

**Edge Case 2: Zero Units**

**Input: 0 units**
```php
if (empty($units)) {
    // 0 is considered "empty" by empty()!
    $error = "Please enter units";
}
```

**Problem:** 0 is valid (meter just installed)
**Solution:** Check specifically
```php
if ($units === "" || $units === null) {
    $error = "Please enter units";
} elseif ($units == 0) {
    // Allow 0, but bill is ₹0
    $totalBill = 0;
}
```

---

**Edge Case 3: Decimal Units (125.50 units)**

**Handled Correctly:**
```php
$units = floatval($units);
// Converts "125.50" to 125.50 (float)

// Step attribute in HTML
<input type="number" step="0.01">
// Allows decimals in input

// Calculation works fine
if ($units <= 200) {
    $slab1 = 100 * 5 = ₹500
    $slab2 = 25.5 * 7 = ₹178.50
    $total = ₹678.50
}
```

---

**Edge Case 4: Very Large Numbers (10,000 units)**

**Input: 10000 units**
```php
else {  // Above 300
    $slab1 = 100 * 5 = ₹500
    $slab2 = 100 * 7 = ₹700
    $slab3 = 100 * 10 = ₹1,000
    $slab4 = 9700 * 15 = ₹145,500
    $total = ₹147,700
}
```
Works correctly! No upper limit.

---

**Edge Case 5: Negative Numbers**

**Input: -50**
```php
elseif ($units < 0) {
    $error = "Units cannot be negative.";
}
```
Caught by validation!

---

**Edge Case 6: Non-numeric Input**

**Input: "abc" or "12.5.6"**
```php
elseif (!is_numeric($units)) {
    $error = "Please enter a valid number.";
}
```
Validation prevents calculation errors!

---

**Edge Case 7: Scientific Notation (1e3 = 1000)**

**Input: 1e3**
```php
is_numeric("1e3")  // Returns TRUE!
floatval("1e3")    // Converts to 1000

// Calculation proceeds correctly
// 1000 units = ₹8,000
```
PHP handles this automatically!

---

**Edge Case 8: Leading/Trailing Spaces**

**Input: "  150  "**
```php
$units = trim($_POST["units"]);
// trim() removes: "  150  " → "150"
```
Cleaned before validation!

---

**Edge Case 9: Form Resubmission (Refresh After Submit)**

**Problem:** Hitting refresh after submit recalculates
**Current Behavior:** Recalculates same amount (harmless)
**Production Fix:**
```php
// Redirect after POST (PRG pattern)
if ($totalBill > 0) {
    header("Location: result.php?bill=$totalBill");
    exit();
}
```

---

**Edge Case 10: Multiple Decimal Points (12.5.6)**

**Input: "12.5.6"**
```php
is_numeric("12.5.6")  // Returns FALSE
// Caught by validation!
```

---

**Summary of Edge Case Handling:**

| Edge Case | Handled | How |
|-----------|---------|-----|
| Zero | ⚠️ Partial | empty() catches it |
| Negative | ✅ Yes | Specific check |
| Decimal | ✅ Yes | floatval(), step |
| Large | ✅ Yes | No limit |
| Non-numeric | ✅ Yes | is_numeric() |
| Boundary | ✅ Yes | <= operator |
| Spaces | ✅ Yes | trim() |
| Empty | ✅ Yes | empty() check |

**Good edge case handling = robust application!**

---

## 📚 ADDITIONAL CONCEPTS FOR VIVA

### Progressive Calculation vs Flat Rate

**Flat Rate (Wrong for Tiered):**
```php
// WRONG!
$rate = 10;  // Assume average rate
$bill = $units * $rate;
```
Problem: Doesn't account for different tier rates

**Progressive Calculation (Correct):**
```php
// CORRECT!
$bill = 0;
if ($units > 0) {
    $bill += min($units, 100) * 5;
}
if ($units > 100) {
    $bill += min($units - 100, 100) * 7;
}
// ... and so on
```
Each tier calculated separately

---

### Form Security Best Practices

**What We Do:**
1. POST method (not GET)
2. htmlspecialchars() on output
3. Input validation
4. Type checking (is_numeric)
5. Range checking (negative check)

**What We Could Add (Advanced):**
1. CSRF tokens (prevent forged requests)
2. Rate limiting (prevent spam)
3. Input sanitization (additional cleaning)
4. Session validation
5. HTTPS enforcement

---

### CSS Gradient Explained

```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

**Breakdown:**
- `linear-gradient`: Type (straight line gradient)
- `135deg`: Angle (diagonal, top-left to bottom-right)
- `#667eea`: Start color (light purple)
- `0%`: Start position
- `#764ba2`: End color (dark purple)
- `100%`: End position

**Other gradient types:**
- `radial-gradient`: Circular
- `conic-gradient`: Angular (pie chart)

---

## 🎯 DEMONSTRATION TIPS

**For Examiner:**
1. Show pricing table clearly
2. Test multiple values (50, 150, 250, 350)
3. Demonstrate validation errors
4. Explain tiered calculation verbally
5. Show formatted output (₹1,234.50)
6. Highlight breakdown table
7. Test boundary values (100, 200, 300)
8. Show decimal handling (125.50)
9. Demonstrate responsive design
10. Explain code structure

**Key Points to Emphasize:**
- ✅ Tiered pricing logic (not simple multiplication)
- ✅ Input validation (security & UX)
- ✅ Progressive calculation
- ✅ Detailed breakdown (transparency)
- ✅ Professional UI/UX
- ✅ Number formatting (readability)
- ✅ Error handling (helpful messages)
- ✅ Real-world application

---

## 📖 REFERENCES & FURTHER READING

**PHP Documentation:**
- Input validation: https://www.php.net/manual/en/filter.examples.validation.php
- Array functions: https://www.php.net/manual/en/ref.array.php
- Number formatting: https://www.php.net/manual/en/function.number-format.php

**Web Development:**
- HTML Forms: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/form
- CSS Gradients: https://developer.mozilla.org/en-US/docs/Web/CSS/gradient
- Responsive Design: https://web.dev/responsive-web-design-basics/

**Security:**
- XSS Prevention: https://owasp.org/www-community/attacks/xss/
- Input Validation: https://cheatsheetseries.owasp.org/cheatsheets/Input_Validation_Cheat_Sheet.html

---

**🎓 You're now fully prepared for Lab 6 demonstration and viva!**

Good luck! 🚀
