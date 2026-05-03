<?php
// ============================================================
// FILE: report.php
// PURPOSE: Public form for citizens to report waste locations
//          No login required — anyone can submit a report
//
// THEORY DEMONSTRATED:
//   Unit I  — HTML5 form elements, Bootstrap responsive layout
//   Unit II — JavaScript DOM validation, events, FileReader API
//   Unit III — PHP form handling ($_POST), file uploads ($_FILES),
//              sessions, MySQLi INSERT, server-side validation
// ============================================================

session_start();
// session_start() MUST be the very first thing — before any HTML output
// It initializes the PHP session so we can use $_SESSION[]
// Sessions store data on the SERVER linked to a browser via a cookie

include 'db.php'; // Get database connection ($conn)

$success = "";
$error   = "";

// ============================================================
// HANDLE FORM SUBMISSION
// ============================================================
// $_SERVER['REQUEST_METHOD'] tells us HOW the page was accessed:
//   'GET'  = user just visited the URL (no form submitted)
//   'POST' = user submitted the form

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // -----------------------------------------------------------
    // STEP 1: Collect and sanitize inputs from $_POST
    // -----------------------------------------------------------
    // $_POST['field_name'] reads data sent via form method="POST"
    // trim() removes leading/trailing whitespace
    // mysqli_real_escape_string() escapes special SQL characters
    // to prevent SQL Injection attacks (e.g., ' " \ → escaped)

    $name     = mysqli_real_escape_string($conn, trim($_POST['reporter_name']));
    $phone    = mysqli_real_escape_string($conn, trim($_POST['reporter_phone']));
    $email    = mysqli_real_escape_string($conn, trim($_POST['reporter_email'] ?? ''));
    // ?? is the null coalescing operator: if left is null, use right side
    $waste    = mysqli_real_escape_string($conn, trim($_POST['waste_type']));
    $location = mysqli_real_escape_string($conn, trim($_POST['location']));
    $landmark = mysqli_real_escape_string($conn, trim($_POST['landmark'] ?? ''));
    $city     = mysqli_real_escape_string($conn, trim($_POST['city']));
    $quantity = mysqli_real_escape_string($conn, trim($_POST['quantity']));
    $desc     = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));

    // -----------------------------------------------------------
    // STEP 2: Server-side validation
    // -----------------------------------------------------------
    // IMPORTANT: Client-side (JS) validation can be bypassed.
    // ALWAYS validate on the server for security!

    $errors = []; // PHP array to collect multiple error messages

    if (empty($name))            $errors[] = "Your name is required.";
    if (empty($phone))           $errors[] = "Phone number is required.";
    if (strlen($phone) < 10)     $errors[] = "Phone must be at least 10 digits.";
    // strlen() = count characters in string
    if (empty($waste))           $errors[] = "Waste type is required.";
    if (empty($location))        $errors[] = "Location is required.";
    if (empty($city))            $errors[] = "City is required.";

    // Validate email format only if provided (it's optional)
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // filter_var() with FILTER_VALIDATE_EMAIL checks email format
        $errors[] = "Invalid email address format.";
    }

    // -----------------------------------------------------------
    // STEP 3: Save to database if no errors
    // -----------------------------------------------------------
    if (empty($errors)) {

        // SQL INSERT query — adds a new row to waste_reports table
        // All string values are wrapped in single quotes in SQL
        $sql = "INSERT INTO waste_reports
                    (reporter_name, reporter_phone, reporter_email,
                     waste_type, location, landmark, city, quantity, description)
                VALUES
                    ('$name', '$phone', '$email',
                     '$waste', '$location', '$landmark', '$city', '$quantity', '$desc')";

        if (mysqli_query($conn, $sql)) {
            // mysqli_query() returns TRUE on successful INSERT/UPDATE/DELETE
            
            $report_id = mysqli_insert_id($conn);
            // mysqli_insert_id() returns the AUTO_INCREMENT id of the newly inserted row
            
            // Store success message in SESSION (survives a redirect)
            $_SESSION['flash_success'] = "✅ Report #$report_id submitted! Authorities notified within 24 hours.";

            // POST-Redirect-GET Pattern:
            // After a POST form submit, REDIRECT instead of showing the page directly.
            // This prevents "Form resubmission" warnings when user refreshes.
            header("Location: report.php?done=1");
            exit();
        } else {
            $error = "Database error: " . mysqli_error($conn);
            // mysqli_error() returns MySQL's error message for debugging
        }

    } else {
        // Join all error strings with HTML line break
        // implode(separator, array) = joins array elements into one string
        $error = implode("<br>• ", $errors);
        $error = "• " . $error; // Add bullet to first item too
    }
}

