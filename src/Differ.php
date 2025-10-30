<?php

namespace Hexlet\Code;

use Exception;
use Hexlet\Code\Formatters\Enums\OutputFormatEnum;
use Hexlet\Code\Formatters\PlainFormatter;
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
        $tree = $this
            ->diffBuilder
            ->build($this->parser->parseFile($pathToFile1), $this->parser->parseFile($pathToFile2));

        return $this->format($tree, $format);
    }

    /**
     * @throws Exception
     */
    private function format(array $tree, OutputFormatEnum $format): string
    {
        return match ($format) {
            OutputFormatEnum::STYLISH => new StylishFormatter()->format($tree),
            OutputFormatEnum::PLAIN => new PlainFormatter()->format($tree),
        };
    }
}
