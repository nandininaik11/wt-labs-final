# 📊 Array Operations — JavaScript
### Lab Question 3 | Web Technology (WT) — Unit II: JavaScript Arrays

---

## 📁 FILE STRUCTURE

```
array_operations/
└── index.html    ← Everything in ONE file: HTML + CSS + JavaScript
```

> ✅ NO Node.js, NO npm, NO XAMPP needed — just open in browser!

---

## ⚙️ HOW TO RUN

### Method 1 — Direct (Simplest)
Double-click `index.html` → opens in browser ✅

### Method 2 — VS Code Live Server (Best for demo)
1. Open folder in VS Code
2. Install **"Live Server"** extension (Ctrl+Shift+X → search Live Server)
3. Right-click `index.html` → **Open with Live Server**
4. Opens at `http://127.0.0.1:5500`

---

## 🖥️ EXPECTED OUTPUT — 8 Tabs to Show Examiner

**Tab 1 ↩️ Reverse**
Input: `5, 3, 8, 1, 9, 2, 7, 4` → Click Reverse
Shows: Original boxes [0]=5 [1]=3... → Reversed boxes [0]=4 [1]=7...
Output: swap steps displayed (Swap [0]=5 ↔ [7]=4, etc.)

**Tab 2 🫧 Bubble Sort**
Animated bar chart — yellow bars = comparing, green = sorted
Steps + swaps counter updates live
Output: shows every swap per pass

**Tab 3 🎯 Selection Sort**
Animated — purple = current minimum, yellow = comparing
Shows: "Pass 1: Min found: 8 at [6] → swap with [0]"

**Tab 4 📌 Insertion Sort**
Shows key being inserted and elements shifting right

**Tab 5 ⚡ Built-in Sort**
4 buttons: Ascending / Descending / String sort / Sort by Length
SHOW THIS: Click "Sort as Strings" → [10, 9, 100] sorts to [10, 100, 9] ← demonstrates JS bug!
Then click "Sort Ascending" → [9, 10, 100] ← correct

**Tab 6 🔍 Linear Search**
Animated step-by-step: each element highlighted yellow, found = green
Search for 11 in [15, 4, 8, 22, 11, 7, 33, 19] → "✅ FOUND at index [4], 5 comparisons"

**Tab 7 🎯 Binary Search**
Animated — shows left/right/mid pointers each iteration
"✅ FOUND! 23 at index [5] — took 1 step. Linear would need 6."

**Tab 8 🛠️ More Operations**
12 live buttons — push, pop, shift, unshift, splice, slice, filter, map, reduce, concat
Each shows Before → After → JS code used

---

## 📖 THEORY (WT Syllabus Unit II)

### Arrays in JavaScript
An array is an ordered collection. Zero-indexed, dynamic size, mixed types allowed.
```js
let arr = [5, 3, 8, 1];   // array literal
arr[0]        // 5  (first element)
arr.length    // 4
```

### Reverse — Two Pointer Technique
```js
let left = 0, right = arr.length - 1;
while (left < right) {
  [arr[left], arr[right]] = [arr[right], arr[left]]; // destructuring swap
  left++;  right--;
}
// Time: O(n)  Space: O(1)
```

### Bubble Sort — Compare Adjacent, Swap if Wrong Order
```js
for (let i = 0; i < n-1; i++) {
  let swapped = false;
  for (let j = 0; j < n-i-1; j++) {
    if (arr[j] > arr[j+1]) {
      [arr[j], arr[j+1]] = [arr[j+1], arr[j]];
      swapped = true;
    }
  }
  if (!swapped) break; // optimization: already sorted
}
// Best: O(n) | Worst: O(n²) | Stable: YES
```

### Selection Sort — Find Min, Swap to Front
```js
for (let i = 0; i < n-1; i++) {
  let minIdx = i;
  for (let j = i+1; j < n; j++)
    if (arr[j] < arr[minIdx]) minIdx = j;
  [arr[i], arr[minIdx]] = [arr[minIdx], arr[i]];
}
// Always O(n²) | Stable: NO | Only O(n) swaps
```

