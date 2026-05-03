<?php
// ============================================================
// submit_complaint.php — File a New Complaint
// Concepts: HTML Forms, POST handling, SQL INSERT, Redirect-After-Post
// ============================================================
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin(); // only logged-in users can file complaints

$error = '';

// List of organizations users can complain about
// Using a PHP array — can be moved to DB later
$organizations = ['PMC', 'PMT', 'MSEB', 'NMC', 'MCGM', 'Railways', 'Post Office', 'Other'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form inputs and sanitize
    $org         = trim(htmlspecialchars($_POST['organization']));
    $subject     = trim(htmlspecialchars($_POST['subject']));
    $description = trim(htmlspecialchars($_POST['description']));

    // Validate: no empty fields
    if (empty($org) || empty($subject) || empty($description)) {
        $error = "Please fill in all fields.";
    } elseif (!in_array($org, $organizations)) {
        // in_array() checks if value exists in array — prevents invalid org
        $error = "Invalid organization selected.";
    } elseif (strlen($subject) < 5) {
        $error = "Subject must be at least 5 characters.";
    } elseif (strlen($description) < 20) {
        $error = "Please provide a more detailed description (min 20 characters).";
    } else {
        // INSERT complaint into database
        // currentUserId() gets the logged-in user's ID from session
        $stmt = $pdo->prepare("
            INSERT INTO complaints (user_id, organization, subject, description)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([currentUserId(), $org, $subject, $description]);
        
        // ── Post/Redirect/Get (PRG) Pattern ──
        // After INSERT, redirect so browser doesn't resubmit on refresh
        // Store success message in SESSION (flash message)
        $_SESSION['flash'] = "✅ Complaint submitted successfully! ID: #" . $pdo->lastInsertId();
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <span class="brand">📋 Complaint Management System</span>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container" style="max-width:640px">
    <div class="card">
        <h2>📝 Submit a New Complaint</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" action="" id="complaintForm">

            <!-- Organization Dropdown -->
            <div class="form-group">
                <label for="organization">Organization / Institution *</label>
                <!-- <select> creates a dropdown menu -->
                <select name="organization" id="organization" required>
                    <option value="">-- Select Organization --</option>
                    <?php foreach ($organizations as $org): ?>
                        <!-- selected if this was the previously submitted value -->
                        <option value="<?= $org ?>"
                            <?= (($_POST['organization'] ?? '') === $org) ? 'selected' : '' ?>>
                            <?= $org ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Subject -->
            <div class="form-group">
                <label for="subject">Complaint Subject *</label>
                <input type="text" id="subject" name="subject"
                       value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
                       placeholder="Brief title of your complaint (e.g. Road not repaired for 6 months)"
                       maxlength="200" required>
                <!-- maxlength limits chars in browser; we also validate server-side -->
            </div>

            <!-- Description (Textarea) -->
            <div class="form-group">
                <label for="description">Detailed Description *</label>
                <textarea id="description" name="description"
                          rows="6" required
                          placeholder="Describe your complaint in detail: location, date, what happened, what you expect..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                <small style="color:#64748b">Minimum 20 characters</small>
            </div>

            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn btn-primary">Submit Complaint</button>
                <a href="dashboard.php" class="btn" style="background:#e2e8f0;color:#334155">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
// ── Client-Side Validation with JavaScript ──
// This runs BEFORE the form is sent to the server
// Server validation (PHP) is still required — JS can be disabled
document.getElementById('complaintForm').addEventListener('submit', function(e) {
    const subject = document.getElementById('subject').value.trim();
    const desc    = document.getElementById('description').value.trim();
    const org     = document.getElementById('organization').value;

    if (!org) {
        alert('Please select an organization.');
        e.preventDefault(); // stop form from submitting
        return;
    }
    if (subject.length < 5) {
        alert('Subject must be at least 5 characters.');
        e.preventDefault();
        return;
    }
    if (desc.length < 20) {
        alert('Description must be at least 20 characters.');
        e.preventDefault();
        return;
    }
});
</script>
</body>
</html>
