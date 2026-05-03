/**
 * HEADER COMPONENT
 * Displays application title and semester information
 * This is a FUNCTIONAL COMPONENT (simpler, modern approach)
 */

import React from 'react';

/**
 * PROPS:
 * - title: String - Application title
 * - semester: Number - Current semester number
 * 
 * Props are passed from parent (App) to child (Header)
 * Props are READ-ONLY (cannot be modified by child)
 */
function Header({ title, semester }) {
  // Destructuring props: { title, semester } = props
  // Instead of: props.title, props.semester
  
  return (
    <header className="header">
      <div className="header-content">
        <h1>{title}</h1>
        {/* JSX: Curly braces {} embed JavaScript expressions */}
        <p className="semester-badge">Semester {semester}</p>
      </div>
    </header>
  );
}

export default Header;