### Insertion Sort — Insert One Card at a Time
```js
for (let i = 1; i < n; i++) {
  let key = arr[i], j = i-1;
  while (j >= 0 && arr[j] > key) { arr[j+1] = arr[j]; j--; }
  arr[j+1] = key;
}
// Best: O(n) | Worst: O(n²) | Stable: YES
```

### Built-in .sort() — ALWAYS USE COMPARATOR FOR NUMBERS
```js
[10, 9, 100].sort()              // [10, 100, 9] ← WRONG! (sorts as strings)
[10, 9, 100].sort((a,b) => a-b)  // [9, 10, 100] ← Correct ascending
[10, 9, 100].sort((a,b) => b-a)  // [100, 10, 9] ← Descending
```

### Linear Search — Check One by One
```js
function linearSearch(arr, target) {
  for (let i = 0; i < arr.length; i++)
    if (arr[i] === target) return i;
  return -1; // -1 = not found
}
// Best: O(1) | Worst: O(n) | Works on UNSORTED arrays
```

### Binary Search — Halve Search Space Each Step
```js
function binarySearch(arr, target) {
  let left = 0, right = arr.length - 1;
  while (left <= right) {
    let mid = Math.floor((left + right) / 2);
    if (arr[mid] === target) return mid;
    else if (arr[mid] < target) left = mid + 1;  // search right
    else right = mid - 1;                         // search left
  }
  return -1;
}
// Best: O(1) | Worst: O(log n) | REQUIRES SORTED ARRAY
```

### Sorting Comparison Table
| Algorithm    | Best  | Worst | Space | Stable |
|-------------|-------|-------|-------|--------|
| Bubble Sort  | O(n)  | O(n²) | O(1)  | ✅ Yes |
| Selection Sort| O(n²)| O(n²) | O(1)  | ❌ No  |
| Insertion Sort| O(n) | O(n²) | O(1)  | ✅ Yes |
| JS .sort()   | O(n log n)| O(n log n)| O(log n)| ✅ Yes|

---

## ❓ VIVA QUESTIONS + ANSWERS

**Q1: What is an array? How is it declared in JavaScript?**
An ordered collection of values accessed by index (starting 0).
Three ways: `let a = [1,2,3]` (literal), `new Array(3)` (constructor), `Array.from("abc")` (from iterable).
JS arrays are dynamic (resize automatically), mixed-type, and are technically objects.

**Q2: What is the two-pointer technique for reversing?**
Start with left=0 and right=last index. Swap arr[left] and arr[right] using destructuring `[a,b]=[b,a]`, then move both pointers inward (left++, right--). Stop when left >= right. O(n) time, O(1) space — no extra array needed.

**Q3: Explain Bubble Sort. What is the swapped flag optimization?**
Compares adjacent pairs, swaps if out of order. After each pass, the largest element reaches its correct position. The `swapped` flag tracks if any swap occurred in a pass — if no swap, array is already sorted and we break early. This makes the best case O(n) instead of always O(n²).

**Q4: Why is Selection Sort called "selection"? Is it stable?**
It "selects" the minimum element from the unsorted portion in each pass and places it at the beginning of the unsorted part. It is NOT stable — it can change the relative order of equal elements because swaps can skip over elements.

**Q5: When would you choose Insertion Sort over Bubble Sort?**
Insertion Sort is preferred when: (1) array is nearly sorted — it performs very few shifts; (2) elements arrive one at a time (online algorithm); (3) small arrays — simpler logic, less overhead. Both have O(n²) worst case but insertion sort makes fewer comparisons in practice.

**Q6: What is the biggest mistake with JavaScript's .sort()? How to fix it?**
Without a comparator, .sort() converts numbers to strings. "100" comes before "9" because "1" < "9" in ASCII/Unicode order. Fix: always pass `(a,b) => a-b` for ascending numeric sort. The comparator returns negative (a first), positive (b first), or 0 (unchanged).

