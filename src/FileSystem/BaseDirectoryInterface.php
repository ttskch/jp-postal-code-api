<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\FileSystem;

use Ttskch\JpPostalCodeApi\Model\ParsedCsvRow;

interface BaseDirectoryInterface
{
    public function clear(): void;

    public function putJsonFile(ParsedCsvRow $row): void;
}
