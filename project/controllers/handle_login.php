<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/db.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

try {
  $stmt = $pdo->prepare("SELECT user_id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['flash']['danger'][] = 'Invalid email or password.';
    header('Location: /login.php');  // back to login with flash
    exit;
  }

  // write session and redirect
  $_SESSION['user'] = [
    'user_id' => $user['user_id'],
    'name'    => $user['name'],
    'email'   => $user['email'],
    'role'    => $user['role']
  ];
  header('Location: /events.php');
  exit;

} catch (Throwable $e) {
  $_SESSION['flash']['danger'][] = 'Login error: '.$e->getMessage();
  header('Location: /login.php');
  exit;
}
