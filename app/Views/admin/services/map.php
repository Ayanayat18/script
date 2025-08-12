<?php use App\Core\CSRF; use App\Core\View; ?>
<h4 class="mb-3">Map API Services</h4>
<form method="post" action="/admin/services/map" class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <?= CSRF::field() ?>
    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead><tr><th>API</th><th>Remote Service</th><th>Price</th><th>Map to Local Service</th></tr></thead>
        <tbody>
          <?php foreach ($apiServices as $as): ?>
            <tr>
              <td><?= (int)$as['api_id'] ?></td>
              <td><?= View::e($as['name']) ?> <small class="text-muted">(<?= View::e($as['remote_service_id']) ?>)</small></td>
              <td>$<?= number_format((float)$as['price'], 2) ?></td>
              <td>
                <select name="map[<?= (int)$as['id'] ?>]" class="form-select form-select-sm">
                  <option value="">— Select —</option>
                  <?php foreach ($services as $s): ?>
                    <option value="<?= (int)$s['id'] ?>"><?= View::e($s['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer text-end">
    <button class="btn btn-primary" type="submit">Save Mapping</button>
  </div>
</form>
<form method="post" action="/admin/services/sync-prices" class="text-end">
  <?= CSRF::field() ?>
  <button class="btn btn-outline-secondary" type="submit">Sync Prices from API</button>
</form>