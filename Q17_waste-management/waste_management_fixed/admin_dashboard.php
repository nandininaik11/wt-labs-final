<?php
// ============================================================
// FILE: admin_dashboard.php
// PURPOSE: Admin sees ALL reports, stats, can assign authority
//          and update status of each waste report
//
// THEORY DEMONSTRATED:
//   - PHP Sessions (authentication guard)
//   - SQL SELECT with JOIN (fetch reports + authority info)
//   - SQL UPDATE (change status, assign authority)
//   - SQL aggregate functions (COUNT, GROUP BY for stats)
//   - PHP loops (while, foreach) to display data
//   - Bootstrap responsive table layout
// ============================================================

session_start();

// ---- AUTHENTICATION GUARD ----
// Check if admin is logged in via session
// If not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
    // This protects the page from unauthorized access
    // Even if someone types the URL directly, they get redirected
}

include 'db.php';

$admin_name = $_SESSION['admin_name'];
$flash_msg  = "";

// ============================================================
// HANDLE: Update report (status, priority, assign authority, notes)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];

    if ($action === 'update_report') {
        $id         = (int)$_POST['report_id'];
        // (int) casting: converts to integer — safe against SQL injection
        $status     = mysqli_real_escape_string($conn, $_POST['status']);
        $priority   = mysqli_real_escape_string($conn, $_POST['priority']);
        $assigned   = mysqli_real_escape_string($conn, $_POST['assigned_to']);
        $notes      = mysqli_real_escape_string($conn, trim($_POST['admin_notes']));

        // Set assigned_at timestamp only when assigning for first time
        $assigned_at_sql = "";
        if (!empty($assigned)) {
            $assigned_at_sql = ", assigned_at = NOW()";
            // NOW() is a MySQL function returning current date and time
        }

        // SQL UPDATE: modifies existing rows
        // SET: which columns to change
        // WHERE: which specific row — ALWAYS include WHERE in UPDATE!
        $sql = "UPDATE waste_reports
                SET status='$status', priority='$priority',
                    assigned_to='$assigned', admin_notes='$notes'
                    $assigned_at_sql
                WHERE id=$id";

        if (mysqli_query($conn, $sql)) {
            $flash_msg = "✅ Report #$id updated successfully!";
        }
    }

    if ($action === 'delete_report') {
        $id  = (int)$_POST['report_id'];
        $sql = "DELETE FROM waste_reports WHERE id=$id";
        // DELETE removes a row from the table
        // WHERE is critical — without it, ALL rows would be deleted!
        if (mysqli_query($conn, $sql)) {
            $flash_msg = "🗑️ Report #$id deleted.";
        }
    }
}

// ============================================================
// FETCH: Statistics for summary cards
// ============================================================
// COUNT(*) counts total rows — aggregate function
// We run separate queries for each count (simple approach)
function getCount($conn, $where = "") {
    // User-defined PHP function (Unit III)
    // $where is an optional parameter with default value ""
    $sql    = "SELECT COUNT(*) as cnt FROM waste_reports" . ($where ? " WHERE $where" : "");
    $result = mysqli_query($conn, $sql);
    $row    = mysqli_fetch_assoc($result);
    return $row['cnt']; // Return just the count number
}

$total_reports   = getCount($conn);
$pending_count   = getCount($conn, "status='Pending'");
$assigned_count  = getCount($conn, "status='Assigned'");
$progress_count  = getCount($conn, "status='In Progress'");
$collected_count = getCount($conn, "status='Collected'");
$urgent_count    = getCount($conn, "priority='Urgent'");

// ============================================================
// FETCH: Filters from GET parameters (for search/filter)
// ============================================================
// Filters are in URL: ?filter_status=Pending&filter_city=Pune
$f_status   = isset($_GET['filter_status'])   ? $_GET['filter_status']   : '';
$f_city     = isset($_GET['filter_city'])     ? $_GET['filter_city']     : '';
$f_waste    = isset($_GET['filter_waste'])    ? $_GET['filter_waste']    : '';
$f_priority = isset($_GET['filter_priority']) ? $_GET['filter_priority'] : '';

// Build WHERE clause dynamically based on active filters
$whereClause = "1=1"; // Always-true base condition (allows easy AND appending)
if ($f_status)   $whereClause .= " AND status='".mysqli_real_escape_string($conn, $f_status)."'";
if ($f_city)     $whereClause .= " AND city='".mysqli_real_escape_string($conn, $f_city)."'";
if ($f_waste)    $whereClause .= " AND waste_type='".mysqli_real_escape_string($conn, $f_waste)."'";
if ($f_priority) $whereClause .= " AND priority='".mysqli_real_escape_string($conn, $f_priority)."'";
// .= in PHP: string concatenation assignment — appends to existing string

