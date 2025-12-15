<?php

namespace ImamHasan\ThemeManager\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

trait HasTablePrefix
{
    /**
     * Get the table name with prefix applied.
     */
    public function getTable(): string
    {
        $table = $this->table ?? $this->getTableNameFromClass();
        $prefix = $this->getTablePrefix();

        if (empty($prefix)) {
            return $table;
        }

        return $prefix . $table;
    }

    /**
     * Get the table prefix from config.
     */
    protected function getTablePrefix(): string
    {
        return Config::get('theme-manager.table_prefix', '');
    }

    /**
     * Get table name from class name if not explicitly set.
     */
    protected function getTableNameFromClass(): string
    {
        if (isset($this->table)) {
            return $this->table;
        }

        return str_replace('\\', '', Str::snake(Str::plural(class_basename($this))));
    }
}

