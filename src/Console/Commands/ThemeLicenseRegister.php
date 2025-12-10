<?php

namespace ImamHasan\ThemeManager\Console\Commands;

use Illuminate\Console\Command;
use ImamHasan\ThemeManager\Services\LicenseService;

class ThemeLicenseRegister extends Command
{
    protected $signature = 'theme:license {slug} {license_key} {domain?}';

    protected $description = 'Store or update the license key for a theme.';

    public function handle(LicenseService $licenseService): int
    {
        $slug = $this->argument('slug');
        $licenseKey = $this->argument('license_key');
        $domain = $this->argument('domain');

        $license = $licenseService->registerLicense($slug, $licenseKey, $domain);

        $this->info("License saved for {$slug} on domain {$license->domain}.");

        return self::SUCCESS;
    }
}
