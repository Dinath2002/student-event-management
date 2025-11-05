<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
function is_logged_in(): bool { return isset($_SESSION['user']); }
function require_login(): void { if (!is_logged_in()) { header('Location: /index.php'); exit; } }
