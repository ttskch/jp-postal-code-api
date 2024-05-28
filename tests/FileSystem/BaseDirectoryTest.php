<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\FileSystem;

use PHPUnit\Framework\TestCase;
use Ttskch\JpPostalCodeApi\Model\Address;
use Ttskch\JpPostalCodeApi\Model\AddressUnit;
use Ttskch\JpPostalCodeApi\Model\ParsedCsvRow;

class BaseDirectoryTest extends TestCase
{
    protected string $path;
    protected BaseDirectory $SUT;

    protected function setUp(): void
    {
        $this->path = sys_get_temp_dir().'/'.md5(uniqid());

        mkdir($this->path);

        $jsonContent = <<<JSON
{"postalCode":"0600000","addresses":[{"prefectureCode":"01","ja":{"prefecture":"北海道","address1":"札幌市中央区","address2":"旭ケ丘","address3":"","address4":""},"en":{"prefecture":"Hokkaido","address1":"Chuo-ku, Sapporo-shi","address2":"Asahigaoka","address3":"","address4":""}}]}
JSON;
        file_put_contents(sprintf('%s/1.json', $this->path), '');
        file_put_contents(sprintf('%s/2.json', $this->path), '');
        file_put_contents(sprintf('%s/0600000.json', $this->path), $jsonContent);

        $this->SUT = new BaseDirectory($this->path);
    }

    public function testClear(): void
    {
        $this->SUT->clear();

        self::assertTrue(is_dir($this->path));
        self::assertFalse(file_exists(sprintf('%s/1.json', $this->path)));
        self::assertFalse(file_exists(sprintf('%s/2.json', $this->path)));
        self::assertFalse(file_exists(sprintf('%s/0600000.json', $this->path)));
    }

    public function testPutJsonFile(): void
    {
        $this->SUT->putJsonFile(new ParsedCsvRow(
            '0600000',
            new Address(
                '02',
                ja: new AddressUnit('test', 'test', 'test'),
                en: new AddressUnit(),
            ),
        ));

        $content = strval(file_get_contents(sprintf('%s/0600000.json', $this->path)));
        $expected = <<<JSON
{"postalCode":"0600000","addresses":[{"prefectureCode":"01","ja":{"prefecture":"北海道","address1":"札幌市中央区","address2":"旭ケ丘","address3":"","address4":""},"en":{"prefecture":"Hokkaido","address1":"Chuo-ku, Sapporo-shi","address2":"Asahigaoka","address3":"","address4":""}},{"prefectureCode":"02","ja":{"prefecture":"test","address1":"test","address2":"test","address3":"","address4":""},"en":{"prefecture":"","address1":"","address2":"","address3":"","address4":""}}]}
JSON;

        self::assertSame($expected, $content);
    }
}
