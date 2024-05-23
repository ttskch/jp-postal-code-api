<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Csv\Jigyosyo;

use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\Model\Address;
use Ttskch\JpPostalCodeApi\Model\ApiResource;
use Ttskch\JpPostalCodeApi\Model\PrefectureCode;

final readonly class CsvParser implements CsvParserInterface
{
    public function parse(array $csvRow): ApiResource
    {
        $postalCode = trim($csvRow[CsvHeaders::POSTAL_CODE]);

        $prefectureJa = trim($csvRow[CsvHeaders::PREFECTURE_JA]);

        $prefCode = trim(PrefectureCode::of($prefectureJa));

        $address1Ja = trim($csvRow[CsvHeaders::ADDRESS_1_JA]);

        $address2Ja = trim($csvRow[CsvHeaders::ADDRESS_2_JA]);

        $address3Ja = trim($csvRow[CsvHeaders::ADDRESS_3_JA]);

        $address4Ja = trim($csvRow[CsvHeaders::ADDRESS_4_JA]);
        $address4Ja = strval(preg_replace('/　/', ' ', $address4Ja));

        return new ApiResource(
            $postalCode,
            $prefCode,
            ja: new Address($prefectureJa, $address1Ja, $address2Ja, $address3Ja, $address4Ja),
            en: new Address(),
        );
    }
}
