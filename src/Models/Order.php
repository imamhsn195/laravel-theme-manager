<?php

namespace ImamHasan\ThemeManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ImamHasan\ThemeManager\Traits\HasTablePrefix;

class TmOrder extends Model
{
    use HasFactory, HasTablePrefix;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'tax',
        'shipping',
        'status',
        'payment_status',
        'payment_method',
        'billing_address',
        'shipping_address',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(TmOrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
