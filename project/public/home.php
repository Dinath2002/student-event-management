<?php
require_once __DIR__ . '/../config/auth.php';
require_login();
include __DIR__ . '/../includes/header.php';
?>
<div class="gh-page">
  <div class="gh-hero mb-4">
    <h2 class="mb-2">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></h2>
    <p class="text-muted">Manage and explore university events with a clean, GitHub-like interface.</p>
    <div class="d-flex gap-2 mt-3">
      <a href="/events.php" class="btn btn-primary">Browse Events</a>
      <a href="/logout.php" class="btn btn-outline-secondary">Logout</a>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
