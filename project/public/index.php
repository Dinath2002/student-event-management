<?php
require_once __DIR__ . '/../config/auth.php';

if (is_logged_in()) {
    header('Location: /events.php');
    exit;
}

header('Location: /login.php');
exit;
