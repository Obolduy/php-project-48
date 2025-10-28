<?php

namespace Hexlet\Code;

use Exception;
use Hexlet\Code\Formatters\Enums\OutputFormatEnum;
use Hexlet\Code\Formatters\StylishFormatter;

class Differ
{
    private Parser $parser;
    private DiffBuilder $diffBuilder;

    public function __construct(Parser $parser, DiffBuilder $diffBuilder)
    {
        $this->parser = $parser;
        $this->diffBuilder = $diffBuilder;
    }

    /**
     * @throws Exception
     */
    public function generate(
        string $pathToFile1,
        string $pathToFile2,
        OutputFormatEnum $format = OutputFormatEnum::STYLISH
    ): string {
        $data1 = $this->parser->parseFile($pathToFile1);
        $data2 = $this->parser->parseFile($pathToFile2);

        $tree = $this->diffBuilder->build($data1, $data2);

        return $this->format($tree, $format);
    }

    /**
     * @throws Exception
     */
    private function format(array $tree, OutputFormatEnum|string $format): string
    {
        $outputFormat = is_string($format) ? OutputFormatEnum::tryFrom($format) : $format;

        if ($outputFormat === null) {
            throw new Exception("Unsupported format: $format");
        }

        return match ($outputFormat) {
            OutputFormatEnum::STYLISH => new StylishFormatter()->format($tree),
        };
    }
}
