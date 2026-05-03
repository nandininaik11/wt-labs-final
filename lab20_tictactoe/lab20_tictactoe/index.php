<?php
/* ============================================================
   Lab Q20: Tic-Tac-Toe Game in PHP
   WT Syllabus: Unit III (PHP Sessions, Form Handling, Arrays)
                Unit I  (HTML5, CSS, Bootstrap)
                Unit II (JavaScript, DOM Manipulation)

   HOW IT WORKS:
   - PHP Sessions store the game board (3x3 array) and scores
   - HTML form sends the clicked cell to the server
   - PHP checks for a winner after every move
   - JavaScript adds animations and sound effects
   ============================================================ */

// ── STEP 1: Start the session ─────────────────────────────────
// session_start() MUST be the very first line before any HTML output
// Sessions let us remember the board state between page loads
session_start();

// ── STEP 2: Initialize game state ─────────────────────────────
// If this is the very first visit OR if "New Game" was clicked,
// set up a fresh board stored in $_SESSION
if (!isset($_SESSION['board']) || isset($_GET['reset'])) {

    // The board is a PHP indexed array with 9 elements (positions 0–8)
    // Empty string '' means the cell is empty
    // 'X' or 'O' means that player has claimed that cell
    $_SESSION['board']          = ['', '', '', '', '', '', '', '', ''];

    // Track whose turn it is ('X' always goes first)
    $_SESSION['current_player'] = 'X';

    // Track game result: null = ongoing, 'X'/'O' = winner, 'draw' = draw
    $_SESSION['winner']         = null;

    // Score tracking — these persist across games
    if (!isset($_SESSION['score_x'])) $_SESSION['score_x'] = 0;
    if (!isset($_SESSION['score_o'])) $_SESSION['score_o'] = 0;
    if (!isset($_SESSION['draws']))   $_SESSION['draws']   = 0;

    // Store which cells form the winning line (for highlighting)
    $_SESSION['winning_cells']  = [];
}

// ── STEP 3: Handle a player's move ───────────────────────────
// When a player clicks a cell, the form sends ?move=N (0-8) via GET
// We only process a move if:
//   (a) 'move' is set in the URL
//   (b) there's no winner yet (game is still going)
//   (c) the chosen cell is still empty
if (
    isset($_GET['move'])                    // Player clicked a cell
    && $_SESSION['winner'] === null          // Game not over yet
    && $_SESSION['board'][(int)$_GET['move']] === ''  // Cell is empty
) {
    $cell   = (int)$_GET['move'];           // Convert string to integer safely
    $player = $_SESSION['current_player'];  // Who is playing right now?

    // Mark the board: put 'X' or 'O' into the clicked cell
    $_SESSION['board'][$cell] = $player;

    // ── STEP 4: Check if this move wins the game ───────────────
    // All 8 possible winning combinations (rows, columns, diagonals)
    $wins = [
        [0, 1, 2],   // Top row    →→→
        [3, 4, 5],   // Middle row →→→
        [6, 7, 8],   // Bottom row →→→
        [0, 3, 6],   // Left column ↓↓↓
        [1, 4, 7],   // Middle column ↓↓↓
        [2, 5, 8],   // Right column ↓↓↓
        [0, 4, 8],   // Diagonal \
        [2, 4, 6],   // Diagonal /
    ];

    $board = $_SESSION['board'];   // Local variable for convenience

    // Loop through every winning combination
    foreach ($wins as $combo) {
        // Check if all 3 cells in this combo are claimed by the SAME player
        // $combo[0], $combo[1], $combo[2] are the 3 cell indices
        if (
            $board[$combo[0]] !== ''                    // Not empty
            && $board[$combo[0]] === $board[$combo[1]]  // All same
            && $board[$combo[1]] === $board[$combo[2]]
        ) {
            // This player has won!
            $_SESSION['winner']        = $player;
            $_SESSION['winning_cells'] = $combo;       // Save for green highlight

            // Update the score counter in the session
            if ($player === 'X') {
                $_SESSION['score_x']++;
            } else {
                $_SESSION['score_o']++;
            }
            break; // Stop checking — we found a winner
        }
    }

    // ── STEP 5: Check for a draw ───────────────────────────────
    // A draw = no winner AND all 9 cells are filled
    // array_filter removes empty strings, count checks if any remain
    if ($_SESSION['winner'] === null && count(array_filter($board)) === 9) {
        $_SESSION['winner'] = 'draw';  // Mark as draw
        $_SESSION['draws']++;
    }

    // ── STEP 6: Switch turn to the other player ────────────────
    // Only switch if the game is still going (no winner/draw yet)
    if ($_SESSION['winner'] === null) {
        $_SESSION['current_player'] = ($player === 'X') ? 'O' : 'X';
        // Ternary operator: if X was playing, now O's turn; else X's turn
    }

    // ── STEP 7: Redirect (PRG Pattern) ────────────────────────
    // After processing a POST/GET action, redirect to the same page
    // This prevents the browser from re-submitting if you refresh the page
    // "Post/Redirect/Get" is a standard web pattern
    header('Location: index.php');
    exit; // IMPORTANT: always call exit after header('Location: ...')
}

