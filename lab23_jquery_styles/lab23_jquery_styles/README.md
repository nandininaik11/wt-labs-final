# 📘 Lab Q23 — jQuery Style Switcher
## Web Technology Subject — Complete Notes

---

## ⚙️ HOW TO OPEN IN VS CODE

1. Extract the ZIP anywhere
2. Open VS Code → File → Open Folder → select `lab23_jquery_styles`
3. Click `index.html` in the sidebar to open it
4. Press **Go Live** (Live Server extension) at the bottom-right of VS Code
   OR just double-click `index.html` — no server needed, opens in browser

> ✅ No npm install, no server, no setup — pure HTML + CSS + jQuery (CDN)!

---

## 📁 File Structure

```
lab23_jquery_styles/
│
├── index.html        ← Full app: HTML structure + CSS themes + jQuery logic
└── README.md         ← This file: theory + viva prep
```

---

## 🖥️ Expected Output (What to Show the Examiner)

Open `index.html` in browser. You'll see:

1. **Header** with page title + 3 colored buttons: 🌑 Dark Cyber | 🌿 Warm Paper | 🌱 Mint Fresh
2. **Student Registration Form** — inputs, select dropdown, textarea, submit button
3. **Marks Table** — with header row, alternating row colors, Pass badges
4. **Course List** — styled bullet list
5. **About section** — paragraphs + "Active Theme" dot indicator

### Demonstrate to examiner:
- Click **🌿 Warm Paper** → entire page turns warm/orange/serif — ALL controls change
- Click **🌱 Mint Fresh** → entire page turns green/clean — ALL controls change
- Click **🌑 Dark Cyber** → back to dark purple — ALL controls change
- Show the **Active Theme dot** updates each time
- Open browser **DevTools → Console** (F12) → show `jQuery version loaded: 3.7.1`
- Show each form control (input, select, textarea, table, button) all change together

---

## 📖 THEORY — WT Syllabus Mapped

### 1. What is jQuery? (Unit-II)
jQuery is a **fast, small JavaScript library** (85KB minified) created by John Resig in 2006.
It simplifies:
- Selecting and manipulating HTML elements (DOM)
- Handling events (clicks, hovers, keypresses)
- Animating elements
- Making AJAX requests

**Tagline**: "Write less, do more."

Without jQuery (plain JS):
```javascript
document.querySelectorAll('.theme-btn').forEach(btn => {
  btn.addEventListener('click', function() { ... });
});
```

With jQuery:
```javascript
$('.theme-btn').on('click', function() { ... });
```

---

### 2. Loading jQuery (Unit-II: "Loading jQuery")
Three ways to load jQuery:

**A) CDN (used in this lab):**
```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
```
- No install needed
- Fast (cached by browser from previous visits)

**B) Download and host locally:**
```html
<script src="js/jquery.min.js"></script>
```

**C) npm (for Node/React projects):**
```bash
npm install jquery
```

---

### 3. jQuery Selectors (Unit-II: "Selecting elements")

| Selector | jQuery | Selects |
|---|---|---|
| ID | `$('#myId')` | One element with id="myId" |
| Class | `$('.myClass')` | All elements with class="myClass" |
| Tag | `$('p')` | All `<p>` elements |
| All | `$('*')` | Every element |
| Descendant | `$('table td')` | All `<td>` inside any table |
| Multiple | `$('h1, h2, h3')` | All heading elements |

---

### 4. Changing Styles with jQuery (Unit-II: "Changing styles")

**`.css(property, value)`** — sets inline CSS:
```javascript
$('body').css('background-color', '#0a0a0f');
$('p').css('font-size', '16px');
```

**`.addClass(className)`** — adds a CSS class:
```javascript
$('body').addClass('theme-dark');
```

**`.removeClass(className)`** — removes a CSS class:
```javascript
$('body').removeClass('theme-dark');
```

**`.toggleClass(className)`** — adds if absent, removes if present.

**In this lab**: we use `removeClass + addClass` on `<body>`. Since ALL elements inherit CSS from body, one class swap reskins the ENTIRE page.

---

### 5. Handling Events (Unit-II: "Handling events")
```javascript
$('.theme-btn').on('click', function() {
  // $(this) = the element that triggered the event
  const theme = $(this).data('theme');
});
```

- `.on(event, callback)` — modern way to attach event listeners
- `$(this)` — wraps the event-triggering element with jQuery
- `.data('theme')` — reads `data-theme="..."` HTML attribute

Old way (deprecated): `.click()`, `.bind()`
Modern way: `.on('click', ...)` ← always use this

---

### 6. jQuery DOM Manipulation (Unit-II: "Manipulating DOM")

| Method | Purpose |
|---|---|
| `.text('value')` | Set text content |
| `.html('<b>Hi</b>')` | Set HTML content |
| `.val('value')` | Set form input value |
| `.css(prop, val)` | Set inline style |
| `.addClass()` | Add CSS class |
| `.removeClass()` | Remove CSS class |
| `.attr('name', val)` | Set HTML attribute |
| `.fadeIn(ms)` | Fade element in |
| `.fadeOut(ms)` | Fade element out |
| `.hide()` / `.show()` | Hide/show element |

---

