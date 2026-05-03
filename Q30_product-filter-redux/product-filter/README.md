# 🛍️ Product Filter — React + Redux
### Lab Question 30 | Web Technology | Unit V

---

## 📁 FILE STRUCTURE
```
product-filter/
├── index.html                    → Single HTML file (React SPA entry point)
├── package.json                  → npm dependencies (React, Redux, Vite)
├── vite.config.js                → Vite build tool configuration
│
└── src/
    ├── main.jsx                  → Mounts app + wraps with Redux <Provider>
    ├── App.jsx                   → Root layout: Header + Sidebar + Grid
    ├── App.css                   → All component styles
    ├── index.css                 → Global CSS reset
    │
    ├── data/
    │   └── products.js           → 20 products, 5 categories (Task 1 data)
    │
    ├── store/
    │   ├── store.js              → configureStore() — creates Redux store
    │   └── productsSlice.js      → ✅ Tasks 1,2,3: state + actions + reducers
    │
    └── components/
        ├── Header.jsx            → Search bar, view toggle, reset button
        ├── FilterPanel.jsx       → ✅ Task 2: Dispatches all filter actions
        ├── ProductGrid.jsx       → ✅ Task 4: Displays filtered products
        └── ProductCard.jsx       → Single product display (receives props)
```

---

## ⚙️ SETUP & RUN COMMANDS

### Prerequisites
Install **Node.js LTS** from: https://nodejs.org
→ Verify: open terminal → `node -v` and `npm -v` should show version numbers

### Step 1: Extract ZIP
Extract to any folder → `product-filter/`

### Step 2: Open in VS Code
```
File → Open Folder → Select 'product-filter'
```

### Step 3: Open Terminal in VS Code
```
Terminal → New Terminal  (Ctrl + `)
```

### Step 4: Install Dependencies
```bash
npm install
```
Downloads: react, react-dom, @reduxjs/toolkit, react-redux, vite
→ Creates `node_modules/` folder (takes 30-60 seconds)

### Step 5: Start Development Server
```bash
npm run dev
```
→ Output:
```
  VITE v4.x  ready in 300ms
  ➜  Local: http://localhost:5173/
```

### Step 6: Open in Browser
```
http://localhost:5173
```

### Step 7: Stop Server
```
Ctrl + C  (in terminal)
```

---

## 🖥️ EXPECTED OUTPUT (Show the Examiner)

### Screen Layout:
```
┌─────────────────────────────────────────────────────────┐
│  🛍️ ShopFilter  [🔍 Search...]       [⊞][☰] [🔄 Reset] │  ← Header
├────────────┬────────────────────────────────────────────┤
│ 🎛️ Filters │  20 products found                         │
│            │  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐      │
│ 📦 Category│  │ 🎧   │ │ ⌨️   │ │ ⌚   │ │ 🔊   │      │
│ [All]      │  │Head- │ │Mech  │ │Smart │ │BT    │      │
│ [Electronics]│ │phones│ │Keybd │ │Watch │ │Spkr  │      │
│ [Clothing] │  │₹2,999│ │₹4,500│ │₹8,999│ │₹1,799│      │
│ [Books]    │  │★4.5  │ │★4.7  │ │★4.3  │ │★4.1  │      │
│ [Home & K] │  └──────┘ └──────┘ └──────┘ └──────┘      │
│ [Sports]   │  ...more products...                        │
│            │                                             │
│ 💰 Price   │                                             │
│ ₹0 — ₹10K  │                                             │
│ [slider]   │                                             │
│            │                                             │
│ ⭐ Rating  │                                             │
│ [Any][4★+] │                                             │
│            │                                             │
│ ↕️ Sort By  │                                             │
│ [dropdown] │                                             │
│            │                                             │
│ [🔄 Clear] │                                             │
└────────────┴────────────────────────────────────────────┘
```

### Demo Steps for Examiner:
1. **Category Filter**: Click "Electronics" → only 5 electronics shown
2. **Price Filter**: Move price sliders → products update instantly
3. **Price Preset**: Click "₹1K–₹3K" → products in that range appear
4. **Rating Filter**: Click "4★+" → only 4+ rated products
5. **Search**: Type "headphone" → matching product appears
6. **Sort**: Select "Price: Low to High" → products reorder
7. **In Stock**: Toggle "In Stock Only" → out-of-stock hidden
8. **List View**: Click ☰ → grid switches to list layout
9. **Empty State**: Set very narrow price (₹0–₹100) → shows "No products found" + Reset button
10. **Task 5 - Reset**: Click "Reset All Filters" → ALL filters cleared, all 20 products shown

---

## 📖 COMPLETE THEORY — Redux (Unit V Syllabus)

### 1. What is Redux?
Redux is a JavaScript library for **global state management**.

**Problem without Redux (Prop Drilling):**
```
App (has data)
  ↓ pass props
  Header
    ↓ pass props
    Sidebar
      ↓ pass props
      FilterPanel   ← needs the data
