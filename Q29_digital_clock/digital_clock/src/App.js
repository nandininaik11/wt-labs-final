// ============================================================
// src/App.js — Root App Component
// WT Syllabus Unit V: React — Component composition
//
// In React, the App component is the ROOT of the component tree.
// It composes (contains) other components.
// Component Tree:
//   <App>
//     └── <Clock>        (main clock display + controls)
//           └── <TimeDisplay>   (shows HH:MM:SS)
//           └── <Controls>      (start/stop/reset buttons)
//           └── <InfoPanel>     (date, timezone info)
// ============================================================

// Every React file MUST import React when using JSX
import React from 'react';

// Import child components (each in its own file — good practice)
import Clock from './components/Clock';

// Import component-level CSS
import './App.css';

// ── App Component (Functional Component) ──
// A functional component is simply a JavaScript function
// that returns JSX (HTML-like syntax compiled to React.createElement calls)
function App() {
  // JSX: looks like HTML but is actually JavaScript
  // Rules: must return ONE root element, className not class, camelCase events
  return (
    <div className="app-wrapper">
      {/* JSX comment syntax: {/* ... */}  */}
      {/* This div is the full-page container */}

      {/* Animated background dots */}
      <div className="bg-animation">
        {/* Array.from creates an array of 20 items, map renders 20 dots */}
        {Array.from({ length: 20 }).map((_, i) => (
          // key prop: required when rendering lists — helps React track items
          <div key={i} className="dot" />
        ))}
      </div>

      {/* Clock component — the main feature */}
      <Clock />
    </div>
  );
}

// export default — makes this component importable by other files
export default App;
