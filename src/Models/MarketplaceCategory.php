<?php

namespace ImamHasan\ThemeManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImamHasan\ThemeManager\Traits\HasTablePrefix;

class MarketplaceCategory extends Model
{
    use HasFactory, HasTablePrefix;

    protected $table = 'marketplace_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'sort_order',
    ];

    public function themes(): HasMany
    {
        return $this->hasMany(MarketplaceTheme::class, 'category_id');
    }
}
