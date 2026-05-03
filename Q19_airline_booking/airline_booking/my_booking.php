<?php
// ============================================================
// my_booking.php — Retrieve Booking by PNR
// User enters their PNR code to see booking details
// Concepts: $_GET vs $_POST, SQL JOIN, string functions
// ============================================================
require_once 'includes/db.php';
require_once 'includes/helpers.php';

$booking = null;  // will hold fetched booking data
$error   = '';

// ── Handle PNR search ──
// We accept PNR via GET (search form) so URL is shareable
if (isset($_GET['pnr']) && !empty($_GET['pnr'])) {
    // strtoupper() converts entered PNR to uppercase (our PNRs are uppercase)
    $pnr = strtoupper(trim($_GET['pnr']));
    
    // Validate: PNR should be exactly 6 alphanumeric characters
    // preg_match() uses regular expression:
    // ^ = start, [A-Z0-9] = uppercase letters or digits, {6} = exactly 6, $ = end
    if (!preg_match('/^[A-Z0-9]{6}$/', $pnr)) {
        $error = "Invalid PNR format. PNR should be 6 alphanumeric characters (e.g. AX7K2P).";
    } else {
        // ── SQL JOIN query ──
        // JOIN combines data from multiple tables into one result row
        // b = bookings alias, f = flights alias, s = seats alias
        $stmt = $pdo->prepare("
            SELECT 
                b.*,                                    -- all booking columns
                f.flight_no, f.origin, f.destination,  -- flight details
                f.depart_time, f.price,
                s.seat_no, s.class, s.row_num, s.col_label  -- seat details
            FROM bookings b
            JOIN flights f ON b.flight_id = f.id        -- link booking → flight
            JOIN seats   s ON b.seat_id   = s.id        -- link booking → seat
            WHERE b.pnr = ?                              -- filter by PNR
        ");
        $stmt->execute([$pnr]);
        $booking = $stmt->fetch(); // one row or false

        if (!$booking) {
            $error = "No booking found with PNR: <strong>" . htmlspecialchars($pnr) . "</strong>. Please check and try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking — SkyBook</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <div class="brand"><span>✈️</span> SkyBook Airlines</div>
    <div>
        <a href="index.php">All Flights</a>
    </div>
</nav>

<div class="container" style="max-width:680px">

    <!-- PNR Lookup Card -->
    <div class="card">
        <h2>🔍 Retrieve Booking</h2>
        <p style="color:var(--muted);margin-bottom:22px">
            Enter the 6-character PNR code you received when booking.
        </p>

        <!-- GET form: data appears in URL, useful for shareable searches -->
        <form method="get" action="" id="pnrForm">
            <div style="display:flex;gap:12px;align-items:flex-end">
                <div class="form-group" style="flex:1;margin-bottom:0">
                    <label for="pnr">PNR / Booking Reference</label>
                    <input type="text"
                           id="pnr"
                           name="pnr"
                           value="<?= clean($_GET['pnr'] ?? '') ?>"
                           placeholder="e.g. AX7K2P"
                           maxlength="6"
                           style="text-transform:uppercase;font-size:1.2rem;font-weight:700;letter-spacing:4px;font-family:monospace"
                           required>
                    <!-- style="text-transform:uppercase" — CSS auto-capitalizes input visually -->
                </div>
                <button type="submit" class="btn btn-primary" style="white-space:nowrap">
                    🔍 Find Booking
                </button>
            </div>
        </form>
    </div>

    <!-- Error Alert -->
    <?php if ($error): ?>
        <div class="alert alert-error">❌ <?= $error ?></div>
    <?php endif; ?>

    <!-- Booking Details (shown only if PNR found) -->
    <?php if ($booking): ?>
    <div class="card">
        <div class="booking-confirm">
            <!-- Checkmark and heading -->
            <div style="font-size:3rem">✅</div>
            <h2 style="color:var(--success);margin:8px 0 4px">Booking Found</h2>
            <p style="color:var(--muted);margin-bottom:16px">Your Booking Reference:</p>
            
            <!-- Large PNR display (monospace font for readability) -->
            <div class="pnr"><?= clean($booking['pnr']) ?></div>
            
            <p style="margin-top:8px;font-size:0.85rem;color:var(--muted)">
                Booked on <?= date('d M Y \a\t h:i A', strtotime($booking['booked_at'])) ?>
            </p>

            <!-- Detail Table -->
            <div style="max-width:480px;margin:24px auto 0;text-align:left">
                <!-- Each detail-row: label on left, value on right (flexbox) -->
                <div class="detail-row">
                    <span class="label">Passenger Name</span>
                    <span class="value"><?= clean($booking['passenger']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Email</span>
                    <span class="value"><?= clean($booking['email']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Phone</span>
                    <span class="value"><?= clean($booking['phone']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Flight</span>
                    <span class="value"><?= clean($booking['flight_no']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Route</span>
                    <span class="value">
                        <?= clean($booking['origin']) ?> ✈️ <?= clean($booking['destination']) ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="label">Departure</span>
                    <span class="value">
                        <?= date('D, d M Y', strtotime($booking['depart_time'])) ?><br>
                        <span style="font-size:0.9rem;color:var(--sky)">
                            <?= date('h:i A', strtotime($booking['depart_time'])) ?>
                        </span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="label">Seat Number</span>
                    <span class="value" style="font-size:1.3rem;color:var(--deep)">
                        <?= clean($booking['seat_no']) ?>
                        <span style="font-size:0.85rem;color:var(--muted);font-weight:400">
                            (Row <?= $booking['row_num'] ?>, Seat <?= $booking['col_label'] ?>)
                        </span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="label">Class</span>
                    <span class="value" style="color:<?= $booking['class']==='Business' ? 'var(--gold)' : 'var(--success)' ?>">
                        <?= seatClassLabel($booking['class']) ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="label">Amount Paid</span>
                    <span class="value" style="font-size:1.2rem;color:var(--sky)">
                        <?= formatPrice($booking['price']) ?>
                    </span>
                </div>
            </div>

            <!-- Boarding pass style footer -->
            <div style="margin-top:24px;padding:16px;background:linear-gradient(135deg,var(--deep),var(--sky));border-radius:10px;color:#fff">
                <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px">
                    <div>
                        <div style="font-size:0.75rem;opacity:0.7;text-transform:uppercase">From</div>
                        <div style="font-size:1.4rem;font-weight:800"><?= strtoupper(substr($booking['origin'],0,3)) ?></div>
                        <div style="font-size:0.85rem;opacity:0.8"><?= clean($booking['origin']) ?></div>
                    </div>
                    <div style="font-size:2rem;align-self:center">✈️</div>
                    <div style="text-align:right">
                        <div style="font-size:0.75rem;opacity:0.7;text-transform:uppercase">To</div>
                        <div style="font-size:1.4rem;font-weight:800"><?= strtoupper(substr($booking['destination'],0,3)) ?></div>
                        <div style="font-size:0.85rem;opacity:0.8"><?= clean($booking['destination']) ?></div>
                    </div>
                </div>
            </div>

            <div style="margin-top:20px;display:flex;gap:12px;justify-content:center">
                <a href="index.php" class="btn btn-primary">✈️ Book Another</a>
                <a href="seat_map.php?flight=<?= $booking['flight_id'] ?>" class="btn btn-deep">🗺️ View Seat Map</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Help text when nothing searched yet -->
    <?php if (!$booking && !$error && !isset($_GET['pnr'])): ?>
    <div class="card" style="text-align:center;padding:40px">
        <div style="font-size:3rem;margin-bottom:12px">🎟️</div>
        <h3 style="color:var(--muted);font-weight:400">Enter your PNR above to view booking details</h3>
        <p style="margin-top:12px;color:var(--muted);font-size:0.9rem">
            Your PNR was shown on the confirmation page after booking
        </p>
        <a href="index.php" style="display:inline-block;margin-top:20px" class="btn btn-primary">
            ← Browse Flights
        </a>
    </div>
    <?php endif; ?>

</div>

<script>
// Auto-uppercase the PNR input as user types
// addEventListener with 'input' event fires on every keystroke
document.getElementById('pnr').addEventListener('input', function() {
    // this.value refers to the input element's current value
    this.value = this.value.toUpperCase(); // JS string method
});
</script>
</body>
</html>
