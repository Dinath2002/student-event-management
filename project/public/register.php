<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="auth-main">
  <form class="card p-4 auth-card" method="post" action="/handle_register.php" onsubmit="return validateSignup(this)">
    <h3 class="mb-3 text-center">Create an account</h3>

    <div class="row g-3">
      <div class="col-12">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-control" name="name" required>
      </div>

      <div class="col-12">
        <label class="form-label">Student ID (optional)</label>
        <input type="text" class="form-control" name="student_id">
      </div>

      <div class="col-12">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>

      <div class="col-12">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" minlength="6" required>
      </div>
    </div>

    <button class="btn btn-primary w-100 mt-3" type="submit">Sign up</button>

    <div class="mt-3 text-center">
      <small>Already have an account? <a href="/login.php">Sign in</a></small>
    </div>
  </form>
</main>

<script>
function validateSignup(f){
  if (f.password.value.length < 6) { 
    alert('Password must be at least 6 characters.'); 
    return false; 
  }
  return true;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
