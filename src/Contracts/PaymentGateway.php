<?php

namespace ImamHasan\ThemeManager\Contracts;

use ImamHasan\ThemeManager\Models\Order;
use ImamHasan\ThemeManager\Models\Purchase;

interface PaymentGateway
{
    public function charge(array $payload): array;

    public function capture(string $paymentReference): bool;

    public function refund(string $paymentReference, float $amount): bool;
}
