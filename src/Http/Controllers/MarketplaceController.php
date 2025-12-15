<?php

namespace ImamHasan\ThemeManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use ImamHasan\ThemeManager\Models\TmLicense;
use ImamHasan\ThemeManager\Models\TmPurchase;
use ImamHasan\ThemeManager\Services\MarketplaceService;

class MarketplaceController extends Controller
{
    public function __construct(protected MarketplaceService $marketplaceService)
    {
    }

    public function index(): View
    {
        $themes = $this->marketplaceService->listThemes();
        $featured = $this->marketplaceService->featuredThemes();

        return view('theme-manager::marketplace.index', compact('themes', 'featured'));
    }

    public function show(string $slug): View
    {
        $theme = $this->marketplaceService->findBySlug($slug);

        abort_unless($theme, 404);

        return view('theme-manager::marketplace.show', compact('theme'));
    }

    public function dashboard(): View
    {
        $userId = auth()->id();
        $purchases = TmPurchase::with('theme')->where('user_id', $userId)->latest()->paginate(10);
        $licenses = TmLicense::with('theme')->where('user_id', $userId)->latest()->get();

        return view('theme-manager::marketplace.dashboard', compact('purchases', 'licenses'));
    }

    public function download(int $purchaseId): View
    {
        $purchase = TmPurchase::with(['theme', 'license'])
            ->where('user_id', auth()->id())
            ->findOrFail($purchaseId);

        $downloadUrl = $purchase->theme?->download_url ?? $purchase->theme?->preview_url ?? null;
        $distributionMethod = config('theme-manager.distribution.method', 'zip');

        return view('theme-manager::marketplace.download', [
            'purchase' => $purchase,
            'downloadUrl' => $downloadUrl,
            'distributionMethod' => $distributionMethod,
        ]);
    }
}
