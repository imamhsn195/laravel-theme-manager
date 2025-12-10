<?php

namespace ImamHasan\ThemeManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketplaceTheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'is_free',
        'is_featured',
        'preview_url',
        'demo_url',
        'screenshots',
        'package_name',
        'version',
        'author_id',
        'category_id',
        'tags',
        'features',
        'sales_count',
        'rating',
        'rating_count',
        'status',
        'download_url',
        'license_required',
        'license_types',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'screenshots' => 'array',
        'tags' => 'array',
        'features' => 'array',
        'license_types' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MarketplaceCategory::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'marketplace_theme_id');
    }
}
