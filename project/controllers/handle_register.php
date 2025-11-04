<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/db.php';

$name       = trim($_POST['name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$student_id = trim($_POST['student_id'] ?? '');
$password   = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
  $_SESSION['flash']['danger'][] = 'All fields (except Student ID) are required.';
  header('Location: /register.php'); exit;
}

try {
  // check existing email
  $check = $pdo->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
  $check->execute([$email]);
  if ($check->fetch()) {
    $_SESSION['flash']['warning'][] = 'Email is already registered.';
    header('Location: /register.php'); exit;
  }

  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("INSERT INTO users (name, email, password, student_id, role) VALUES (?, ?, ?, ?, 'student')");
  $stmt->execute([$name, $email, $hash, $student_id ?: null]);

  $_SESSION['flash']['success'][] = 'Registration successful. Please login.';
  header('Location: /login.php'); exit;

} catch (Throwable $e) {
  $_SESSION['flash']['danger'][] = 'Error: ' . $e->getMessage();
  header('Location: /register.php'); exit;
}
