<?php use App\Core\CSRF; use App\Core\View; ?>
<h4 class="mb-3">Support</h4>
<?php if (!empty($success)): ?>
  <div class="alert alert-success"><?= View::e($success) ?></div>
<?php endif; ?>
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form method="post" action="/support/send">
      <?= CSRF::field() ?>
      <div class="mb-3">
        <label class="form-label">Subject</label>
        <input type="text" name="subject" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea name="message" class="form-control" rows="5" required></textarea>
      </div>
      <button class="btn btn-primary" type="submit">Send</button>
    </form>
  </div>
</div>