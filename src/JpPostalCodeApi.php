<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi;

use Symfony\Component\Console\Application;
use Ttskch\JpPostalCodeApi\Command\BuildCommand;
use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\DataSource\CsvProviderInterface;

final readonly class JpPostalCodeApi
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function __construct(
        private CsvProviderInterface $csvProvider,
        private CsvParserInterface $kenAllCsvParser,
        private CsvParserInterface $jigyosyoCsvParser,
    ) {
    }

    public function run(): void
    {
        $console = new Application();
        $console->setName('jp-postal-code-api console');
        $console->add(new BuildCommand($this->csvProvider, $this->kenAllCsvParser, $this->jigyosyoCsvParser));
        $console->run();
    }
}
