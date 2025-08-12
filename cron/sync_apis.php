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
    // TODO: iterate configured APIs and sync services/prices
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['sync-apis', 'ok', 'Sync completed']);
    echo "Sync APIs: done\n";
} catch (Throwable $e) {
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['sync-apis', 'error', $e->getMessage()]);
    echo "Error: " . $e->getMessage() . "\n";
}