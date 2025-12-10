<?php

namespace ImamHasan\ThemeManager\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ImamHasan\ThemeManager\Models\MarketplaceTheme;
use ImamHasan\ThemeManager\Services\PurchaseService;

class MarketplaceCheckoutController extends Controller
{
    public function __construct(protected PurchaseService $purchaseService)
    {
    }

    public function index(Request $request): View
    {
        $cart = $request->session()->get('theme_manager_cart', []);
        $themes = MarketplaceTheme::whereIn('id', array_keys($cart))->get();

        return view('theme-manager::marketplace.checkout', compact('themes'));
    }

    public function process(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'theme_id' => ['required', 'exists:marketplace_themes,id'],
        ]);

        $purchase = $this->purchaseService->processPurchase(
            $data['theme_id'],
            $request->user()->id,
            []
        );

        $request->session()->forget('theme_manager_cart');

        return redirect()->route('marketplace.checkout.success', ['order' => $purchase->order_number]);
    }

    public function success(string $order): View
    {
        return view('theme-manager::marketplace.success', ['orderNumber' => $order]);
    }
}
