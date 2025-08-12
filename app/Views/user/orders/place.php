<?php use App\Core\View; use App\Core\CSRF; ?>
<h4 class="mb-3">Place Order</h4>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= View::e($error) ?></div><?php endif; ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form method="post" action="/place-order">
      <?= CSRF::field() ?>
      <div class="mb-3">
        <label class="form-label">Service</label>
        <select name="service_id" class="form-select" required>
          <option value="">Select a service</option>
          <?php foreach ($services as $s): ?>
            <option value="<?= (int)$s['id'] ?>"><?= View::e($s['category_name'] . ' - ' . $s['name']) ?> — $<?= number_format((float)$s['final_price'], 2) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Order Input</label>
        <input name="input" class="form-control" placeholder="Enter required data" required>
      </div>
      <button class="btn btn-primary" type="submit">Submit Order</button>
    </form>
  </div>
</div>