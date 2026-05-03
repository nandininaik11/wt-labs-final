# 📘 Lab Q25 — Responsive Traffic Signal Lights
## Web Technology Subject — Complete Notes

---

## ⚙️ HOW TO OPEN IN VS CODE

1. Extract the ZIP anywhere on your computer
2. Open VS Code → File → Open Folder → select `lab25_traffic_signal`
3. Click `index.html` in the Explorer panel
4. Press **Go Live** (Live Server extension, bottom-right of VS Code)
   OR just double-click `index.html` — opens directly in browser

> ✅ No npm install. No server. No dependencies. Pure HTML + CSS + JavaScript!

---

## 📁 File Structure

```
lab25_traffic_signal/
│
├── index.html     ← Complete app: HTML + CSS + JavaScript in one file
└── README.md      ← This file: theory + viva prep
```

---

## 🖥️ Expected Output (What to Show the Examiner)

Open `index.html` in browser. You will see:

### Scene (top section):
- **Dark night road background** (CSS gradient)
- **Two traffic signal poles** (Signal A + Signal B) side by side
- Each pole has a **black housing box** with 3 circular LEDs: Red (top), Yellow (middle), Green (bottom)
- LEDs that are **ON glow brightly** with colored halos (CSS box-shadow)
- LEDs that are **OFF appear dark/dim**
- Signal A and Signal B are in **opposite phases** (when A is RED, B is GREEN)
- White **dashed road markings** at the bottom

### Control Panel (bottom section):
- **Current phase status** (colored dot + text: "RED — STOP" etc.)
- **Progress bar** showing time remaining in current phase (shrinks as time passes)
- **Countdown timer** in seconds
- **Buttons**: Auto Cycle ▶ | Stop ⏹ | Force Red 🔴 | Force Yellow 🟡 | Force Green 🟢
- **Speed slider**: 0.5× to 3× (makes lights cycle faster/slower)
- **Information table**: Color | Duration | Meaning | Action

### Cycle demo:
- Page auto-starts with RED phase (10 seconds)
- Automatically cycles: RED → GREEN → YELLOW → RED...
- Click "Force Green" → both signals instantly switch to green/red
- Drag speed slider to 3× → lights cycle much faster
- Click "Stop" → cycle pauses on current phase

### Open DevTools Console (F12):
```
Lab 25: Traffic Signal initialized
Phases: ['red', 'green', 'yellow']
Durations: 10000ms RED, 3000ms YELLOW, 10000ms GREEN
```

### Responsive demo:
- Resize browser window to mobile width → signals stack nicely, buttons wrap

---

## 📖 THEORY — WT Syllabus Mapped

### 1. HTML5 Structure (Unit-I)
Every HTML5 page has this structure:
```html
<!DOCTYPE html>          <!-- declares HTML5 -->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" .../>   <!-- responsive -->
    <title>Page Title</title>
    <style>...</style>             <!-- CSS here -->
  </head>
  <body>
    <!-- visible content here -->
    <script>...</script>           <!-- JS at bottom -->
  </body>
</html>
```

HTML elements used in this lab:
- `<header>` — semantic header section
- `<div>` — generic container
- `<table>`, `<thead>`, `<tbody>`, `<tr>`, `<th>`, `<td>` — tabular data
- `<button>` — clickable buttons with onclick handlers
- `<input type="range">` — slider control
- `<span>` — inline text wrapper

---

### 2. CSS Glowing LED Effect (Unit-I: CSS)
The "glow" effect is created entirely with CSS `box-shadow`:

```css
/* Multiple box-shadows create layers of glow */
.led.red-active {
  background: radial-gradient(circle at 35% 35%, #ff6060, #ff2020);
  box-shadow:
    0 0 20px 8px rgba(255,32,32,0.75),    /* inner tight glow */
    0 0 50px 15px rgba(255,0,0,0.3),      /* wide soft glow */
    inset 0 2px 8px rgba(255,200,200,0.4); /* inner highlight */
}
```

`box-shadow: h-offset v-offset blur spread color`:
- h/v offset = 0 → centered glow (all directions)
- blur = how soft/fuzzy the glow is
- spread = how far the glow extends
- `inset` → shadow is inside the element

`radial-gradient` creates a circular light source look (brighter at center).

---

### 3. CSS Animations / @keyframes (Unit-I: CSS)
```css
@keyframes pulse-red {
  0%, 100% { box-shadow: 0 0 20px 8px rgba(255,32,32,0.75); }
  50%       { box-shadow: 0 0 35px 16px rgba(255,32,32,0.9); }
}

.led.red-active {
  animation: pulse-red 1.2s ease-in-out infinite;
}
```

