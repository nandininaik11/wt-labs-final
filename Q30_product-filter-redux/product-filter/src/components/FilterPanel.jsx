// ============================================================
// FILE: src/components/FilterPanel.jsx
// PURPOSE: Sidebar with ALL filter controls
//
// TASK 2: Create actions for filtering products
// This component DISPATCHES all filter actions to Redux.
//
// REDUX HOOKS USED:
//   useDispatch() → send actions to store (write)
//   useSelector() → read values from store (read)
//
// THEORY — React-Redux Hooks:
//   Before hooks, you needed connect() HOC — complex boilerplate.
//   With hooks (React-Redux v7.1+):
//     const dispatch = useDispatch()
//     const value = useSelector(state => state.slice.field)
//   Much simpler — works in any functional component.
// ============================================================

import React from 'react'
import { useDispatch, useSelector } from 'react-redux'
import {
  setCategory,
  setPriceMin,
  setPriceMax,
  setMinRating,
  setSortBy,
  setInStockOnly,
  resetFilters,
  selectFilters,
  selectCategories,
  selectIsFiltered,
} from '../store/productsSlice'

// Category emoji mapping — JavaScript Object (Unit II)
// Object: { key: value } — access with obj['key'] or obj.key
const CATEGORY_ICONS = {
  'All':            '🛍️',
  'Electronics':    '📱',
  'Clothing':       '👕',
  'Books':          '📚',
  'Home & Kitchen': '🏠',
  'Sports':         '🏃',
}

