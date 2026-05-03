# ✕ Lab Q20 – Tic-Tac-Toe in PHP
## Single-file game using PHP Sessions + JavaScript DOM

---

## 📁 File Structure

```
lab20_tictactoe/
├── index.php       ← The ENTIRE game (PHP + HTML + CSS + JS in one file)
└── README.md       ← This file (theory + viva Q&A)
```

That's it — just **one file**! This is intentional to show how PHP and HTML can be mixed.

---

## ⚙️ How to Run (Step-by-Step for Beginners)

### Option A: XAMPP (Recommended — works on Windows/Mac/Linux)

**Step 1: Install XAMPP**
Download from: https://www.apachefriends.org/download.html

**Step 2: Start Apache**
Open XAMPP Control Panel → Click "Start" next to Apache

**Step 3: Place the file**
```
Windows: Copy lab20_tictactoe/ to → C:\xampp\htdocs\lab20_tictactoe\
Linux:   Copy lab20_tictactoe/ to → /opt/lampp/htdocs/lab20_tictactoe/
Mac:     Copy lab20_tictactoe/ to → /Applications/XAMPP/htdocs/lab20_tictactoe/
```

**Step 4: Open browser**
```
http://localhost/lab20_tictactoe/
```

---

### Option B: VS Code + PHP built-in server (No XAMPP needed)

**Step 1: Check PHP is installed**
```bash
php --version
# Should show: PHP 8.x.x ...
# If not installed:
#   Windows: download from https://windows.php.net/download/
#   Ubuntu: sudo apt install php
#   Mac: brew install php
```

**Step 2: Open the folder in VS Code**
```bash
# In terminal:
cd lab20_tictactoe
code .
```

