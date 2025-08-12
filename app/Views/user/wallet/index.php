<?php use App\Core\View; use App\Core\CSRF; ?>
<h4 class="mb-3">Wallet</h4>
<div class="row g-3">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="text-muted">Current Balance</div>
        <div class="fs-2 fw-bold">$<?= number_format((float)$balance, 2) ?></div>
      </div>
    </div>
    <div class="card border-0 shadow-sm mt-3">
      <div class="card-body">
        <h6 class="card-title">Add Funds</h6>
        <form method="post" action="/wallet/add">
          <?= CSRF::field() ?>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="number" name="amount" class="form-control" step="0.01" min="1" value="10" required>
            <select name="method" class="form-select" style="max-width: 180px;">
              <option value="paypal">PayPal</option>
              <option value="bkash">Bkash</option>
              <option value="nagad">Nagad</option>
              <option value="rocket">Rocket</option>
              <option value="binance">Binance Pay</option>
            </select>
            <button class="btn btn-primary" type="submit">Add Funds</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="card-title">Recent Transactions</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr><th>Date</th><th>Type</th><th>Method</th><th class="text-end">Amount</th></tr>
            </thead>
            <tbody>
              <?php foreach ($txs as $t): ?>
                <tr>
                  <td><?= View::e($t['created_at']) ?></td>
                  <td><?= View::e($t['type']) ?></td>
                  <td><?= View::e($t['method'] ?: '-') ?></td>
                  <td class="text-end <?= $t['type'] === 'credit' ? 'text-success' : 'text-danger' ?>">$<?= number_format((float)$t['amount'], 2) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>