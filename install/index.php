<?php
session_start();
$basePath = dirname(__DIR__);

function view_header(string $title)
{
    echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>Installer - ' . htmlspecialchars($title) . '</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '</head><body class="bg-light"><div class="container py-5">';
    echo '<div class="mb-4"><h2>Installation Wizard</h2><div class="text-muted">Setup your application</div></div>';
}

function view_footer()
{
    echo '</div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>';
}

function progress_bar(int $step, int $total = 8)
{
    $percent = (int) floor(($step - 1) / $total * 100);
    echo '<div class="progress mb-4"><div class="progress-bar" role="progressbar" style="width:' . $percent . '%">Step ' . $step . ' / ' . $total . '</div></div>';
}

if (file_exists($basePath . '/config.php')) {
    header('Location: /');
    exit;
}

$step = isset($_GET['step']) ? (int) $_GET['step'] : 1;

// Step 1: Environment check
if ($step === 1) {
    view_header('Environment Check');
    progress_bar(1);
    $requirements = [
        'PHP >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
        'mysqli' => extension_loaded('mysqli'),
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'curl' => extension_loaded('curl'),
        'openssl' => extension_loaded('openssl'),
        'json' => extension_loaded('json'),
        'mbstring' => extension_loaded('mbstring'),
        'zip' => extension_loaded('zip'),
        'gd' => extension_loaded('gd'),
        'fileinfo' => extension_loaded('fileinfo'),
        'allow_url_fopen' => (bool) ini_get('allow_url_fopen'),
        'write permissions (root)' => is_writable($basePath),
    ];
    echo '<div class="card"><div class="card-body">';
    echo '<ul class="list-group list-group-flush">';
    $ok = true;
    foreach ($requirements as $name => $passed) {
        $ok = $ok && $passed;
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">' . htmlspecialchars($name) . '<span class="badge ' . ($passed ? 'text-bg-success' : 'text-bg-danger') . '">' . ($passed ? 'OK' : 'Missing') . '</span></li>';
    }
    echo '</ul>';
    echo '</div></div>';
    echo '<div class="mt-3 text-end">';
    if ($ok) {
        echo '<a class="btn btn-primary" href="?step=2">Continue</a>';
    } else {
        echo '<div class="alert alert-danger mt-3">Please resolve the missing requirements and reload this page.</div>';
    }
    echo '</div>';
    view_footer();
    exit;
}

// Step 2: License
if ($step === 2) {
    view_header('License Agreement');
    progress_bar(2);
    $license = @file_get_contents($basePath . '/license.txt');
    echo '<div class="card"><div class="card-body" style="max-height: 300px; overflow:auto; white-space: pre-wrap;">' . htmlspecialchars($license ?: 'No license.txt found.') . '</div></div>';
    echo '<form class="mt-3" method="post" action="?step=3">';
    echo '<div class="form-check"><input class="form-check-input" type="checkbox" name="agree" required id="agree"><label class="form-check-label" for="agree">I agree to the license terms</label></div>';
    echo '<div class="text-end mt-3"><button class="btn btn-primary" type="submit">Accept & Continue</button></div></form>';
    view_footer();
    exit;
}

// Step 3: Database config
if ($step === 3) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['agree'])) {
        header('Location: ?step=2');
        exit;
    }
    view_header('Database');
    progress_bar(3);
    echo '<form method="post" action="?step=4">';
    echo '<div class="row g-3">';
    echo '<div class="col-md-6"><label class="form-label">DB Host</label><input class="form-control" name="db_host" value="localhost" required></div>';
    echo '<div class="col-md-6"><label class="form-label">DB Name</label><input class="form-control" name="db_name" required></div>';
    echo '<div class="col-md-6"><label class="form-label">DB User</label><input class="form-control" name="db_user" required></div>';
    echo '<div class="col-md-6"><label class="form-label">DB Password</label><input type="password" class="form-control" name="db_pass"></div>';
    echo '</div><div class="text-end mt-3"><button class="btn btn-primary" type="submit">Continue</button></div></form>';
    view_footer();
    exit;
}

// Step 4: Site settings
if ($step === 4) {
    $_SESSION['db'] = [
        'host' => $_POST['db_host'] ?? '',
        'name' => $_POST['db_name'] ?? '',
        'user' => $_POST['db_user'] ?? '',
        'pass' => $_POST['db_pass'] ?? '',
    ];
    view_header('Site Settings');
    progress_bar(4);
    $url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
    echo '<form method="post" action="?step=5">';
    echo '<div class="row g-3">';
    echo '<div class="col-md-6"><label class="form-label">Site Name</label><input class="form-control" name="site_name" value="GSM Theme Clone" required></div>';
    echo '<div class="col-md-6"><label class="form-label">Site URL</label><input class="form-control" name="site_url" value="' . htmlspecialchars($url) . '" required></div>';
    echo '<div class="col-md-6"><label class="form-label">Admin Email</label><input type="email" class="form-control" name="admin_email" value="admin@example.com" required></div>';
    echo '<div class="col-md-6"><label class="form-label">Admin Password</label><input type="password" class="form-control" name="admin_password" value="Password@123" required></div>';
    echo '</div><div class="text-end mt-3"><button class="btn btn-primary" type="submit">Continue</button></div></form>';
    view_footer();
    exit;
}

