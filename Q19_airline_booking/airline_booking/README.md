# ✈️ Airline Seat Booking System — PHP
### Lab Question 19 | Web Technology (WT)

---

## 📁 FILE STRUCTURE

```
airline_booking/
│
├── index.php           ← Home page: lists all flights with availability
├── seat_map.php        ← ⭐ CORE: Visual seat map + booking form
├── my_booking.php      ← Retrieve booking by PNR code
├── admin.php           ← Admin panel: all bookings + revenue stats
│
├── database.sql        ← SQL to create all tables + seed data
│
├── includes/
│   ├── db.php          ← PDO MySQL connection
│   └── helpers.php     ← Reusable functions (PNR gen, validation, etc.)
│
└── css/
    └── style.css       ← Complete CSS (variables, flexbox, grid, animations)
```

---

## ⚙️ SETUP (Do This Once)

### 1. Install XAMPP
Download: https://www.apachefriends.org/
Start **Apache** and **MySQL** in XAMPP Control Panel

### 2. Copy Project
Copy `airline_booking/` folder to:
- **Windows:** `C:\xampp\htdocs\airline_booking\`
- **Mac:** `/Applications/XAMPP/htdocs/airline_booking/`

### 3. Import Database
- Open browser → `http://localhost/phpmyadmin`
- Click **New** → Database name: `airline_db` → Click **Create**
- Click **Import** tab → Choose `database.sql` → Click **Go**

### 4. Run the App
Open: `http://localhost/airline_booking/`

---

## 🖥️ EXPECTED OUTPUT — What to Show Examiner

### Page 1: `index.php` (Home)
- 4 flight cards in a grid: AI-101, AI-202, 6E-303, SG-404
- Each card shows: flight number, route (Mumbai → Delhi), time, price (₹4500), seats available
- Green badge = many seats, yellow = few, red = full
- Click a card → goes to seat map

### Page 2: `seat_map.php?flight=1`
- **Left side:** Visual airplane shape (nose at top)
  - Row 1-2: Gold/yellow = Business Class seats
  - Row 3-10: Green = Economy Available seats
  - Some seats shown in red = already booked (cannot click)
  - Click a green seat → it turns blue = selected
- **Right side:** Booking form appears
  - Enter: Name, Email, 10-digit mobile
  - Click "Confirm Booking" → shows success with PNR code

### Page 3: After Booking
- Big checkmark ✅
- PNR code displayed in large monospace font (e.g. `AX7K2P`)
- Full booking details: name, flight, seat, class, price

### Page 4: `my_booking.php`
- Enter PNR code → shows full booking details
- Boarding pass style card at bottom

### Page 5: `admin.php`
- Flight stats table: total/booked/available seats + revenue per flight
- All bookings table with PNR, passenger, seat, amount

---

## 📖 THEORY — WT Syllabus Concepts Mapped

### HTML5 (Unit I)
- **Semantic elements:** `<nav>`, `<form>`, `<table>`, `<button>`
- **Form types:** `type="email"`, `type="tel"`, `type="text"`, `type="hidden"`
- **Data attributes:** `data-seat-id`, `data-seat-no`, `data-class` — custom HTML5 attributes for storing data on elements
- **`required`, `maxlength`, `placeholder`** — HTML5 form attributes

### CSS3 (Unit I)
- **CSS Variables:** `--sky: #0ea5e9` — define colors once, reuse everywhere
- **Flexbox:** `display:flex; justify-content:space-between` — nav, card layouts
- **CSS Grid:** `display:grid; grid-template-columns: repeat(3, 1fr)` — seat map grid
- **`aspect-ratio: 1`** — makes seat buttons square
- **`border-radius`** — seat shape, rounded cards
- **CSS Animations/Transitions:** `transition: all 0.2s`, `@keyframes fadeIn`
- **Pseudo-classes:** `:hover`, `:focus`, `:active`, `[disabled]`
- **`position: sticky`** — navbar stays at top
- **Responsive:** `@media (max-width: 600px)` — mobile layout

### JavaScript / DOM (Unit II)
- **DOM Selection:** `document.getElementById('id')` — get element by ID
- **Event Listener:** `element.addEventListener('submit', fn)` — listen for events
- **`classList.add/remove`** — dynamically change CSS classes
- **`dataset`** — read `data-*` HTML attributes: `btn.dataset.seatId`
- **`textContent`** — safely set element text (no HTML injection)
- **`preventDefault()`** — stop form from submitting
- **Client validation:** check empty fields, regex for email/phone
- **Regular Expression:** `/^[6-9]\d{9}$/.test(phone)` — validate phone format

