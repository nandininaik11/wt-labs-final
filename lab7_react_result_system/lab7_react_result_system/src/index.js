/**
 * INDEX.JS - React Application Entry Point
 * This file renders the App component into the DOM
 */

import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import App from './App';

/**
 * Get the root DOM element where React app will be mounted
 * This element is defined in public/index.html as <div id="root"></div>
 */
const root = ReactDOM.createRoot(document.getElementById('root'));

/**
 * Render the React application
 * StrictMode helps identify potential problems
 */
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);
