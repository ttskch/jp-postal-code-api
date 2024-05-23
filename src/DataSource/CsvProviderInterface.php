<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\DataSource;

use League\Csv\Reader;

interface CsvProviderInterface
{
    public function fromZipUrl(string $zipUrl, string $csvEncoding = 'Shift_JIS'): Reader;
}
