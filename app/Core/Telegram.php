<?php
namespace App\Core;

class Telegram
{
    public static function send(string $message): bool
    {
        if (!defined('TELEGRAM_BOT_TOKEN') || !TELEGRAM_BOT_TOKEN || !defined('TELEGRAM_CHAT_ID') || !TELEGRAM_CHAT_ID) {
            return false;
        }
        $url = 'https://api.telegram.org/bot' . urlencode(TELEGRAM_BOT_TOKEN) . '/sendMessage';
        $data = [
            'chat_id' => TELEGRAM_CHAT_ID,
            'text' => $message,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ];
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'timeout' => 10,
            ],
        ];
        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        return $result !== false;
    }
}