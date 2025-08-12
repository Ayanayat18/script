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
        $payload = [
            'service_id' => $order['remote_service_id'] ?? '',
            'input' => $inputData['input'] ?? '',
        ];
        $res = $client->post('/orders', $payload);
        $remoteId = (string)($res['id'] ?? $res['order_id'] ?? '');
        $remoteStatus = (string)($res['status'] ?? 'processing');
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
        $res = $client->get('/orders/' . urlencode((string)$order['api_order_id']));
        $remoteStatus = (string)($res['status'] ?? 'processing');
        $result = $res['result'] ?? null;
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