<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();                  // ensures session cookie is created
}

function is_logged_in(): bool {
  return isset($_SESSION['user']);
}

function require_login(): void {
  if (!is_logged_in()) {
    header('Location: /login.php');
    exit;
}
}