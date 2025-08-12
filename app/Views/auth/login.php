<?php use App\Core\CSRF; use App\Core\View; ?>
<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= View::e($error) ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
  <div class="alert alert-success"><?= View::e($success) ?></div>
<?php endif; ?>
<form method="post" action="/login">
  <?= CSRF::field() ?>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <div class="d-grid gap-2">
    <button class="btn btn-primary" type="submit">Login</button>
  </div>
  <div class="text-center mt-3">
    <a href="/forgot">Forgot Password?</a>
  </div>
</form>