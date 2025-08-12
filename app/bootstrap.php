<?php
declare(strict_types=1);

// Define base paths
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', BASE_PATH . '/public');
}

// Load config or redirect to installer when in web context
if (!file_exists(BASE_PATH . '/config.php')) {
    if (php_sapi_name() !== 'cli') {
        header('Location: /install');
        exit;
    } else {
        throw new RuntimeException('config.php missing. Run /install');
    }
}
require_once BASE_PATH . '/config.php';

// Session name configuration
if (!headers_sent() && session_status() === PHP_SESSION_NONE) {
    if (defined('SESSION_NAME') && SESSION_NAME) {
        session_name(SESSION_NAME);
    }
    session_start();
}

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = BASE_PATH . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize DB
App\Core\DB::init([
    'host' => DB_HOST,
    'database' => DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => DB_CHARSET ?? 'utf8mb4',
]);

// Seed default admin if none exists
try {
    $count = App\Core\DB::fetch('SELECT COUNT(*) AS c FROM users');
    if ($count && (int)$count['c'] === 0) {
        $email = defined('DEFAULT_ADMIN_EMAIL') ? DEFAULT_ADMIN_EMAIL : 'admin@example.com';
        $pass = defined('DEFAULT_ADMIN_PASSWORD') ? DEFAULT_ADMIN_PASSWORD : 'Password@123';
        App\Core\DB::insert('INSERT INTO users (name, email, password_hash, role, status, wallet_balance, created_at, updated_at) VALUES (:n,:e,:p,\'super_admin\',1,0,NOW(),NOW())', [
            'n' => 'Administrator',
            'e' => $email,
            'p' => password_hash($pass, PASSWORD_DEFAULT),
        ]);
    }
} catch (\Throwable $e) {
    // ignore seeding errors
}