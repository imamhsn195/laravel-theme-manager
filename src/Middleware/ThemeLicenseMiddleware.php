<?php

namespace ImamHasan\ThemeManager\Middleware;

use Closure;
use Illuminate\Http\Request;
use ImamHasan\ThemeManager\Services\LicenseService;
use ImamHasan\ThemeManager\Services\ThemeService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ThemeLicenseMiddleware
{
    public function __construct(
        protected ThemeService $themeService,
        protected LicenseService $licenseService
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        $activeTheme = $this->themeService->getActiveTheme();

        if ($activeTheme && ! $this->licenseService->validateTheme($activeTheme)) {
            throw new AccessDeniedHttpException('Theme license is missing or invalid.');
        }

        return $next($request);
    }
}
