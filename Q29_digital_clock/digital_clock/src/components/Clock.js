// ============================================================
// src/components/Clock.js — Main Clock Component
// WT Syllabus Unit V: React — useState, useEffect, Hooks,
//                    Functional Components, Component lifecycle,
//                    State, Props, Events, Component styling
//
// This is the CORE file for the lab question:
//   Task 1: Functional React component ✓
//   Task 2: useState() to store time  ✓
//   Task 3: useEffect() + setInterval ✓
//   Task 4: HH:MM:SS format           ✓
//   Task 5: Start/Stop option         ✓
// ============================================================

// Named imports from 'react' package
// useState   = Hook to add state to functional components
// useEffect  = Hook to run side effects (timers, API calls, etc.)
// useCallback= Hook to memoize functions (performance optimization)
import React, { useState, useEffect, useCallback } from 'react';

// Import child components
import TimeDisplay from './TimeDisplay';
import Controls    from './Controls';
import InfoPanel   from './InfoPanel';

// Import CSS for this component (CSS Modules alternative: Clock.module.css)
import './Clock.css';

// ============================================================
// Clock Component — Functional Component
// ============================================================
// A functional component is a plain JavaScript function that:
//   - Receives props as parameter (here we have none)
//   - Returns JSX (React elements)
//   - Can use Hooks (useState, useEffect, etc.)
// ============================================================
function Clock() {

  // ══════════════════════════════════════════════════════════
  // STATE with useState()
  // ══════════════════════════════════════════════════════════
  //
  // useState(initialValue) returns an ARRAY with 2 elements:
  //   [currentValue, setterFunction]
  // We use array destructuring to name them.
  //
  // When setterFunction is called, React:
  //   1. Updates the state value
  //   2. Re-renders the component (calls this function again)
  //   3. The UI reflects the new state
  // ══════════════════════════════════════════════════════════

  // Task 2: useState to store the current time
  // new Date() creates a Date object with the current date+time
  const [time, setTime] = useState(new Date());
  //     ^^^^  ^^^^^^^
  //     state setter function
  //           initial value = current time when component first renders

  // Task 5: State for running/stopped
  // true = clock is ticking, false = paused
  const [isRunning, setIsRunning] = useState(true);
  //     ^^^^^^^^^  ^^^^^^^^^^
  //     boolean state    setter

  // State for 12hr/24hr format toggle
  const [is24Hour, setIs24Hour] = useState(true);

  // State for showing/hiding seconds
  const [showSeconds, setShowSeconds] = useState(true);

  // State to track total elapsed seconds (for the lap timer display)
  const [elapsedSeconds, setElapsedSeconds] = useState(0);

  // State for theme color
  const [theme, setTheme] = useState('blue');
  // theme options: 'blue' | 'green' | 'purple' | 'red'

  // ══════════════════════════════════════════════════════════
  // SIDE EFFECTS with useEffect()
  // ══════════════════════════════════════════════════════════
  //
  // useEffect(effectFn, dependencyArray)
  //
  // effectFn runs AFTER every render by default.
  // dependencyArray controls WHEN it re-runs:
  //   []            = run once after first render only (mount)
  //   [a, b]        = run when a or b changes
  //   (no array)    = run after EVERY render
  //
  // effectFn can return a CLEANUP function:
  //   The cleanup runs BEFORE the next effect, or when component unmounts.
  //   This prevents memory leaks (e.g., clearing timers)
  // ══════════════════════════════════════════════════════════

  // Task 3: useEffect to update time every second
  useEffect(() => {
    // Only run the interval if the clock is running
    if (!isRunning) return; // early return — no interval when stopped

    // setInterval(fn, ms): calls fn every `ms` milliseconds
    // Returns an interval ID we use to clear it later
    const intervalId = setInterval(() => {
      // Arrow function runs every 1000ms (1 second)

      // setTime: updates the time state → triggers re-render
      setTime(new Date()); // new Date() = current date and time right now

      // Update elapsed seconds counter
      // Functional update form: setElapsedSeconds(prev => prev + 1)
      // Use this when new state depends on old state (prevents stale closure)
      setElapsedSeconds(prev => prev + 1);

    }, 1000); // 1000 milliseconds = 1 second

    // ── CLEANUP FUNCTION ──
    // React calls this before the next effect runs (when isRunning changes)
    // and when the component unmounts (removed from DOM)
    // Without this, old intervals would keep running → memory leak!
    return () => {
      clearInterval(intervalId); // stop the interval using saved ID
    };

  }, [isRunning]);
  //  ^^^^^^^^^^ dependency array
  // Effect re-runs whenever `isRunning` changes:
  //   - When isRunning becomes true  → create new interval
  //   - When isRunning becomes false → cleanup (clearInterval) called, no new interval


  // ══════════════════════════════════════════════════════════
  // useCallback — Memoized functions
  // ══════════════════════════════════════════════════════════
  // useCallback(fn, deps) returns a memoized (cached) version of fn.
  // It only recreates the function when deps change.
  // Good practice when passing functions as props to child components.
  // ══════════════════════════════════════════════════════════

  // Task 5: Toggle start/stop
  const handleToggle = useCallback(() => {
    setIsRunning(prev => !prev); // !prev flips true↔false
  }, []); // empty deps: function never needs recreation

  // Reset clock and elapsed time
  const handleReset = useCallback(() => {
    setTime(new Date());
    setElapsedSeconds(0);
    setIsRunning(true); // always start running after reset
  }, []);

  // Toggle 12/24 hour format
  const toggleFormat = useCallback(() => {
    setIs24Hour(prev => !prev);
  }, []);

  // Toggle seconds visibility
  const toggleSeconds = useCallback(() => {
    setShowSeconds(prev => !prev);
  }, []);

  // ══════════════════════════════════════════════════════════
  // HELPER FUNCTIONS — Format time for display
  // ══════════════════════════════════════════════════════════

  // Task 4: Format time as HH:MM:SS
  // padStart(2, '0') adds leading zero if needed: 9 → "09"
  const formatTime = () => {
    let hours   = time.getHours();   // 0-23
    const mins  = time.getMinutes(); // 0-59
    const secs  = time.getSeconds(); // 0-59

    let period = ''; // AM/PM

    if (!is24Hour) {
      // 12-hour format conversion
      period = hours >= 12 ? 'PM' : 'AM';
      hours  = hours % 12 || 12; // 0 becomes 12, 13 becomes 1, etc.
    }

    // String.padStart(length, char): pad with '0' to ensure 2 digits
    // '9'.padStart(2,'0') → '09'
    const hh = String(hours).padStart(2, '0');
    const mm  = String(mins).padStart(2, '0');
    const ss  = String(secs).padStart(2, '0');

    // Template literal (backtick string) with ${expression}
    return { hh, mm, ss, period };
  };

  // Format date as "Monday, 01 January 2025"
  const formatDate = () => {
    return time.toLocaleDateString('en-IN', {
      weekday: 'long',   // "Monday"
      day:     '2-digit',// "01"
      month:   'long',   // "January"
      year:    'numeric' // "2025"
    });
  };

  // Format elapsed time as MM:SS
  const formatElapsed = () => {
    const m = Math.floor(elapsedSeconds / 60);
    const s = elapsedSeconds % 60;
    return `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
  };

  const { hh, mm, ss, period } = formatTime(); // destructure returned object

  // ══════════════════════════════════════════════════════════
  // JSX RETURN — What the component renders
  // ══════════════════════════════════════════════════════════
  // JSX Rules:
  //   1. Return ONE root element (wrap in <div> or <>...</>)
  //   2. Use className instead of class (class is JS reserved word)
  //   3. JavaScript expressions go in { }
  //   4. Self-closing tags: <br /> not <br>
  //   5. camelCase event handlers: onClick, onChange, onSubmit
  // ══════════════════════════════════════════════════════════
  return (
    <div className={`clock-container theme-${theme}`}>
      {/* Dynamic className using template literal — theme changes color */}

      {/* ── Header ── */}
      <div className="clock-header">
        <div className="clock-label">⏰ DIGITAL CLOCK</div>
        <div className="clock-subtitle">React Hooks — useState & useEffect</div>
      </div>

      {/* ── Running indicator ── */}
      <div className={`status-indicator ${isRunning ? 'status-running' : 'status-stopped'}`}>
        {/* Conditional rendering: ternary operator condition ? A : B */}
        <span className="status-dot" />
        {isRunning ? '● LIVE' : '■ PAUSED'}
      </div>

      {/* ── Time Display Component ── */}
      {/* Passing data to child via PROPS */}
      <TimeDisplay
        hh={hh}
        mm={mm}
        ss={showSeconds ? ss : null}
        period={period}
        isRunning={isRunning}
        theme={theme}
      />

      {/* ── Elapsed Timer ── */}
      <div className="elapsed-section">
        <div className="elapsed-label">ELAPSED TIME</div>
        <div className="elapsed-time">{formatElapsed()}</div>
      </div>

      {/* ── Controls Component ── */}
      {/* Passing callback functions as props (child calls parent functions) */}
      <Controls
        isRunning={isRunning}
        is24Hour={is24Hour}
        showSeconds={showSeconds}
        theme={theme}
        onToggle={handleToggle}
        onReset={handleReset}
        onFormatToggle={toggleFormat}
        onSecondsToggle={toggleSeconds}
        onThemeChange={setTheme}
      />

      {/* ── Info Panel ── */}
      <InfoPanel
        dateString={formatDate()}
        timezone={Intl.DateTimeFormat().resolvedOptions().timeZone}
        elapsedSeconds={elapsedSeconds}
      />
    </div>
  );
}

// Named export as default — allows: import Clock from './Clock'
export default Clock;
