<!DOCTYPE html>
<!-- ============================================================
GET_DEMO.PHP — Demonstrates HTML form with GET method
THEORY: GET method appends form data to the URL like:
  get_demo.php?name=John&email=john@mail.com&search=hello
This is visible in the address bar — do NOT use for passwords!
Good use cases: search bars, filters, pagination links
============================================================ -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GET Method Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4>🔍 GET Method Demo</h4>
                    <small>Submit this form and watch the URL change!</small>
                </div>
                <div class="card-body">

                    <!-- THEORY: method="GET" puts form data in URL
                         Notice: NO password field here — never use GET for passwords! -->
                    <form action="get_demo.php" method="GET">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name (will appear in URL)</label>
                            <!-- PHP: if name was submitted, pre-fill the field -->
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>"
                                   placeholder="Type your name">
                        </div>
                        <div class="mb-3">
                            <label for="search" class="form-label">Search Query</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                                   placeholder="What are you searching for?">
                        </div>

                        <button type="submit" class="btn btn-warning">
                            🔍 Search (GET Request)
                        </button>
                        <a href="index.php" class="btn btn-secondary ms-2">← Back to Login</a>
                    </form>

                    <!-- Only show results section if form was submitted -->
                    <?php if (!empty($_GET)): ?>
                    <hr>
                    <div class="alert alert-info">
                        <h5>📦 Data Received via $_GET:</h5>
                        <p>Look at your browser's address bar — you can see the data in the URL!</p>
                        <table class="table table-sm table-bordered">
                            <tr><th>$_GET Key</th><th>Value</th></tr>
                            <?php
                            // THEORY: Loop through all GET parameters and display them
                            // foreach iterates over every key=>value pair in an array
                            foreach ($_GET as $key => $value):
                                // Always sanitize before displaying! (XSS prevention)
                                $safe_key   = htmlspecialchars($key);
                                $safe_value = htmlspecialchars($value);
                            ?>
                            <tr>
                                <td><code>$_GET['<?= $safe_key ?>']</code></td>
                                <td><?= $safe_value ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>

                        <p class="mt-2 mb-0 small text-muted">
                            <strong>Current URL:</strong>
                            <code><?= htmlspecialchars($_SERVER['REQUEST_URI']) ?></code>
                        </p>
                    </div>
                    <?php endif; ?>

                    <!-- Theory box -->
                    <div class="card bg-light mt-3">
                        <div class="card-body small">
                            <h6>📚 GET Method Rules:</h6>
                            <ul class="mb-0">
                                <li>✅ Use for: search queries, filters, links you want to share</li>
                                <li>❌ Never use for: passwords, credit card numbers, personal data</li>
                                <li>Data is visible in URL, browser history, and server logs</li>
                                <li>Can be bookmarked and shared</li>
                                <li>Max URL length ~2000 characters</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
