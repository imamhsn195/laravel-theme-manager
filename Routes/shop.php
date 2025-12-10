<?php

use Illuminate\Support\Facades\Route;
use ImamHasan\ThemeManager\Http\Controllers\CartController;
use ImamHasan\ThemeManager\Http\Controllers\CheckoutController;
use ImamHasan\ThemeManager\Http\Controllers\ShopController;

if (! config('theme-manager.ecommerce.enabled')) {
    return;
}

Route::middleware(['web'])
    ->prefix('shop')
    ->name('shop.')
    ->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('index');
        Route::get('/product/{slug}', [ShopController::class, 'show'])->name('show');

        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.add');
        Route::delete('/cart/{productId}', [CartController::class, 'destroy'])->name('cart.remove');

        Route::middleware('auth')->group(function () {
            Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
            Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
            Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
        });
    });
