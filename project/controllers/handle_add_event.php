<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

require_login();
if (!is_admin()) {
    http_response_code(403);
    exit('Access denied');
}

/** simple flash helper */
function flash($type, $msg) {
    $_SESSION['flash'][$type][] = $msg;
}

$title       = trim($_POST['title'] ?? '');
$date        = trim($_POST['date'] ?? '');
$venue       = trim($_POST['venue'] ?? '');
$organizer   = trim($_POST['organizer'] ?? '');
$description = trim($_POST['description'] ?? '');

$errors = [];

if ($title === '')       $errors[] = 'Title is required.';
if ($date === '')        $errors[] = 'Date is required.';
if ($venue === '')       $errors[] = 'Venue is required.';
if ($organizer === '')   $errors[] = 'Organizer is required.';
if ($description === '') $errors[] = 'Description is required.';

$imagePath = null;

/* ---- Handle optional image upload ---- */
if (!empty($_FILES['image']['name'])) {
    $file     = $_FILES['image'];
    $tmpPath  = $file['tmp_name'];
    $size     = (int)$file['size'];

    if (!is_uploaded_file($tmpPath)) {
        $errors[] = 'Image upload failed.';
    } else {
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        $mime = @mime_content_type($tmpPath);
        if (!isset($allowedTypes[$mime])) {
            $errors[] = 'Only JPG, PNG, or WebP images are allowed.';
        } elseif ($size > 2 * 1024 * 1024) { // 2MB
            $errors[] = 'Image size must be 2MB or less.';
        } else {
            $ext       = $allowedTypes[$mime];
            $uploadDir = __DIR__ . '/../public/assets/img/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = 'event_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $destPath = $uploadDir . $fileName;

            if (!move_uploaded_file($tmpPath, $destPath)) {
                $errors[] = 'Error saving uploaded image.';
            } else {
                // public URL path used in <img src="">
                $imagePath = '/assets/img/events/' . $fileName;
            }
        }
    }
}

if ($errors) {
    foreach ($errors as $e) {
        flash('danger', $e);
    }
    header('Location: /admin_add_event.php');
    exit;
}

/* ---- Insert event ---- */
$stmt = $pdo->prepare("
    INSERT INTO events (title, date, venue, organizer, description, image_path)
    VALUES (:title, :date, :venue, :organizer, :description, :image_path)
");

$stmt->execute([
    ':title'       => $title,
    ':date'        => $date,
    ':venue'       => $venue,
    ':organizer'   => $organizer,
    ':description' => $description,
    ':image_path'  => $imagePath,
]);

flash('success', 'Event created successfully.');
header('Location: /events.php');
exit;
