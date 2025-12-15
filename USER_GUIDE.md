# Laravel Theme Manager - User Guide

## Table of Contents

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Basic Usage](#basic-usage)
4. [Admin Panel](#admin-panel)
5. [Theme Management](#theme-management)
6. [License Management](#license-management)
7. [Marketplace](#marketplace)
8. [Payment Integration](#payment-integration)
9. [Distribution Methods](#distribution-methods)
10. [Troubleshooting](#troubleshooting)

---

## Installation

### Step 1: Install the Package

Add the package to your Laravel project via Composer:

```bash
composer require imamhasan/laravel-theme-manager
```

### Step 2: Publish Configuration

Publish the configuration file to customize settings:

```bash
php artisan vendor:publish --tag=theme-manager-config
```

This creates `config/theme-manager.php` in your project.

### Step 3: Run Migrations

The package will automatically load migrations if `load_migrations` is enabled in config. Otherwise, publish and run manually:

```bash
php artisan vendor:publish --tag=theme-manager-migrations
php artisan migrate
```

**Note:** Migrations are published to `database/migrations/theme-manager` folder to keep them organized separately. Laravel will automatically discover and run migrations from this subfolder.

### Step 4: Set Up Admin Permissions (Required for Admin Panel)

The admin panel uses Spatie Laravel Permission. Install and configure it:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

Add the `HasRoles` trait to your `User` model:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    // ...
}
```

Assign admin role or permission to a user:

```php
// Option 1: Assign role
$user->assignRole('theme-admin');

// Option 2: Assign permission
$user->givePermissionTo('manage theme manager');
```

---

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# Theme Configuration
ACTIVE_THEME=modern
THEME_MANAGER_TABLE_PREFIX=
THEME_MANAGER_LOAD_MIGRATIONS=true
THEME_MANAGER_LOAD_ROUTES=true

# Marketplace Configuration
THEME_MARKETPLACE_API_URL=
MARKETPLACE_CURRENCY=USD
MARKETPLACE_TAX_RATE=0
MARKETPLACE_COMMISSION=0
MARKETPLACE_PACKAGIST_URL=
MARKETPLACE_PACKAGIST_TOKEN=

# Payment Gateways
THEME_PAYMENT_GATEWAY=stripe
THEME_PAYMENT_CURRENCY=USD

# Stripe
STRIPE_SECRET=sk_test_...
STRIPE_KEY=pk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# PayPal
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_client_secret
PAYPAL_MODE=sandbox

# Ngenius
NGENIUS_API_KEY=your_api_key
NGENIUS_OUTLET_ID=your_outlet_id
NGENIUS_ENV=sandbox

# Distribution
THEME_DISTRIBUTION_METHOD=zip
THEME_TOKEN_PROVIDER=packagist

# E-commerce (Optional)
THEME_ECOMMERCE_ENABLED=false
```

### Configuration File Options

Edit `config/theme-manager.php` to customize:

- **Table Prefix**: Avoid conflicts with existing tables
- **Theme Path**: Where themes are stored (default: `base_path('themes')`)
- **Asset Path**: Public asset path (default: `'themes'`)
- **Admin Middleware**: Customize admin access
- **License Validation**: Configure validation settings
- **Marketplace**: Enable/disable marketplace features
- **Payment Gateways**: Configure Stripe, PayPal, or Ngenius
- **Distribution**: Choose distribution method (zip, packagist, token)

---

## Basic Usage

### Artisan Commands

#### 1. Discover Themes

Scan for Laravel themes from both Composer packages and local directories:

```bash
php artisan theme:discover
```

This command:
- Scans `vendor/` for packages with `laravel-theme` type
- Scans `themes/` directory (configurable via `theme-manager.theme_path`) for local themes
- Registers themes in the database
- Updates existing theme records

**Output:**
```
Discovering installed themes...
- Registered theme: Modern Theme (modern) [composer]
- Registered theme: Default Theme (default) [local]
Theme discovery completed. Found 1 Composer theme(s) and 1 local theme(s).
```

#### 2. Activate a Theme

Activate a discovered theme:

```bash
php artisan theme:activate modern
```

This command:
- Sets the theme as active
- Updates the `ACTIVE_THEME` environment variable (if configured)
- Deactivates other themes

**Output:**
```
Theme Modern Theme activated.
```

#### 3. Publish Theme Assets

Publish theme assets to the public directory. Works for both Composer and local themes:

```bash
php artisan theme:publish modern
```

For Composer themes, this uses Laravel's vendor publish system.
For local themes, this copies assets from the theme directory to `public/themes/{slug}/`.

Use `--force` to overwrite existing assets:

```bash
php artisan theme:publish modern --force
```

**Output:**
```
Assets published for Modern Theme.
```

#### 4. Register a License

Register a license key for a theme and domain:

```bash
php artisan theme:license modern LICENSE-KEY-12345 example.com
```

This command:
- Stores the license in the database
- Associates it with the theme and domain
- Enables license validation

**Output:**
```
License registered for Modern Theme on example.com.
```

---

## Admin Panel

### Accessing the Admin Panel

Navigate to: `/admin/theme-manager`

**Requirements:**
- User must be authenticated
- User must have `theme-admin` role OR `manage theme manager` permission
- Admin middleware is automatically applied

### Admin Routes

- **Themes Management**: `/admin/theme-manager/themes`
- **License Management**: `/admin/theme-manager/licenses`
- **Marketplace**: `/admin/theme-manager/marketplace`

### Features

1. **Theme Management**
   - View all discovered themes
   - Activate/deactivate themes
   - Install new themes

2. **License Management**
   - View all licenses
   - Add new licenses
   - Manage license associations

3. **Marketplace Management**
   - Browse marketplace themes
   - Manage theme listings

---

## Theme Management

### Installing Themes

Themes can be installed in two ways:

#### Method 1: Composer Packages (Production)

Themes can be installed via Composer as `laravel-theme` packages:

```bash
composer require vendor/theme-name
```

Then run discovery:

```bash
php artisan theme:discover
```

#### Method 2: Local Development (Recommended for Development)

For local development, you can place themes directly in the `themes/` directory:

1. Create a theme directory structure in `themes/{slug}/`:

```bash
mkdir -p themes/modern
```

2. Create a `theme.json` file in the theme directory:

```json
{
    "name": "Modern Theme",
    "slug": "modern",
    "version": "1.0.0",
    "description": "A modern, responsive theme",
    "license_required": false
}
```

3. Run discovery:

```bash
php artisan theme:discover
```

The theme manager will automatically discover themes from both Composer packages and local directories.

### Theme Structure

#### Composer Package Theme Structure:

```
vendor/theme-package/
├── composer.json (with "type": "laravel-theme")
├── theme.json
├── src/
│   ├── Assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── Views/
└── ThemeServiceProvider.php
```

#### Local Theme Structure:

```
themes/{slug}/
├── theme.json (required)
├── src/
│   ├── Assets/ (or Assets/)
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── Views/ (or Views/ or views/)
│       ├── layout.blade.php
│       └── pages/
└── (optional) routes, config, etc.
```

**Note:** The theme manager will automatically detect views from:
- `src/Views/`
- `Views/`
- `views/`
- `resources/views/`

And assets from:
- `src/Assets/`
- `Assets/`
- `assets/`
- `public/`

### Activating Themes

**Via Command Line:**
```bash
php artisan theme:activate {slug}
```

**Via Admin Panel:**
1. Go to `/admin/theme-manager/themes`
2. Click "Activate" on the desired theme

**Programmatically:**
```php
use ImamHasan\ThemeManager\Services\ThemeService;

$themeService = app(ThemeService::class);
$themeService->setActiveTheme('modern');
```

### Publishing Assets

After activating a theme, publish its assets:

```bash
php artisan theme:publish {slug}
```

Assets are published using Laravel's vendor publish system with tag: `theme-{slug}-assets`

---

## License Management

### License Requirements

Some themes require licenses. Configure this in the theme's `composer.json`:

```json
{
    "extra": {
        "laravel-theme": {
            "license_required": true
        }
    }
}
```

### Registering Licenses

**Via Command Line:**
```bash
php artisan theme:license {slug} {license-key} {domain}
```

**Via Admin Panel:**
1. Go to `/admin/theme-manager/licenses`
2. Click "Add License"
3. Fill in theme, license key, and domain

### License Validation

License validation is configured in `config/theme-manager.php`:

```php
'license_validation' => [
    'enabled' => true,
    'offline_mode' => true,
    'check_interval' => 86400, // 24 hours
],
```

### License Middleware

Protect routes with license validation:

```php
use ImamHasan\ThemeManager\Middleware\ThemeLicenseMiddleware;

Route::middleware([ThemeLicenseMiddleware::class])->group(function () {
    // Protected routes
});
```

---

## Marketplace

### Enabling Marketplace

The marketplace is enabled by default. Configure in `config/theme-manager.php`:

```php
'marketplace' => [
    'enabled' => true,
    'currency' => 'USD',
    'tax_rate' => 0,
    'commission_rate' => 0,
    // ...
],
```

### Marketplace Routes

- **Browse Themes**: `/marketplace`
- **Theme Details**: `/marketplace/theme/{slug}`
- **Cart**: `/marketplace/cart`
- **Checkout**: `/marketplace/checkout` (requires auth)
- **Dashboard**: `/marketplace/dashboard` (requires auth)
- **Download**: `/marketplace/dashboard/download/{purchaseId}` (requires auth)

### Shopping Flow

1. **Browse Themes**: Visit `/marketplace`
2. **Add to Cart**: Click "Add to Cart" on a theme
3. **View Cart**: Go to `/marketplace/cart`
4. **Checkout**: Proceed to checkout (must be logged in)
5. **Payment**: Complete payment via configured gateway
6. **Download**: Access purchased themes from dashboard

### Customer Dashboard

After purchase, customers can:
- View purchase history
- Download purchased themes
- Access license keys
- View download tokens

---

## Payment Integration

### Supported Gateways

1. **Stripe** (default)
2. **PayPal**
3. **Ngenius**

### Configuring Payment Gateways

#### Stripe

```env
THEME_PAYMENT_GATEWAY=stripe
STRIPE_SECRET=sk_test_...
STRIPE_KEY=pk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

#### PayPal

```env
THEME_PAYMENT_GATEWAY=paypal
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_client_secret
PAYPAL_MODE=sandbox
```

#### Ngenius

```env
THEME_PAYMENT_GATEWAY=ngenius
NGENIUS_API_KEY=your_api_key
NGENIUS_OUTLET_ID=your_outlet_id
NGENIUS_ENV=sandbox
```

### Payment Flow

1. Customer proceeds to checkout
2. Payment gateway processes payment
3. `PurchaseService` updates payment status
4. License is automatically generated (if configured)
5. Download token is created
6. Customer receives confirmation email

### Webhooks

Configure webhooks for payment gateways to handle:
- Payment confirmations
- Refunds
- Subscription updates

---

## Distribution Methods

### 1. ZIP Distribution (Default)

**Configuration:**
```php
'distribution' => [
    'method' => 'zip',
    'zip_storage' => storage_path('app/themes'),
],
```

**Setup:**
1. Upload theme ZIP files to `storage/app/themes/{slug}.zip`
2. Customers receive expiring download tokens
3. Downloads are served via secure token URLs

**Advantages:**
- Simple setup
- Direct file delivery
- Token-based security

### 2. Packagist Distribution

**Configuration:**
```php
'distribution' => [
    'method' => 'packagist',
    'packagist' => [
        'repository' => env('MARKETPLACE_PACKAGIST_URL'),
        'token' => env('MARKETPLACE_PACKAGIST_TOKEN'),
    ],
],
```

**Setup:**
1. Host themes in a private Packagist repository
2. Provide repository URL and token after purchase
3. Customers install via Composer

**Advantages:**
- Version control
- Composer integration
- Automatic updates

### 3. Token Distribution

**Configuration:**
```php
'distribution' => [
    'method' => 'token',
    'token' => [
        'provider' => 'packagist',
    ],
],
```

**Setup:**
- Generic token-based workflow
- Custom delivery methods
- Flexible integration

---

## Troubleshooting

### Theme Not Found

**Problem:** `Theme {slug} not found`

**Solution:**
1. Run `php artisan theme:discover`
2. Verify theme is installed via Composer
3. Check theme's `composer.json` has `"type": "laravel-theme"`

### Assets Not Publishing

**Problem:** Assets not appearing after `theme:publish`

**Solution:**
1. Check theme defines asset tag: `theme-{slug}-assets`
2. Verify theme path exists
3. Use `--force` flag to overwrite: `php artisan theme:publish {slug} --force`
4. Check file permissions on `public/` directory

### Admin Panel Access Denied

**Problem:** Cannot access `/admin/theme-manager`

**Solution:**
1. Verify user is authenticated
2. Assign `theme-admin` role: `$user->assignRole('theme-admin')`
3. Or assign permission: `$user->givePermissionTo('manage theme manager')`
4. Check middleware configuration in `config/theme-manager.php`

### License Validation Failing

**Problem:** License validation errors

**Solution:**
1. Verify license is registered: `php artisan theme:license {slug} {key} {domain}`
2. Check domain matches exactly
3. Verify `license_validation.enabled` is `true` in config
4. Check license expiration date

### Payment Gateway Errors

**Problem:** Payment processing fails

**Solution:**
1. Verify API credentials in `.env`
2. Check gateway is set correctly: `THEME_PAYMENT_GATEWAY`
3. Test with sandbox/test credentials first
4. Check webhook configuration
5. Review payment gateway logs

### Migration Conflicts

**Problem:** Table already exists errors

**Solution:**
1. Set table prefix: `THEME_MANAGER_TABLE_PREFIX=tm_`
2. Or disable auto-loading: `THEME_MANAGER_LOAD_MIGRATIONS=false`
3. Publish migrations manually and modify: `php artisan vendor:publish --tag=theme-manager-migrations`

### Route Conflicts

**Problem:** Routes not working or conflicts

**Solution:**
1. Check route loading: `THEME_MANAGER_LOAD_ROUTES=true`
2. Review route prefixes in route files
3. Clear route cache: `php artisan route:clear`
4. Check middleware conflicts

---

## Additional Resources

### Service Classes

- **ThemeService**: Theme discovery and management
- **LicenseService**: License validation and management
- **PaymentGatewayManager**: Payment processing
- **DistributionService**: Theme distribution
- **PurchaseService**: Purchase processing
- **CartService**: Shopping cart management
- **OrderService**: Order management

### Models

- **Theme**: Theme records
- **License**: License records
- **Purchase**: Purchase records
- **Order**: Order records
- **Product**: Product records
- **MarketplaceTheme**: Marketplace theme listings

### Middleware

- **AdminMiddleware**: Admin panel access control
- **ThemeLicenseMiddleware**: License validation

---

## Support

For issues, questions, or contributions, please refer to the project repository or contact the maintainer.

---

**Last Updated:** January 2025
