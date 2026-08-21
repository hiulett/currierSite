<?php

namespace App\Helpers;

class DatabaseHelper
{
    /**
     * Returns a database-specific date format string for raw queries.
     * Supports SQLite (strftime), MySQL/MariaDB (DATE_FORMAT) and PostgreSQL.
     *
     * @param string $column The column name
     * @param string $format SQLite style format: 'm' for month, 'Y-m' for year-month, etc.
     * @return string
     */
    public static function formatMonth($column = 'created_at', $format = '%m')
    {
        $driver = config('database.default');

        if ($driver === 'sqlite') {
            return "strftime('{$format}', $column)";
        }

        if ($driver === 'pgsql' || $driver === 'postgres') {
            // Convert SQLite specifiers to PostgreSQL (to_char)
            $pgFormat = str_replace(['%Y', '%m', '%d'], ['YYYY', 'MM', 'DD'], $format);
            return "to_char($column, '$pgFormat')";
        }

        // MySQL / MariaDB use DATE_FORMAT with the same % specifiers
        return "DATE_FORMAT($column, '{$format}')";
    }
}
