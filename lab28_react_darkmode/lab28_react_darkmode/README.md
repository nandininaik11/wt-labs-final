Let me check this new project to see what scripts are available:Perfect! This project is **different** - it **doesn't use npm**! 🎉

This is a **standalone HTML file** that loads React from CDN (a web link), so you can run it directly without any build tools.

## 🚀 How to Run It:

### **Option 1: Using Live Server in VS Code (Recommended)**

1. Open the `lab28_react_darkmode` folder in VS Code
2. If you don't have **Live Server** extension:
   - Press `Ctrl+Shift+X` (Extensions)
   - Search for "Live Server"
   - Install it
3. Right-click on `index.html`
4. Click **"Open with Live Server"**

The page will open in your browser at `http://127.0.0.1:5500` or similar.

---

### **Option 2: Just open in browser**

Simply double-click `index.html` and it will open in your default browser. 

---

### **Why no npm?**

This project uses:
```html
<script src="https://unpkg.com/react@18/umd/react.development.js"></script>
```

React is loaded from the internet (CDN), so there's no need for:
- ❌ `npm install`
- ❌ `npm start`
- ❌ `npm run dev`
- ❌ node_modules folder

This is a **beginner-friendly approach** - perfect for learning React concepts without dealing with build tools! 

Just use Live Server or open the HTML file directly. 🎨




# 📘 Lab Q28 — React Dark Mode Toggle using useState
## Web Technology — Complete Notes

---

## ⚙️ HOW TO OPEN IN VS CODE

### Option A — Direct open (simplest, no npm needed):
1. Extract the ZIP anywhere
2. Open VS Code → File → Open Folder → select `lab28_react_darkmode`
3. Double-click `index.html` in the Explorer
4. Press **Go Live** (Live Server extension) OR right-click → Open with Live Server
   - If Live Server not installed: Extensions (Ctrl+Shift+X) → search "Live Server" → Install

### Option B — npm Vite (shows real project setup for examiner):
If you want to impress the examiner with a real Vite setup:
```bash
# In VS Code terminal (Ctrl+`)
npm create vite@latest my-app -- --template react
cd my-app
npm install
npm run dev
```
Then copy the App.jsx content from `src/App.jsx` in this project.

> ✅ The provided `index.html` uses React via CDN — works by simply opening in browser, no npm needed.

---

## 📁 File Structure

```
lab28_react_darkmode/
│
├── index.html       ← Complete React app (HTML + CSS + JSX all in one)
└── README.md        ← This file: theory + viva prep

--- In a real Vite project it would look like: ---
my-react-app/
├── public/
│   └── index.html
├── src/
│   ├── App.jsx        ← Main component (useState, useEffect here)
│   ├── main.jsx       ← ReactDOM.createRoot().render(<App />)
│   └── App.css        ← Component styles
├── package.json       ← npm dependencies
└── vite.config.js     ← Vite build config
```

---

## 🖥️ Expected Output (What to Show the Examiner)

### On page load:
- Page opens in **Light Mode** (warm cream background)
- Header shows: "☀️ React Theme Switcher"
- Hero shows big ☀️ icon + text "Currently in **Light Mode**"
- Toggle button: 🌙 Switch to Dark Mode
- Theme banner: "Active Theme: Light Mode ☀️" + **LIGHT** pill

### After clicking toggle button:
- Page **instantly switches** to Dark Mode (deep dark background)
- ALL elements change: bg, text, cards, inputs, table, footer
- Hero shows 🌕 icon + "Currently in **Dark Mode**"
- Toggle button changes to: ☀️ Switch to Light Mode
- Theme banner shows: "Active Theme: Dark Mode 🌙" + **DARK** pill

### State Inspector table updates live:
| Variable | Value |
|---|---|
| isDark | **true** (was false) |
| toggleCount | 1, 2, 3... increments each click |
| clock | live ticking time |
| localStorage | **"dark"** (was "light") |

### Task 5 — Persistence demo:
1. Switch to Dark Mode
2. Press **F5** (refresh page)
3. Page loads in Dark Mode — theme was saved to localStorage!

### DevTools → Application → localStorage:
- Key: `labTheme`, Value: `"dark"` or `"light"`

### DevTools → Console — type:
```javascript
localStorage.getItem('labTheme')  // → "dark"
```

---

## 📖 THEORY — WT Syllabus Mapped

### 1. What is React? (Unit-V: Introduction to React)
React is a **JavaScript library** for building user interfaces, created by Meta (Facebook) in 2013.

Core ideas:
- **Component-based**: UI is split into small, reusable pieces (components)
- **Declarative**: You describe *what* the UI should look like; React figures out *how* to update the DOM
- **Virtual DOM**: React keeps a lightweight copy of the DOM in memory and only updates what changed (efficient)
- **Unidirectional data flow**: Data flows from parent to child via props

---

### 2. JSX (Unit-V: JSX)
JSX = JavaScript XML. Lets you write HTML-like code inside JavaScript.

```jsx
// JSX (what you write):
function App() {
  return <h1 className="title">Hello World</h1>;
}

