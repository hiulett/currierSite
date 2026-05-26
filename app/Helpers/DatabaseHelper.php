<?php

namespace App\Helpers;

class DatabaseHelper
{
    /**
     * Returns a database-specific date format string for raw queries.
     * Supports SQLite (strftime) and MySQL (DATE_FORMAT).
     *
     * @param string $column The column name
     * @param string $format 'm' for month, 'Y-m' for year-month, etc.
     * @return string
     */
    public static function formatMonth($column = 'created_at', $format = '%m')
    {
        $driver = config('database.default');

        if ($driver === 'sqlite') {
            return "strftime('{$format}', $column)";
        }

        // MySQL equivalent
        $mysqlFormat = str_replace('%', '', $format); // Convert %m to m
        return "DATE_FORMAT($column, '%{$mysqlFormat}')";
    }
}
