<?php

namespace ImamHasan\ThemeManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ImamHasan\ThemeManager\Models\Product;
use ImamHasan\ThemeManager\Services\ThemeService;

class ShopController extends Controller
{
    public function __construct(protected ThemeService $themeService)
    {
    }

    public function index(): View
    {
        $products = Product::paginate(12);

        return view($this->themeService->resolveView('shop.index', 'theme-manager::shop.index'), compact('products'));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return view($this->themeService->resolveView('shop.show', 'theme-manager::shop.show'), compact('product'));
    }
}
