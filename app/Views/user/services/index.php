<?php use App\Core\View; ?>
<h4 class="mb-3">Services</h4>
<div class="row g-3">
  <?php foreach ($services as $s): ?>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body d-flex flex-column">
          <div class="small text-muted mb-1"><?= View::e($s['category_name']) ?></div>
          <h6 class="card-title mb-2"><?= View::e($s['name']) ?></h6>
          <p class="text-muted small flex-grow-1"><?= View::e(substr((string)$s['description'], 0, 120)) ?><?= strlen((string)$s['description']) > 120 ? '…' : '' ?></p>
          <div class="d-flex justify-content-between align-items-center">
            <div class="fw-bold">$<?= number_format((float)$s['price'], 2) ?></div>
            <a href="#" class="btn btn-sm btn-primary disabled" title="Ordering placeholder">Order</a>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>