- `@keyframes` defines animation steps (0% = start, 100% = end)
- `animation` shorthand: name duration timing-function iteration-count
- `infinite` = loops forever
- The glow expands at 50% and contracts back → pulsing heartbeat effect

---

### 4. CSS Responsive Design (Unit-I)
Three techniques used:

**A) Flexbox:**
```css
.scene {
  display: flex;           /* enables flexbox */
  flex-wrap: wrap;         /* items wrap to new row on small screens */
  justify-content: center; /* center horizontally */
  align-items: flex-end;   /* align to bottom */
  gap: 80px;               /* space between items */
}
```

**B) clamp() — responsive sizing:**
```css
font-size: clamp(1rem, 4vw, 2rem);
/* min=1rem, preferred=4% of viewport width, max=2rem */
/* scales smoothly with screen size */
```

**C) @media queries:**
```css
@media (max-width: 600px) {
  .btn { padding: 8px 14px; font-size: 0.8rem; }
}
/* Applied only when screen is 600px wide or less */
```

---

### 5. JavaScript DOM Manipulation (Unit-II)
**Core concept**: JavaScript talks to HTML elements through the DOM (Document Object Model).

```javascript
// GET element by ID
const led = document.getElementById('r1');

// ADD a CSS class → turns LED "on" with glow
led.classList.add('red-active');

// REMOVE a CSS class → turns LED "off"
led.classList.remove('red-active');

// SET text content
document.getElementById('statusText').textContent = 'RED — STOP';

// SET inline CSS style
document.getElementById('timerBar').style.width = '75%';
document.getElementById('timerBar').style.background = '#ff2020';
```

---

### 6. JavaScript Timing Functions (Unit-II: Functions)

**setTimeout(fn, ms)** — runs function ONCE after delay:
```javascript
setTimeout(function() {
  nextPhase();
}, 10000);  // runs once after 10 seconds
```

**setInterval(fn, ms)** — runs function REPEATEDLY every ms:
```javascript
timerInterval = setInterval(function() {
  timeLeft -= 0.1;
  updateTimerDisplay();
}, 100);  // runs every 100ms = 10 times per second
```

**clearTimeout(id) / clearInterval(id)** — stops the timer:
```javascript
clearTimeout(autoInterval);
clearInterval(timerInterval);
```

In this lab: setTimeout schedules phase transitions. setInterval updates the progress bar 10 times per second for smooth animation.

---

### 7. JavaScript Arrays and Control Flow (Unit-II)
```javascript
const SEQUENCE = ['red', 'green', 'yellow'];  // Array of phases
let currentPhaseIdx = 0;

function nextPhase() {
  // Modulo (%) wraps index back to 0 after last element
  // 0 → 1 → 2 → 0 → 1 → 2 → ...
  currentPhaseIdx = (currentPhaseIdx + 1) % SEQUENCE.length;
  setPhase(SEQUENCE[currentPhaseIdx]);
}
```

---

### 8. JavaScript Objects (Unit-II: "Objects in JS")
```javascript
const CONFIG = {
  red:    { duration: 10000, label: 'RED — STOP',       barColor: '#ff2020' },
  yellow: { duration: 3000,  label: 'YELLOW — CAUTION', barColor: '#ffd700' },
  green:  { duration: 10000, label: 'GREEN — GO',       barColor: '#00e676' }
};

// Accessing object property:
CONFIG['red'].duration  // → 10000
CONFIG.green.label      // → 'GREEN — GO'
```

---

### 9. Traffic Signal Logic (opposite phases)
Real traffic signals at a 2-way intersection work oppositely:
- When one direction is RED (stop) → the other direction is GREEN (go)
- Both go YELLOW during transition

```javascript
if (phase === 'red') {
  document.getElementById('r1').classList.add('red-active');   // Signal A: RED
  document.getElementById('g2').classList.add('green-active'); // Signal B: GREEN
}
else if (phase === 'green') {
  document.getElementById('g1').classList.add('green-active'); // Signal A: GREEN
  document.getElementById('r2').classList.add('red-active');   // Signal B: RED
}
else if (phase === 'yellow') {
  document.getElementById('y1').classList.add('yellow-active'); // Both: YELLOW
  document.getElementById('y2').classList.add('yellow-active');
}
```

---

