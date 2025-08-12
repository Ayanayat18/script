<?php use App\Core\View; ?>
<h4 class="mb-3">Wallet Transactions</h4>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>User</th>
        <th>Type</th>
        <th>Method</th>
        <th>Amount</th>
        <th>Reference</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($txs as $t): ?>
        <tr>
          <td><?= (int)$t['id'] ?></td>
          <td><?= View::e($t['email']) ?></td>
          <td><span class="badge text-bg-<?= $t['type'] === 'credit' ? 'success' : 'danger' ?>"><?= View::e($t['type']) ?></span></td>
          <td><?= View::e($t['method'] ?: '-') ?></td>
          <td class="fw-bold <?= $t['type'] === 'credit' ? 'text-success' : 'text-danger' ?>">$<?= number_format((float)$t['amount'], 2) ?></td>
          <td><?= View::e($t['reference'] ?: '-') ?></td>
          <td><?= View::e($t['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>