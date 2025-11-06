<?php
require_once __DIR__ . '/../config/auth.php';
require_login();
include __DIR__ . '/../includes/header.php';
?>
<div class="container text-center">
  <h2 class="fw-semibold mb-4">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?> 🎉</h2>
  <p class="lead text-muted mb-5">Manage and explore your university events easily.</p>
  <div class="d-flex justify-content-center gap-3">
    <a href="/events.php" class="btn btn-primary px-4">View Events</a>
    <a href="/logout.php" class="btn btn-outline-secondary px-4">Logout</a>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
