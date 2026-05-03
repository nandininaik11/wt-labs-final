// FILE: vite.config.js
// THEORY: Vite is a modern JavaScript build tool.
// It uses ES Modules natively during development (no bundling = fast startup).
// @vitejs/plugin-react: transforms JSX → React.createElement() + enables Fast Refresh.
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
})
