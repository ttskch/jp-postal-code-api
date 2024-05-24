<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Csv\KenAll;

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
                    '0600042',
                    '北海道',
                    '札幌市　中央区',
                    '以下に掲載がない場合',
                    'HOKKAIDO',
                    'SAPPORO SHI CHUO KU',
                    'IKANIKEISAIGANAIBAAI',
                ],
                [
                    '0600042',
                    '01',
                    '北海道',
                    '札幌市中央区',
                    '',
                    '',
                    '',
                    'Hokkaido',
                    'Chuo-ku, Sapporo-shi',
                    '',
                    '',
                    '',
                ],
            ],
            [
                [
                    '0600042',
                    '北海道',
                    '札幌市　中央区',
                    '大通西（１～１９丁目）',
                    'HOKKAIDO',
                    'SAPPORO SHI CHUO KU',
                    'ODORINISHI(1-19-CHOME)',
                ],
                [
                    '0600042',
                    '01',
                    '北海道',
                    '札幌市中央区',
                    '大通西',
                    '',
                    '',
                    'Hokkaido',
                    'Chuo-ku, Sapporo-shi',
                    'Odorinishi',
                    '',
                    '',
                ],
            ],
            [
                [
                    '0660005',
                    '北海道',
                    '千歳市',
                    '協和（８８－２、２７１－１０、３４',
                    'HOKKAIDO',
                    'CHITOSE SHI',
                    'KYOWA(88-2.271-10.343-2.404-1.427-',
                ],
                [
                    '0660005',
                    '01',
                    '北海道',
                    '千歳市',
                    '協和',
                    '',
                    '',
                    'Hokkaido',
                    'Chitose-shi',
                    'Kyowa',
                    '',
                    '',
                ],
            ],
            [
                [
                    '0660005',
                    '北海道',
                    '千歳市',
                    '３、４３１－１２、４４３－６、６０',
                    'HOKKAIDO',
                    'CHITOSE SHI',
                    '3.431-12.443-6.608-2.641-8.814.842-',
                ],
                [
                    '0660005',
                    '01',
                    '北海道',
                    '千歳市',
                    '',
                    '',
                    '',
                    'Hokkaido',
                    'Chitose-shi',
                    '',
                    '',
                    '',
                ],
            ],
        ];
    }
}
