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
    private Application $console;

    public function __construct(
        private CsvProviderInterface $csvProvider,
        private CsvParserInterface $kenAllCsvParser,
        private CsvParserInterface $kenAllRomeCsvParser,
        private CsvParserInterface $jigyosyoCsvParser,
        private BaseDirectoryInterface $baseDirectory,
        ?Application $console = null,
    ) {
        $this->console = $console ?? new Application();
    }

    public function run(): void
    {
        $this->console->setName('jp-postal-code-api console');
        $this->console->add(new BuildCommand(
            $this->csvProvider,
            $this->kenAllCsvParser,
            $this->kenAllRomeCsvParser,
            $this->jigyosyoCsvParser,
            $this->baseDirectory,
        ));
        $this->console->run();
    }
}
