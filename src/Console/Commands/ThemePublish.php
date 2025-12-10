<?php

namespace ImamHasan\ThemeManager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use ImamHasan\ThemeManager\Models\Theme;
use ImamHasan\ThemeManager\Services\ThemeService;

class ThemePublish extends Command
{
    protected $signature = 'theme:publish {slug} {--force}';

    protected $description = 'Publish the assets for a theme to the public directory.';

    public function handle(ThemeService $themeService): int
    {
        $slug = $this->argument('slug');
        $theme = Theme::where('slug', $slug)->first();

        if (! $theme) {
            $this->error("Theme {$slug} not found. Run theme:discover first.");
            return self::FAILURE;
        }

        $tag = "theme-{$slug}-assets";
        $path = $themeService->getThemePathForSlug($slug);

        if (! is_dir($path)) {
            $this->warn('Theme path not found locally; attempting vendor publish regardless.');
        }

        Artisan::call('vendor:publish', [
            '--tag' => $tag,
            '--force' => $this->option('force'),
        ]);

        $this->info("Assets published for {$theme->name}." );

        return self::SUCCESS;
    }
}
