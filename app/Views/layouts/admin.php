<?php /** @var callable $content */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? App\Core\View::e($title) : 'Admin' ?> - <?= App\Core\View::e(SITE_NAME) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <style>
    body { min-height: 100vh; }
    .sidebar { width: 240px; }
    @media (max-width: 991px) {
      .sidebar { width: 100%; }
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/admin">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav" aria-controls="topnav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="topnav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/" target="_blank"><i class="fa fa-globe"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="/logout">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid">
  <div class="row">
    <nav class="col-lg-2 col-md-3 d-md-block bg-light sidebar py-3 border-end">
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="/admin">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/users">Users</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/services">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/apis">API Management</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/orders">Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/wallet">Wallet</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/reports">Reports</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/settings">Settings</a></li>
        <li class="nav-item"><a class="nav-link" href="/admin/logs">Logs</a></li>
      </ul>
    </nav>
    <main class="col-lg-10 col-md-9 p-4">
      <?php $content(); ?>
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>