<?php include __DIR__ . '/../includes/header.php'; ?>
<h2 class="mb-3">Create an Account</h2>
<form class="card p-3" method="post" action="/controllers/handle_register.php" onsubmit="return validateSignup(this)">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Full Name</label>
      <input type="text" class="form-control" name="name" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Student ID (optional)</label>
      <input type="text" class="form-control" name="student_id">
    </div>
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="email" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Password</label>
      <input type="password" class="form-control" name="password" minlength="6" required>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-success" type="submit">Sign up</button>
  </div>
</form>
<script>
function validateSignup(f){
  if (f.password.value.length < 6) { alert('Password must be at least 6 characters.'); return false; }
  return true;
}
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