// What Babel compiles it to (plain JS):
function App() {
  return React.createElement('h1', { className: 'title' }, 'Hello World');
}
```

Key JSX rules:
- Use `className` not `class` (class is a reserved JS keyword)
- Use `htmlFor` not `for`
- Every component must return ONE root element (or use `<>...</>` fragment)
- JavaScript expressions go inside `{ }`: `{isDark ? 'Dark' : 'Light'}`
- `style={{ }}` takes a JS object: `style={{ color: 'red', fontSize: '16px' }}`

---

### 3. React Components (Unit-V: React component)
A component is a **JavaScript function** that returns JSX.

```jsx
// Functional component (modern way):
function Greeting({ name }) {           // `name` is a PROP
  return <h1>Hello, {name}!</h1>;
}

// Usage (like an HTML tag):
<Greeting name="Rahul" />
```

Types:
- **Functional components** (used in this lab) — simple functions with hooks
- **Class components** (old way) — extend React.Component, use this.state

---

### 4. useState Hook — CORE CONCEPT (Unit-V: State)
```jsx
const [isDark, setIsDark] = useState(false);
//     ↑               ↑                ↑
//   state value    setter fn    initial value
```

**How it works:**
1. `useState(false)` creates a state variable `isDark` with initial value `false`
2. `setIsDark(newValue)` updates the state AND triggers a re-render
3. After re-render, `isDark` has the new value everywhere in the component
4. State is **preserved between re-renders** (unlike regular variables)

**Why not use a regular variable?**
```jsx
// ❌ WRONG: regular variable — React doesn't know it changed
let theme = 'light';
function toggle() { theme = 'dark'; }  // UI won't update!

// ✅ CORRECT: useState — React re-renders when setIsDark() is called
const [isDark, setIsDark] = useState(false);
function toggle() { setIsDark(true); }  // UI updates automatically!
```

---

### 5. useEffect Hook (Unit-V: Component lifecycle)
```jsx
useEffect(function() {
  // side effect code here
  localStorage.setItem('labTheme', isDark ? 'dark' : 'light');
}, [isDark]);   // runs when isDark changes
```

| Dependency array | When it runs |
|---|---|
| No array | After EVERY render |
| `[]` empty | Once after first render (componentDidMount) |
| `[isDark]` | After first render + whenever isDark changes |

**Lifecycle equivalent:**
- `useEffect(fn, [])` ≈ `componentDidMount`
- `useEffect(fn, [dep])` ≈ `componentDidUpdate`
- `return () => cleanup()` ≈ `componentWillUnmount`

---

### 6. Props (Unit-V: Props, Pass data parent to child)
Props = data passed from parent component to child component.

```jsx
// Parent passes props:
<ThemeToggle isDark={isDark} onToggle={handleToggle} theme={theme} />

// Child receives and uses them:
function ThemeToggle({ isDark, onToggle, theme }) {
  return (
    <button onClick={onToggle} style={{ color: theme.btnText }}>
      {isDark ? 'Go Light' : 'Go Dark'}
    </button>
  );
}
```

Props are **read-only** in the child — the child can't modify them directly.
To send data from child to parent: pass a callback function as a prop.

---

### 7. Dynamic Styling (Unit-V: Component styling, Task 3)
Two ways to style React components:

**A) Inline style (used in this lab):**
```jsx
// style prop takes a JavaScript OBJECT (note double braces)
<div style={{ backgroundColor: isDark ? '#000' : '#fff', color: theme.text }}>
```

**B) CSS class switching:**
```jsx
// Add/remove CSS class based on state
<div className={isDark ? 'dark-mode' : 'light-mode'}>
```

---

### 8. localStorage — Persistence (Task 5)
```javascript
// SAVE — writes a string to browser storage (persists across refreshes)
localStorage.setItem('labTheme', 'dark');

// READ — retrieves the value (null if key doesn't exist)
const saved = localStorage.getItem('labTheme');  // → "dark"

