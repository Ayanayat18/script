<?php
namespace App\Core;

class Helpers
{
    public static function url(string $path = ''): string
    {
        $base = defined('APP_URL') ? rtrim(APP_URL, '/') : '';
        $path = '/' . ltrim($path, '/');
        return $base . $path;
    }

    public static function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    public static function now(): string
    {
        return date('Y-m-d H:i:s');
    }
}