### PHP (Unit III)
- **`$_GET`:** reads URL parameters — `?flight=1` → `$_GET['flight']` = 1
- **`$_POST`:** reads form submission data
- **`$_SERVER['REQUEST_METHOD']`:** detect if page was accessed via GET or POST
- **PDO Prepared Statements:** `$pdo->prepare("SQL ?")` + `execute([value])`
- **`filter_var($email, FILTER_VALIDATE_EMAIL)`** — PHP built-in email validation
- **`preg_match()`** — regular expression matching (phone validation)
- **`htmlspecialchars()`** — prevent XSS: `<script>` → `&lt;script&gt;`
- **`trim()`** — remove whitespace from input
- **`strtoupper()`** — convert string to uppercase
- **`date()`** — format date: `date('d M Y', strtotime($dbDate))`
- **`strtotime()`** — parse date string to Unix timestamp
- **`substr()`**, `strlen()`, `str_shuffle()` — string functions
- **`array_filter()`** — filter array elements by condition
- **`range(1, 10)`** — creates array [1, 2, 3, ..., 10]
- **`in_array()`** — check if value exists in array
- **Arrow function:** `fn($s) => $s['is_booked']` — PHP 7.4+ short syntax
- **`$pdo->lastInsertId()`** — get ID of last inserted row
- **DB Transaction:**
  ```php
  $pdo->beginTransaction();   // start
  // ... multiple queries ...
  $pdo->commit();             // save all
  $pdo->rollBack();           // undo all (on error)
  ```

### MySQL (Unit III)
- **CREATE TABLE** with `ENUM`, `UNIQUE KEY`, `FOREIGN KEY`
- **Stored Procedure + LOOP** — generate 60 seats per flight programmatically
- **INSERT, SELECT, UPDATE**
- **JOIN types:** INNER JOIN (match both), LEFT JOIN (include unmatched)
- **GROUP BY** with aggregate functions: `COUNT(*)`, `SUM()`, `AVG()`
- **Aliases:** `FROM bookings b JOIN flights f ON b.flight_id = f.id`
- **`ON DELETE CASCADE`** — auto-delete seats if flight is deleted

---

## ❓ VIVA QUESTIONS + ANSWERS

**Q1: What is the main concept demonstrated in this project?**
Three-tier architecture:
- **Presentation layer:** HTML + CSS (what user sees)
- **Logic layer:** PHP (processes form data, talks to DB)
- **Data layer:** MySQL (stores flights, seats, bookings)

**Q2: What is a seat map? How did you implement it?**
A seat map is a visual grid showing all seats in the airplane with their availability status. Implementation:
- SQL: Store each seat as a row in `seats` table with `row_num` and `col_label`
- PHP: Fetch all seats, organize into 2D array `$seatMap[row][col]`
- HTML+CSS: Use CSS Grid (`grid-template-columns: 36px repeat(3,1fr) 28px repeat(3,1fr) 36px`) to create the airplane layout
- CSS Classes: `available` (green), `booked` (red), `selected` (blue) — toggled by JavaScript

**Q3: How does seat selection work without a page reload?**
JavaScript handles it:
1. User clicks a seat `<button>` → `onclick="selectSeat(this)"` fires
2. JS function removes `available` class, adds `selected` class → CSS changes color
3. JS reads `data-seat-id` attribute from the button
4. JS sets hidden input's value: `document.getElementById('seatIdInput').value = seatId`
5. When form submits, hidden input sends seat ID to PHP in `$_POST['seat_id']`

**Q4: What is a database Transaction? Why did you use it?**
A transaction groups multiple SQL statements into one atomic operation — either ALL succeed or ALL fail (rollback). Used when:
1. Mark seat as booked (`UPDATE seats`)
2. Insert booking record (`INSERT INTO bookings`)

If step 1 succeeds but step 2 fails (e.g. duplicate PNR), we'd have a seat marked booked with no booking record — data corruption. Transaction prevents this with `beginTransaction() → commit() / rollBack()`.

