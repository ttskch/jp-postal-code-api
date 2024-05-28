<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\DataSource\CsvProviderInterface;
use Ttskch\JpPostalCodeApi\DataSource\ZipUrls;
use Ttskch\JpPostalCodeApi\FileSystem\BaseDirectoryInterface;

#[AsCommand(
    name: 'build',
    description: 'Build JSON files from JAPAN POST\'S official CSV data'
)]
final class BuildCommand extends Command
{
    public function __construct(
        readonly private CsvProviderInterface $csvProvider,
        readonly private CsvParserInterface $kenAllCsvParser,
        readonly private CsvParserInterface $jigyosyoCsvParser,
        readonly private BaseDirectoryInterface $baseDirectory,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $kenAllCsv = $this->csvProvider->fromZipUrl(ZipUrls::KEN_ALL);
        $jigyosyoCsv = $this->csvProvider->fromZipUrl(ZipUrls::JIGYOSYO);

        $total = $kenAllCsv->count() + $jigyosyoCsv->count();

        $io = new SymfonyStyle($input, $output);
        $io->progressStart($total);

        $this->baseDirectory->clear();

        /** @var array<int, string> $row */
        foreach ($kenAllCsv as $row) {
            $parsedRow = $this->kenAllCsvParser->parse($row);
            $this->baseDirectory->putJsonFile($parsedRow);
            $io->progressAdvance();
        }

        /** @var array<int, string> $row */
        foreach ($jigyosyoCsv as $row) {
            $parsedRow = $this->jigyosyoCsvParser->parse($row);
            $this->baseDirectory->putJsonFile($parsedRow);
            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success('Finished!');

        return Command::SUCCESS;
    }
}
