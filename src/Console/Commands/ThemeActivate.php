<?php

namespace ImamHasan\ThemeManager\Console\Commands;

use Illuminate\Console\Command;
use ImamHasan\ThemeManager\Models\Theme;
use ImamHasan\ThemeManager\Services\ThemeService;

class ThemeActivate extends Command
{
    protected $signature = 'theme:activate {slug}';

    protected $description = 'Activate a discovered theme and set it as current.';

    public function handle(ThemeService $themeService): int
    {
        $slug = $this->argument('slug');
        $theme = Theme::where('slug', $slug)->first();

        if (! $theme) {
            $this->error("Theme {$slug} not found. Run theme:discover first.");
            return self::FAILURE;
        }

        Theme::query()->update(['is_active' => false]);
        $theme->update(['is_active' => true]);

        $themeService->setActiveTheme($slug);

        $this->info("Theme {$theme->name} activated.");

        return self::SUCCESS;
    }
}
