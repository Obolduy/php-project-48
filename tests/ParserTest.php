<?php

namespace Hexlet\Code\Tests;

use Hexlet\Code\Parsers\Common\ParserFactory;
use Hexlet\Code\Parsers\JsonParser;
use Hexlet\Code\Parsers\YamlParser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testJsonParserParsesValidJson(): void
    {
        $parser = new JsonParser();
        $json = '{"host": "hexlet.io", "timeout": 50}';

        $result = $parser->parse($json);

        $this->assertEquals([
            'host' => 'hexlet.io',
            'timeout' => 50
        ], $result);
    }

    public function testJsonParserThrowsExceptionOnInvalidJson(): void
    {
        $parser = new JsonParser();
        $invalidJson = '{invalid json}';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid JSON');

        $parser->parse($invalidJson);
    }

    public function testYamlParserParsesValidYaml(): void
    {
        $parser = new YamlParser();
        $yaml = "host: hexlet.io\ntimeout: 50";

        $result = $parser->parse($yaml);

        $this->assertEquals([
            'host' => 'hexlet.io',
            'timeout' => 50
        ], $result);
    }

    public function testYamlParserThrowsExceptionOnInvalidYaml(): void
    {
        $parser = new YamlParser();
        $invalidYaml = "invalid:\n  yaml: [\n  unclosed";

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid YAML');

        $parser->parse($invalidYaml);
    }

    public function testParserFactoryReturnsJsonParser(): void
    {
        $factory = new ParserFactory();

        $parser = $factory->getParser('json');

        $this->assertInstanceOf(JsonParser::class, $parser);
    }

    public function testParserFactoryReturnsYamlParser(): void
    {
        $factory = new ParserFactory();

        $parser1 = $factory->getParser('yml');
        $parser2 = $factory->getParser('yaml');

        $this->assertInstanceOf(YamlParser::class, $parser1);
        $this->assertInstanceOf(YamlParser::class, $parser2);
    }

    public function testParserFactoryThrowsExceptionOnUnsupportedFormat(): void
    {
        $factory = new ParserFactory();

        $this->expectException(\Exception::class);
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

    public function testParserFactoryCaseInsensitive(): void
    {
        $factory = new ParserFactory();

        $parser1 = $factory->getParser('JSON');
        $parser2 = $factory->getParser('json');
        $parser3 = $factory->getParser('YML');

        $this->assertInstanceOf(JsonParser::class, $parser1);
        $this->assertInstanceOf(JsonParser::class, $parser2);
        $this->assertInstanceOf(YamlParser::class, $parser3);
    }
}
