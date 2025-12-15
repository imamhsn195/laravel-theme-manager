<?php

namespace ImamHasan\ThemeManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ImamHasan\ThemeManager\Traits\HasTablePrefix;

class ThemeSetting extends Model
{
    use HasFactory, HasTablePrefix;

    protected $table = 'theme_settings';

    protected $fillable = ['key', 'value'];
}
