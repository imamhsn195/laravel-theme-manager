<?php

namespace ImamHasan\ThemeManager\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Str;
use ImamHasan\ThemeManager\Models\License;
use ImamHasan\ThemeManager\Models\MarketplaceTheme;
use ImamHasan\ThemeManager\Models\Purchase;
use ImamHasan\ThemeManager\Models\Theme;
use ImamHasan\ThemeManager\Services\DistributionService;
use ImamHasan\ThemeManager\Services\Payments\PaymentGatewayManager;

class PurchaseService
{
    public function __construct(
        protected LicenseService $licenseService,
        protected PaymentGatewayManager $gatewayManager,
        protected DistributionService $distributionService
    ) {
    }

    public function processPurchase(int $themeId, int $userId, array $paymentData): Purchase
    {
        return DB::transaction(function () use ($themeId, $userId, $paymentData) {
            $theme = MarketplaceTheme::findOrFail($themeId);
            $currency = config('theme-manager.payments.currency', config('theme-manager.marketplace.currency', 'USD'));
            $gatewayName = $paymentData['gateway'] ?? config('theme-manager.payments.default', 'stripe');

            $purchase = Purchase::create([
                'user_id' => $userId,
                'marketplace_theme_id' => $theme->id,
                'order_number' => $this->generateOrderNumber(),
                'amount' => $theme->sale_price ?? $theme->price,
                'currency' => $currency,
                'payment_method' => $gatewayName,
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

            $chargeResult = $this->chargeGateway($gatewayName, [
                'amount' => $purchase->amount,
                'currency' => $currency,
                'description' => 'Theme purchase: ' . $theme->name,
                'metadata' => [
                    'purchase_id' => $purchase->id,
                    'order_number' => $purchase->order_number,
                ],
            ]);

            if (! $chargeResult['success']) {
                $purchase->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                    'notes' => ($chargeResult['message'] ?? 'Payment failed'),
                ]);

                return $purchase;
            }

            $purchase->update([
                'payment_reference' => $chargeResult['reference'] ?? null,
                'payment_status' => 'completed',
                'status' => 'processing',
            ]);

            $license = $this->maybeIssueLicense($theme, $userId, $purchase);
            $this->distributionService->issueToken($purchase, $theme);

            $theme->increment('sales_count');

            $purchase->refresh()->update(['status' => 'completed']);

            if ($license instanceof License) {
                $purchase->setRelation('license', $license);
            }

            return $purchase;
        });
    }

    protected function chargeGateway(string $gatewayName, array $payload): array
    {
        try {
            $gateway = $this->gatewayManager->gateway($gatewayName);
            return $gateway->charge($payload);
        } catch (\Throwable $throwable) {
            Log::error('Payment gateway error', [
                'gateway' => $gatewayName,
                'message' => $throwable->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $throwable->getMessage(),
            ];
        }
    }

    protected function maybeIssueLicense(MarketplaceTheme $marketplaceTheme, int $userId, Purchase $purchase): ?License
    {
        if (! $marketplaceTheme->license_required || ! $marketplaceTheme->package_name) {
            return null;
        }

        if (! config('theme-manager.marketplace.license_auto_generate')) {
            return null;
        }

        $theme = Theme::where('package', $marketplaceTheme->package_name)->first();

        if (! $theme) {
            return null;
        }

        $license = $this->licenseService->create([
            'theme_id' => $theme->id,
            'user_id' => $userId,
            'domain' => request()->getHost(),
            'status' => 'active',
        ]);

        $purchase->update(['license_id' => $license->id]);

        return $license;
    }

    protected function prepareDistribution(Purchase $purchase, MarketplaceTheme $theme): void
    {
        $method = config('theme-manager.distribution.method', 'zip');

        if ($method === 'zip') {
            $token = $this->generateDownloadToken();
            $purchase->update([
                'download_token' => $token,
                'download_expires_at' => Carbon::now()->addDays(7),
            ]);
        } elseif ($method === 'packagist') {
            $notes = trim(($purchase->notes ?? '') . '\nPackagist access: ' . config('theme-manager.distribution.packagist.repository'));
            $purchase->update(['notes' => $notes]);
        } elseif ($method === 'token') {
            $purchase->update([
                'download_token' => $this->generateDownloadToken(),
            ]);
        }
    }

    protected function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(Str::random(8));
    }

    protected function generateDownloadToken(): string
    {
        return Str::uuid()->toString();
    }
}
