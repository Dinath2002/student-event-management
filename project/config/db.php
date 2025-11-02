<?php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'student_events_db';
$DB_USER = 'webuser';
$DB_PASS = '12345'; // use your password if set

try {
  $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (PDOException $e) {
  die("DB connection failed: " . $e->getMessage());
}
?>
