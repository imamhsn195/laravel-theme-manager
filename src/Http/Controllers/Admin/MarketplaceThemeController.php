<?php

namespace ImamHasan\ThemeManager\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

class MarketplaceThemeController extends Controller
{
    public function index(): View
    {
        $themes = collect();

        return view('theme-manager::admin.marketplace.index', compact('themes'));
    }
}
