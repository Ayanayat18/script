<?php use App\Core\View; ?>
<h4 class="mb-3">Settings</h4>
<div class="table-responsive">
  <table class="table table-striped">
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