<?php use App\Core\View; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Users</h4>
</div>
<div class="table-responsive">
  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Wallet</th>
        <th>Subscription</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= (int)$u['id'] ?></td>
          <td><?= View::e($u['name'] ?: '-') ?></td>
          <td><?= View::e($u['email']) ?></td>
          <td><span class="badge text-bg-secondary"><?= View::e($u['role']) ?></span></td>
          <td>
            <?php if ((int)$u['status'] === 1): ?>
              <span class="badge text-bg-success">Active</span>
            <?php else: ?>
              <span class="badge text-bg-danger">Inactive</span>
            <?php endif; ?>
          </td>
          <td>$<?= number_format((float)$u['wallet_balance'], 2) ?></td>
          <td><?= View::e($u['subscription_expires_at'] ?: '-') ?></td>
          <td><?= View::e($u['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>