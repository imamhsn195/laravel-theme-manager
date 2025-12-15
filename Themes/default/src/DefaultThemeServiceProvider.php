<?php

namespace ImamHasan\ThemeManager\Themes\Default;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class DefaultThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register theme views
        $this->loadViewsFrom(
            __DIR__ . '/../../resources/views',
            'theme-default'
        );
    }
}

