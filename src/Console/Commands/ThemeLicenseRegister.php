<?php

namespace ImamHasan\ThemeManager\Console\Commands;

use Illuminate\Console\Command;
use ImamHasan\ThemeManager\Services\LicenseService;

class ThemeLicenseRegister extends Command
{
    protected $signature = 'theme:license {slug : The theme slug to register the license for} {license_key : The license key for the theme} {domain? : Optional domain to associate with the license}';

    protected $description = 'Store or update the license key for a theme. Required for premium themes that need license validation.';

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