// Step 5: Write config.php
if ($step === 5) {
    $_SESSION['site'] = [
        'name' => $_POST['site_name'] ?? 'GSM Theme',
        'url' => $_POST['site_url'] ?? '',
        'admin_email' => $_POST['admin_email'] ?? '',
        'admin_password' => $_POST['admin_password'] ?? '',
    ];

    $db = $_SESSION['db'] ?? [];
    $site = $_SESSION['site'] ?? [];

    $config = "<?php\n";
    $config .= "const DB_HOST = '" . addslashes($db['host']) . "';\n";
    $config .= "const DB_NAME = '" . addslashes($db['name']) . "';\n";
    $config .= "const DB_USER = '" . addslashes($db['user']) . "';\n";
    $config .= "const DB_PASS = '" . addslashes($db['pass']) . "';\n";
    $config .= "const DB_CHARSET = 'utf8mb4';\n\n";
    $config .= "const APP_ENV = 'production';\n";
    $config .= "const APP_DEBUG = false;\n";
    $config .= "const APP_URL = '" . addslashes($site['url']) . "';\n";
    $config .= "const SITE_NAME = '" . addslashes($site['name']) . "';\n\n";
    $config .= "const APP_KEY = '" . bin2hex(random_bytes(16)) . "';\n";
    $config .= "const SESSION_NAME = 'gsm_app_session';\n";
    $config .= "const CSRF_TOKEN_KEY = 'csrf_token';\n\n";
    $config .= "const MAIL_DRIVER = 'smtp';\n";
    $config .= "const MAIL_HOST = '';\n";
    $config .= "const MAIL_PORT = 587;\n";
    $config .= "const MAIL_ENCRYPTION = 'tls';\n";
    $config .= "const MAIL_USERNAME = '';\n";
    $config .= "const MAIL_PASSWORD = '';\n";
    $config .= "const MAIL_FROM_ADDRESS = 'no-reply@' . parse_url(APP_URL, PHP_URL_HOST);\n";
    $config .= "const MAIL_FROM_NAME = SITE_NAME;\n\n";
    $config .= "const TELEGRAM_BOT_TOKEN = '';\n";
    $config .= "const TELEGRAM_CHAT_ID = '';\n\n";
    $config .= "const DEFAULT_ADMIN_EMAIL = '" . addslashes($site['admin_email']) . "';\n";
    $config .= "const DEFAULT_ADMIN_PASSWORD = '" . addslashes($site['admin_password']) . "';\n";
    $config .= "const CRON_SECRET = '" . bin2hex(random_bytes(16)) . "';\n";

    file_put_contents($basePath . '/config.php', $config);

    header('Location: ?step=6');
    exit;
}

// Step 6: Import database.sql
if ($step === 6) {
    $db = $_SESSION['db'] ?? [];
    view_header('Database Import');
    progress_bar(6);
    $dsn = 'mysql:host=' . $db['host'] . ';dbname=' . $db['name'] . ';charset=utf8mb4';
    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $sql = file_get_contents($basePath . '/database.sql');
        $pdo->exec($sql);
        echo '<div class="alert alert-success">Database imported successfully.</div>';
        echo '<div class="text-end"><a class="btn btn-primary" href="?step=7">Continue</a></div>';
    } catch (Throwable $e) {
        echo '<div class="alert alert-danger">Import failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
        echo '<a class="btn btn-secondary" href="?step=6">Retry</a>';
    }
    view_footer();
    exit;
}

// Step 7: Create admin account
if ($step === 7) {
    require $basePath . '/config.php';
    view_header('Create Admin');
    progress_bar(7);
    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $email = DEFAULT_ADMIN_EMAIL;
        $pass = DEFAULT_ADMIN_PASSWORD;
        $exists = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $exists->execute([$email]);
        if (!$exists->fetch()) {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, status, wallet_balance, created_at, updated_at) VALUES (?, ?, ?, ?, 1, 0, NOW(), NOW())');
            $stmt->execute(['Administrator', $email, password_hash($pass, PASSWORD_DEFAULT), 'super_admin']);
        }
        echo '<div class="alert alert-success">Admin account is ready.</div>';
        echo '<div class="text-end"><a class="btn btn-primary" href="?step=8">Finish</a></div>';
    } catch (Throwable $e) {
        echo '<div class="alert alert-danger">Failed to create admin: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
    view_footer();
    exit;
}

// Step 8: Cleanup installer
if ($step === 8) {
    view_header('Finish');
    progress_bar(8);
    $renamed = false;
    $installDir = __DIR__;
    $newName = dirname(__DIR__) . '/_install_completed_' . date('Ymd_His');
    if (@rename($installDir, $newName)) {
        $renamed = true;
    }
    echo '<div class="card"><div class="card-body">';
    echo '<h5>Installation Successful</h5>';
    echo '<p>Your application is ready.</p>';
    if ($renamed) {
        echo '<p class="text-success">Installer has been renamed for security.</p>';
    } else {
        echo '<p class="text-warning">Please delete or rename the /install folder manually.</p>';
    }
    echo '<div class="d-flex gap-2">';
    echo '<a class="btn btn-success" href="/">Go to Website</a>';
    echo '<a class="btn btn-primary" href="/admin">Go to Admin Panel</a>';
    echo '</div>';
    echo '</div></div>';
    view_footer();
    exit;
}