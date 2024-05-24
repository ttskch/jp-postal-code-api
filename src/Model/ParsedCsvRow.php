<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Model;

final readonly class ParsedCsvRow
{
    public function __construct(
        public string $postalCode,
        public Address $address,
    ) {
    }
}