**Step 3: Open VS Code Terminal (Ctrl + `)** and run:
```bash
php -S localhost:8000
```
You will see:
```
PHP 8.x.x Development Server (http://localhost:8000) started
```

**Step 4: Open browser**
```
http://localhost:8000
```

**Step 5: Stop the server**
Press `Ctrl + C` in the terminal.

---

### Option C: VS Code Extension (PHP Server)

1. Install extension: **"PHP Server"** by brapifra (VS Code Extensions tab)
2. Right-click `index.php` → "PHP Server: Serve project"
3. Browser opens automatically

---

## 🖥️ Expected Output (What to Show Examiner)

### Initial Screen
```
[ ✕ Tic-Tac-Toe ○ ]
[ Lab Q20 · PHP Sessions + JavaScript DOM ]

[ 0 ]  [ 0 ]  [ 0 ]
[Player X] [Draws] [Player O]

[ ✕ Player X's Turn ]   ← Blue/Red status banner

[   ] [   ] [   ]
[   ] [   ] [   ]   ← Empty 3×3 board (dark cells)
[   ] [   ] [   ]

[ 🔄 New Game ]
[ Reset Score ]

🔧 How it works: Board stored in $_SESSION['board']...
```

### After X wins
```
[ 1 ]  [ 0 ]  [ 0 ]
                        ← Score incremented

[ 🎉 Player X Wins! ]   ← Red banner

[✕] [   ] [✕]
[   ] [✕] [   ]         ← Winning cells glow GREEN
[✕] [   ] [   ]         ← Win sound plays (3 ascending beeps)
```

### After a Draw
```
[ 1 ]  [ 1 ]  [ 0 ]
[ 🤝 It's a Draw! ]     ← Gray banner + descending sad sound
```

### Demo Script for Examiner:
1. Open `http://localhost:8000`
2. Play a full game — click cells alternately as X and O
3. Reach a win — point out: winning cells glow green, score updates
4. Click "New Game" — board resets, score stays
5. Right-click page → View Page Source — show PHP-generated HTML
6. Press F12 → Network tab → click a cell → show GET request `?move=3`
7. Point out Session ID in the info box at the bottom

---

## 📖 Complete Theory (WT Syllabus Mapping)

### Unit III: PHP Sessions (Core Concept)

A **session** is a way to remember data between page loads.
When you click a cell, the page reloads, but PHP "remembers" the board through sessions.

```php
session_start();                          // Start session (line 1!)

$_SESSION['board'] = ['','','','','','','','',''];   // Store board array
$_SESSION['current_player'] = 'X';                   // Store whose turn

// Next page load: data is still there!
echo $_SESSION['current_player'];  // Outputs: X
```

**How sessions work internally:**
1. PHP creates a unique Session ID (e.g., `abc123xyz`)
2. Sends it to browser as a cookie: `PHPSESSID=abc123xyz`
3. Browser sends this cookie on every request
4. PHP looks up the session file and loads the data

**Session file on server:**
```
/tmp/sess_abc123xyz → contains serialized $_SESSION data
```

### Unit III: PHP Arrays (Used for the board)

```php
// Indexed array: positions 0 to 8
$board = ['', '', '', '', '', '', '', '', ''];
//         0    1    2    3    4    5    6    7    8

// Represents:
// [0][1][2]
// [3][4][5]
// [6][7][8]

// Access by index
$board[4] = 'X';   // Center cell

// Loop through array
foreach ($board as $index => $value) {
    echo "Cell $index: $value";
}

// Count non-empty cells (check for draw)
count(array_filter($board));  // array_filter removes empty strings
```

### Unit III: PHP Form Handling / GET

```php
// When user clicks <a href="?move=4">
// PHP receives: $_GET['move'] = "4"

if (isset($_GET['move'])) {       // Check if 'move' exists in GET
    $cell = (int)$_GET['move'];   // Cast to integer (security)
    // (int) ensures malicious input like "4; DROP TABLE" becomes just 4
}
```

### Unit III: PHP Control Structures

```php
// if / elseif / else
if ($winner === 'X') {
    echo "X wins!";
} elseif ($winner === 'O') {
    echo "O wins!";
} elseif ($winner === 'draw') {
    echo "Draw!";
} else {
    echo "Game continues";
}

// foreach loop
foreach ($board as $index => $value) {
    // $index = 0,1,2,...,8
    // $value = '', 'X', or 'O'
}

// Ternary operator (shorthand if/else)
$next = ($current === 'X') ? 'O' : 'X';
// Reads: "if current is X, then O, else X"
```

### Unit III: PHP Functions

```php
// Function declaration
function getCellDisplay(string $value): string {
    // string $value = type hint (PHP 7+)
    // : string      = return type hint
    if ($value === 'X') return '<span class="x-mark">✕</span>';
    if ($value === 'O') return '<span class="o-mark">○</span>';
    return '';  // Empty string for empty cells
}

// Call the function
echo getCellDisplay('X');   // Outputs: <span class="x-mark">✕</span>
```

### Unit III: PRG Pattern (Post/Redirect/Get)

```php
// After processing a move, ALWAYS redirect:
header('Location: index.php');
exit;  // STOP execution after redirect

// Why? Without redirect:
// User plays → page shows result → user presses F5 (refresh)
// → Browser re-sends the GET request → move is processed AGAIN!
// With redirect: F5 just reloads the clean page
```

### Unit I: HTML5 Structure

```html
<!DOCTYPE html>      ← Declares HTML5 (not HTML4 or XHTML)
<html lang="en">
<head>
    <meta charset="UTF-8">               ← Character encoding
    <meta name="viewport" ...>           ← Mobile responsive
    <title>Game</title>
    <link rel="stylesheet" href="...">   ← External CSS
</head>
<body>
    <!-- Content here -->
    <script src="..."></script>          ← JS at bottom (best practice)
</body>
</html>
```

### Unit I: CSS Grid (for 3×3 board)

```css
.board {
    display: grid;                          /* Use CSS Grid layout */
    grid-template-columns: repeat(3, 1fr);  /* 3 equal columns */
    gap: 12px;                              /* Space between cells */
}
/* Each child <a> automatically goes into the next grid cell */
/* No need to manually position! */
```

### Unit I: CSS Flexbox (for centering)

```css
body {
    display: flex;           /* Enable flexbox */
    align-items: center;     /* Vertical center */
    justify-content: center; /* Horizontal center */
    min-height: 100vh;       /* Full viewport height */
}
```

### Unit II: JavaScript DOM Manipulation

```javascript
// Select element(s)
const cell = document.getElementById('cell-0');        // Single by ID
const cells = document.querySelectorAll('.cell');      // Multiple by class

// Add event listener
cell.addEventListener('click', function() {
    // 'this' = the clicked element
    this.style.background = 'green';   // Change style
    this.textContent = 'X';           // Change content
});

// Loop through NodeList (like array)
cells.forEach(function(cell, index) {
    cell.title = 'Cell ' + (index + 1);  // Set tooltip
});
```

### Unit II: JavaScript and PHP interaction

```javascript
// PHP can embed values INTO JavaScript using <?= ?>
const winner = "<?= htmlspecialchars($winner ?? '') ?>";
// If $winner = 'X', this becomes:
const winner = "X";
// JavaScript can now use this PHP variable!

// This runs AFTER PHP finishes, in the browser
if (winner === 'X') {
    playWinSound();
}
```

---

## ❓ Viva Questions + Answers

### Basic (Q1–Q10)

**Q1. What is PHP? Name its key features.**
PHP (Hypertext Preprocessor) is a server-side scripting language designed for web development.
Key features:
- **Open source** and free
- **Embeds in HTML**: `<?php echo "Hello"; ?>`
- **Server-side**: Code runs on server, browser only receives HTML
- **Database support**: Works with MySQL, PostgreSQL, SQLite
- **Session/cookie management**: Built-in `session_start()`, `$_SESSION`, `$_COOKIE`
- **Cross-platform**: Runs on Windows, Linux, Mac

**Q2. What is a PHP Session? How is it used in this game?**
A session stores data on the SERVER across multiple page requests. The client only stores a Session ID (in a cookie called `PHPSESSID`).

In this game:
- `$_SESSION['board']` = array of 9 cells (the game state)
- `$_SESSION['current_player']` = 'X' or 'O'
- `$_SESSION['winner']` = null / 'X' / 'O' / 'draw'
- `$_SESSION['score_x']`, `$_SESSION['score_o']` = persistent scores

Without sessions, every page reload would forget the board!

**Q3. What is `session_start()` and where must it be placed?**
`session_start()` initializes (or resumes) a session. It **must be the very first thing** in the PHP file, before ANY output (even spaces or blank lines), because it needs to send HTTP headers to set the PHPSESSID cookie.

```php
<?php
session_start();  // FIRST LINE — before echo, HTML, spaces
?>
```

**Q4. What does `$_SESSION` superglobal contain?**
`$_SESSION` is a PHP superglobal array (available everywhere without needing to pass it). It stores key-value pairs that persist across page requests for a specific user:
```php
$_SESSION['board'] = ['', '', '', 'X', '', '', '', 'O', ''];
$_SESSION['score_x'] = 3;
```

**Q5. How is the game board represented in PHP?**
As a flat indexed array with 9 elements (indices 0–8):
```php
$board = ['', '', '', '', '', '', '', '', ''];
//         [0][1][2]
//         [3][4][5]
//         [6][7][8]
```
Empty string = empty cell, 'X' or 'O' = claimed cell.

**Q6. What is `$_GET` and how does a move get sent to PHP?**
`$_GET` is a superglobal array containing data from the URL query string.

In this game, cells are `<a href="?move=4">` links. Clicking sends:
```
GET /index.php?move=4 HTTP/1.1
```
PHP reads: `$_GET['move']` = "4"

**Q7. How does PHP detect a winner?**
By checking all 8 winning combinations using a foreach loop:
```php
$wins = [
    [0,1,2], [3,4,5], [6,7,8],  // 3 rows
    [0,3,6], [1,4,7], [2,5,8],  // 3 columns
    [0,4,8], [2,4,6]             // 2 diagonals
];
foreach ($wins as $combo) {
    if ($board[$combo[0]] !== ''
        && $board[$combo[0]] === $board[$combo[1]]
        && $board[$combo[1]] === $board[$combo[2]]) {
        // Winner found!
    }
}
```

**Q8. How is a draw detected?**
```php
// A draw: no winner AND all 9 cells are filled
// array_filter() removes empty strings from the array
// count() on the result tells us how many non-empty cells exist
if ($winner === null && count(array_filter($board)) === 9) {
    $winner = 'draw';
}
```

**Q9. What is the difference between GET and POST? Which is used here?**
- **GET**: Data in URL (`?move=4`), visible, bookmarkable, for fetching
- **POST**: Data in request body, hidden, for submitting forms

This game uses **GET** because:
- Each cell click is a simple data retrieval (not form submission)
- The URL (`?move=4`) can be bookmarked/shared
- Easy to implement with `<a href>` links

**Q10. What is CSS Grid and how does it create the 3×3 board?**
```css
.board {
    display: grid;
    grid-template-columns: repeat(3, 1fr);  /* 3 equal columns */
    gap: 12px;
}
```
CSS Grid automatically places the 9 `<a>` children into a 3-column layout. `1fr` means "1 fraction of available space" — so each column gets 1/3 of the width. No manual positioning needed.

---

### Intermediate (Q11–Q20)

**Q11. What is the PRG (Post/Redirect/Get) pattern?**
After processing a GET/POST action that changes data, redirect to a clean URL:
```php
header('Location: index.php');
exit;  // Critical: stop all further execution
```
Without this: pressing F5 (refresh) re-sends the move, causing bugs. With PRG: F5 just reloads the page without re-sending the move.

**Q12. What are PHP superglobals? List all of them.**
Superglobals are built-in PHP arrays available in ALL scopes (inside functions too):
| Superglobal | Contains |
|---|---|
| `$_SESSION` | Session data (server-side) |
| `$_GET` | URL query parameters |
| `$_POST` | Form POST data |
| `$_COOKIE` | Browser cookies |
| `$_SERVER` | Server/request info (IP, method, path) |
| `$_FILES` | Uploaded file data |
| `$_REQUEST` | Combined GET + POST + COOKIE |
| `$_ENV` | Environment variables |
| `$GLOBALS` | All global variables |

**Q13. Explain `isset()` and `empty()` in PHP.**
```php
isset($var)   // true if variable exists AND is not null
empty($var)   // true if variable is: '', 0, '0', null, false, [], or unset

// In this game:
if (!isset($_SESSION['board'])) {
    // $_SESSION['board'] doesn't exist yet → first visit
    $_SESSION['board'] = ['', '', '', '', '', '', '', '', ''];
}

if ($board[$index] !== '') {  // Cell is NOT empty
    // Cell is taken
}
```

**Q14. What is `htmlspecialchars()` and why is it used?**
It converts special HTML characters to safe entities to prevent XSS:
```php
htmlspecialchars('<script>alert("hack")</script>')
// Output: &lt;script&gt;alert(&quot;hack&quot;)&lt;/script&gt;
// Browser displays as text, NOT executes as script
```
Used in this game for: `href`, `winner` value, any user-influenced output.

**Q15. Explain the ternary operator used in this code.**
```php
$next = ($currentPlayer === 'X') ? 'O' : 'X';
// Syntax: condition ? value_if_true : value_if_false
// Same as:
if ($currentPlayer === 'X') {
    $next = 'O';
} else {
    $next = 'X';
}
```

**Q16. How does JavaScript interact with PHP in this file?**
PHP runs SERVER-SIDE first, generates HTML+JS, sends to browser.
JavaScript then runs CLIENT-SIDE in the browser:

```php
// PHP embeds its value into JS at render time:
$winner = $_SESSION['winner'] ?? '';
```
```javascript
// Generated JavaScript has PHP value baked in:
const winner = "X";   // PHP filled this in

if (winner === "X") {
    playWinSound();   // JS plays sound in the browser
}
```

**Q17. Explain JavaScript's `addEventListener` and event handling.**
```javascript
// Attach a function to an event on a DOM element
element.addEventListener('click', function(event) {
    // 'event' = the event object (has info like which key, mouse position)
    // 'this' = the element that was clicked
    this.style.background = 'green';
});

// 'DOMContentLoaded' fires when HTML is fully parsed (before images load)
document.addEventListener('DOMContentLoaded', function() {
    // Safe to manipulate DOM here
});

// 'keydown' fires when any key is pressed
document.addEventListener('keydown', function(event) {
    if (event.key === 'N') { ... }
});
```

**Q18. What are CSS Animations and how are they used in this game?**
```css
/* Define animation: name, from state, to state */
@keyframes popIn {
    from { transform: scale(0) rotate(-10deg); opacity: 0; }
    to   { transform: scale(1) rotate(0deg);   opacity: 1; }
}

/* Apply animation to element */
.x-mark {
    animation: popIn 0.2s ease-out;
    /* Name  Duration  Timing-function */
}
```
In this game:
- `popIn`: X/O marks pop in when placed
- `pulse`: Winning cells glow/pulse
- `slideDown`: Board slides in on page load

**Q19. How does `array_filter()` work in PHP?**
```php
$board = ['X', '', 'O', '', 'X', '', 'O', 'X', 'O'];

// array_filter() removes "falsy" values by default
// In PHP, '' (empty string) is falsy
$filled = array_filter($board);
// Result: ['X', 'O', 'X', 'O', 'X', 'O'] (removes empty strings)

count($filled);  // 6 → 6 cells are filled
count($filled) === 9  // false → not a draw yet
```

**Q20. What is `in_array()` used for in this game?**
```php
$winCells = [0, 4, 8];  // Diagonal that won

// Check if index 4 is a winning cell
if (in_array(4, $winCells)) {
    // Add 'winning' CSS class → cell glows green
    $classes .= ' winning';
}
```
`in_array($needle, $haystack)` returns true if `$needle` exists in `$haystack` array.

---

### Advanced (Q21–Q28)

**Q21. What is the difference between `==` and `===` in PHP?**
```php
// == (loose comparison): checks value only, allows type coercion
0 == ''    // true  (0 is loosely equal to empty string)
0 == false // true
1 == '1'   // true

// === (strict comparison): checks value AND type
0 === ''    // false (int vs string → different types)
1 === '1'   // false
'X' === 'X' // true ← used in this game for safety
```
This game uses `===` throughout to avoid bugs like `'' == 0` returning true.

**Q22. How would you add an AI opponent?**
```php
function getBestAIMove(array $board): int {
    // Check if AI (O) can win in one move
    foreach (getWins() as $combo) {
        if ($board[$combo[0]] === 'O' && $board[$combo[1]] === 'O' && $board[$combo[2]] === '') return $combo[2];
        // ... check other orderings
    }
    // Block X from winning
    foreach (getWins() as $combo) {
        if ($board[$combo[0]] === 'X' && $board[$combo[1]] === 'X' && $board[$combo[2]] === '') return $combo[2];
    }
    // Take center
    if ($board[4] === '') return 4;
    // Take any corner
    foreach ([0,2,6,8] as $corner) {
        if ($board[$corner] === '') return $corner;
    }
    // Take any empty cell
    foreach ($board as $i => $v) {
        if ($v === '') return $i;
    }
}
```
For perfect AI, use the **Minimax algorithm** — recursively evaluates all possible game states.

**Q23. What is Minimax algorithm (AI concept)?**
A decision tree algorithm used in two-player games:
- Recursively tries ALL possible moves
- MAX player (AI/O) picks the move with highest score
- MIN player (Human/X) picks the move with lowest score (worst for AI)
- Returns +10 for AI win, -10 for human win, 0 for draw

**Q24. What is the Web Audio API used for in this game?**
```javascript
const ctx = new AudioContext();      // Sound engine
const osc = ctx.createOscillator(); // Sound wave generator
osc.frequency.value = 440;          // 440 Hz = musical note A4
osc.type = 'sine';                  // Wave shape: sine, square, triangle, sawtooth
osc.connect(ctx.destination);       // Connect to speakers
osc.start();                        // Play
osc.stop(ctx.currentTime + 0.3);    // Stop after 0.3 seconds
```
No external audio files needed — generates sound mathematically.

**Q25. What is `header('Location: ...')` and why must `exit` follow it?**
```php
header('Location: index.php');  // Send HTTP 302 redirect to browser
exit;                           // STOP all PHP execution immediately

// Without exit:
header('Location: index.php');
// PHP continues executing below code!
$_SESSION['board'] = [];        // This still runs! Bug!
echo "This still runs!";        // This still runs!
```
`header()` only sends the HTTP header; the PHP script continues unless you call `exit`.

**Q26. What are PHP type hints and how are they used?**
```php
// Type hints enforce parameter and return types (PHP 7+)
function getCellDisplay(string $value): string {
//                      ^^^^^^          ^^^^^^
//                      param type      return type

    // PHP will throw a TypeError if you pass a non-string
    // or try to return a non-string
}

// Without type hints (PHP 5 style):
function getCellDisplay($value) {
    // $value could be anything — no type safety
}
```

**Q27. How would you implement this game with AJAX (no page reload)?**
Instead of `<a href="?move=N">`, use JavaScript fetch():
```javascript
// Frontend: JavaScript sends move
async function makeMove(cell) {
    const response = await fetch('move.php', {
        method: 'POST',
        body: JSON.stringify({ cell: cell }),
        headers: { 'Content-Type': 'application/json' }
    });
    const data = await response.json();
    updateBoard(data.board);     // Update DOM without reload
    if (data.winner) showWinner(data.winner);
}
```
```php
// move.php: PHP processes move, returns JSON
<?php
session_start();
$input = json_decode(file_get_contents('php://input'), true);
$cell  = (int)$input['cell'];
$_SESSION['board'][$cell] = $_SESSION['current_player'];
// ... check winner ...
echo json_encode(['board' => $_SESSION['board'], 'winner' => $_SESSION['winner']]);
```

**Q28. What is the difference between client-side and server-side code in this file?**
```
PHP (Server-side):                    JavaScript (Client-side):
━━━━━━━━━━━━━━━━                      ━━━━━━━━━━━━━━━━━━━━━━━━
Runs on SERVER                        Runs in BROWSER
Runs BEFORE sending HTML              Runs AFTER receiving HTML
Has access to $_SESSION               Has access to DOM elements
Can connect to database               Can play sounds, animate
User cannot see PHP code              User CAN see JS (View Source)
Used for: game logic, state           Used for: sound, animation,
         winner check, redirect                keyboard shortcuts
```

---

## 🔑 Key Concepts Quick Reference

| Concept | Code |
|---|---|
| Start session | `session_start()` — Line 1! |
| Store in session | `$_SESSION['key'] = value` |
| Read GET param | `$_GET['move']` |
| Check key exists | `isset($_GET['move'])` |
| Loop array | `foreach ($board as $i => $v)` |
| Check array | `in_array($val, $arr)` |
| Filter empty | `array_filter($board)` |
| Redirect | `header('Location: url'); exit;` |
| PHP echo shorthand | `<?= $var ?>` same as `<?php echo $var ?>` |
| Prevent XSS | `htmlspecialchars($input)` |
| Strict compare | `===` not `==` |
| Ternary | `$x = (cond) ? a : b` |
| Null coalescing | `$v = $_GET['k'] ?? 'default'` |
