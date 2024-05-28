<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Application;
use Ttskch\JpPostalCodeApi\Command\BuildCommand;
use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\DataSource\CsvProviderInterface;
use Ttskch\JpPostalCodeApi\FileSystem\BaseDirectoryInterface;

class JpPostalCodeApiTest extends TestCase
{
    use ProphecyTrait;

    protected JpPostalCodeApi $SUT;

    protected function setUp(): void
    {
        $console = $this->prophesize(Application::class);
        $console->setName('jp-postal-code-api console')->shouldBeCalled();
        $console->add(Argument::type(BuildCommand::class))->shouldBeCalled();
        $console->run()->shouldBeCalled();

        $this->SUT = new JpPostalCodeApi(
            $this->prophesize(CsvProviderInterface::class)->reveal(),
            $this->prophesize(CsvParserInterface::class)->reveal(),
            $this->prophesize(CsvParserInterface::class)->reveal(),
            $this->prophesize(CsvParserInterface::class)->reveal(),
            $this->prophesize(BaseDirectoryInterface::class)->reveal(),
            $console->reveal(),
        );
    }

    public function testIsInstanceOfJpPostalCodeApi(): void
    {
        $this->SUT->run();
    }
}
