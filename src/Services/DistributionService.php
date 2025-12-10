<?php

namespace ImamHasan\ThemeManager\Services;

use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ImamHasan\ThemeManager\Models\MarketplaceTheme;
use ImamHasan\ThemeManager\Models\Purchase;

class DistributionService
{
    public function issueToken(Purchase $purchase, MarketplaceTheme $theme): array
    {
        $method = config('theme-manager.distribution.method', 'zip');

        return match ($method) {
            'zip' => $this->prepareZipToken($purchase, $theme),
            'packagist' => $this->preparePackagistToken($purchase, $theme),
            'token' => $this->prepareGenericToken($purchase),
            default => $this->prepareGenericToken($purchase),
        };
    }

    protected function prepareZipToken(Purchase $purchase, MarketplaceTheme $theme): array
    {
        $storagePath = rtrim(config('theme-manager.distribution.zip_storage'), DIRECTORY_SEPARATOR);
        $zipPath = $storagePath . DIRECTORY_SEPARATOR . ($theme->slug ?? $theme->id) . '.zip';

        $token = Str::uuid()->toString();
        $purchase->update([
            'download_token' => $token,
            'download_expires_at' => Carbon::now()->addDays(7),
            'notes' => trim(($purchase->notes ?? '') . "\nZIP: {$zipPath}"),
        ]);

        return ['token' => $token, 'path' => $zipPath];
    }

    protected function preparePackagistToken(Purchase $purchase, MarketplaceTheme $theme): array
    {
        $token = Str::random(32);
        $repository = config('theme-manager.distribution.packagist.repository');

        $instructions = "Add repository {$repository} with token {$token}";
        $purchase->update([
            'download_token' => $token,
            'notes' => trim(($purchase->notes ?? '') . "\n{$instructions}"),
        ]);

        return ['token' => $token, 'instructions' => $instructions];
    }

    protected function prepareGenericToken(Purchase $purchase): array
    {
        $token = Str::uuid()->toString();
        $purchase->update(['download_token' => $token]);

        return ['token' => $token];
    }
}
