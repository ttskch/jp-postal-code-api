<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Csv\Jigyosyo;

use PHPUnit\Framework\TestCase;

class CsvParserTest extends TestCase
{
    /**
     * @dataProvider parseDataProvider
     *
     * @param array<int, string> $csvRow
     * @param array<int, string> $expected
     */
    public function testParse(array $csvRow, array $expected): void
    {
        $SUT = new CsvParser();

        $actual = $SUT->parse($csvRow);

        self::assertSame($expected[0], $actual->postalCode);
        self::assertSame($expected[1], $actual->address->prefectureCode);
        self::assertSame($expected[2], $actual->address->ja->prefecture);
        self::assertSame($expected[3], $actual->address->ja->address1);
        self::assertSame($expected[4], $actual->address->ja->address2);
        self::assertSame($expected[5], $actual->address->ja->address3);
        self::assertSame($expected[6], $actual->address->ja->address4);
        self::assertSame($expected[7], $actual->address->en->prefecture);
        self::assertSame($expected[8], $actual->address->en->address1);
        self::assertSame($expected[9], $actual->address->en->address2);
        self::assertSame($expected[10], $actual->address->en->address3);
        self::assertSame($expected[11], $actual->address->en->address4);
    }

    /**
     * @return array<mixed>
     */
    public function parseDataProvider(): array
    {
        return [
            [
                [
                    '01668',
                    'ｼﾗﾇｶﾁﾖｳｷﾞﾖｷﾞﾖｳ ｷﾖｳﾄﾞｳｸﾐｱｲ',
                    '白糠町漁業　協同組合',
                    '北海道',
                    '白糠郡白糠町',
                    '岬',
                    '１丁目２－４２',
                    '0880394',
                    '08803',
                    '白糠',
                    '0',
                    '0',
                    '0',
                ],
                [
                    '0880394',
                    '01',
                    '北海道',
                    '白糠郡白糠町',
                    '岬',
                    '１丁目２－４２',
                    '白糠町漁業 協同組合',
                    '',
                    '',
                    '',
                    '',
                    '',
                ],
            ],
        ];
    }
}
