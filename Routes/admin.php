<?php

use Illuminate\Support\Facades\Route;
use ImamHasan\ThemeManager\Http\Controllers\Admin\LicenseManagementController;
use ImamHasan\ThemeManager\Http\Controllers\Admin\MarketplaceThemeController;
use ImamHasan\ThemeManager\Http\Controllers\Admin\ThemeInstallerController;
use ImamHasan\ThemeManager\Http\Controllers\Admin\ThemeManagementController;

Route::middleware(config('theme-manager.admin_middleware', ['web', 'auth']))
    ->prefix('admin/theme-manager')
    ->name('theme-manager.')
    ->group(function () {
        Route::get('themes', [ThemeManagementController::class, 'index'])->name('themes.index');
        Route::post('themes/{slug}/activate', [ThemeManagementController::class, 'activate'])->name('themes.activate');
        Route::post('themes/{slug}/deactivate', [ThemeManagementController::class, 'deactivate'])->name('themes.deactivate');
        Route::post('themes/install', [ThemeInstallerController::class, 'store'])->name('themes.install');

        Route::get('licenses', [LicenseManagementController::class, 'index'])->name('licenses.index');
        Route::post('licenses', [LicenseManagementController::class, 'store'])->name('licenses.store');

        Route::get('marketplace', [MarketplaceThemeController::class, 'index'])->name('marketplace.index');
    });
