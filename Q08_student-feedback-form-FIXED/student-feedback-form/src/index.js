// Import React library - required for JSX
import React from 'react';

// Import ReactDOM to render React components to the browser DOM
import ReactDOM from 'react-dom/client';

// Import the main App component
import App from './App';

/**
 * CONCEPT: React Root Rendering
 * ==============================
 * React 18+ uses createRoot() to create a root that can render components.
 * This is the entry point where React takes control of a DOM element.
 * 
 * document.getElementById('root') - Gets the <div id="root"> from index.html
 * createRoot() - Creates a React root for rendering
 * render() - Renders the React component tree into the DOM
 */

// Get the root DOM element from public/index.html
const root = ReactDOM.createRoot(document.getElementById('root'));

// Render the App component into the root element
// StrictMode is a tool for highlighting potential problems in the application
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);

/**
 * VIVA CONCEPT: Why use StrictMode?
 * ==================================
 * React.StrictMode helps identify:
 * - Components with unsafe lifecycle methods
 * - Legacy string ref API usage
 * - Deprecated findDOMNode usage
 * - Unexpected side effects
 * - Legacy context API
 * 
 * It only runs in development mode and doesn't affect production build.
 */
