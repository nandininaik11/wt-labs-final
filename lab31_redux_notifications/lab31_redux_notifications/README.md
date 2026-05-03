# 📘 Lab Q31 — Redux Notification System (React + Redux)
## Web Technology — Complete Notes

---

## ⚙️ HOW TO OPEN IN VS CODE

### Option A — Direct (no npm, works instantly):
1. Extract the ZIP
2. Open VS Code → File → Open Folder → `lab31_redux_notifications`
3. Double-click `index.html`
4. Press **Go Live** (Live Server extension) at the bottom-right
   OR just double-click `index.html` in File Explorer — opens in browser

> ✅ React + Redux loaded via CDN. No npm install needed.

### Option B — Real Vite project (for examiner if asked):
```bash
# Open VS Code Terminal (Ctrl + `)
npm create vite@latest lab31-redux -- --template react
cd lab31-redux
npm install redux react-redux
npm run dev
# Opens at http://localhost:5173
```

---

## 📁 File Structure

```
lab31_redux_notifications/
│
├── index.html       ← Complete app: HTML + CSS + JSX + Redux
└── README.md        ← Theory + commands + viva notes

--- Real Vite project structure: ---
src/
├── store/
│   ├── store.js          ← createStore()
│   ├── actionTypes.js    ← ADD_NOTIFICATION, REMOVE_NOTIFICATION
│   ├── actionCreators.js ← addNotification(), removeNotification()
│   └── reducer.js        ← notificationReducer()
├── components/
│   ├── NotificationCard.jsx
│   ├── NotificationFeed.jsx
│   └── ControlPanel.jsx
├── App.jsx
└── main.jsx          ← ReactDOM + <Provider store={store}>
```

---

## 🖥️ Expected Output (What to Show the Examiner)

### On page load:
- Left panel: "Add Notification" form + Quick Presets + Redux Store State viewer
- Right panel: Empty state "No Notifications" + stats row (all zeros)

### Demo sequence:
1. **Type** a title: "Server Online", message: "All systems running normally." → select ✅ Success → click **"+ Add Notification to Store"**
2. Right panel shows a **green-accented notification card** with title, message, timestamp, ID
3. Stats row shows: **Success: 1**

4. Click **⚠️ Low Storage** quick preset → yellow warning notification appears

5. Click **❌ Network Error** quick preset → red error notification appears

6. **Redux Store State** (bottom of left panel) updates live in real-time — shows JSON

7. Click **×** on any notification card → it disappears (Task 5: dismiss)

8. Click **🗑 Clear All** → all notifications removed

### Console (F12):
Redux store is visible. Type in console:
```javascript
// Not directly accessible from console in CDN version,
// but Redux state is shown live in the Redux Store State box
```

---

## 📖 THEORY — WT Syllabus Mapped (Unit V)

### 1. What is Redux? (Unit-V: Redux, Flux)
Redux is a **predictable state management library** for JavaScript applications.

**Why Redux?**
- In large React apps, state shared across many components becomes difficult to manage
- Passing state through many layers of props = "prop drilling"
- Redux solves this: ONE global store that ALL components can read from and write to

**Core Principles:**
1. **Single source of truth** — all app state in ONE store object
2. **State is read-only** — only actions can change it
3. **Changes via pure functions** — reducers are pure functions

**Flux vs Redux:**
- Flux (by Facebook) was the original architecture pattern: Action → Dispatcher → Store → View
- Redux is a simplified implementation of Flux with ONE store and no Dispatcher

---

### 2. Redux Data Flow (Unidirectional)
```
User clicks button
     ↓
Component calls dispatch(action)
     ↓
Redux calls reducer(currentState, action)
     ↓
Reducer returns NEW state
     ↓
Redux store updates
     ↓
useSelector detects change → React re-renders components
     ↓
UI updates automatically
```

---

### 3. Task 2: Actions and Action Creators
```javascript
// ACTION TYPE CONSTANTS (prevent typos)
const ADD_NOTIFICATION    = 'ADD_NOTIFICATION';
const REMOVE_NOTIFICATION = 'REMOVE_NOTIFICATION';

