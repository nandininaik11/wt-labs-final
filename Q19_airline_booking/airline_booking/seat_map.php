<?php
// ============================================================
// seat_map.php — Visual Seat Map + Booking Form
// CORE PAGE: Shows airplane seat grid, lets user pick a seat,
//            then fill passenger details to confirm booking
// Concepts: $_GET, 2D arrays, dynamic CSS classes, JS DOM
// ============================================================
require_once 'includes/db.php';
require_once 'includes/helpers.php';

// ── Get flight ID from URL query string ──
// URL: seat_map.php?flight=2 → $_GET['flight'] = "2"
$flightId = isset($_GET['flight']) ? (int)$_GET['flight'] : 0;

// Validate: must be a positive integer
if ($flightId <= 0) {
    header("Location: index.php");
    exit();
}

// ── Fetch flight details ──
$stmt = $pdo->prepare("SELECT * FROM flights WHERE id = ?");
$stmt->execute([$flightId]);
$flight = $stmt->fetch(); // one row or false

if (!$flight) {
    die("Flight not found. <a href='index.php'>Back to flights</a>");
}

// ── Fetch all seats for this flight ──
// ORDER BY row_num, col_label → seats come in order: 1A, 1B, 1C, 1D, ...
$stmt = $pdo->prepare("SELECT * FROM seats WHERE flight_id = ? ORDER BY row_num, col_label");
$stmt->execute([$flightId]);
$allSeats = $stmt->fetchAll();

// ── Organise seats into a 2D array for easy rendering ──
// $seatMap[rowNum][colLabel] = seat data
// Example: $seatMap[3]['B'] = ['id'=>..., 'seat_no'=>'3B', 'is_booked'=>0, ...]
$seatMap = [];
foreach ($allSeats as $seat) {
    // Two-dimensional array: first index = row, second = column
    $seatMap[$seat['row_num']][$seat['col_label']] = $seat;
}

// Stats for display
$totalSeats   = count($allSeats);
$bookedSeats  = count(array_filter($allSeats, fn($s) => $s['is_booked'])); // array_filter with arrow function
$availSeats   = $totalSeats - $bookedSeats;

// ── Handle booking form submission (POST) ──
$bookingSuccess = null; // will hold booking data after success
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form inputs
    $seatId    = (int)$_POST['seat_id'];
    $passenger = clean($_POST['passenger']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);

    // ── Server-side Validation ──
    if (empty($passenger) || strlen($passenger) < 3) {
        $error = "Please enter a valid passenger name (min 3 characters).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // PHP built-in email validation filter
        $error = "Please enter a valid email address.";
    } elseif (!validPhone($phone)) {
        $error = "Please enter a valid 10-digit Indian mobile number.";
    } elseif ($seatId <= 0) {
        $error = "Please select a seat from the map above.";
    } else {
        // Re-verify seat is still available (race condition: someone else might book it)
        $stmt = $pdo->prepare("SELECT * FROM seats WHERE id=? AND flight_id=? AND is_booked=0");
        $stmt->execute([$seatId, $flightId]);
        $seat = $stmt->fetch();

        if (!$seat) {
            $error = "This seat was just booked. Please select another seat.";
        } else {
            // ── Transaction: all-or-nothing DB operation ──
            // If any step fails, ROLLBACK undoes all changes
            try {
                $pdo->beginTransaction(); // start transaction

                // Step 1: Generate unique PNR (loop until unique)
                do {
                    $pnr = generatePNR();
                    $chk = $pdo->prepare("SELECT id FROM bookings WHERE pnr=?");
                    $chk->execute([$pnr]);
                } while ($chk->fetch()); // retry if PNR already exists

                // Step 2: Mark seat as booked
                $upd = $pdo->prepare("UPDATE seats SET is_booked=1 WHERE id=?");
                $upd->execute([$seatId]);

                // Step 3: Insert booking record
                $ins = $pdo->prepare("
                    INSERT INTO bookings (flight_id, seat_id, passenger, email, phone, pnr)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $ins->execute([$flightId, $seatId, $passenger, $email, $phone, $pnr]);

                $pdo->commit(); // save all changes permanently

                // Prepare success data to display on page
                $bookingSuccess = [
                    'pnr'       => $pnr,
                    'passenger' => $passenger,
                    'email'     => $email,
                    'seat_no'   => $seat['seat_no'],
                    'class'     => $seat['class'],
                    'flight_no' => $flight['flight_no'],
                    'route'     => $flight['origin'].' → '.$flight['destination'],
                    'depart'    => $flight['depart_time'],
                    'price'     => $flight['price'],
                ];

                // Refresh seat map data after booking
                $stmt = $pdo->prepare("SELECT * FROM seats WHERE flight_id=? ORDER BY row_num, col_label");
                $stmt->execute([$flightId]);
                $allSeats = $stmt->fetchAll();
                $seatMap = [];
                foreach ($allSeats as $s) $seatMap[$s['row_num']][$s['col_label']] = $s;

            } catch (Exception $e) {
                $pdo->rollBack(); // undo all DB changes
                $error = "Booking failed. Please try again. Error: " . $e->getMessage();
            }
        }
    }
}

