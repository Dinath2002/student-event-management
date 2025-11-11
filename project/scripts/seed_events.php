<?php
 

require_once __DIR__ . '/../config/db.php';

$events = [
    [
        'title' => 'AI Seminar 2025',
        'date' => '2025-12-10',
        'time' => '10:00:00',
        'venue' => 'Dept. of IT Auditorium',
        'organizer' => 'Dept. of IT',
        'description' => 'Introduction to AI, LLMs and modern student projects.'
    ],
    [
        'title' => 'Hackathon 2025',
        'date' => '2025-12-15',
        'time' => '09:00:00',
        'venue' => 'Main Hall',
        'organizer' => 'CS Society',
        'description' => '24-hour coding challenge for student teams with prizes.'
    ]
];

$inserted = 0;
foreach ($events as $e) {
    
    if (!$pdo) {
        echo "Database connection failed\n";
        exit(1);
    }
    $stmt = $pdo->prepare("SELECT event_id FROM events WHERE title = ? AND date = ? LIMIT 1");
    if (!$stmt) {
        echo "Query preparation failed\n";
        exit(1);
    }
    $stmt->execute([$e['title'], $e['date']]);
    $exists = $stmt->fetch();
    if ($exists) {
        echo "Skipping existing event: {$e['title']} ({$e['date']})\n";
        continue;
    }

    if (!$pdo) {
        echo "Database connection failed\n";
        exit(1);
    }
    $ins = $pdo->prepare(
        "INSERT INTO events (title, date, time, venue, organizer, description, image_path) VALUES (:title, :date, :time, :venue, :organizer, :description, NULL)"
    );
    if (!$ins) {
        echo "Insert preparation failed\n";
        exit(1);
    }

    $ins->execute([
        ':title' => $e['title'],
        ':date' => $e['date'],
        ':time' => $e['time'],
        ':venue' => $e['venue'],
        ':organizer' => $e['organizer'],
        ':description' => $e['description'],
    ]);

    $inserted++;
    echo "Inserted: {$e['title']} ({$e['date']})\n";
}

if ($inserted === 0) {
    echo "No new events inserted.\n";
} else {
    echo "Done. Inserted $inserted event(s).\n";
}
