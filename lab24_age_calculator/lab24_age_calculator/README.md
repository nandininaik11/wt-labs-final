# 📘 Lab Q24 — JavaScript Age Calculator
## Web Technology Subject — Complete Notes

---

## ⚙️ HOW TO OPEN IN VS CODE

1. Extract the ZIP anywhere on your computer
2. Open VS Code → File → Open Folder → select `lab24_age_calculator`
3. Click `index.html` in the Explorer panel
4. Press **Go Live** (Live Server extension, bottom-right) OR double-click `index.html` to open directly in browser

> ✅ No npm install. No server. No dependencies. Pure HTML + CSS + JavaScript!

---

## 📁 File Structure

```
lab24_age_calculator/
│
├── index.html     ← Complete app: HTML structure + CSS styling + JS logic
└── README.md      ← This file: theory + viva notes
```

---

## 🖥️ Expected Output (What to Show Examiner)

1. Page loads with a warm-toned card containing a **date input** field
2. Click the date field → browser's native **calendar picker** opens
3. Select a birth date (e.g. **15 June 2002**)
4. Click **"Calculate My Age →"** button
5. Result appears with:
   - **Three large boxes**: `22 Years` | `10 Months` | `15 Days` (example)
   - **Summary sentence**: "You are 22 years, 10 months and 15 days old."
   - **Extra stats**: Total Months / Total Weeks / Total Days / Total Hours lived
   - **Next birthday**: "🎂 Next Birthday: June 15, 2026 — in 46 days"

### Validation demo:
- Click Calculate without selecting a date → error message appears
- Future date is blocked by the `max` attribute on the input

### Console demo (press F12 → Console tab):
```
=== Age Calculation Result ===
Birth Date : Sat Jun 15 2002
Today      : Wed Apr 30 2026
Age        : 23 years, 10 months, 15 days
Total Days : 8719
```

---

## 📖 THEORY — WT Syllabus Mapped

### 1. JavaScript Date Object (Unit-II: "Objects in JS")

The `Date` object is a **built-in JavaScript object** for working with dates and times.

```javascript
const now  = new Date();              // current date and time
const dob  = new Date("2002-06-15"); // date from string (ISO format)
const d    = new Date(2002, 5, 15);  // date from year, month (0-indexed), day
```

Key methods:

| Method | Returns | Example |
|---|---|---|
| `.getFullYear()` | 4-digit year | `2002` |
| `.getMonth()` | Month 0–11 | `5` (= June) |
| `.getDate()` | Day 1–31 | `15` |
| `.getDay()` | Weekday 0–6 | `0` (= Sunday) |
| `.getTime()` | Milliseconds since epoch | `1023580800000` |
| `.toDateString()` | Human string | `"Sat Jun 15 2002"` |
| `.toISOString()` | ISO string | `"2002-06-15T00:00:00.000Z"` |

> **Important**: `.getMonth()` is 0-indexed — January = 0, December = 11. Always add 1 when displaying to humans.

---

### 2. Age Calculation Algorithm (Unit-II: Control Structures)

```
years  = currentYear  - birthYear
months = currentMonth - birthMonth
days   = currentDay   - birthDay

if (days < 0):
    months = months - 1
    days = days + (number of days in previous month)

if (months < 0):
    years = years - 1
    months = months + 12
```

**Why the adjustments?**
- If you're born on the 25th and today is the 10th, your "days" component is negative — you haven't hit the 25th this month yet. Borrow from last month.
- If you're born in August and today is March, your "months" is negative — you haven't hit August this year yet. Borrow from last year.

**Getting days in previous month:**
```javascript
new Date(year, month, 0).getDate()
// day=0 = last day of previous month
// new Date(2025, 3, 0) = March 31 (last day of March)
```

---

### 3. HTML5 Date Input (Unit-I: HTML5 Forms)

```html
<input type="date" id="birthDate" max="2025-04-30" />
```

- Renders a **native calendar picker** in the browser
- Value is always returned as `"YYYY-MM-DD"` string
- `max` attribute prevents selecting future dates
- `min` attribute prevents selecting before a certain date

---

### 4. DOM Manipulation (Unit-II: "Manipulating DOM")

```javascript
// READ input value
const val = document.getElementById('birthDate').value;

// WRITE to element
document.getElementById('ageYears').textContent = 22;

// SHOW/HIDE elements
element.style.display = 'block';   // show
element.style.display = 'none';    // hide

// ADD/REMOVE CSS class
element.classList.add('visible');
element.classList.remove('visible');
element.classList.contains('visible'); // true/false check
```

---

### 5. JavaScript Functions and Scope (Unit-II)

```javascript
function calculateAge() {
    // Function declaration — hoisted (can be called before it's defined)
    const birthDate = new Date(inputVal);  // local variable, only inside function
    // ...
}
```

**IIFE (Immediately Invoked Function Expression):**
```javascript
(function setMaxDate() {
    // runs immediately on page load
    document.getElementById('birthDate').max = new Date().toISOString().split('T')[0];
})();
```

---

### 6. JavaScript Data Types (Unit-II)

