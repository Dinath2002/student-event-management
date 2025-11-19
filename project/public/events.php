<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

if (!$pdo) {
    http_response_code(500);
    exit('Database connection failed');
}

$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'date';
$filter_date = $_GET['filter_date'] ?? '';

$query = "SELECT event_id, title, date, venue, organizer, description, image_path FROM events WHERE 1=1";
$params = [];

if ($search !== '') {
    $query .= " AND (title LIKE ? OR description LIKE ? OR organizer LIKE ?)";
    $searchTerm = "%" . $search . "%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
}

if ($filter_date !== '') {
    $query .= " AND DATE(date) = ?";
    $params[] = $filter_date;
}

if ($sort === 'title') {
    $query .= " ORDER BY title ASC";
} elseif ($sort === 'date_desc') {
    $query .= " ORDER BY date DESC";
} else {
    $query .= " ORDER BY date ASC";
}

$stmt = $pdo->prepare($query);
if (!$stmt) {
    http_response_code(500);
    exit('Database query failed');
}
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../includes/header.php';
?>

<div class="gh-page">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">All Events</h3>
    <small class="text-muted"><?= count($events) ?> event<?= count($events) !== 1 ? 's' : '' ?></small>
  </div>

  <div class="card p-4 mb-4 search-filters">
    <form method="get" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Search Events</label>
        <input type="text" class="form-control" name="search" placeholder="Search by title, organizer, or description..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Filter by Date</label>
        <input type="date" class="form-control" name="filter_date" value="<?= htmlspecialchars($filter_date) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Sort By</label>
        <select class="form-select" name="sort">
          <option value="date" <?= $sort === 'date' ? 'selected' : '' ?>>Date (Upcoming)</option>
          <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>Date (Latest)</option>
          <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>Title (A-Z)</option>
        </select>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="/events.php" class="btn btn-outline-secondary">Clear</a>
      </div>
    </form>
  </div>

  <?php if (empty($events)): ?>
    <div class="alert alert-info text-center py-4">
      <strong>No events found.</strong><br>
      <small>Try adjusting your search or filters to see more events.</small>
    </div>
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
                  <span class="badge bg-secondary">
                    📍 <?= htmlspecialchars($e['venue']) ?>
                  </span>
                <?php endif; ?>
              </div>

              <div class="meta mb-2">
                <small>
                  📅 <?= date('M d, Y', strtotime($e['date'])) ?>
                  <?php if (!empty($e['organizer'])): ?>
                    • 🏢 <?= htmlspecialchars($e['organizer']) ?>
                  <?php endif; ?>
                </small>
              </div>

              <?php if (!empty($e['description'])): ?>
                <p class="card-text mb-3">
                  <?= nl2br(htmlspecialchars($e['description'])) ?>
                </p>
              <?php endif; ?>

              <div class="d-flex gap-2 mt-auto">
                <?php if (is_logged_in()): ?>
                  <a class="btn btn-primary flex-grow-1 btn-sm"
                     href="/event_register.php?id=<?= (int)$e['event_id'] ?>">
                    📝 Register
                  </a>
                <?php else: ?>
                  <a class="btn btn-outline-primary flex-grow-1 btn-sm"
                     href="/login.php">
                    Login to Register
                  </a>
                <?php endif; ?>

                <?php if (is_admin()): ?>
                  <a class="btn btn-outline-secondary btn-sm"
                     href="/admin_edit_event.php?id=<?= (int)$e['event_id'] ?>"
                     title="Edit event">
                    ✏️ Edit
                  </a>
                  <a class="btn btn-outline-danger btn-sm"
                     href="#"
                     onclick="if(confirm('Delete this event?')) { document.location='/handle_delete_event.php?id=<?= (int)$e['event_id'] ?>'; } return false;"
                     title="Delete event">
                    🗑️
                  </a>
                <?php endif; ?>
              </div>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
