<?php

namespace Hexlet\Code\Tests;

use Exception;
use Hexlet\Code\Formatters\Enums\OutputFormatEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $fixturesPath;

    protected function setUp(): void
    {
        $this->fixturesPath = __DIR__ . '/fixtures';
    }

    #[DataProvider('flatFilesProvider')]
    public function testGenDiffWithFlatFiles(string $file1, string $file2): void
    {
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_diff.txt'));
        $actual = genDiff($file1, $file2);

        $this->assertEquals($expected, trim($actual));
    }

    public static function flatFilesProvider(): array
    {
        $fixturesPath = __DIR__ . '/fixtures';

        return [
            'json files' => [
                $fixturesPath . '/file1.json',
                $fixturesPath . '/file2.json'
            ],
            'yaml files' => [
                $fixturesPath . '/file1.yml',
                $fixturesPath . '/file2.yml'
            ],
            'mixed formats' => [
                $fixturesPath . '/file1.json',
                $fixturesPath . '/file2.yml'
            ]
        ];
    }

    #[DataProvider('outputFormatsProvider')]
    public function testGenDiffWithDifferentOutputFormats(
        string $file1,
        string $file2,
        OutputFormatEnum $format,
        string $expectedFile
    ): void {
        $expected = trim(file_get_contents($this->fixturesPath . '/' . $expectedFile));
        $actual = genDiff($file1, $file2, $format);

        $this->assertEquals($expected, trim($actual));
    }

    public static function outputFormatsProvider(): array
    {
        $fixturesPath = __DIR__ . '/fixtures';

        return [
            'stylish format with json' => [
                $fixturesPath . '/file1_nested.json',
                $fixturesPath . '/file2_nested.json',
                OutputFormatEnum::STYLISH,
                'expected_nested_stylish.txt'
            ],
            'stylish format with yaml' => [
                $fixturesPath . '/file1_nested.yml',
                $fixturesPath . '/file2_nested.yml',
                OutputFormatEnum::STYLISH,
                'expected_nested_stylish.txt'
            ],
            'stylish format with mixed' => [
                $fixturesPath . '/file1_nested.json',
                $fixturesPath . '/file2_nested.yml',
                OutputFormatEnum::STYLISH,
                'expected_nested_stylish.txt'
            ],
            'plain format with json' => [
                $fixturesPath . '/file1_nested.json',
                $fixturesPath . '/file2_nested.json',
                OutputFormatEnum::PLAIN,
                'expected_nested_plain.txt'
            ],
            'plain format with yaml' => [
                $fixturesPath . '/file1_nested.yml',
                $fixturesPath . '/file2_nested.yml',
                OutputFormatEnum::PLAIN,
                'expected_nested_plain.txt'
            ],
            'plain format with mixed' => [
                $fixturesPath . '/file1_nested.json',
                $fixturesPath . '/file2_nested.yml',
                OutputFormatEnum::PLAIN,
                'expected_nested_plain.txt'
            ],
            'json format with json' => [
                $fixturesPath . '/file1_nested.json',
                $fixturesPath . '/file2_nested.json',
                OutputFormatEnum::JSON,
                'expected_nested_json.txt'
            ],
            'json format with yaml' => [
                $fixturesPath . '/file1_nested.yml',
                $fixturesPath . '/file2_nested.yml',
                OutputFormatEnum::JSON,
                'expected_nested_json.txt'
            ],
            'json format with mixed' => [
                $fixturesPath . '/file1_nested.json',
                $fixturesPath . '/file2_nested.yml',
                OutputFormatEnum::JSON,
                'expected_nested_json.txt'
            ]
        ];
    }

    public function testGenDiffWithDefaultFormat(): void
    {
        $file1 = $this->fixturesPath . '/file1_nested.json';
        $file2 = $this->fixturesPath . '/file2_nested.json';
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_nested_stylish.txt'));

        $actual = genDiff($file1, $file2);

        $this->assertEquals($expected, trim($actual));
    }

    #[DataProvider('identicalFilesProvider')]
    public function testGenDiffWithIdenticalFiles(string $file, string $expectedFile): void
    {
        $expected = trim(file_get_contents($this->fixturesPath . '/' . $expectedFile));
        $actual = genDiff($file, $file);

        $this->assertEquals($expected, trim($actual));
    }

    public static function identicalFilesProvider(): array
    {
        $fixturesPath = __DIR__ . '/fixtures';

        return [
            'flat json files' => [
                $fixturesPath . '/file1.json',
                'expected_identical.txt'
            ],
            'flat yaml files' => [
                $fixturesPath . '/file1.yml',
                'expected_identical.txt'
            ]
        ];
    }

    public function testGenDiffWithNonExistentFile(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('File not found');

        genDiff('non_existent_file.json', $this->fixturesPath . '/file1.json');
    }

    #[DataProvider('invalidFilesProvider')]
    public function testGenDiffWithInvalidFiles(string $content, string $extension, string $expectedMessage): void
    {
        $invalidFile = $this->fixturesPath . '/temp_invalid' . $extension;
        file_put_contents($invalidFile, $content);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedMessage);

        try {
            genDiff($invalidFile, $invalidFile);
        } finally {
            unlink($invalidFile);
        }
    }

    public static function invalidFilesProvider(): array
    {
        return [
            'invalid json' => [
                '{invalid json}',
                '.json',
                'Invalid JSON'
            ],
            'invalid yaml' => [
                "invalid:\n  yaml: [\n  unclosed",
                '.yml',
                'Invalid YAML'
            ],
            'unsupported format' => [
                'some text',
                '.txt',
                'Unsupported file format'
            ]
        ];
    }
}
