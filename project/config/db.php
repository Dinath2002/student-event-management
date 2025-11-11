<?php
/**
 * Database connection.
 *
 * Try MySQL first (using env vars if present). If connection fails
 * (for example on developer machines without a MySQL server or
 * differing credentials), fall back to a local SQLite file located
 * at project/db/dev.sqlite and ensure required tables exist.
 */

$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'student_events_db';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

$pdo = null;

try {
    // Attempt MySQL connection first (production-like)
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

} catch (PDOException $e) {
    // Fallback to SQLite for local development when MySQL is unavailable.
    $sqlitePath = __DIR__ . '/../db/dev.sqlite';
    $dsn = 'sqlite:' . $sqlitePath;

    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create required tables if they don't exist. Schema is kept minimal
    // and aligned with the application's queries (image_path, contact_no, etc.).
    $pdo->exec(<<<'SQL'
    CREATE TABLE IF NOT EXISTS users (
        user_id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'student',
        student_id TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS events (
        event_id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        date TEXT NOT NULL,
        time TEXT NULL,
        venue TEXT NOT NULL,
        organizer TEXT NULL,
        description TEXT NULL,
        image_path TEXT NULL,
        created_by INTEGER NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS registrations (
        reg_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        event_id INTEGER NOT NULL,
        student_id TEXT NULL,
        contact_no TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(user_id, event_id)
    );
    SQL
    );

    // Seed an admin user if none exists (only on fresh sqlite DB).
    try {
        $r = $pdo->query("SELECT COUNT(*) as c FROM users")->fetch();
        if (empty($r) || (int)$r['c'] === 0) {
            $hash = password_hash('Admin123', PASSWORD_DEFAULT);
            $ins = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $ins->execute(['Administrator', 'admin@example.com', $hash, 'admin']);
        }
    } catch (Throwable $inner) {
        // ignore seeding errors - DB is usable even without seed
    }
}
