/**
 * LAB 7 - SEMESTER RESULT SYSTEM
 * React Application Demonstrating:
 * - Components (Functional & Class)
 * - State Management (useState hook)
 * - Props passing
 * - Event handling
 * - Conditional rendering
 * - Array methods (map, filter)
 * - CSS styling in React
 */

import React, { useState } from 'react';
import './App.css';
import StudentList from './components/StudentList';
import ResultCalculator from './components/ResultCalculator';
import Header from './components/Header';

/**
 * MAIN APP COMPONENT
 * This is the root component that manages overall application state
 */
function App() {
  // STATE: Sample student data
  // useState hook for managing state in functional components
  // Array of student objects with their marks
  const [students] = useState([
    {
      id: 1,
      name: 'John Doe',
      rollNo: 'CS101',
      semester: 5,
      subjects: [
        { name: 'Web Technology', marks: 85, maxMarks: 100 },
        { name: 'Database Management', marks: 78, maxMarks: 100 },
        { name: 'Software Engineering', marks: 92, maxMarks: 100 },
        { name: 'Computer Networks', marks: 88, maxMarks: 100 },
        { name: 'Operating Systems', marks: 75, maxMarks: 100 }
      ]
    },
    {
      id: 2,
      name: 'Jane Smith',
      rollNo: 'CS102',
      semester: 5,
      subjects: [
        { name: 'Web Technology', marks: 92, maxMarks: 100 },
        { name: 'Database Management', marks: 88, maxMarks: 100 },
        { name: 'Software Engineering', marks: 95, maxMarks: 100 },
        { name: 'Computer Networks', marks: 90, maxMarks: 100 },
        { name: 'Operating Systems', marks: 85, maxMarks: 100 }
      ]
    },
    {
      id: 3,
      name: 'Bob Johnson',
      rollNo: 'CS103',
      semester: 5,
      subjects: [
        { name: 'Web Technology', marks: 72, maxMarks: 100 },
        { name: 'Database Management', marks: 65, maxMarks: 100 },
        { name: 'Software Engineering', marks: 78, maxMarks: 100 },
        { name: 'Computer Networks', marks: 70, maxMarks: 100 },
        { name: 'Operating Systems', marks: 68, maxMarks: 100 }
      ]
    },
    {
      id: 4,
      name: 'Alice Williams',
      rollNo: 'CS104',
      semester: 5,
      subjects: [
        { name: 'Web Technology', marks: 88, maxMarks: 100 },
        { name: 'Database Management', marks: 91, maxMarks: 100 },
        { name: 'Software Engineering', marks: 85, maxMarks: 100 },
        { name: 'Computer Networks', marks: 89, maxMarks: 100 },
        { name: 'Operating Systems', marks: 92, maxMarks: 100 }
      ]
    }
  ]);

  // STATE: Selected student for detailed view
  // null initially, will store student object when selected
  const [selectedStudent, setSelectedStudent] = useState(null);
  // useState returns: [currentValue, functionToUpdateValue]
  // selectedStudent = current value
  // setSelectedStudent = function to update it

  /**
   * FUNCTION: Handle student selection
   * Called when user clicks on a student
   * Updates selectedStudent state
   */
  const handleStudentSelect = (student) => {
    setSelectedStudent(student);
    // When state changes, React re-renders the component
  };

  /**
   * FUNCTION: Clear selection (go back to list)
   */
  const handleBackToList = () => {
    setSelectedStudent(null);
  };

  /**
   * RENDER: Main app layout
   * Conditional rendering based on selectedStudent state
   */
  return (
    <div className="App">
      {/* Header component - always visible */}
      <Header title="Semester Result System" semester={5} />
      
      <div className="container">
        {/* CONDITIONAL RENDERING */}
        {/* If selectedStudent is null, show list */}
        {/* If selectedStudent exists, show calculator */}
        {!selectedStudent ? (
          // Show student list when no student selected
          // Passing students data and handler function as props
          <StudentList 
            students={students} 
            onStudentSelect={handleStudentSelect} 
          />
        ) : (
          // Show result calculator when student selected
          // Passing selected student and back handler as props
          <ResultCalculator 
            student={selectedStudent} 
            onBack={handleBackToList} 
          />
        )}
      </div>

      {/* Footer with lab information */}
      <footer className="footer">
        <p>Lab 7 - Web Technology | React Application</p>
        <p>Demonstrating Components, State, Props & Event Handling</p>
      </footer>
    </div>
  );
}

export default App;
// export default makes this component available for import in other files
