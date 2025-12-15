# How to Create a Default Theme

This guide will walk you through creating a default theme for your Laravel application using the Theme Manager.

## Step 1: Create Theme Directory Structure

Themes use Laravel's standard directory structure:
- **Views**: `resources/views/{themename}/`
- **Assets**: `public/{themename}/`
- **Theme Config**: `themes/{themename}/theme.json`
- **Source Assets** (optional): `themes/{themename}/assets/` (for publishing)

Create the following directory structure:

```bash
themes/
└── default/
    ├── theme.json
    └── assets/          # Source assets (optional, for publishing)
        ├── css/
        │   └── style.css
        └── js/
            └── app.js

resources/
└── views/
    └── default/         # Theme views
        ├── layout.blade.php
        └── pages/
            └── home.blade.php

public/
└── default/             # Published assets (created when publishing)
    ├── css/
    │   └── style.css
    └── js/
        └── app.js
```

**Quick command to create directories:**
```bash
mkdir -p themes/default/assets/css
mkdir -p themes/default/assets/js
mkdir -p resources/views/default/pages
mkdir -p public/default/css
mkdir -p public/default/js
```

## Step 2: Create theme.json

Create `themes/default/theme.json`:

```json
{
    "name": "Default Theme",
    "slug": "default",
    "version": "1.0.0",
    "description": "Default theme for the application",
    "author": "Your Name",
    "author_url": "https://yourwebsite.com",
    "license": "MIT",
    "license_required": false,
    "features": [
        "responsive",
        "clean-design"
    ]
}
```

**Required fields:**
- `name`: Display name of the theme
- `slug`: Unique identifier (used in commands and view namespaces)
- `version`: Theme version

**Optional fields:**
- `description`: Theme description
- `author`: Author name
- `author_url`: Author website
- `license`: License type
- `license_required`: Whether license validation is required (default: false)
- `features`: Array of theme features

## Step 3: Create Layout View

Create `resources/views/default/layout.blade.php`:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('default/css/style.css') }}">
    
    @stack('styles')
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <h1>{{ config('app.name', 'Laravel') }}</h1>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
        </div>
    </footer>

    <!-- Theme JS -->
    <script src="{{ asset('default/js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
```

## Step 4: Create Home Page View

Create `resources/views/default/pages/home.blade.php`:

```blade
@extends('theme-default::layout')

@section('content')
    <div class="container">
        <section class="hero">
            <h1>Welcome to {{ config('app.name', 'Laravel') }}</h1>
            <p>This is your default theme. Start customizing it to match your brand!</p>
        </section>

        <section class="features">
            <h2>Features</h2>
            <div class="grid">
                <div class="card">
                    <h3>Responsive Design</h3>
                    <p>Works on all devices and screen sizes.</p>
                </div>
                <div class="card">
                    <h3>Easy Customization</h3>
                    <p>Modify views and assets to match your needs.</p>
                </div>
                <div class="card">
                    <h3>Theme Manager</h3>
                    <p>Switch between themes easily.</p>
                </div>
            </div>
        </section>
    </div>
@endsection
```

## Step 5: Create CSS Styles

Create `themes/default/assets/css/style.css` (source) and it will be published to `public/default/css/style.css`:

```css
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f5f5f5;
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header */
header {
    background: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

nav {
    padding: 1rem 0;
}

nav .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav h1 {
    font-size: 1.5rem;
    color: #333;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 2rem;
}

nav a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: color 0.3s;
}

nav a:hover {
    color: #007bff;
}

/* Main Content */
main {
    min-height: calc(100vh - 200px);
    padding: 2rem 0;
}

/* Hero Section */
.hero {
    text-align: center;
    padding: 4rem 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    margin-bottom: 3rem;
}

.hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.hero p {
    font-size: 1.25rem;
    opacity: 0.9;
}

/* Features Section */
.features {
    margin-top: 3rem;
}

