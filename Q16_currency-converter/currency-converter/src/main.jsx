// ============================================================
// FILE: src/main.jsx
// PURPOSE: Entry point — mounts the React app to the HTML DOM
//
// THEORY: ReactDOM.createRoot() is the bridge between React
// and the actual HTML page. It finds <div id="root"> and
// tells React to control everything inside it.
//
// React 18 introduced createRoot() (replacing ReactDOM.render).
// StrictMode wraps the app to highlight potential problems
// during development only (does NOT affect production).
// ============================================================

import React from 'react'
// React must be imported to use JSX
// JSX gets compiled to React.createElement() calls by Babel/Vite

import ReactDOM from 'react-dom/client'
// ReactDOM connects React to the browser's DOM (Document Object Model)

import App from './App.jsx'
// Import our root component — the top of the component tree

import './index.css'
// Import global CSS — applies to the whole app

// ReactDOM.createRoot(): creates a React root at the DOM element with id="root"
// document.getElementById('root') finds <div id="root"> in index.html
const root = ReactDOM.createRoot(document.getElementById('root'))

// root.render(): tells React WHAT to render at that root
root.render(
  <React.StrictMode>
    {/* 
      React.StrictMode: A developer tool that:
      1. Warns about deprecated API usage
      2. Detects unexpected side effects
      3. Runs components twice in dev mode to find bugs
      This only affects development — no impact on production build
    */}
    <App />
    {/* <App /> is JSX syntax — it renders our App component */}
  </React.StrictMode>
)
