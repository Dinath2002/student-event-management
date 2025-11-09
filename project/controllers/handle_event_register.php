<?php
// Handle event registration

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

require_login(); // only logged-in users

$user = current_user();
$user_id = $user['user_id'] ?? null;

$event_id   = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
$student_id = trim($_POST['student_id'] ?? '');
$contact_no = trim($_POST['contact_no'] ?? '');

// basic validation
if (!$event_id || !$user_id) {
    $_SESSION['flash']['danger'][] = 'Invalid request.';
    header('Location: /events.php');
    exit;
}

// ensure event exists
$eStmt = $pdo->prepare("SELECT event_id, title FROM events WHERE event_id = ?");
$eStmt->execute([$event_id]);
$event = $eStmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $_SESSION['flash']['danger'][] = 'Selected event not found.';
    header('Location: /events.php');
    exit;
}

try {
    // check duplicate
    $check = $pdo->prepare("SELECT reg_id FROM registrations WHERE user_id = ? AND event_id = ?");
    $check->execute([$user_id, $event_id]);

    if ($check->fetch()) {
        $_SESSION['flash']['info'][] = 'You have already registered for this event.';
        header('Location: /event_register.php?id=' . $event_id);
        exit;
    }

    // insert registration
    $ins = $pdo->prepare(
        "INSERT INTO registrations (user_id, event_id, student_id, contact_no)
         VALUES (:user_id, :event_id, :student_id, :contact_no)"
    );
    $ins->execute([
        ':user_id'    => $user_id,
        ':event_id'   => $event_id,
        ':student_id' => $student_id ?: ($user['student_id'] ?? null),
        ':contact_no' => $contact_no ?: null
    ]);

    $_SESSION['flash']['success'][] =
        'Successfully registered for "' . htmlspecialchars($event['title']) . '".';

    header('Location: /events.php');
    exit;

} catch (Throwable $e) {
    $_SESSION['flash']['danger'][] = 'Registration failed. Please try again.';
    header('Location: /event_register.php?id=' . $event_id);
    exit;
}
