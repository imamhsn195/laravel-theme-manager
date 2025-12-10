<?php

use Illuminate\Support\Facades\Route;
use ImamHasan\ThemeManager\Http\Controllers\MarketplaceCartController;
use ImamHasan\ThemeManager\Http\Controllers\MarketplaceCheckoutController;
use ImamHasan\ThemeManager\Http\Controllers\MarketplaceController;

Route::middleware(['web'])
    ->prefix('marketplace')
    ->name('marketplace.')
    ->group(function () {
        Route::get('/', [MarketplaceController::class, 'index'])->name('index');
        Route::get('/theme/{slug}', [MarketplaceController::class, 'show'])->name('theme.show');

        Route::get('/cart', [MarketplaceCartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [MarketplaceCartController::class, 'add'])->name('cart.add');
        Route::delete('/cart/remove/{themeId}', [MarketplaceCartController::class, 'remove'])->name('cart.remove');

        Route::middleware('auth')->group(function () {
            Route::get('/checkout', [MarketplaceCheckoutController::class, 'index'])->name('checkout.index');
            Route::post('/checkout/process', [MarketplaceCheckoutController::class, 'process'])->name('checkout.process');
            Route::get('/checkout/success/{order}', [MarketplaceCheckoutController::class, 'success'])->name('checkout.success');

            Route::prefix('dashboard')->name('dashboard.')->group(function () {
                Route::get('/', [MarketplaceController::class, 'dashboard'])->name('index');
                Route::get('/download/{purchaseId}', [MarketplaceController::class, 'download'])->name('download');
            });
        });
    });
