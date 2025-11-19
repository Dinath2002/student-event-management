<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

require_login();

if (!$pdo) {
    http_response_code(500);
    exit('Database connection failed');
}

$user = current_user();
$user_id = $user['user_id'];

$upcomingStmt = $pdo->prepare("
    SELECT COUNT(DISTINCT event_id) as count 
    FROM events 
    WHERE date >= DATE('now')
");
$upcomingStmt->execute();
$upcomingCount = $upcomingStmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

$myRegStmt = $pdo->prepare("
    SELECT COUNT(*) as count 
    FROM registrations 
    WHERE user_id = ?
");
$myRegStmt->execute([$user_id]);
$myRegCount = $myRegStmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

$nextEventStmt = $pdo->prepare("
    SELECT e.event_id, e.title, e.date, e.venue, e.image_path
    FROM events e
    WHERE e.date >= DATE('now')
    ORDER BY e.date ASC
    LIMIT 3
");
$nextEventStmt->execute();
$nextEvents = $nextEventStmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../includes/header.php';
?>
<div class="gh-page">
  <div class="gh-hero mb-5">
    <h2 class="mb-2">Welcome back, <?= htmlspecialchars($user['name']) ?> 👋</h2>
    <p class="text-muted mb-4">Stay connected with university events. Discover workshops, hackathons, seminars and more happening around campus.</p>
  </div>

  <div class="row g-3 mb-5">
    <div class="col-md-4">
      <div class="stat-card">
        <div class="stat-icon">📅</div>
        <div class="stat-content">
          <small>Upcoming Events</small>
          <h4><?= $upcomingCount ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-content">
          <small>Events Registered</small>
          <h4><?= $myRegCount ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <div class="stat-icon">🎓</div>
        <div class="stat-content">
          <small>Your Status</small>
          <h4><?= is_admin() ? 'Admin' : 'Student' ?></h4>
        </div>
      </div>
    </div>
  </div>

  <?php if (!empty($nextEvents)): ?>
    <div class="mb-5">
      <h4 class="mb-3">⭐ Upcoming This Week</h4>
      <div class="row g-3">
        <?php foreach ($nextEvents as $evt): ?>
          <div class="col-md-4">
            <div class="card event-card h-100">
              <?php if (!empty($evt['image_path'])): ?>
                <div class="event-thumb" style="background-image:url('<?= htmlspecialchars($evt['image_path']) ?>');"></div>
              <?php endif; ?>
              <div class="event-body p-3">
                <h6><?= htmlspecialchars($evt['title']) ?></h6>
                <small class="text-muted d-block">
                  📅 <?= date('M d, Y', strtotime($evt['date'])) ?>
                  <br>📍 <?= htmlspecialchars($evt['venue']) ?>
                </small>
                <a href="/event_register.php?id=<?= (int)$evt['event_id'] ?>" class="btn btn-primary btn-sm w-100 mt-2">Register</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="d-flex gap-2 flex-wrap">
    <a href="/events.php" class="btn btn-primary">Browse All Events</a>
    <a href="/profile.php" class="btn btn-outline-secondary">View Profile</a>
    <?php if (is_admin()): ?>
      <a href="/admin_add_event.php" class="btn btn-outline-secondary">Create Event</a>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

