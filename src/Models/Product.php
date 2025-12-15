<?php

namespace ImamHasan\ThemeManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImamHasan\ThemeManager\Traits\HasTablePrefix;

class TmProduct extends Model
{
    use HasFactory, HasTablePrefix;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(TmOrderItem::class);
    }
}
