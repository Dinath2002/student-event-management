<?php
// Controller: handle_delete_event.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

require_login();
if (!is_admin()) {
    http_response_code(403);
    exit('Access denied');
}

$event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
if (!$event_id) {
    $_SESSION['flash']['danger'][] = 'Invalid event id.';
    header('Location: /events.php');
    exit;
}

try {
    // fetch image path to delete file
    $s = $pdo->prepare('SELECT image_path FROM events WHERE event_id = ? LIMIT 1');
    $s->execute([$event_id]);
    $row = $s->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['image_path'])) {
        $file = __DIR__ . '/../public' . $row['image_path'];
        if (is_file($file)) @unlink($file);
    }

    // delete registrations for the event
    $d1 = $pdo->prepare('DELETE FROM registrations WHERE event_id = ?');
    $d1->execute([$event_id]);

    // delete the event
    $d2 = $pdo->prepare('DELETE FROM events WHERE event_id = ?');
    $d2->execute([$event_id]);

    $_SESSION['flash']['success'][] = 'Event deleted.';
} catch (Throwable $e) {
    $_SESSION['flash']['danger'][] = 'Error deleting event.';
}

header('Location: /events.php');
exit;
