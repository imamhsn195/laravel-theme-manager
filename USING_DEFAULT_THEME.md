# How to Use the Default Theme in Blade Templates

## Step 1: Activate the Theme

First, activate the default theme:

```bash
php artisan theme:activate default
```

Or programmatically in your code:

```php
use ImamHasan\ThemeManager\Services\ThemeService;

$themeService = app(ThemeService::class);
$themeService->setActiveTheme('default');
```

## Step 2: Use Theme Views in Controllers

### Method 1: Direct View Reference (Recommended)

In your controllers, return views using the theme namespace:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('theme-default::pages.home');
    }
    
    public function dashboard()
    {
        return view('theme-default::pages.dashboard');
    }
}
```

### Method 2: Using ThemeService Helper

You can also use the theme service to resolve views:

```php
use ImamHasan\ThemeManager\Services\ThemeService;

class HomeController extends Controller
{
    public function index(ThemeService $themeService)
    {
        $view = $themeService->resolveView('pages.home', 'welcome');
        return view($view);
    }
}
```

## Step 3: Create Your Own Blade Views

### Extending the Theme Layout

Create your own views that extend the theme's layout:

**Example: `resources/views/pages/about.blade.php`**

```blade
@extends('theme-default::layout')

@section('content')
<div class="container">
    <h1>About Us</h1>
    <p>This is the about page using the default theme.</p>
</div>
@endsection
```

**Example: `resources/views/products/index.blade.php`**

```blade
@extends('theme-default::layout')

@section('content')
<div class="container">
    <h1>Products</h1>
    
    <div class="d-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
        @foreach($products as $product)
            <div class="card">
                <div class="card-header">
                    <h3>{{ $product->name }}</h3>
                </div>
                <div class="card-body">
                    <p>{{ $product->description }}</p>
                    <p><strong>Price:</strong> ${{ $product->price }}</p>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary">
                        View Details
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
```

## Step 4: Available Theme Views

The default theme provides these views:

### Layout
- `theme-default::layout` - Main layout template

### Pages
- `theme-default::pages.home` - Homepage
- `theme-default::pages.dashboard` - Dashboard page

### Partials
- `theme-default::partials.header` - Header/navigation
- `theme-default::partials.footer` - Footer

### Error Pages
- `theme-default::errors.404` - 404 error page
- `theme-default::errors.500` - 500 error page

## Step 5: Using Theme Components

### Include Partials

You can include theme partials in your views:

```blade
@extends('theme-default::layout')

@section('content')
<div class="container">
    <h1>My Page</h1>
    
    @include('theme-default::partials.header')
    
    <p>Your content here...</p>
</div>
@endsection
```

### Using Theme CSS Classes

The theme provides CSS classes you can use:

```blade
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Card Title</h3>
        </div>
        <div class="card-body">
            <p>Card content</p>
            <button class="btn btn-primary">Click Me</button>
        </div>
    </div>
</div>
```

### Available CSS Classes

- **Layout**: `.container`, `.container-fluid`
- **Buttons**: `.btn`, `.btn-primary`, `.btn-secondary`, `.btn-outline`
- **Cards**: `.card`, `.card-header`, `.card-body`
- **Alerts**: `.alert`, `.alert-success`, `.alert-danger`, `.alert-warning`, `.alert-info`
- **Forms**: `.form-group`, `.form-label`, `.form-control`
- **Utilities**: `.text-center`, `.mt-1`, `.mb-2`, `.p-3`, `.d-flex`, `.d-grid`, etc.

## Step 6: Push to Stacks

You can push additional CSS/JS to the theme's stacks:

```blade
@extends('theme-default::layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/custom.js') }}"></script>
@endpush

@section('content')
    <p>Your content</p>
@endsection
```

## Step 7: Override Theme Views (Optional)

If you need to customize theme views, you can publish them:

1. Copy theme views to your `resources/views` directory
2. Modify them as needed
3. Reference them without the theme namespace

**Example:**

Copy `Themes/default/resources/views/layout.blade.php` to `resources/views/themes/default/layout.blade.php` and modify it.

Then use:
```blade
@extends('themes.default.layout')
```

## Complete Example Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('theme-default::pages.home');
    }
    
    public function about()
    {
        return view('pages.about'); // Your custom view extending theme layout
    }
    
    public function products()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }
}
```

## Route Example

```php
// routes/web.php

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
```

## Summary

1. **Activate theme**: `php artisan theme:activate default`
2. **Use theme views**: `view('theme-default::pages.home')`
3. **Extend layout**: `@extends('theme-default::layout')`
4. **Use theme classes**: Use provided CSS classes in your HTML
5. **Push assets**: Use `@push('styles')` and `@push('scripts')` for additional assets

The theme namespace format is: `theme-{slug}::view-name`

