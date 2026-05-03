# 📘 Lab Q22 — HTML5 Canvas Drawing App
## Web Technology Subject — Complete Notes

---

## ⚙️ HOW TO OPEN IN VS CODE

1. Extract the ZIP file anywhere on your computer
2. Right-click the `lab22_canvas_drawing` folder → "Open with VS Code"
3. Inside VS Code, open the file `index.html`
4. Press **Go Live** button at the bottom right (requires Live Server extension)
   OR simply double-click `index.html` to open in browser directly.

> ✅ No npm install, no server needed — this is pure HTML/CSS/JS!

---

## 📁 File Structure

```
lab22_canvas_drawing/
│
├── index.html        ← The entire app (HTML + CSS + JS in one file)
└── README.md         ← This file (theory + viva notes)
```

---

## 🖥️ Expected Output (What to Show Examiner)

1. A dark-themed drawing app opens in the browser.
2. **Left panel** shows tool buttons: Line, Rectangle, Circle, Freehand, Point.
3. **Canvas area** (large, 16:9) is where you draw.
4. **Status bar** below canvas shows live X/Y mouse coordinates and shape count.

### Demonstrate:
- Click "Line" → click-drag on canvas → a line is drawn
- Click "Rectangle" → click-drag → a filled rectangle appears
- Click "Circle" → drag → circle drawn
- Click "Freehand" → hold mouse and scribble
- Click "• Point" → single click → dot placed
- Change **Stroke Color** and **Fill Color** using color pickers
- Drag **Line Width** slider to change thickness
- Click **✕ Clear Canvas** to wipe everything

---

## 📖 THEORY — WT Syllabus Mapped

### 1. HTML5 `<canvas>` Element (Unit-I)
- Introduced in **HTML5** as a new semantic element.
- Provides a **bitmap drawing surface** inside the browser.
- By itself, `<canvas>` is blank — JavaScript is required to draw on it.
- Syntax: `<canvas id="myCanvas" width="800" height="600"></canvas>`
- Width and height are the **drawing buffer resolution** (not CSS size).

### 2. Canvas 2D Context API (Unit-II — DOM)
JavaScript accesses the canvas through its **rendering context**:
```javascript
const ctx = canvas.getContext('2d');
```
The `ctx` object exposes drawing methods:

| Method | Purpose |
|---|---|
| `beginPath()` | Start a new path (clears previous path) |
| `moveTo(x, y)` | Move pen to x,y without drawing |
| `lineTo(x, y)` | Draw line from current position to x,y |
| `rect(x, y, w, h)` | Define rectangle path |
| `arc(cx, cy, r, start, end)` | Draw arc/circle path |
| `stroke()` | Render the path outline |
| `fill()` | Fill the path interior |
| `clearRect(x, y, w, h)` | Erase a rectangular region |

### 3. Mouse Events (Unit-II — DOM Events)
JavaScript listens to browser events on DOM elements:
```javascript
canvas.addEventListener('mousedown', handler);
canvas.addEventListener('mousemove', handler);
canvas.addEventListener('mouseup', handler);
```
- `mousedown` → user presses mouse button → start drawing
- `mousemove` → mouse is moving → update preview
- `mouseup` → user releases button → finalize shape

The event object `e` has:
- `e.clientX`, `e.clientY` — mouse position from viewport
- Use `getBoundingClientRect()` to convert to canvas coordinates

### 4. Responsive Design (Unit-I — HTML5 + CSS)
- `<meta name="viewport">` makes the page mobile-friendly
- CSS Flexbox (`display: flex`) creates responsive layouts
- `flex-wrap: wrap` allows items to wrap on small screens
- `aspect-ratio: 16/9` maintains canvas proportions
- `@media (max-width: 700px)` applies styles only on small screens
- `clamp(min, preferred, max)` scales font sizes responsively

### 5. DOM Manipulation (Unit-II)
- `document.getElementById()` — get element by id
- `element.classList.add/remove()` — modify CSS classes
- `dataset.tool` — read custom HTML data attributes (`data-tool="line"`)
- `element.textContent` — update visible text

### 6. CSS Custom Properties / Variables (Unit-I — CSS)
```css
:root {
  --accent: #6c63ff;
}
.btn { color: var(--accent); }
```
Defined in `:root`, used with `var()` — enables consistent theming.