// DELETE
localStorage.removeItem('labTheme');
```

Used with useState lazy initializer:
```jsx
const [isDark, setIsDark] = useState(
  () => localStorage.getItem('labTheme') === 'dark'
  // Arrow function: only called once on mount (efficient)
);
```

---

### 9. Virtual DOM and Re-rendering (Unit-V: React component)
1. State changes → `setIsDark(!isDark)` called
2. React creates a new **Virtual DOM tree** from the updated JSX
3. React **diffs** (compares) the new tree with the previous one
4. React only updates the **real DOM nodes that actually changed**
5. This is much faster than manually updating every element like jQuery does

---

## ❓ LIKELY VIVA QUESTIONS + ANSWERS

**Q1. What is React and why is it used?**
A: React is a JavaScript library by Meta for building component-based UIs. It uses a Virtual DOM for efficient updates, supports one-way data flow via props, and uses hooks (useState, useEffect) to manage state and side effects. It makes UIs predictable and easy to maintain.

---

**Q2. What is useState and how does it work?**
A: `useState(initialValue)` is a React Hook that adds state to functional components. It returns an array of two items: the current state value and a setter function. Calling the setter with a new value triggers a re-render. Without hooks, you'd need class components with `this.state` and `this.setState()`.

---

**Q3. What is a React Hook?**
A: Hooks are functions that let functional components use React features like state and lifecycle. Rules: (1) Only call hooks at the top level — not inside loops/conditions. (2) Only call inside React functions — not regular JS functions. Common hooks: `useState`, `useEffect`, `useRef`, `useContext`.

---

**Q4. What is the difference between state and props?**
A: Props are read-only data passed from parent to child — a child cannot modify its own props. State is local data managed inside a component — it can change via setState and causes re-renders. Props = external input. State = internal memory.

---

**Q5. What is JSX?**
A: JSX is a syntax extension for JavaScript that looks like HTML. Babel compiles it to `React.createElement()` calls. You use it to describe UI structure. Key differences from HTML: `className` instead of `class`, `htmlFor` instead of `for`, `style={{ }}` takes a JS object, and JS expressions go inside `{ }`.

---

**Q6. How does the dark mode toggle work exactly?**
A: 1. `useState(false)` initializes `isDark` as false (light mode). 2. The toggle button's `onClick` calls `setIsDark(prev => !prev)`. 3. React re-renders the App component with `isDark = true`. 4. All `style={{ backgroundColor: theme.bg }}` values now read from `THEMES.dark` instead of `THEMES.light`. 5. The entire page updates simultaneously.

---

**Q7. What is localStorage and how is it used for persistence (Task 5)?**
A: `localStorage` is a browser API that stores key-value string pairs that persist across page refreshes and even browser restarts. We use `localStorage.setItem('labTheme', 'dark')` inside `useEffect` whenever `isDark` changes, and read it back with `localStorage.getItem('labTheme')` as the initial value for `useState`.

---

**Q8. What is useEffect and when does it run?**
A: `useEffect(callback, [deps])` runs the callback after the component renders. With `[isDark]` as dependency, it runs after first render and every time `isDark` changes. It's used for side effects: API calls, DOM changes, subscriptions, localStorage. Return a cleanup function to run on unmount.

---

**Q9. What is the Virtual DOM?**
A: The Virtual DOM is a JavaScript object that is a lightweight copy of the real DOM. When state changes, React creates a new Virtual DOM tree, compares (diffs) it to the previous one, and only updates the real DOM nodes that actually changed. This is much faster than frameworks that re-render everything (like jQuery replacing innerHTML).

---

**Q10. What is conditional rendering in React?**
A: Rendering different JSX based on a condition. Methods: (1) Ternary: `{isDark ? <Moon /> : <Sun />}`. (2) && operator: `{isDark && <DarkIcon />}`. (3) if/else before return. Used in this lab to show different icons, text, and styles based on the `isDark` boolean state.

---

**Q11. What are props and how are they passed?**
A: Props are arguments passed to components like HTML attributes: `<ThemeToggle isDark={isDark} onToggle={handleToggle} />`. Inside the child, they're received as a function parameter: `function ThemeToggle({ isDark, onToggle })`. They flow only downward (parent → child). To pass data upward, pass a callback function as a prop.

---

**Q12. Why use `setIsDark(prev => !prev)` instead of `setIsDark(!isDark)`?**
A: Using the updater function form `prev => !prev` is safer because it uses the latest state value (not a stale closure). If multiple state updates happen in the same event cycle, React batches them and the functional form guarantees you're working with the most recent value, preventing race conditions.

---

*Prepared for WT Lab Q28 — React useState Dark Mode Toggle*
