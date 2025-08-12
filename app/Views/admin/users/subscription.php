<?php use App\Core\CSRF; ?>
<h4 class="mb-3">Update User Subscription</h4>
<form method="post" action="/admin/users/subscription" class="card border-0 shadow-sm">
  <div class="card-body">
    <?= CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">User ID</label>
        <input type="number" name="user_id" class="form-control" min="1" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Months</label>
        <select name="months" class="form-select" required>
          <option value="3">3</option>
          <option value="6">6</option>
          <option value="12">12</option>
        </select>
      </div>
    </div>
  </div>
  <div class="card-footer text-end">
    <button class="btn btn-primary" type="submit">Update</button>
  </div>
</form>