**Q5: What is a PNR? How do you generate a unique one?**
PNR = Passenger Name Record — a unique 6-character booking reference. Generated with:
```php
$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$pnr = strtoupper(substr(str_shuffle($chars), 0, 6));
```
Then verify uniqueness with `SELECT id FROM bookings WHERE pnr=?`. Retry if it exists (loop). The probability of collision is tiny: 32^6 = 1 billion+ combinations.

**Q6: What is SQL Injection? How does your code prevent it?**
SQL Injection: attacker puts SQL into form fields, e.g. `' OR '1'='1`. Prevention:
- **PDO Prepared Statements:** `$pdo->prepare("SELECT * WHERE id=?")` + `execute([$id])`
- The `?` placeholder is never concatenated with SQL. PDO escapes values automatically.
- NEVER do: `"SELECT * WHERE id=" . $_GET['id']` — this is vulnerable.

**Q7: What is XSS? How does `htmlspecialchars()` prevent it?**
XSS (Cross-Site Scripting): attacker types `<script>alert('hacked')</script>` into a form. Without protection, this gets stored and rendered as real HTML in the browser.
`htmlspecialchars()` converts `<` to `&lt;`, `>` to `&gt;`, so browser displays it as text, not executes it as code.

**Q8: Difference between `$_GET` and `$_POST`?**
| Feature | GET | POST |
|---------|-----|------|
| Data location | URL: `?flight=1` | HTTP request body |
| Visibility | Visible in URL | Hidden |
| Size limit | ~2048 chars | No practical limit |
| Caching | Yes (bookmarkable) | No |
| Use when | Searching/reading | Submitting/modifying |

In our project: `seat_map.php?flight=1` uses GET (shareable URL), booking form uses POST (sensitive data).

**Q9: What is `data-*` attribute in HTML5?**
Custom attributes starting with `data-` allow storing extra data on HTML elements:
```html
<button data-seat-id="42" data-seat-no="5C" data-class="Economy">5C</button>
```
JavaScript reads them via `element.dataset.seatId`, `element.dataset.seatNo`. This avoids global JS variables and keeps data close to the element it belongs to.

**Q10: What is CSS Flexbox? Where did you use it?**
Flexbox (`display: flex`) is a CSS layout model for arranging items in a row or column with flexible sizing:
- `justify-content: space-between` → pushes items to opposite ends (nav logo + links)
- `align-items: center` → vertical centering
- `flex: 1` → item takes equal share of remaining space
Used in: navigation bar, seat legend, flight card meta section, detail rows.

**Q11: What is CSS Grid? How is the seat map built with it?**
CSS Grid (`display: grid`) creates two-dimensional layouts. The seat map uses:
```css
grid-template-columns: 36px repeat(3,1fr) 28px repeat(3,1fr) 36px;
```
This creates: [row-label] [A] [B] [C] [aisle] [D] [E] [F] [row-label]
`repeat(3, 1fr)` = 3 equal-width columns. `28px` = fixed aisle gap.

**Q12: What are the different SQL JOINs? Which did you use?**
- **INNER JOIN** — returns rows that have a match in BOTH tables
- **LEFT JOIN** — returns ALL rows from left table, matched rows from right (NULL if no match)
- **RIGHT JOIN** — opposite of LEFT JOIN
We used INNER JOIN to get booking + flight + seat data together, and LEFT JOIN in admin stats to include flights with zero bookings.

**Q13: What is `ENUM` in MySQL?**
`ENUM` restricts a column to a set of allowed string values:
```sql
class ENUM('Business', 'Economy') NOT NULL
```
MySQL stores it efficiently as an integer internally. Inserts values outside the ENUM cause an error. Used for `class` (seat type) and `is_booked` could be `TINYINT(1)` (boolean-like).

**Q14: What is the `UNIQUE KEY` constraint?**
```sql
UNIQUE KEY unique_seat (flight_id, seat_no)
```
Composite unique key: combination of `flight_id` + `seat_no` must be unique. This prevents two identical seat numbers (e.g. "5C") for the same flight, while allowing "5C" to exist across different flights.

**Q15: Explain the `array_filter()` function used in your code.**
```php
$bookedSeats = count(array_filter($allSeats, fn($s) => $s['is_booked']));
```
`array_filter()` returns only elements for which the callback returns `true`. The arrow function `fn($s) => $s['is_booked']` returns 1 (truthy) for booked seats, 0 (falsy) for available. `count()` then gives the total.

---

*Good luck with your viva! ✈️🎯*
