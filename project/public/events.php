<?php
// 1) Sessions + (optional) force login
require_once __DIR__ . '/../config/auth.php';
// If you want events only for logged-in users, uncomment next line:
// require_login();

require_once __DIR__ . '/../config/db.php';

// 2) Fetch events (ordered by date)
$stmt = $pdo->query("
  SELECT event_id, title, date, venue, organizer, description
  FROM events
  ORDER BY date ASC
");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3) Header (navbar shows greeting if session exists)
include __DIR__ . '/../includes/header.php';
?>

<div class="gh-page">
  <h3 class="mb-3">All Events</h3>

  <?php if (empty($events)): ?>
    <div class="alert alert-warning">No events yet.</div>
  <?php else: ?>
    <div class="event-grid row row-cols-1 row-cols-md-2 g-3">
      <?php foreach ($events as $e): ?>
        <div class="col">
          <div class="card p-3 event-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="title"><?= htmlspecialchars($e['title']) ?></div>
              <?php if (!empty($e['venue'])): ?>
                <span class="badge text-bg-light border"><?= htmlspecialchars($e['venue']) ?></span>
              <?php endif; ?>
            </div>

            <div class="meta mb-2">
              <?= htmlspecialchars($e['date']) ?>
              <?php if (!empty($e['organizer'])): ?>
                • <?= htmlspecialchars($e['organizer']) ?>
              <?php endif; ?>
            </div>

            <?php if (!empty($e['description'])): ?>
              <p class="mb-3 text-muted"><?= nl2br(htmlspecialchars($e['description'])) ?></p>
            <?php endif; ?>

            <?php if (is_logged_in()): ?>
              <a class="btn btn-primary btn-sm"
                 href="/event_register.php?id=<?= (int)$e['event_id'] ?>">
                Register
              </a>
            <?php else: ?>
              <a class="btn btn-outline-primary btn-sm"
                 href="/login.php">
                Login to Register
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
