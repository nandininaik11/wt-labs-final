<?php
// ============================================================
// index.php — Flight Listing Page (Home)
// Shows all available flights with seat availability
// Concepts: PDO SELECT, PHP foreach, HTML cards, CSS Grid
// ============================================================
require_once 'includes/db.php';      // get $pdo connection
require_once 'includes/helpers.php'; // get helper functions

// ── Fetch all flights from database ──
// SQL: SELECT everything from flights, ORDER BY departure time ascending
$flights = $pdo->query("SELECT * FROM flights ORDER BY depart_time ASC")->fetchAll();
// fetchAll() returns array of all rows: [ [id=>1, flight_no=>'AI-101', ...], ... ]
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Viewport: makes page mobile-friendly -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✈️ SkyBook — Flight Booking</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navigation Bar (sticky — stays at top while scrolling) -->
<nav>
    <div class="brand"><span>✈️</span> SkyBook Airlines</div>
    <div>
        <a href="index.php">Flights</a>
        <a href="my_booking.php">My Booking</a>
    </div>
</nav>

<div class="container">

    <!-- Hero Banner -->
    <div class="hero">
        <h1>✈️ Book Your Flight</h1>
        <p>Select a flight below to view the seat map and book your seat</p>
    </div>

    <!-- Flights Grid -->
    <div class="card">
        <h2>🛫 Available Flights</h2>

        <?php if (empty($flights)): ?>
            <!-- Alternative syntax: if(): ... endif; — readable in HTML templates -->
            <div class="alert alert-info">No flights available right now.</div>
        <?php else: ?>

            <div class="flights-grid">
                <?php foreach ($flights as $f): ?>
                    <?php
                    // Call helper function to get available seat count
                    $avail = availableSeats($pdo, $f['id']);
                    $total = $f['total_seats'];
                    
                    // Determine badge color based on availability
                    // Ternary operator: condition ? valueIfTrue : valueIfFalse
                    if ($avail == 0)        $badgeClass = 'avail-none';
                    elseif ($avail <= 10)   $badgeClass = 'avail-low';
                    else                    $badgeClass = 'avail-good';
                    ?>
                    
                    <!-- Each flight is a clickable card linking to seat map -->
                    <!-- PHP string interpolation: {$f['id']} inside double quotes -->
                    <a href="seat_map.php?flight=<?= $f['id'] ?>" class="flight-card">
                        
                        <!-- Flight number -->
                        <div class="fn">🛫 <?= clean($f['flight_no']) ?></div>
                        
                        <!-- Route: Origin → Destination -->
                        <div class="route">
                            <strong><?= clean($f['origin']) ?></strong>
                            <span class="arrow">→</span>
                            <strong><?= clean($f['destination']) ?></strong>
                        </div>

                        <!-- Departure time -->
                        <!-- date() formats a date; strtotime() parses DB datetime string to Unix timestamp -->
                        <div style="font-size:0.88rem;color:var(--muted)">
                            🕐 <?= date('D, d M Y • h:i A', strtotime($f['depart_time'])) ?>
                        </div>

                        <!-- Price and seat availability -->
                        <div class="meta">
                            <div>
                                <div class="price"><?= formatPrice($f['price']) ?></div>
                                <small style="color:var(--muted)">per seat</small>
                            </div>
                            <div style="text-align:right">
                                <span class="avail-badge <?= $badgeClass ?>">
                                    <?= $avail ?> / <?= $total ?> seats
                                </span>
                                <div style="font-size:0.78rem;color:var(--muted);margin-top:4px">available</div>
                            </div>
                        </div>

                        <!-- Call to action -->
                        <?php if ($avail > 0): ?>
                            <div style="margin-top:14px;text-align:center">
                                <span class="btn btn-primary btn-sm">View Seats →</span>
                            </div>
                        <?php else: ?>
                            <div style="margin-top:14px;text-align:center;color:#dc2626;font-weight:700">
                                🚫 Fully Booked
                            </div>
                        <?php endif; ?>
                    </a>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>

    <!-- Info Section -->
    <div class="card" style="text-align:center">
        <h2>ℹ️ How It Works</h2>
        <div style="display:flex;gap:32px;justify-content:center;flex-wrap:wrap;margin-top:16px">
            <?php
            // PHP array of steps — arrays can hold strings, numbers, other arrays
            $steps = [
                ['1️⃣', 'Choose Flight', 'Pick from available flights above'],
                ['2️⃣', 'Select Seat',   'Click on a green seat in the map'],
                ['3️⃣', 'Enter Details', 'Provide passenger info'],
                ['4️⃣', 'Get PNR',       'Receive your unique booking code'],
            ];
            foreach ($steps as $step):
            ?>
            <div style="flex:1;min-width:130px">
                <div style="font-size:2rem"><?= $step[0] ?></div>
                <div style="font-weight:700;margin:6px 0 4px"><?= $step[1] ?></div>
                <div style="font-size:0.85rem;color:var(--muted)"><?= $step[2] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div><!-- /.container -->
</body>
</html>
