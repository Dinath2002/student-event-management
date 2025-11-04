<?php
require_once __DIR__ . '/../config/auth.php';
if (is_logged_in()) { header('Location: /events.php'); exit; }
include __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-3">Login</h2>
<form class="card p-3" method="post" action="/controllers/handle_login.php" onsubmit="return validateLogin(this)">
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" class="form-control" name="email" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" class="form-control" name="password" required>
  </div>
  <button class="btn btn-primary" type="submit">Login</button>
  <p class="mt-3">Don’t have an account? <a href="/register.php">Sign up here</a></p>
</form>
<script>
function validateLogin(f){ if(!/.+@.+\..+/.test(f.email.value)){ alert('Enter a valid email'); return false; } return true; }
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
