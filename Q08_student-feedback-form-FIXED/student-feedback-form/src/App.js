// Import React and the useState hook for state management
import React, { useState } from 'react';

// Import child components
import FeedbackForm from './components/FeedbackForm';
import FeedbackList from './components/FeedbackList';

// Import CSS styles
import './App.css';

/**
 * MAIN APP COMPONENT
 * ==================
 * This is the parent component that manages the application state.
 * It demonstrates:
 * - Component composition (parent-child relationship)
 * - State management using useState hook
 * - Passing data from parent to child via props
 * - Passing callbacks from parent to child to update state
 */

function App() {
  /**
   * STATE MANAGEMENT - useState Hook
   * =================================
   * useState is a React Hook that lets you add state to functional components.
   * 
   * Syntax: const [stateVariable, setterFunction] = useState(initialValue);
   * 
   * - feedbackList: array that stores all submitted feedback
   * - setFeedbackList: function to update feedbackList
   * - []: initial value is an empty array
   * 
   * VIVA POINT: Why use hooks instead of class components?
   * - Cleaner code, no 'this' keyword confusion
   * - Easier to share stateful logic between components
   * - Better performance and optimization
   * - Functional programming paradigm
   */
  const [feedbackList, setFeedbackList] = useState([]);

  /**
   * CALLBACK FUNCTION - Handling Form Submission
   * =============================================
   * This function is passed as a prop to the FeedbackForm component.
   * When the form is submitted, this function receives the feedback data.
   * 
   * @param {Object} feedbackData - Contains studentName, courseName, rating, comments
   * 
   * CONCEPT: Lifting State Up
   * - The state (feedbackList) lives in the parent (App)
   * - The child (FeedbackForm) cannot directly modify parent state
   * - So parent passes down a callback function
   * - Child calls this function with new data
   * - Parent updates its own state
   */
  const handleFeedbackSubmit = (feedbackData) => {
    // Create a new feedback object with unique ID and timestamp
    const newFeedback = {
      id: Date.now(), // Unique ID using current timestamp (milliseconds since 1970)
      ...feedbackData, // Spread operator to copy all properties from feedbackData
      submittedAt: new Date().toLocaleString(), // Human-readable date/time
    };

    /**
     * UPDATE STATE - Immutability Principle
     * ======================================
     * In React, you should never modify state directly.
     * Always create a new array/object when updating state.
     * 
     * Why? Because React uses shallow comparison to detect changes.
     * If you mutate the existing array, React won't know it changed.
     * 
     * BAD:  feedbackList.push(newFeedback)  ❌ Mutates original
     * GOOD: setFeedbackList([...feedbackList, newFeedback])  ✅ Creates new array
     * 
     * The spread operator (...) creates a new array with all old items plus the new one
     */
    setFeedbackList([...feedbackList, newFeedback]);
  };

  /**
   * CALLBACK FUNCTION - Deleting Feedback
   * ======================================
   * This function removes a feedback item from the list.
   * 
   * @param {number} id - The unique ID of the feedback to delete
   * 
   * CONCEPT: Array filter method
   * - filter() creates a new array with items that pass a test
   * - We keep all items where feedback.id !== id (all except the one to delete)
   * - This maintains immutability (creates new array, doesn't modify original)
   */
  const handleDeleteFeedback = (id) => {
    setFeedbackList(feedbackList.filter((feedback) => feedback.id !== id));
  };

  /**
   * JSX RETURN - Component Render
   * ==============================
   * JSX (JavaScript XML) allows us to write HTML-like code in JavaScript.
   * It gets compiled to React.createElement() calls by Babel.
   * 
   * RULES:
   * - Must return a single parent element (here it's <div className="App">)
   * - Use className instead of class (class is reserved in JavaScript)
   * - Use camelCase for attributes (onClick, onChange, etc.)
   * - JavaScript expressions go inside curly braces {}
   */
  return (
    <div className="App">
      {/* Header section with title */}
      <header className="app-header">
        <h1 className="app-title">📚 Student Feedback System</h1>
        <p className="app-subtitle">Help us improve by sharing your experience</p>
      </header>

      {/* Main content area with two columns */}
      <div className="app-content">
        {/* LEFT COLUMN: Feedback Form */}
        <div className="form-section">
          {/**
           * PROPS - Passing Data to Child Components
           * =========================================
           * onSubmit is a prop (property) we're passing to FeedbackForm.
           * Props flow down from parent to child (one-way data flow).
           * 
           * Inside FeedbackForm, it can access this via props.onSubmit
           * When FeedbackForm calls props.onSubmit(data), it executes
           * the handleFeedbackSubmit function defined here in the parent.
           */}
          <FeedbackForm onSubmit={handleFeedbackSubmit} />
        </div>

        {/* RIGHT COLUMN: Feedback List */}
        <div className="list-section">
          {/**
           * CONDITIONAL RENDERING
           * =====================
           * We only show the FeedbackList if there are items to display.
           * 
           * feedbackList.length > 0 ? <Component /> : <Message />
           * 
           * If true: render FeedbackList component
           * If false: render "No feedback submitted yet" message
           * 
           * PROPS BEING PASSED:
           * - feedbacks: the array of feedback objects (data flows down)
           * - onDelete: callback function for deleting items (behavior flows down)
           */}
          {feedbackList.length > 0 ? (
            <FeedbackList 
              feedbacks={feedbackList} 
              onDelete={handleDeleteFeedback} 
            />
          ) : (
            <div className="no-feedback">
              <p>📝 No feedback submitted yet.</p>
              <p className="hint">Fill out the form to get started!</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

/**
 * EXPORT - Making Component Available
 * ====================================
 * export default allows other files to import this component.
 * 
 * In index.js, we did: import App from './App';
 * This import statement works because of this export.
 */
export default App;

/**
 * SUMMARY OF REACT CONCEPTS USED IN THIS FILE:
 * ============================================
 * ✅ Functional Components
 * ✅ useState Hook for state management
 * ✅ Props (passing data and callbacks to children)
 * ✅ JSX syntax
 * ✅ Conditional rendering
 * ✅ Component composition (parent-child structure)
 * ✅ Immutability (never mutate state directly)
 * ✅ Event handling (through callbacks)
 * ✅ Array methods (filter, spread operator)
 */
