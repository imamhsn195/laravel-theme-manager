<?php

namespace ImamHasan\ThemeManager\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ImamHasan\ThemeManager\Models\Theme;
use ImamHasan\ThemeManager\Services\ThemeService;

class ThemeManagementController extends Controller
{
    public function __construct(protected ThemeService $themeService)
    {
    }

    public function index(): View
    {
        $themes = Theme::query()->orderBy('name')->get();

        return view('theme-manager::admin.themes.index', compact('themes'));
    }

    public function activate(string $slug): RedirectResponse
    {
        $theme = Theme::where('slug', $slug)->firstOrFail();
        $theme->update(['is_active' => true]);

        $this->themeService->setActiveTheme($theme->slug);

        return back()->with('status', "Theme {$theme->name} activated.");
    }

    public function deactivate(string $slug): RedirectResponse
    {
        $theme = Theme::where('slug', $slug)->firstOrFail();
        $theme->update(['is_active' => false]);

        return back()->with('status', "Theme {$theme->name} deactivated.");
    }
}
