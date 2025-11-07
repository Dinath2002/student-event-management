<?php
require_once __DIR__ . '/../config/auth.php';
require_admin(); // extra safety: only admin can post

require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin_add_event.php');
    exit;
}

// Trim and collect input
$title       = trim($_POST['title'] ?? '');
$date        = trim($_POST['date'] ?? '');
$venue       = trim($_POST['venue'] ?? '');
$organizer   = trim($_POST['organizer'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($title === '' || $date === '' || $venue === '' || $organizer === '' || $description === '') {
    $_SESSION['flash']['danger'][] = 'All fields are required.';
    header('Location: /admin_add_event.php');
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO events (title, date, venue, organizer, description)
        VALUES (:title, :date, :venue, :organizer, :description)
    ");

    $stmt->execute([
        ':title'       => $title,
        ':date'        => $date,
        ':venue'       => $venue,
        ':organizer'   => $organizer,
        ':description' => $description,
    ]);

    $_SESSION['flash']['success'][] = 'Event created successfully.';
    header('Location: /events.php');
    exit;

} catch (Throwable $e) {
    $_SESSION['flash']['danger'][] = 'Error creating event: ' . $e->getMessage();
    header('Location: /admin_add_event.php');
    exit;
}
