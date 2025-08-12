<?php use App\Core\CSRF; use App\Core\View; ?>
<h4 class="mb-3">Create Service</h4>
<form method="post" action="/admin/services/create" class="card border-0 shadow-sm">
  <div class="card-body">
    <?= CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label">Category</label>
        <select name="category_id" class="form-select" required>
          <?php foreach ($cats as $c): ?><option value="<?= (int)$c['id'] ?>"><?= View::e($c['name']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6"><label class="form-label">Price</label><input type="number" step="0.01" name="price" class="form-control" value="0.00" required></div>
      <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Enabled</option><option value="0">Disabled</option></select></div>
      <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
    </div>
  </div>
  <div class="card-footer text-end"><button class="btn btn-primary" type="submit">Save</button></div>
</form>