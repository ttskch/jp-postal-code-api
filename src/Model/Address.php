<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Model;

final readonly class Address implements \JsonSerializable
{
    public function __construct(
        public string $prefecture = '',
        public string $address1 = '',
        public string $address2 = '',
        public string $address3 = '',
        public string $address4 = '',
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'prefecture' => $this->prefecture,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'address3' => $this->address3,
            'address4' => $this->address4,
        ];
    }
}
