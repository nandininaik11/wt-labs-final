// ============================================================
// FILE: src/components/CurrencyConverter.jsx
// PURPOSE: Main component — contains all state and conversion logic
//
// KEY REACT CONCEPTS DEMONSTRATED:
// 1. useState Hook — manages state (data that changes over time)
// 2. Event Handlers — respond to user actions (typing, clicking)
// 3. Controlled Components — form inputs controlled by React state
// 4. Conditional Rendering — show/hide elements based on state
// 5. Props — passing data from parent to child component
// 6. List Rendering — rendering arrays with .map()
// ============================================================

import React, { useState } from 'react'
// useState is a React HOOK
// Hooks are special functions that let function components use React features
// useState was introduced in React 16.8 to replace class component state

import ConversionHistory from './ConversionHistory'
// Import child component for displaying conversion history

// ============================================================
// CONVERSION RATES (Static — normally would come from an API)
// In a real app, you'd fetch from: https://api.exchangerate-api.com
// ============================================================
const RATES = {
  USD_TO_INR: 83.50,    // 1 USD = 83.50 INR (approximate)
  USD_TO_EUR: 0.92,     // 1 USD = 0.92 EUR (bonus feature)
  USD_TO_GBP: 0.79,     // 1 USD = 0.79 GBP (bonus feature)
  USD_TO_JPY: 149.50,   // 1 USD = 149.50 JPY (bonus feature)
}

// Currency metadata for display
const CURRENCIES = [
  { code: 'INR', symbol: '₹', name: 'Indian Rupee',    flag: '🇮🇳', rate: RATES.USD_TO_INR },
  { code: 'EUR', symbol: '€', name: 'Euro',             flag: '🇪🇺', rate: RATES.USD_TO_EUR },
  { code: 'GBP', symbol: '£', name: 'British Pound',   flag: '🇬🇧', rate: RATES.USD_TO_GBP },
  { code: 'JPY', symbol: '¥', name: 'Japanese Yen',    flag: '🇯🇵', rate: RATES.USD_TO_JPY },
]

