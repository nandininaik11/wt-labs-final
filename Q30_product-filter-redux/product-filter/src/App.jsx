// ============================================================
// FILE: src/App.jsx
// PURPOSE: Root component — app layout: header + sidebar + grid
//
// COMPONENT TREE:
//   App
//   ├── Header
//   ├── FilterPanel  (sidebar — dispatches filter actions)
//   └── ProductGrid  (main — reads filtered products from Redux)
//         └── ProductCard (displays one product)
// ============================================================

import React from 'react'
import FilterPanel from './components/FilterPanel'
import ProductGrid from './components/ProductGrid'
import Header from './components/Header'
import './App.css'

// App is a Functional Component — a JS function returning JSX
// JSX: JavaScript XML — HTML-like syntax that compiles to React.createElement()
function App() {
  return (
    <div className="app">
      {/* Header: title, search bar, view toggle */}
      <Header />

      {/* Main layout: sidebar filter + product grid side by side */}
      <div className="app-body">
        {/* 
          FilterPanel: sidebar with category, price, rating filters
          FilterPanel DISPATCHES actions → Redux store updates → ProductGrid re-renders
        */}
        <aside className="sidebar">
          <FilterPanel />
        </aside>

        {/* 
          ProductGrid: reads filtered products from Redux via useSelector
          Task 4: Display filtered products dynamically
        */}
        <main className="main-content">
          <ProductGrid />
        </main>
      </div>
    </div>
  )
}

export default App
// export default: enables import App from './App' (no curly braces)
