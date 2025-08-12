<?php use App\Core\CSRF; ?>
<h4 class="mb-3">Add API</h4>
<form method="post" action="/admin/apis/create" class="card border-0 shadow-sm">
  <div class="card-body">
    <?= CSRF::field() ?>
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Type</label>
        <input name="type" class="form-control" value="generic" required>
      </div>
      <div class="col-12">
        <label class="form-label">Base URL</label>
        <input name="base_url" class="form-control" placeholder="https://provider.example/api" required>
      </div>
      <div class="col-12">
        <label class="form-label">API Key</label>
        <input name="api_key" class="form-control" required>
      </div>
    </div>
  </div>
  <div class="card-footer text-end">
    <button class="btn btn-primary" type="submit">Save</button>
  </div>
</form>