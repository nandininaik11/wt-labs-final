// ============================================================
// src/components/InfoPanel.js — Info Panel Component
// WT Syllabus Unit V: React — Props, Pure/Presentational Component
// ============================================================

import React from 'react';
import './InfoPanel.css';

// Pure presentational component — no state, just displays props
function InfoPanel({ dateString, timezone, elapsedSeconds }) {

  // Format elapsed into human-readable
  const hours   = Math.floor(elapsedSeconds / 3600);
  const minutes = Math.floor((elapsedSeconds % 3600) / 60);
  const seconds = elapsedSeconds % 60;

  // Build elapsed string dynamically
  // Array filter: only include non-zero parts
  const parts = [];
  if (hours   > 0) parts.push(`${hours}h`);
  if (minutes > 0) parts.push(`${minutes}m`);
  parts.push(`${seconds}s`); // always show seconds

  const elapsedStr = parts.join(' ');

  return (
    <div className="info-panel">
      {/* Date */}
      <div className="info-row">
        <span className="info-icon">📅</span>
        <span className="info-text">{dateString}</span>
      </div>

      {/* Timezone */}
      <div className="info-row">
        <span className="info-icon">🌍</span>
        <span className="info-text">{timezone}</span>
      </div>

      {/* Elapsed since page load */}
      <div className="info-row">
        <span className="info-icon">⏱</span>
        <span className="info-text">Running for: {elapsedStr}</span>
      </div>

      {/* Footer note */}
      <div className="info-footer">
        Built with React Hooks: useState + useEffect
      </div>
    </div>
  );
}

export default InfoPanel;
