<div class="container">
  <div class="row align-items-center">
    <div class="col-lg-6">
      <h1 class="display-5 fw-bold mb-3"><?= App\Core\View::e(SITE_NAME) ?></h1>
      <p class="lead">All-in-one GSM services platform. Fast API integration, order management, and wallet system.</p>
      <a href="/login" class="btn btn-primary btn-lg me-2">Login</a>
      <a href="#features" class="btn btn-outline-secondary btn-lg">Learn more</a>
    </div>
    <div class="col-lg-6 text-center">
      <img src="https://images.unsplash.com/photo-1518779578993-ec3579fee39f?auto=format&fit=crop&w=1000&q=60" class="img-fluid rounded shadow" alt="GSM Services">
    </div>
  </div>
  <hr class="my-5">
  <div id="features" class="row g-4">
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title"><i class="fa fa-plug me-2"></i>API Integrations</h5>
          <p class="card-text">Connect multiple providers and sync services/prices.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title"><i class="fa fa-wallet me-2"></i>Wallet & Payments</h5>
          <p class="card-text">Top-up with Bkash, Nagad, Rocket, Binance Pay, PayPal.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title"><i class="fa fa-shield-halved me-2"></i>Secure</h5>
          <p class="card-text">CSRF, XSS, SQLi protection and optional 2FA.</p>
        </div>
      </div>
    </div>
  </div>
</div>