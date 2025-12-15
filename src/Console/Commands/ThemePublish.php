<?php

namespace ImamHasan\ThemeManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ImamHasan\ThemeManager\Models\TmTheme;
use ImamHasan\ThemeManager\Services\ThemeService;

class ThemePublish extends Command
{
    protected $signature = 'theme:publish {slug} {--force}';

    protected $description = 'Publish the assets for a theme to the public directory.';

    public function handle(ThemeService $themeService): int
    {
        $slug = $this->argument('slug');
        $theme = TmTheme::where('slug', $slug)->first();

        if (! $theme) {
            $this->error("Theme {$slug} not found. Run theme:discover first.");
            return self::FAILURE;
        }

        $themePath = $themeService->getThemePathForSlug($slug);
        $source = $theme->config['source'] ?? 'composer';

        // Handle local themes
        if ($source === 'local' && File::isDirectory($themePath)) {
            return $this->publishLocalTheme($theme, $themePath);
        }

        // Handle Composer themes via vendor publish
        $tag = "theme-{$slug}-assets";

        if (! File::isDirectory($themePath)) {
            $this->warn('Theme path not found locally; attempting vendor publish regardless.');
        }

        Artisan::call('vendor:publish', [
            '--tag' => $tag,
            '--force' => $this->option('force'),
        ]);

        $this->info("Assets published for {$theme->name}.");

        return self::SUCCESS;
    }

    /**
     * Publish assets from a local theme directory
     */
    protected function publishLocalTheme(TmTheme $theme, string $themePath): int
    {
        $assetPaths = [
            $themePath . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Assets',
            $themePath . DIRECTORY_SEPARATOR . 'Assets',
            $themePath . DIRECTORY_SEPARATOR . 'assets',
            $themePath . DIRECTORY_SEPARATOR . 'public',
        ];

        $sourcePath = null;
        foreach ($assetPaths as $path) {
            if (File::isDirectory($path)) {
                $sourcePath = $path;
                break;
            }
        }

        if (! $sourcePath) {
            $this->warn("No assets directory found for theme {$theme->slug}. Skipping asset publishing.");
            return self::SUCCESS;
        }

        $assetPath = $this->laravel['config']->get('theme-manager.asset_path', 'themes');
        $destinationPath = public_path($assetPath . DIRECTORY_SEPARATOR . $theme->slug);

        if (File::isDirectory($destinationPath) && ! $this->option('force')) {
            if (! $this->confirm("Assets already exist. Overwrite?", false)) {
                $this->info('Publishing cancelled.');
                return self::SUCCESS;
            }
        }

        File::ensureDirectoryExists($destinationPath);
        File::copyDirectory($sourcePath, $destinationPath);

        $this->info("Assets published for {$theme->name} to {$destinationPath}.");

        return self::SUCCESS;
    }
}