**Q7: What does Linear Search return if element is not found? Why -1?**
Returns -1. Convention: -1 is used because it's not a valid array index (indices start at 0). This way you can check: `if (result === -1)` to know it wasn't found. Built-in `indexOf()` follows the same convention.

**Q8: Why must Binary Search have a sorted array?**
Binary Search decides to go left or right based on comparing mid element with target. If array is unsorted, this decision is meaningless — the target could be anywhere on either side. The algorithm would give wrong results or miss the element entirely.

**Q9: Calculate: how many steps does Binary Search need for 1000 elements?**
Maximum steps = ceil(log₂(1000)) = ceil(9.97) = 10 steps.
Linear Search would need up to 1000 steps in the worst case.
This is why Binary Search is O(log n) — extremely efficient for large arrays.

**Q10: What is the difference between splice() and slice()?**
`splice(start, count)` — MUTATES original array, removes elements, returns removed.
`slice(start, end)` — does NOT mutate, returns a NEW sub-array.
Memory trick: s**p**lice = s**p**oils (changes) original.

**Q11: Explain map(), filter(), reduce() with examples.**
All return new arrays/values without mutating the original:
```js
[1,2,3,4].map(x => x*2)          // [2,4,6,8]   — transform each
[1,2,3,4].filter(x => x>2)       // [3,4]        — keep matching
[1,2,3,4].reduce((a,x) => a+x, 0) // 10          — collapse to one value
```

**Q12: What is DOM manipulation? How did you use it in this project?**
DOM (Document Object Model) represents the HTML page as a tree of objects that JavaScript can read and modify.
Used in this project:
- `document.getElementById('bub-visual')` — get the chart container
- `container.innerHTML = bars.map(...).join('')` — inject all bar HTML at once
- `element.classList.add('comparing')` — change bar color to yellow
- `element.textContent = steps` — update step counter number
- `document.createElement('style')` — dynamically add CSS

**Q13: What is async/await? Why is it needed for sorting animations?**
`async` marks a function as asynchronous. `await` pauses execution until a Promise resolves. We use `await sleep(100)` (where sleep = `new Promise(resolve => setTimeout(resolve, 100))`) to pause 100ms between each comparison. Without this, the browser would freeze while all comparisons run instantly and only show the final result. async/await lets the browser re-render (update bar colors) between each step.

**Q14: What is the spread operator `...` and destructuring? Where used?**
Spread `[...arr]` creates a shallow copy of an array — used to avoid mutating the original when sorting.
Destructuring swap `[arr[i], arr[j]] = [arr[j], arr[i]]` swaps two values simultaneously without a temp variable. Used in every sort algorithm in this project.

**Q15: What are the Time and Space complexities of all algorithms?**
| | Best | Worst | Space |
|--|------|-------|-------|
| Reverse | O(n) | O(n) | O(1) |
| Bubble Sort | O(n) | O(n²) | O(1) |
| Selection Sort | O(n²) | O(n²) | O(1) |
| Insertion Sort | O(n) | O(n²) | O(1) |
| Linear Search | O(1) | O(n) | O(1) |
| Binary Search | O(1) | O(log n) | O(1) |

All are "in-place" algorithms — O(1) space means no extra array needed.

---

## 🔗 Concept → Syllabus Mapping

| Feature in Code          | Syllabus Unit |
|--------------------------|---------------|
| Array declaration, access | Unit II — Arrays |
| for/while loops in algorithms | Unit II — Control Structures |
| function declarations    | Unit II — Functions and Scopes |
| Arrow functions `(a,b)=>a-b` | Unit II — JavaScript |
| `document.getElementById()` | Unit II — DOM Objects |
| `innerHTML`, `textContent` | Unit II — Manipulating DOM |
| `classList.add/remove`   | Unit II — Manipulating DOM |
| `onclick`, event handlers | Unit II — Events |
| async/await, setTimeout  | Unit II — JavaScript |
| CSS variables, flexbox, grid | Unit I — CSS |
| HTML5 form `<input>`, `<button>` | Unit I — HTML5 |

*Good luck! 📊🎯*
