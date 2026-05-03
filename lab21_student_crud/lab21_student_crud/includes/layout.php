<?php
/* ============================================================
   includes/layout.php
   Shared HTML header, navbar, sidebar, and footer
   THEORY (Unit I): HTML5 document structure, Bootstrap 5 layout
   ============================================================ */

/**
 * pageHeader($title, $activePage)
 * Outputs everything from <!DOCTYPE html> through to <main>
 *
 * @param string $title      Browser tab title
 * @param string $activePage Which nav item to highlight
 */
function pageHeader(string $title, string $activePage = ''): void {
    ?>
<!DOCTYPE html>
<!-- HTML5 Document Declaration (Unit I Syllabus) -->
<html lang="en">
<head>
    <!-- UTF-8: supports all characters including Indian language fonts -->
    <meta charset="UTF-8">

    <!-- Viewport: makes page responsive on mobile phones -->
    <!-- width=device-width = use actual screen width -->
    <!-- initial-scale=1.0 = don't zoom in/out by default -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title) ?> – Student Records</title>

    <!-- Bootstrap 5 CSS (CDN) – Unit I: CSS Framework -->
    <!-- Bootstrap gives us responsive grid, buttons, forms, tables etc. -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons (icon font library) -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Google Fonts: Inter for clean modern look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        /* ── CSS Custom Properties (Variables) ──────────────────
           Define colors once, use everywhere
           If we want to change the theme, only edit these values */
        :root {
            --primary:       #2563eb;   /* Blue – main brand color */
            --primary-dark:  #1d4ed8;   /* Darker blue – hover state */
            --success:       #16a34a;   /* Green – success messages */
            --danger:        #dc2626;   /* Red – delete/error */
            --warning:       #d97706;   /* Orange – warnings */
            --sidebar-width: 240px;     /* Fixed sidebar width */
            --topbar-height: 60px;      /* Fixed top navbar height */
            --bg:            #f1f5f9;   /* Light gray page background */
            --surface:       #ffffff;   /* White card background */
            --border:        #e2e8f0;   /* Light border color */
            --text:          #1e293b;   /* Dark text */
            --text-muted:    #64748b;   /* Gray muted text */
        }

        /* ── Base styles ────────────────────────────────────────
           * = applies to ALL elements
           box-sizing: border-box = padding/border included in width */
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
        }

        /* ── TOP NAVBAR ─────────────────────────────────────────
           Fixed to top of page so it stays visible when scrolling */
        .topbar {
            height: var(--topbar-height);
            background: var(--primary);
            position: fixed;            /* Fixed = stays in place on scroll */
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;             /* Always on top of other elements */
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,.2);
        }
        .topbar .brand {
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar .subtitle {
            color: rgba(255,255,255,.65);
            font-size: .8rem;
            margin-left: auto;       /* Push to right side */
        }

        /* ── SIDEBAR ────────────────────────────────────────────
           Fixed left column navigation */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--surface);
            border-right: 1px solid var(--border);
            position: fixed;          /* Fixed = stays in place */
            top: var(--topbar-height);
            left: 0;
            bottom: 0;
            overflow-y: auto;         /* Scroll if too many items */
            padding: 1.5rem 0.75rem;
            z-index: 900;
            transition: transform .3s ease; /* Animate mobile toggle */
        }

        /* Sidebar navigation items */
        .sidebar .nav-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 0 0.75rem;
            margin-bottom: .5rem;
            margin-top: 1rem;
        }
        .sidebar .nav-link {
            color: var(--text);
            border-radius: 8px;
            padding: .6rem .75rem;
            font-size: .9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all .2s;
        }
        .sidebar .nav-link:hover {
            background: #eff6ff;         /* Light blue on hover */
            color: var(--primary);
        }
        .sidebar .nav-link.active {
            background: var(--primary);  /* Blue background when active */
            color: white;
            font-weight: 600;
        }
        .sidebar .nav-link i {
            font-size: 1.1rem;
            width: 20px;               /* Fixed width keeps text aligned */
        }

        /* ── MAIN CONTENT AREA ──────────────────────────────────
           Offset from the fixed sidebar and topbar */
        .main-wrap {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            min-height: calc(100vh - var(--topbar-height));
            padding: 1.75rem;
        }

        /* ── CARDS ──────────────────────────────────────────────
           White rounded boxes for content sections */
        .card {
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 1px 8px rgba(0,0,0,.06);
        }
        .card-header {
            background: white;
            border-bottom: 1px solid var(--border);
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.25rem;
            font-weight: 600;
            font-size: .95rem;
        }

        /* ── STAT CARDS ─────────────────────────────────────────
           Dashboard summary boxes at the top */
        .stat-card {
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid var(--border);
            background: white;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .stat-num  { font-size: 1.8rem; font-weight: 700; line-height: 1; }
        .stat-lbl  { font-size: .8rem; color: var(--text-muted); margin-top: 3px; }

        /* ── TABLE STYLES ───────────────────────────────────────
           Custom styled table for student records */
        .student-table { border-collapse: collapse; width: 100%; }
        .student-table thead th {
            background: #1e40af;           /* Dark blue header */
            color: white;
            padding: .85rem 1rem;
            font-size: .82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            border: none;
            white-space: nowrap;
        }
        .student-table thead th:first-child { border-radius: 8px 0 0 0; }
        .student-table thead th:last-child  { border-radius: 0 8px 0 0; }
        .student-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .15s;   /* Smooth hover effect */
        }
        .student-table tbody tr:hover { background: #f8faff; }
        .student-table tbody td {
            padding: .85rem 1rem;
            font-size: .88rem;
            vertical-align: middle;
        }

        /* ── BADGES ─────────────────────────────────────────────
           Colored pill labels for department, year, CGPA */
        .dept-badge {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 500;
            white-space: nowrap;
        }
        .cgpa-badge {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 700;
        }
        .cgpa-high   { background: #dcfce7; color: #16a34a; }  /* Green: ≥ 8 */
        .cgpa-medium { background: #fef3c7; color: #d97706; }  /* Yellow: 6–8 */
        .cgpa-low    { background: #fee2e2; color: #dc2626; }  /* Red: < 6 */

        /* ── ACTION BUTTONS ─────────────────────────────────────
           Edit (blue) and Delete (red) buttons in each row */
        .btn-edit {
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 7px;
            padding: 5px 14px;
            font-size: .8rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background .2s, transform .1s;
        }
        .btn-edit:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-1px);  /* Lift effect on hover */
        }
        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 7px;
            padding: 5px 14px;
            font-size: .8rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all .2s;
            cursor: pointer;
        }
        .btn-delete:hover {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
        }

        /* ── SEARCH BAR ─────────────────────────────────────────
           Live search input above the table */
        .search-box {
            position: relative;
        }
        .search-box input {
            padding-left: 2.5rem;         /* Space for search icon */
            border-radius: 8px;
            border: 1px solid var(--border);
            width: 100%;
        }
        .search-box .search-icon {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);  /* Vertically center icon */
            color: var(--text-muted);
            pointer-events: none;         /* Click passes through icon */
        }

        /* ── FORMS ──────────────────────────────────────────────
           Edit form styling */
        .form-label { font-weight: 600; font-size: .88rem; margin-bottom: .35rem; }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid var(--border);
            padding: .6rem .9rem;
            font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.15); /* Blue glow on focus */
            outline: none;
        }
        .form-control.is-invalid { border-color: #dc2626; }
        .invalid-feedback { font-size: .8rem; }

        /* ── MODAL (Delete Confirmation) ─────────────────────── */
        .modal-content { border-radius: 16px; border: none; }
        .modal-header  { border-bottom: 1px solid var(--border); }
        .modal-footer  { border-top: 1px solid var(--border); }

        /* ── RESPONSIVE: Mobile ─────────────────────────────────
           On small screens (< 768px), hide sidebar, full width content */
        @media (max-width: 768px) {
            .sidebar   { transform: translateX(-100%); }  /* Hide sidebar */
            .sidebar.open { transform: translateX(0); }   /* Show when toggled */
            .main-wrap { margin-left: 0; padding: 1rem; } /* Full width */
            .student-table thead { display: none; }       /* Hide table header */
            .student-table tbody tr { display: block; border: 1px solid var(--border);
                                       border-radius: 10px; margin-bottom: 1rem; padding: .5rem; }
            .student-table tbody td {
                display: flex; justify-content: space-between;
                border: none; padding: .4rem .75rem;
            }
            /* Show "column name: value" on mobile using data-label attribute */
            .student-table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--text-muted);
                font-size: .78rem;
            }
        }

        /* ── ANIMATIONS ─────────────────────────────────────────
           Smooth fade-in for new content */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn .3s ease-out; }

        /* Page title styling */
        .page-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: .25rem;
        }
        .page-subtitle { font-size: .85rem; color: var(--text-muted); }
    </style>
