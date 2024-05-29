<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Model;

final class Address implements \JsonSerializable
{
    public AddressUnit $ja;
    public readonly AddressUnit $kana;
    public AddressUnit $en;

    public function __construct(
        public string $prefectureCode,
        ?AddressUnit $ja = null,
        ?AddressUnit $kana = null,
        ?AddressUnit $en = null,
    ) {
        $this->ja = $ja ?? new AddressUnit();
        $this->kana = $kana ?? new AddressUnit();
        $this->en = $en ?? new AddressUnit();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'prefectureCode' => $this->prefectureCode,
            'ja' => $this->ja,
            'kana' => $this->kana,
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
            && is_array($array['kana'] ?? null)
            && is_array($array['en'] ?? null)
        );

        return new self(
            $array['prefectureCode'],
            AddressUnit::fromArray($array['ja']),
            AddressUnit::fromArray($array['kana']),
            AddressUnit::fromArray($array['en']),
        );
    }
}
