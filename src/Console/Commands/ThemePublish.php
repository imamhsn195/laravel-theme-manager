<?php

namespace ImamHasan\ThemeManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ImamHasan\ThemeManager\Models\TmTheme;
use ImamHasan\ThemeManager\Services\ThemeService;

class ThemePublish extends Command
{
    protected $signature = 'theme:publish {slug : The theme slug to publish assets for} {--force : Overwrite existing assets without confirmation}';

    protected $description = 'Publish theme assets (CSS, JS, images) from the theme directory to the public directory.';

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
     * Assets are copied from themes/{slug}/assets to public/{slug}
     */
    protected function publishLocalTheme(TmTheme $theme, string $themePath): int
    {
        // Check for assets in theme directory (themes/{slug}/assets)
        $assetPaths = [
            $themePath . DIRECTORY_SEPARATOR . 'assets',
            $themePath . DIRECTORY_SEPARATOR . 'Assets',
            $themePath . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Assets',
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
            $this->warn("No assets directory found in theme folder. Assets should be in public/{$theme->slug}/ directly.");
            return self::SUCCESS;
        }

        // Assets go directly to public/{themename}/
        $destinationPath = public_path($theme->slug);

        if (File::isDirectory($destinationPath) && ! $this->option('force')) {
            if (! $this->confirm("Assets already exist in {$destinationPath}. Overwrite?", false)) {
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
