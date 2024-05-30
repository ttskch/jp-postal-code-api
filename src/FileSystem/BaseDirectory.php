<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\FileSystem;

use Ttskch\JpPostalCodeApi\Model\AddressUnit;
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

    public function putJsonFile(ParsedCsvRow $row, bool $en = false): void
    {
        $jsonFilePath = sprintf('%s/%s.json', $this->path, $row->postalCode);

        $existentJson = is_file($jsonFilePath) ? file_get_contents($jsonFilePath) : false;

        $apiResource = false !== $existentJson
            ? ApiResource::fromJson($existentJson)
            : new ApiResource($row->postalCode)
        ;

        // Add English address unit only if Japanese address unit is the same
        if ($en) {
            foreach ($apiResource->addresses as $address) {
                if ($row->address->ja == $address->ja) { // not `===`
                    $address->en = $row->address->en;
                    break;
                }
            }
        }

        // Push or overwrite Japanese address
        if (!$en) {
            // If the CSV row and existent JSON refer to the same address, overwrite it with the one that contains more information
            $overwritten = false;
            foreach ($apiResource->addresses as $address) {
                if (null !== ($ja = $this->max($address->ja, $row->address->ja))) {
                    $address->ja = $ja;
                    $overwritten = true;
                    break;
                }
            }
            // If not, just push it
            if (!$overwritten) {
                $apiResource->addresses[] = $row->address;
            }
        }

        // For postal codes where only English addresses exist in the CSV
        if ([] === $apiResource->addresses) {
            return;
        }

        $json = json_encode($apiResource, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        file_put_contents($jsonFilePath, $json);
    }

    public function countJsonFiles(): int
    {
        $glob = glob(sprintf('%s/*.json', $this->path), GLOB_NOSORT | GLOB_BRACE);
        $glob = false === $glob ? [] : $glob;

        return count($glob);
    }

    /**
     * If $a and $b refer to the same address, return the one that contains more information. If not, return null.
     */
    private function max(AddressUnit $a, AddressUnit $b): ?AddressUnit
    {
        if ($a->prefecture !== $b->prefecture || $a->address1 !== $b->address1) {
            return null;
        }

        if ('' === $a->address2 && '' !== $b->address2) {
            return $b;
        }

        if ('' !== $a->address2 && '' === $b->address2) {
            return $a;
        }

        return null;
    }
}
