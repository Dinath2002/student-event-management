<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>SEM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/">SEM</a>
    <div class="navbar-nav">
      <a class="nav-link text-white" href="/events.php">Events</a>
    </div>
    <div class="ms-auto navbar-nav">
      <?php if (empty($_SESSION['user'])): ?>
        <a class="nav-link text-white" href="/index.php">Login</a>
        <a class="nav-link text-white" href="/register.php">Sign up</a>
      <?php else: ?>
        <span class="nav-link text-white">Hi, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
        <a class="nav-link text-white" href="/logout.php">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container py-4">
<?php if (!empty($_SESSION['flash'])): ?>
  <?php foreach ($_SESSION['flash'] as $type => $msgs): foreach ($msgs as $m): ?>
    <div class="alert alert-<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($m) ?></div>
  <?php endforeach; endforeach; $_SESSION['flash'] = []; ?>
<?php endif; ?>
