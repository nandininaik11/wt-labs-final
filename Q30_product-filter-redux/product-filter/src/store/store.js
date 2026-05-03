// ============================================================
// FILE: src/store/store.js
// PURPOSE: Create and configure the Redux store
//
// THEORY: The Redux STORE is a single JavaScript object that holds
// ALL application state. There is only ONE store per app (single source of truth).
//
// configureStore() from Redux Toolkit:
//   - Creates the store
//   - Sets up Redux DevTools automatically (for debugging in browser)
//   - Enables helpful middleware (like serializability check)
//
// The 'reducer' object maps slice names to their reducers:
//   { products: productsReducer }
//   This means state is accessible as: state.products.xxx
// ============================================================

import { configureStore } from '@reduxjs/toolkit'
// configureStore: RTK helper — wraps Redux's createStore() with defaults

import productsReducer from './productsSlice'
// Import the reducer from our slice (the default export)

const store = configureStore({
  reducer: {
    // KEY: 'products' → this becomes the state namespace
    // Any component reading state does: state.products.filters, state.products.products
    // VALUE: productsReducer → the function that handles state updates for this slice
    products: productsReducer,
  },
  // Redux Toolkit automatically adds:
  //   - redux-thunk middleware (for async actions)
  //   - Serializability middleware (warns if non-serializable values in state)
  //   - Redux DevTools Extension support
})

// Named export: import { store } from './store/store'
export { store }
