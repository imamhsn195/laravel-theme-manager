<?php

namespace ImamHasan\ThemeManager\Services\Payments;

use Illuminate\Support\Str;
use ImamHasan\ThemeManager\Contracts\PaymentGateway;

class StripeGateway implements PaymentGateway
{
    public function __construct(
        protected array $config
    ) {
    }

    public function charge(array $payload): array
    {
        return [
            'success' => true,
            'reference' => 'stripe_' . Str::uuid(),
            'amount' => $payload['amount'] ?? 0,
            'currency' => $payload['currency'] ?? 'USD',
        ];
    }

    public function capture(string $paymentReference): bool
    {
        return true;
    }

    public function refund(string $paymentReference, float $amount): bool
    {
        return true;
    }
}
