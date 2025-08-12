<?php use App\Core\View; ?>
<h4 class="mb-3">Notifications</h4>
<div class="list-group">
  <?php foreach ($items as $n): ?>
    <div class="list-group-item">
      <div class="d-flex justify-content-between">
        <strong><?= View::e($n['title']) ?></strong>
        <small class="text-muted"><?= View::e($n['created_at']) ?></small>
      </div>
      <div><?= nl2br(View::e($n['message'])) ?></div>
    </div>
  <?php endforeach; ?>
  <?php if (empty($items)): ?>
    <div class="text-muted">No notifications</div>
  <?php endif; ?>
</div>