<?php use App\Core\View; ?>
<h4 class="mb-3">Subscription</h4>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <p>
      <?php if ($expires && $expires >= date('Y-m-d')): ?>
        Active until <strong><?= View::e($expires) ?></strong>
      <?php else: ?>
        <span class="text-danger">Your subscription is expired.</span>
      <?php endif; ?>
    </p>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-primary btn-sm" disabled>Renew 3 months</button>
      <button class="btn btn-outline-primary btn-sm" disabled>Renew 6 months</button>
      <button class="btn btn-primary btn-sm" disabled>Renew 12 months</button>
    </div>
  </div>
</div>