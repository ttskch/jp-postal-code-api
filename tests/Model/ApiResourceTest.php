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
                kana: new AddressUnit('ホッカイドウ', 'サッポロシチュウオウク', 'アサヒガオカ'),
                en: new AddressUnit('Hokkaido', 'Chuo-ku, Sapporo-shi', 'Asahigaoka'),
            ),
        ]);
    }

    public function testJsonSerialize(): void
    {
        $json = json_encode($this->SUT, flags: JSON_THROW_ON_ERROR);
        $expected = <<<JSON
{
  "postalCode": "0640941",
  "addresses": [
    {
      "prefectureCode": "01",
      "ja": {
        "prefecture": "北海道",
        "address1": "札幌市中央区",
        "address2": "旭ケ丘",
        "address3": "",
        "address4": ""
      },
      "kana": {
        "prefecture": "ホッカイドウ",
        "address1": "サッポロシチュウオウク",
        "address2": "アサヒガオカ",
        "address3": "",
        "address4": ""
      },
      "en": {
        "prefecture": "Hokkaido",
        "address1": "Chuo-ku, Sapporo-shi",
        "address2": "Asahigaoka",
        "address3": "",
        "address4": ""
      }
    }
  ]
}
JSON;
        self::assertJsonStringEqualsJsonString($expected, $json);
    }

    public function testFromJson(): void
    {
        $json = <<<JSON
{
  "postalCode": "0640941",
  "addresses": [
    {
      "prefectureCode": "01",
      "ja": {
        "prefecture": "北海道",
        "address1": "札幌市中央区",
        "address2": "旭ケ丘",
        "address3": "",
        "address4": ""
      },
      "kana": {
        "prefecture": "ホッカイドウ",
        "address1": "サッポロシチュウオウク",
        "address2": "アサヒガオカ",
        "address3": "",
        "address4": ""
      },
      "en": {
        "prefecture": "Hokkaido",
        "address1": "Chuo-ku, Sapporo-shi",
        "address2": "Asahigaoka",
        "address3": "",
        "address4": ""
      }
    }
  ]
}
JSON;
        $actual = ApiResource::fromJson($json);
        self::assertEquals($this->SUT, $actual); // not `assertSame()`
    }
}
