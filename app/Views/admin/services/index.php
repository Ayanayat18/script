<?php use App\Core\View; ?>
<h4 class="mb-3">Services</h4>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <a href="/admin/categories" class="btn btn-outline-secondary btn-sm">Categories</a>
    <a href="/admin/services/create" class="btn btn-primary btn-sm">Create Service</a>
    <a href="/admin/services/map" class="btn btn-outline-primary btn-sm">Map API Services</a>
  </div>
</div>
<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Category</th>
        <th>Name</th>
        <th>Price</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($services as $s): ?>
        <tr>
          <td><?= (int)$s['id'] ?></td>
          <td><?= View::e($s['category_name']) ?></td>
          <td><?= View::e($s['name']) ?></td>
          <td>$<?= number_format((float)$s['price'], 2) ?></td>
          <td><?= (int)$s['status'] === 1 ? '<span class="badge text-bg-success">Enabled</span>' : '<span class="badge text-bg-secondary">Disabled</span>' ?></td>
          <td class="d-flex gap-2">
            <a href="/admin/services/edit?id=<?= (int)$s['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
            <form method="post" action="/admin/services/delete" onsubmit="return confirm('Delete this service?')">
              <?= App\Core\CSRF::field() ?>
              <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>