/**
 * RESULT CALCULATOR COMPONENT
 * Displays detailed result for selected student
 * Shows subject-wise marks, percentage, grade, and analysis
 */

import React from 'react';

/**
 * PROPS:
 * - student: Object - Selected student data
 * - onBack: Function - Callback to return to student list
 */
function ResultCalculator({ student, onBack }) {
  
  /**
   * CALCULATE TOTAL MARKS
   */
  const totalMarks = student.subjects.reduce((sum, subject) => {
    return sum + subject.marks;
  }, 0);

  /**
   * CALCULATE MAX POSSIBLE MARKS
   */
  const maxMarks = student.subjects.reduce((sum, subject) => {
    return sum + subject.maxMarks;
  }, 0);

  /**
   * CALCULATE PERCENTAGE
   */
  const percentage = ((totalMarks / maxMarks) * 100).toFixed(2);

  /**
   * DETERMINE GRADE
   */
  const getGrade = () => {
    if (percentage >= 90) return { grade: 'A+', color: '#2ecc71' };
    if (percentage >= 80) return { grade: 'A', color: '#27ae60' };
    if (percentage >= 70) return { grade: 'B+', color: '#f39c12' };
    if (percentage >= 60) return { grade: 'B', color: '#e67e22' };
    if (percentage >= 50) return { grade: 'C', color: '#e74c3c' };
    if (percentage >= 40) return { grade: 'D', color: '#c0392b' };
    return { grade: 'F', color: '#95a5a6' };
  };

  const gradeInfo = getGrade();

  /**
   * DETERMINE RESULT STATUS
   */
  const result = percentage >= 40 ? 'PASS' : 'FAIL';

  /**
   * FIND HIGHEST AND LOWEST SCORING SUBJECTS
   */
  const highestSubject = student.subjects.reduce((max, subject) => {
    return subject.marks > max.marks ? subject : max;
  });

  const lowestSubject = student.subjects.reduce((min, subject) => {
    return subject.marks < min.marks ? subject : min;
  });

  /**
   * COUNT SUBJECTS WITH DISTINCTION (>=75)
   */
  const distinctionCount = student.subjects.filter(subject => {
    return subject.marks >= 75;
    // filter() creates new array with items that pass test
  }).length;

  return (
    <div className="result-container">
      {/* Back Button */}
      <button className="back-button" onClick={onBack}>
        ← Back to Student List
      </button>

      {/* Student Header */}
      <div className="result-header">
        <div className="student-details">
          <h2>{student.name}</h2>
          <p>Roll No: {student.rollNo} | Semester: {student.semester}</p>
        </div>
        <div 
          className="result-badge-large"
          style={{ backgroundColor: result === 'PASS' ? '#2ecc71' : '#e74c3c' }}
        >
          {/* Inline style in React: style={{ key: value }} */}
          {/* Note: Double curly braces - outer for JSX, inner for object */}
          {result}
        </div>
      </div>

      {/* Summary Cards */}
      <div className="summary-cards">
        <div className="summary-card">
          <h3>Total Marks</h3>
          <p className="summary-value">{totalMarks} / {maxMarks}</p>
        </div>

        <div className="summary-card">
          <h3>Percentage</h3>
          <p className="summary-value">{percentage}%</p>
        </div>

        <div className="summary-card">
          <h3>Grade</h3>
          <p 
            className="summary-value" 
            style={{ color: gradeInfo.color, fontSize: '48px' }}
          >
            {gradeInfo.grade}
          </p>
        </div>

        <div className="summary-card">
          <h3>Distinctions</h3>
          <p className="summary-value">{distinctionCount}</p>
          <p style={{ fontSize: '12px', color: '#7f8c8d' }}>Subjects ≥ 75%</p>
        </div>
      </div>

      {/* Subject-wise Marks Table */}
      <div className="marks-table-container">
        <h3>Subject-wise Performance</h3>
        <table className="marks-table">
          <thead>
            <tr>
              <th>Subject</th>
              <th>Marks Obtained</th>
              <th>Max Marks</th>
              <th>Percentage</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            {/* Map through subjects to create table rows */}
            {student.subjects.map((subject, index) => {
              const subjectPercentage = ((subject.marks / subject.maxMarks) * 100).toFixed(2);
              const status = subject.marks >= 40 ? 'Pass' : 'Fail';
              
              return (
                <tr key={index}>
                  {/* Using index as key here since subjects don't have unique IDs */}
                  {/* In real app, subjects should have unique IDs */}
                  
                  <td>{subject.name}</td>
                  <td>{subject.marks}</td>
                  <td>{subject.maxMarks}</td>
                  <td>{subjectPercentage}%</td>
                  <td>
                    <span className={`status-badge ${status.toLowerCase()}`}>
                      {status}
                    </span>
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>

      {/* Performance Analysis */}
      <div className="analysis-section">
        <h3>Performance Analysis</h3>
        <div className="analysis-cards">
          <div className="analysis-card strength">
            <h4>💪 Strongest Subject</h4>
            <p className="subject-name">{highestSubject.name}</p>
            <p className="subject-marks">{highestSubject.marks}/{highestSubject.maxMarks}</p>
          </div>

          <div className="analysis-card improvement">
            <h4>📈 Needs Improvement</h4>
            <p className="subject-name">{lowestSubject.name}</p>
            <p className="subject-marks">{lowestSubject.marks}/{lowestSubject.maxMarks}</p>
          </div>
        </div>
      </div>

      {/* Remarks */}
      <div className="remarks-section">
        <h4>Remarks:</h4>
        <p>
          {percentage >= 75 
            ? "Excellent performance! Keep up the great work." 
            : percentage >= 60 
            ? "Good performance. Focus on improving weaker subjects." 
            : percentage >= 40 
            ? "Satisfactory performance. More effort needed in some subjects." 
            : "Performance below expectations. Immediate attention required."}
        </p>
      </div>
    </div>
  );
}

export default ResultCalculator;