### 10. HTML Table (Unit-I: HTML elements — tables)
```html
<table>
  <thead>              <!-- header section -->
    <tr>               <!-- table row -->
      <th>Color</th>   <!-- header cell (bold) -->
      <th>Meaning</th>
    </tr>
  </thead>
  <tbody>              <!-- body section -->
    <tr>
      <td>RED</td>     <!-- data cell -->
      <td>STOP</td>
    </tr>
  </tbody>
</table>
```

`border-collapse: collapse` in CSS removes the double borders between cells.

---

## ❓ LIKELY VIVA QUESTIONS + ANSWERS

**Q1. What HTML element is the traffic light LED? How is the circle made?**
A: It's a `<div>` element. The circle shape is achieved with CSS `border-radius: 50%` on an element that has equal `width` and `height`. 50% border-radius makes a perfect circle.

---

**Q2. How is the glowing effect created?**
A: Using CSS `box-shadow` with zero horizontal/vertical offset so the shadow spreads equally in all directions. Multiple shadows are layered — a tight inner glow and a wider soft glow. `radial-gradient` background makes it look brighter at the center. A `@keyframes` animation pulsates the glow size to simulate a live LED.

---

**Q3. What is the difference between `setTimeout` and `setInterval`?**
A: `setTimeout(fn, ms)` runs the function ONCE after the delay. `setInterval(fn, ms)` runs the function REPEATEDLY every `ms` milliseconds until `clearInterval()` is called. In this lab, `setTimeout` schedules phase changes (each phase has different duration), and `setInterval` updates the countdown bar 10 times per second for smooth animation.

---

**Q4. What is `classList.add()` and `classList.remove()`?**
A: These are DOM methods that add or remove CSS class names from an element. Adding `'red-active'` class to an LED applies the red glow CSS rules. Removing it restores the dim/off appearance. `classList` is a `DOMTokenList` object that manages the space-separated class attribute of elements.

---

**Q5. What is the modulo operator `%` used for here?**
A: To wrap the phase index back to 0 after it reaches the last phase. `(2 + 1) % 3 = 0` — so after the last phase (index 2), it goes back to index 0. This creates the infinite loop: RED→GREEN→YELLOW→RED...

---

**Q6. What is CSS `@keyframes` and how does animation work?**
A: `@keyframes` defines the steps of a CSS animation. You specify property values at different percentages (0% = start, 100% = end). The `animation` property applies it to an element with name, duration, timing-function, and iteration-count. `ease-in-out` means it starts slow, speeds up, then slows down. `infinite` means it repeats forever.

---

**Q7. What makes this page responsive?**
A: Three techniques: (1) `<meta name="viewport">` tag enables mobile scaling. (2) CSS Flexbox with `flex-wrap: wrap` allows signal poles to stack on small screens. (3) `clamp(min, preferred, max)` scales font sizes and element dimensions proportionally. (4) `@media (max-width: 600px)` applies smaller styles on mobile.

---

**Q8. What is `querySelectorAll()` vs `getElementById()`?**
A: `getElementById('id')` returns one specific element by its id attribute. `querySelectorAll('.class')` returns a NodeList of ALL elements matching a CSS selector. Used here to clear all active classes from all LEDs before activating the new ones.

---

**Q9. Explain the IIFE pattern `(function init() { ... })()`.**
A: IIFE = Immediately Invoked Function Expression. It's a function that defines and calls itself at the same time. Used for initialization code that should run once when the script loads, without polluting the global scope with variables. The `()` at the end triggers immediate execution.

---

**Q10. What is `box-shadow: inset` vs regular `box-shadow`?**
A: Regular `box-shadow` draws the shadow OUTSIDE the element (creates the outer glow). `inset` draws the shadow INSIDE the element (creates inner depth/highlight). In the LED, `inset` creates a subtle highlight that makes it look like a physical convex bulb with a bright spot.

---

**Q11. What standard traffic light cycle is used and why?**
A: The standard cycle is RED → GREEN → YELLOW → RED. RED is shown first and has the longest duration (10s) because stopping traffic safely is the most important. YELLOW is shortest (3s) — it's just a warning that RED is coming. GREEN is equal to RED (10s) to give vehicles time to move. This matches real-world traffic signal timing.

---

**Q12. How does the speed slider work in JavaScript?**
A: The `<input type="range">` slider fires an `oninput` event on change. The handler reads the value as a `speedMultiplier`. The phase duration is divided by this multiplier: `duration / speedMult`. At 2× speed, a 10-second phase becomes 5 seconds. `parseFloat()` converts the string value from the input to a JavaScript number.

---

*Prepared for WT Lab Q25 — Responsive Traffic Signal Lights*
