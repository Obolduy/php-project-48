<?php

namespace Hexlet\Code\Tests;

use Exception;
use Hexlet\Code\Parsers\Common\ParserFactory;
use Hexlet\Code\Parsers\JsonParser;
use Hexlet\Code\Parsers\ParserInterface;
use Hexlet\Code\Parsers\YamlParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    #[DataProvider('validParserDataProvider')]
    public function testParserParsesValidInput(ParserInterface $parser, string $content, array $expected): void
    {
        $result = $parser->parse($content);

        $this->assertEquals($expected, $result);
    }

    public static function validParserDataProvider(): array
    {
        return [
            'json parser' => [
                new JsonParser(),
                '{"host": "hexlet.io", "timeout": 50}',
                ['host' => 'hexlet.io', 'timeout' => 50]
            ],
            'yaml parser' => [
                new YamlParser(),
                "host: hexlet.io\ntimeout: 50",
                ['host' => 'hexlet.io', 'timeout' => 50]
            ]
        ];
    }

    #[DataProvider('invalidParserDataProvider')]
    public function testParserThrowsExceptionOnInvalidInput(
        ParserInterface $parser,
        string $content,
        string $expectedMessage
    ): void {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedMessage);

        $parser->parse($content);
    }

    public static function invalidParserDataProvider(): array
    {
        return [
            'invalid json' => [
                new JsonParser(),
                '{invalid json}',
                'Invalid JSON'
            ],
            'invalid yaml' => [
                new YamlParser(),
                "invalid:\n  yaml: [\n  unclosed",
                'Invalid YAML'
            ]
        ];
    }

    #[DataProvider('parserFactoryDataProvider')]
    public function testParserFactoryReturnsCorrectParser(string $format, string $expectedClass): void
    {
        $factory = new ParserFactory();
        $parser = $factory->getParser($format);

        $this->assertInstanceOf($expectedClass, $parser);
    }

    public static function parserFactoryDataProvider(): array
    {
        return [
            'json format' => ['json', JsonParser::class],
            'yml format' => ['yml', YamlParser::class],
            'yaml format' => ['yaml', YamlParser::class],
            'JSON uppercase' => ['JSON', JsonParser::class],
            'YML uppercase' => ['YML', YamlParser::class]
        ];
    }

    public function testParserFactoryThrowsExceptionOnUnsupportedFormat(): void
    {
        $factory = new ParserFactory();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unsupported file format');

        $factory->getParser('txt');
    }

    public function testParserFactoryCanRegisterCustomParser(): void
    {
        $factory = new ParserFactory();

        $factory->registerParser('txt', JsonParser::class);

        $parser = $factory->getParser('txt');
        $this->assertInstanceOf(JsonParser::class, $parser);
    }

    public function testParserFactoryLazyInitialization(): void
    {
        $factory = new ParserFactory();

        $parser1 = $factory->getParser('json');
        $parser2 = $factory->getParser('json');

        $this->assertSame($parser1, $parser2);
    }
}
