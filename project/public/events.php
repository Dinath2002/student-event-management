<?php
require_once __DIR__ . '/../config/db.php';
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<h2>All Events</h2>
<div class="row">
<?php foreach ($events as $e): ?>
  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-body">
        <h5><?php echo htmlspecialchars($e['title']); ?></h5>
        <p><?php echo htmlspecialchars($e['description']); ?></p>
        <p><strong>Date:</strong> <?php echo $e['date']; ?> <br>
        <strong>Venue:</strong> <?php echo $e['venue']; ?></p>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
