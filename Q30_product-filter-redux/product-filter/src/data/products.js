// ============================================================
// FILE: src/data/products.js
// PURPOSE: Static product dataset — loaded into Redux store on startup
//
// THEORY (Unit II — JS Objects & Arrays):
//   Object: { key: value, key: value } — collection of named properties
//   Array:  [ item1, item2, item3 ]   — ordered list of items
//   Array of Objects: the most common data structure in web apps
//
// In a real app this data would come from a REST API via fetch().
// For this lab we use static data to keep focus on Redux concepts.
// ============================================================

const products = [
  // ──────────── Electronics ────────────
  { id: 1,  name: "Wireless Headphones",   category: "Electronics",   price: 2999, rating: 4.5, image: "🎧", brand: "SoundMax",    inStock: true,  description: "Premium noise-cancelling over-ear headphones" },
  { id: 2,  name: "Mechanical Keyboard",   category: "Electronics",   price: 4500, rating: 4.7, image: "⌨️", brand: "KeyPro",      inStock: true,  description: "RGB backlit mechanical gaming keyboard" },
  { id: 3,  name: "Smart Watch",           category: "Electronics",   price: 8999, rating: 4.3, image: "⌚", brand: "FitTech",     inStock: true,  description: "Fitness tracker with heart rate monitoring" },
  { id: 4,  name: "Bluetooth Speaker",     category: "Electronics",   price: 1799, rating: 4.1, image: "🔊", brand: "SoundMax",    inStock: false, description: "Portable waterproof wireless speaker" },
  { id: 5,  name: "USB-C Hub",             category: "Electronics",   price: 1299, rating: 4.4, image: "🔌", brand: "TechHub",     inStock: true,  description: "7-in-1 USB-C hub with HDMI and USB ports" },

  // ──────────── Clothing ────────────
  { id: 6,  name: "Cotton Kurta",          category: "Clothing",      price: 799,  rating: 4.2, image: "👕", brand: "FabIndia",    inStock: true,  description: "Comfortable everyday cotton kurta" },
  { id: 7,  name: "Denim Jeans",           category: "Clothing",      price: 1499, rating: 4.0, image: "👖", brand: "DenimCo",     inStock: true,  description: "Slim fit stretch denim jeans" },
  { id: 8,  name: "Running Shoes",         category: "Clothing",      price: 3499, rating: 4.6, image: "👟", brand: "SpeedRun",    inStock: true,  description: "Lightweight breathable running shoes" },
  { id: 9,  name: "Winter Jacket",         category: "Clothing",      price: 2999, rating: 4.3, image: "🧥", brand: "WinterWear",  inStock: false, description: "Warm insulated winter jacket with hood" },

  // ──────────── Books ────────────
  { id: 10, name: "Clean Code",            category: "Books",         price: 599,  rating: 4.8, image: "📘", brand: "Robert Martin", inStock: true,  description: "Handbook of agile software craftsmanship" },
  { id: 11, name: "JavaScript: The Good Parts", category: "Books",   price: 449,  rating: 4.5, image: "📗", brand: "D. Crockford", inStock: true,  description: "Unearthing the excellence in JavaScript" },
  { id: 12, name: "Design Patterns",       category: "Books",         price: 699,  rating: 4.6, image: "📙", brand: "Gang of Four", inStock: true,  description: "Elements of Reusable Object-Oriented Software" },
  { id: 13, name: "Pragmatic Programmer",  category: "Books",         price: 549,  rating: 4.7, image: "📕", brand: "Hunt & Thomas", inStock: false, description: "Your journey to mastery in software" },

  // ──────────── Home & Kitchen ────────────
  { id: 14, name: "Coffee Maker",          category: "Home & Kitchen", price: 3299, rating: 4.4, image: "☕", brand: "BrewMaster",  inStock: true,  description: "Drip coffee maker with thermal carafe" },
  { id: 15, name: "Air Fryer",             category: "Home & Kitchen", price: 4999, rating: 4.5, image: "🍳", brand: "CrispCook",   inStock: true,  description: "3.5L digital air fryer with 8 presets" },
  { id: 16, name: "Blender",              category: "Home & Kitchen", price: 1999, rating: 4.2, image: "🥤", brand: "BlendPro",    inStock: true,  description: "High-speed countertop blender 1200W" },

  // ──────────── Sports ────────────
  { id: 17, name: "Yoga Mat",             category: "Sports",         price: 899,  rating: 4.3, image: "🧘", brand: "ZenFit",      inStock: true,  description: "Non-slip premium TPE yoga mat 6mm" },
  { id: 18, name: "Resistance Bands Set", category: "Sports",         price: 599,  rating: 4.4, image: "🏋️", brand: "FitBand",     inStock: true,  description: "Set of 5 resistance bands for home workouts" },
  { id: 19, name: "Water Bottle",         category: "Sports",         price: 699,  rating: 4.1, image: "🚰", brand: "HydroFlask",  inStock: true,  description: "Insulated stainless steel 1L bottle" },
  { id: 20, name: "Jump Rope",            category: "Sports",         price: 349,  rating: 4.0, image: "🪢", brand: "SpeedRope",   inStock: false, description: "Speed jump rope with ball bearings" },
]

export default products
// export default: makes array importable as: import products from './data/products'