### 7. CSS Classes for Theming (Unit-I: CSS)
This lab uses **class-based theming**:
- Three CSS rule sets: `.theme-dark`, `.theme-warm`, `.theme-mint`
- Each class defines colors, fonts, and borders for ALL elements
- jQuery swaps the class on `<body>` — children inherit automatically
- CSS `transition` property makes the change animate smoothly

```css
body.theme-dark { background: #0a0a0f; color: #e2e2f0; font-family: monospace; }
body.theme-warm { background: #fdf6ec; color: #3b2f2f; font-family: Georgia; }
body.theme-mint { background: #f0faf5; color: #1a3d30; font-family: Trebuchet MS; }
```

---

### 8. $(document).ready() (Unit-II)
```javascript
$(document).ready(function() {
  // Safe to use DOM here
});
// Shorthand:
$(function() { ... });
```

Ensures jQuery code runs AFTER the HTML is fully parsed.
Without it, selectors might fail because elements don't exist yet.

---

## ❓ LIKELY VIVA QUESTIONS + ANSWERS

**Q1. What is jQuery and why do we use it?**
A: jQuery is a JavaScript library that simplifies DOM manipulation, event handling, animations, and AJAX. We use it because it reduces code verbosity — tasks that need 5+ lines in plain JS take 1–2 lines in jQuery.

---

**Q2. How do you load jQuery in a project?**
A: Via CDN using a `<script src="https://...jquery.min.js"></script>` tag in the HTML head or before the closing body tag. Alternatively, you can download the file and host it locally, or install via npm.

---

**Q3. What is the `$` symbol in jQuery?**
A: `$` is a shorthand alias for the `jQuery` function. `$('.btn')` is exactly the same as `jQuery('.btn')`. It selects DOM elements and returns a jQuery object with methods to manipulate them.

---

**Q4. How does changing one class on `<body>` restyle the ENTIRE page?**
A: CSS inheritance — child elements inherit color, font-family, and other inheritable properties from their parent. Since `<body>` is the root of all visible content, adding a theme class there allows all CSS rules scoped under `.theme-dark body input`, `.theme-dark h1`, etc. to cascade down automatically.

---

**Q5. Explain `.removeClass().addClass()` chaining.**
A: jQuery supports method chaining — each method returns the same jQuery object, so you can call another method on it immediately. `$('body').removeClass('theme-dark').addClass('theme-warm')` first removes the old class, then adds the new one, in one line.

---

**Q6. What is `$(this)` inside a click handler?**
A: Inside a jQuery event handler, `this` is the native DOM element that triggered the event. Wrapping it with `$()` gives you a jQuery object so you can use jQuery methods on it, like `$(this).data('theme')` or `$(this).addClass('active')`.

---

**Q7. What is `.data()` in jQuery?**
A: `.data('key')` reads the value of a `data-key="..."` HTML attribute on the element. Example: `<button data-theme="theme-dark">` → `$(btn).data('theme')` returns `"theme-dark"`. It's a clean way to store metadata in HTML.

---

**Q8. What is the difference between `.css()` and `.addClass()`?**
A: `.css()` sets inline styles directly on the element (higher specificity, harder to override). `.addClass()` adds a CSS class, keeping styles in the stylesheet (better separation of concerns, easier to maintain). For theming, `.addClass()` is preferred.

---

**Q9. What is `$(document).ready()` and why is it needed?**
A: It defers execution of jQuery code until the DOM is fully parsed. If you try to select `$('#myBtn')` before the button HTML exists, it returns an empty set and event binding fails silently. `$(document).ready()` prevents this.

---

**Q10. What are the three jQuery selectors used in this lab?**
A: ID selector `$('#themeLabel')` — selects one element by its id. Class selector `$('.theme-btn')` — selects all elements with that class. Chained on the result: `.on('click', ...)`, `.addClass()`, `.removeClass()`, `.text()`, `.css()`, `.fadeOut()`, `.fadeIn()`.

---

**Q11. How is jQuery different from plain JavaScript DOM manipulation?**
A: Plain JS requires `document.querySelectorAll()`, then `forEach()`, then `addEventListener()` — verbose and inconsistent across browsers. jQuery normalizes this: `$('.btn').on('click', fn)` works identically in all browsers. jQuery also provides built-in animation methods like `fadeIn/fadeOut`.

---

**Q12. What is CSS specificity and how does it relate to theme switching?**
A: Specificity determines which CSS rule wins when multiple rules target the same element. `body.theme-dark h1` has higher specificity than just `h1`, so the theme class reliably overrides base styles. This is why the scoped selector pattern `body.theme-X element` works correctly.

---

**Q13. What does `.fadeOut(120).fadeIn(300)` do?**
A: jQuery animation chain. `.fadeOut(120)` fades the element to invisible in 120ms. `.fadeIn(300)` fades it back in over 300ms. Together they create a brief blink/flash that signals to the user that the theme changed. These are jQuery's built-in effect methods.

---

**Q14. What is the difference between `.on('click', fn)` and `onclick="fn()"`?**
A: `onclick="..."` is inline HTML event handling (old way, mixes HTML and JS). `.on('click', fn)` is unobtrusive JavaScript — event handling is separated from HTML markup. jQuery's `.on()` also supports event delegation and can attach multiple handlers to the same event.

---

*Prepared for WT Lab Q23 — jQuery Dynamic Style Switcher*
