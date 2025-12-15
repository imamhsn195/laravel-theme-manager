# How to Update the Theme Manager Package in Tailor Project

Since the tailor project uses a **path repository with symlink**, changes in the `laravel-theme-manager` package are automatically reflected. However, you need to run a few commands to ensure everything is updated properly.

## Quick Update Steps

Run these commands in the **tailor project** directory:

```bash
cd C:\xampp\htdocs\tailor

# 1. Update composer to refresh symlink (if needed)
composer update imamhsn195/laravel-theme-manager

# 2. Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 3. Discover themes (to register the updated default theme)
php artisan theme:discover

# 4. Publish theme assets (if you want to update the published assets)
php artisan theme:publish default

# 5. Clear config cache again (to ensure new theme is recognized)
php artisan config:clear
```

## Detailed Explanation

### 1. Composer Update
Since you're using a path repository with `symlink: true`, changes are automatically reflected. However, running `composer update` ensures the symlink is refreshed and autoload files are regenerated.

### 2. Clear Caches
Laravel caches configuration, views, and routes. Clearing them ensures:
- New theme files are recognized
- Updated service providers are loaded
- View cache is refreshed

### 3. Theme Discovery
The `theme:discover` command scans for themes in:
- Composer packages (packages with `type: laravel-theme`)
- Local theme directories (configured in `config/theme-manager.php`)

This registers the updated default theme.

### 4. Publish Assets
The `theme:publish default` command copies assets from:
- `Themes/default/assets/` (source)
- To `public/default/` (published, accessible via web)

This is only needed if you want to update the published CSS/JS files.

### 5. Final Config Clear
Ensures the newly discovered theme is properly loaded.

## Alternative: One-Line Update

You can combine all commands:

```bash
cd C:\xampp\htdocs\tailor && composer update imamhsn195/laravel-theme-manager && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan theme:discover && php artisan theme:publish default && php artisan config:clear
```

## Verify the Update

Check if the theme is discovered:

```bash
php artisan theme:list
```

Activate the default theme (if not already active):

```bash
php artisan theme:activate default
```

## Troubleshooting

### If changes don't appear:

1. **Check symlink**: Ensure the symlink is working
   ```bash
   ls -la vendor/imamhsn195/laravel-theme-manager
   ```

2. **Regenerate autoload**:
   ```bash
   composer dump-autoload
   ```

3. **Clear all caches**:
   ```bash
   php artisan optimize:clear
   ```

4. **Re-discover themes**:
   ```bash
   php artisan theme:discover
   ```

## Notes

- Since you're using symlink, you can develop directly in `laravel-theme-manager` and changes will be reflected in `tailor` automatically
- No need to run `composer require` again - the path repository handles updates
- Theme assets need to be published separately using `theme:publish` command

