<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi;

use PHPUnit\Framework\TestCase;

class JpPostalCodeApiTest extends TestCase
{
    protected JpPostalCodeApi $jpPostalCodeApi;

    protected function setUp(): void
    {
        $this->jpPostalCodeApi = new JpPostalCodeApi();
    }

    public function testIsInstanceOfJpPostalCodeApi(): void
    {
        $actual = $this->jpPostalCodeApi;
        self::assertInstanceOf(JpPostalCodeApi::class, $actual);
    }
}
