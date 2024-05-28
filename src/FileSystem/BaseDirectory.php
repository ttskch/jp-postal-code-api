<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\FileSystem;

use Ttskch\JpPostalCodeApi\Model\ApiResource;
use Ttskch\JpPostalCodeApi\Model\ParsedCsvRow;

final readonly class BaseDirectory implements BaseDirectoryInterface
{
    public function __construct(private string $path = __DIR__.'/../../docs/api/v1')
    {
    }

    public function clear(): void
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        if (is_dir($this->path)) {
            $iterator = new \RecursiveDirectoryIterator($this->path, \FilesystemIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                assert($file instanceof \SplFileInfo);
                $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
            }

            rmdir($this->path);
        }

        mkdir($this->path, recursive: true);
    }

    public function putJsonFile(ParsedCsvRow $row): void
    {
        $jsonFilePath = sprintf('%s/%s.json', $this->path, $row->postalCode);

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
