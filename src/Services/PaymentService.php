<?php

namespace ImamHasan\ThemeManager\Services;

use ImamHasan\ThemeManager\Models\TmOrder;

class PaymentService
{
    public function charge(string $method, TmOrder $order): array
    {
        // @todo Integrate Stripe, PayPal, etc.
        return [
            'success' => true,
            'reference' => 'TEST-' . $order->order_number,
        ];
    }
}
