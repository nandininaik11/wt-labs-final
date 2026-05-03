// Import React, useState for state, and useRef for DOM references
import React, { useState, useRef } from 'react';

/**
 * FEEDBACK FORM COMPONENT
 * =======================
 * This component demonstrates:
 * 1. Controlled Components (form inputs controlled by React state)
 * 2. Form Validation
 * 3. useRef Hook (accessing DOM elements directly)
 * 4. Event Handling
 * 5. Props (receiving callback from parent)
 */

function FeedbackForm({ onSubmit }) {
  /**
   * STATE FOR CONTROLLED COMPONENTS
   * ================================
   * Controlled components = form inputs whose values are controlled by React state.
   * 
   * Each input field has:
   * - A value prop tied to state
   * - An onChange handler that updates state
   * 
   * This gives React complete control over the input values.
   * 
   * VIVA POINT: Controlled vs Uncontrolled Components
   * --------------------------------------------------
   * CONTROLLED: React state is the "single source of truth"
   *   - Input value comes from state
   *   - Changes update state
   *   - More React-like, better for validation
   * 
   * UNCONTROLLED: DOM is the source of truth
   *   - Access value using refs
   *   - Less React control
   *   - Useful for simple forms or file inputs
   */
  
  // State for each form field
  const [studentName, setStudentName] = useState('');
  const [courseName, setCourseName] = useState('');
  const [rating, setRating] = useState('');
  const [comments, setComments] = useState('');
  
  /**
   * STATE FOR VALIDATION ERRORS
   * ============================
   * Stores error messages for each field.
   * Initially all empty (no errors).
   */
  const [errors, setErrors] = useState({
    studentName: '',
    courseName: '',
    rating: '',
    comments: ''
  });

  /**
   * useRef HOOK - DOM REFERENCES
   * =============================
   * useRef gives you a way to access DOM elements directly.
   * It creates a mutable object that persists across re-renders.
   * 
   * Syntax: const myRef = useRef(initialValue);
   * 
   * Use cases:
   * - Accessing DOM elements (like document.getElementById)
   * - Storing mutable values that don't trigger re-renders
   * - Managing focus, text selection, animations
   * 
   * VIVA POINT: useRef vs useState
   * -------------------------------
   * useState: Changing value triggers re-render
   * useRef: Changing value does NOT trigger re-render
   * 
   * useRef returns: { current: value }
   * Access the actual element via: studentNameRef.current
   */
  
  // Create refs for each input field
  const studentNameRef = useRef(null);
  const courseNameRef = useRef(null);
  const ratingRef = useRef(null);
  const commentsRef = useRef(null);

  /**
   * VALIDATION FUNCTION
   * ===================
   * Checks if form data is valid before submission.
   * Returns true if valid, false if there are errors.
   * 
   * VALIDATION RULES:
   * - Student name: required, min 3 characters, only letters and spaces
   * - Course name: required
   * - Rating: required, must be selected
   * - Comments: required, min 10 characters
   */
  const validateForm = () => {
    const newErrors = {};
    let isValid = true;

    // Validate student name
    if (!studentName.trim()) {
      newErrors.studentName = 'Student name is required';
      isValid = false;
    } else if (studentName.trim().length < 3) {
      newErrors.studentName = 'Name must be at least 3 characters';
      isValid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(studentName)) {
      // Regular expression: ^ = start, [a-zA-Z\s]+ = one or more letters or spaces, $ = end
      newErrors.studentName = 'Name should contain only letters';
      isValid = false;
    }

    // Validate course name
    if (!courseName.trim()) {
      newErrors.courseName = 'Course name is required';
      isValid = false;
    }

    // Validate rating
    if (!rating) {
      newErrors.rating = 'Please select a rating';
      isValid = false;
    }

    // Validate comments
    if (!comments.trim()) {
      newErrors.comments = 'Comments are required';
      isValid = false;
    } else if (comments.trim().length < 10) {
      newErrors.comments = 'Comments must be at least 10 characters';
      isValid = false;
    }

    // Update error state
    setErrors(newErrors);

    /**
     * USING useRef TO FOCUS ON FIRST ERROR
     * =====================================
     * If validation fails, we want to focus on the first field with an error.
     * This improves user experience.
     * 
     * We use the .current property to access the actual DOM element,
     * then call .focus() to move cursor to that input.
     */
    if (!isValid) {
      // Focus on first field with error
      if (newErrors.studentName) {
        studentNameRef.current.focus(); // Using ref to access DOM and call focus()
      } else if (newErrors.courseName) {
        courseNameRef.current.focus();
      } else if (newErrors.rating) {
        ratingRef.current.focus();
      } else if (newErrors.comments) {
        commentsRef.current.focus();
      }
    }

    return isValid;
  };

  /**
   * FORM SUBMISSION HANDLER
   * =======================
   * Called when user clicks "Submit Feedback" button.
   * 
   * @param {Event} e - The form submission event
   * 
   * IMPORTANT: e.preventDefault()
   * - By default, form submission reloads the page
   * - preventDefault() stops this default behavior
   * - Allows us to handle submission with JavaScript
   */
  const handleSubmit = (e) => {
    e.preventDefault(); // Stop default form submission (page reload)

    // Validate form before submitting
    if (validateForm()) {
      // Create feedback object with form data
      const feedbackData = {
        studentName: studentName.trim(),
        courseName: courseName.trim(),
        rating: rating,
        comments: comments.trim(),
      };

      /**
       * CALLING PARENT CALLBACK
       * =======================
       * onSubmit is a prop passed from parent (App component).
       * When we call it, we're actually calling handleFeedbackSubmit in App.js
       * This is how child components communicate with parents.
       */
      onSubmit(feedbackData);

      // Reset form after successful submission
      resetForm();
    }
  };

  /**
   * FORM RESET FUNCTION
   * ===================
   * Clears all form fields and errors after successful submission.
   * Also uses useRef to focus on the first input for better UX.
   */
  const resetForm = () => {
    // Clear all state values
    setStudentName('');
    setCourseName('');
    setRating('');
    setComments('');
    setErrors({
      studentName: '',
      courseName: '',
      rating: '',
      comments: ''
    });

    // Focus on first input using ref
    studentNameRef.current.focus();
  };

  /**
   * JSX RENDER - FORM STRUCTURE
   * ============================
   * Each input demonstrates CONTROLLED COMPONENT pattern:
   * 1. value={stateVariable} - Input value comes from state
   * 2. onChange={(e) => setStateVariable(e.target.value)} - Updates state on change
   * 3. ref={refVariable} - Attaches ref to DOM element
   */
  return (
    <div className="feedback-form-container">
      <h2 className="form-title">✍️ Submit Your Feedback</h2>
      
      {/**
       * FORM ELEMENT
       * ============
       * onSubmit handler prevents default and validates before submitting
       * noValidate attribute disables browser's built-in HTML5 validation
       * (We're using our own custom validation instead)
       */}
      <form onSubmit={handleSubmit} className="feedback-form" noValidate>
        
        {/* STUDENT NAME INPUT - Controlled Component Example */}
        <div className="form-group">
          <label htmlFor="studentName" className="form-label">
            Student Name <span className="required">*</span>
          </label>
          <input
            type="text"
            id="studentName"
            className={`form-input ${errors.studentName ? 'error' : ''}`}
            value={studentName}
            onChange={(e) => setStudentName(e.target.value)}
            ref={studentNameRef}
            placeholder="Enter your full name"
            aria-label="Student Name"
            aria-invalid={errors.studentName ? 'true' : 'false'}
          />
          {/* Display error message if exists */}
          {errors.studentName && (
            <span className="error-message">⚠️ {errors.studentName}</span>
          )}
        </div>

        {/* COURSE NAME INPUT - Controlled Component */}
        <div className="form-group">
          <label htmlFor="courseName" className="form-label">
            Course Name <span className="required">*</span>
          </label>
          <input
            type="text"
            id="courseName"
            className={`form-input ${errors.courseName ? 'error' : ''}`}
            value={courseName}
            onChange={(e) => setCourseName(e.target.value)}
            ref={courseNameRef}
            placeholder="e.g., Web Technology, Data Structures"
            aria-label="Course Name"
            aria-invalid={errors.courseName ? 'true' : 'false'}
          />
          {errors.courseName && (
            <span className="error-message">⚠️ {errors.courseName}</span>
          )}
        </div>

        {/* RATING SELECT - Controlled Component */}
        <div className="form-group">
          <label htmlFor="rating" className="form-label">
            Rating <span className="required">*</span>
          </label>
          {/**
           * SELECT DROPDOWN - Controlled Component
           * ======================================
           * value prop controls which option is selected
           * onChange updates state when user selects different option
           */}
          <select
            id="rating"
            className={`form-select ${errors.rating ? 'error' : ''}`}
            value={rating}
            onChange={(e) => setRating(e.target.value)}
            ref={ratingRef}
            aria-label="Rating"
            aria-invalid={errors.rating ? 'true' : 'false'}
          >
            <option value="">Select a rating</option>
            <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
            <option value="4">⭐⭐⭐⭐ Very Good</option>
            <option value="3">⭐⭐⭐ Good</option>
            <option value="2">⭐⭐ Fair</option>
            <option value="1">⭐ Poor</option>
          </select>
          {errors.rating && (
            <span className="error-message">⚠️ {errors.rating}</span>
          )}
        </div>

        {/* COMMENTS TEXTAREA - Controlled Component */}
        <div className="form-group">
          <label htmlFor="comments" className="form-label">
            Comments <span className="required">*</span>
          </label>
          {/**
           * TEXTAREA - Controlled Component
           * ===============================
           * Same pattern as input: value and onChange
           * Note: In HTML, textarea value goes between tags: <textarea>value</textarea>
           * In React, we use value prop just like input: <textarea value={state} />
           */}
          <textarea
            id="comments"
            className={`form-textarea ${errors.comments ? 'error' : ''}`}
            value={comments}
            onChange={(e) => setComments(e.target.value)}
            ref={commentsRef}
            placeholder="Share your detailed feedback here (minimum 10 characters)"
            rows="5"
            aria-label="Comments"
            aria-invalid={errors.comments ? 'true' : 'false'}
          />
          {/* Character counter - shows remaining characters */}
          <div className="char-counter">
            {comments.length} / 500 characters
          </div>
          {errors.comments && (
            <span className="error-message">⚠️ {errors.comments}</span>
          )}
        </div>

        {/* SUBMIT BUTTON */}
        <button type="submit" className="submit-button">
          📤 Submit Feedback
        </button>
      </form>
    </div>
  );
}

// Export component for use in other files
export default FeedbackForm;

/**
 * SUMMARY OF CONCEPTS DEMONSTRATED:
 * ==================================
 * ✅ Controlled Components (all inputs controlled by state)
 * ✅ useState Hook (managing form field values and errors)
 * ✅ useRef Hook (accessing DOM elements for focus management)
 * ✅ Form Validation (custom validation logic)
 * ✅ Event Handling (onChange, onSubmit)
 * ✅ Conditional Rendering (showing error messages)
 * ✅ Props (receiving onSubmit callback from parent)
 * ✅ Regular Expressions (validating name format)
 * ✅ Accessibility (aria-labels, aria-invalid)
 */
