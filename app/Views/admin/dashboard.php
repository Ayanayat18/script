<?php use App\Core\View; ?>
<h3 class="mb-4">Welcome to Admin Dashboard</h3>
<div class="row g-3">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Users</div>
            <div class="fs-3 fw-bold"><?= (int)$totals['users'] ?></div>
          </div>
          <i class="fa fa-users fa-2x text-primary"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Orders</div>
            <div class="fs-3 fw-bold"><?= (int)$totals['orders'] ?></div>
          </div>
          <i class="fa fa-list-check fa-2x text-success"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Revenue</div>
            <div class="fs-3 fw-bold">$<?= number_format((float)$totals['revenue'], 2) ?></div>
          </div>
          <i class="fa fa-dollar-sign fa-2x text-warning"></i>
        </div>
      </div>
    </div>
  </div>
</div>