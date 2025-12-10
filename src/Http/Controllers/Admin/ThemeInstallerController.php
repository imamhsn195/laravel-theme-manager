<?php

namespace ImamHasan\ThemeManager\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;

class ThemeInstallerController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'package' => ['required', 'string'],
        ]);

        // In a full implementation this would trigger composer require / queue job.
        // For now we just re-run discovery assuming the package is already installed.
        Artisan::call('theme:discover');

        return back()->with('status', 'Theme install initiated.');
    }
}
