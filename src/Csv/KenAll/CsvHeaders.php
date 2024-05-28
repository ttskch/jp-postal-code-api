<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Csv\KenAll;

final readonly class CsvHeaders
{
    public const int POSTAL_CODE = 2;
    public const int PREFECTURE_JA = 6;
    public const int ADDRESS_1_JA = 7;
    public const int ADDRESS_2_JA = 8;
    public const int PREFECTURE_KANA = 3;
    public const int ADDRESS_1_KANA = 4;
    public const int ADDRESS_2_KANA = 5;
}