// ============================================================
// FETCH: All waste reports with applied filters
// ============================================================
$sql_reports = "SELECT * FROM waste_reports WHERE $whereClause ORDER BY reported_at DESC";
// ORDER BY reported_at DESC: newest reports appear first
$reports_result = mysqli_query($conn, $sql_reports);

// ============================================================
// FETCH: All active authorities for assignment dropdown
// ============================================================
$authorities_result = mysqli_query($conn, "SELECT * FROM authorities WHERE is_active=1 ORDER BY name");

// Collect authorities into an array for easy access
// PHP array built from database query results
$authorities = [];
while ($auth = mysqli_fetch_assoc($authorities_result)) {
    $authorities[] = $auth; // Append each row to the array
}
// $authorities is now: [['id'=>1,'name'=>'Green Team',...], ...]

// Helper PHP function — returns Bootstrap badge color for status
function statusColor($s) {
    $map = ['Pending'=>'warning','Assigned'=>'info','In Progress'=>'primary','Collected'=>'success','Closed'=>'secondary'];
    return $map[$s] ?? 'secondary';
    // ?? null coalescing: if key not found, default to 'secondary'
}
function priorityColor($p) {
    $map = ['Low'=>'secondary','Normal'=>'info','High'=>'warning','Urgent'=>'danger'];
    return $map[$p] ?? 'secondary';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — SwachhCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="admin-body">

<!-- ADMIN NAVBAR -->
<nav class="navbar navbar-dark sticky-top" style="background:linear-gradient(90deg,#c0392b,#e74c3c);">
    <div class="container-fluid px-4">
        <span class="navbar-brand fw-bold fs-5">
            🔐 SwachhCity Admin Panel
        </span>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-white small">
                <i class="bi bi-person-circle me-1"></i>
                <?php echo htmlspecialchars($admin_name); ?>
            </span>
            <a href="report.php" class="btn btn-outline-light btn-sm" target="_blank">
                <i class="bi bi-eye"></i> View Public Site
            </a>
            <a href="logout.php" class="btn btn-light btn-sm fw-bold">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 py-4">

    <!-- Flash Message -->
    <?php if ($flash_msg): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $flash_msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <h4 class="fw-bold mb-4">📊 Waste Reports Dashboard</h4>

    <!-- ===== STATS CARDS ===== -->
    <!-- Bootstrap grid: 6 equal columns in a row -->
    <div class="row g-3 mb-4">
        <?php
        // PHP array of stat cards data — array of associative arrays
        $stats = [
            ['label'=>'Total Reports',   'val'=>$total_reports,   'color'=>'#3498db', 'icon'=>'bi-clipboard-data'],
            ['label'=>'Pending',         'val'=>$pending_count,   'color'=>'#f39c12', 'icon'=>'bi-hourglass-split'],
            ['label'=>'Assigned',        'val'=>$assigned_count,  'color'=>'#2980b9', 'icon'=>'bi-person-check'],
            ['label'=>'In Progress',     'val'=>$progress_count,  'color'=>'#8e44ad', 'icon'=>'bi-arrow-repeat'],
            ['label'=>'Collected',       'val'=>$collected_count, 'color'=>'#27ae60', 'icon'=>'bi-check-circle'],
            ['label'=>'Urgent',          'val'=>$urgent_count,    'color'=>'#e74c3c', 'icon'=>'bi-exclamation-triangle'],
        ];

        // foreach: iterate over each array element
        foreach ($stats as $stat):
        ?>
        <div class="col-md-2 col-6">
            <div class="stat-card" style="border-top: 4px solid <?php echo $stat['color']; ?>;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-num" style="color:<?php echo $stat['color']; ?>">
                            <?php echo $stat['val']; ?>
                        </div>
                        <div class="stat-label"><?php echo $stat['label']; ?></div>
                    </div>
                    <i class="bi <?php echo $stat['icon']; ?> fs-2 opacity-25"></i>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- ===== FILTER ROW ===== -->
    <!-- GET form: filters appear in URL — allows bookmarking filtered views -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">Status</label>
                    <select name="filter_status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <?php
                        $statuses = ['Pending','Assigned','In Progress','Collected','Closed'];
                        foreach ($statuses as $s):
                        ?>
                            <option value="<?php echo $s; ?>"
                                <?php echo ($f_status === $s) ? 'selected' : ''; ?>>
                                <!-- selected attribute: pre-selects current filter -->
                                <?php echo $s; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">City</label>
                    <select name="filter_city" class="form-select form-select-sm">
                        <option value="">All Cities</option>
                        <?php
                        foreach (['Pune','Mumbai','Nashik','Nagpur','Aurangabad','Thane','Other'] as $c):
                        ?>
                            <option value="<?php echo $c; ?>"
                                <?php echo ($f_city === $c) ? 'selected' : ''; ?>>
                                <?php echo $c; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">Waste Type</label>
                    <select name="filter_waste" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <?php
                        foreach (['Plastic','Paper','Organic','Electronic','Chemical','Medical','Construction','Mixed'] as $w):
                        ?>
                            <option value="<?php echo $w; ?>"
                                <?php echo ($f_waste === $w) ? 'selected' : ''; ?>>
                                <?php echo $w; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">Priority</label>
                    <select name="filter_priority" class="form-select form-select-sm">
                        <option value="">All Priorities</option>
                        <?php foreach (['Low','Normal','High','Urgent'] as $p): ?>
                            <option value="<?php echo $p; ?>"
                                <?php echo ($f_priority === $p) ? 'selected' : ''; ?>>
                                <?php echo $p; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-danger btn-sm flex-fill">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                    <a href="admin_dashboard.php" class="btn btn-outline-secondary btn-sm flex-fill">
                        <i class="bi bi-x"></i> Clear
                    </a>
                </div>
                <div class="col-md-2 text-end">
                    <span class="badge bg-secondary">
                        <?php echo mysqli_num_rows($reports_result); ?> reports found
                        <!-- mysqli_num_rows(): count rows in result set -->
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== REPORTS TABLE ===== -->
    <div class="card border-0 shadow">
        <div class="card-header bg-danger text-white fw-bold">
            <i class="bi bi-table me-2"></i> All Waste Reports
        </div>
        <div class="card-body p-0">
            <!-- table-responsive: adds horizontal scroll on small screens -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0 small">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Reporter</th>
                            <th>Waste</th>
                            <th>Location</th>
                            <th>City</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Assigned To</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Check if any reports were returned
                    if (mysqli_num_rows($reports_result) === 0):
                    ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                No reports found matching your filters.
                            </td>
                        </tr>
                    <?php else:
                        // while loop: fetch one row at a time until no more rows
                        // mysqli_fetch_assoc() returns false when no more rows
                        while ($row = mysqli_fetch_assoc($reports_result)):
                    ?>
                        <tr>
                            <td class="fw-bold text-danger">#<?php echo $row['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['reporter_name']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($row['reporter_phone']); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-dark"><?php echo htmlspecialchars($row['waste_type']); ?></span>
                            </td>
                            <td style="max-width:150px;">
                                <small>
                                    <?php
                                    // substr() extracts part of a string
                                    // substr(string, start, length) — show max 60 chars
                                    echo htmlspecialchars(substr($row['location'], 0, 60));
                                    if (strlen($row['location']) > 60) echo '...';
                                    ?>
                                </small>
                                <?php if ($row['landmark']): ?>
                                    <br><small class="text-muted">📍 <?php echo htmlspecialchars($row['landmark']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['city']); ?></td>
                            <td><small><?php echo $row['quantity']; ?></small></td>
                            <td>
                                <span class="badge bg-<?php echo statusColor($row['status']); ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo priorityColor($row['priority']); ?>">
                                    <?php echo $row['priority']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['assigned_to']): ?>
                                    <span class="text-success small">
                                        🚛 <?php echo htmlspecialchars($row['assigned_to']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small>
                                    <?php echo date('d M Y', strtotime($row['reported_at'])); ?>
                                </small>
                            </td>
                            <td>
                                <!-- Button to open the edit modal -->
                                <button class="btn btn-sm btn-primary"
                                        onclick="openEditModal(<?php
                                            // Pass all report data to JavaScript function
                                            // json_encode() converts PHP array to JSON string
                                            echo htmlspecialchars(json_encode($row), ENT_QUOTES);
                                        ?>)">
                                    <!-- json_encode: PHP → JSON for passing to JS -->
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                <!-- Delete form (inline) -->
                                <form method="POST" style="display:inline;"
                                      onsubmit="return confirm('Delete Report #<?php echo $row['id']; ?>? This cannot be undone.')">
                                    <!-- confirm(): JS dialog — returns true if OK clicked -->
                                    <input type="hidden" name="action" value="delete_report">
                                    <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                                    <!-- hidden inputs send data without showing on screen -->
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /container-fluid -->


