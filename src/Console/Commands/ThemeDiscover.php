<?php

namespace ImamHasan\ThemeManager\Console\Commands;

use Illuminate\Console\Command;
use ImamHasan\ThemeManager\Models\TmTheme;
use ImamHasan\ThemeManager\Services\ThemeService;

class ThemeDiscover extends Command
{
    protected $signature = 'theme:discover';

    protected $description = 'Scan for Laravel themes from Composer packages and local directories and register them in the database.';

    public function handle(ThemeService $themeService): int
    {
        $this->info('Discovering installed themes...');

        $installedThemes = $themeService->discoverInstalledThemes();

        if (empty($installedThemes)) {
            $this->warn('No themes found. Make sure you have themes installed via Composer or in the themes directory.');
            return self::SUCCESS;
        }

        $composerCount = 0;
        $localCount = 0;

        foreach ($installedThemes as $themeInfo) {
            $source = $themeInfo['source'] ?? 'composer';
            $sourceLabel = $source === 'local' ? 'local' : 'composer';

            $theme = TmTheme::updateOrCreate(
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

            if ($source === 'local') {
                $localCount++;
            } else {
                $composerCount++;
            }

            $this->line("- Registered theme: {$theme->name} ({$theme->slug}) [{$sourceLabel}]");
        }

        $this->info("Theme discovery completed. Found {$composerCount} Composer theme(s) and {$localCount} local theme(s).");

        return self::SUCCESS;
    }
}
