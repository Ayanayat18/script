<?php use App\Core\View; ?>
<h4 class="mb-3">Cron Logs</h4>
<div class="table-responsive">
  <table class="table table-striped">
    <thead><tr><th>Time</th><th>Job</th><th>Status</th><th>Message</th></tr></thead>
    <tbody>
      <?php foreach ($logs as $log): ?>
        <tr>
          <td><?= View::e($log['ran_at']) ?></td>
          <td><?= View::e($log['job']) ?></td>
          <td><span class="badge text-bg-<?= $log['status'] === 'ok' ? 'success' : 'danger' ?>"><?= View::e($log['status']) ?></span></td>
          <td><?= View::e($log['message'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>