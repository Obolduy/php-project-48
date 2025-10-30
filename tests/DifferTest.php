<?php

namespace Hexlet\Code\Tests;

use Hexlet\Code\DiffBuilder;
use Hexlet\Code\Differ;
use Hexlet\Code\Formatters\Enums\OutputFormatEnum;
use Hexlet\Code\Nodes\DTOs\DiffNode;
use Hexlet\Code\Nodes\Enums\DiffNodeTypeEnum;
use Hexlet\Code\Parser;
use PHPUnit\Framework\TestCase;

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
        $tree = $diffBuilder->build($data1, $data2);

        $this->assertIsArray($tree);
        $this->assertCount(2, $tree);
        $this->assertContainsOnlyInstancesOf(DiffNode::class, $tree);
    }

    public function testDiffBuilderWithNullValues(): void
    {
        $data1 = ['value' => null];
        $data2 = ['value' => 'something'];

        $diffBuilder = new DiffBuilder();
        $tree = $diffBuilder->build($data1, $data2);

        $this->assertIsArray($tree);
        $this->assertCount(1, $tree);
        $this->assertInstanceOf(DiffNode::class, $tree[0]);
        $this->assertEquals(DiffNodeTypeEnum::CHANGED, $tree[0]->type);
    }

    public function testDiffBuilderWithNumericValues(): void
    {
        $data1 = ['timeout' => 50, 'port' => 8080];
        $data2 = ['timeout' => 20, 'port' => 8080];

        $diffBuilder = new DiffBuilder();
        $tree = $diffBuilder->build($data1, $data2);

        $this->assertIsArray($tree);
        $this->assertCount(2, $tree);
        $this->assertContainsOnlyInstancesOf(DiffNode::class, $tree);
    }

    public function testDiffBuilderWithAddedKeys(): void
    {
        $data1 = ['host' => 'hexlet.io'];
        $data2 = ['host' => 'hexlet.io', 'verbose' => true, 'timeout' => 50];

        $diffBuilder = new DiffBuilder();
        $tree = $diffBuilder->build($data1, $data2);

        $this->assertIsArray($tree);
        $this->assertCount(3, $tree);
        $this->assertContainsOnlyInstancesOf(DiffNode::class, $tree);
    }

    public function testDiffBuilderWithRemovedKeys(): void
    {
        $data1 = ['host' => 'hexlet.io', 'verbose' => true, 'timeout' => 50];
        $data2 = ['host' => 'hexlet.io'];

        $diffBuilder = new DiffBuilder();
        $tree = $diffBuilder->build($data1, $data2);

        $this->assertIsArray($tree);
        $this->assertCount(3, $tree);
        $this->assertContainsOnlyInstancesOf(DiffNode::class, $tree);
    }

    public function testGenDiffWithFlatYamlFiles(): void
    {
        $file1 = $this->fixturesPath . '/file1.yml';
        $file2 = $this->fixturesPath . '/file2.yml';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_diff.txt'));

        $actual = genDiff($file1, $file2);

        $this->assertEquals($expected, trim($actual));
    }

    public function testDifferWithYamlFiles(): void
    {
        $file1 = $this->fixturesPath . '/file1.yml';
        $file2 = $this->fixturesPath . '/file2.yml';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_diff.txt'));

        $differ = new Differ(new Parser(), new DiffBuilder());
        $actual = $differ->generate($file1, $file2);

        $this->assertEquals($expected, trim($actual));
    }

    public function testDifferWithIdenticalYamlFiles(): void
    {
        $file1 = $this->fixturesPath . '/file1.yml';

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

    public function testParserWithInvalidYaml(): void
    {
        $invalidYamlFile = $this->fixturesPath . '/invalid.yml';
        file_put_contents($invalidYamlFile, "invalid:\n  yaml: [\n  unclosed");

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid YAML');

        $parser = new Parser();
        $parser->parseFile($invalidYamlFile);

        unlink($invalidYamlFile);
    }

    public function testParserWithMixedFormats(): void
    {
        $jsonFile = $this->fixturesPath . '/file1.json';
        $yamlFile = $this->fixturesPath . '/file1.yml';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_diff.txt'));

        $differ = new Differ(new Parser(), new DiffBuilder());
        $actual = $differ->generate($jsonFile, $this->fixturesPath . '/file2.yml');

        $this->assertEquals($expected, trim($actual));
    }

    public function testParserWithUnsupportedFormat(): void
    {
        $unsupportedFile = $this->fixturesPath . '/file.txt';
        file_put_contents($unsupportedFile, "some text");

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unsupported file format');

        $parser = new Parser();
        $parser->parseFile($unsupportedFile);

        unlink($unsupportedFile);
    }

    public function testGenDiffWithNestedJsonFiles(): void
    {
        $file1 = $this->fixturesPath . '/file1_nested.json';
        $file2 = $this->fixturesPath . '/file2_nested.json';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_nested_stylish.txt'));

        $actual = genDiff($file1, $file2);

        $this->assertEquals($expected, trim($actual));
    }

    public function testGenDiffWithNestedYamlFiles(): void
    {
        $file1 = $this->fixturesPath . '/file1_nested.yml';
        $file2 = $this->fixturesPath . '/file2_nested.yml';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_nested_stylish.txt'));

        $actual = genDiff($file1, $file2);

        $this->assertEquals($expected, trim($actual));
    }

    public function testGenDiffWithMixedNestedFormats(): void
    {
        $file1 = $this->fixturesPath . '/file1_nested.json';
        $file2 = $this->fixturesPath . '/file2_nested.yml';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_nested_stylish.txt'));

        $actual = genDiff($file1, $file2);

        $this->assertEquals($expected, trim($actual));
    }

    public function testDifferWithIdenticalNestedFiles(): void
    {
        $file1 = $this->fixturesPath . '/file1_nested.json';

        $differ = new Differ(new Parser(), new DiffBuilder());
        $actual = $differ->generate($file1, $file1);

        $this->assertStringContainsString('common:', $actual);
        $this->assertStringContainsString('group1:', $actual);
        $this->assertStringContainsString('group2:', $actual);
    }

    public function testDiffBuilderWithNestedStructures(): void
    {
        $data1 = [
            'common' => [
                'setting1' => 'Value 1',
                'setting2' => 200
            ],
            'group1' => [
                'baz' => 'bas'
            ]
        ];

        $data2 = [
            'common' => [
                'setting1' => 'Value 1',
                'setting3' => null
            ],
            'group1' => [
                'baz' => 'bars'
            ]
        ];

        $diffBuilder = new DiffBuilder();
        $tree = $diffBuilder->build($data1, $data2);

        $this->assertIsArray($tree);
        $this->assertCount(2, $tree);
        $this->assertContainsOnlyInstancesOf(DiffNode::class, $tree);

        $commonNode = array_filter($tree, fn(DiffNode $node) => $node->key === 'common');
        $this->assertNotEmpty($commonNode);
        $commonNode = array_values($commonNode)[0];
        $this->assertInstanceOf(DiffNode::class, $commonNode);
        $this->assertEquals(DiffNodeTypeEnum::NESTED, $commonNode->type);
        $this->assertIsArray($commonNode->children);
        $this->assertNotEmpty($commonNode->children);
    }

    public function testGenDiffWithStylishFormat(): void
    {
        $file1 = $this->fixturesPath . '/file1_nested.json';
        $file2 = $this->fixturesPath . '/file2_nested.json';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_nested_stylish.txt'));

        $actual = genDiff($file1, $file2, OutputFormatEnum::STYLISH);

        $this->assertEquals($expected, trim($actual));
    }

    public function testGenDiffWithPlainFormat(): void
    {
        $file1 = $this->fixturesPath . '/file1_nested.json';
        $file2 = $this->fixturesPath . '/file2_nested.json';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_nested_plain.txt'));

        $actual = genDiff($file1, $file2, OutputFormatEnum::PLAIN);

        $this->assertEquals($expected, trim($actual));
    }

    public function testGenDiffWithPlainFormatYaml(): void
    {
        $file1 = $this->fixturesPath . '/file1_nested.yml';
        $file2 = $this->fixturesPath . '/file2_nested.yml';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_nested_plain.txt'));

        $actual = genDiff($file1, $file2, OutputFormatEnum::PLAIN);

        $this->assertEquals($expected, trim($actual));
    }

    public function testGenDiffWithPlainFormatEnum(): void
    {
        $file1 = $this->fixturesPath . '/file1_nested.json';
        $file2 = $this->fixturesPath . '/file2_nested.json';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_nested_plain.txt'));

        $actual = genDiff($file1, $file2, OutputFormatEnum::PLAIN);

        $this->assertEquals($expected, trim($actual));
    }

    public function testGenDiffWithPlainFormatMixedFiles(): void
    {
        $file1 = $this->fixturesPath . '/file1_nested.json';
        $file2 = $this->fixturesPath . '/file2_nested.yml';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_nested_plain.txt'));

        $actual = genDiff($file1, $file2, OutputFormatEnum::PLAIN);

        $this->assertEquals($expected, trim($actual));
    }
}
