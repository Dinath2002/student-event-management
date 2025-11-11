<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

require_login();
require_admin();

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$event_id) {
    $_SESSION['flash']['danger'][] = 'Invalid event id.';
    header('Location: /events.php');
    exit;
}

$stmt = $pdo->prepare('SELECT event_id, title, date FROM events WHERE event_id = ? LIMIT 1');
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$event) {
    $_SESSION['flash']['danger'][] = 'Event not found.';
    header('Location: /events.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="gh-page">
  <div class="card p-4">
    <h4>Delete event</h4>
    <p>Are you sure you want to delete the event <strong><?= htmlspecialchars($event['title']) ?></strong> scheduled on <?= htmlspecialchars($event['date']) ?>?</p>

    <form method="post" action="/handle_delete_event.php">
      <input type="hidden" name="event_id" value="<?= (int)$event['event_id'] ?>">
      <button class="btn btn-danger" type="submit">Yes, delete</button>
      <a class="btn btn-outline-secondary" href="/admin_edit_event.php?id=<?= (int)$event['event_id'] ?>">Cancel</a>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
