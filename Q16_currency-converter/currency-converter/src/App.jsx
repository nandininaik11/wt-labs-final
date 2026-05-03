// ============================================================
// FILE: src/App.jsx
// PURPOSE: Root (parent) component — holds app layout
//
// THEORY: In React, components are the building blocks.
// A component is a JavaScript function that returns JSX.
// JSX (JavaScript XML) looks like HTML but is actually JS.
// Components can be nested inside each other like HTML tags.
//
// COMPONENT TREE:
//   App
//   └── CurrencyConverter
//       ├── ConversionDisplay
//       └── ConversionHistory
// ============================================================

import React from 'react'
import CurrencyConverter from './components/CurrencyConverter'
// Import our child component — the main converter UI
import './App.css'
// Import CSS specific to this component

// App is a Functional Component — a regular JavaScript function
// that returns JSX (what to render on screen)
function App() {
  return (
    // JSX must have ONE root element — we use a div wrapper
    // In React, class → className (because "class" is a JS keyword)
    <div className="app-wrapper">

      {/* Header Section */}
      <header className="app-header">
        <div className="header-content">
          <h1 className="app-title">
            <span className="flag">🇺🇸</span>
            {/* In JSX, JavaScript expressions go inside { } */}
            {' '}Currency Converter{' '}
            <span className="flag">🇮🇳</span>
          </h1>
          <p className="app-subtitle">
            Real-time USD → INR conversion using React State & Events
          </p>
        </div>
      </header>

      {/* Main Content */}
      <main className="app-main">
        {/*
          CurrencyConverter is our child component.
          Placing <CurrencyConverter /> here is called
          COMPONENT COMPOSITION — building UI from smaller pieces.
          This component contains all the state and logic.
        */}
        <CurrencyConverter />
      </main>

      {/* Footer */}
      <footer className="app-footer">
        <p>Built with ⚛️ ReactJS | Lab Question 16 | Web Technology</p>
        <p className="footer-note">
          💡 Demonstrates: State, Events, Props, Component Lifecycle
        </p>
      </footer>

    </div>
  )
}

// Export the component so other files can import it
// Default export means: import App from './App' (no curly braces)
export default App
