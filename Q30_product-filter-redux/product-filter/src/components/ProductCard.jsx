// ============================================================
// FILE: src/components/ProductCard.jsx
// PURPOSE: Display a single product's details
//
// THEORY — Props (Unit V):
//   Props are data passed FROM parent TO child component.
//   They are READ-ONLY — child cannot modify props.
//   Like function parameters: (product) => JSX
//
//   Parent (ProductGrid) owns the data.
//   Child (ProductCard) just displays it.
//   This is "separation of concerns" — fetch/manage vs display.
//
// THEORY — Pure Component:
//   ProductCard is a pure functional component:
//   - Given the same props → always renders the same JSX
//   - No side effects, no API calls, no state
//   - Easiest to test and reuse
// ============================================================

import React, { useState } from 'react'
// useState: even child components can have LOCAL state
// Here we use it for "wish list" toggle (local — no need for Redux)

// ProductCard receives TWO props: product object and viewMode string
const ProductCard = ({ product, viewMode }) => {
  // Destructuring props: { product, viewMode } = props
  // Instead of props.product and props.viewMode

  // LOCAL STATE — not in Redux because only this card needs it
  // Redux is for SHARED state. Local UI state stays in useState.
  const [wished, setWished] = useState(false)
  // wished: whether this item is in local wishlist
  // setWished: toggles the heart icon

  // Helper: render star rating as filled/empty stars
  // This is a pure JavaScript function inside the component
  const renderStars = (rating) => {
    const full  = Math.floor(rating)   // Math.floor: rounds down (4.5 → 4)
    const half  = rating % 1 >= 0.5    // % is modulo: remainder (4.5 % 1 = 0.5)
    const empty = 5 - full - (half ? 1 : 0)
    // Build star string: "★★★★☆" etc.
    return '★'.repeat(full) + (half ? '½' : '') + '☆'.repeat(empty)
    // String.repeat(n): repeats string n times — "★".repeat(3) → "★★★"
  }

  // Price formatter — Indian locale adds ₹ and commas
  const formatPrice = (price) =>
    new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 })
      .format(price)
  // Intl.NumberFormat: built-in JS internationalization API
  // 'en-IN': English (India) locale → formats 2999 as ₹2,999

  return (
    // Dynamic className: adds 'product-card--list' class in list view
    // className is JSX's way to set HTML class attribute
    // (can't use 'class' — it's a reserved word in JavaScript)
    <div className={`product-card ${viewMode === 'list' ? 'product-card--list' : ''} ${!product.inStock ? 'product-card--out' : ''}`}>

      {/* Out of stock overlay badge */}
      {!product.inStock && (
        // Conditional rendering: only shows if NOT in stock
        <div className="out-of-stock-badge">Out of Stock</div>
      )}

      {/* Wishlist heart button — uses LOCAL useState (not Redux) */}
      <button
        className={`wish-btn ${wished ? 'wish-btn--active' : ''}`}
        onClick={() => setWished(!wished)}
        // setWished(!wished): toggles boolean (true→false, false→true)
        // This only affects THIS card's local state — not shared globally
        title={wished ? 'Remove from wishlist' : 'Add to wishlist'}
      >
        {wished ? '❤️' : '🤍'}
        {/* Ternary: show filled/empty heart based on wished state */}
      </button>

      {/* Product Image (emoji used as image for simplicity) */}
      <div className={`product-image ${viewMode === 'list' ? 'product-image--sm' : ''}`}>
        <span className="product-emoji">{product.image}</span>
        {/* product.image comes from props — passed from ProductGrid via Redux data */}
      </div>

      {/* Product Info */}
      <div className="product-info">
        {/* Category badge */}
        <span className="product-category">{product.category}</span>

        {/* Product name */}
        <h3 className="product-name">{product.name}</h3>
        {/* htmlspecialchars not needed in React — JSX auto-escapes output */}

        {/* Brand */}
        <p className="product-brand">by {product.brand}</p>

        {/* Description — only show in grid mode to save space in list mode */}
        {viewMode !== 'list' && (
          <p className="product-desc">{product.description}</p>
        )}

        {/* Star Rating */}
        <div className="product-rating">
          <span className="stars">{renderStars(product.rating)}</span>
          <span className="rating-num">{product.rating}</span>
        </div>

        {/* Price + Stock + Add to Cart */}
        <div className="product-footer">
          <div>
            <span className="product-price">{formatPrice(product.price)}</span>
            {/* Stock status — conditional rendering */}
            <span className={`stock-status ${product.inStock ? 'in-stock' : 'no-stock'}`}>
              {product.inStock ? '✓ In Stock' : '✗ Out of Stock'}
            </span>
          </div>

          <button
            className="add-cart-btn"
            disabled={!product.inStock}
            // disabled: HTML attribute — button unclickable when out of stock
          >
            {product.inStock ? '🛒 Add' : '🔔 Notify'}
          </button>
        </div>
      </div>

    </div>
  )
}

export default ProductCard
