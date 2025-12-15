<?php

namespace ImamHasan\ThemeManager\Helpers;

class TablePrefixHelper
{
    /**
     * Get the table name with prefix applied.
     *
     * @param string $tableName
     * @return string
     */
    public static function getTableName(string $tableName): string
    {
        $prefix = config('theme-manager.table_prefix', '');

        if (empty($prefix)) {
            return $tableName;
        }

        return $prefix . $tableName;
    }

    /**
     * Check if a table should be prefixed.
     * Laravel default tables (users, migrations, etc.) should not be prefixed.
     *
     * @param string $tableName
     * @return bool
     */
    public static function shouldPrefix(string $tableName): bool
    {
        $excludedTables = ['users', 'migrations', 'password_reset_tokens', 'password_resets', 'failed_jobs', 'personal_access_tokens'];

        return !in_array($tableName, $excludedTables);
    }

    /**
     * Get prefixed table name if it should be prefixed.
     *
     * @param string $tableName
     * @return string
     */
    public static function prefixIfNeeded(string $tableName): string
    {
        if (!self::shouldPrefix($tableName)) {
            return $tableName;
        }

        return self::getTableName($tableName);
    }
}

