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
use Ttskch\JpPostalCodeApi\Model\ApiResource;

#[AsCommand(
    name: 'build',
    description: 'Build JSON files from JAPAN POST\'S official CSV data'
)]
final class BuildCommand extends Command
{
    private const string DESTINATION_BASE_PATH = __DIR__.'/../../docs/api';

    public function __construct(
        readonly private CsvProviderInterface $csvProvider,
        readonly private CsvParserInterface $kenAllCsvParser,
        readonly private CsvParserInterface $jigyosyoCsvParser,
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

        $this->clear();

        /** @var array<int, string> $row */
        foreach ($kenAllCsv as $row) {
            $apiResource = $this->kenAllCsvParser->parse($row);
            $this->buildOne($apiResource);
            $io->progressAdvance();
        }

        /** @var array<int, string> $row */
        foreach ($jigyosyoCsv as $row) {
            $apiResource = $this->jigyosyoCsvParser->parse($row);
            $this->buildOne($apiResource);
            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success('Finished!');

        return Command::SUCCESS;
    }

    private function clear(): void
    {
        if (is_file(self::DESTINATION_BASE_PATH)) {
            unlink(self::DESTINATION_BASE_PATH);
        }

        if (is_dir(self::DESTINATION_BASE_PATH)) {
            $iterator = new \RecursiveDirectoryIterator(self::DESTINATION_BASE_PATH, \FilesystemIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                assert($file instanceof \SplFileInfo);
                $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
            }

            rmdir(self::DESTINATION_BASE_PATH);
        }

        mkdir(self::DESTINATION_BASE_PATH, recursive: true);
    }

    private function buildOne(ApiResource $resource): void
    {
        $json = json_encode($resource, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        $jsonFilePath = sprintf('%s/%s.json', self::DESTINATION_BASE_PATH, $resource->postalCode);

        file_put_contents($jsonFilePath, $json);
    }
}
