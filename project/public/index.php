<?php
require_once __DIR__ . '/../config/auth.php';

// If user is already logged in, go straight to events
if (is_logged_in()) {
    header('Location: /events.php');
    exit;
}

// If not logged in, always use the dedicated login page layout
header('Location: /login.php');
exit;
