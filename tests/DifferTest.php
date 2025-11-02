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
    public function testGenDiffWithFlatFiles(string $extension1, string $extension2): void
    {
        $file1 = $this->fixturesPath . '/file1.' . $extension1;
        $file2 = $this->fixturesPath . '/file2.' . $extension2;
        
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_diff.txt'));
        $actual = genDiff($file1, $file2);

        $this->assertEquals($expected, trim($actual));
    }

    public static function flatFilesProvider(): array
    {
        return [
            'json files' => ['json', 'json'],
            'yaml files' => ['yml', 'yml'],
            'mixed formats' => ['json', 'yml']
        ];
    }

    #[DataProvider('outputFormatsProvider')]
    public function testGenDiffWithDifferentOutputFormats(
        OutputFormatEnum $format,
        string $expectedFile
    ): void {
        $file1 = $this->fixturesPath . '/file1_nested.json';
        $file2 = $this->fixturesPath . '/file2_nested.json';
        
        $expected = trim(file_get_contents($this->fixturesPath . '/' . $expectedFile));
        $actual = genDiff($file1, $file2, $format);

        $this->assertEquals($expected, trim($actual));
    }

    public static function outputFormatsProvider(): array
    {
        return [
            'stylish format' => [
                OutputFormatEnum::STYLISH,
                'expected_nested_stylish.txt'
            ],
            'plain format' => [
                OutputFormatEnum::PLAIN,
                'expected_nested_plain.txt'
            ],
            'json format' => [
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
    public function testGenDiffWithIdenticalFiles(string $extension): void
    {
        $file = $this->fixturesPath . '/file1.' . $extension;
        
        $expected = trim(file_get_contents($this->fixturesPath . '/expected_identical.txt'));
        $actual = genDiff($file, $file);

        $this->assertEquals($expected, trim($actual));
    }

    public static function identicalFilesProvider(): array
    {
        return [
            'flat json files' => ['json'],
            'flat yaml files' => ['yml']
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
