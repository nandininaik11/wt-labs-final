// ============================================================
// FILE: src/components/ConversionHistory.jsx
// PURPOSE: Child component — displays past conversions
//
// KEY CONCEPTS:
// 1. Props — receives data from parent component
// 2. Destructuring props — clean syntax to extract prop values
// 3. Conditional rendering — show/hide based on data
// 4. List rendering with .map() and keys
// 5. Callback props — child calls parent function
// ============================================================

import React from 'react'

// PROPS DESTRUCTURING in function parameter
// Instead of: const ConversionHistory = (props) => { ... props.history ... }
// We destructure: const ConversionHistory = ({ history, onClear }) => { ... history ... }
// Both are equivalent — destructuring is just cleaner syntax
const ConversionHistory = ({ history, onClear }) => {

  // If history array is empty, show nothing (or a placeholder)
  // history.length === 0 means no conversions done yet
  if (history.length === 0) {
    return (
      <div className="history-section">
        <h3 className="history-title">📜 Conversion History</h3>
        <div className="history-empty">
          <p>🔍 No conversions yet. Start by entering an amount above!</p>
        </div>
      </div>
    )
  }

  return (
    <div className="history-section">
      
      {/* Header with title and clear button */}
      <div className="history-header">
        <h3 className="history-title">📜 Conversion History</h3>
        <button
          className="btn-clear-history"
          onClick={onClear}
          // onClear is a PROP FUNCTION passed from parent
          // When clicked here, it calls setHistory([]) in the parent
          // This is how child → parent communication works in React
          // React calls this pattern: "lifting state up" + callback props
        >
          Clear All
        </button>
      </div>

      {/* HISTORY LIST */}
      <ul className="history-list">
        {history.map((entry, index) => (
          // .map(): transforms each history entry object into a <li> element
          
          // key={entry.id}: uses Date.now() timestamp as unique key
          // React uses keys to efficiently update the DOM
          // If key changes, React destroys and recreates the element
          // If same key, React updates the existing element
          <li key={entry.id} className="history-item">
            
            {/* Serial number — index starts at 0, +1 to show 1,2,3... */}
            <span className="history-number">#{index + 1}</span>
            
            <div className="history-details">
              <span className="history-conversion">
                {/* Display formatted conversion summary */}
                ${entry.amount.toLocaleString()} USD
                {' → '}
                {entry.symbol}{parseFloat(entry.result).toLocaleString(undefined, {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2
                })} {entry.currency}
              </span>
              <span className="history-time">🕐 {entry.timestamp}</span>
            </div>

            <span className="history-rate">
              Rate: {entry.symbol}{entry.rate}
            </span>

          </li>
        ))}
      </ul>

      <p className="history-note">Showing last {history.length} conversion(s)</p>

    </div>
  )
}

export default ConversionHistory
