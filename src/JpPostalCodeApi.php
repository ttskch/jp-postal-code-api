<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi;

use Symfony\Component\Console\Application;
use Ttskch\JpPostalCodeApi\Command\BuildCommand;
use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\DataSource\CsvProviderInterface;
use Ttskch\JpPostalCodeApi\FileSystem\BaseDirectoryInterface;

final readonly class JpPostalCodeApi
{
    public function __construct(
        private CsvProviderInterface $csvProvider,
        private CsvParserInterface $kenAllCsvParser,
        private CsvParserInterface $jigyosyoCsvParser,
        private BaseDirectoryInterface $baseDirectory,
    ) {
    }

    public function run(): void
    {
        $console = new Application();
        $console->setName('jp-postal-code-api console');
        $console->add(new BuildCommand(
            $this->csvProvider,
            $this->kenAllCsvParser,
            $this->jigyosyoCsvParser,
            $this->baseDirectory,
        ));
        $console->run();
    }
}
