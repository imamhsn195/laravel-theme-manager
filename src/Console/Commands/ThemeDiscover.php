<?php

namespace ImamHasan\ThemeManager\Console\Commands;

use Illuminate\Console\Command;
use ImamHasan\ThemeManager\Models\Theme;
use ImamHasan\ThemeManager\Services\ThemeService;

class ThemeDiscover extends Command
{
    protected $signature = 'theme:discover';

    protected $description = 'Scan installed Composer packages for Laravel themes.';

    public function handle(ThemeService $themeService): int
    {
        $this->info('Discovering installed themes...');

        $installedThemes = $themeService->discoverInstalledThemes();

        if (empty($installedThemes)) {
            $this->warn('No laravel-theme packages found.');
            return self::SUCCESS;
        }

        foreach ($installedThemes as $themeInfo) {
            $theme = Theme::updateOrCreate(
                ['package' => $themeInfo['package']],
                [
                    'name' => $themeInfo['name'] ?? $themeInfo['slug'] ?? $themeInfo['package'],
                    'slug' => $themeInfo['slug'] ?? $themeInfo['package'],
                    'version' => $themeInfo['version'] ?? '1.0.0',
                    'description' => $themeInfo['description'] ?? null,
                    'license_required' => (bool) ($themeInfo['license_required'] ?? false),
                    'config' => $themeInfo,
                ]
            );

            $this->line("- Registered theme: {$theme->name} ({$theme->slug})");
        }

        $this->info('Theme discovery completed.');

        return self::SUCCESS;
    }
}
