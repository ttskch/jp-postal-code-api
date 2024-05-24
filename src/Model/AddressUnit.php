<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Model;

final readonly class AddressUnit implements \JsonSerializable
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

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
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
            is_string($array['prefecture'] ?? null)
            && is_string($array['address1'] ?? null)
            && is_string($array['address2'] ?? null)
            && is_string($array['address3'] ?? null)
            && is_string($array['address4'] ?? null)
        );

        return new self(
            $array['prefecture'],
            $array['address1'],
            $array['address2'],
            $array['address3'],
            $array['address4'],
        );
    }
}
