<?php
require_once __DIR__ . '/../config/auth.php';

// Redirect if already logged in
if (is_logged_in()) {
    header('Location: /home.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="gh-auth-wrapper d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
  <div class="gh-card p-4 auth-card" style="width: 420px;">
    <h2 class="text-center mb-4" style="color: #f0f6fc;">Login</h2>

    <form method="post" action="/handle_login.php" onsubmit="return validateLogin(this)">
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
    </form>

    <p class="gh-auth-subtext mt-3">
      Don’t have an account? <a href="/register.php">Sign up</a>
    </p>
  </div>
</div>

<script>
function validateLogin(f) {
  if (!/.+@.+\..+/.test(f.email.value)) {
    alert('Enter a valid email');
    return false;
  }
  return true;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
