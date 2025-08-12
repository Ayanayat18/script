<?php use App\Core\View; ?>
<h3 class="mb-4">Hello, <?= View::e($user['name'] ?: $user['email']) ?></h3>
<div class="row g-3">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">Wallet Balance</div>
        <div class="fs-3 fw-bold">$<?= number_format((float)$user['wallet_balance'], 2) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">My Orders</div>
        <div class="fs-3 fw-bold"><?= (int)$ordersCount ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">Subscription</div>
        <div class="fs-6 fw-bold">
          <?php if (!empty($user['subscription_expires_at']) && $user['subscription_expires_at'] >= date('Y-m-d')): ?>
            Active until <?= View::e($user['subscription_expires_at']) ?>
          <?php else: ?>
            Expired
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>