</head>
<body>

<!-- ══════════════════════════════════════════════════════════
     TOP NAVIGATION BAR
     Fixed header visible at all times
     ══════════════════════════════════════════════════════════ -->
<header class="topbar">
    <!-- Mobile hamburger button (shows on small screens) -->
    <button class="btn btn-link text-white d-md-none me-2 p-0" id="sidebarToggle"
            style="font-size:1.4rem">
        <i class="bi bi-list"></i>
    </button>

    <!-- Brand logo and name -->
    <a class="brand" href="index.php">
        <span style="background:rgba(255,255,255,.2);border-radius:8px;padding:4px 8px">🎓</span>
        StudentMS
    </a>

    <!-- Right side: lab info -->
    <span class="subtitle d-none d-md-block">Lab Q21 · PHP CRUD · Edit &amp; Delete</span>
</header>

<!-- ══════════════════════════════════════════════════════════
     SIDEBAR NAVIGATION
     Left column with links to all pages
     ══════════════════════════════════════════════════════════ -->
<nav class="sidebar" id="sidebar">
    <div class="nav-label">Main</div>
    <a href="index.php"
       class="nav-link <?= $activePage === 'students' ? 'active' : '' ?>">
        <i class="bi bi-people-fill"></i> Student Records
    </a>
    <a href="add.php"
       class="nav-link <?= $activePage === 'add' ? 'active' : '' ?>">
        <i class="bi bi-person-plus-fill"></i> Add Student
    </a>

    <div class="nav-label" style="margin-top:1.5rem">Info</div>
    <!-- Link shows current database state -->
    <a href="#" class="nav-link" onclick="return false" style="cursor:default;opacity:.6">
        <i class="bi bi-database"></i> student_db
    </a>
