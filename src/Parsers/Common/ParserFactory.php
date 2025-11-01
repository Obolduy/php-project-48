<?php

namespace Hexlet\Code\Parsers\Common;

use Hexlet\Code\Parsers\Exceptions\ParserException;
use Hexlet\Code\Parsers\JsonParser;
use Hexlet\Code\Parsers\ParserInterface;
use Hexlet\Code\Parsers\YamlParser;

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
     * @throws ParserException
     */
    public function getParser(string $extension): ParserInterface
    {
        $normalizedExtension = strtolower($extension);

        if (!isset($this->parserMap[$normalizedExtension])) {
            throw new ParserException("Unsupported file format: $extension");
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
