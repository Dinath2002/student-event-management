<?php
// show errors while developing
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../includes/header.php';
?>
<h1 class="mb-3">Student Event Management</h1>
<p>Browse and register for upcoming university events.</p>
<a href="/events.php" class="btn btn-primary">View Events</a>
<?php include __DIR__ . '/../includes/footer.php'; ?>