// ACTION = plain JS object with 'type' and optional 'payload'
{
  type: 'ADD_NOTIFICATION',
  payload: {
    id: 1,
    title: 'Login Success',
    message: 'Welcome back!',
    notifType: 'success',
    timestamp: '10:30:00 AM'
  }
}

// ACTION CREATOR = function that returns an action object
function addNotification(title, message, type) {
  return {
    type: ADD_NOTIFICATION,
    payload: { id: counter++, title, message, notifType: type, timestamp: ... }
  };
}

// Usage: dispatch the action from a component
dispatch(addNotification('Title', 'Message', 'success'));
```

---

### 4. Task 3: Reducer
```javascript
// INITIAL STATE — default value before any actions
const initialState = {
  notifications: [],
  totalAdded: 0,
};

// REDUCER — pure function: (state, action) => newState
function notificationReducer(state = initialState, action) {
  switch (action.type) {
    case ADD_NOTIFICATION:
      return {
        ...state,           // spread: copy all existing properties
        notifications: [action.payload, ...state.notifications],
        totalAdded: state.totalAdded + 1,
      };
    case REMOVE_NOTIFICATION:
      return {
        ...state,
        notifications: state.notifications.filter(n => n.id !== action.payload.id),
      };
    case CLEAR_ALL:
      return { ...state, notifications: [] };
    default:
      return state;  // ALWAYS return state for unknown actions
  }
}
```

**Key rule**: NEVER mutate state. Always return a NEW object:
```javascript
// ❌ WRONG — mutates existing state
state.notifications.push(newItem);
return state;

// ✅ CORRECT — returns NEW array
return { ...state, notifications: [...state.notifications, newItem] };
```

---

### 5. Task 1: Redux Store
```javascript
// createStore(reducer) creates the global state container
const store = createStore(notificationReducer);

// Store API:
store.getState()         // returns current state
store.dispatch(action)   // sends action to reducer
store.subscribe(fn)      // runs fn after every state change
```

---

### 6. react-redux: Connecting React to Redux
```jsx
// 1. Wrap app in Provider — makes store available to all components
ReactDOM.createRoot(root).render(
  <Provider store={store}>
    <App />
  </Provider>
);

// 2. useSelector — reads state from store
const notifications = useSelector(state => state.notifications);
// Runs when state changes, component re-renders if result changed

// 3. useDispatch — gets the dispatch function
const dispatch = useDispatch();
dispatch(addNotification('Title', 'Message', 'info'));
```

**Old way (class components — class-based connect):**
```javascript
// mapStateToProps and mapDispatchToProps with connect() HOC
const mapStateToProps = state => ({ notifications: state.notifications });
export default connect(mapStateToProps)(NotificationFeed);
```

---

### 7. Task 4 & 5: Display and Dismiss (React)
```jsx
// Task 4: Render notifications with .map()
{notifications.map(n => (
  <NotificationCard key={n.id} notification={n} />
))}

// Task 5: Dismiss button dispatches REMOVE action
function handleDismiss() {
  dispatch(removeNotification(id));
  // Reducer filters out the notification with this id
}
<button onClick={handleDismiss}>×</button>
```

---

### 8. Pure Functions (Unit-II: Functions)
A pure function:
1. Same input → always same output
2. No side effects (no API calls, no console.log, no mutating parameters)

Reducers MUST be pure. This enables Redux's features:
- Time-travel debugging (replay actions)
- Hot module reloading
- Predictable behavior in tests

---

### 9. Spread Operator in Reducers (Unit-II: JavaScript)
```javascript
// Creates a NEW object with all properties of state, then overrides some
const newState = { ...state, notifications: [...state.notifications, newItem] };

