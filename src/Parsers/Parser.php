<?php

namespace Hexlet\Code\Parsers;

use Exception;

class Parser
{
    private ParserFactory $parserFactory;

    public function __construct(?ParserFactory $parserFactory = null)
    {
        $this->parserFactory = $parserFactory ?? new ParserFactory();
    }

    /**
     * @throws Exception
     */
    public function parseFile(string $pathToFile): array
    {
        $absolutePath = $this->getRealPath($pathToFile);

        if (!file_exists($absolutePath)) {
            throw new Exception("File not found: $pathToFile");
        }

        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        $content = file_get_contents($absolutePath);

        try {
            return $this->parserFactory->getParser($extension)->parse($content);
        } catch (Exception $e) {
            throw new Exception($e->getMessage() . " in file: $pathToFile");
        }
    }

    private function getRealPath(string $path): string
    {
        if ($path[0] === '/') {
            return $path;
        }

        $realPath = realpath($path);

        if ($realPath === false) {
            return getcwd() . '/' . $path;
        }

        return $realPath;
    }
}
