<?php use App\Core\View; ?>
<h4 class="mb-3">Orders</h4>
<div class="table-responsive">
  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>User</th>
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
          <td><?= View::e($o['email']) ?></td>
          <td><?= View::e($o['service_name']) ?></td>
          <td>
            <?php $status = $o['status']; $map = [
              'pending' => 'warning', 'processing' => 'info', 'completed' => 'success', 'partial' => 'secondary', 'cancelled' => 'dark', 'failed' => 'danger'
            ]; ?>
            <span class="badge text-bg-<?= $map[$status] ?? 'secondary' ?>"><?= View::e($status) ?></span>
          </td>
          <td>$<?= number_format((float)$o['price'], 2) ?></td>
          <td><?= View::e($o['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>