<?php

namespace ImamHasan\ThemeManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ImamHasan\ThemeManager\Traits\HasTablePrefix;

class TmOrderItem extends Model
{
    use HasFactory, HasTablePrefix;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'price',
        'quantity',
        'total',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(TmOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(TmProduct::class);
    }
}
