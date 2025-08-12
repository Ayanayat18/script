<?php
namespace App\Payments;

use App\Interfaces\PaymentGatewayInterface;

class BinancePayGateway implements PaymentGatewayInterface
{
    public function getName(): string { return 'Binance Pay'; }

    public function createPayment(int $userId, float $amount, array $meta = []): array
    {
        return [
            'status' => 'redirect',
            'redirect_url' => '/wallet?success=1',
            'reference' => 'BP-' . time() . '-' . $userId,
        ];
    }

    public function handleCallback(array $requestData): array
    {
        return [ 'status' => 'success', 'amount' => (float)($requestData['amount'] ?? 0), 'reference' => (string)($requestData['reference'] ?? '') ];
    }
}