// Without spread (equivalent but more verbose):
const newState = Object.assign({}, state, { notifications: [...state.notifications, newItem] });
```

---

### 10. Array.filter() for REMOVE (Unit-II: Arrays)
```javascript
// filter() returns a NEW array containing only items where the test is true
notifications.filter(n => n.id !== removedId)
// Items with id === removedId are excluded → notification is "removed"
```

---

## ❓ LIKELY VIVA QUESTIONS + ANSWERS

**Q1. What is Redux? Why do we use it with React?**
A: Redux is a predictable state management library. React's local state (useState) works for small apps, but when many components need to share state, passing it through props becomes complex ("prop drilling"). Redux provides a single global store all components can read from and write to, making state management predictable and debuggable.

---

**Q2. What are the three core principles of Redux?**
A: (1) Single source of truth — all application state in ONE store. (2) State is read-only — you can only change state by dispatching actions; no direct mutation. (3) Changes are made with pure functions — reducers take old state + action and return new state without side effects.

---

**Q3. What is an Action in Redux?**
A: An action is a plain JavaScript object that describes what happened. It must have a `type` property (string constant) and optionally a `payload` with data. Example: `{ type: 'ADD_NOTIFICATION', payload: { id: 1, title: 'Hello', type: 'info' } }`. Actions are the ONLY way to trigger state changes.

---

**Q4. What is a Reducer?**
A: A reducer is a pure function with signature `(state, action) => newState`. It takes the current state and an action, and returns a new state object. It must NEVER mutate the existing state — it always creates and returns a new object. It uses a switch/case on action.type to decide how to update state.

---

**Q5. What is the Redux store?**
A: The store is the central object that holds the entire application state tree. Created with `createStore(reducer)`. It provides: `getState()` to read state, `dispatch(action)` to update state, and `subscribe(listener)` to register callbacks that run after every update.

---

**Q6. What is Provider in react-redux?**
A: `<Provider store={store}>` is a React component from react-redux that wraps the entire app. It uses React Context to make the Redux store available to all nested components — without manually passing the store as a prop to every component.

---

**Q7. What is useSelector?**
A: `useSelector(selectorFn)` is a react-redux hook that reads data from the Redux store. The selector function receives the entire state and returns the slice needed. The component automatically re-renders when the selected data changes. It replaces the old `mapStateToProps` pattern.

---

**Q8. What is useDispatch?**
A: `useDispatch()` is a react-redux hook that returns the Redux store's `dispatch` function. You call `dispatch(actionCreator())` to send an action to the reducer. It replaces the old `mapDispatchToProps` pattern.

---

**Q9. Explain the Redux data flow.**
A: Component calls `dispatch(addNotification(...))` → Redux sends the action to the reducer → reducer processes it and returns new state → Redux updates the store → `useSelector` detects the change → React re-renders affected components → UI updates. This is unidirectional (one-way) data flow.

---

**Q10. Why must reducers be pure functions?**
A: Pure functions are predictable, testable, and have no side effects. Redux relies on this to enable: time-travel debugging (replay any sequence of actions to recreate past state), hot module reloading, and serialization of state. If reducers had side effects, state would be unpredictable.

---

**Q11. What is the difference between Redux state and React state (useState)?**
A: React `useState` is local — only the component and its children can access it. Redux state is global — any component in the app can read from and write to it via `useSelector`/`useDispatch`. Use React state for UI-only state (form inputs, modal open/close). Use Redux for data shared across components.

---

**Q12. What is the `key` prop in React lists and why is it required?**
A: `key` is a special prop React uses to identify which items in a list have changed, been added, or removed. It must be unique and stable (not array index for dynamic lists). React uses keys during reconciliation (diffing Virtual DOM) to efficiently update only changed DOM nodes. Using `id` from the Redux notification object is correct.

---

**Q13. What is Flux architecture?**
A: Flux is an application architecture pattern created by Facebook. It enforces unidirectional data flow: Action → Dispatcher → Store → View. Redux is a simplified implementation of Flux — it removes the Dispatcher (reducer handles it directly) and uses a single store instead of multiple stores.

---

*Prepared for WT Lab Q31 — Redux Notification System*
