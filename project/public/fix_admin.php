<?php
require_once __DIR__ . '/../config/db.php';
$email = 'admin@example.com';
$hash  = '$2y$10$3i8t8gQ0mXbW3T0p6y3kNuey8w1jYh7w7nqM9bXG8kqkN4v7c7iN6';

$upd = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
$upd->execute([$hash, $email]);

$row = $pdo->prepare('SELECT email, password, LENGTH(password) len, HEX(password) hex FROM users WHERE email=?');
$row->execute([$email]);
var_dump($row->fetch());
