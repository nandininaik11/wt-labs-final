# ⚛️ Currency Converter — ReactJS
### Lab Question 16 | Web Technology | USD → INR Converter

---

## 📁 FILE STRUCTURE
```
currency-converter/
├── index.html                    → Single HTML file (React SPA entry point)
├── package.json                  → Project metadata + npm dependencies
├── vite.config.js                → Vite bundler configuration
│
└── src/
    ├── main.jsx                  → Mounts React app to DOM
    ├── App.jsx                   → Root component (layout)
    ├── App.css                   → All component styles
    ├── index.css                 → Global reset styles
    │
    └── components/
        ├── CurrencyConverter.jsx → Main component (state + events + logic)
        └── ConversionHistory.jsx → Child component (displays history via props)
```

---

## ⚙️ SETUP & RUN COMMANDS

### Prerequisites
1. Install **Node.js** from: https://nodejs.org (choose LTS version)
   → Node.js includes **npm** (Node Package Manager) automatically
   → Verify: open terminal → type `node -v` and `npm -v` → should show version numbers

### Step-by-Step Setup

**Step 1: Extract the ZIP**
Extract to any folder, e.g., Desktop → `currency-converter/`

**Step 2: Open in VS Code**
```
File → Open Folder → Select the 'currency-converter' folder
```

**Step 3: Open Terminal in VS Code**
```
Terminal → New Terminal  (or press Ctrl + `)
```

**Step 4: Install Dependencies**
```bash
npm install
```
→ This reads `package.json` and downloads React, Vite, and all needed packages
→ Creates a `node_modules/` folder (DO NOT edit this folder)
→ Takes 30-60 seconds on first run

**Step 5: Start Development Server**
```bash
npm run dev
```
→ Vite starts a local server
→ You'll see output like:
```
  VITE v4.x.x  ready in 300ms
  ➜  Local:   http://localhost:5173/
  ➜  Network: http://192.168.x.x:5173/
```

**Step 6: Open in Browser**
```
http://localhost:5173
```

**Step 7: Stop the Server**
```
Press Ctrl + C in the terminal
```

### Build for Production (optional)
```bash
npm run build
```
Creates an optimized `dist/` folder that can be deployed to any web server.

---

## 🖥️ EXPECTED OUTPUT (Show the Examiner This)

### Screen Layout:
```
┌────────────────────────────────────────┐
│  🇺🇸 Currency Converter 🇮🇳            │
│  Real-time USD → INR conversion        │
│                          [🌙 Dark Mode] │
│  📊 Live Rate: 1 USD = ₹83.50 INR     │
│                                        │
│  [INR] [EUR] [GBP] [JPY]  ← tabs      │
│                                        │
│  💵 Enter Amount in USD               │
│  ┌──┬────────────────────┐            │
│  │$ │   100              │            │
│  └──┴────────────────────┘            │
│                                        │
│  [🔄 Convert to INR] [🗑️ Clear]        │
│                                        │
│  ┌──────────────────────────────────┐  │
│  │ 🇺🇸 $100 USD  →  🇮🇳 ₹8,350 INR │  │
│  └──────────────────────────────────┘  │
│  Exchange Rate: 1 USD = ₹83.50         │
│                                        │
│  📜 Conversion History                 │
│  #1 $100 → ₹8,350.00  🕐 10:30 AM     │
└────────────────────────────────────────┘
```

### Demo Steps for Examiner:
1. Enter `100` → Click Convert → Shows ₹8,350.00
2. Enter `50` → Click Convert → Shows ₹4,175.00
3. Switch to EUR tab → Enter `200` → Shows €184.00
4. Click 🌙 Dark Mode → App turns dark
5. Clear button → Input and result clear, history remains
6. Enter text like `abc` → Shows validation error
7. Check history section showing past conversions

---

## 📖 COMPLETE THEORY — REACT (Unit V Syllabus)

### 1. What is React?
React is an open-source JavaScript LIBRARY (not a framework) created by Facebook (Meta) in 2013.
It is used to build user interfaces, especially Single Page Applications (SPA).

Key features:
- **Component-Based**: UI is broken into small, reusable pieces called Components
- **Virtual DOM**: React maintains a virtual copy of the DOM for performance
- **Unidirectional Data Flow**: Data flows ONE WAY: Parent → Child (predictable)
- **Declarative**: You describe WHAT the UI should look like, React figures out HOW to update DOM

### 2. JSX (JavaScript XML)
JSX lets you write HTML-like syntax inside JavaScript:
```jsx
// JSX:
const element = <h1 className="title">Hello</h1>

