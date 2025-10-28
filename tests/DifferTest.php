<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use Hexlet\Code\Differ;
use Hexlet\Code\Parser;
use Hexlet\Code\DiffBuilder;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $fixturesPath;

    protected function setUp(): void
    {
        $this->fixturesPath = __DIR__ . '/fixtures';
    }

    public function testGenDiffWithFlatJsonFiles(): void
    {
        $file1 = $this->fixturesPath . '/file1.json';
        $file2 = $this->fixturesPath . '/file2.json';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_diff.txt'));

        $actual = genDiff($file1, $file2);

        $this->assertEquals($expected, trim($actual));
    }

    public function testDifferGenerateMethod(): void
    {
        $file1 = $this->fixturesPath . '/file1.json';
        $file2 = $this->fixturesPath . '/file2.json';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_diff.txt'));

        $differ = new Differ(new Parser(), new DiffBuilder());
        $actual = $differ->generate($file1, $file2);

        $this->assertEquals($expected, trim($actual));
    }

    public function testDifferWithIdenticalFiles(): void
    {
        $file1 = $this->fixturesPath . '/file1.json';

        $differ = new Differ(new Parser(), new DiffBuilder());
        $actual = $differ->generate($file1, $file1);

        $expected = <<<DIFF
{
    follow: false
    host: hexlet.io
    proxy: 123.234.53.22
    timeout: 50
}
DIFF;

        $this->assertEquals($expected, trim($actual));
    }

    public function testDifferWithNonExistentFile(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File not found');

        $differ = new Differ(new Parser(), new DiffBuilder());
        $differ->generate('non_existent_file.json', $this->fixturesPath . '/file1.json');
    }

    public function testParserWithInvalidJson(): void
    {
        $invalidJsonFile = $this->fixturesPath . '/invalid.json';
        file_put_contents($invalidJsonFile, '{invalid json}');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid JSON');

        $parser = new Parser();
        $parser->parseFile($invalidJsonFile);

        unlink($invalidJsonFile);
    }

    public function testDiffBuilderWithBooleanValues(): void
    {
        $data1 = ['verbose' => true, 'enabled' => false];
        $data2 = ['verbose' => false, 'enabled' => false];

        $diffBuilder = new DiffBuilder();
        $result = $diffBuilder->build($data1, $data2);

        $expected = <<<DIFF
{
    enabled: false
  - verbose: true
  + verbose: false
}
DIFF;

        $this->assertEquals($expected, trim($result));
    }

    public function testDiffBuilderWithNullValues(): void
    {
        $data1 = ['value' => null];
        $data2 = ['value' => 'something'];

        $diffBuilder = new DiffBuilder();
        $result = $diffBuilder->build($data1, $data2);

        $expected = <<<DIFF
{
  - value: null
  + value: something
}
DIFF;

        $this->assertEquals($expected, trim($result));
    }

    public function testDiffBuilderWithNumericValues(): void
    {
        $data1 = ['timeout' => 50, 'port' => 8080];
        $data2 = ['timeout' => 20, 'port' => 8080];

        $diffBuilder = new DiffBuilder();
        $result = $diffBuilder->build($data1, $data2);

        $expected = <<<DIFF
{
    port: 8080
  - timeout: 50
  + timeout: 20
}
DIFF;

        $this->assertEquals($expected, trim($result));
    }

    public function testDiffBuilderWithAddedKeys(): void
    {
        $data1 = ['host' => 'hexlet.io'];
        $data2 = ['host' => 'hexlet.io', 'verbose' => true, 'timeout' => 50];

        $diffBuilder = new DiffBuilder();
        $result = $diffBuilder->build($data1, $data2);

        $expected = <<<DIFF
{
    host: hexlet.io
  + timeout: 50
  + verbose: true
}
DIFF;

        $this->assertEquals($expected, trim($result));
    }

    public function testDiffBuilderWithRemovedKeys(): void
    {
        $data1 = ['host' => 'hexlet.io', 'verbose' => true, 'timeout' => 50];
        $data2 = ['host' => 'hexlet.io'];

        $diffBuilder = new DiffBuilder();
        $result = $diffBuilder->build($data1, $data2);

        $expected = <<<DIFF
{
    host: hexlet.io
  - timeout: 50
  - verbose: true
}
DIFF;

        $this->assertEquals($expected, trim($result));
    }
}

