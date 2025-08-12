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
    // Iterate configured APIs and upsert api_services
    $apis = DB::fetchAll('SELECT * FROM apis WHERE status = 1');
    $updated = 0;
    foreach ($apis as $api) {
        $client = new App\Services\ApiClient($api['base_url'], $api['api_key']);
        try {
            $list = $client->fetchServices();
            if (isset($list['data']) && is_array($list['data'])) { $list = $list['data']; }
            foreach ($list as $item) {
                $remoteId = (string)($item['id'] ?? $item['service_id'] ?? '');
                $name = (string)($item['name'] ?? $item['service'] ?? '');
                $price = (float)($item['price'] ?? 0);
                if (!$remoteId || !$name) { continue; }
                $exists = DB::fetch('SELECT id FROM api_services WHERE api_id = :api AND remote_service_id = :rid LIMIT 1', ['api' => $api['id'], 'rid' => $remoteId]);
                if ($exists) {
                    DB::query('UPDATE api_services SET name = :n, price = :p, updated_at = NOW() WHERE id = :id', ['n' => $name, 'p' => $price, 'id' => $exists['id']]);
                } else {
                    DB::insert('INSERT INTO api_services (api_id, remote_service_id, name, price, status, created_at, updated_at) VALUES (:api,:rid,:n,:p,1,NOW(),NOW())', ['api' => $api['id'], 'rid' => $remoteId, 'n' => $name, 'p' => $price]);
                }
                $updated++;
            }
            DB::query('UPDATE apis SET last_sync_at = NOW() WHERE id = :id', ['id' => $api['id']]);
        } catch (\Throwable $e) {
            DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['sync-apis', 'error', 'API ' . $api['name'] . ': ' . $e->getMessage()]);
        }
    }
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['sync-apis', 'ok', 'Services updated: ' . $updated]);
    echo "Sync APIs: updated {$updated}\n";
} catch (Throwable $e) {
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['sync-apis', 'error', $e->getMessage()]);
    echo "Error: " . $e->getMessage() . "\n";
}