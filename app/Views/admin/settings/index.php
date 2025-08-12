<?php use App\Core\View; use App\Core\CSRF; ?>
<h4 class="mb-3">Settings</h4>
<div class="row g-3">
  <div class="col-lg-6">
    <form method="post" action="/admin/settings/save" class="card border-0 shadow-sm">
      <div class="card-body">
        <?= CSRF::field() ?>
        <div class="mb-3">
          <label class="form-label">Minimum Balance to Place Order ($)</label>
          <input name="min_balance" type="number" class="form-control" step="0.01" value="<?= View::e($min_balance) ?>">
        </div>
        <div class="mb-2 fw-bold">Dhru-like API Paths</div>
        <div class="mb-3">
          <label class="form-label">Services Path</label>
          <input name="dhru_services_path" class="form-control" value="<?= View::e($dhru_services_path) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Place Order Path</label>
          <input name="dhru_place_order_path" class="form-control" value="<?= View::e($dhru_place_order_path) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Order Status Path</label>
          <input name="dhru_order_status_path" class="form-control" value="<?= View::e($dhru_order_status_path) ?>">
          <div class="form-text">Use {id} placeholder for order ID.</div>
        </div>
        <div class="mb-2 fw-bold">Dhru-like Keys</div>
        <div class="mb-3">
          <label class="form-label">Services List Key</label>
          <input name="dhru_services_list_key" class="form-control" value="<?= View::e($dhru_services_list_key) ?>">
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Request Service Key</label>
            <input name="dhru_req_service_key" class="form-control" value="<?= View::e($dhru_req_service_key) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Request Input Key</label>
            <input name="dhru_req_input_key" class="form-control" value="<?= View::e($dhru_req_input_key) ?>">
          </div>
        </div>
        <div class="row g-3 mt-1">
          <div class="col-md-4">
            <label class="form-label">Response Order ID Key</label>
            <input name="dhru_res_order_id_key" class="form-control" value="<?= View::e($dhru_res_order_id_key) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Response Status Key</label>
            <input name="dhru_res_status_key" class="form-control" value="<?= View::e($dhru_res_status_key) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Response Result Key</label>
            <input name="dhru_res_result_key" class="form-control" value="<?= View::e($dhru_res_result_key) ?>">
          </div>
        </div>
      </div>
      <div class="card-footer text-end"><button class="btn btn-primary" type="submit">Save</button></div>
    </form>
  </div>
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="card-title">All Settings</h6>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead><tr><th>Key</th><th>Value</th></tr></thead>
            <tbody>
              <?php foreach ($settings as $s): ?>
                <tr>
                  <td><?= View::e($s['key']) ?></td>
                  <td><code><?= View::e($s['value']) ?></code></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>