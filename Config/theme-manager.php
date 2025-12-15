<?php

return [
    // Control auto-loading to prevent conflicts with existing migrations/routes
    'load_migrations' => env('THEME_MANAGER_LOAD_MIGRATIONS', true),
    'load_routes' => env('THEME_MANAGER_LOAD_ROUTES', true),

    // Table prefix to avoid conflicts with existing tables
    // Set to empty string '' to disable prefixing
    'table_prefix' => env('THEME_MANAGER_TABLE_PREFIX', ''),

    'active_theme' => env('ACTIVE_THEME', null),

    'theme_path' => base_path('themes'),

    'asset_path' => 'themes',

    'admin_middleware' => ['web', 'auth', 'theme-manager.admin'],
    'admin_roles' => ['theme-admin'],
    'admin_permissions' => ['manage theme manager'],

    'license_validation' => [
        'enabled' => true,
        'offline_mode' => true,
        'check_interval' => 86400,
    ],

    'marketplace' => [
        'enabled' => true,
        'api_url' => env('THEME_MARKETPLACE_API_URL'),
        'currency' => env('MARKETPLACE_CURRENCY', 'USD'),
        'tax_rate' => env('MARKETPLACE_TAX_RATE', 0),
        'commission_rate' => env('MARKETPLACE_COMMISSION', 0),
        'license_auto_generate' => true,
        'license_auto_activate' => false,
        'download_method' => 'packagist',
        'packagist_url' => env('MARKETPLACE_PACKAGIST_URL'),
        'packagist_token' => env('MARKETPLACE_PACKAGIST_TOKEN'),
        'email_notifications' => [
            'purchase_confirmation' => true,
            'license_generated' => true,
            'download_ready' => true,
        ],
    ],

    'ecommerce' => [
        'enabled' => env('THEME_ECOMMERCE_ENABLED', false),
    ],

    'payments' => [
        'default' => env('THEME_PAYMENT_GATEWAY', 'stripe'),
        'currency' => env('THEME_PAYMENT_CURRENCY', 'USD'),
        'gateways' => [
            'stripe' => [
                'secret' => env('STRIPE_SECRET'),
                'publishable_key' => env('STRIPE_KEY'),
                'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            ],
            'paypal' => [
                'client_id' => env('PAYPAL_CLIENT_ID'),
                'client_secret' => env('PAYPAL_CLIENT_SECRET'),
                'mode' => env('PAYPAL_MODE', 'sandbox'),
            ],
            'ngenius' => [
                'api_key' => env('NGENIUS_API_KEY'),
                'outlet_id' => env('NGENIUS_OUTLET_ID'),
                'environment' => env('NGENIUS_ENV', 'sandbox'),
            ],
        ],
    ],

    'distribution' => [
        'method' => env('THEME_DISTRIBUTION_METHOD', 'zip'), // zip|packagist|token
        'zip_storage' => storage_path('app/themes'),
        'packagist' => [
            'repository' => env('MARKETPLACE_PACKAGIST_URL'),
            'token' => env('MARKETPLACE_PACKAGIST_TOKEN'),
        ],
        'token' => [
            'provider' => env('THEME_TOKEN_PROVIDER', 'packagist'),
        ],
    ],
];
