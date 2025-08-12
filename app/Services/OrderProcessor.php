<?php
namespace App\Services;

use App\Core\DB;

class OrderProcessor
{
    public static function submitToApi(int $orderId): void
    {
        $order = DB::fetch('SELECT o.*, s.api_service_id, a.api_id, ap.base_url, ap.api_key, aps.remote_service_id FROM orders o JOIN services s ON s.id = o.service_id LEFT JOIN api_services aps ON aps.id = s.api_service_id LEFT JOIN apis ap ON ap.id = aps.api_id LEFT JOIN apis a ON a.id = aps.api_id WHERE o.id = :id', ['id' => $orderId]);
        if (!$order || empty($order['api_service_id']) || empty($order['api_key']) || empty($order['base_url'])) {
            return; // Not mapped to API or missing creds
        }
        $client = new ApiClient($order['base_url'], $order['api_key']);
        $inputData = json_decode((string)$order['input_data'], true) ?: [];
        $placePath = \App\Core\Settings::get('dhru_place_order_path', '/orders');
        $serviceKey = \App\Core\Settings::get('dhru_req_service_key', 'service_id');
        $inputKey = \App\Core\Settings::get('dhru_req_input_key', 'input');
        // Send request honoring custom keys
        $res = $client->post($placePath, [
            $serviceKey => (string)($order['remote_service_id'] ?? ''),
            $inputKey => (string)($inputData['input'] ?? ''),
        ]);

        $idKey = \App\Core\Settings::get('dhru_res_order_id_key', 'order_id');
        $statusKey = \App\Core\Settings::get('dhru_res_status_key', 'status');
        $remoteId = (string)($res['id'] ?? $res[$idKey] ?? '');
        $remoteStatus = (string)($res[$statusKey] ?? 'processing');
        if ($remoteId) {
            DB::query('UPDATE orders SET api_id = :api, api_order_id = :rid, status = :st, updated_at = NOW() WHERE id = :id', [
                'api' => $order['api_id'], 'rid' => $remoteId, 'st' => self::mapStatus($remoteStatus), 'id' => $orderId,
            ]);
        }
    }

    public static function refreshOrder(int $orderId): void
    {
        $order = DB::fetch('SELECT o.*, ap.base_url, ap.api_key FROM orders o JOIN apis ap ON ap.id = o.api_id WHERE o.id = :id AND o.api_order_id IS NOT NULL', ['id' => $orderId]);
        if (!$order) { return; }
        $client = new ApiClient($order['base_url'], $order['api_key']);
        $statusPath = \App\Core\Settings::get('dhru_order_status_path', '/orders/{id}');
        $statusPath = str_replace('{id}', urlencode((string)$order['api_order_id']), $statusPath);
        $res = $client->orderStatus($statusPath);
        $statusKey = \App\Core\Settings::get('dhru_res_status_key', 'status');
        $resultKey = \App\Core\Settings::get('dhru_res_result_key', 'result');
        $remoteStatus = (string)($res[$statusKey] ?? 'processing');
        $result = $res[$resultKey] ?? null;
        DB::query('UPDATE orders SET status = :st, result_data = :res, updated_at = NOW() WHERE id = :id', [
            'st' => self::mapStatus($remoteStatus),
            'res' => $result ? json_encode($result) : null,
            'id' => $orderId,
        ]);
    }

    public static function mapStatus(string $remote): string
    {
        $remote = strtolower($remote);
        return match ($remote) {
            'queued' => 'pending',
            'in_progress', 'processing' => 'processing',
            'done', 'completed', 'success' => 'completed',
            'partial' => 'partial',
            'canceled', 'cancelled' => 'cancelled',
            'failed', 'error' => 'failed',
            default => 'processing',
        };
    }
}