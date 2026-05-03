// ============================================================
// src/index.js — React Entry Point
// WT Syllabus Unit V: React — Render Function
//
// This file is the bridge between the HTML file (public/index.html)
// and the React component tree. It mounts the App component
// into the <div id="root"> in index.html.
// ============================================================

// React: the core library — needed for JSX and component logic
import React from 'react';

// ReactDOM: handles rendering React components into the real DOM
// (separate from React because React can target other platforms: React Native, etc.)
import ReactDOM from 'react-dom/client';

// Our root component — the top of the component tree
import App from './App';

// Import global CSS styles
import './index.css';

// ReactDOM.createRoot() — React 18+ way to create a root
// It finds the <div id="root"> in public/index.html
const root = ReactDOM.createRoot(document.getElementById('root'));

// root.render() — renders the App component inside the root div
// React.StrictMode wraps the app to detect potential problems
// in development (no effect on production builds)
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);
