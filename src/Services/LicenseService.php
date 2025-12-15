<?php

namespace ImamHasan\ThemeManager\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use ImamHasan\ThemeManager\Models\TmLicense;
use ImamHasan\ThemeManager\Models\TmTheme;

class LicenseService
{
    public function validateTheme(string $themeSlug, ?string $domain = null): bool
    {
        $theme = TmTheme::where('slug', $themeSlug)->first();

        if (! $theme) {
            return false;
        }

        if (! $theme->license_required) {
            return true;
        }

        $license = TmLicense::where('theme_id', $theme->id)
            ->where('domain', $domain ?? $this->currentDomain())
            ->where('status', 'active')
            ->first();

        if (! $license) {
            return false;
        }

        return $this->validateLicenseKey($license->license_key);
    }

    public function create(array $attributes): TmLicense
    {
        if (! isset($attributes['license_key'])) {
            $attributes['license_key'] = $this->generateLicenseKey();
        }

        if (! isset($attributes['domain'])) {
            $attributes['domain'] = $this->currentDomain();
        }

        $attributes['license_key'] = $this->encryptLicenseKey($attributes['license_key']);

        return TmLicense::create($attributes);
    }

    public function registerLicense(string $themeSlug, string $licenseKey, ?string $domain = null): TmLicense
    {
        $theme = TmTheme::where('slug', $themeSlug)->firstOrFail();

        return TmLicense::updateOrCreate(
            [
                'theme_id' => $theme->id,
                'domain' => $domain ?? $this->currentDomain(),
            ],
            [
                'license_key' => $this->encryptLicenseKey($licenseKey),
                'status' => 'active',
                'purchased_at' => now(),
            ]
        );
    }

    protected function validateLicenseKey(string $encryptedKey): bool
    {
        try {
            $key = Crypt::decryptString($encryptedKey);
        } catch (DecryptException $e) {
            return false;
        }

        if (! Str::startsWith($key, 'THEME-')) {
            return false;
        }

        return true;
    }

    protected function generateLicenseKey(): string
    {
        $parts = [
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4)),
        ];

        return 'THEME-' . implode('-', $parts);
    }

    protected function encryptLicenseKey(string $key): string
    {
        // Avoid double-encrypting keys that already look encrypted.
        try {
            Crypt::decryptString($key);
            // If decrypt succeeds, it's already encrypted.
            return $key;
        } catch (DecryptException $e) {
            return Crypt::encryptString($key);
        }
    }

    protected function currentDomain(): string
    {
        $request = request();

        return $request?->getHost() ?? parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
    }
}
