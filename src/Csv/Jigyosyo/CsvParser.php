<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Csv\Jigyosyo;

use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\Model\Address;
use Ttskch\JpPostalCodeApi\Model\AddressUnit;
use Ttskch\JpPostalCodeApi\Model\ParsedCsvRow;
use Ttskch\JpPostalCodeApi\Model\PrefectureCode;
use Ttskch\JpPostalCodeApi\Util\KanaString;

final readonly class CsvParser implements CsvParserInterface
{
    public function parse(array $csvRow): ParsedCsvRow
    {
        $postalCode = trim($csvRow[CsvHeaders::POSTAL_CODE]);

        $prefectureJa = trim($csvRow[CsvHeaders::PREFECTURE_JA]);

        $prefectureCode = trim(PrefectureCode::of($prefectureJa));

        $address1Ja = trim($csvRow[CsvHeaders::ADDRESS_1_JA]);

        $address2Ja = trim($csvRow[CsvHeaders::ADDRESS_2_JA]);

        $address3Ja = trim($csvRow[CsvHeaders::ADDRESS_3_JA]);

        $address4Ja = trim($csvRow[CsvHeaders::ADDRESS_4_JA]);
        $address4Ja = strval(preg_replace('/　/', ' ', $address4Ja));

        $address4Kana = trim($csvRow[CsvHeaders::ADDRESS_4_KANA]);
        $address4Kana = KanaString::normalize($address4Kana);

        $address = new Address(
            $prefectureCode,
            ja: new AddressUnit($prefectureJa, $address1Ja, $address2Ja, $address3Ja, $address4Ja),
            kana: new AddressUnit('', '', '', '', $address4Kana),
        );

        return new ParsedCsvRow($postalCode, $address);
    }
}
