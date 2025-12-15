# Laravel Theme Manager

This package provides theme discovery, activation, licensing, and optional marketplace/ecommerce integrations for any Laravel 11+ application. It is the foundation of the Laravel Theme System described in `LARAVEL_THEME_SYSTEM_PLAN.md`.

## Current Status

- [x] Repository scaffolding
- [x] Theme services implementation (discovery, activation helpers, CLI)
- [x] License management + middleware
- [x] Admin panel, marketplace, and ecommerce scaffolding

### Artisan Commands

- `php artisan theme:discover` — scan for themes from Composer packages and local directories
- `php artisan theme:activate modern` — mark a theme active
- `php artisan theme:publish modern` — publish theme assets (works for both Composer and local themes)
- `php artisan theme:license modern LICENSE-KEY domain.com` — persist a license for a theme/domain

### Local Theme Development

The theme manager supports both Composer packages and local theme development. Place themes in the `themes/` directory (configurable via `theme-manager.theme_path`) with a `theme.json` file, and run `theme:discover` to register them. See `USER_GUIDE.md` for detailed instructions.

### Admin Access & Permissions

The admin panel is built on [jeroennoten/laravel-adminlte](https://github.com/jeroennoten/Laravel-AdminLTE) and gated by [spatie/laravel-permission](https://github.com/spatie/laravel-permission).

1. Publish Spatie's config/migrations in the host app:
   ```bash
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   php artisan migrate
   ```
2. Add `Spatie\Permission\Traits\HasRoles` to your `User` model.
3. Assign either the `theme-admin` role or the `manage theme manager` permission (configurable via `config/theme-manager.php`).

Admin routes automatically apply the `theme-manager.admin` middleware so only authorized users reach the dashboard.

### Payments & Distribution

- Configure gateways via `config/theme-manager.php` (`payments` section) – Stripe, PayPal, and Ngenius drivers are available.
- Purchases run through `PurchaseService`, which calls the selected gateway, updates payment status, issues licenses, and generates download tokens.
- Distribution modes:
  - `zip`: upload theme ZIPs to `storage/app/themes/{slug}.zip`; customers receive expiring download tokens.
  - `packagist`: provide private repository/token instructions after purchase.
  - `token`: generic token-only workflows for custom delivery.
- Extend `DistributionService` or add new drivers if you need bespoke delivery flows.

More detailed implementation steps can be found in the project plan.