// Compiled to:
const element = React.createElement('h1', {className: 'title'}, 'Hello')
```
Rules:
- `class` → `className` (class is reserved in JS)
- `for` → `htmlFor`
- All tags must be closed: `<br />`, `<input />`
- One root element per return statement
- JavaScript expressions go inside `{ }`

### 3. Components
A component is a JavaScript function that returns JSX.

```jsx
// Functional Component (modern way — what we use)
function MyComponent() {
  return <div>Hello World</div>
}

// Arrow function component (same thing, different syntax)
const MyComponent = () => {
  return <div>Hello World</div>
}
```

**Class Component (old way — still valid)**:
```jsx
class MyComponent extends React.Component {
  render() {
    return <div>Hello World</div>
  }
}
```

### 4. State (useState Hook)
State is data that belongs to a component and can CHANGE over time.
When state changes, React automatically re-renders the component.

```jsx
import { useState } from 'react'

const Counter = () => {
  // useState(initialValue) returns [currentValue, setter function]
  const [count, setCount] = useState(0)
  
  return (
    <div>
      <p>Count: {count}</p>
      <button onClick={() => setCount(count + 1)}>Increment</button>
    </div>
  )
}
```

**Rules of State:**
1. NEVER modify state directly: `count = 5` ← WRONG
2. ALWAYS use the setter: `setCount(5)` ← CORRECT
3. State updates trigger a re-render
4. State is local to the component (unless lifted up)

### 5. Props (Properties)
Props are how parent components pass data to child components.
Props are READ-ONLY — child cannot modify them.

```jsx
// Parent passes props:
<ChildComponent name="Rahul" age={20} />

// Child receives via parameter:
const ChildComponent = ({ name, age }) => {
  return <p>{name} is {age} years old</p>
}
```

**Parent → Child communication**: Props (data/functions passed down)
**Child → Parent communication**: Callback props (child calls a function passed by parent)

### 6. Event Handlers
React events use camelCase and pass functions (not strings):
```jsx
// onClick, onChange, onKeyDown, onSubmit, onMouseOver...
<button onClick={handleClick}>Click</button>
<input onChange={(e) => setAmount(e.target.value)} />
```

### 7. Controlled Components
A controlled component is one where React state controls the input's value:
```jsx
const [value, setValue] = useState('')

// Controlled: React state drives the input
<input value={value} onChange={(e) => setValue(e.target.value)} />

// Uncontrolled: DOM drives itself (React can't control it)
<input defaultValue="hello" />
```

### 8. Conditional Rendering
Show/hide UI based on state:
```jsx
// Method 1: Ternary
{isLoggedIn ? <Dashboard /> : <Login />}

// Method 2: Short-circuit (AND)
{error && <p className="error">{error}</p>}

// Method 3: if statement
let content
if (loading) { content = <Spinner /> }
else { content = <Data /> }
```

### 9. List Rendering
Render arrays of items using `.map()`:
```jsx
const items = ['Apple', 'Banana', 'Cherry']

