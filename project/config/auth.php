<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function is_logged_in() {
  return isset($_SESSION['user']);
}

function require_login() {
  if (!is_logged_in()) {
    header('Location: /login.php');
    exit;
  }
}