<!-- ===== EDIT REPORT MODAL ===== -->
<!-- Modal: Bootstrap component that shows a popup overlay -->
<!-- data-bs-backdrop="static": clicking outside doesn't close it -->
<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-fill me-2"></i>Edit Report
                    <span id="modalReportId" class="ms-2 badge bg-light text-dark"></span>
                </h5>
                <!-- data-bs-dismiss="modal": Bootstrap closes modal on click -->
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="">
                <input type="hidden" name="action" value="update_report">
                <!-- Hidden input: sends action value without showing it -->
                <input type="hidden" name="report_id" id="modalId">

                <div class="modal-body p-4">

                    <!-- Report info display (read-only) -->
                    <div class="alert alert-light border mb-3">
                        <strong>Location:</strong> <span id="modalLocation"></span><br>
                        <strong>Waste:</strong> <span id="modalWaste"></span> |
                        <strong>Reporter:</strong> <span id="modalReporter"></span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" id="modalStatus" class="form-select">
                                <?php
                                // Generate options from PHP array
                                $statuses = ['Pending','Assigned','In Progress','Collected','Closed'];
                                foreach ($statuses as $s):
                                ?>
                                    <option value="<?php echo $s; ?>"><?php echo $s; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Priority</label>
                            <select name="priority" id="modalPriority" class="form-select">
                                <?php foreach (['Low','Normal','High','Urgent'] as $p): ?>
                                    <option value="<?php echo $p; ?>"><?php echo $p; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Assign Authority</label>
                            <select name="assigned_to" id="modalAssigned" class="form-select">
                                <option value="">-- Unassigned --</option>
                                <?php
                                // Loop through authorities array we built earlier
                                // PHP foreach on array (Unit III)
                                foreach ($authorities as $auth):
                                ?>
                                    <option value="<?php echo htmlspecialchars($auth['name']); ?>">
                                        <?php echo htmlspecialchars($auth['name']); ?>
                                        (<?php echo htmlspecialchars($auth['area']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Admin Notes / Instructions</label>
                            <textarea name="admin_notes" id="modalNotes"
                                      class="form-control" rows="3"
                                      placeholder="Instructions for the collection team..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger fw-bold">
                        <i class="bi bi-save-fill me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ============================================================
// JAVASCRIPT: Open edit modal and pre-fill with report data
//
// THEORY (Unit II):
//   - JSON: JavaScript Object Notation — data format
//   - DOM manipulation: reading and setting element properties
//   - Bootstrap Modal API: programmatic control via JS
// ============================================================

/**
 * openEditModal() — Fills modal form with report data and shows it
 * Called by: onclick="openEditModal(<?php echo json_encode($row); ?>)"
 *
 * @param {Object} report - Report data passed as JSON from PHP
 * THEORY: JSON (JavaScript Object Notation) is used to pass
 * structured data from PHP (server) to JavaScript (client).
 * json_encode() in PHP converts array → JSON string
 * JavaScript receives it as an object automatically.
 */
function openEditModal(report) {
    // JSON (Unit I): key-value format — report.status, report.id, etc.
    // DOM manipulation: setting values of form elements

    // Set hidden input values (what gets submitted to PHP)
    document.getElementById('modalId').value      = report.id;

    // Set display fields (read-only info)
    document.getElementById('modalReportId').textContent = '#' + report.id;
    document.getElementById('modalLocation').textContent  = report.location;
    document.getElementById('modalWaste').textContent     = report.waste_type;
    document.getElementById('modalReporter').textContent  = report.reporter_name;

    // Pre-select current values in dropdowns
    // .value setter on <select>: sets the selected option
    document.getElementById('modalStatus').value   = report.status;
    document.getElementById('modalPriority').value = report.priority;
    document.getElementById('modalAssigned').value = report.assigned_to || '';
    // || '' — if assigned_to is null, use empty string

    // Pre-fill textarea
    document.getElementById('modalNotes').value = report.admin_notes || '';

    // Show the Bootstrap modal programmatically
    // bootstrap.Modal.getOrCreateInstance() gets/creates a Modal object
    // .show() opens the modal overlay
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal'));
    modal.show();
}
</script>

</body>
</html>
