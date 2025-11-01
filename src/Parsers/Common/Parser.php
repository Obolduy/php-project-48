<?php

namespace Hexlet\Code\Parsers\Common;

use Hexlet\Code\Parsers\Exceptions\AbstractParserException;
use Hexlet\Code\Parsers\Exceptions\ParserException;

class Parser
{
    private ParserFactory $parserFactory;

    public function __construct(?ParserFactory $parserFactory = null)
    {
        $this->parserFactory = $parserFactory ?? new ParserFactory();
    }

    /**
     * @throws AbstractParserException
     */
    public function parseFile(string $pathToFile): array
    {
        $absolutePath = $this->getRealPath($pathToFile);

        if (!file_exists($absolutePath)) {
            throw new ParserException("File not found: $pathToFile");
        }

        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        $content = file_get_contents($absolutePath);

        if ($content === false) {
            throw new ParserException("Failed to read file: $pathToFile");
        }

        try {
            return $this->parserFactory->getParser($extension)->parse($content);
        } catch (ParserException $e) {
            throw new ParserException($e->getMessage() . " in file: $pathToFile");
        }
    }

    private function getRealPath(string $path): string
    {
        if ($path[0] === '/') {
            return $path;
        }

        $realPath = realpath($path);

        return $realPath === false ? getcwd() . '/' . $path : $realPath;
    }
}
