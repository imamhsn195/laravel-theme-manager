<?php

use Illuminate\Support\Facades\Route;
use ImamHasan\ThemeManager\Http\Controllers\Admin\OrderController;
use ImamHasan\ThemeManager\Http\Controllers\Admin\ProductController;

if (! config('theme-manager.ecommerce.enabled')) {
    return;
}

Route::middleware(config('theme-manager.admin_middleware', ['web', 'auth']))
    ->prefix('admin/theme-manager/shop')
    ->name('theme-manager.')
    ->group(function () {
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    });