const FilterPanel = () => {
  const dispatch    = useDispatch()
  // dispatch: function returned by useDispatch()
  // Calling dispatch(action) sends action to the Redux reducer

  const filters     = useSelector(selectFilters)
  // selectFilters selector: returns state.products.filters object
  // When filters change, this component automatically re-renders

  const categories  = useSelector(selectCategories)
  // selectCategories: returns ['All', 'Electronics', 'Clothing', ...]

  const isFiltered  = useSelector(selectIsFiltered)
  // Boolean: true if any filter is not at its default value

  return (
    <div className="filter-panel">

      {/* Panel Title + Reset button */}
      <div className="filter-header">
        <h2 className="filter-title">🎛️ Filters</h2>
        {/* TASK 5: Reset — only shown/highlighted when filters are active */}
        {isFiltered && (
          // Conditional rendering: JSX only appears when isFiltered is true
          <button
            className="filter-reset-link"
            onClick={() => dispatch(resetFilters())}
            // Arrow function: () => dispatch(resetFilters())
            // resetFilters() returns action object: { type: "products/resetFilters" }
            // dispatch() sends it to the Redux store → reducer resets all filters
          >
            Reset All ✕
          </button>
        )}
      </div>

      {/* ── SECTION 1: Category Filter ── */}
      <div className="filter-section">
        <h3 className="filter-section-title">📦 Category</h3>
        <div className="category-list">
          {/* 
            TASK 4: List rendering — .map() converts array to JSX elements
            categories = ['All', 'Electronics', 'Clothing', ...]
            Each category becomes a button
            key={cat}: required by React for list reconciliation
          */}
          {categories.map(cat => (
            <button
              key={cat}
              // key: unique identifier — React uses this to track list items
              // Without keys, React re-renders the entire list on any change
              // With keys, React only updates the changed items (efficient DOM)
              
              className={`cat-btn ${filters.category === cat ? 'cat-btn--active' : ''}`}
              // Template literal: `...${expression}...`
              // Conditionally adds 'cat-btn--active' class if this category is selected
              // This changes the button's visual style (highlighting selected filter)
              
              onClick={() => dispatch(setCategory(cat))}
              // onClick event handler: user clicks → dispatch action → state updates
              // dispatch(setCategory('Electronics')) sends:
              //   { type: 'products/setCategory', payload: 'Electronics' }
              // Reducer sets state.filters.category = 'Electronics'
              // Components re-render with new filtered results
            >
              <span>{CATEGORY_ICONS[cat] || '📦'}</span>
              {/* Bracket notation: object property access with variable key */}
              {cat}
            </button>
          ))}
        </div>
      </div>

      {/* ── SECTION 2: Price Range Filter ── */}
      <div className="filter-section">
        <h3 className="filter-section-title">💰 Price Range (₹)</h3>
        
        {/* Price display badge */}
        <div className="price-display">
          <span className="price-badge">₹{filters.priceMin.toLocaleString()}</span>
          {/* toLocaleString(): formats 1000 as "1,000" (adds commas) */}
          <span className="price-sep">—</span>
          <span className="price-badge">₹{filters.priceMax.toLocaleString()}</span>
        </div>

        {/* Min price slider */}
        <div className="slider-wrap">
          <label className="slider-label">Min: ₹{filters.priceMin}</label>
          <input
            type="range"
            // type="range": HTML5 slider input
            className="price-slider"
            min={0}
            max={10000}
            step={100}
            // step="100": slider moves in increments of ₹100
            value={filters.priceMin}
            // CONTROLLED: value bound to Redux state
            onChange={(e) => {
              const val = Number(e.target.value)
              // Number(): converts string to number (input values are always strings)
              // Enforce: min price must not exceed max price
              if (val <= filters.priceMax) dispatch(setPriceMin(val))
            }}
          />
        </div>

        {/* Max price slider */}
        <div className="slider-wrap">
          <label className="slider-label">Max: ₹{filters.priceMax}</label>
          <input
            type="range"
            className="price-slider"
            min={0}
            max={10000}
            step={100}
            value={filters.priceMax}
            onChange={(e) => {
              const val = Number(e.target.value)
              if (val >= filters.priceMin) dispatch(setPriceMax(val))
            }}
          />
        </div>

        {/* Quick preset price range buttons */}
        <div className="price-presets">
          {/* JavaScript Array of preset objects (Unit II — Arrays) */}
          {[
            { label: 'Under ₹1K',    min: 0,    max: 1000  },
            { label: '₹1K – ₹3K',   min: 1000, max: 3000  },
            { label: '₹3K – ₹6K',   min: 3000, max: 6000  },
            { label: 'Over ₹6K',     min: 6000, max: 10000 },
          ].map(preset => (
            <button
              key={preset.label}
              className={`preset-btn ${
                filters.priceMin === preset.min && filters.priceMax === preset.max
                  ? 'preset-btn--active' : ''
              }`}
              onClick={() => {
                // Dispatch TWO actions to set both min and max
                dispatch(setPriceMin(preset.min))
                dispatch(setPriceMax(preset.max))
                // Dispatching multiple actions is fine — Redux batches updates in React 18
              }}
            >
              {preset.label}
            </button>
          ))}
        </div>
      </div>

      {/* ── SECTION 3: Rating Filter ── */}
      <div className="filter-section">
        <h3 className="filter-section-title">⭐ Minimum Rating</h3>
        <div className="rating-options">
          {/* Array literal used inline with .map() */}
          {[0, 3, 3.5, 4, 4.5].map(r => (
            <button
              key={r}
              className={`rating-btn ${filters.minRating === r ? 'rating-btn--active' : ''}`}
              onClick={() => dispatch(setMinRating(r))}
            >
              {r === 0 ? 'Any' : `${r}★+`}
              {/* Ternary operator: condition ? ifTrue : ifFalse */}
              {/* If r is 0 → show 'Any', else show '3★+', '4★+' etc. */}
            </button>
          ))}
        </div>
      </div>

      {/* ── SECTION 4: Sort By ── */}
      <div className="filter-section">
        <h3 className="filter-section-title">↕️ Sort By</h3>
        <select
          className="sort-select"
          value={filters.sortBy}
          // CONTROLLED SELECT: value from Redux state controls which option is selected
          onChange={(e) => dispatch(setSortBy(e.target.value))}
          // onChange: fires when user selects a different option
          // e.target.value: the value attribute of the selected <option>
        >
          <option value="default">Default Order</option>
          <option value="price-asc">Price: Low to High</option>
          <option value="price-desc">Price: High to Low</option>
          <option value="rating">Highest Rated First</option>
          <option value="name">Name: A to Z</option>
        </select>
      </div>

      {/* ── SECTION 5: Availability Toggle ── */}
      <div className="filter-section">
        <h3 className="filter-section-title">📦 Availability</h3>
        <label className="toggle-label">
          <div className="toggle-wrap">
            <input
              type="checkbox"
              checked={filters.inStockOnly}
              // type="checkbox" with checked={} is a CONTROLLED CHECKBOX
              // controlled by Redux state
              onChange={(e) => dispatch(setInStockOnly(e.target.checked))}
              // e.target.checked: boolean (true if checked, false if unchecked)
              className="toggle-input"
            />
            <span className="toggle-slider"></span>
          </div>
          <span className="toggle-text">
            In Stock Only
            {filters.inStockOnly && <span className="toggle-on">● ON</span>}
          </span>
        </label>
      </div>

      {/* ── TASK 5: Full Reset Button ── */}
      <button
        className={`full-reset-btn ${isFiltered ? 'full-reset-btn--active' : ''}`}
        onClick={() => dispatch(resetFilters())}
        disabled={!isFiltered}
        // disabled: HTML attribute — button is unclickable and greyed out when no filters active
      >
        {isFiltered ? '🔄 Clear All Filters' : '✓ No Filters Active'}
      </button>

    </div>
  )
}

export default FilterPanel
