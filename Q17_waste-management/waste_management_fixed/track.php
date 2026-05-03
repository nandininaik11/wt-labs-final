<?php
// ============================================================
// FILE: track.php
// PURPOSE: Citizens can check the status of their waste report
//          by entering their Report ID or phone number
// THEORY: GET form + PHP SELECT query with WHERE clause
// ============================================================

include 'db.php';

$report    = null; // Will hold the fetched report row
$searched  = false; // Flag to know if search was attempted
$notFound  = false;

// Handle search — form uses GET (data visible in URL, bookmarkable)
// GET is appropriate for searches: /track.php?report_id=5
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['report_id'])) {
    $searched  = true;
    $report_id = (int)$_GET['report_id'];
    // (int): type casting — forces value to integer
    // "5abc" → 5, "abc" → 0 — prevents SQL injection for numeric values

    if ($report_id > 0) {
        $sql    = "SELECT * FROM waste_reports WHERE id = $report_id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            // mysqli_num_rows() counts rows returned by SELECT
            $report = mysqli_fetch_assoc($result);
            // mysqli_fetch_assoc() returns one row as an associative array
            // $report['status'], $report['location'], etc.
        } else {
            $notFound = true;
        }
    }
}

// PHP function to map status to Bootstrap badge color
// THEORY: User-defined functions in PHP (Unit III)
function statusBadge($status) {
    // PHP match expression (PHP 8): cleaner than switch
    $colors = [
        'Pending'     => 'warning',   // Yellow
        'Assigned'    => 'info',      // Blue
        'In Progress' => 'primary',   // Dark blue
        'Collected'   => 'success',   // Green
        'Closed'      => 'secondary', // Grey
    ];
    // array_key_exists() checks if key exists in array
    return array_key_exists($status, $colors) ? $colors[$status] : 'secondary';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Report — SwachhCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-dark sticky-top" id="topNav">
    <div class="container">
        <a class="navbar-brand fw-bold" href="report.php">♻️ SwachhCity Portal</a>
        <div class="ms-auto d-flex gap-2">
            <a href="report.php" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> New Report
            </a>
            <a href="admin_login.php" class="btn btn-warning btn-sm fw-bold">
                <i class="bi bi-shield-lock-fill"></i> Admin
            </a>
        </div>
    </div>
</nav>

<div class="hero-banner py-4">
    <div class="container text-center py-3">
        <div class="hero-emoji">🔍</div>
        <h2 class="fw-bold text-white">Track Your Waste Report</h2>
        <p class="text-white opacity-75">Enter your Report ID to check current status</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- Search Form using GET method -->
            <!-- GET: data sent in URL — suitable for search forms (bookmarkable) -->
            <div class="card shadow border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <form action="" method="GET" class="d-flex gap-2">
                        <!-- method="GET": data appears in URL as ?report_id=5 -->
                        <input type="number" name="report_id"
                               class="form-control form-control-lg"
                               placeholder="Enter Report ID (e.g. 3)"
                               min="1" required
                               value="<?php echo isset($_GET['report_id']) ? (int)$_GET['report_id'] : ''; ?>">
                               <!-- Pre-fill with previous search value -->
                        <button type="submit" class="btn btn-submit btn-lg px-4">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Not Found Message -->
            <?php if ($searched && $notFound): ?>
                <!-- PHP && operator: both conditions must be true -->
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-4"></i>
                    <strong>No report found</strong> with ID #<?php echo (int)$_GET['report_id']; ?><br>
                    <small>Please check the ID and try again.</small>
                </div>
            <?php endif; ?>

            <!-- Report Found: Show Details -->
            <?php if ($report): ?>
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header-custom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-clipboard-check me-2"></i>
                                Report #<?php echo $report['id']; ?>
                            </h5>
                            <!-- Output status badge with dynamic color -->
                            <span class="badge bg-<?php echo statusBadge($report['status']); ?> px-3 py-2 fs-6">
                                <?php echo htmlspecialchars($report['status']); ?>
                                <!-- htmlspecialchars(): converts < > & " to HTML entities
                                     Prevents XSS (Cross-Site Scripting) attacks -->
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">

                        <!-- Status Timeline -->
                        <div class="status-timeline mb-4">
                            <?php
                            // PHP array of all possible statuses
                            $allStatuses = ['Pending','Assigned','In Progress','Collected','Closed'];
                            $currentIdx  = array_search($report['status'], $allStatuses);
                            // array_search() returns the key of a value in an array
                            // If status is 'Assigned', currentIdx = 1

                            foreach ($allStatuses as $i => $s):
                                // Determine if this step is: done, current, or future
                                $isDone    = $i < $currentIdx;
                                $isCurrent = $i === $currentIdx;
                            ?>
                                <div class="timeline-step <?php echo $isDone ? 'done' : ($isCurrent ? 'current' : 'future'); ?>">
                                    <div class="timeline-dot">
                                        <?php echo $isDone ? '✓' : ($isCurrent ? '●' : '○'); ?>
                                    </div>
                                    <small><?php echo $s; ?></small>
                                </div>
                                <?php if ($i < count($allStatuses)-1): ?>
                                    <div class="timeline-line <?php echo $isDone ? 'done' : ''; ?>"></div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- Report Details Table -->
                        <table class="table table-bordered table-sm">
                            <!-- Bootstrap table classes for styling -->
                            <tbody>
                                <tr>
                                    <th class="table-light" width="35%">Waste Type</th>
                                    <td><?php echo htmlspecialchars($report['waste_type']); ?></td>
                                </tr>
                                <tr>
                                    <th class="table-light">Location</th>
                                    <td><?php echo htmlspecialchars($report['location']); ?></td>
                                </tr>
                                <?php if ($report['landmark']): ?>
                                <tr>
                                    <th class="table-light">Landmark</th>
                                    <td><?php echo htmlspecialchars($report['landmark']); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th class="table-light">City</th>
                                    <td><?php echo htmlspecialchars($report['city']); ?></td>
                                </tr>
                                <tr>
                                    <th class="table-light">Quantity</th>
                                    <td><?php echo $report['quantity']; ?></td>
                                </tr>
                                <tr>
                                    <th class="table-light">Priority</th>
                                    <td>
                                        <?php
                                        $pClr = ['Low'=>'secondary','Normal'=>'info','High'=>'warning','Urgent'=>'danger'];
                                        echo '<span class="badge bg-'.($pClr[$report['priority']] ?? 'secondary').'">'.$report['priority'].'</span>';
                                        ?>
                                    </td>
                                </tr>
                                <?php if ($report['assigned_to']): ?>
                                <tr>
                                    <th class="table-light">Assigned To</th>
                                    <td class="text-success fw-bold">
                                        🚛 <?php echo htmlspecialchars($report['assigned_to']); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($report['admin_notes']): ?>
                                <tr>
                                    <th class="table-light">Admin Notes</th>
                                    <td><?php echo htmlspecialchars($report['admin_notes']); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th class="table-light">Reported On</th>
                                    <td>
                                        <?php
                                        // date() formats timestamp, strtotime() converts string to Unix timestamp
                                        echo date('d M Y, h:i A', strtotime($report['reported_at']));
                                        // Output: "15 Jan 2024, 10:30 AM"
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <a href="report.php" class="btn btn-outline-success w-100">
                            <i class="bi bi-plus-circle me-1"></i> Report Another
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<footer class="site-footer">
    <div class="container text-center">
        <p class="mb-0">🌿 SwachhCity Waste Management Portal | Web Technology Lab Q17</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