| Type | Example in this lab |
|---|---|
| String | `"2002-06-15"`, `"Years"` |
| Number | `years = 22`, `totalDays = 8000` |
| Boolean | `daysUntilBday === 0` → `true/false` |
| Object | `new Date()`, `new Date("2002-06-15")` |
| Undefined | uninitialized variable |

---

### 7. Template Literals (Unit-II: JavaScript)

```javascript
// Old way (concatenation):
"You are " + years + " years old"

// New way (template literal with backticks):
`You are ${years} years old`
```

Template literals use backtick `` ` `` and `${expression}` for embedding values.

---

### 8. Ternary Operator (Unit-II: Control Structures)

```javascript
years !== 1 ? 's' : ''
// if years is NOT 1: result is 's' (plural)
// if years IS 1: result is '' (singular)
// Output: "2 years" or "1 year"
```

---

### 9. HTML5 Form Validation (Unit-II)

**HTML5 built-in validation** (handled by browser):
```html
<input type="date" max="2025-04-30" required />
```

**Custom JavaScript validation** (used in this lab):
```javascript
if (!inputVal) {
    errorMsg.style.display = 'block';
    return;  // stop function execution
}
if (birthDate > today) {
    // future date — invalid
}
```

---

## ❓ LIKELY VIVA QUESTIONS + ANSWERS

**Q1. What is the JavaScript Date object?**
A: `Date` is a built-in JavaScript object that represents a single moment in time. Internally it stores time as milliseconds since January 1, 1970 00:00:00 UTC (Unix epoch). It provides methods to get/set year, month, day, hours, etc.

---

**Q2. Why is `.getMonth()` 0-indexed?**
A: JavaScript's `Date` follows the convention from the C language where months are 0–11. January = 0, February = 1, ... December = 11. When displaying to users, add 1. When creating a Date with `new Date(year, month, day)`, also use 0-indexed months.

---

**Q3. Explain the age calculation algorithm — why do we need to adjust days and months?**
A: Direct subtraction can give negative values. Example: born on 25th, today is 10th → `10 - 25 = -15 days`. This means the birthday hasn't occurred in the current month yet, so we borrow the full days of the previous month and reduce months by 1. Similarly if birth month > current month, we borrow 12 months and reduce years by 1.

---

**Q4. How do you get the number of days in a month in JavaScript?**
A: Use `new Date(year, month, 0).getDate()`. When you pass `0` as the day to the `Date` constructor, it returns the last day of the previous month. Example: `new Date(2025, 3, 0).getDate()` = 31 (last day of March, because month 3 = April so month 2 = March).

---

**Q5. What does `new Date()` (no arguments) return?**
A: It returns a Date object representing the current date and time at the moment the line executes.

---

**Q6. What is `toISOString()` and why is it used here?**
A: `.toISOString()` converts a Date to a string in ISO 8601 format: `"YYYY-MM-DDTHH:mm:ss.sssZ"`. We use `.split('T')[0]` to extract just `"YYYY-MM-DD"`, which is the format required by the HTML `<input type="date">` `max` attribute.

---

**Q7. What is `Math.floor()` and when do we use it?**
A: `Math.floor()` rounds a decimal number DOWN to the nearest integer. Used when dividing milliseconds: `(today - birthDate) / msPerDay` can give a decimal like `8719.5` — we floor it to `8719` whole days.

---

**Q8. What is the difference between `textContent` and `innerHTML`?**
A: `textContent` sets plain text — HTML tags are treated as literal text (safe, prevents XSS attacks). `innerHTML` parses HTML tags — used when you want to insert formatted HTML like `<strong>22 years</strong>`. Always prefer `textContent` unless you need HTML formatting.

---

**Q9. What is `input type="date"` in HTML5?**
A: An HTML5 form input that renders a native calendar date picker in the browser. Its value is always returned as a string in `"YYYY-MM-DD"` format. The `max` and `min` attributes restrict the selectable date range. Before HTML5, you'd need a JavaScript library (like jQuery UI Datepicker) for this.

---

**Q10. What is an IIFE?**
A: IIFE = Immediately Invoked Function Expression. A function defined and called at the same time using the pattern `(function() { ... })()`. Used to run initialization code once without polluting the global scope with variables.

---

**Q11. What is the Unix epoch / timestamp?**
A: The Unix epoch is January 1, 1970 00:00:00 UTC. JavaScript's `Date` object stores time internally as milliseconds since this point. When you subtract two Date objects, you get the difference in milliseconds, which you can then divide to get days, weeks, etc.

---

**Q12. Explain `Math.ceil()` vs `Math.floor()` vs `Math.round()`.**
A:
- `Math.floor(4.9)` = 4 (always round DOWN)
- `Math.ceil(4.1)` = 5 (always round UP)
- `Math.round(4.5)` = 5 (round to nearest, 0.5+ goes up)

Used for "days until birthday": `Math.ceil` ensures even a few minutes into a day counts as 1 day remaining.

---

*Prepared for WT Lab Q24 — JavaScript Age Calculator*
