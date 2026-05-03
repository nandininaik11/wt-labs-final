// ============================================================
// FILE: src/components/Header.jsx
// PURPOSE: Top header — app title, search bar, view mode toggle
//
// THEORY:
//   useDispatch(): Hook that returns the Redux dispatch function.
//   dispatch(action): Sends an action to the Redux store.
//   useSelector(): Hook that reads a value from Redux state.
//   Every time the selected state changes, this component re-renders.
// ============================================================

import React from 'react'
import { useDispatch, useSelector } from 'react-redux'
// useDispatch: gives us the dispatch function to send actions to Redux
// useSelector: reads values from Redux state (subscribes to changes)

import {
  setSearchText,
  setViewMode,
  resetFilters,
  selectFilters,
  selectIsFiltered,
} from '../store/productsSlice'

const Header = () => {
  // useDispatch() returns the store's dispatch function
  // dispatch(actionCreator(payload)) → sends action to reducer → state updates
  const dispatch = useDispatch()

  // useSelector(selectorFn) reads from Redux state
  // When the selected value changes, this component re-renders automatically
  const filters     = useSelector(selectFilters)
  const isFiltered  = useSelector(selectIsFiltered)
  // selectIsFiltered returns true if ANY filter is active (not at default)

  return (
    <header className="app-header">
      <div className="header-inner">
        {/* Brand / Title */}
        <div className="brand">
          <span className="brand-icon">🛍️</span>
          <div>
            <h1 className="brand-name">ShopFilter</h1>
            <p className="brand-sub">React + Redux — Lab Q30</p>
          </div>
        </div>

        {/* Search Bar */}
        <div className="search-wrap">
          <span className="search-icon">🔍</span>
          <input
            type="text"
            className="search-input"
            placeholder="Search products, brands..."
            value={filters.searchText}
            // CONTROLLED INPUT: value comes from Redux state
            // React controls the input — this is a "controlled component"
            onChange={(e) => dispatch(setSearchText(e.target.value))}
            // onChange fires on every keystroke
            // e.target.value = what user typed
            // dispatch(setSearchText("laptop")) → Redux updates searchText → re-render
          />
          {/* Clear search X button — only shows when text is typed */}
          {filters.searchText && (
            // Short-circuit rendering: renders only when searchText is truthy
            <button
              className="search-clear"
              onClick={() => dispatch(setSearchText(''))}
              title="Clear search"
            >
              ✕
            </button>
          )}
        </div>

        {/* Controls: View Toggle + Reset */}
        <div className="header-controls">
          {/* View mode toggle: grid ⊞ or list ☰ */}
          <div className="view-toggle">
            <button
              className={`view-btn ${filters.sortBy !== 'default' || true ? '' : ''} ${useSelector(s => s.products.viewMode) === 'grid' ? 'active' : ''}`}
              onClick={() => dispatch(setViewMode('grid'))}
              title="Grid view"
            >
              ⊞
            </button>
            <button
              className={`view-btn ${useSelector(s => s.products.viewMode) === 'list' ? 'active' : ''}`}
              onClick={() => dispatch(setViewMode('list'))}
              title="List view"
            >
              ☰
            </button>
          </div>

          {/* TASK 5: Reset button — only highlighted when filters are active */}
          <button
            className={`reset-btn ${isFiltered ? 'reset-btn--active' : ''}`}
            onClick={() => dispatch(resetFilters())}
            // dispatch(resetFilters()) sends reset action → reducer restores all defaults
            disabled={!isFiltered}
            // disabled attribute: button is greyed out when no filters active
            title="Reset all filters"
          >
            🔄 Reset
            {/* Show active filter count badge */}
            {isFiltered && <span className="reset-badge">!</span>}
          </button>
        </div>
      </div>
    </header>
  )
}

export default Header
