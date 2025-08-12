<?php use App\Core\CSRF; ?>
<h4 class="mb-3">Create Category</h4>
<form method="post" action="/admin/categories/create" class="card border-0 shadow-sm">
  <div class="card-body">
    <?= CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
      <div class="col-md-3"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
      <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Active</option><option value="0">Disabled</option></select></div>
    </div>
  </div>
  <div class="card-footer text-end"><button class="btn btn-primary" type="submit">Save</button></div>
</form>