<?php
namespace App\Interfaces;

interface PaymentGatewayInterface
{
    public function getName(): string;

    public function createPayment(int $userId, float $amount, array $meta = []): array;

    public function handleCallback(array $requestData): array;
}