<?php use App\Core\CSRF; use App\Core\View; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Service Categories</h4>
  <a href="/admin/categories/create" class="btn btn-primary btn-sm">Create</a>
</div>
<div class="table-responsive">
  <table class="table table-striped align-middle">
    <thead><tr><th>ID</th><th>Name</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($cats as $c): ?>
        <tr>
          <td><?= (int)$c['id'] ?></td>
          <td><?= View::e($c['name']) ?></td>
          <td><?= (int)$c['sort_order'] ?></td>
          <td><?= (int)$c['status'] === 1 ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-secondary">Disabled</span>' ?></td>
          <td class="d-flex gap-2">
            <a href="/admin/categories/edit?id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
            <form method="post" action="/admin/categories/delete" onsubmit="return confirm('Delete this category?')">
              <?= CSRF::field() ?>
              <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>