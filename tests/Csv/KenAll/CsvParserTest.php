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
        self::assertSame($expected[7], $actual->address->kana->prefecture);
        self::assertSame($expected[8], $actual->address->kana->address1);
        self::assertSame($expected[9], $actual->address->kana->address2);
        self::assertSame($expected[10], $actual->address->kana->address3);
        self::assertSame($expected[11], $actual->address->kana->address4);
    }

    /**
     * @return array<mixed>
     */
    public function parseDataProvider(): array
    {
        return [
            [
                [
                    '01101',
                    '060',
                    '0600000',
                    'ﾎｯｶｲﾄﾞｳ',
                    'ｻｯﾎﾟﾛｼﾁｭｳｵｳｸ',
                    'ｲｶﾆｹｲｻｲｶﾞﾅｲﾊﾞｱｲ',
                    '北海道',
                    '札幌市中央区',
                    '以下に掲載がない場合',
                ],
                [
                    '0600000',
                    '01',
                    '北海道',
                    '札幌市中央区',
                    '',
                    '',
                    '',
                    'ホッカイドウ',
                    'サッポロシチュウオウク',
                    '',
                    '',
                    '',
                ],
            ],
            [
                [
                    '01101',
                    '060',
                    '0600042',
                    'ﾎｯｶｲﾄﾞｳ',
                    'ｻｯﾎﾟﾛｼﾁｭｳｵｳｸ',
                    'ｵｵﾄﾞｵﾘﾆｼ(1-19ﾁｮｳﾒ)',
                    '北海道',
                    '札幌市中央区',
                    '大通西（１～１９丁目）',
                ],
                [
                    '0600042',
                    '01',
                    '北海道',
                    '札幌市中央区',
                    '大通西',
                    '',
                    '',
                    'ホッカイドウ',
                    'サッポロシチュウオウク',
                    'オオドオリニシ',
                    '',
                    '',
                ],
            ],
            [
                [
                    '01224',
                    '066',
                    '0660005',
                    'ﾎｯｶｲﾄﾞｳ',
                    'ﾁﾄｾｼ',
                    'ｷｮｳﾜ(88-2､271-10､343-2､404-1､427-',
                    '北海道',
                    '千歳市',
                    '協和（８８－２、２７１－１０、３４３－２、４０４－１、４２７－',
                ],
                [
                    '0660005',
                    '01',
                    '北海道',
                    '千歳市',
                    '協和',
                    '',
                    '',
                    'ホッカイドウ',
                    'チトセシ',
                    'キョウワ',
                    '',
                    '',
                ],
            ],
            [
                [
                    '01224',
                    '066',
                    '0660005',
                    'ﾎｯｶｲﾄﾞｳ',
                    'ﾁﾄｾｼ',
                    '3､431-12､443-6､608-2､641-8､814､842-',
                    '北海道',
                    '千歳市',
                    '３、４３１－１２、４４３－６、６０８－２、６４１－８、８１４、８４２－',
                ],
                [
                    '0660005',
                    '01',
                    '北海道',
                    '千歳市',
                    '',
                    '',
                    '',
                    'ホッカイドウ',
                    'チトセシ',
                    '',
                    '',
                    '',
                ],
            ],
        ];
    }
}
