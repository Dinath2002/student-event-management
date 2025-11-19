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

$userStmt = $pdo->prepare("SELECT user_id, name, email, student_id, role, created_at FROM users WHERE user_id = ?");
$userStmt->execute([$user_id]);
$userProfile = $userStmt->fetch(PDO::FETCH_ASSOC);

$regStmt = $pdo->prepare("
    SELECT 
        r.reg_id, 
        e.event_id, 
        e.title, 
        e.date, 
        e.venue, 
        e.image_path,
        r.created_at as registered_at
    FROM registrations r
    JOIN events e ON r.event_id = e.event_id
    WHERE r.user_id = ?
    ORDER BY e.date DESC
");
$regStmt->execute([$user_id]);
$registrations = $regStmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../includes/header.php';
?>

<div class="gh-page">
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card p-4">
        <div class="text-center mb-3">
          <div class="avatar-circle mb-3" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #2f81f7, #6366f1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            👤
          </div>
          <h4 class="mb-1"><?= htmlspecialchars($userProfile['name']) ?></h4>
          <small class="text-muted"><?= $userProfile['role'] === 'admin' ? 'Administrator' : 'Student' ?></small>
        </div>
        <hr>
        <div class="profile-info mb-3">
          <small class="text-muted d-block mb-1">Email</small>
          <div><?= htmlspecialchars($userProfile['email']) ?></div>
        </div>
        <?php if (!empty($userProfile['student_id'])): ?>
          <div class="profile-info mb-3">
            <small class="text-muted d-block mb-1">Student ID</small>
            <div><?= htmlspecialchars($userProfile['student_id']) ?></div>
          </div>
        <?php endif; ?>
        <div class="profile-info mb-3">
          <small class="text-muted d-block mb-1">Member Since</small>
          <div><?= date('M d, Y', strtotime($userProfile['created_at'])) ?></div>
        </div>
        <a href="/logout.php" class="btn btn-outline-danger w-100 mt-3">Logout</a>
      </div>
    </div>

    <div class="col-md-8">
      <h4 class="mb-3">
        📝 My Registered Events
        <span class="badge bg-secondary"><?= count($registrations) ?></span>
      </h4>

      <?php if (empty($registrations)): ?>
        <div class="alert alert-info text-center py-4">
          <p class="mb-2"><strong>You haven't registered for any events yet.</strong></p>
          <a href="/events.php" class="btn btn-primary btn-sm">Browse Events</a>
        </div>
      <?php else: ?>
        <div class="row g-3">
          <?php foreach ($registrations as $reg): ?>
            <div class="col-12">
              <div class="card p-3 registration-card">
                <div class="row align-items-center g-3">
                  <?php if (!empty($reg['image_path'])): ?>
                    <div class="col-md-2">
                      <div class="event-thumb-small" style="background-image:url('<?= htmlspecialchars($reg['image_path']) ?>');"></div>
                    </div>
                  <?php endif; ?>
                  <div class="col-md-<?= !empty($reg['image_path']) ? '7' : '9' ?>">
                    <h5 class="mb-1"><?= htmlspecialchars($reg['title']) ?></h5>
                    <small class="text-muted d-block">
                      📅 <?= date('M d, Y', strtotime($reg['date'])) ?> • 📍 <?= htmlspecialchars($reg['venue']) ?>
                    </small>
                    <small class="text-muted d-block mt-1">Registered: <?= date('M d, Y', strtotime($reg['registered_at'])) ?></small>
                  </div>
                  <div class="col-md-3 text-md-end">
                    <a href="/event_register.php?id=<?= (int)$reg['event_id'] ?>" class="btn btn-sm btn-primary">View Event</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
