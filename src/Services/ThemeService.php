<?php

namespace ImamHasan\ThemeManager\Services;

use Composer\InstalledVersions;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;
use ImamHasan\ThemeManager\Models\TmTheme;
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

        TmTheme::query()->update(['is_active' => false]);
        TmTheme::where('slug', $slug)->update(['is_active' => true]);
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
        $discovered = [];

        // Discover themes from Composer packages
        $discovered = array_merge($discovered, $this->discoverComposerThemes());

        // Discover themes from local directories
        $discovered = array_merge($discovered, $this->discoverLocalThemes());

        return $discovered;
    }

    /**
     * Discover themes from Composer packages (vendor/)
     *
     * @return array<int, array<string, mixed>>
     */
    protected function discoverComposerThemes(): array
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
                'source' => 'composer',
                'path' => $installPath,
            ]);
        }

        return $discovered;
    }

    /**
     * Discover themes from local directories (themes/)
     *
     * @return array<int, array<string, mixed>>
     */
    protected function discoverLocalThemes(): array
    {
        $themeRoot = $this->config->get('theme-manager.theme_path', base_path('themes'));
        $discovered = [];

        if (! $this->files->isDirectory($themeRoot)) {
            return $discovered;
        }

        $directories = $this->files->directories($themeRoot);

        foreach ($directories as $directory) {
            $themeJsonPath = $directory . DIRECTORY_SEPARATOR . 'theme.json';

            if (! $this->files->exists($themeJsonPath)) {
                continue;
            }

            $themeInfo = json_decode($this->files->get($themeJsonPath), true);

            if (! is_array($themeInfo)) {
                continue;
            }

            // Use directory name as slug if not specified
            $slug = $themeInfo['slug'] ?? basename($directory);

            $discovered[] = array_merge($themeInfo, [
                'slug' => $slug,
                'package' => $themeInfo['package'] ?? 'local/' . $slug,
                'source' => 'local',
                'path' => $directory,
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
