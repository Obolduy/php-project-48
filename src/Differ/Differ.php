<?php

namespace Hexlet\Code\Differ;

use Exception;
use Hexlet\Code\Formatters\Enums\OutputFormatEnum;
use Hexlet\Code\Formatters\FormatterFactory;
use Hexlet\Code\Parsers\Common\Parser;
use Hexlet\Code\Parsers\Exceptions\AbstractParserException;
use Hexlet\Code\Parsers\Exceptions\ParserException;
use Hexlet\Code\Readers\Exceptions\ReaderException;
use Hexlet\Code\Readers\Reader;

class Differ
{
    private Parser $parser;
    private DiffBuilder $diffBuilder;
    private FormatterFactory $formatterFactory;

    private Reader $reader;

    public function __construct(
        Parser $parser,
        DiffBuilder $diffBuilder,
        FormatterFactory $formatterFactory,
        ?Reader $reader = null,
    ) {
        $this->parser = $parser;
        $this->diffBuilder = $diffBuilder;
        $this->formatterFactory = $formatterFactory;
        $this->reader = $reader ?? new Reader();
    }

    /**
     * @throws Exception
     */
    public function generate(
        string $pathToFile1,
        string $pathToFile2,
        OutputFormatEnum $format = OutputFormatEnum::STYLISH
    ): string {
        $absolutePath1 = $this->getAbsolutePath($pathToFile1);
        $this->checkFileExists($absolutePath1, $pathToFile1);

        $absolutePath2 = $this->getAbsolutePath($pathToFile2);
        $this->checkFileExists($absolutePath2, $pathToFile2);

        $data1 = $this->getParsedData($absolutePath1, $pathToFile1);
        $data2 = $this->getParsedData($absolutePath2, $pathToFile2);

        $tree = $this->diffBuilder->build($data1, $data2);

        $formatter = $this->formatterFactory->getFormatter($format);

        return $formatter->format($tree);
    }

    /**
     * @throws ParserException
     */
    private function checkFileExists(string $absolutePath, string $originalPath): void
    {
        if (!file_exists($absolutePath)) {
            throw new ParserException("File not found: $originalPath");
        }
    }

    private function getAbsolutePath(string $path): string
    {
        if ($path[0] === '/') {
            return $path;
        }

        $realPath = realpath($path);

        return $realPath === false ? getcwd() . '/' . $path : $realPath;
    }

    private function getFileExtension(string $path): string
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    /**
     * @throws AbstractParserException
     * @throws ParserException|ReaderException
     */
    private function getParsedData(string $absolutePath, string $pathToFile): array
    {
        $content = $this->reader->read($absolutePath, $pathToFile);
        $extension = $this->getFileExtension($absolutePath);

        return $this->parser->parse($content, $extension);
    }
}