// ============================================================
// MAIN COMPONENT: CurrencyConverter
// This is a Functional Component (arrow function style)
// ============================================================
const CurrencyConverter = () => {

  // ---- STATE DECLARATIONS ----
  // useState(initialValue) returns [currentValue, setterFunction]
  // When setter is called, React re-renders the component with new value

  const [amount, setAmount] = useState('')
  // amount: the USD value the user types
  // setAmount: function to update amount
  // '': initial value is empty string (empty input field)

  const [selectedCurrency, setSelectedCurrency] = useState(CURRENCIES[0])
  // selectedCurrency: which target currency is selected (default: INR)
  // Stored as the full currency object (code, symbol, rate, etc.)

  const [result, setResult] = useState(null)
  // result: the converted amount (null means "not yet calculated")
  // null initial value means: don't show result section yet

  const [history, setHistory] = useState([])
  // history: array of past conversions
  // [] initial value: empty array (no conversions yet)

  const [error, setError] = useState('')
  // error: validation error message string
  // '': no error initially

  const [isDark, setIsDark] = useState(false)
  // isDark: boolean for dark/light theme toggle

  // ---- EVENT HANDLERS ----
  // Event handlers are functions called when user interacts with UI

  // Called every time user types in the input field
  const handleAmountChange = (event) => {
    // event: the DOM event object
    // event.target: the input element that triggered the event
    // event.target.value: what the user typed
    const value = event.target.value

    setAmount(value)   // Update state with new typed value
    setError('')       // Clear any existing error when user types
    setResult(null)    // Clear result when input changes
  }

  // Called when user selects a different target currency
  const handleCurrencyChange = (currency) => {
    setSelectedCurrency(currency)  // Update selected currency state
    setResult(null)                // Clear result (needs recalculation)
  }

  // Called when user clicks the Convert button
  const handleConvert = () => {
    // INPUT VALIDATION
    // Always validate user input before processing!
    
    if (amount === '' || amount === null) {
      setError('⚠️ Please enter an amount to convert.')
      return  // Stop function execution if invalid
    }

    const numericAmount = parseFloat(amount)
    // parseFloat() converts string to decimal number
    // e.g., "10.5" → 10.5

    if (isNaN(numericAmount)) {
      // isNaN() = "Is Not a Number?" — returns true if value is not a valid number
      setError('⚠️ Please enter a valid number.')
      return
    }

    if (numericAmount <= 0) {
      setError('⚠️ Amount must be greater than zero.')
      return
    }

    if (numericAmount > 10000000) {
      setError('⚠️ Amount too large. Maximum is $10,000,000.')
      return
    }

    // CALCULATION
    const converted = (numericAmount * selectedCurrency.rate).toFixed(2)
    // toFixed(2) rounds to 2 decimal places and returns a string
    // e.g., 83.5 * 10 = 835.0 → "835.00"

    setResult(converted)  // Update result state → triggers re-render

    // ADD TO HISTORY
    // Create a new history entry object
    const newEntry = {
      id: Date.now(),
      // Date.now() returns milliseconds since Jan 1 1970 — unique ID
      
      amount: numericAmount,
      currency: selectedCurrency.code,
      symbol: selectedCurrency.symbol,
      result: converted,
      rate: selectedCurrency.rate,
      timestamp: new Date().toLocaleTimeString()
      // toLocaleTimeString() formats time as "10:30:45 AM"
    }

    // IMMUTABLE STATE UPDATE
    // NEVER directly mutate state: history.push(newEntry) ← WRONG!
    // Instead, create a new array with spread operator:
    setHistory(prevHistory => [newEntry, ...prevHistory].slice(0, 5))
    // prevHistory: the old array (functional update form)
    // [newEntry, ...prevHistory]: new array with new entry FIRST
    // .slice(0, 5): keep only the 5 most recent conversions
    // Spread operator (...) unpacks array elements into a new array
  }

  // Called when user clicks the Reset/Clear button
  const handleReset = () => {
    setAmount('')           // Clear input field
    setResult(null)         // Hide result
    setError('')            // Clear errors
    // Note: history is NOT cleared — user might want to see past conversions
  }

  // Called when user presses a key in the input field
  const handleKeyDown = (event) => {
    // event.key: which key was pressed
    if (event.key === 'Enter') {
      // If user presses Enter, trigger conversion (like clicking the button)
      handleConvert()
    }
  }

  // Find the current currency object for display
  const currentCurrency = selectedCurrency

  // ---- RENDER (JSX RETURN) ----
  // This JSX is what gets displayed in the browser
  // React re-renders this whenever state changes

  return (
    <div className={`converter-container ${isDark ? 'dark' : 'light'}`}>
    {/* Template literal in className: adds 'dark' or 'light' class based on state */}

      {/* THEME TOGGLE */}
      <button
        className="theme-toggle"
        onClick={() => setIsDark(!isDark)}
        // onClick: event handler for click event
        // Arrow function: () => setIsDark(!isDark)
        // !isDark: toggles boolean (true → false, false → true)
        title="Toggle dark/light mode"
      >
        {isDark ? '☀️ Light Mode' : '🌙 Dark Mode'}
        {/* Ternary operator: condition ? valueIfTrue : valueIfFalse */}
        {/* This is CONDITIONAL RENDERING — React shows different JSX based on state */}
      </button>

      {/* RATE INFO BANNER */}
      <div className="rate-info">
        <span>📊 Live Rate:</span>
        <strong> 1 USD = {currentCurrency.symbol}{currentCurrency.rate} {currentCurrency.code}</strong>
        {/* { } in JSX: evaluate JavaScript expression and display its value */}
      </div>

      {/* CURRENCY SELECTOR TABS */}
      <div className="currency-tabs">
        {CURRENCIES.map(currency => (
          // .map(): transforms array into JSX elements — LIST RENDERING
          // Each mapped element MUST have a unique "key" prop
          // Key helps React identify which items changed, added, or removed
          <button
            key={currency.code}
            // key: unique identifier for React's reconciliation algorithm
            
            className={`tab-btn ${selectedCurrency.code === currency.code ? 'active' : ''}`}
            // Conditionally add 'active' class to selected currency tab
            
            onClick={() => handleCurrencyChange(currency)}
            // Pass currency object to handler when clicked
          >
            {currency.flag} {currency.code}
          </button>
        ))}
      </div>

      {/* INPUT SECTION */}
      <div className="input-section">
        <label className="input-label" htmlFor="amountInput">
          {/* htmlFor (not "for") — JSX uses htmlFor because "for" is a JS keyword */}
          💵 Enter Amount in US Dollars (USD)
        </label>

        <div className="input-group">
          <span className="currency-prefix">$</span>
          
          <input
            id="amountInput"
            type="number"
            // type="number": shows numeric keyboard on mobile, prevents letters
            
            className={`amount-input ${error ? 'input-error' : ''}`}
            // Conditionally add error class if there's a validation error
            
            placeholder="e.g. 100"
            value={amount}
            // CONTROLLED COMPONENT: value is bound to React state
            // React state controls what's displayed in the input
            // Without this, the input would be "uncontrolled" (DOM manages itself)
            
            onChange={handleAmountChange}
            // onChange fires every time the input value changes (each keystroke)
            // This is the most important event for form inputs in React
            
            onKeyDown={handleKeyDown}
            // onKeyDown fires when a key is pressed
            
            min="0"
            step="0.01"
            // step="0.01": allows decimal values in number input
          />
        </div>

        {/* ERROR MESSAGE — only shown when error state is not empty */}
        {error && (
          // Short-circuit evaluation: if error is truthy, render the div
          // If error is '' (empty string, falsy), nothing renders
          <p className="error-message">{error}</p>
        )}
      </div>

      {/* ACTION BUTTONS */}
      <div className="button-group">
        <button
          className="btn-convert"
          onClick={handleConvert}
          // onClick calls handleConvert which validates and calculates
          disabled={!amount}
          // disabled: HTML attribute that greys out button when amount is empty
          // !amount: true when amount is '', 0, null, undefined (falsy values)
        >
          🔄 Convert to {currentCurrency.code}
        </button>

        <button
          className="btn-reset"
          onClick={handleReset}
        >
          🗑️ Clear
        </button>
      </div>

      {/* RESULT SECTION — Conditional Rendering */}
      {result !== null && (
        // Only renders when result is not null (after a successful conversion)
        // null !== null is false, so this block is hidden initially
        <div className="result-section">
          <div className="result-card">
            <div className="result-from">
              <span className="flag-large">🇺🇸</span>
              <div>
                <p className="result-label">You entered</p>
                <p className="result-value-usd">
                  $ {parseFloat(amount).toLocaleString()}
                  {/* toLocaleString() formats number with commas: 1000 → "1,000" */}
                  <span className="result-currency"> USD</span>
                </p>
              </div>
            </div>

            <div className="result-arrow">⟶</div>

            <div className="result-to">
              <span className="flag-large">{currentCurrency.flag}</span>
              <div>
                <p className="result-label">Converts to</p>
                <p className="result-value-converted">
                  {currentCurrency.symbol}
                  {parseFloat(result).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                    // minimumFractionDigits: always show at least 2 decimal places
                    // maximumFractionDigits: never show more than 2
                  })}
                  <span className="result-currency"> {currentCurrency.code}</span>
                </p>
              </div>
            </div>
          </div>

          <p className="rate-note">
            Exchange Rate: 1 USD = {currentCurrency.symbol}{currentCurrency.rate} {currentCurrency.code}
            <span className="rate-note-small"> (static rate — update for live rates)</span>
          </p>
        </div>
      )}

      {/* CONVERSION HISTORY COMPONENT */}
      {/* 
        PROPS: Passing data from parent (CurrencyConverter) to child (ConversionHistory)
        Props are like function arguments — they customize child components.
        Parent owns the data (history, clearHistory function).
        Child just displays it.
        Data flows ONE-WAY: Parent → Child (React's unidirectional data flow)
      */}
      <ConversionHistory
        history={history}
        // history prop: passes the history array DOWN to child
        
        onClear={() => setHistory([])}
        // onClear prop: passes a function DOWN to child
        // Child can CALL this function, which updates parent's state
        // This is how CHILD communicates back to PARENT (callback pattern)
      />

    </div>
  )
}

// Named export: can also be imported as: import { CurrencyConverter } from '...'
// But we use default export for the main component of a file
export default CurrencyConverter
