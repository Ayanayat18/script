<?php use App\Core\View; ?>
<h4 class="mb-3">My Orders</h4>
<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Service</th>
        <th>Status</th>
        <th>Price</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?= (int)$o['id'] ?></td>
          <td><?= View::e($o['service_name']) ?></td>
          <td><?= View::e($o['status']) ?></td>
          <td>$<?= number_format((float)$o['price'], 2) ?></td>
          <td><?= View::e($o['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>