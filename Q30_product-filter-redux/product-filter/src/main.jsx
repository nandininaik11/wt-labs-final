// ============================================================
// FILE: src/main.jsx
// PURPOSE: App entry point — mounts React + connects Redux store
//
// THEORY: Provider (from react-redux) is a React component that
// wraps the entire app and makes the Redux store available to
// ALL child components via React Context.
// Without Provider, useSelector() and useDispatch() won't work.
//
// Think of Provider as a "global data broadcaster":
//   Provider (has the store)
//     └── App
//           └── FilterPanel  ← can read/write Redux state
//           └── ProductGrid  ← can read/write Redux state
//                 └── ProductCard ← can read/write Redux state
// ============================================================

import React from 'react'
import ReactDOM from 'react-dom/client'

// react-redux: official React bindings for Redux
// Provider: component that makes Redux store accessible to all children
import { Provider } from 'react-redux'

import { store } from './store/store'
// Import our configured Redux store

import App from './App.jsx'
import './index.css'

// ReactDOM.createRoot: React 18 way to mount the app
// document.getElementById('root') finds <div id="root"> in index.html
const root = ReactDOM.createRoot(document.getElementById('root'))

root.render(
  <React.StrictMode>
    {/*
      Provider wraps the ENTIRE app.
      store={store} passes our Redux store to Provider.
      Now any component anywhere in the tree can:
        - Read state with useSelector()
        - Dispatch actions with useDispatch()
      This eliminates prop drilling entirely.
    */}
    <Provider store={store}>
      <App />
    </Provider>
  </React.StrictMode>
)
