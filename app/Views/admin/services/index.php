<?php use App\Core\View; ?>
<h4 class="mb-3">Services</h4>
<div class="table-responsive">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Category</th>
        <th>Name</th>
        <th>Price</th>
        <th>Status</th>
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
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>