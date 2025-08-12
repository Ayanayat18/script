<?php
namespace App\Core;

class CSRF
{
    public static function token(): string
    {
        if (empty($_SESSION[CSRF_TOKEN_KEY])) {
            $_SESSION[CSRF_TOKEN_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_KEY];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . self::token() . '">';
    }

    public static function validate(?string $token): bool
    {
        return hash_equals($_SESSION[CSRF_TOKEN_KEY] ?? '', (string)$token);
    }
}