// Read and clear session success flash message
if (isset($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']); // Delete from session after reading
}
?>
<!DOCTYPE html>
<!-- DOCTYPE: tells browser this is an HTML5 document -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- charset UTF-8: supports all international characters -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- viewport: makes page responsive on mobile screens -->
    <title>♻️ Report Waste — SwachhCity</title>

    <!-- Bootstrap 5 CSS from CDN (Content Delivery Network) -->
    <!-- Bootstrap: CSS framework with pre-built responsive components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons: icon font library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ===== NAVBAR ===== -->
<!-- Bootstrap navbar: navbar-expand-lg = collapses to hamburger on small screens -->
<nav class="navbar navbar-dark sticky-top" id="topNav">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="report.php">
            ♻️ SwachhCity Portal
        </a>
        <!-- ms-auto: Bootstrap utility — margin-start:auto pushes links to right -->
        <div class="ms-auto d-flex gap-2">
            <a href="track.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-search"></i> Track My Report
            </a>
            <a href="admin_login.php" class="btn btn-warning btn-sm fw-bold">
                <i class="bi bi-shield-lock-fill"></i> Admin
            </a>
        </div>
    </div>
</nav>

<!-- ===== HERO / BANNER ===== -->
<div class="hero-banner">
    <div class="container text-center py-5">
        <div class="hero-emoji">♻️</div>
        <h1 class="display-5 fw-bold text-white mb-3">Report Waste in Your Area</h1>
        <p class="lead text-white opacity-75 mb-4">
            Spot waste? Report it! Our teams collect it within <strong>24–48 hours</strong>.
        </p>
        <!-- How it works: 3-step process -->
        <div class="row justify-content-center g-3 mt-2">
            <?php
            // PHP array of steps — demonstrates PHP Arrays (Unit III)
            // Associative array: key => value pairs
            $steps = [
                ['icon'=>'📍', 'text'=>'Report Location'],
                ['icon'=>'🚛', 'text'=>'Authority Dispatched'],
                ['icon'=>'✅', 'text'=>'Waste Collected'],
            ];
            // foreach loop: iterates over each element in the array
            foreach ($steps as $i => $step):
            ?>
                <div class="col-auto">
                    <div class="step-pill">
                        <span class="step-num"><?php echo $i+1; ?></span>
                        <!-- $i = current index (0,1,2), +1 to show 1,2,3 -->
                        <?php echo $step['icon']; ?> <?php echo $step['text']; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ===== MAIN FORM AREA ===== -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <!-- Success Alert -->
            <?php if ($success): ?>
                <!-- PHP if: outputs this HTML block only when $success is not empty -->
                <div class="alert alert-success alert-dismissible fade show shadow mb-4">
                    <!-- alert-dismissible fade show: Bootstrap animated closable alert -->
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <strong><?php echo $success; ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <!-- data-bs-dismiss="alert": Bootstrap JS auto-handles close button -->
                </div>
            <?php endif; ?>

            <!-- Error Alert -->
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <strong>Please fix these errors:</strong><br>
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- ===== THE REPORT FORM ===== -->
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header-custom">
                    <h4 class="mb-1 fw-bold">
                        <i class="bi bi-file-earmark-text-fill me-2"></i>Waste Collection Request
                    </h4>
                    <small class="opacity-75">Fields marked * are required</small>
                </div>

                <!--
                    FORM ATTRIBUTES (important theory):
                    action=""           = submit to same page (self-processing)
                    method="POST"       = sends data in HTTP request body (hidden)
                    enctype="multipart/form-data" = REQUIRED when form has file upload
                    Without enctype, $_FILES will always be empty!
                    onsubmit="return validateForm()" = run JS before submitting
                -->
                <form action="" method="POST" id="wasteForm"
                      onsubmit="return validateForm()">
                    <div class="card-body p-4">

                        <!-- SECTION 1: Reporter Info -->
                        <div class="section-block">
                            <div class="section-header">
                                <i class="bi bi-person-circle text-primary"></i>
                                Your Information
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Full Name *</label>
                                    <input type="text" name="reporter_name"
                                           class="form-control" id="inpName"
                                           placeholder="e.g. Rahul Sharma"
                                           required minlength="2" maxlength="100">
                                    <!-- minlength/maxlength: HTML5 built-in validation -->
                                    <!-- required: browser won't submit if empty -->
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Phone Number *</label>
                                    <input type="tel" name="reporter_phone"
                                           class="form-control" id="inpPhone"
                                           placeholder="10-digit mobile"
                                           pattern="[0-9]{10,15}" required>
                                    <!-- type="tel": shows numeric keyboard on mobile -->
                                    <!-- pattern="[0-9]{10,15}": HTML5 regex validation -->
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">
                                        Email <span class="text-muted fw-normal">(optional — for status updates)</span>
                                    </label>
                                    <input type="email" name="reporter_email"
                                           class="form-control"
                                           placeholder="you@example.com">
                                    <!-- type="email": browser validates email format -->
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Waste Details -->
                        <div class="section-block">
                            <div class="section-header">
                                <i class="bi bi-trash3-fill text-danger"></i>
                                Waste Details
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Type of Waste *</label>
                                    <!-- SELECT dropdown — maps to $_POST['waste_type'] -->
                                    <select name="waste_type" class="form-select" required
                                            id="wasteTypeSelect" onchange="updateWasteIcon()">
                                        <option value="" disabled selected>-- Select Type --</option>
                                        <option value="Plastic">🛍️ Plastic</option>
                                        <option value="Paper">📦 Paper / Cardboard</option>
                                        <option value="Organic">🌿 Organic / Food Waste</option>
                                        <option value="Electronic">🔌 Electronic (E-Waste)</option>
                                        <option value="Chemical">⚗️ Chemical / Hazardous</option>
                                        <option value="Medical">🏥 Medical / Biomedical</option>
                                        <option value="Construction">🏗️ Construction Debris</option>
                                        <option value="Mixed">♻️ Mixed / Multiple Types</option>
                                    </select>
                                    <!-- Dynamic icon display -->
                                    <div id="wasteIconDisplay" class="mt-2 fs-2" style="display:none;"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Estimated Quantity *</label>
                                    <select name="quantity" class="form-select" required>
                                        <option value="" disabled selected>-- Select Quantity --</option>
                                        <option value="Small">🟡 Small — 1 to 2 bags</option>
                                        <option value="Medium">🟠 Medium — Dustbin size</option>
                                        <option value="Large">🔴 Large — Pick-up truck needed</option>
                                        <option value="Very Large">🆘 Very Large — Multiple trucks</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Description <span class="text-muted fw-normal">(optional)</span>
                                    </label>
                                    <!-- TEXTAREA: multi-line text input -->
                                    <!-- rows="3": visible height of textarea -->
                                    <textarea name="description" class="form-control" rows="3"
                                              placeholder="Describe the waste — how long it's been there, smell, hazard level..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Location -->
                        <div class="section-block">
                            <div class="section-header">
                                <i class="bi bi-geo-alt-fill text-success"></i>
                                Waste Location
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Exact Address / Location *</label>
                                    <input type="text" name="location"
                                           class="form-control" id="inpLocation"
                                           placeholder="e.g. Near Bus Stop, MG Road, Shivaji Nagar"
                                           required maxlength="255">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Nearby Landmark <span class="text-muted fw-normal">(optional)</span>
                                    </label>
                                    <input type="text" name="landmark"
                                           class="form-control"
                                           placeholder="e.g. Opposite SBI Bank">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">City / Town *</label>
                                    <select name="city" class="form-select" required>
                                        <option value="" disabled selected>-- Select City --</option>
                                        <option value="Pune">Pune</option>
                                        <option value="Mumbai">Mumbai</option>
                                        <option value="Nashik">Nashik</option>
                                        <option value="Nagpur">Nagpur</option>
                                        <option value="Aurangabad">Aurangabad</option>
                                        <option value="Thane">Thane</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Declaration checkbox -->
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input"
                                   id="chkDeclare" required>
                            <!-- required on checkbox: form won't submit if unchecked -->
                            <label class="form-check-label" for="chkDeclare">
                                I confirm this report is genuine and the location is accurate.
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <!-- d-grid: Bootstrap — makes button full width -->
                            <button type="submit" class="btn btn-submit btn-lg" id="submitBtn">
                                <i class="bi bi-send-fill me-2"></i>Submit Waste Report
                            </button>
                        </div>

                    </div>
                </form>
            </div><!-- /card -->

            <!-- Info strip -->
            <div class="row mt-4 g-3 text-center">
                <div class="col-md-4">
                    <div class="info-chip">🚀 Reports processed 24/7</div>
                </div>
                <div class="col-md-4">
                    <div class="info-chip">📲 SMS alert on assignment</div>
                </div>
                <div class="col-md-4">
                    <div class="info-chip">✅ Collected in 24–48 hrs</div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="site-footer">
    <div class="container text-center">
        <p class="mb-1 fw-semibold">🌿 SwachhCity Waste Management Portal</p>
        <p class="mb-0 small opacity-60">
            Built with PHP + MySQL | Web Technology Lab Q17 |
            <a href="admin_login.php" class="text-warning">Admin Panel →</a>
        </p>
    </div>
</footer>

<!-- Bootstrap JS (required for dropdowns, alerts, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ============================================================
// JAVASCRIPT — Client-side validation & DOM manipulation
// THEORY (Unit II):
//   - JavaScript runs in the BROWSER (client-side)
//   - Can validate and give instant feedback WITHOUT server
//   - But server-side PHP validation is still required for security
//   - DOM manipulation: JS can read/change HTML elements dynamically
// ============================================================

/**
 * validateForm()
 * Called by: onsubmit="return validateForm()" on <form>
 * Returns false → cancels form submission
 * Returns true  → form submits normally to PHP
 *
 * THEORY: return false from onsubmit prevents the default
 * form submit action (the HTTP POST request is never sent)
 */
function validateForm() {
    // document.getElementById() — gets reference to an HTML element by its id
    // This is Level 1 DOM access (DOM Levels — Unit II)
    const phone    = document.getElementById('inpPhone');
    const name     = document.getElementById('inpName');
    const location = document.getElementById('inpLocation');

    // Validate name — .value reads current value of input
    if (name.value.trim().length < 2) {
        // .trim() removes whitespace (JS String method)
        showError(name, 'Please enter your full name (min 2 characters).');
        return false;
    }

    // Validate phone — only digits, min 10
    const phoneVal = phone.value.trim();
    if (!/^[0-9]{10,15}$/.test(phoneVal)) {
        // RegExp test(): checks if string matches the pattern
        // /^[0-9]{10,15}$/ means: start, 10-15 digits only, end
        showError(phone, 'Enter a valid 10-digit phone number (digits only).');
        return false;
    }

    // Validate location
    if (location.value.trim().length < 5) {
        showError(location, 'Please enter a detailed location (min 5 characters).');
        return false;
    }

    // Show loading state on button to prevent double submission
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
    // spinner-border: Bootstrap CSS loading spinner class
    btn.disabled = true;
    // disabled = true: greys out button, prevents clicks

    return true; // Let the form submit to PHP
}

/**
 * showError() — Highlights an input field with error styling
 * Demonstrates DOM manipulation (Unit II)
 *
 * @param {HTMLElement} el  - The input element
 * @param {string}      msg - Error message to show
 */
function showError(el, msg) {
    el.classList.add('is-invalid');
    // classList.add() — adds a CSS class to element
    // is-invalid: Bootstrap class that adds red border to input

    // Create a new DOM element for the error message
    // createElement(): creates a new <div> node in memory (Unit II — DOM manipulation)
    let errDiv = el.parentElement.querySelector('.invalid-feedback');
    if (!errDiv) {
        errDiv = document.createElement('div');
        errDiv.className = 'invalid-feedback'; // Bootstrap error text style
        el.parentElement.appendChild(errDiv);
        // appendChild(): inserts the new div as last child of parent
    }
    errDiv.textContent = msg; // .textContent sets the text inside the div

    el.focus(); // Move keyboard cursor to this input
    alert(msg); // Also show browser alert for clarity
}

/**
 * updateWasteIcon()
 * Shows a relevant emoji when user selects waste type
 * Demonstrates: DOM events, JS Objects, conditional logic (Unit II)
 */
function updateWasteIcon() {
    const select = document.getElementById('wasteTypeSelect');
    const display = document.getElementById('wasteIconDisplay');

    // JavaScript Object: key-value pairs — like a dictionary
    // Accessing: wasteIcons['Plastic'] → '🛍️'
    const wasteIcons = {
        'Plastic':      '🛍️',
        'Paper':        '📦',
        'Organic':      '🌿',
        'Electronic':   '🔌',
        'Chemical':     '⚗️',
        'Medical':      '🏥',
        'Construction': '🏗️',
        'Mixed':        '♻️'
    };

    const selected = select.value; // Read selected option value
    if (wasteIcons[selected]) {
        // Bracket notation to access object property dynamically
        display.textContent = wasteIcons[selected];
        display.style.display = 'block';
        // DOM manipulation: changing CSS display property via JavaScript
    } else {
        display.style.display = 'none';
    }
}

// DOMContentLoaded — fires when the HTML is fully parsed
// Ensures we don't try to access elements before they exist
document.addEventListener('DOMContentLoaded', function () {

    // Remove is-invalid class when user starts typing (clear error state)
    // querySelectorAll() returns ALL elements matching CSS selector
    document.querySelectorAll('.form-control, .form-select').forEach(function(el) {
        // forEach() loops over each element in the NodeList
        el.addEventListener('input', function () {
            // 'input' event fires whenever the value changes
            this.classList.remove('is-invalid');
            // 'this' refers to the element that triggered the event
        });
    });

    // Scroll effect on navbar
    window.addEventListener('scroll', function () {
        const nav = document.getElementById('topNav');
        // window.scrollY = pixels scrolled from top
        if (window.scrollY > 60) {
            nav.style.boxShadow = '0 4px 20px rgba(0,0,0,0.3)';
        } else {
            nav.style.boxShadow = 'none';
        }
    });
});
</script>

</body>
</html>
