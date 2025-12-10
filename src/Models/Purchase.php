<?php

namespace ImamHasan\ThemeManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'marketplace_theme_id',
        'order_number',
        'amount',
        'currency',
        'payment_method',
        'payment_reference',
        'payment_status',
        'status',
        'license_id',
        'notes',
        'download_token',
        'download_expires_at',
    ];

    protected $casts = [
        'download_expires_at' => 'datetime',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(MarketplaceTheme::class, 'marketplace_theme_id');
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}