.features h2 {
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2rem;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.card h3 {
    margin-bottom: 1rem;
    color: #667eea;
}

.card p {
    color: #666;
}

/* Footer */
footer {
    background: #333;
    color: white;
    text-align: center;
    padding: 2rem 0;
    margin-top: 3rem;
}

footer p {
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    nav .container {
        flex-direction: column;
        gap: 1rem;
    }

    nav ul {
        gap: 1rem;
    }

    .hero h1 {
        font-size: 2rem;
    }

    .grid {
        grid-template-columns: 1fr;
    }
}
```

## Step 6: Create JavaScript File (Optional)

Create `themes/default/assets/js/app.js` (source) and it will be published to `public/default/js/app.js`:

```javascript
// Default Theme JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Default theme loaded');
    
    // Add your custom JavaScript here
});
```

## Step 7: Discover and Activate the Theme

Now that you've created your theme, register it with the Theme Manager:

```bash
# Discover themes (scans themes/ directory and Composer packages)
php artisan theme:discover
```

You should see output like:
```
Discovering installed themes...
- Registered theme: Default Theme (default) [local]
Theme discovery completed. Found 0 Composer theme(s) and 1 local theme(s).
```

## Step 8: Activate the Theme

Activate your default theme:

```bash
php artisan theme:activate default
```

Output:
```
Theme Default Theme activated.
```

## Step 9: Publish Theme Assets

Publish the theme's CSS and JavaScript files to the public directory:

```bash
php artisan theme:publish default
```

This will copy assets from `themes/default/assets/` to `public/default/`.

**Note:** If assets are already in `public/default/`, you can skip this step. The `theme:publish` command is mainly for copying source assets from the theme directory to public.

## Step 10: Use the Theme in Your Routes

In your Laravel routes or controllers, use the theme views:

**In routes/web.php:**
```php
Route::get('/', function () {
    return view('theme-default::pages.home', [
        'title' => 'Welcome'
    ]);
});
```

**In a Controller:**
```php
public function index()
{
    return view('theme-default::pages.home', [
        'title' => 'Home Page'
    ]);
}
```

**View namespace format:** `theme-{slug}::view-name`

## Step 11: Test Your Theme

1. Start your Laravel development server:
   ```bash
   php artisan serve
   ```

2. Visit `http://localhost:8000` to see your theme in action.

## Theme Structure Summary

```
themes/
└── default/
    ├── theme.json                    # Theme metadata (required)
    └── assets/                       # Source assets (optional, for publishing)
        ├── css/
        │   └── style.css
        └── js/
            └── app.js

resources/
└── views/
    └── default/                      # Theme views
        ├── layout.blade.php          # Main layout
        └── pages/
            └── home.blade.php        # Page views

public/
└── default/                          # Published assets (accessible via web)
    ├── css/
    │   └── style.css
    └── js/
        └── app.js
```

## View Namespace

When using theme views, use the namespace format:
- Namespace: `theme-{slug}`
- Example: `theme-default::layout`
- Example: `theme-default::pages.home`

## Customization Tips

1. **Add More Views**: Create additional views in `src/Views/` and reference them using the namespace.

2. **Add Routes**: You can create a `routes/web.php` file in your theme directory (for Composer themes with ServiceProvider).

3. **Add Configuration**: Create a `config/theme-default.php` file if your theme needs configuration.

4. **Add Translations**: Create `lang/en/` directory for theme-specific translations.

5. **Theme Settings**: Use the Theme Manager's settings system to store theme-specific options.

## Next Steps

- Customize the layout and styles to match your brand
- Add more page templates
- Create additional themes (free/premium) in the `themes/` directory
- Package your theme as a Composer package for distribution

## Troubleshooting

**Theme not discovered?**
- Check that `theme.json` exists and is valid JSON
- Verify the directory structure matches the expected format
- Run `php artisan theme:discover` again

**Views not found?**
- Ensure views are in `src/Views/`, `Views/`, `views/`, or `resources/views/`
- Check the view namespace: `theme-{slug}::view-name`
- Clear view cache: `php artisan view:clear`

**Assets not loading?**
- Run `php artisan theme:publish default` to copy assets from `themes/default/assets/` to `public/default/`
- Check that assets are in `public/default/`
- Verify asset paths in your views use `asset('default/...')` (not `asset('themes/default/...')`)

## Additional Resources

- See `USER_GUIDE.md` for complete Theme Manager documentation
- Check `README.md` for package overview

