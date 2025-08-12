<?php
namespace App\Core;

class TOTP
{
    public static function verifyCode(string $secret, string $code, int $window = 1): bool
    {
        $timeSlice = floor(time() / 30);
        for ($i = -$window; $i <= $window; $i++) {
            $calc = self::calcCode($secret, $timeSlice + $i);
            if (hash_equals($calc, $code)) {
                return true;
            }
        }
        return false;
    }

    public static function calcCode(string $secret, int $timeSlice): string
    {
        $secretKey = self::base32Decode($secret);
        $time = pack('N*', 0) . pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $truncatedHash = substr($hash, $offset, 4);
        $value = unpack('N', $truncatedHash)[1] & 0x7FFFFFFF;
        $modulo = 10 ** 6;
        return str_pad((string)($value % $modulo), 6, '0', STR_PAD_LEFT);
    }

    public static function generateSecret(int $length = 16): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        return $secret;
    }

    public static function base32Decode(string $secret): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = strtoupper($secret);
        $secret = preg_replace('/[^A-Z2-7]/', '', $secret);
        $buffer = 0;
        $bitsLeft = 0;
        $output = '';
        for ($i = 0; $i < strlen($secret); $i++) {
            $buffer = ($buffer << 5) | strpos($alphabet, $secret[$i]);
            $bitsLeft += 5;
            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $output .= chr(($buffer & (0xFF << $bitsLeft)) >> $bitsLeft);
            }
        }
        return $output;
    }
}