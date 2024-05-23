<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Model;

final readonly class ApiResource implements \JsonSerializable
{
    public function __construct(
        public string $postalCode,
        public string $prefCode,
        public Address $ja,
        public Address $en,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'postalCode' => $this->postalCode,
            'prefCode' => $this->prefCode,
            'ja' => $this->ja,
            'en' => $this->en,
        ];
    }
}
