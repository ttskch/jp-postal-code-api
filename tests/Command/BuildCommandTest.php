<?php

declare(strict_types=1);

namespace Ttskch\JpPostalCodeApi\Command;

use League\Csv\Reader;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Tester\CommandTester;
use Ttskch\JpPostalCodeApi\Csv\CsvParserInterface;
use Ttskch\JpPostalCodeApi\DataSource\CsvProviderInterface;
use Ttskch\JpPostalCodeApi\Model\Address;
use Ttskch\JpPostalCodeApi\Model\ApiResource;

class BuildCommandTest extends TestCase
{
    use ProphecyTrait;

    public function testExecute(): void
    {
        $csvProvider = $this->prophesize(CsvProviderInterface::class);
        $csvProvider->fromZipUrl('https://www.post.japanpost.jp/zipcode/dl/roman/KEN_ALL_ROME.zip')->willReturn(Reader::createFromString('ken,all,csv'));
        $csvProvider->fromZipUrl('https://www.post.japanpost.jp/zipcode/dl/jigyosyo/zip/jigyosyo.zip')->willReturn(Reader::createFromString('jigyo,syo,csv'));

        $kenAllCsvParser = $this->prophesize(CsvParserInterface::class);
        $kenAllCsvParser->parse(['ken', 'all', 'csv'])->willReturn(new ApiResource(
            '0600000',
            '01',
            ja: new Address('北海道', '札幌市中央区', '旭ケ丘', '', ''),
            en: new Address('Hokkaido', 'Chuo-ku, Sapporo-shi', 'Asahigaoka', '', ''),
        ));

        $jigyosyoCsvParser = $this->prophesize(CsvParserInterface::class);
        $jigyosyoCsvParser->parse(['jigyo', 'syo', 'csv'])->willReturn(new ApiResource(
            '1008111',
            '13',
            ja: new Address('東京都', '千代田区', '千代田', '1-1', '宮内庁'),
            en: new Address(),
        ));

        $command = new BuildCommand($csvProvider->reveal(), $kenAllCsvParser->reveal(), $jigyosyoCsvParser->reveal());

        $commandTester = new CommandTester($command);

        $destinationDir = sys_get_temp_dir().'/'.md5(uniqid());

        ob_start();
        $commandTester->execute([
            '--destination_dir' => $destinationDir,
        ]);
        ob_end_clean();

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Finished!', $output);

        $content = strval(file_get_contents($destinationDir.'/0600000.json'));
        $expected = <<<JSON
{"postalCode":"0600000","prefCode":"01","ja":{"prefecture":"北海道","address1":"札幌市中央区","address2":"旭ケ丘","address3":"","address4":""},"en":{"prefecture":"Hokkaido","address1":"Chuo-ku, Sapporo-shi","address2":"Asahigaoka","address3":"","address4":""}}
JSON;
        self::assertSame($expected, $content);

        $content = strval(file_get_contents($destinationDir.'/1008111.json'));
        $expected = <<<JSON
{"postalCode":"1008111","prefCode":"13","ja":{"prefecture":"東京都","address1":"千代田区","address2":"千代田","address3":"1-1","address4":"宮内庁"},"en":{"prefecture":"","address1":"","address2":"","address3":"","address4":""}}
JSON;
        self::assertSame($expected, $content);
    }
}
