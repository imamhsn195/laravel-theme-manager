<?php

namespace ImamHasan\ThemeManager\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use ImamHasan\ThemeManager\Console\Commands\ThemeActivate;
use ImamHasan\ThemeManager\Console\Commands\ThemeDiscover;
use ImamHasan\ThemeManager\Console\Commands\ThemeLicenseRegister;
use ImamHasan\ThemeManager\Console\Commands\ThemePublish;
use ImamHasan\ThemeManager\Middleware\AdminMiddleware;
use ImamHasan\ThemeManager\Services\DistributionService;
use ImamHasan\ThemeManager\Services\LicenseService;
use ImamHasan\ThemeManager\Services\Payments\PaymentGatewayManager;
use ImamHasan\ThemeManager\Services\ThemeService;

class ThemeManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../Config/theme-manager.php',
            'theme-manager'
        );

        $this->app->singleton(ThemeService::class, function ($app) {
            return new ThemeService($app['config'], $app['files']);
        });

        $this->app->singleton(LicenseService::class, function ($app) {
            return new LicenseService();
        });

        $this->app->singleton(PaymentGatewayManager::class, function ($app) {
            $config = $app['config']->get('theme-manager.payments', []);

            return new PaymentGatewayManager($app, $config);
        });

        $this->app->singleton(DistributionService::class, function ($app) {
            return new DistributionService();
        });
    }

    public function boot(Router $router): void
    {
        $router->aliasMiddleware('theme-manager.admin', AdminMiddleware::class);

        // Conditionally load migrations to avoid conflicts with existing tables
        if ($this->shouldLoadMigrations()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../Database/migrations');
        }

        // Conditionally load routes to avoid conflicts with existing routes
        if ($this->shouldLoadRoutes()) {
            $this->loadRoutesFrom(__DIR__ . '/../../Routes/admin.php');
            $this->loadRoutesFrom(__DIR__ . '/../../Routes/web.php');
            $this->loadRoutesFrom(__DIR__ . '/../../Routes/shop.php');
            $this->loadRoutesFrom(__DIR__ . '/../../Routes/admin_shop.php');
        }

        $this->loadViewsFrom(__DIR__ . '/../../Resources/views', 'theme-manager');

        // Register views from local themes
        $this->registerLocalThemeViews();

        if ($this->app->runningInConsole()) {
            $this->commands([
                ThemeActivate::class,
                ThemeDiscover::class,
                ThemeLicenseRegister::class,
                ThemePublish::class,
            ]);
        }

        $this->registerPublishing();
    }

    /**
     * Register views from local theme directories
     * Views are expected in resources/views/{themename}/
     */
    protected function registerLocalThemeViews(): void
    {
        $themeService = $this->app->make(ThemeService::class);
        $themeRoot = $this->app['config']->get('theme-manager.theme_path', base_path('themes'));
        $files = $this->app['files'];

        if (! $files->isDirectory($themeRoot)) {
            return;
        }

        $directories = $files->directories($themeRoot);

        foreach ($directories as $directory) {
            $themeJsonPath = $directory . DIRECTORY_SEPARATOR . 'theme.json';

            if (! $files->exists($themeJsonPath)) {
                continue;
            }

            $themeInfo = json_decode($files->get($themeJsonPath), true);

            if (! is_array($themeInfo)) {
                continue;
            }

            $slug = $themeInfo['slug'] ?? basename($directory);
            $viewNamespace = 'theme-' . $slug;

            // Views are in resources/views/{themename}/
            $viewPath = resource_path('views' . DIRECTORY_SEPARATOR . $slug);

            if ($files->isDirectory($viewPath)) {
                $this->loadViewsFrom($viewPath, $viewNamespace);
            }
        }
    }

    protected function registerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../../Config/theme-manager.php' => config_path('theme-manager.php'),
        ], 'theme-manager-config');

        $this->publishes([
            __DIR__ . '/../../Resources/assets' => public_path('vendor/theme-manager'),
        ], 'theme-manager-assets');

        $this->publishes([
            __DIR__ . '/../../Database/migrations/' => database_path('migrations/theme-manager'),
        ], 'theme-manager-migrations');
    }

    /**
     * Check if migrations should be auto-loaded.
     * Set 'theme-manager.load_migrations' to false in config to disable.
     */
    protected function shouldLoadMigrations(): bool
    {
        return $this->app['config']->get('theme-manager.load_migrations', true);
    }

    /**
     * Check if routes should be auto-loaded.
     * Set 'theme-manager.load_routes' to false in config to disable.
     */
    protected function shouldLoadRoutes(): bool
    {
        return $this->app['config']->get('theme-manager.load_routes', true);
    }
}
