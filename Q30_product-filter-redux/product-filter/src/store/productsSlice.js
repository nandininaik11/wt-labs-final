// ============================================================
// FILE: src/store/productsSlice.js
// PURPOSE: THE HEART OF THIS APP — Redux state, actions, reducers
//
// TASKS ADDRESSED:
//   ✅ Task 1 — Store product data in Redux state
//   ✅ Task 2 — Create actions for filtering products
//   ✅ Task 3 — Implement reducer for filter logic
//
// ============================================================
// REDUX THEORY (Unit V Syllabus):
//
// WHAT IS REDUX?
//   Redux is a GLOBAL STATE MANAGEMENT library.
//   Problem without Redux: Components deep in the tree need data from
//   components far away → "prop drilling" (passing props through many
//   levels) becomes messy and hard to maintain.
//   Redux solution: ONE central store holds ALL state. Any component
//   can read from or write to this store directly.
//
// FLUX ARCHITECTURE (Redux is an implementation of Flux):
//   User Interaction
//       ↓
//   dispatch(action)      ← component sends an action
//       ↓
//   Redux Store           ← receives action
//       ↓
//   Reducer               ← pure function: (state, action) → newState
//       ↓
//   New State             ← store updated
//       ↓
//   useSelector()         ← component re-reads new state
//       ↓
//   Re-render             ← React updates the UI
//
// THREE CORE PRINCIPLES OF REDUX:
//   1. Single Source of Truth: ONE store for all app state
//   2. State is Read-Only: ONLY reducers can change state (via dispatch)
//   3. Pure Functions: reducers must be pure (same input → same output, no side effects)
//
// REDUX TOOLKIT (RTK):
//   Modern way to write Redux. Eliminates boilerplate.
//   createSlice() generates BOTH action creators AND reducer in one call.
//   Uses Immer internally → we can write "mutating" code that becomes immutable updates.
// ============================================================

import { createSlice } from '@reduxjs/toolkit'
// createSlice: RTK function that creates a "slice" of global state
// A slice = one section of the Redux store (products, auth, cart, etc.)

import products from '../data/products'
// Import the 20 product objects array

// ============================================================
// TASK 1: INITIAL STATE — Shape of Redux state when app starts
// ============================================================
// initialState is a JavaScript OBJECT containing:
//   - products: the full dataset (never filtered/mutated)
//   - filters:  all current filter selections
const initialState = {

  // ── TASK 1: Product data stored in Redux ──
  products: products,
  // This is the "single source of truth" for product data.
  // We NEVER modify this array — filtering creates a derived copy.

  // ── Filter state — what the user has selected ──
  filters: {
    category:   'All',        // 'All' or specific category like 'Electronics'
    priceMin:   0,            // Minimum price filter (₹)
    priceMax:   10000,        // Maximum price filter (₹)
    minRating:  0,            // Minimum star rating (0 = no filter)
    searchText: '',           // Text search in product name/brand
    sortBy:     'default',    // 'default' | 'price-asc' | 'price-desc' | 'rating'
    inStockOnly: false,       // Boolean: true = show only in-stock items
  },

  viewMode: 'grid',           // 'grid' | 'list' — layout toggle
}

// ============================================================
// createSlice() — generates actions + reducer together
// ============================================================
const productsSlice = createSlice({
  name: 'products',
  // 'name' prefixes all action type strings:
  // setCategory action type → "products/setCategory"
  // resetFilters action type → "products/resetFilters"

  initialState,
  // Shorthand for initialState: initialState (ES6 shorthand property name)

  // ============================================================
  // TASK 2 + TASK 3: REDUCERS (become action creators + reducer cases)
  // ============================================================
  // Each method here:
  //   - Becomes an ACTION CREATOR function (exported below)
  //   - Becomes a REDUCER CASE (handles the action in the store)
  //
  // REDUCER SIGNATURE: (state, action) => void
  //   state  = current Redux state
  //   action = { type: "products/setCategory", payload: "Electronics" }
  //   action.payload = the data sent with dispatch()
  //
  // RTK uses Immer library → we can write state.x = y (looks mutating)
  // Immer internally converts this to immutable state updates.
  // Classic Redux requires: return { ...state, filters: { ...state.filters, category: action.payload } }
  // RTK requires: state.filters.category = action.payload  ← much simpler!
  reducers: {

    // TASK 2: ACTION 1 — Filter by category
    setCategory: (state, action) => {
      // action.payload = category string e.g. "Electronics" or "All"
      state.filters.category = action.payload
      // Immer converts this to: { ...state, filters: { ...state.filters, category: action.payload } }
    },

    // TASK 2: ACTION 2 — Set minimum price
    setPriceMin: (state, action) => {
      // action.payload = number e.g. 500
      state.filters.priceMin = action.payload
    },

    // TASK 2: ACTION 3 — Set maximum price
    setPriceMax: (state, action) => {
      // action.payload = number e.g. 5000
      state.filters.priceMax = action.payload
    },

    // TASK 2: ACTION 4 — Filter by minimum star rating
    setMinRating: (state, action) => {
      // action.payload = number: 0, 1, 2, 3, 4, or 5
      state.filters.minRating = action.payload
    },

    // TASK 2: ACTION 5 — Text search filter
    setSearchText: (state, action) => {
      // action.payload = string e.g. "headphones"
      state.filters.searchText = action.payload
    },

    // TASK 2: ACTION 6 — Sort order
    setSortBy: (state, action) => {
      // action.payload = 'default' | 'price-asc' | 'price-desc' | 'rating'
      state.filters.sortBy = action.payload
    },

    // TASK 2: ACTION 7 — In-stock toggle
    setInStockOnly: (state, action) => {
      // action.payload = boolean true or false
      state.filters.inStockOnly = action.payload
    },

    // TASK 2: ACTION 8 — Grid/List view toggle
    setViewMode: (state, action) => {
      state.viewMode = action.payload
    },

    // TASK 5: ACTION — RESET ALL FILTERS to defaults
    // THEORY: Reset restores state.filters to the exact same object
    // as initialState.filters. All components reading these values
    // will re-render with "All" category, full price range, etc.
    resetFilters: (state) => {
      state.filters = {
        category:    'All',
        priceMin:    0,
        priceMax:    10000,
        minRating:   0,
        searchText:  '',
        sortBy:      'default',
        inStockOnly: false,
      }
      // No action.payload needed — reset uses hardcoded defaults
    },
  },
})

