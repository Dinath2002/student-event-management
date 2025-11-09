<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

require_login();

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    include __DIR__ . '/../includes/header.php';
    echo '<div class="container gh-page"><div class="alert alert-danger mt-4">Event not found.</div></div>';
    include __DIR__ . '/../includes/footer.php';
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="gh-page">
  <div class="row">
    <div class="col-lg-7 mb-3">
      <div class="card p-3 event-card">
        <h3 class="card-title mb-2"><?= htmlspecialchars($event['title']) ?></h3>
        <p class="meta mb-1">
          <strong>Date:</strong> <?= htmlspecialchars($event['date']) ?>
          <?php if (!empty($event['time'])): ?>
            &bull; <strong>Time:</strong> <?= htmlspecialchars(substr($event['time'],0,5)) ?>
          <?php endif; ?>
          <br>
          <strong>Venue:</strong> <?= htmlspecialchars($event['venue']) ?><br>
          <strong>Organizer:</strong> <?= htmlspecialchars($event['organizer']) ?>
        </p>
        <p class="card-text mt-2"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
      </div>
    </div>

    <div class="col-lg-5">
      <form class="card p-3 auth-card"
            method="post"
            action="/controllers/handle_event_register.php"
            onsubmit="return validateEventRegister(this)">
        <h4 class="mb-3">Register for this event</h4>

        <input type="hidden" name="event_id" value="<?= (int)$event['event_id'] ?>">

        <div class="mb-3">
          <label class="form-label">Student ID (optional)</label>
          <input type="text" class="form-control" name="student_id"
                 placeholder="E.g., 23IT0471">
        </div>

        <div class="mb-3">
          <label class="form-label">Contact Number</label>
          <input type="text" class="form-control" name="contact_no"
                 placeholder="E.g., 0771234567" required>
        </div>

        <button class="btn btn-primary w-100" type="submit">Confirm Registration</button>
      </form>
    </div>
  </div>
</div>

<script>
function validateEventRegister(f) {
  if (!f.contact_no.value.trim()) {
    alert('Please enter your contact number.');
    return false;
  }
  return true;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
