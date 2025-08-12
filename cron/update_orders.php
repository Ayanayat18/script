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
    $orders = DB::fetchAll("SELECT id FROM orders WHERE api_id IS NOT NULL AND api_order_id IS NOT NULL AND status IN ('pending','processing','partial') ORDER BY id DESC LIMIT 200");
    $count = 0;
    foreach ($orders as $o) {
        try { App\Services\OrderProcessor::refreshOrder((int)$o['id']); $count++; } catch (\Throwable $e) {}
    }
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['update-orders', 'ok', 'Updated: ' . $count]);
    echo "Update Orders: {$count}\n";
} catch (Throwable $e) {
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['update-orders', 'error', $e->getMessage()]);
    echo "Error: " . $e->getMessage() . "\n";
}