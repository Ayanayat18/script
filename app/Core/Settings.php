<?php
namespace App\Core;

class Settings
{
    private static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }
        $row = DB::fetch('SELECT `value` FROM settings WHERE `key` = :k LIMIT 1', ['k' => $key]);
        if ($row) {
            self::$cache[$key] = $row['value'];
            return $row['value'];
        }
        return $default;
    }

    public static function set(string $key, string $value): void
    {
        $exists = DB::fetch('SELECT id FROM settings WHERE `key` = :k LIMIT 1', ['k' => $key]);
        if ($exists) {
            DB::query('UPDATE settings SET `value` = :v WHERE `key` = :k', ['v' => $value, 'k' => $key]);
        } else {
            DB::insert('INSERT INTO settings (`key`,`value`) VALUES (:k,:v)', ['k' => $key, 'v' => $value]);
        }
        self::$cache[$key] = $value;
    }
}