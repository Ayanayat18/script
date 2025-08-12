<?php
namespace App\Payments;

use App\Interfaces\PaymentGatewayInterface;

class NagadGateway implements PaymentGatewayInterface
{
    public function getName(): string { return 'Nagad'; }

    public function createPayment(int $userId, float $amount, array $meta = []): array
    {
        return [
            'status' => 'redirect',
            'redirect_url' => '/wallet?success=1',
            'reference' => 'NG-' . time() . '-' . $userId,
        ];
    }

    public function handleCallback(array $requestData): array
    {
        return [ 'status' => 'success', 'amount' => (float)($requestData['amount'] ?? 0), 'reference' => (string)($requestData['reference'] ?? '') ];
    }
}