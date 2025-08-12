<?php
require dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\DB;

if (php_sapi_name() !== 'cli') {
    if (!isset($_GET['secret']) || $_GET['secret'] !== CRON_SECRET) {
        http_response_code(403);
        exit('Forbidden');
    }
}

try {
    // Expire users with past subscription_expires_at
    DB::query("UPDATE users SET status = 0 WHERE subscription_expires_at IS NOT NULL AND subscription_expires_at < CURDATE()");
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['subscriptions-check', 'ok', 'Expired subscriptions updated']);
    echo "Subscriptions check: done\n";
} catch (Throwable $e) {
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['subscriptions-check', 'error', $e->getMessage()]);
    echo "Error: " . $e->getMessage() . "\n";
}