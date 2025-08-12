<?php
namespace App\Core;

class Notifier
{
    public static function notify(?int $userId, string $title, string $message): void
    {
        DB::insert('INSERT INTO notifications (user_id, title, message, created_at) VALUES (:uid,:t,:m,NOW())', [
            'uid' => $userId,
            't' => $title,
            'm' => $message,
        ]);
    }

    public static function adminAlert(string $message): void
    {
        Telegram::send($message);
        if (defined('DEFAULT_ADMIN_EMAIL') && DEFAULT_ADMIN_EMAIL) {
            Mailer::send(DEFAULT_ADMIN_EMAIL, SITE_NAME . ' - Alert', nl2br(htmlentities($message)));
        }
    }
}