<?php

namespace ImamHasan\ThemeManager\Services;

use ImamHasan\ThemeManager\Models\Order;

class PaymentService
{
    public function charge(string $method, Order $order): array
    {
        // @todo Integrate Stripe, PayPal, etc.
        return [
            'success' => true,
            'reference' => 'TEST-' . $order->order_number,
        ];
    }
}
