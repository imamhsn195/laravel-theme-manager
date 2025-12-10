<?php

namespace ImamHasan\ThemeManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'package',
        'version',
        'description',
        'license_required',
        'is_active',
        'config',
    ];

    protected $casts = [
        'license_required' => 'boolean',
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }
}
