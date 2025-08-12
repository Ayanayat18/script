<?php use App\Core\CSRF; use App\Core\View; ?>
<h4 class="mb-3">Edit Category</h4>
<form method="post" action="/admin/categories/update" class="card border-0 shadow-sm">
  <div class="card-body">
    <?= CSRF::field() ?>
    <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" value="<?= View::e($cat['name']) ?>" required></div>
      <div class="col-md-3"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="<?= (int)$cat['sort_order'] ?>"></div>
      <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1" <?= (int)$cat['status']===1?'selected':'' ?>>Active</option><option value="0" <?= (int)$cat['status']===0?'selected':'' ?>>Disabled</option></select></div>
    </div>
  </div>
  <div class="card-footer text-end"><button class="btn btn-primary" type="submit">Update</button></div>
</form>