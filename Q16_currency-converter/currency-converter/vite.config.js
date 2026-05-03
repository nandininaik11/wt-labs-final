// vite.config.js
// ============================================================
// THEORY: Vite is a modern build tool for JavaScript projects.
// It replaces the older Create React App (CRA) setup.
// Vite is MUCH faster because it uses ES Modules natively
// during development instead of bundling all files first.
//
// @vitejs/plugin-react: tells Vite how to process .jsx files
// and enables React's Fast Refresh (hot reload during dev)
// ============================================================

import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// defineConfig() is a helper that gives us IntelliSense (autocomplete)
export default defineConfig({
  plugins: [react()],
  // plugins: array of Vite plugins to use
  // react() activates JSX transformation and HMR (Hot Module Replacement)
})
