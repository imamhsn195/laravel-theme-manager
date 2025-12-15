<?php

namespace ImamHasan\ThemeManager\Services;

use Illuminate\Support\Facades\Str;
use ImamHasan\ThemeManager\Models\TmOrder;
use ImamHasan\ThemeManager\Models\TmOrderItem;
use ImamHasan\ThemeManager\Models\TmProduct;

class OrderService
{
    public function create(array $customerData, array $cartItems): TmOrder
    {
        $order = TmOrder::create([
            'user_id' => $customerData['user_id'] ?? null,
            'order_number' => $this->generateOrderNumber(),
            'total' => $this->calculateTotal($cartItems),
            'tax' => 0,
            'shipping' => 0,
            'billing_address' => $customerData['billing'] ?? null,
            'shipping_address' => $customerData['shipping'] ?? null,
        ]);

        foreach ($cartItems as $item) {
            $product = TmProduct::find($item['product_id']);

            if (! $product) {
                continue;
            }

            TmOrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item['quantity'],
                'total' => $product->price * $item['quantity'],
            ]);
        }

        return $order;
    }

    protected function calculateTotal(array $items): float
    {
        $sum = 0;

        foreach ($items as $item) {
            $product = TmProduct::find($item['product_id']);
            if ($product) {
                $sum += ($product->price * $item['quantity']);
            }
        }

        return $sum;
    }

    protected function generateOrderNumber(): string
    {
        return 'SHP-' . strtoupper(Str::random(8));
    }
}
