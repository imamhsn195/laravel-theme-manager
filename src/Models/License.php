<?php

namespace ImamHasan\ThemeManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ImamHasan\ThemeManager\Traits\HasTablePrefix;

class TmLicense extends Model
{
    use HasFactory, HasTablePrefix;

    protected $table = 'licenses';

    protected $fillable = [
        'theme_id',
        'user_id',
        'license_key',
        'domain',
        'status',
        'purchased_at',
        'expires_at',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(TmTheme::class);
    }
}
