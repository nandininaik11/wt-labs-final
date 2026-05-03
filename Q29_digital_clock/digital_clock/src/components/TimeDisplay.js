// ============================================================
// src/components/TimeDisplay.js — Time Display Component
// WT Syllabus Unit V: React — Props, Component API, JSX
//
// This component RECEIVES data via props and displays it.
// It has NO state of its own — purely presentational.
// This is called a "Dumb/Presentational Component".
// ============================================================

import React from 'react';
import './TimeDisplay.css';

// ── TimeDisplay Component ──
// props = object containing all attributes passed by parent
// Destructuring in parameter: { hh, mm, ss, period, isRunning, theme }
// is equivalent to: props.hh, props.mm, etc.
function TimeDisplay({ hh, mm, ss, period, isRunning, theme }) {

  // ── Digit Component (component inside component) ──
  // Small helper component renders one pair of digits with a label
  // This is a NESTED functional component — defined inside another
  function Digit({ value, label }) {
    return (
      <div className="digit-group">
        {/* Each digit animates when the value changes */}
        <div className={`digit-block ${!isRunning ? 'digit-paused' : ''}`}>
          {/* Split "09" into ['0','9'] and render each digit separately */}
          {/* This lets us animate each digit individually */}
          {String(value).split('').map((char, i) => (
            <span key={i} className="digit-char">{char}</span>
          ))}
        </div>
        {/* Label below digits */}
        <div className="digit-label">{label}</div>
      </div>
    );
  }

  return (
    <div className="time-display">

      {/* Hours */}
      <Digit value={hh} label="HOURS" />

      {/* Colon separator — blinks when running */}
      <div className={`colon ${isRunning ? 'colon-blink' : 'colon-dim'}`}>
        <span>:</span>
      </div>

      {/* Minutes */}
      <Digit value={mm} label="MINUTES" />

      {/* Seconds — conditionally rendered */}
      {/* Short-circuit: ss && <element> → renders only if ss is truthy */}
      {ss !== null && (
        <>
          {/* React Fragment <>...</> groups elements without adding DOM node */}
          <div className={`colon ${isRunning ? 'colon-blink' : 'colon-dim'}`}
               style={{ animationDelay: '0.5s' }}>
            {/* Inline style in JSX: style={{ property: 'value' }} — double braces! */}
            {/* Outer {} = JSX expression, Inner {} = JS object */}
            <span>:</span>
          </div>
          <Digit value={ss} label="SECONDS" />
        </>
      )}

      {/* AM/PM indicator — only shows in 12-hour mode */}
      {period && (
        <div className="period-badge">
          {/* period is 'AM' or 'PM' — empty string '' is falsy in JS */}
          {period}
        </div>
      )}

    </div>
  );
}

// PropTypes could be added here for type checking (bonus knowledge):
// import PropTypes from 'prop-types';
// TimeDisplay.propTypes = { hh: PropTypes.string.isRequired, ... };

export default TimeDisplay;
