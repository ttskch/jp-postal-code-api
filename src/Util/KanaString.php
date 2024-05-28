<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Util;

final readonly class KanaString
{
    public static function normalize(string $string): string
    {
        $string = mb_convert_kana($string, 'ASKV');
        $string = strval(preg_replace('/\(/', '（', $string));
        $string = strval(preg_replace('/\)/', '）', $string));

        return $string;
    }
}
