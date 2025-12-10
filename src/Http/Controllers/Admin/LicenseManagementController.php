<?php

namespace ImamHasan\ThemeManager\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ImamHasan\ThemeManager\Models\License;
use ImamHasan\ThemeManager\Models\Theme;
use ImamHasan\ThemeManager\Services\LicenseService;

class LicenseManagementController extends Controller
{
    public function __construct(protected LicenseService $licenseService)
    {
    }

    public function index(): View
    {
        $licenses = License::with('theme')->latest()->paginate(20);
        $themes = Theme::orderBy('name')->pluck('name', 'id');

        return view('theme-manager::admin.licenses.index', compact('licenses', 'themes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'theme_id' => ['required', 'exists:themes,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'domain' => ['required', 'string'],
            'status' => ['required', 'in:active,expired,revoked'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $this->licenseService->create($data);

        return back()->with('status', 'License issued successfully.');
    }
}
