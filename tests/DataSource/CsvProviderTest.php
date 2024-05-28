<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\DataSource;

use PHPUnit\Framework\TestCase;

class CsvProviderTest extends TestCase
{
    public function testFromZipUrl(): void
    {
        $SUT = new CsvProvider();

        $actual = $SUT->fromZipUrl(__DIR__.'/../resources/ken_all.zip');

        self::assertSame(124683, $actual->count());

        foreach ($actual as $i => $row) {
            if (0 === $i) {
                self::assertSame([
                    '01101',
                    '060  ',
                    '0600000',
                    'ﾎｯｶｲﾄﾞｳ',
                    'ｻｯﾎﾟﾛｼﾁｭｳｵｳｸ',
                    'ｲｶﾆｹｲｻｲｶﾞﾅｲﾊﾞｱｲ',
                    '北海道',
                    '札幌市中央区',
                    '以下に掲載がない場合',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                ], $row);
            } elseif ($i === $actual->count() - 1) {
                self::assertSame([
                    '47382',
                    '90718',
                    '9071801',
                    'ｵｷﾅﾜｹﾝ',
                    'ﾔｴﾔﾏｸﾞﾝﾖﾅｸﾞﾆﾁｮｳ',
                    'ﾖﾅｸﾞﾆ',
                    '沖縄県',
                    '八重山郡与那国町',
                    '与那国',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                ], $row);
            }
        }

        $actual = $SUT->fromZipUrl(__DIR__.'/../resources/KEN_ALL_ROME.zip');

        self::assertSame(124574, $actual->count());

        foreach ($actual as $i => $row) {
            if (0 === $i) {
                self::assertSame([
                    '0600000',
                    '北海道',
                    '札幌市　中央区',
                    '以下に掲載がない場合',
                    'HOKKAIDO',
                    'SAPPORO SHI CHUO KU',
                    'IKANIKEISAIGANAIBAAI',
                ], $row);
            } elseif ($i === $actual->count() - 1) {
                self::assertSame([
                    '9071801',
                    '沖縄県',
                    '八重山郡　与那国町',
                    '与那国',
                    'OKINAWA KEN',
                    'YAEYAMA GUN YONAGUNI CHO',
                    'YONAGUNI',
                ], $row);
            }
        }

        $actual = $SUT->fromZipUrl(__DIR__.'/../resources/jigyosyo.zip');

        self::assertSame(22409, $actual->count());

        foreach ($actual as $i => $row) {
            if (0 === $i) {
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
            } elseif ($i === $actual->count() - 1) {
                self::assertSame([
                    '47382',
                    'ﾖﾅｸﾞﾆﾁﾖｳﾔｸﾊﾞ',
                    '与那国町役場',
                    '沖縄県',
                    '八重山郡与那国町',
                    '字与那国',
                    '１２９',
                    '9071892',
                    '90718',
                    '八重山',
                    '0',
                    '0',
                    '0',
                ], $row);
            }
        }
    }
}
