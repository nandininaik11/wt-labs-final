/**
 * STUDENT LIST COMPONENT
 * Displays list of all students
 * Demonstrates: Array.map(), Event handling, Props
 */

import React from 'react';

/**
 * PROPS:
 * - students: Array - List of student objects
 * - onStudentSelect: Function - Callback when student clicked
 */
function StudentList({ students, onStudentSelect }) {
  
  /**
   * FUNCTION: Calculate total marks for a student
   * Uses reduce() to sum all subject marks
   */
  const calculateTotal = (subjects) => {
    return subjects.reduce((total, subject) => {
      return total + subject.marks;
      // reduce accumulates values
      // total starts at 0 (second parameter below)
      // For each subject, adds marks to total
    }, 0);
  };

  /**
   * FUNCTION: Calculate percentage
   */
  const calculatePercentage = (subjects) => {
    const totalMarks = calculateTotal(subjects);
    const maxMarks = subjects.length * 100; // Each subject out of 100
    return ((totalMarks / maxMarks) * 100).toFixed(2);
    // toFixed(2) rounds to 2 decimal places
  };

  /**
   * FUNCTION: Determine grade based on percentage
   */
  const getGrade = (percentage) => {
    if (percentage >= 90) return 'A+';
    if (percentage >= 80) return 'A';
    if (percentage >= 70) return 'B+';
    if (percentage >= 60) return 'B';
    if (percentage >= 50) return 'C';
    if (percentage >= 40) return 'D';
    return 'F';
  };

  /**
   * FUNCTION: Determine result status
   */
  const getResult = (percentage) => {
    return percentage >= 40 ? 'PASS' : 'FAIL';
    // Ternary operator: condition ? ifTrue : ifFalse
  };

  return (
    <div className="student-list-container">
      <h2>Student List</h2>
      <p className="instruction">Click on any student to view detailed results</p>
      
      <div className="student-cards">
        {/* Array.map() - loops through each student and returns JSX */}
        {students.map((student) => {
          // For each student, create a card
          const percentage = calculatePercentage(student.subjects);
          const grade = getGrade(percentage);
          const result = getResult(percentage);
          
          return (
            <div 
              key={student.id}
              // key prop: Unique identifier for list items
              // Helps React efficiently update the DOM
              // MUST be unique and stable (don't use array index)
              
              className={`student-card ${result === 'PASS' ? 'pass' : 'fail'}`}
              // Template literal for dynamic className
              // Adds 'pass' or 'fail' class based on result
              
              onClick={() => onStudentSelect(student)}
              // Arrow function to pass student to handler
              // onClick={() => onStudentSelect(student)}
              // NOT: onClick={onStudentSelect(student)} 
              // (would call immediately!)
            >
              <div className="student-info">
                <h3>{student.name}</h3>
                <p className="roll-no">Roll No: {student.rollNo}</p>
              </div>
              
              <div className="student-stats">
                <div className="stat">
                  <span className="stat-label">Total:</span>
                  <span className="stat-value">
                    {calculateTotal(student.subjects)}/{student.subjects.length * 100}
                  </span>
                </div>
                
                <div className="stat">
                  <span className="stat-label">Percentage:</span>
                  <span className="stat-value">{percentage}%</span>
                </div>
                
                <div className="stat">
                  <span className="stat-label">Grade:</span>
                  <span className={`grade grade-${grade.replace('+', 'plus')}`}>
                    {grade}
                  </span>
                </div>
                
                <div className="stat">
                  <span className="stat-label">Result:</span>
                  <span className={`result-badge ${result.toLowerCase()}`}>
                    {result}
                  </span>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}

export default StudentList;