// ============================================================
// TASK 2: Export action creators
// ============================================================
// productsSlice.actions contains all the generated action creator functions.
// When called, they return: { type: "products/setCategory", payload: value }
// Components import these and call: dispatch(setCategory("Electronics"))
export const {
  setCategory,
  setPriceMin,
  setPriceMax,
  setMinRating,
  setSearchText,
  setSortBy,
  setInStockOnly,
  setViewMode,
  resetFilters,
} = productsSlice.actions

// ============================================================
// TASK 3 + TASK 4: SELECTOR — Derive filtered products
// ============================================================
// A selector is a function: (rootState) → derivedValue
// Called in components via: const data = useSelector(selectFilteredProducts)
// Every time Redux state changes, this selector runs and returns new filtered data.
//
// THEORY: We NEVER store filteredProducts in Redux state.
// We compute it from existing state. This is "derived state" pattern.
// Storing derived state causes duplication and inconsistency bugs.
export const selectFilteredProducts = (rootState) => {
  // rootState = entire Redux store
  // rootState.products = our slice (named 'products' in configureStore)
  const { products, filters, viewMode } = rootState.products

  const { category, priceMin, priceMax, minRating, searchText, sortBy, inStockOnly } = filters
  // ES6 destructuring: extract named properties from filters object

  // ── TASK 3: Filter logic ──
  // Start with ALL products, progressively apply each active filter

  let result = products
  // 'let' because we reassign result several times

  // Filter 1: Category
  if (category !== 'All') {
    result = result.filter(p => p.category === category)
    // Array.filter(): returns NEW array with only elements where callback returns true
    // p => p.category === category  is an arrow function returning a boolean
  }

  // Filter 2: Price range
  result = result.filter(p => p.price >= priceMin && p.price <= priceMax)
  // && = logical AND: price must be >= min AND <= max

  // Filter 3: Minimum rating
  if (minRating > 0) {
    result = result.filter(p => p.rating >= minRating)
  }

  // Filter 4: Search text (case-insensitive, searches name + brand + description)
  if (searchText.trim() !== '') {
    const q = searchText.toLowerCase()
    // .toLowerCase() normalizes case: "Laptop" === "laptop"
    result = result.filter(p =>
      p.name.toLowerCase().includes(q) ||
      p.brand.toLowerCase().includes(q) ||
      p.description.toLowerCase().includes(q)
      // String.includes(): returns true if string contains the substring
      // || = OR: match if ANY field contains the search text
    )
  }

  // Filter 5: In-stock only
  if (inStockOnly) {
    result = result.filter(p => p.inStock === true)
  }

  // Sort the filtered results
  // IMPORTANT: [...result] spread creates a copy before sorting!
  // Array.sort() mutates the original — we must sort a COPY to keep Redux state immutable
  result = [...result]

  if (sortBy === 'price-asc') {
    result.sort((a, b) => a.price - b.price)
    // comparator: negative → a before b → ascending price
  } else if (sortBy === 'price-desc') {
    result.sort((a, b) => b.price - a.price)
    // positive → b before a → descending price
  } else if (sortBy === 'rating') {
    result.sort((a, b) => b.rating - a.rating)
    // higher rating first
  } else if (sortBy === 'name') {
    result.sort((a, b) => a.name.localeCompare(b.name))
    // localeCompare: proper alphabetical comparison for strings
  }

  return { filtered: result, viewMode }
  // Return both the filtered array and view mode — components need both
}

// Selector: get all unique category names for filter buttons
export const selectCategories = (rootState) => {
  const cats = rootState.products.products.map(p => p.category)
  // .map() transforms each product to just its category string
  return ['All', ...new Set(cats)]
  // new Set(cats) removes duplicates (Set = unique values only)
  // Spread ... converts Set back to array
  // Prepend 'All' as first option
}

// Selector: get current filter state (for filter panel display)
export const selectFilters = (rootState) => rootState.products.filters

// Selector: check if ANY filter is active (not at default)
// Used to show/highlight the Reset button
export const selectIsFiltered = (rootState) => {
  const f = rootState.products.filters
  return (
    f.category    !== 'All'     ||
    f.priceMin    !== 0         ||
    f.priceMax    !== 10000     ||
    f.minRating   !== 0         ||
    f.searchText  !== ''        ||
    f.sortBy      !== 'default' ||
    f.inStockOnly !== false
  )
  // Returns boolean: true if any filter differs from its default
}

// Export the reducer — needed by configureStore()
export default productsSlice.reducer
