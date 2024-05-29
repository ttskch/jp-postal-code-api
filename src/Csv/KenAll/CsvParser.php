<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Csv\KenAll;

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
        $address1Ja = strval(preg_replace('/　/', '', $address1Ja));

        $address2Ja = trim($csvRow[CsvHeaders::ADDRESS_2_JA]);
        $address2Ja = strval(preg_replace('/　/', '', $address2Ja));
        $address2Ja = strval(preg_replace('/（.*/', '', $address2Ja));
        $address2Ja = strval(preg_replace('/.*）/', '', $address2Ja));
        $address2Ja = strval(preg_replace('/.*場合$/', '', $address2Ja));
        $address2Ja = strval(preg_replace('/.*一円$/', '', $address2Ja));

        $prefectureKana = trim($csvRow[CsvHeaders::PREFECTURE_KANA]);
        $prefectureKana = KanaString::normalize($prefectureKana);

        $address1Kana = trim($csvRow[CsvHeaders::ADDRESS_1_KANA]);
        $address1Kana = KanaString::normalize($address1Kana);

        $address2Kana = trim($csvRow[CsvHeaders::ADDRESS_2_KANA]);
        $address2Kana = KanaString::normalize($address2Kana);
        $address2Kana = strval(preg_replace('/（.*/', '', $address2Kana));
        $address2Kana = strval(preg_replace('/.*）/', '', $address2Kana));
        $address2Kana = strval(preg_replace('/.*バアイ$/', '', $address2Kana));
        $address2Kana = strval(preg_replace('/.*イチエン$/', '', $address2Kana));

        // if address2Ja contains '、', it's some irregular address, so ignore it for now.
        if (false !== mb_strpos($address2Ja, '、')) {
            $address2Ja = '';
            $address2Kana = '';
        }

        $address = new Address(
            $prefectureCode,
            ja: new AddressUnit($prefectureJa, $address1Ja, $address2Ja),
            kana: new AddressUnit($prefectureKana, $address1Kana, $address2Kana),
        );

        return new ParsedCsvRow($postalCode, $address);
    }
}
