<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Model;

use PHPUnit\Framework\TestCase;

class ApiResourceTest extends TestCase
{
    private ApiResource $SUT;

    protected function setUp(): void
    {
        $this->SUT = new ApiResource('0640941', [
            new Address(
                '01',
                ja: new AddressUnit('北海道', '札幌市中央区', '旭ケ丘'),
                en: new AddressUnit('Hokkaido', 'Chuo-ku, Sapporo-shi', 'Asahigaoka'),
            ),
        ]);
    }

    public function testJsonSerialize(): void
    {
        $json = json_encode($this->SUT, flags: JSON_THROW_ON_ERROR);
        $expected = <<<JSON
{"postalCode":"0640941","addresses":[{"prefectureCode":"01","ja":{"prefecture":"\u5317\u6d77\u9053","address1":"\u672d\u5e4c\u5e02\u4e2d\u592e\u533a","address2":"\u65ed\u30b1\u4e18","address3":"","address4":""},"en":{"prefecture":"Hokkaido","address1":"Chuo-ku, Sapporo-shi","address2":"Asahigaoka","address3":"","address4":""}}]}
JSON;
        self:self::assertSame($expected, $json);
    }

    public function testFromJson(): void
    {
        $json = <<<JSON
{"postalCode":"0640941","addresses":[{"prefectureCode":"01","ja":{"prefecture":"\u5317\u6d77\u9053","address1":"\u672d\u5e4c\u5e02\u4e2d\u592e\u533a","address2":"\u65ed\u30b1\u4e18","address3":"","address4":""},"en":{"prefecture":"Hokkaido","address1":"Chuo-ku, Sapporo-shi","address2":"Asahigaoka","address3":"","address4":""}}]}
JSON;
        $actual = ApiResource::fromJson($json);
        self::assertEquals($this->SUT, $actual);
    }
}
