<?php

namespace ImamHasan\ThemeManager\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ImamHasan\ThemeManager\Models\MarketplaceTheme;

class MarketplaceCartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('theme_manager_cart', []);
        $items = MarketplaceTheme::whereIn('id', array_keys($cart))->get();

        return view('theme-manager::marketplace.cart', compact('items', 'cart'));
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'theme_id' => ['required', 'exists:marketplace_themes,id'],
        ]);

        $cart = $request->session()->get('theme_manager_cart', []);
        $cart[$data['theme_id']] = 1;
        $request->session()->put('theme_manager_cart', $cart);

        return back()->with('status', 'Theme added to cart.');
    }

    public function remove(Request $request, int $themeId): RedirectResponse
    {
        $cart = $request->session()->get('theme_manager_cart', []);
        unset($cart[$themeId]);
        $request->session()->put('theme_manager_cart', $cart);

        return back()->with('status', 'Theme removed from cart.');
    }
}
