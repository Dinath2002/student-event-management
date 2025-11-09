<?php
require_once __DIR__ . '/../config/auth.php';

// Redirect to events if already logged in
if (is_logged_in()) {
    header('Location: /events.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="gh-page auth-center">
  <form class="card p-4 auth-card shadow-lg" method="post" action="/handle_login.php" onsubmit="return validateLogin(this)">
    <h3 class="text-center mb-4" style="color: #f0f6fc;">Login</h3>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input
        type="email"
        class="form-control"
        name="email"
        placeholder="you@example.com"
        required
      >
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <input
        type="password"
        class="form-control"
        name="password"
        placeholder="Enter your password"
        required
      >
    </div>

    <button class="btn btn-primary w-100" type="submit">Login</button>

    <div class="mt-3 text-center">
      <small>Don’t have an account? <a href="/register.php">Sign up</a></small>
    </div>
  </form>
</div>

<script>
function validateLogin(f) {
  if (!/.+@.+\..+/.test(f.email.value)) {
    alert('Enter a valid email address');
    return false;
  }
  return true;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
