<?php
require_once __DIR__ . '/../config/auth.php';
require_admin(); // only admin can add events

require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../includes/header.php';
?>

<h2 class="mb-4">Create New Event</h2>

<form class="card p-4" method="post" action="/controllers/handle_add_event.php">
  <div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" class="form-control" name="title" required>
  </div>

  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Date</label>
      <input type="date" class="form-control" name="date" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Venue</label>
      <input type="text" class="form-control" name="venue" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Organizer</label>
      <input type="text" class="form-control" name="organizer" placeholder="e.g. CS Society" required>
    </div>
  </div>

  <div class="mt-3">
    <label class="form-label">Description</label>
    <textarea class="form-control" name="description" rows="4" required></textarea>
  </div>

  <div class="mt-3 text-end">
    <a href="/events.php" class="btn btn-outline-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Event</button>
  </div>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
