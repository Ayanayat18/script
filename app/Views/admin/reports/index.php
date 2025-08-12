<?php use App\Core\View; ?>
<h4 class="mb-3">Reports</h4>
<div class="row g-3 mb-3">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Total Credits</div><div class="fs-4 fw-bold">$<?= number_format((float)$totals['credits'], 2) ?></div></div></div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Total Debits</div><div class="fs-4 fw-bold">$<?= number_format((float)$totals['debits'], 2) ?></div></div></div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Orders</div><div class="fs-4 fw-bold"><?= (int)$totals['orders'] ?></div></div></div>
  </div>
</div>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h6 class="card-title">Last 14 Days</h6>
    <div class="table-responsive">
      <table class="table table-sm">
        <thead><tr><th>Date</th><th class="text-end">Credits</th><th class="text-end">Debits</th></tr></thead>
        <tbody>
        <?php foreach ($recent as $r): ?>
          <tr>
            <td><?= View::e($r['d']) ?></td>
            <td class="text-end text-success">$<?= number_format((float)$r['credits'], 2) ?></td>
            <td class="text-end text-danger">$<?= number_format((float)$r['debits'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>