<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

require_login();
require_admin();

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$event_id) {
    $_SESSION['flash']['danger'][] = 'Invalid event ID.';
    header('Location: /events.php');
    exit;
}

if (!$pdo) {
    http_response_code(500);
    exit('Database connection failed');
}
$stmt = $pdo->prepare('SELECT * FROM events WHERE event_id = ? LIMIT 1');
if (!$stmt) {
    http_response_code(500);
    exit('Database query preparation failed');
}
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $_SESSION['flash']['danger'][] = 'Event not found.';
    header('Location: /events.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="event-create-wrapper">
  <form
    class="card p-4 event-create-card"
    method="post"
    action="/handle_edit_event.php"
    enctype="multipart/form-data"
  >
    <input type="hidden" name="event_id" value="<?= (int)$event['event_id'] ?>">

    <div class="d-flex justify-content-between align-items-baseline mb-1">
      <h3 class="mb-0">Edit Event</h3>
      <span class="badge rounded-pill bg-secondary text-uppercase"
            style="font-size: 0.65rem; letter-spacing: .08em;">
        Admin Panel
      </span>
    </div>

    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>
    </div>

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Date</label>
        <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($event['date']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Venue</label>
        <input type="text" class="form-control" name="venue" value="<?= htmlspecialchars($event['venue']) ?>" required>
      </div>
    </div>

    <div class="mb-3 mt-3">
      <label class="form-label">Organizer</label>
      <input type="text" class="form-control" name="organizer" value="<?= htmlspecialchars($event['organizer']) ?>" required>
    </div>

    <div class="mb-4">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($event['description']) ?></textarea>
    </div>

    <?php if (!empty($event['image_path'])): ?>
      <div class="mb-3">
        <label class="form-label">Current Banner</label>
        <div>
          <img src="<?= htmlspecialchars($event['image_path']) ?>" alt="banner" style="max-width:100%;height:auto;">
        </div>
      </div>
    <?php endif; ?>

    <div class="mb-4">
      <label class="form-label">Replace Banner (optional)</label>
      <input type="file" class="form-control" name="image" accept=".jpg,.jpeg,.png,.webp">
      <div class="form-text">Leave empty to keep existing banner. Max 2MB.</div>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary" type="submit">Save changes</button>
      <a class="btn btn-outline-danger" href="/admin_delete_event.php?id=<?= (int)$event['event_id'] ?>"
         onclick="return confirm('Delete this event? This action cannot be undone.')">Delete</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
