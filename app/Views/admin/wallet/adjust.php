<?php use App\Core\CSRF; ?>
<h4 class="mb-3">Adjust Wallet</h4>
<form method="post" action="/admin/wallet/adjust" class="card border-0 shadow-sm">
  <div class="card-body">
    <?= CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">User ID</label>
        <input type="number" name="user_id" class="form-control" min="1" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Amount (+/-)</label>
        <input type="number" step="0.01" name="amount" class="form-control" required>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-primary" type="submit">Apply</button>
      </div>
    </div>
  </div>
</form>