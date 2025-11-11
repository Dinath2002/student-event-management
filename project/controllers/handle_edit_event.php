<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

require_login();
if (!is_admin()) {
    http_response_code(403);
    exit('Access denied');
}

$event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
$title = trim($_POST['title'] ?? '');
$date = trim($_POST['date'] ?? '');
$venue = trim($_POST['venue'] ?? '');
$organizer = trim($_POST['organizer'] ?? '');
$description = trim($_POST['description'] ?? '');

$errors = [];
if (!$event_id) $errors[] = 'Invalid event.';
if ($title === '') $errors[] = 'Title is required.';
if ($date === '') $errors[] = 'Date is required.';
if ($venue === '') $errors[] = 'Venue is required.';
if ($organizer === '') $errors[] = 'Organizer is required.';

$imagePath = null;
if (!empty($_FILES['image']['name'])) {
    $file = $_FILES['image'];
    $tmpPath = $file['tmp_name'];
    $size = (int)$file['size'];

    if (!is_uploaded_file($tmpPath)) {
        $errors[] = 'Image upload failed.';
    } else {
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        $mime = @mime_content_type($tmpPath);
        if (!isset($allowedTypes[$mime])) {
            $errors[] = 'Only JPG, PNG, or WebP images are allowed.';
        } elseif ($size > 2 * 1024 * 1024) {
            $errors[] = 'Image size must be 2MB or less.';
        } else {
            $ext = $allowedTypes[$mime];
            $uploadDir = __DIR__ . '/../public/assets/img/events/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $fileName = 'event_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $destPath = $uploadDir . $fileName;
            if (!move_uploaded_file($tmpPath, $destPath)) {
                $errors[] = 'Error saving uploaded image.';
            } else {
                $imagePath = '/assets/img/events/' . $fileName;
            }
        }
    }
}

if ($errors) {
    foreach ($errors as $e) {
        $_SESSION['flash']['danger'][] = $e;
    }
    ob_end_clean();
    header('Location: /admin_edit_event.php?id=' . $event_id);
    exit;
}

if ($imagePath === null) {
    $sql = "UPDATE events SET title = :title, date = :date, venue = :venue, organizer = :organizer, description = :description WHERE event_id = :event_id";
    $params = [
        ':title' => $title,
        ':date' => $date,
        ':venue' => $venue,
        ':organizer' => $organizer,
        ':description' => $description,
        ':event_id' => $event_id,
    ];
} else {
    
    try {
        if (!$pdo) {
            throw new Exception('Database connection failed');
        }
        $s = $pdo->prepare('SELECT image_path FROM events WHERE event_id = ? LIMIT 1');
        if (!$s) {
            throw new Exception('Query preparation failed');
        }
        $s->execute([$event_id]);
        $old = $s->fetch(PDO::FETCH_ASSOC);
        if (!empty($old['image_path'])) {
            $oldPath = __DIR__ . '/../public' . $old['image_path'];
            if (is_file($oldPath)) @unlink($oldPath);
        }
    } catch (Throwable $e) {
    }

    $sql = "UPDATE events SET title = :title, date = :date, venue = :venue, organizer = :organizer, description = :description, image_path = :image_path WHERE event_id = :event_id";
    $params = [
        ':title' => $title,
        ':date' => $date,
        ':venue' => $venue,
        ':organizer' => $organizer,
        ':description' => $description,
        ':image_path' => $imagePath,
        ':event_id' => $event_id,
    ];
}

if (!$pdo) {
    ob_end_clean();
    $_SESSION['flash']['danger'][] = 'Database connection failed.';
    header('Location: /admin_edit_event.php?id=' . $event_id);
    exit;
}
$stmt = $pdo->prepare($sql);
if (!$stmt) {
    ob_end_clean();
    $_SESSION['flash']['danger'][] = 'Database query preparation failed.';
    header('Location: /admin_edit_event.php?id=' . $event_id);
    exit;
}
$stmt->execute($params);

$_SESSION['flash']['success'][] = 'Event updated successfully.';
ob_end_clean();
header('Location: /events.php');
exit;
