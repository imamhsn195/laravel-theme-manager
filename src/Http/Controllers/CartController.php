<?php

namespace ImamHasan\ThemeManager\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ImamHasan\ThemeManager\Services\CartService;
use ImamHasan\ThemeManager\Services\ThemeService;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected ThemeService $themeService
    ) {
    }

    public function index(): View
    {
        $items = $this->cart->items();
        $quantities = $this->cart->all();

        return view(
            $this->themeService->resolveView('shop.cart', 'theme-manager::shop.cart'),
            compact('items', 'quantities')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $this->cart->add($data['product_id'], $data['quantity'] ?? 1);

        return back()->with('status', 'Product added to cart.');
    }

    public function destroy(int $productId): RedirectResponse
    {
        $this->cart->remove($productId);

        return back()->with('status', 'Product removed from cart.');
    }
}
