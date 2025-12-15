<?php

namespace ImamHasan\ThemeManager\Contracts;

use ImamHasan\ThemeManager\Models\TmOrder;
use ImamHasan\ThemeManager\Models\TmPurchase;

interface PaymentGateway
{
    public function charge(array $payload): array;

    public function capture(string $paymentReference): bool;

    public function refund(string $paymentReference, float $amount): bool;
}
