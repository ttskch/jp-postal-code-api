<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Command;

use League\Csv\Reader;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Tester\CommandTester;
use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\DataSource\CsvProviderInterface;
use Ttskch\JpPostalCodeApi\FileSystem\BaseDirectoryInterface;
use Ttskch\JpPostalCodeApi\Model\Address;
use Ttskch\JpPostalCodeApi\Model\AddressUnit;
use Ttskch\JpPostalCodeApi\Model\ParsedCsvRow;

class BuildCommandTest extends TestCase
{
    use ProphecyTrait;

    public function testExecute(): void
    {
        $csvProvider = $this->prophesize(CsvProviderInterface::class);
        $csvProvider->fromZipUrl('https://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip')->willReturn(Reader::createFromString('ken,all,csv'));
        $csvProvider->fromZipUrl('https://www.post.japanpost.jp/zipcode/dl/roman/KEN_ALL_ROME.zip')->willReturn(Reader::createFromString('kenall,rome,csv'));
        $csvProvider->fromZipUrl('https://www.post.japanpost.jp/zipcode/dl/jigyosyo/zip/jigyosyo.zip')->willReturn(Reader::createFromString('jigyo,syo,csv'));

        $kenAllCsvParser = $this->prophesize(CsvParserInterface::class);
        $kenAllCsvParser->parse(['ken', 'all', 'csv'])->willReturn(new ParsedCsvRow(
            '0600000',
            new Address(
                '01',
                ja: new AddressUnit('北海道', '札幌市中央区', '旭ケ丘', '', ''),
                kana: new AddressUnit('ホッカイドウ', 'サッポロシチュウオウク', 'アサヒガオカ', '', ''),
            ),
        ));

        $kenAllRomeCsvParser = $this->prophesize(CsvParserInterface::class);
        $kenAllRomeCsvParser->parse(['kenall', 'rome', 'csv'])->willReturn(new ParsedCsvRow(
            '0600000',
            new Address(
                '01',
                ja: new AddressUnit('北海道', '札幌市中央区', '旭ケ丘', '', ''),
                en: new AddressUnit('Hokkaido', 'Chuo-ku, Sapporo-shi', 'Asahigaoka', '', ''),
            ),
        ));

        $jigyosyoCsvParser = $this->prophesize(CsvParserInterface::class);
        $jigyosyoCsvParser->parse(['jigyo', 'syo', 'csv'])->willReturn(new ParsedCsvRow(
            '1008111',
            new Address(
                '13',
                ja: new AddressUnit('東京都', '千代田区', '千代田', '1-1', '宮内庁'),
                en: new AddressUnit(),
            ),
        ));

        $baseDirectory = $this->prophesize(BaseDirectoryInterface::class);
        $baseDirectory->clear()->shouldBeCalled();
        $baseDirectory->putJsonFile(new ParsedCsvRow(
            '0600000',
            new Address(
                '01',
                ja: new AddressUnit('北海道', '札幌市中央区', '旭ケ丘', '', ''),
                kana: new AddressUnit('ホッカイドウ', 'サッポロシチュウオウク', 'アサヒガオカ', '', ''),
            ),
        ))->shouldBeCalled();
        $baseDirectory->putJsonFile(new ParsedCsvRow(
            '0600000',
            new Address(
                '01',
                ja: new AddressUnit('北海道', '札幌市中央区', '旭ケ丘', '', ''),
                en: new AddressUnit('Hokkaido', 'Chuo-ku, Sapporo-shi', 'Asahigaoka', '', ''),
            ),
        ), true)->shouldBeCalled();
        $baseDirectory->putJsonFile(new ParsedCsvRow(
            '1008111',
            new Address(
                '13',
                ja: new AddressUnit('東京都', '千代田区', '千代田', '1-1', '宮内庁'),
                en: new AddressUnit(),
            ),
        ))->shouldBeCalled();
        $baseDirectory->countJsonFiles()->willReturn(1);

        $command = new BuildCommand(
            $csvProvider->reveal(),
            $kenAllCsvParser->reveal(),
            $kenAllRomeCsvParser->reveal(),
            $jigyosyoCsvParser->reveal(),
            $baseDirectory->reveal(),
        );

        $commandTester = new CommandTester($command);

        ob_start();
        $commandTester->execute([]);
        ob_end_clean();

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Finished! 1 files are created from 3 CSV records.', $output);
    }
}
