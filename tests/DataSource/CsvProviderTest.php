<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\DataSource;

use PHPUnit\Framework\TestCase;

class CsvProviderTest extends TestCase
{
    public function testFromZipUrl(): void
    {
        $SUT = new CsvProvider();

        $actual = $SUT->fromZipUrl(__DIR__.'/../resources/KEN_ALL_ROME.zip');

        self::assertSame(124574, $actual->count());

        foreach ($actual as $row) {
            self::assertSame([
                '0600000',
                '北海道',
                '札幌市　中央区',
                '以下に掲載がない場合',
                'HOKKAIDO',
                'SAPPORO SHI CHUO KU',
                'IKANIKEISAIGANAIBAAI',
            ], $row);
            break;
        }

        $actual = $SUT->fromZipUrl(__DIR__.'/../resources/jigyosyo.zip');

        // @see https://www.php.net/manual/ja/language.exceptions.php#language.exceptions.notes
        set_error_handler(fn ($severity, $message, $filename, $lineno) => throw new \ErrorException($message, 0, $severity, $filename, $lineno));
        try {
            self::assertSame(22409, $actual->count());
        } catch (\ErrorException $e) { // @phpstan-ignore-line
            if ('fgetcsv(): iconv stream filter ("Shift_JIS"=>"UTF-8"): invalid multibyte sequence' !== $e->getMessage()) {
                throw $e;
            }
        }

        foreach ($actual as $row) {
            self::assertSame([
                '01101',
                '(ｶﾌﾞ) ﾆﾎﾝｹｲｻﾞｲｼﾝﾌﾞﾝｼﾔ ｻﾂﾎﾟﾛｼｼﾔ',
                '株式会社　日本経済新聞社　札幌支社',
                '北海道',
                '札幌市中央区',
                '北一条西',
                '６丁目１−２アーバンネット札幌ビル２Ｆ',
                '0608621',
                '060  ',
                '札幌中央',
                '0',
                '0',
                '0',
            ], $row);
            break;
        }
    }
}
