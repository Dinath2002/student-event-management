<?php
// 1) sessions + (optional) force login
require_once __DIR__ . '/../config/auth.php';
// If you want events only for logged-in users, uncomment:
// require_login();

require_once __DIR__ . '/../config/db.php';

// 2) fetch events
$stmt = $pdo->query("SELECT event_id, title, date, venue, organizer, description
                     FROM events ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3) header (navbar shows greeting if session exists)
include __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-3">All Events</h2>

<?php if (empty($events)): ?>
  <div class="alert alert-secondary">No events yet.</div>
<?php else: ?>
  <div class="row row-cols-1 row-cols-md-2 g-3">
    <?php foreach ($events as $e): ?>
      <div class="col">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($e['title']) ?></h5>
            <p class="mb-2">
              <strong>Date:</strong> <?= htmlspecialchars($e['date']) ?><br>
              <strong>Venue:</strong> <?= htmlspecialchars($e['venue']) ?><br>
              <strong>Organizer:</strong> <?= htmlspecialchars($e['organizer'] ?? 'Student Union') ?>
            </p>
            <p class="card-text"><?= nl2br(htmlspecialchars($e['description'])) ?></p>
            <?php if (!empty($_SESSION['user'])): ?>
              <a class="btn btn-primary" href="/event.php?id=<?= (int)$e['event_id'] ?>">View & Register</a>
            <?php else: ?>
              <a class="btn btn-outline-primary" href="/login.php">Login to Register</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
