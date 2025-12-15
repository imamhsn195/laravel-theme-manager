<?php

namespace ImamHasan\ThemeManager\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ImamHasan\ThemeManager\Models\TmOrder;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = TmOrder::with('items')->latest()->paginate(20);

        return view('theme-manager::admin.orders.index', compact('orders'));
    }
}
