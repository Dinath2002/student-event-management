<?php
require_once __DIR__ . '/../config/auth.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SEM - Student Event Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid px-4">
    <a class="navbar-brand d-flex align-items-center text-light" href="/events.php">
      <img src="/assets/img/footer-banner.png" alt="SEM Logo" class="nav-logo me-2">
      <span class="fw-bold">SEM</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link<?= ($_SERVER['REQUEST_URI'] === '/events.php') ? ' active' : '' ?>" href="/events.php">Events</a>
        </li>

        <?php if (is_admin()): ?>
          <li class="nav-item">
            <a class="nav-link<?= ($_SERVER['REQUEST_URI'] === '/admin_add_event.php') ? ' active' : '' ?>" href="/admin_add_event.php">
              Add Event
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link<?= ($_SERVER['REQUEST_URI'] === '/admin_registrations.php') ? ' active' : '' ?>" href="/admin_registrations.php">
              Registrations
            </a>
          </li>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav ms-auto">
        <?php if (is_logged_in()): ?>
          <li class="nav-item">
            <span class="nav-link disabled">
              <?= htmlspecialchars(current_user()['name']) ?>
              <?php if (is_admin()): ?> (Admin)<?php endif; ?>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link<?= ($_SERVER['REQUEST_URI'] === '/login.php' || $_SERVER['REQUEST_URI'] === '/index.php') ? ' active' : '' ?>" href="/login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?= ($_SERVER['REQUEST_URI'] === '/register.php') ? ' active' : '' ?>" href="/register.php">Sign up</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<?php
// Flash message handler
if (!empty($_SESSION['flash'])): ?>
  <div class="container mt-3">
    <?php foreach ($_SESSION['flash'] as $type => $messages): ?>
      <?php foreach ($messages as $msg): ?>
        <div class="alert alert-<?= htmlspecialchars($type) ?> mb-2">
          <?= htmlspecialchars($msg) ?>
        </div>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="container gh-page">
