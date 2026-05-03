// ============================================================
// FILE: src/components/ProductGrid.jsx
// PURPOSE: TASK 4 — Display filtered products dynamically
//
// This component READS from Redux store using useSelector().
// It never dispatches — it's a "read-only" component.
//
// THEORY — useSelector():
//   const data = useSelector(selectorFn)
//   - Calls selectorFn(state) and subscribes to the result
//   - When relevant state changes → selector runs → if result differs → re-render
//   - This is how Redux "drives" the UI automatically
//
// THEORY — Component Composition (Unit V):
//   ProductGrid renders multiple ProductCard components.
//   Each ProductCard receives product data as PROPS.
//   Props = data passed from parent to child.
//   ProductGrid (parent) owns the data; ProductCard (child) displays it.
// ============================================================

import React from 'react'
import { useSelector } from 'react-redux'
import { selectFilteredProducts, selectIsFiltered, resetFilters } from '../store/productsSlice'
import { useDispatch } from 'react-redux'
import ProductCard from './ProductCard'

const ProductGrid = () => {
  const dispatch = useDispatch()

  // useSelector reads from Redux store
  // selectFilteredProducts runs ALL filter + sort logic and returns { filtered, viewMode }
  const { filtered, viewMode } = useSelector(selectFilteredProducts)
  // Destructuring: extract 'filtered' and 'viewMode' from returned object

  const isFiltered = useSelector(selectIsFiltered)

  return (
    <div className="product-grid-wrap">

      {/* ── Results summary bar ── */}
      <div className="results-bar">
        <div className="results-count">
          {/* Template literal: combines string + expression */}
          <span className="count-num">{filtered.length}</span>
          {/* String methods used inline */}
          {` product${filtered.length !== 1 ? 's' : ''} found`}
          {/* Ternary: adds 's' for plural (0 products, 2 products, but 1 product) */}
        </div>
        {isFiltered && (
          <span className="filters-active-badge">
            🎯 Filters Active
          </span>
        )}
      </div>

      {/* ── TASK 4: Display filtered products ── */}
      {filtered.length === 0 ? (
        // Conditional rendering: show empty state when no products match
        <div className="empty-state">
          <div className="empty-icon">🔍</div>
          <h3 className="empty-title">No products found</h3>
          <p className="empty-text">
            Try adjusting your filters or search term.
          </p>
          {isFiltered && (
            <button
              className="empty-reset-btn"
              onClick={() => dispatch(resetFilters())}
            >
              🔄 Reset All Filters
            </button>
          )}
        </div>
      ) : (
        // Render the product grid when results exist
        <div className={`products-container ${viewMode === 'list' ? 'products-list' : 'products-grid'}`}>
          {/*
            TASK 4: .map() over filtered array → render one ProductCard per product
            
            THEORY — List Rendering in React:
              .map() transforms each data item into a JSX element.
              Each element MUST have a unique 'key' prop.
              React uses keys to efficiently update the DOM:
              - If key exists → update element (don't recreate)
              - If key is new → create new element
              - If key is gone → remove element
              Without keys, React re-renders the ENTIRE list on any change.
              With unique keys, React only updates changed items. (Reconciliation)
          */}
          {filtered.map(product => (
            <ProductCard
              key={product.id}
              // key={product.id}: unique key using product's id property
              // React requires this for list rendering performance
              
              product={product}
              // product is a PROP — passing data from parent (ProductGrid)
              //   to child (ProductCard)
              // THEORY: Unidirectional data flow — data flows parent → child via props
              // Child reads it via: const ProductCard = ({ product }) => { ... }
              
              viewMode={viewMode}
              // Also passing viewMode so ProductCard knows grid vs list layout
            />
          ))}
        </div>
      )}
    </div>
  )
}

export default ProductGrid
