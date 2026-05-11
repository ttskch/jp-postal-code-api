<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\DataSource;

final readonly class ZipUrls
{
    public const string KEN_ALL = 'https://www.post.japanpost.jp/service/search/zipcode/download/kogaki/zip/ken_all.zip';
    public const string KEN_ALL_ROME = 'https://www.post.japanpost.jp/service/search/zipcode/download/roman/KEN_ALL_ROME.zip';
    public const string JIGYOSYO = 'https://www.post.japanpost.jp/service/search/zipcode/download/office/zip/jigyosyo.zip';
}