// ── PHP Helper Functions ────────────────────────────────────

/**
 * getCellDisplay($value)
 * Returns the HTML to show inside a board cell
 * Empty string → empty cell
 * 'X' → red X with animation class
 * 'O' → blue O with animation class
 */
function getCellDisplay(string $value): string {
    if ($value === 'X') return '<span class="mark x-mark">✕</span>';
    if ($value === 'O') return '<span class="mark o-mark">○</span>';
    return ''; // Empty cell shows nothing
}

// Grab session values into shorter local variables for easy use in HTML
$board         = $_SESSION['board'];
$currentPlayer = $_SESSION['current_player'];
$winner        = $_SESSION['winner'];
$winCells      = $_SESSION['winning_cells'];
$scoreX        = $_SESSION['score_x'];
$scoreO        = $_SESSION['score_o'];
$draws         = $_SESSION['draws'];
?>
<!DOCTYPE html>
<!--
    HTML5 Document Structure (Unit I Syllabus)
    DOCTYPE tells the browser this is HTML5
    lang="en" helps screen readers and search engines
-->
<html lang="en">
<head>
    <!-- UTF-8 charset supports all characters including symbols -->
    <meta charset="UTF-8">

    <!-- Viewport meta tag makes the page responsive on mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tic-Tac-Toe – Lab Q20 (PHP Sessions)</title>

    <!-- Bootstrap 5 CDN (Unit I: CSS Framework) -->
    <!-- CDN = Content Delivery Network: loads CSS from the internet -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Google Fonts: Inter for clean modern look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap"
          rel="stylesheet">

    <style>
        /* ── CSS Custom Properties (Variables) ──────────────── */
        /* Makes it easy to change the color theme in one place  */
        :root {
            --x-color:   #e74c3c;   /* Red for X player  */
            --o-color:   #3498db;   /* Blue for O player */
            --win-color: #2ecc71;   /* Green for winner  */
            --bg:        #1a1a2e;   /* Dark background   */
            --surface:   #16213e;   /* Card background   */
            --border:    #0f3460;   /* Border color      */
        }

        /* Base page styles */
        body {
            background: var(--bg);          /* Dark navy background */
            color: #eaeaea;                 /* Light text */
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;                  /* Flexbox centering (Unit II: flex) */
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        /* Main game container card */
        .game-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,.5);
            max-width: 480px;
            width: 100%;
        }

        /* ── Scoreboard ──────────────────────────────────────── */
        .scoreboard {
            display: flex;                      /* Flexbox layout */
            justify-content: space-around;      /* Equal spacing */
            background: rgba(255,255,255,.05);  /* Semi-transparent */
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .score-item { text-align: center; }
        .score-num  { font-size: 2rem; font-weight: 900; line-height: 1; }
        .score-lbl  { font-size: .75rem; color: #888; margin-top: 4px; }
        .score-x    { color: var(--x-color); }
        .score-o    { color: var(--o-color); }
        .score-draw { color: #aaa; }

        /* ── Status Banner ───────────────────────────────────── */
        .status-banner {
            text-align: center;
            padding: .75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all .3s ease;          /* Smooth color change */
        }
        .status-banner.turn-x   { background: rgba(231,76,60,.15);  color: var(--x-color); }
        .status-banner.turn-o   { background: rgba(52,152,219,.15); color: var(--o-color); }
        .status-banner.win-x    { background: rgba(231,76,60,.3);   color: var(--x-color); font-size: 1.3rem; }
        .status-banner.win-o    { background: rgba(52,152,219,.3);  color: var(--o-color); font-size: 1.3rem; }
        .status-banner.draw     { background: rgba(255,255,255,.1); color: #ccc; }

        /* ── 3×3 Game Board (CSS Grid) ──────────────────────── */
        /* CSS Grid creates the 3-column layout automatically    */
        .board {
            display: grid;                              /* CSS Grid display */
            grid-template-columns: repeat(3, 1fr);     /* 3 equal columns  */
            gap: 12px;                                  /* Space between cells */
            margin-bottom: 1.5rem;
        }

        /* Each individual cell */
        .cell {
            aspect-ratio: 1 / 1;            /* Always a perfect square */
            background: var(--border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            cursor: pointer;
            text-decoration: none;          /* Remove link underline */
            transition: background .2s, transform .1s;  /* Hover animation */
            border: 2px solid transparent;
        }
        /* Hover effect: only on empty clickable cells */
        .cell:hover:not(.taken):not(.game-over) {
            background: rgba(255,255,255,.12);
            transform: scale(1.05);        /* Slightly enlarge on hover */
            border-color: #555;
        }

        /* Winning cells: highlighted green */
        .cell.winning {
            background: rgba(46,204,113,.2);
            border-color: var(--win-color);
            animation: pulse 1s infinite alternate; /* Pulsing animation */
        }

        /* Taken cells can't be clicked */
        .cell.taken { cursor: default; }
        .cell.game-over { cursor: default; }

        /* ── Player marks (X and O) ──────────────────────────── */
        .mark { font-style: normal; font-weight: 900; line-height: 1; }
        .x-mark {
            color: var(--x-color);
            animation: popIn .2s ease-out;    /* Pop-in animation when placed */
        }
        .o-mark {
            color: var(--o-color);
            animation: popIn .2s ease-out;
        }

        /* ── CSS Animations (keyframes) ─────────────────────── */
        /* @keyframes defines the start and end of an animation  */
        @keyframes popIn {
            from { transform: scale(0) rotate(-10deg); opacity: 0; }
            to   { transform: scale(1) rotate(0deg);   opacity: 1; }
        }
        @keyframes pulse {
            from { transform: scale(1); }
            to   { transform: scale(1.06); }
        }
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to   { transform: translateY(0);     opacity: 1; }
        }

        /* Animate the whole board in on load */
        .board { animation: slideDown .4s ease-out; }

        /* ── Buttons ─────────────────────────────────────────── */
        .btn-new-game {
            width: 100%;
            padding: .75rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .5px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: #fff;
            transition: opacity .2s, transform .1s;
        }
        .btn-new-game:hover {
            opacity: .9;
            transform: translateY(-2px);    /* Lift effect on hover */
            color: #fff;
        }
        .btn-reset-score {
            width: 100%;
            margin-top: .5rem;
            background: transparent;
            border: 1px solid #555;
            color: #888;
            border-radius: 10px;
            padding: .5rem;
            font-size: .85rem;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-reset-score:hover { border-color: #e74c3c; color: #e74c3c; }

        /* ── PHP Info Box (for examiner) ─────────────────────── */
        .info-box {
            background: rgba(255,255,255,.04);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            font-size: .8rem;
            color: #888;
        }
        .info-box code { color: #7ec8e3; background: none; }

        /* Responsive: smaller font on very small screens */
        @media (max-width: 400px) {
            .cell { font-size: 2rem; border-radius: 8px; }
            .game-card { padding: 1.2rem; }
        }
    </style>
</head>

<body>
<!-- ──────────────────────────────────────────────────────────
     MAIN GAME CARD
     Bootstrap class "container" + custom .game-card for layout
     ────────────────────────────────────────────────────────── -->
<div class="container py-4">
<div class="game-card mx-auto">

    <!-- Game Title -->
    <h1 class="text-center fw-black mb-1" style="font-size:1.8rem;letter-spacing:-1px">
        ✕ Tic-Tac-Toe ○
    </h1>
    <p class="text-center text-secondary small mb-3">Lab Q20 · PHP Sessions + JavaScript DOM</p>

    <!-- ── SCOREBOARD ─────────────────────────────────────────
         Displays X wins, Draws, O wins
         PHP variables $scoreX, $draws, $scoreO printed with echo
         ──────────────────────────────────────────────────────── -->
    <div class="scoreboard">
        <!-- Player X score -->
        <div class="score-item">
            <div class="score-num score-x"><?= $scoreX ?></div>
            
            <div class="score-lbl">✕ PLAYER X</div>
        </div>
        <!-- Draws -->
        <div class="score-item">
            <div class="score-num score-draw"><?= $draws ?></div>
            <div class="score-lbl">DRAWS</div>
        </div>
        <!-- Player O score -->
        <div class="score-item">
            <div class="score-num score-o"><?= $scoreO ?></div>
            <div class="score-lbl">○ PLAYER O</div>
        </div>
    </div>

    <!-- ── STATUS BANNER ──────────────────────────────────────
         Shows: "X's Turn" / "O's Turn" / "X Wins!" / "Draw!"
         PHP if/elseif/else decides which message to show
         ──────────────────────────────────────────────────────── -->
    <?php
    // Determine which CSS class and message to show
    if ($winner === 'X'):
    ?>
        <div class="status-banner win-x">🎉 Player X Wins!</div>
    <?php elseif ($winner === 'O'): ?>
        <div class="status-banner win-o">🎉 Player O Wins!</div>
    <?php elseif ($winner === 'draw'): ?>
        <div class="status-banner draw">🤝 It's a Draw!</div>
    <?php elseif ($currentPlayer === 'X'): ?>
        <div class="status-banner turn-x">✕ Player X's Turn</div>
    <?php else: ?>
        <div class="status-banner turn-o">○ Player O's Turn</div>
    <?php endif; ?>

    <!-- ── GAME BOARD ──────────────────────────────────────────
         3×3 grid. Each cell is an <a href="?move=N"> link.
         PHP foreach loop iterates over the $board array (0 to 8)
         ──────────────────────────────────────────────────────── -->
    <div class="board">
        <?php
        // Loop through each of the 9 cells (index 0 to 8)
        // $index = cell number (0-8)
        // $value = '' (empty), 'X', or 'O'
        foreach ($board as $index => $value):

            // Build CSS class string for this cell
            $classes = 'cell';

            // If this cell is in the winning combination → add 'winning' class
            if (in_array($index, $winCells)) {
                $classes .= ' winning';
            }

            // If cell is already taken → mark as 'taken'
            if ($value !== '') {
                $classes .= ' taken';
            }

            // If game is over (winner or draw) → mark all as 'game-over' (not clickable)
            if ($winner !== null) {
                $classes .= ' game-over';
            }

            // Determine the link href:
            // - If game over OR cell taken → '#' (no action)
            // - Else → '?move=N' to send this move to PHP
            $href = ($winner !== null || $value !== '')
                    ? '#'
                    : '?move=' . $index;
        ?>
            <!--
                Each cell is a link <a href="...">
                CSS class changes based on game state
                PHP echo fills in the X or O mark
            -->
            <a href="<?= htmlspecialchars($href) ?>" class="<?= $classes ?>">
                <?= getCellDisplay($value) ?>
                <!-- getCellDisplay() returns HTML span for X, O, or empty -->
            </a>

        <?php endforeach; // End of foreach loop ?>
    </div>

    <!-- ── BUTTONS ─────────────────────────────────────────────
         New Game: links to ?reset=1 which resets the board
         Reset Score: links to ?reset_score=1
         ──────────────────────────────────────────────────────── -->
    <a href="?reset=1" class="btn btn-new-game text-decoration-none text-center d-block">
        🔄 New Game
    </a>
    <a href="?reset_score=1" class="btn-reset-score d-block text-center text-decoration-none">
        Reset Score
    </a>

    <!-- ── INFO BOX ────────────────────────────────────────────
         Shows technical info for the examiner
         ──────────────────────────────────────────────────────── -->
    <div class="info-box mt-3">
        <strong style="color:#7ec8e3">🔧 How it works:</strong><br>
        Board stored in <code>$_SESSION['board']</code> (PHP array[9])<br>
        Move sent via <code>GET ?move=N</code> → PHP updates session<br>
        Winner check: 8 combinations via <code>foreach</code> loop<br>
        Session ID: <code style="color:#f39c12;font-size:.75rem">
            <?= substr(session_id(), 0, 20) ?>…
        </code>
    </div>

</div><!-- /game-card -->
</div><!-- /container -->

<!-- ── JavaScript Section (Unit II Syllabus) ──────────────────
     JavaScript runs in the browser AFTER PHP has sent the HTML
     Used here for: sound effects, keyboard shortcuts, click feedback
     ──────────────────────────────────────────────────────────── -->
<script>
// ── DOM is fully loaded before we run JS ─────────────────────
// 'DOMContentLoaded' fires when all HTML is parsed (not waiting for images)
document.addEventListener('DOMContentLoaded', function () {

    // ── SOUND EFFECTS using Web Audio API ────────────────────
    // AudioContext creates sound programmatically (no audio file needed)
    // This is a modern JavaScript API built into all browsers
    const AudioContext = window.AudioContext || window.webkitAudioContext;

    /**
     * playSound(frequency, duration, type)
     * Generates a beep using oscillator (sound wave generator)
     * @param {number} frequency - Hz (440 = A4 note)
     * @param {number} duration  - seconds
     * @param {string} type      - 'sine'|'square'|'triangle'
     */
    function playSound(frequency, duration, type = 'sine') {
        try {
            const ctx  = new AudioContext();          // Create audio context
            const osc  = ctx.createOscillator();      // Sound wave generator
            const gain = ctx.createGain();            // Volume controller

            osc.type      = type;
            osc.frequency.setValueAtTime(frequency, ctx.currentTime);

            // Fade out: starts at volume 0.3, fades to 0 over duration
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);

            // Connect: oscillator → gain → speakers
            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.start();                              // Start playing
            osc.stop(ctx.currentTime + duration);    // Stop after duration
        } catch (e) {
            // If audio fails (some browsers restrict it), silently ignore
        }
    }

    // ── Play sound based on game state ───────────────────────
   
    const winner        = "<?= htmlspecialchars($winner ?? '') ?>";
    const currentPlayer = "<?= htmlspecialchars($currentPlayer) ?>";

    if (winner === 'X' || winner === 'O') {
        // Win: play a happy ascending chord
        playSound(523, 0.15);                        // C5
        setTimeout(() => playSound(659, 0.15), 150); // E5
        setTimeout(() => playSound(784, 0.4),  300); // G5
    } else if (winner === 'draw') {
        // Draw: descending sad notes
        playSound(392, 0.2);                         // G4
        setTimeout(() => playSound(349, 0.2), 200);  // F4
        setTimeout(() => playSound(294, 0.4), 400);  // D4
    }

    // ── Click feedback on cells ───────────────────────────────
    // querySelectorAll selects ALL elements matching '.cell' (DOM manipulation)
    const cells = document.querySelectorAll('.cell:not(.taken):not(.game-over)');

    // addEventListener attaches a function to each cell's click event
    cells.forEach(function(cell) {
        cell.addEventListener('click', function () {
            if (this.getAttribute('href') !== '#') {
                playSound(440, 0.1, 'triangle');     // Click sound
            }
        });
    });

    // ── Keyboard shortcut: Press 'N' to start new game ───────
    // keydown event fires when any key is pressed
    document.addEventListener('keydown', function (event) {
        if (event.key === 'n' || event.key === 'N') {
            window.location.href = '?reset=1';
        }
    });

    // ── Add tooltip showing cell position ────────────────────
    // querySelectorAll returns a NodeList (like an array) of all .cell links
    document.querySelectorAll('.cell').forEach(function(cell, index) {
        // getAttribute reads the href attribute of the element
        if (cell.getAttribute('href') !== '#') {
            cell.title = 'Click to place ' + currentPlayer + ' in cell ' + (index + 1);
        }
    });
});
</script>

<?php
// ── Handle Score Reset (separate GET action) ─────────────────
// If ?reset_score=1 is in the URL, clear only the score counters
// This runs AFTER the HTML has been output (normally put at top,
// but since we only write to session, it's fine here for teaching clarity)
if (isset($_GET['reset_score'])) {
    $_SESSION['score_x'] = 0;
    $_SESSION['score_o'] = 0;
    $_SESSION['draws']   = 0;
    // Redirect back to avoid re-triggering on refresh
    header('Location: index.php');
    exit;
}
?>
</body>
</html>
