<?php

namespace ImamHasan\ThemeManager\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ImamHasan\ThemeManager\Services\CartService;
use ImamHasan\ThemeManager\Services\OrderService;
use ImamHasan\ThemeManager\Services\PaymentService;
use ImamHasan\ThemeManager\Services\ThemeService;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected OrderService $orderService,
        protected PaymentService $paymentService,
        protected ThemeService $themeService
    ) {
    }

    public function index(): View
    {
        $items = $this->cart->items();

        return view(
            $this->themeService->resolveView('shop.checkout', 'theme-manager::shop.checkout'),
            compact('items')
        );
    }

    public function process(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $cartItems = [];
        foreach ($this->cart->all() as $productId => $quantity) {
            $cartItems[] = ['product_id' => $productId, 'quantity' => $quantity];
        }

        $order = $this->orderService->create([
            'user_id' => $request->user()->id ?? null,
        ], $cartItems);

        $result = $this->paymentService->charge($request->input('payment_method'), $order);

        if ($result['success']) {
            $this->cart->clear();
            return redirect()->route('shop.checkout.success', $order->order_number);
        }

        return back()->withErrors('Payment failed.');
    }

    public function success(string $orderNumber): View
    {
        return view(
            $this->themeService->resolveView('shop.success', 'theme-manager::shop.success'),
            compact('orderNumber')
        );
    }
}
