<?php

namespace ImamHasan\ThemeManager\Services;

use Composer\InstalledVersions;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;
use ImamHasan\ThemeManager\Models\Theme;
use ImamHasan\ThemeManager\Models\ThemeSetting;
use RuntimeException;

class ThemeService
{
    protected ?string $cachedActiveTheme = null;

    public function __construct(
        protected Repository $config,
        protected Filesystem $files
    ) {
    }

    public function getActiveTheme(): ?string
    {
        if ($this->cachedActiveTheme !== null) {
            return $this->cachedActiveTheme;
        }

        $setting = ThemeSetting::where('key', 'active_theme')->value('value');

        if ($setting) {
            return $this->cachedActiveTheme = $setting;
        }

        return $this->cachedActiveTheme = $this->config->get('theme-manager.active_theme');
    }

    public function setActiveTheme(string $slug): void
    {
        $this->config->set('theme-manager.active_theme', $slug);

        ThemeSetting::updateOrCreate(
            ['key' => 'active_theme'],
            ['value' => $slug]
        );

        $this->cachedActiveTheme = $slug;

        Theme::query()->update(['is_active' => false]);
        Theme::where('slug', $slug)->update(['is_active' => true]);
    }

    public function getThemePath(?string $slug = null): string
    {
        $slug ??= $this->getActiveTheme();

        if (! $slug) {
            throw new RuntimeException('No active theme has been configured.');
        }

        return $this->getThemePathForSlug($slug);
    }

    public function getThemePathForSlug(string $slug): string
    {
        $themeRoot = $this->config->get('theme-manager.theme_path', base_path('themes'));

        return rtrim($themeRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $slug;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function discoverInstalledThemes(): array
    {
        if (! class_exists(InstalledVersions::class)) {
            return [];
        }

        $packages = InstalledVersions::getInstalledPackagesByType('laravel-theme');
        $discovered = [];

        foreach ($packages as $packageName) {
            $installPath = InstalledVersions::getInstallPath($packageName);
            $themeJsonPath = $installPath ? $installPath . DIRECTORY_SEPARATOR . 'theme.json' : null;

            if (! $themeJsonPath || ! $this->files->exists($themeJsonPath)) {
                continue;
            }

            $themeInfo = json_decode($this->files->get($themeJsonPath), true);

            if (! is_array($themeInfo)) {
                continue;
            }

            $discovered[] = array_merge($themeInfo, [
                'package' => $packageName,
            ]);
        }

        return $discovered;
    }

    public function resolveView(string $relativeView, string $fallback): string
    {
        $activeTheme = $this->getActiveTheme();

        if ($activeTheme) {
            $namespaced = 'theme-' . $activeTheme . '::' . $relativeView;

            if (View::exists($namespaced)) {
                return $namespaced;
            }
        }

        return $fallback;
    }
}
