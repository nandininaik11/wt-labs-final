// ============================================================
// src/components/Controls.js — Clock Controls Component
// WT Syllabus Unit V: React — Events, Props, Forms, State lifting
//
// "State lifting" pattern: state lives in Clock (parent),
// Controls (child) receives setter functions as props and calls them.
// Data flows DOWN (props), Events flow UP (callbacks).
// ============================================================

import React from 'react';
import './Controls.css';

// Destructure all props — these are functions (callbacks) from parent
function Controls({
  isRunning,
  is24Hour,
  showSeconds,
  theme,
  onToggle,         // parent function: toggles running state
  onReset,          // parent function: resets clock
  onFormatToggle,   // parent function: toggles 12/24 hour
  onSecondsToggle,  // parent function: toggles seconds visibility
  onThemeChange     // parent function: changes color theme
}) {

  // Theme color options
  const themes = [
    { id: 'blue',   label: '💙', color: '#00d4ff' },
    { id: 'green',  label: '💚', color: '#10b981' },
    { id: 'purple', label: '💜', color: '#7c3aed' },
    { id: 'red',    label: '❤️',  color: '#ef4444' },
  ];

  return (
    <div className="controls">

      {/* ── Primary Controls: Start/Stop + Reset ── */}
      <div className="controls-row">

        {/* Task 5: Start/Stop button */}
        {/* onClick: event handler — calls parent's onToggle function */}
        {/* Dynamic label: isRunning ? 'Stop' : 'Start' */}
        <button
          className={`btn btn-main ${isRunning ? 'btn-stop' : 'btn-start'}`}
          onClick={onToggle}
          // onClick={onToggle} NOT onClick={onToggle()} !!
          // onToggle = reference to function (called when clicked)
          // onToggle() = calls immediately during render (WRONG!)
        >
          {/* Conditional rendering with ternary operator */}
          {isRunning ? (
            <><span className="btn-icon">⏸</span> Pause</>
          ) : (
            <><span className="btn-icon">▶</span> Resume</>
          )}
        </button>

        {/* Reset button */}
        <button
          className="btn btn-reset"
          onClick={onReset}
        >
          <span className="btn-icon">↺</span> Reset
        </button>
      </div>

      {/* ── Secondary Controls: Format toggles ── */}
      <div className="controls-row controls-row-sm">

        {/* 12/24 Hour toggle button */}
        <button
          className={`btn btn-toggle ${is24Hour ? 'btn-toggle-active' : ''}`}
          onClick={onFormatToggle}
          title="Toggle 12/24 hour format"
        >
          {/* Conditional text based on current mode */}
          {is24Hour ? '12H' : '24H'}
        </button>

        {/* Show/Hide seconds toggle */}
        <button
          className={`btn btn-toggle ${showSeconds ? 'btn-toggle-active' : ''}`}
          onClick={onSecondsToggle}
          title="Toggle seconds display"
        >
          {showSeconds ? '⏱ :SS' : '⏱ Hide'}
        </button>
      </div>

      {/* ── Theme Picker ── */}
      <div className="theme-picker">
        <div className="theme-label">THEME</div>
        <div className="theme-dots">
          {/* Array.map: transform themes array into JSX elements */}
          {themes.map(t => (
            <button
              key={t.id}         // key prop: required for list items
              className={`theme-dot ${theme === t.id ? 'theme-dot-active' : ''}`}
              style={{ background: t.color }}
              onClick={() => onThemeChange(t.id)}
              // Arrow function in onClick: () => onThemeChange(t.id)
              // We use arrow function because we need to pass t.id argument
              // Without arrow function: onClick={onThemeChange} would work
              // but wouldn't pass the theme id
              title={t.id}
            >
              {/* Show emoji for active theme */}
              {theme === t.id ? '✓' : ''}
            </button>
          ))}
        </div>
      </div>

    </div>
  );
}

export default Controls;
