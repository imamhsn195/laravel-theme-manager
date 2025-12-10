<?php

namespace ImamHasan\ThemeManager\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use ImamHasan\ThemeManager\Models\MarketplaceTheme;

class MarketplaceService
{
    public function listThemes(int $perPage = 12): LengthAwarePaginator
    {
        return MarketplaceTheme::query()
            ->where('status', 'published')
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function featuredThemes(int $limit = 4): Collection
    {
        return MarketplaceTheme::query()
            ->where('status', 'published')
            ->where('is_featured', true)
            ->take($limit)
            ->get();
    }

    public function findBySlug(string $slug): ?MarketplaceTheme
    {
        return MarketplaceTheme::where('slug', $slug)->first();
    }
}