```
You'd have to pass data through every level even if intermediate components don't use it.

**Redux solution:**
Any component reads directly from the store — no intermediate passing.

### 2. Three Core Principles of Redux
1. **Single Source of Truth**: ONE store holds all app state
2. **State is Read-Only**: State changes only via dispatching actions
3. **Pure Functions**: Reducers must be pure (same input → same output)

### 3. Flux Architecture (Redux implements Flux)
```
User Action (click filter button)
    ↓
dispatch(setCategory('Electronics'))    ← send action
    ↓
Redux Store receives action
    ↓
Reducer: (state, action) → newState     ← pure function
    ↓
Store updates state
    ↓
useSelector() detects change
    ↓
Components re-render with new filtered data
```

### 4. Key Redux Concepts

#### Store
```javascript
import { configureStore } from '@reduxjs/toolkit'
const store = configureStore({ reducer: { products: productsReducer } })
```
The store is the central data repository. ONE store per app.

#### Action
An action is a plain JavaScript object describing "what happened":
```javascript
{ type: 'products/setCategory', payload: 'Electronics' }
```
- `type`: string describing the event (namespaced with slice name)
- `payload`: data carried with the action (what to change to)

#### Action Creator
A function that creates action objects:
```javascript
// Auto-generated by createSlice:
setCategory('Electronics')
// Returns: { type: 'products/setCategory', payload: 'Electronics' }
```

#### Reducer
A **pure function** that computes new state from current state + action:
```javascript
// Our reducer (simplified):
setCategory: (state, action) => {
  state.filters.category = action.payload
  // RTK/Immer handles immutability internally
}
```
Rules:
- NEVER mutate state directly (in classic Redux — RTK handles this)
- NO side effects (no API calls, no Math.random())
- Same inputs → always same output

#### dispatch()
The function that sends actions to the store:
```javascript
const dispatch = useDispatch()
dispatch(setCategory('Electronics'))
// → Store receives action → Reducer updates state → Components re-render
```

#### useSelector()
Hook to read from Redux state. Subscribes to changes:
```javascript
const filters = useSelector(state => state.products.filters)
// When filters change → component automatically re-renders
```

#### Provider
Wraps the entire app, makes store accessible to all components:
```jsx
<Provider store={store}>
  <App />
</Provider>
```

### 5. Redux Toolkit (RTK) vs Classic Redux

| Feature | Classic Redux | Redux Toolkit |
|---------|--------------|---------------|
| Setup   | 50+ lines | 10 lines |
| Immutability | Manual (spread operators) | Automatic (Immer) |
| Action creators | Write manually | Auto-generated |
| DevTools | Manual setup | Automatic |
| Recommended? | Legacy | ✅ YES (official) |

### 6. createSlice()
RTK's `createSlice()` creates both action creators AND reducer:
```javascript
const slice = createSlice({
  name: 'products',           // prefix for action types
  initialState: { ... },     // starting state
  reducers: {                // becomes actions + reducer cases
    setCategory: (state, action) => {
      state.filters.category = action.payload
    },
  }
})
// slice.actions.setCategory → action creator
// slice.reducer → reducer function for configureStore
```

### 7. Selector Pattern
A selector extracts/derives values from state:
```javascript
export const selectFilteredProducts = (state) => {
  const { products, filters } = state.products
  return products.filter(p => p.category === filters.category)
  // Derived data — computed, never stored
}

