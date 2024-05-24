<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Model;

final readonly class Address implements \JsonSerializable
{
    public function __construct(
        public string $prefectureCode,
        public AddressUnit $ja,
        public AddressUnit $en,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'prefectureCode' => $this->prefectureCode,
            'ja' => $this->ja,
            'en' => $this->en,
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
            is_string($array['prefectureCode'] ?? null)
            && is_array($array['ja'] ?? null)
            && is_array($array['en'] ?? null)
        );

        return new self(
            $array['prefectureCode'],
            AddressUnit::fromArray($array['ja']),
            AddressUnit::fromArray($array['en']),
        );
    }
}
