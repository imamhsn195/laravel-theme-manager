<?php

namespace ImamHasan\ThemeManager\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ImamHasan\ThemeManager\Models\TmProduct;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = TmProduct::paginate(20);

        return view('theme-manager::admin.products.index', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'unique:products,slug'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer'],
        ]);

        TmProduct::create($data);

        return back()->with('status', 'Product created.');
    }
}
