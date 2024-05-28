<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\DataSource\CsvProviderInterface;
use Ttskch\JpPostalCodeApi\DataSource\ZipUrls;
use Ttskch\JpPostalCodeApi\Model\ApiResource;
use Ttskch\JpPostalCodeApi\Model\ParsedCsvRow;

#[AsCommand(
    name: 'build',
    description: 'Build JSON files from JAPAN POST\'S official CSV data'
)]
final class BuildCommand extends Command
{
    private const string DEFAULT_DESTINATION_DIR = __DIR__.'/../../docs/api/v1';

    public function __construct(
        readonly private CsvProviderInterface $csvProvider,
        readonly private CsvParserInterface $kenAllCsvParser,
        readonly private CsvParserInterface $jigyosyoCsvParser,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('destination_dir', 'd', InputOption::VALUE_REQUIRED, 'Destination directory path', realpath(self::DEFAULT_DESTINATION_DIR));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $destinationDir = $input->getOption('destination_dir');
        assert(is_string($destinationDir));

        $kenAllCsv = $this->csvProvider->fromZipUrl(ZipUrls::KEN_ALL);
        $jigyosyoCsv = $this->csvProvider->fromZipUrl(ZipUrls::JIGYOSYO);

        $total = $kenAllCsv->count() + $jigyosyoCsv->count();

        $io = new SymfonyStyle($input, $output);
        $io->progressStart($total);

        $this->clear($destinationDir);

        /** @var array<int, string> $row */
        foreach ($kenAllCsv as $row) {
            $parsedRow = $this->kenAllCsvParser->parse($row);
            $this->buildOne($parsedRow, $destinationDir);
            $io->progressAdvance();
        }

        /** @var array<int, string> $row */
        foreach ($jigyosyoCsv as $row) {
            $parsedRow = $this->jigyosyoCsvParser->parse($row);
            $this->buildOne($parsedRow, $destinationDir);
            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success('Finished!');

        return Command::SUCCESS;
    }

    private function clear(string $destinationDir): void
    {
        if (is_file($destinationDir)) {
            unlink($destinationDir);
        }

        if (is_dir($destinationDir)) {
            $iterator = new \RecursiveDirectoryIterator($destinationDir, \FilesystemIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                assert($file instanceof \SplFileInfo);
                $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
            }

            rmdir($destinationDir);
        }

        mkdir($destinationDir, recursive: true);
    }

    private function buildOne(ParsedCsvRow $row, string $destinationDir): void
    {
        $jsonFilePath = sprintf('%s/%s.json', $destinationDir, $row->postalCode);

        $existentJson = is_file($jsonFilePath) ? file_get_contents($jsonFilePath) : false;

        $apiResource = false !== $existentJson
            ? ApiResource::fromJson($existentJson)
            : new ApiResource($row->postalCode)
        ;

        $apiResource->addresses[] = $row->address;

        $json = json_encode($apiResource, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        file_put_contents($jsonFilePath, $json);
    }
}
