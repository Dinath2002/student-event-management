<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="gh-page auth-center">
  <form class="card p-4 auth-card" method="post" action="/controllers/handle_login.php" onsubmit="return validateLogin(this)">
    <h3 class="mb-3">Sign in</h3>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" class="form-control" name="password" required>
    </div>

    <button class="btn btn-primary w-100" type="submit">Sign in</button>

    <div class="mt-3 text-center">
      <small>New here? <a href="/register.php">Create an account</a></small>
    </div>
  </form>
</div>

<script>
function validateLogin(f){
  if(!/.+@.+\..+/.test(f.email.value)){ alert('Enter a valid email'); return false; }
  return true;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
