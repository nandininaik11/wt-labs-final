<?php
// ============================================================
// admin.php — Admin Panel: All Bookings Overview
// Shows all bookings, seat stats per flight
// Concepts: SQL JOINs, GROUP BY, PHP string functions
// ============================================================
require_once 'includes/db.php';
require_once 'includes/helpers.php';

// ── Fetch all bookings with flight and seat details ──
// Three-table JOIN: bookings → flights → seats
$bookings = $pdo->query("
    SELECT
        b.id, b.pnr, b.passenger, b.email, b.phone, b.booked_at,
        f.flight_no, f.origin, f.destination, f.depart_time, f.price,
        s.seat_no, s.class
    FROM bookings b
    JOIN flights f ON b.flight_id = f.id
    JOIN seats   s ON b.seat_id   = s.id
    ORDER BY b.booked_at DESC    -- newest booking first
")->fetchAll();

// ── Fetch per-flight stats ──
// GROUP BY: groups rows by flight, COUNT/SUM applies to each group
$flightStats = $pdo->query("
    SELECT
        f.flight_no,
        f.origin, f.destination,
        f.total_seats,
        SUM(s.is_booked)         AS booked,
        SUM(s.is_booked=0)       AS available,
        COUNT(DISTINCT b.id)     AS bookings_count,
        SUM(f.price * s.is_booked) AS revenue
    FROM flights f
    LEFT JOIN seats    s ON f.id = s.flight_id   -- LEFT JOIN: include flights with 0 bookings
    LEFT JOIN bookings b ON f.id = b.flight_id
    GROUP BY f.id, f.flight_no, f.origin, f.destination, f.total_seats
    ORDER BY f.depart_time
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — SkyBook</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav>
    <div class="brand"><span>✈️</span> SkyBook Admin Panel</div>
    <div>
        <a href="index.php">User View</a>
    </div>
</nav>

<div class="container" style="max-width:1100px">
    <div class="hero" style="padding:24px 28px;margin-bottom:24px">
        <h1 style="font-size:1.6rem">🛡️ Admin Dashboard</h1>
        <p>Overview of all flights, seats, and bookings</p>
    </div>

    <!-- Flight Stats Cards -->
    <div class="card">
        <h2>✈️ Flight Overview</h2>
        <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Flight</th><th>Route</th><th>Departure</th>
                    <th>Total</th><th>Booked</th><th>Available</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flightStats as $fs): ?>
                <tr>
                    <td><strong><?= clean($fs['flight_no']) ?></strong></td>
                    <td><?= clean($fs['origin']) ?> → <?= clean($fs['destination']) ?></td>
                    <td><?= date('d M, h:i A', strtotime($fs['depart_time'])) ?></td>
                    <td><?= $fs['total_seats'] ?></td>
                    <!-- String concatenation using . in PHP: "text" . $var . "text" -->
                    <td style="color:var(--danger);font-weight:700"><?= $fs['booked'] ?></td>
                    <td style="color:var(--success);font-weight:700"><?= $fs['available'] ?></td>
                    <td style="color:var(--sky);font-weight:700"><?= formatPrice($fs['revenue'] ?? 0) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

    <!-- All Bookings Table -->
    <div class="card">
        <h2>📋 All Bookings (<?= count($bookings) ?>)</h2>
        <?php if (empty($bookings)): ?>
            <div class="alert alert-info">No bookings yet.</div>
        <?php else: ?>
        <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>PNR</th><th>Passenger</th><th>Email</th>
                    <th>Flight</th><th>Seat</th><th>Class</th>
                    <th>Amount</th><th>Booked At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr>
                    <!-- PHP monospace style for PNR code -->
                    <td style="font-family:monospace;font-weight:800;font-size:1rem;letter-spacing:2px">
                        <?= clean($b['pnr']) ?>
                    </td>
                    <td><?= clean($b['passenger']) ?></td>
                    <td style="font-size:0.85rem"><?= clean($b['email']) ?></td>
                    <td><?= clean($b['flight_no']) ?>
                        <div style="font-size:0.78rem;color:var(--muted)"><?= clean($b['origin']) ?>→<?= clean($b['destination']) ?></div>
                    </td>
                    <td style="font-weight:800;font-size:1.05rem"><?= clean($b['seat_no']) ?></td>
                    <td>
                        <span style="color:<?= $b['class']==='Business' ? 'var(--gold)':'var(--success)' ?>;font-weight:700">
                            <?= $b['class'] ?>
                        </span>
                    </td>
                    <td style="color:var(--sky);font-weight:700"><?= formatPrice($b['price']) ?></td>
                    <td style="font-size:0.82rem"><?= date('d M Y h:i A', strtotime($b['booked_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
