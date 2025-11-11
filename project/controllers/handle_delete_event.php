<?php
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
    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    $s = $pdo->prepare('SELECT image_path FROM events WHERE event_id = ? LIMIT 1');
    if (!$s) {
        throw new Exception('Query preparation failed');
    }
    $s->execute([$event_id]);
    $row = $s->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['image_path'])) {
        $file = __DIR__ . '/../public' . $row['image_path'];
        if (is_file($file)) @unlink($file);
    }

    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    $d1 = $pdo->prepare('DELETE FROM registrations WHERE event_id = ?');
    if (!$d1) {
        throw new Exception('Delete registrations query preparation failed');
    }
    $d1->execute([$event_id]);

    
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    $d2 = $pdo->prepare('DELETE FROM events WHERE event_id = ?');
    if (!$d2) {
        throw new Exception('Delete event query preparation failed');
    }
    $d2->execute([$event_id]);

    $_SESSION['flash']['success'][] = 'Event deleted.';
} catch (Throwable $e) {
    $_SESSION['flash']['danger'][] = 'Error deleting event.';
}

header('Location: /events.php');
exit;