---

## ❓ LIKELY VIVA QUESTIONS + ANSWERS

**Q1. What is the HTML5 `<canvas>` element?**
A: `<canvas>` is an HTML5 element that provides a drawable bitmap surface in the browser. It is a container — drawing is done entirely via JavaScript using its 2D rendering context obtained with `canvas.getContext('2d')`.

---

**Q2. What is `getContext('2d')` and what does it return?**
A: It returns a `CanvasRenderingContext2D` object that has all the drawing methods and properties like `strokeStyle`, `fillStyle`, `lineWidth`, `beginPath()`, `stroke()`, `fill()`, etc.

---

**Q3. What is the difference between `stroke()` and `fill()`?**
A: `stroke()` renders only the outline/border of a path. `fill()` fills the inside of a path with the current `fillStyle` color.

---

**Q4. Why do we use two canvas elements (main + preview)?**
A: The main canvas stores permanent shapes. The preview canvas sits on top and shows a real-time preview while the user drags. On each `mousemove`, we clear the preview and redraw the current shape. On `mouseup`, the final shape is committed to the main canvas. This way old shapes don't get erased during preview.

---

**Q5. Explain mousedown, mousemove, mouseup in context of drawing.**
A: 
- `mousedown` → records start coordinates, sets `isDrawing = true`
- `mousemove` → if `isDrawing`, updates the live preview
- `mouseup` → records end coordinates, draws final shape, sets `isDrawing = false`

---

**Q6. What is `beginPath()` and why is it important?**
A: `beginPath()` clears the current path buffer in the context. Without it, new drawing commands get added to the same path — causing incorrect shapes when `stroke()` or `fill()` is called.

---

**Q7. How do you draw a circle using Canvas API?**
A: Using `arc(cx, cy, radius, 0, Math.PI * 2)`. The last two parameters are start and end angles in radians. `2 * Math.PI` = 360° = full circle.

---

**Q8. What is `clearRect()` and how is it used here?**
A: `clearRect(x, y, width, height)` erases the pixels in the given rectangle. We use `clearRect(0, 0, canvas.width, canvas.height)` to clear the entire canvas — both for the Clear button and to reset the preview canvas on each `mousemove`.

---

**Q9. What are CSS media queries?**
A: `@media (max-width: 700px) { ... }` applies CSS rules only when the screen width matches the condition. Used to make the toolbar wrap below the canvas on mobile — making the layout responsive.

---

**Q10. What is `getBoundingClientRect()` and why is it needed?**
A: It returns the position and size of an element relative to the browser viewport. Used to convert `e.clientX/Y` (mouse position from viewport) into canvas-relative coordinates by subtracting the canvas's `left` and `top` values.

---

**Q11. What is the difference between canvas and SVG?**
A: Canvas is pixel-based (raster). Once drawn, shapes have no DOM representation and can't be individually selected. SVG is vector-based; each shape is a DOM element that can be styled and interacted with. Canvas is better for games/pixel art; SVG is better for scalable icons/charts.

---

**Q12. What is `pointer-events: none` used for on the preview canvas?**
A: It makes the preview canvas transparent to mouse events — clicks and moves pass through it to the main canvas below. Without this, the preview canvas would "intercept" all mouse events and drawing on the main canvas would break.

---

**Q13. Explain `addEventListener` and event-driven programming.**
A: Event-driven programming means code runs in response to user actions (events). `addEventListener(eventName, callbackFn)` registers a function to be called when the specified event fires on an element. The browser calls the callback automatically — we don't call it directly.

---

**Q14. What is `data-*` attribute in HTML?**
A: Custom HTML5 attributes that store extra data on elements: `data-tool="line"`. Accessed in JS via `element.dataset.tool`. Used to associate metadata with DOM elements without misusing standard attributes.

---

**Q15. What is `aspect-ratio` CSS property?**
A: Maintains a proportional width-to-height ratio. `aspect-ratio: 16/9` means for every 16px of width, height is 9px. Used here to keep the canvas proportional on all screen sizes.

---

*Prepared for WT Lab Q22 — HTML5 Canvas Mouse Event Drawing*
