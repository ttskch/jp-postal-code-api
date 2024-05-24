<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Csv;

use Ttskch\JpPostalCodeApi\Model\ParsedCsvRow;

interface CsvParserInterface
{
    /**
     * @param array<int, string> $csvRow
     */
    public function parse(array $csvRow): ParsedCsvRow;
}
