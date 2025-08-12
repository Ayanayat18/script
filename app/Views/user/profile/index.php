<?php use App\Core\View; ?>
<h4 class="mb-3">Profile</h4>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input class="form-control" value="<?= View::e($user['name'] ?: '') ?>" disabled>
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input class="form-control" value="<?= View::e($user['email']) ?>" disabled>
      </div>
    </div>
    <div class="text-muted mt-3">Password changes can be performed via Forgot Password.</div>
  </div>
</div>