// Column labels — A, B, C = left side | D, E, F = right side
$leftCols  = ['A', 'B', 'C'];
$rightCols = ['D', 'E', 'F'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Map — <?= clean($flight['flight_no']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <div class="brand"><span>✈️</span> SkyBook Airlines</div>
    <div>
        <a href="index.php">← All Flights</a>
        <a href="my_booking.php">My Booking</a>
    </div>
</nav>

<div class="container">

    <!-- Flight Info Banner -->
    <div class="hero" style="padding:28px 24px;margin-bottom:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
            <div style="text-align:left">
                <div style="font-size:1.8rem;font-weight:900"><?= clean($flight['flight_no']) ?></div>
                <div style="font-size:1.2rem;margin:4px 0">
                    <?= clean($flight['origin']) ?> ✈️ <?= clean($flight['destination']) ?>
                </div>
                <div style="opacity:0.8;font-size:0.9rem">
                    <?= date('D, d M Y • h:i A', strtotime($flight['depart_time'])) ?>
                </div>
            </div>
            <div style="text-align:right">
                <div style="font-size:2rem;font-weight:900"><?= formatPrice($flight['price']) ?></div>
                <div style="opacity:0.8">per seat</div>
                <div style="margin-top:6px">
                    <span style="background:rgba(255,255,255,0.25);padding:4px 12px;border-radius:99px;font-size:0.85rem">
                        <?= $availSeats ?> / <?= $totalSeats ?> seats available
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Confirmation (shown after booking) -->
    <?php if ($bookingSuccess): ?>
    <div class="card">
        <div class="booking-confirm">
            <div style="font-size:3rem;margin-bottom:8px">🎉</div>
            <h2 style="color:var(--success);margin-bottom:4px">Booking Confirmed!</h2>
            <p style="color:var(--muted);margin-bottom:20px">Your PNR (Booking Reference) is:</p>
            <div class="pnr"><?= $bookingSuccess['pnr'] ?></div>
            <p style="margin:16px 0;color:var(--muted);font-size:0.9rem">Save this code to retrieve your booking</p>

            <div style="max-width:400px;margin:0 auto;text-align:left">
                <div class="detail-row"><span class="label">Passenger</span><span class="value"><?= clean($bookingSuccess['passenger']) ?></span></div>
                <div class="detail-row"><span class="label">Flight</span><span class="value"><?= clean($bookingSuccess['flight_no']) ?></span></div>
                <div class="detail-row"><span class="label">Route</span><span class="value"><?= clean($bookingSuccess['route']) ?></span></div>
                <div class="detail-row"><span class="label">Departure</span><span class="value"><?= date('d M Y, h:i A', strtotime($bookingSuccess['depart'])) ?></span></div>
                <div class="detail-row"><span class="label">Seat</span><span class="value"><?= $bookingSuccess['seat_no'] ?> (<?= $bookingSuccess['class'] ?>)</span></div>
                <div class="detail-row"><span class="label">Amount Paid</span><span class="value" style="color:var(--sky)"><?= formatPrice($bookingSuccess['price']) ?></span></div>
            </div>

            <div style="margin-top:24px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                <a href="index.php" class="btn btn-primary">✈️ Book Another Flight</a>
                <a href="my_booking.php" class="btn btn-deep">🔍 View My Booking</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error">❌ <?= $error ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start">
        <!-- LEFT: Seat Map -->
        <div class="card" style="padding:20px">
            <h2>🛩️ Seat Map</h2>

            <!-- Legend -->
            <div class="seat-legend">
                <div class="legend-item">
                    <div style="width:20px;height:20px;background:#fef3c7;border:2px solid #fcd34d;border-radius:4px"></div>
                    <span>Business</span>
                </div>
                <div class="legend-item">
                    <div style="width:20px;height:20px;background:#dcfce7;border:2px solid #86efac;border-radius:4px"></div>
                    <span>Available</span>
                </div>
                <div class="legend-item">
                    <div style="width:20px;height:20px;background:#fee2e2;border:2px solid #fca5a5;border-radius:4px"></div>
                    <span>Booked</span>
                </div>
                <div class="legend-item">
                    <div style="width:20px;height:20px;background:var(--sky);border:2px solid var(--deep);border-radius:4px"></div>
                    <span>Selected</span>
                </div>
            </div>

            <div class="plane-wrapper">
                <!-- Plane nose graphic -->
                <div class="plane-nose">✈️</div>

                <div class="plane-cabin">
                    <!-- Column headers row -->
                    <div class="col-headers">
                        <div></div>             <!-- row label space -->
                        <?php foreach ($leftCols as $col): ?>
                            <div><?= $col ?></div>
                        <?php endforeach; ?>
                        <div style="font-size:0.7rem">🚶</div> <!-- aisle icon -->
                        <?php foreach ($rightCols as $col): ?>
                            <div><?= $col ?></div>
                        <?php endforeach; ?>
                        <div></div>             <!-- right edge space -->
                    </div>

                    <?php
                    // ── Render each row of seats ──
                    // PHP range(1, 10) creates array [1, 2, 3, ..., 10]
                    foreach (range(1, 10) as $rowNum):
                        $isBusinessRow = ($rowNum <= 2); // first 2 rows are business

                        // Show divider between Business and Economy
                        if ($rowNum === 3): ?>
                            <div class="class-divider">Economy Class</div>
                        <?php elseif ($rowNum === 1): ?>
                            <div class="class-divider">Business Class</div>
                        <?php endif; ?>

                        <!-- One row of seats -->
                        <div class="seat-row">
                            <!-- Row number label (left) -->
                            <div class="row-label"><?= $rowNum ?></div>

                            <!-- Left side seats: A, B, C -->
                            <?php foreach ($leftCols as $col):
                                $seat = $seatMap[$rowNum][$col] ?? null;
                                if (!$seat) continue;
                                // Build CSS classes dynamically
                                $classes  = 'seat';
                                $classes .= $isBusinessRow ? ' business' : '';
                                $classes .= $seat['is_booked'] ? ' booked' : ' available';
                                $title    = $seat['seat_no'] . ' (' . $seat['class'] . ') — ' . ($seat['is_booked'] ? 'Booked' : 'Available');
                            ?>
                                <button
                                    class="<?= $classes ?>"
                                    <?= $seat['is_booked'] ? 'disabled' : '' ?>
                                    data-seat-id="<?= $seat['id'] ?>"
                                    data-seat-no="<?= $seat['seat_no'] ?>"
                                    data-class="<?= $seat['class'] ?>"
                                    title="<?= $title ?>"
                                    onclick="selectSeat(this)"
                                >
                                    <?= $seat['seat_no'] ?>
                                </button>
                            <?php endforeach; ?>

                            <!-- Aisle gap -->
                            <div class="aisle"></div>

                            <!-- Right side seats: D, E, F -->
                            <?php foreach ($rightCols as $col):
                                $seat = $seatMap[$rowNum][$col] ?? null;
                                if (!$seat) continue;
                                $classes  = 'seat';
                                $classes .= $isBusinessRow ? ' business' : '';
                                $classes .= $seat['is_booked'] ? ' booked' : ' available';
                                $title    = $seat['seat_no'] . ' (' . $seat['class'] . ') — ' . ($seat['is_booked'] ? 'Booked' : 'Available');
                            ?>
                                <button
                                    class="<?= $classes ?>"
                                    <?= $seat['is_booked'] ? 'disabled' : '' ?>
                                    data-seat-id="<?= $seat['id'] ?>"
                                    data-seat-no="<?= $seat['seat_no'] ?>"
                                    data-class="<?= $seat['class'] ?>"
                                    title="<?= $title ?>"
                                    onclick="selectSeat(this)"
                                >
                                    <?= $seat['seat_no'] ?>
                                </button>
                            <?php endforeach; ?>

                            <!-- Row label (right) -->
                            <div class="row-label"><?= $rowNum ?></div>
                        </div><!-- /.seat-row -->

                    <?php endforeach; // end rows loop ?>
                </div><!-- /.plane-cabin -->
            </div><!-- /.plane-wrapper -->
        </div>

        <!-- RIGHT: Booking Form -->
        <div class="card">
            <h2>📋 Passenger Details</h2>

            <!-- Selected seat info (hidden until seat is clicked via JS) -->
            <div class="selected-info" id="selectedInfo">
                <strong>✅ Selected Seat:</strong>
                <span id="displaySeat" style="font-size:1.1rem;font-weight:800;color:var(--deep)"></span>
                <span id="displayClass" style="margin-left:8px;font-size:0.85rem;color:var(--muted)"></span>
            </div>

            <form method="post" action="" id="bookingForm">
                <!-- Hidden field: stores selected seat ID, submitted with form -->
                <!-- Hidden inputs are not shown to user but are sent in POST data -->
                <input type="hidden" name="seat_id" id="seatIdInput" value="0">

                <div class="form-group">
                    <label for="passenger">Full Name *</label>
                    <input type="text" id="passenger" name="passenger"
                           value="<?= clean($_POST['passenger'] ?? '') ?>"
                           placeholder="As on your ID card" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email"
                           value="<?= clean($_POST['email'] ?? '') ?>"
                           placeholder="your@email.com" required>
                </div>

                <div class="form-group">
                    <label for="phone">Mobile Number *</label>
                    <input type="tel" id="phone" name="phone"
                           value="<?= clean($_POST['phone'] ?? '') ?>"
                           placeholder="10-digit Indian mobile" maxlength="10" required>
                </div>

                <div style="background:#f0f9ff;border-radius:8px;padding:14px;margin-bottom:18px;font-size:0.9rem">
                    <div style="display:flex;justify-content:space-between">
                        <span style="color:var(--muted)">Ticket Price</span>
                        <strong><?= formatPrice($flight['price']) ?></strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-top:6px">
                        <span style="color:var(--muted)">Flight</span>
                        <strong><?= clean($flight['flight_no']) ?> — <?= clean($flight['origin']) ?> to <?= clean($flight['destination']) ?></strong>
                    </div>
                </div>

                <button type="submit" class="btn btn-success" style="width:100%;justify-content:center;font-size:1.05rem">
                    🎟️ Confirm Booking
                </button>

                <p style="text-align:center;margin-top:12px;font-size:0.82rem;color:var(--muted)">
                    Please select a green seat from the map first
                </p>
            </form>
        </div>
    </div>

</div><!-- /.container -->

<!-- ============================================================
     JavaScript — Client-Side Interactivity
     Handles seat selection, form validation before submit
     DOM Manipulation: selecting elements, changing classes
     ============================================================ -->
<script>
// ── Track currently selected seat element ──
let currentSelected = null; // will hold reference to the selected <button> DOM element

// ── selectSeat(btn) — called when user clicks a seat button ──
function selectSeat(btn) {
    // 1. De-select previously selected seat
    if (currentSelected) {
        // classList.remove() removes a CSS class from an element
        currentSelected.classList.remove('selected');
        // classList.add() adds a CSS class — restore original class
        currentSelected.classList.add(
            currentSelected.dataset.class === 'Business' ? 'business' : '', 'available'
        );
    }

    // 2. Mark new seat as selected
    btn.classList.remove('available');  // remove green available style
    btn.classList.add('selected');       // add blue selected style
    currentSelected = btn;               // remember reference

    // 3. Get seat data from data-* HTML attributes
    // dataset.seatId reads data-seat-id attribute; dataset.seatNo reads data-seat-no
    const seatId  = btn.dataset.seatId;
    const seatNo  = btn.dataset.seatNo;
    const seatCls = btn.dataset.class;

    // 4. Update hidden input value → seat_id will be submitted in POST
    // document.getElementById() finds element by id attribute
    document.getElementById('seatIdInput').value = seatId;

    // 5. Show selected seat info panel (CSS: display:none → display:block via class)
    const info = document.getElementById('selectedInfo');
    info.classList.add('visible');  // CSS .selected-info.visible { display: block }

    // 6. Update text in the info panel
    // innerHTML / textContent: ways to set element content in JS
    document.getElementById('displaySeat').textContent = seatNo;
    document.getElementById('displayClass').textContent = '(' + seatCls + ' Class)';
}

// ── Client-side form validation ──
// preventDefault() stops the browser from submitting the form
// This runs BEFORE the POST request is sent to the server
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const seatId   = document.getElementById('seatIdInput').value;
    const name     = document.getElementById('passenger').value.trim();
    const email    = document.getElementById('email').value.trim();
    const phone    = document.getElementById('phone').value.trim();

    if (seatId === '0' || seatId === '') {
        alert('⚠️ Please click on a green seat in the seat map first!');
        e.preventDefault(); // stop form submission
        return;
    }
    if (name.length < 3) {
        alert('⚠️ Please enter your full name (min 3 characters).');
        e.preventDefault();
        return;
    }
    // Basic email format check using JavaScript regex
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('⚠️ Please enter a valid email address.');
        e.preventDefault();
        return;
    }
    // Indian mobile: 10 digits starting with 6-9
    if (!/^[6-9]\d{9}$/.test(phone)) {
        alert('⚠️ Please enter a valid 10-digit Indian mobile number.');
        e.preventDefault();
        return;
    }
    // If all valid, form submits normally to PHP server
});
</script>
</body>
</html>
