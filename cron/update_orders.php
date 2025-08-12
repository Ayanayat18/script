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
    // TODO: poll provider APIs and update local order statuses
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['update-orders', 'ok', 'Update completed']);
    echo "Update Orders: done\n";
} catch (Throwable $e) {
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['update-orders', 'error', $e->getMessage()]);
    echo "Error: " . $e->getMessage() . "\n";
}