<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->query("
  SELECT event_id, title, date, venue, organizer, description, image_path
  FROM events
  ORDER BY date ASC
");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../includes/header.php';
?>

<div class="gh-page">
  <h3 class="mb-3">All Events</h3>

  <?php if (empty($events)): ?>
    <div class="alert alert-warning">No events yet.</div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($events as $e): ?>
        <div class="col-md-6 col-xl-4">
          <div class="card event-card h-100 p-0">

            <?php if (!empty($e['image_path'])): ?>
              <div class="event-thumb"
                   style="background-image:url('<?= htmlspecialchars($e['image_path']) ?>');">
              </div>
            <?php endif; ?>

            <div class="event-body p-3">
              <div class="d-flex justify-content-between align-items-start mb-1">
                <div class="title">
                  <?= htmlspecialchars($e['title']) ?>
                </div>
                <?php if (!empty($e['venue'])): ?>
                  <span class="badge">
                    <?= htmlspecialchars($e['venue']) ?>
                  </span>
                <?php endif; ?>
              </div>

              <div class="meta mb-2">
                <?= htmlspecialchars($e['date']) ?>
                <?php if (!empty($e['organizer'])): ?>
                  • <?= htmlspecialchars($e['organizer']) ?>
                <?php endif; ?>
              </div>

              <?php if (!empty($e['description'])): ?>
                <p class="card-text mb-3">
                  <?= nl2br(htmlspecialchars($e['description'])) ?>
                </p>
              <?php endif; ?>

              <?php if (is_logged_in()): ?>
                <a class="btn btn-primary w-100"
                   href="/event_register.php?id=<?= (int)$e['event_id'] ?>">
                  Register
                </a>
              <?php else: ?>
                <a class="btn btn-outline-primary w-100"
                   href="/login.php">
                  Login to Register
                </a>
              <?php endif; ?>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
