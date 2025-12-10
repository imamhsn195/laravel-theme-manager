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

        $this->loadMigrationsFrom(__DIR__ . '/../../Database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../Routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/../../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../Routes/shop.php');
        $this->loadRoutesFrom(__DIR__ . '/../../Routes/admin_shop.php');
        $this->loadViewsFrom(__DIR__ . '/../../Resources/views', 'theme-manager');

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
            __DIR__ . '/../../Database/migrations/' => database_path('migrations'),
        ], 'theme-manager-migrations');
    }
}