// In component:
const filtered = useSelector(selectFilteredProducts)
```
Benefits: Keeps components clean, centralizes filtering logic, reusable.

---

## ❓ VIVA QUESTIONS & ANSWERS

**Q1: What is Redux? Why do we need it?**
A: Redux is a state management library. In React, when multiple unrelated components need the same data, passing props between them becomes messy (prop drilling). Redux provides a single global store that any component can read from or write to directly. It's particularly useful for large apps with complex shared state.

**Q2: What are the three core principles of Redux?**
A: (1) Single Source of Truth — one store holds all state; (2) State is Read-Only — state can only change via dispatching actions; (3) Pure Functions — reducers must be pure (no side effects, same input → same output always).

**Q3: What is an action in Redux?**
A: An action is a plain JavaScript object that describes "what happened". It must have a `type` property (string) and optionally a `payload` with data. Example: `{ type: 'products/setCategory', payload: 'Electronics' }`. Actions are created by action creator functions and sent to the store via `dispatch()`.

**Q4: What is a reducer? What makes it "pure"?**
A: A reducer is a function that takes current state and an action and returns a new state: `(state, action) => newState`. It's "pure" because: it never directly modifies the original state (creates new objects), it has no side effects (no API calls, no console.log, no Math.random()), and the same inputs always produce the same output. Pure functions are predictable and testable.

**Q5: What is Redux Toolkit (RTK)? How is it different from classic Redux?**
A: RTK is the official recommended way to write Redux. It reduces boilerplate significantly: `createSlice()` generates both action creators and reducer from one definition. It uses Immer internally so you can write "mutating" code that actually creates immutable updates. Redux DevTools is automatically enabled. Classic Redux required writing all these separately and manually.

**Q6: What is `useSelector()` and `useDispatch()`?**
A: These are React-Redux hooks. `useDispatch()` returns the store's dispatch function — you call it to send actions: `dispatch(setCategory('Electronics'))`. `useSelector(selectorFn)` reads a value from Redux state and subscribes to it — when that value changes, the component automatically re-renders. They replace the older `connect()` HOC pattern.

**Q7: What is the difference between local state (useState) and Redux state?**
A: Local state (useState) is private to a single component — only that component can read or update it. Redux state is global — any component anywhere in the tree can read or update it. Rule of thumb: if only one component needs the data, use useState. If multiple components share it, use Redux. In this project, wishlist heart is useState (local), filters are Redux (shared between FilterPanel and ProductGrid).

**Q8: What is the Provider component?**
A: `<Provider store={store}>` is a React component from react-redux that wraps the entire app. It uses React Context internally to make the Redux store accessible to any descendant component. Without Provider, useSelector() and useDispatch() would throw errors. It only needs to be added once at the top level (in main.jsx).

**Q9: What is Flux Architecture? How does Redux relate to it?**
A: Flux is an application architecture pattern created by Facebook for managing data flow. It prescribes: Action → Dispatcher → Store → View → (user triggers) → Action. This is a unidirectional (one-way) data flow. Redux is an implementation of the Flux pattern — it follows the same unidirectional flow but replaces the Dispatcher with a single reducer function and the Store is a single immutable object.

**Q10: What is a "selector" in Redux? Why use them?**
A: A selector is a function that takes Redux state and returns a derived value. Example: `selectFilteredProducts` takes all 20 products and all filters, applies the filter logic, and returns just the matching products. Benefits: (1) Components stay clean — no filter logic inside components; (2) Reusable — multiple components can use the same selector; (3) Memoizable with reselect library for performance.

**Q11: Why do we use createSlice() instead of writing actions and reducers separately?**
A: createSlice() eliminates boilerplate. In classic Redux, you'd write: (a) string constants for action types, (b) action creator functions manually, (c) a reducer with switch-case statements — all in separate files. createSlice() generates all three from one object definition. It also handles Immer for immutable updates. The result is 70% less code for the same functionality.

**Q12: What is the difference between `action.type` and `action.payload`?**
A: `action.type` is a string that identifies WHAT happened — like "products/setCategory". The Redux store uses this to know which reducer case to run. `action.payload` is the DATA that comes with the action — like "Electronics" (the new category value). Not all actions need a payload (e.g., resetFilters doesn't need one — the reducer just uses hardcoded defaults).

**Q13: Explain Task 5 — Reset Filters. How does it work?**
A: The `resetFilters` action is dispatched when the reset button is clicked: `dispatch(resetFilters())`. The reducer sets `state.filters` back to the initialState defaults: `{ category: 'All', priceMin: 0, priceMax: 10000, ... }`. Since FilterPanel reads filters via `useSelector`, it re-renders and all inputs show their default values. Since ProductGrid reads `selectFilteredProducts`, it re-runs with no active filters and shows all 20 products.

**Q14: What is immutability? Why is it important in Redux?**
A: Immutability means not modifying existing objects — instead creating new ones with the changes. Classic Redux required: `return { ...state, filters: { ...state.filters, category: action.payload } }` (spread operator creates new objects). RTK uses Immer library so we write `state.filters.category = action.payload` (looks mutating, but Immer produces an immutable update). Redux needs immutability so it can detect state changes efficiently (reference comparison: `old !== new`).

---

## 📌 COMMANDS REFERENCE
```bash
npm install       # Install all dependencies (once)
npm run dev       # Start dev server → http://localhost:5173
npm run build     # Build optimized production bundle → dist/
npm run preview   # Preview production build locally
```