return (
  <ul>
    {items.map((item, index) => (
      <li key={index}>{item}</li>
      // key: REQUIRED — helps React identify which items changed
    ))}
  </ul>
)
```

### 10. Component Lifecycle
Functional components use hooks. Class components had lifecycle methods.

| Class Lifecycle     | Hook Equivalent           | When it runs              |
|---------------------|---------------------------|---------------------------|
| componentDidMount   | useEffect(() => {}, [])   | After first render        |
| componentDidUpdate  | useEffect(() => {}, [dep])| After state/prop changes  |
| componentWillUnmount| useEffect return cleanup  | Before component removed  |
| constructor         | useState initial value    | Before first render       |

### 11. Hooks
Hooks are functions that "hook into" React features from functional components.
- `useState` — manages state
- `useEffect` — side effects (API calls, subscriptions)
- `useRef` — references to DOM elements
- `useContext` — global state without props drilling
- Custom hooks — reusable stateful logic

### 12. Virtual DOM
The Virtual DOM (VDOM) is a JavaScript object representation of the real DOM.
When state changes:
1. React creates a new Virtual DOM
2. Compares with previous Virtual DOM (Diffing algorithm)
3. Finds minimum changes needed (Reconciliation)
4. Updates only those parts of the real DOM (Patching)
This makes React much faster than direct DOM manipulation.

### 13. Unidirectional Data Flow
```
App (state owner)
│
├── passes props down ↓
│
CurrencyConverter (has state)
│
├── passes props down ↓
│
ConversionHistory (receives history via props)
│
└── calls callback ↑ (onClear → updates parent state)
```

### 14. Vite vs Create React App (CRA)
| Feature    | Create React App        | Vite                     |
|------------|-------------------------|--------------------------|
| Start time | ~30 seconds             | < 1 second               |
| Hot reload | Slower (bundles first)  | Instant (uses ES modules)|
| Config     | Hidden (react-scripts)  | Transparent (vite.config)|
| Build      | Webpack                 | Rollup                   |

We use Vite because it's the modern standard.

---

## ❓ LIKELY VIVA QUESTIONS & ANSWERS

**Q1: What is React? How is it different from a framework?**
A: React is a JavaScript LIBRARY for building user interfaces, created by Meta (Facebook). It is different from a framework like Angular because React only handles the VIEW layer (UI). You combine it with other libraries (React Router for routing, Redux for state management). A framework like Angular includes everything built-in.

**Q2: What is a component in React?**
A: A component is an independent, reusable piece of UI. It's a JavaScript function that accepts inputs (props) and returns JSX (what to render). Components can be nested inside each other to build complex UIs from simple pieces — like LEGO blocks.

**Q3: What is JSX? Why do we write it?**
A: JSX (JavaScript XML) is a syntax extension that lets us write HTML-like code inside JavaScript. Browsers can't run JSX directly — Babel (or Vite) compiles it to `React.createElement()` calls. We write JSX because it's much more readable and intuitive than writing `React.createElement('div', null, 'Hello')` for every element.

**Q4: What is useState? Explain with an example.**
A: useState is a React Hook that adds state to functional components. It returns a pair: the current state value and a function to update it. When the setter is called, React re-renders the component with the new value. Example: `const [amount, setAmount] = useState('')` — amount starts empty, and `setAmount('100')` would set it to '100' and trigger re-render.

**Q5: What is the difference between state and props?**
A: State is PRIVATE data owned by a component — it can change over time, and only that component can update it. Props are data passed FROM a parent component TO a child — they are read-only (the child cannot change them). State is like a component's internal memory; props are like function arguments.

**Q6: What is a controlled component?**
A: A controlled component is a form element whose value is controlled by React state. The input's `value` is set from state, and `onChange` updates that state. This means React is always the single source of truth for the input's value. Contrast with uncontrolled components where the DOM itself manages the value.

**Q7: What is the Virtual DOM and why does React use it?**
A: The Virtual DOM is a lightweight JavaScript object representation of the actual DOM. When state changes, React creates a new Virtual DOM, compares it with the previous one (diffing), finds what changed, and updates ONLY those parts of the real DOM. Direct DOM manipulation is slow; the Virtual DOM makes React much more performant.

**Q8: How does React handle events differently from HTML?**
A: In HTML: `<button onclick="handleClick()">` (lowercase, string). In React: `<button onClick={handleClick}>` (camelCase, function reference — NOT a string, NOT a function call). React uses Synthetic Events — a wrapper around native DOM events that works consistently across all browsers.

**Q9: What is conditional rendering?**
A: Conditional rendering means showing different JSX based on state or props. Three ways: (1) Ternary: `{isLoggedIn ? <Dashboard /> : <Login />}` — shows one or the other. (2) Short-circuit: `{error && <p>{error}</p>}` — shows only if truthy. (3) If/else outside JSX for complex logic.

**Q10: What are keys in React? Why are they needed?**
A: Keys are special props that help React identify which items in a list have changed, been added, or removed. When rendering a list with `.map()`, each element needs a unique `key`. React uses keys in its reconciliation algorithm — without them, it re-renders the entire list even if only one item changed. Keys must be unique among siblings.

**Q11: Explain props drilling vs lifting state up.**
A: When multiple components need the same data, you "lift state up" — move the state to the nearest common ancestor and pass it down via props. Props drilling is when you have to pass props through many intermediate components that don't use them, just to get data to a deeply nested child. Solutions: Context API or Redux.

**Q12: What is the difference between `npm install` and `npm run dev`?**
A: `npm install` reads `package.json` and downloads all listed dependencies into `node_modules/` — you run this ONCE when setting up. `npm run dev` runs the "dev" script defined in package.json (which starts the Vite development server) — you run this every time you want to work on the project.

**Q13: What is Vite? Why use it over Create React App?**
A: Vite is a modern build tool that serves source files directly as ES Modules during development (no bundling step). This makes the dev server start in milliseconds. Create React App uses Webpack which bundles everything first (slow). In production, both create optimized bundles, but Vite's is also faster.

**Q14: What does `export default` mean?**
A: `export default` marks a function/variable as the default export of a file. When another file does `import App from './App'`, it imports whatever was exported as default. Named exports use `export { name }` and are imported with curly braces: `import { useState } from 'react'`.

**Q15: What is the spread operator `...` used for in React state updates?**
A: The spread operator (`...`) copies all elements from an array or all properties from an object. In state updates: `setHistory([newEntry, ...prevHistory])` creates a NEW array with newEntry first, then all old entries. We do this because React state must be updated immutably — never push to existing arrays, always create new ones.

---

## 📌 QUICK COMMANDS REFERENCE
```bash
npm install        # Install dependencies (run once)
npm run dev        # Start dev server → http://localhost:5173
npm run build      # Build for production → creates dist/ folder
npm run preview    # Preview the production build locally
```
