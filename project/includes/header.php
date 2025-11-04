<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Student Event Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a href="/" class="navbar-brand">SEM</a>
    <div class="navbar-nav">
      <a href="/events.php" class="nav-link text-white">Events</a>
    </div>
    <div class="ms-auto navbar-nav">
      <?php if (empty($_SESSION['user'])): ?>
        <a href="/login.php" class="nav-link text-white">Login</a>
        <a href="/register.php" class="nav-link text-white">Sign up</a>
      <?php else: ?>
        <span class="nav-link text-white">Hi, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
        <a href="/logout.php" class="nav-link text-white">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container py-4">
  <?php if (!empty($_SESSION['flash'])): ?>
    <?php foreach ($_SESSION['flash'] as $type => $msgs): ?>
      <?php foreach ($msgs as $m): ?>
        <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($m); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endforeach; ?>
    <?php endforeach; $_SESSION['flash'] = []; ?>
  <?php endif; ?>
