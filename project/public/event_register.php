<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

require_login();

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$pdo) {
    http_response_code(500);
    exit('Database connection failed');
}
$stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
if (!$stmt) {
    http_response_code(500);
    exit('Database query preparation failed');
}
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
      <div class="card p-4 event-card">
        <div class="mb-3">
          <?php if (!empty($event['image_path'])): ?>
            <div class="event-thumb" style="background-image:url('<?= htmlspecialchars($event['image_path']) ?>'); margin: -1.5rem -1.5rem 1.5rem -1.5rem; border-radius: 12px 12px 0 0;"></div>
          <?php endif; ?>
        </div>
        <h3 class="card-title mb-2"><?= htmlspecialchars($event['title']) ?></h3>
        <p class="meta mb-3">
          📅 <strong><?= date('M d, Y', strtotime($event['date'])) ?></strong>
          <?php if (!empty($event['time'])): ?>
            • 🕐 <strong><?= htmlspecialchars(substr($event['time'],0,5)) ?></strong>
          <?php endif; ?>
          <br>
          📍 <strong><?= htmlspecialchars($event['venue']) ?></strong><br>
          🏢 <strong><?= htmlspecialchars($event['organizer']) ?></strong>
        </p>
        <hr>
        <p class="card-text"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
      </div>
    </div>

    <div class="col-lg-5">
      <form class="card p-4 auth-card"
        method="post"
        action="/handle_event_register.php"
        onsubmit="return validateEventRegister(this)">
        <h5 class="mb-3">✅ Register for this event</h5>

        <input type="hidden" name="event_id" value="<?= (int)$event['event_id'] ?>">

        <div class="mb-3">
          <label class="form-label">Student ID</label>
          <input type="text" class="form-control" name="student_id"
                 placeholder="E.g., 23IT0471"
                 pattern="[A-Za-z0-9]{0,20}">
          <small class="form-text">Optional: Your institutional student ID</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Contact Number <span class="text-danger">*</span></label>
          <input type="tel" class="form-control" name="contact_no"
                 placeholder="E.g., 0771234567"
                 pattern="[0-9\s\-\+\(\)]{8,20}"
                 required>
          <small class="form-text">We'll use this to contact you about the event</small>
        </div>

        <div class="alert alert-info mb-3" style="font-size: 0.85rem;">
          <strong>Before registering:</strong> Make sure you can attend on the scheduled date and time.
        </div>

        <button class="btn btn-primary w-100" type="submit">Confirm Registration</button>
        <a href="/event_register.php?id=<?= (int)$event['event_id'] ?>" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
      </form>
    </div>
  </div>
</div>

<script>
function validateEventRegister(f) {
  const contactNo = f.contact_no.value.trim();
  const studentId = f.student_id.value.trim();
  
  if (!contactNo) {
    alert('Please enter your contact number.');
    f.contact_no.focus();
    return false;
  }
  
  if (!/^[0-9\s\-\+\(\)]{8,20}$/.test(contactNo)) {
    alert('Please enter a valid contact number (at least 8 digits).');
    f.contact_no.focus();
    return false;
  }
  
  if (studentId && !/^[A-Za-z0-9]{0,20}$/.test(studentId)) {
    alert('Please enter a valid student ID.');
    f.student_id.focus();
    return false;
  }
  
  return true;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
