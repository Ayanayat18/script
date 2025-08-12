<?php use App\Core\CSRF; use App\Core\View; ?>
<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= View::e($error) ?></div>
<?php endif; ?>
<form method="post" action="/reset">
  <?= CSRF::field() ?>
  <input type="hidden" name="token" value="<?= View::e($token ?? '') ?>">
  <div class="mb-3">
    <label class="form-label">New Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <div class="d-grid gap-2">
    <button class="btn btn-primary" type="submit">Reset Password</button>
  </div>
</form>