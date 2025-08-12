<?php
namespace App\Payments;

use App\Interfaces\PaymentGatewayInterface;

class PaypalGateway implements PaymentGatewayInterface
{
    public function getName(): string
    {
        return 'PayPal';
    }

    public function createPayment(int $userId, float $amount, array $meta = []): array
    {
        // Placeholder: return a redirect URL and reference
        return [
            'status' => 'redirect',
            'redirect_url' => '/wallet?success=1',
            'reference' => 'PP-' . time() . '-' . $userId,
        ];
    }

    public function handleCallback(array $requestData): array
    {
        // Placeholder: validate and return result
        return [
            'status' => 'success',
            'amount' => (float)($requestData['amount'] ?? 0),
            'reference' => (string)($requestData['reference'] ?? ''),
        ];
    }
}