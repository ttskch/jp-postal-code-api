<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Model;

final class ApiResource implements \JsonSerializable
{
    public function __construct(
        readonly public string $postalCode,
        /** @var array<Address> */
        public array $addresses = [],
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'postalCode' => $this->postalCode,
            'addresses' => $this->addresses,
        ];
    }

    public static function fromJson(string $json): self
    {
        $array = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        assert(is_array($array));

        return self::fromArray($array);
    }

    /**
     * @param array<mixed> $array
     */
    public static function fromArray(array $array): self
    {
        assert(
            is_string($array['postalCode'] ?? null)
            && is_array($array['addresses'] ?? null)
        );

        return new self(
            $array['postalCode'],
            array_map(fn (array $address) => Address::fromArray($address), $array['addresses']),
        );
    }
}
