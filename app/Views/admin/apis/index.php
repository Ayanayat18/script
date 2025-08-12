<?php use App\Core\View; ?>
<h4 class="mb-3">API Management</h4>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Base URL</th>
        <th>Type</th>
        <th>Status</th>
        <th>Last Sync</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($apis as $api): ?>
        <tr>
          <td><?= (int)$api['id'] ?></td>
          <td><?= View::e($api['name']) ?></td>
          <td><a href="<?= View::e($api['base_url']) ?>" target="_blank">Link</a></td>
          <td><?= View::e($api['type']) ?></td>
          <td><?= (int)$api['status'] === 1 ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-secondary">Disabled</span>' ?></td>
          <td><?= View::e($api['last_sync_at'] ?: '-') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>