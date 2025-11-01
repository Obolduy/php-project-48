<?php

namespace Hexlet\Code\Parsers\Common;

use Hexlet\Code\Parsers\Exceptions\AbstractParserException;

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
    public function parse(string $content, string $extension): array
    {
        return $this->parserFactory->getParser($extension)->parse($content);
    }
}
