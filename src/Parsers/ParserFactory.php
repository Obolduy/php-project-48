<?php

namespace Hexlet\Code\Parsers;

use Exception;

class ParserFactory
{
    /**
     * @var array<string, ParserInterface>
     */
    private array $parsers = [];

    /**
     * @var array<string, class-string<ParserInterface>>
     */
    private array $parserMap;

    public function __construct()
    {
        $this->parserMap = [
            'json' => JsonParser::class,
            'yml' => YamlParser::class,
            'yaml' => YamlParser::class,
        ];
    }

    /**
     * @throws Exception
     */
    public function getParser(string $extension): ParserInterface
    {
        $normalizedExtension = strtolower($extension);

        if (!isset($this->parserMap[$normalizedExtension])) {
            throw new Exception("Unsupported file format: $extension");
        }

        if (!isset($this->parsers[$normalizedExtension])) {
            $this->parsers[$normalizedExtension] = new $this->parserMap[$normalizedExtension]();
        }

        return $this->parsers[$normalizedExtension];
    }

    /**
     * @param class-string<ParserInterface> $parserClass
     */
    public function registerParser(string $extension, string $parserClass): void
    {
        $normalizedExtension = strtolower($extension);

        $this->parserMap[$normalizedExtension] = $parserClass;

        unset($this->parsers[$normalizedExtension]);
    }
}
