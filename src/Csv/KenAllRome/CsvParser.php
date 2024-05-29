<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Csv\KenAllRome;

use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\Model\Address;
use Ttskch\JpPostalCodeApi\Model\AddressUnit;
use Ttskch\JpPostalCodeApi\Model\ParsedCsvRow;
use Ttskch\JpPostalCodeApi\Model\PrefectureCode;

final readonly class CsvParser implements CsvParserInterface
{
    public function parse(array $csvRow): ParsedCsvRow
    {
        $postalCode = trim($csvRow[CsvHeaders::POSTAL_CODE]);

        $prefectureJa = trim($csvRow[CsvHeaders::PREFECTURE_JA]);

        $prefectureCode = trim(PrefectureCode::of($prefectureJa));

        $address1Ja = trim($csvRow[CsvHeaders::ADDRESS_1_JA]);
        $address1Ja = strval(preg_replace('/　/', '', $address1Ja));

        $address2Ja = trim($csvRow[CsvHeaders::ADDRESS_2_JA]);
        $address2Ja = strval(preg_replace('/　/', '', $address2Ja));
        $address2Ja = strval(preg_replace('/（.*/', '', $address2Ja));
        $address2Ja = strval(preg_replace('/.*）/', '', $address2Ja));
        $address2Ja = strval(preg_replace('/.*場合$/', '', $address2Ja));
        $address2Ja = strval(preg_replace('/.*一円$/', '', $address2Ja));

        $prefectureEn = trim($csvRow[CsvHeaders::PREFECTURE_EN]);
        $prefectureEn = strval(preg_replace('/ .+$/', '', $prefectureEn));
        $prefectureEn = ucwords(strtolower($prefectureEn));

        $address1En = trim($csvRow[CsvHeaders::ADDRESS_1_EN]);
        $address1En = strval(preg_replace('/^([A-Z]+) ([A-Z]+)$/', '$1-$2', $address1En));
        $address1En = strval(preg_replace('/^([A-Z]+) ([A-Z]+) ([A-Z]+) ([A-Z]+)$/', '$3-$4, $1-$2', $address1En));
        $address1En = ucwords(strtolower($address1En));

        $address2En = trim($csvRow[CsvHeaders::ADDRESS_2_EN]);
        $address2En = strval(preg_replace('/\(.*/', '', $address2En));
        $address2En = strval(preg_replace('/.*\)/', '', $address2En));
        $address2En = strval(preg_replace('/.*baai$/i', '', $address2En));
        $address2En = strval(preg_replace('/.*ichien$/i', '', $address2En));
        $address2En = strval(preg_replace('/^([A-Z]+) ([A-Z]+)$/', '$2, $1', $address2En));
        $address2En = ucwords(strtolower($address2En));

        // if address2Ja contains '、', it's some irregular address, so ignore it for now.
        if (false !== mb_strpos($address2Ja, '、')) {
            $address2Ja = '';
            $address2En = '';
        }

        $address = new Address(
            $prefectureCode,
            ja: new AddressUnit($prefectureJa, $address1Ja, $address2Ja),
            en: new AddressUnit($prefectureEn, $address1En, $address2En),
        );

        return new ParsedCsvRow($postalCode, $address);
    }
}
