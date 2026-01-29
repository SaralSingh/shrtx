<?php

namespace App\Services;

class Base62Encoder
{
    private const CHARSET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function encode(int $number): string
    {
        if ($number === 0) {
            return self::CHARSET[0];
        }

        $base = strlen(self::CHARSET);
        $encoded = '';

        while ($number > 0) {
            $encoded = self::CHARSET[$number % $base] . $encoded;
            $number = intdiv($number, $base);
        }

        return $encoded;
    }
}
