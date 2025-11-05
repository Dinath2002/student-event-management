<?php
require_once __DIR__ . '/../config/auth.php';
require_login();
include __DIR__ . '/../includes/header.php';
?>
<h2>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</h2>
<p>Use the buttons below to continue.</p>
<a class="btn btn-primary" href="/events.php">View Events</a>
<a class="btn btn-outline-secondary" href="/logout.php">Logout</a>
<?php include __DIR__ . '/../includes/footer.php'; ?>