</nav>

<!-- ══════════════════════════════════════════════════════════
     MAIN CONTENT WRAPPER
     This is where each page's content goes
     ══════════════════════════════════════════════════════════ -->
<main class="main-wrap fade-in">
<?php
}

/**
 * pageFooter()
 * Closes the main content area, adds JS scripts
 */
function pageFooter(): void {
    ?>
</main><!-- /main-wrap -->

<!-- Bootstrap 5 JS Bundle (includes Popper for dropdowns/modals) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery CDN (Unit II: jQuery library) -->
<!-- jQuery simplifies DOM manipulation, AJAX, and event handling -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
/* ── JavaScript / jQuery (Unit II Syllabus) ────────────────────
   DOM Manipulation, Event Handling, Live Search
   ──────────────────────────────────────────────────────────── */

// ── Mobile Sidebar Toggle ─────────────────────────────────────
// jQuery: $('#id') selects element by ID, .on('click') attaches event
$('#sidebarToggle').on('click', function () {
    // .toggleClass() adds class if missing, removes if present
    $('#sidebar').toggleClass('open');
});

// Close sidebar when clicking outside (on mobile)
$(document).on('click', function (e) {
    // If click is NOT on sidebar or toggle button
    if (!$(e.target).closest('#sidebar, #sidebarToggle').length) {
        $('#sidebar').removeClass('open');
    }
});

// ── Live Table Search ─────────────────────────────────────────
// jQuery: .on('keyup') fires every time user types a character
$('#searchInput').on('keyup', function () {
    // Get search term, convert to lowercase for case-insensitive comparison
    const term = $(this).val().toLowerCase();

    // Select all table body rows
    $('#studentTable tbody tr').each(function () {
        // Get all text content of this row, convert to lowercase
        const rowText = $(this).text().toLowerCase();

        // .toggle(bool) shows if true, hides if false
        // If rowText contains term → show, else hide
        $(this).toggle(rowText.includes(term));
    });

    // Update visible count
    const visible = $('#studentTable tbody tr:visible').length;
    $('#resultCount').text(visible + ' student(s) found');
});

// ── Auto-dismiss flash alerts after 4 seconds ────────────────
// setTimeout() executes a function after a delay (milliseconds)
setTimeout(function () {
    // jQuery fadeOut() animates the element to transparent then hides it
    $('.alert-dismissible').fadeOut(400, function () {
        $(this).remove(); // Remove from DOM after fade
    });
}, 4000); // 4000ms = 4 seconds
</script>

</body>
</html>
<?php
}
?>
