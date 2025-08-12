<?php use App\Core\CSRF; use App\Core\View; ?>
<h4 class="mb-3">Edit User</h4>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form method="post" action="/admin/users/update">
      <?= CSRF::field() ?>
      <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input class="form-control" value="<?= View::e($user['name'] ?: '') ?>" disabled>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input class="form-control" value="<?= View::e($user['email']) ?>" disabled>
        </div>
        <div class="col-md-6">
          <label class="form-label">Price Markup (%)</label>
          <input type="number" step="0.01" name="price_markup_percent" class="form-control" value="<?= number_format((float)$user['price_markup_percent'],2,'.','') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="1" <?= (int)$user['status']===1?'selected':'' ?>>Active</option>
            <option value="0" <?= (int)$user['status']===0?'selected':'' ?>>Inactive</option>
          </select>
        </div>
      </div>
      <div class="text-end mt-3">
        <button class="btn btn-primary" type="submit">Save Changes</button>
      </div>
    </form>
  </div>
</div>