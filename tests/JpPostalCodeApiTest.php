<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\DataSource\CsvProviderInterface;
use Ttskch\JpPostalCodeApi\FileSystem\BaseDirectoryInterface;

class JpPostalCodeApiTest extends TestCase
{
    use ProphecyTrait;

    protected JpPostalCodeApi $jpPostalCodeApi;

    protected function setUp(): void
    {
        $this->jpPostalCodeApi = new JpPostalCodeApi(
            $this->prophesize(CsvProviderInterface::class)->reveal(),
            $this->prophesize(CsvParserInterface::class)->reveal(),
            $this->prophesize(CsvParserInterface::class)->reveal(),
            $this->prophesize(CsvParserInterface::class)->reveal(),
            $this->prophesize(BaseDirectoryInterface::class)->reveal(),
        );
    }

    public function testIsInstanceOfJpPostalCodeApi(): void
    {
        $actual = $this->jpPostalCodeApi;
        self::assertInstanceOf(JpPostalCodeApi::class, $actual);
    }
}
