// Import React
import React from 'react';

/**
 * FEEDBACK LIST COMPONENT
 * =======================
 * This component demonstrates:
 * 1. Rendering lists using .map()
 * 2. Using keys in list items (CRITICAL for React performance)
 * 3. Props (receiving data and callbacks from parent)
 * 4. Event handling (delete button)
 * 5. Conditional styling
 */

/**
 * PROPS DESTRUCTURING
 * ===================
 * Instead of: function FeedbackList(props) { ... }
 * We use: function FeedbackList({ feedbacks, onDelete }) { ... }
 * 
 * This destructures the props object immediately in the function parameters.
 * It's cleaner and more common in modern React.
 * 
 * feedbacks: Array of feedback objects from parent
 * onDelete: Callback function to delete a feedback item
 */
function FeedbackList({ feedbacks, onDelete }) {
  
  /**
   * HELPER FUNCTION - Get Star Display
   * ===================================
   * Converts numeric rating to star emoji string.
   * This is a pure function (same input = same output, no side effects).
   */
  const getStars = (rating) => {
    return '⭐'.repeat(parseInt(rating));
  };

  /**
   * HELPER FUNCTION - Get Rating Label
   * ===================================
   * Returns text label for rating number.
   * Uses switch statement for clarity.
   */
  const getRatingLabel = (rating) => {
    switch(rating) {
      case '5': return 'Excellent';
      case '4': return 'Very Good';
      case '3': return 'Good';
      case '2': return 'Fair';
      case '1': return 'Poor';
      default: return 'Unknown';
    }
  };

  /**
   * HELPER FUNCTION - Get Rating Color Class
   * =========================================
   * Returns CSS class name based on rating for conditional styling.
   */
  const getRatingClass = (rating) => {
    const ratingNum = parseInt(rating);
    if (ratingNum >= 4) return 'rating-excellent';
    if (ratingNum === 3) return 'rating-good';
    return 'rating-poor';
  };

  /**
   * JSX RENDER - LIST RENDERING
   * ============================
   */
  return (
    <div className="feedback-list-container">
      <h2 className="list-title">📋 Submitted Feedback ({feedbacks.length})</h2>
      
      {/**
       * RENDERING LISTS WITH .map()
       * ============================
       * .map() is the standard way to render lists in React.
       * It transforms each item in the array into JSX.
       * 
       * SYNTAX: array.map((item, index) => JSX)
       * 
       * Parameters:
       * - feedback: current item in iteration
       * - index: position in array (0, 1, 2, ...)
       * 
       * Returns: array of JSX elements
       * 
       * VIVA POINT: Why .map() instead of .forEach()?
       * ----------------------------------------------
       * .map() returns a new array (which React can render)
       * .forEach() returns undefined (can't be rendered)
       */}
      <div className="feedback-list">
        {feedbacks.map((feedback, index) => (
          /**
           * KEYS IN REACT LISTS - CRITICAL CONCEPT
           * =======================================
           * When rendering lists, each element needs a unique "key" prop.
           * 
           * WHY ARE KEYS IMPORTANT?
           * -----------------------
           * React uses keys to identify which items have changed, been added, or removed.
           * This helps React optimize re-renders and maintain component state correctly.
           * 
           * Without keys:
           * - React re-renders entire list on every change (slow)
           * - Component state can get mixed up
           * - Animations/transitions may break
           * 
           * With keys:
           * - React only re-renders changed items (fast)
           * - Component state is preserved correctly
           * - Smooth animations and transitions
           * 
           * CHOOSING GOOD KEYS:
           * -------------------
           * ✅ GOOD: Stable, unique IDs (feedback.id)
           * ❌ BAD: Array index (can cause bugs when list changes)
           * ❌ BAD: Random numbers (breaks React's reconciliation)
           * 
           * VIVA QUESTION: Why not use index as key?
           * -----------------------------------------
           * If you delete item 1:
           * - Item 2 becomes index 1
           * - Item 3 becomes index 2
           * React thinks index 1 is still the same component (but data changed)
           * This can cause wrong data to show or state to persist incorrectly.
           * 
           * Using stable IDs (feedback.id) prevents this issue.
           */
          <div 
            key={feedback.id}  // ← CRITICAL: Unique key for each list item
            className="feedback-card"
          >
            {/* Card header with student name and timestamp */}
            <div className="feedback-header">
              <div className="student-info">
                <h3 className="student-name">👤 {feedback.studentName}</h3>
                <p className="submission-time">🕒 {feedback.submittedAt}</p>
              </div>
              
              {/**
               * DELETE BUTTON - Event Handling
               * ===============================
               * onClick handler calls parent's onDelete function.
               * We pass feedback.id so parent knows which item to delete.
               * 
               * Arrow function: () => onDelete(feedback.id)
               * Why arrow function? To pass parameter to the callback.
               * 
               * If we wrote: onClick={onDelete(feedback.id)}
               * That would call onDelete immediately during render! (BUG)
               * 
               * With arrow function: onClick={() => onDelete(feedback.id)}
               * Creates a new function that calls onDelete when clicked.
               */}
              <button 
                onClick={() => onDelete(feedback.id)}
                className="delete-button"
                aria-label={`Delete feedback from ${feedback.studentName}`}
                title="Delete this feedback"
              >
                🗑️
              </button>
            </div>

            {/* Course name */}
            <div className="course-info">
              <strong>📚 Course:</strong> {feedback.courseName}
            </div>

            {/* Rating display with conditional styling */}
            <div className={`rating-display ${getRatingClass(feedback.rating)}`}>
              <div className="stars">{getStars(feedback.rating)}</div>
              <div className="rating-text">{getRatingLabel(feedback.rating)}</div>
            </div>

            {/* Comments section */}
            <div className="comments-section">
              <strong>💬 Comments:</strong>
              <p className="comments-text">{feedback.comments}</p>
            </div>

            {/* Visual separator between cards */}
            {/**
             * CONDITIONAL RENDERING
             * =====================
             * Only show separator if this is not the last item.
             * 
             * index !== feedbacks.length - 1 means "not the last item"
             * && is a short-circuit operator: if left is true, render right
             */}
            {index !== feedbacks.length - 1 && (
              <div className="card-separator"></div>
            )}
          </div>
        ))}
      </div>

      {/**
       * STATISTICS SECTION
       * ==================
       * Demonstrates working with array data to calculate statistics.
       */}
      <div className="feedback-stats">
        <div className="stat-item">
          <span className="stat-label">Total Feedback:</span>
          <span className="stat-value">{feedbacks.length}</span>
        </div>
        
        {/* Calculate average rating */}
        <div className="stat-item">
          <span className="stat-label">Average Rating:</span>
          <span className="stat-value">
            {/**
             * ARRAY REDUCE METHOD
             * ===================
             * reduce() combines all array values into a single value.
             * 
             * Syntax: array.reduce((accumulator, currentItem) => newAccumulator, initialValue)
             * 
             * Here we sum all ratings and divide by count to get average.
             */}
            {feedbacks.length > 0 
              ? (feedbacks.reduce((sum, fb) => sum + parseInt(fb.rating), 0) / feedbacks.length).toFixed(1)
              : '0'
            }
            ⭐
          </span>
        </div>
      </div>
    </div>
  );
}

// Export component
export default FeedbackList;

/**
 * SUMMARY OF CONCEPTS DEMONSTRATED:
 * ==================================
 * ✅ List Rendering with .map()
 * ✅ Keys in Lists (feedback.id as stable unique key)
 * ✅ Props (receiving data and callbacks from parent)
 * ✅ Event Handling (onClick for delete)
 * ✅ Conditional Rendering (separator between cards)
 * ✅ Conditional Styling (CSS classes based on rating)
 * ✅ Array Methods (.map(), .reduce())
 * ✅ Helper Functions (pure functions for calculations)
 * ✅ Accessibility (aria-labels, title attributes)
 * 
 * VIVA TOPICS COVERED:
 * ====================
 * - Why use keys in lists?
 * - Why not use index as key?
 * - How does React reconciliation work?
 * - .map() vs .forEach()
 * - Controlled vs uncontrolled components
 * - Props data flow (parent to child)
 * - Event handling in React
 * - Pure functions vs impure functions
 */
